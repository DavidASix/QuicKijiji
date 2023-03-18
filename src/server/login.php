<?php
include_once '../../config.php';
global $conn;

function error($msg, $code = 400) {
  header('Error: ' . $msg, false, $code);
  echo $msg;
  return;
}

// If IP has attempted to log in over 5 times make them wait 5 min
$checkIP = $conn->query("SELECT * FROM time_restricted_ips
  WHERE ip = '$_SERVER[REMOTE_ADDR]'
  AND used_at > NOW() - INTERVAL 5 MINUTE
  AND action='loginattempt'");
if ($checkIP->num_rows > 99) { return error('You\'re doing that too much. Please try again in 5 minutes.', 403); };

// Prepare and send the email
if (!isset($_POST['username']) || $_POST['username'] === "") { return error('Please enter your username.'); }
if (!isset($_POST['password']) || $_POST['password'] === "") { return error('Please enter your password.'); }

$getUser = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$getUser->bind_param("s", $_POST['username']);
$getUser->execute();
$user = $getUser->get_result();

if ($user->num_rows === 0) { return error('Username not found, sorry!', 401); }

// Uses ths same hashing method as my node server
// Hash user provided password with salt and check it against the stored pass
$user = $user->fetch_assoc();
$passHash = hash_hmac('sha512', $_POST['password'], $user['salt']);
if ($passHash !== $user['password']) { return error('Username or password incorrect.', 401); }

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

if (isset($_POST['remember-me']) && $_POST['remember-me'] === 'true') {
  // Set cookies with username and password
  $auth_token = bin2hex(random_bytes(32));
  setcookie('token', $auth_token, strtotime("+ 1 year"), "/", "dave6.com", false, true);
  $insertToken = $conn->query("INSERT INTO auth_tokens (user_id, token, service) VALUES ('$user[id]', '$auth_token', 'classifieds')");

}

echo $user['id'];


// Enter the ip into the time restrict
$insertIP = $conn->prepare("INSERT INTO time_restricted_ips (ip, action) VALUES (?, 'loginattempt')");
// first argument means string // https://www.php.net/manual/en/mysqli-stmt.bind-param
$insertIP->bind_param("s", $_SERVER['REMOTE_ADDR']);
$insertIP->execute();

?>