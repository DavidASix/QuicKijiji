<?php
include_once '../../config.php';
global $conn;
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function error($msg, $code = 400) {
  header('Error: ' . $msg, false, $code);
  echo $msg;
  return;
}

// If IP has attempted to log in over 5 times make them wait 5 min
$checkIP = $conn->query("SELECT * FROM time_restricted_ips
  WHERE ip = '$_SERVER[REMOTE_ADDR]'
  AND used_at > NOW() - INTERVAL 5 MINUTE
  AND action='createaccount'");
if ($checkIP->num_rows > 5) { return error('You\'re doing that too much. Please try again in 5 minutes.', 403); };

// Prepare and send the email
if (!isset($_POST['username']) || $_POST['username'] === "") { return error('Please enter your username.'); }
if (!isset($_POST['password']) || $_POST['password'] === "") { return error('Please enter your password.'); }

$usernameInUse = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$usernameInUse->bind_param("s", $_POST['username']);
$usernameInUse->execute();
$usernameInUse = $usernameInUse->get_result();

if ($usernameInUse->num_rows !== 0) { return error('Username is taken', 400); }

// Enter the ip into the time restrict. Done before account creation to ensure time restriction isn't effected by failed MYSQL inserts.
$insertIP = $conn->prepare("INSERT INTO time_restricted_ips (ip, action) VALUES (?, 'createaccount')");
$insertIP->bind_param("s", $_SERVER['REMOTE_ADDR']);
$insertIP->execute();

// Create hash and password
$salt = bin2hex(random_bytes(32));
$passHash = hash_hmac('sha512', $_POST['password'], $salt);

// Store new user account
$userInsert = $conn->prepare("INSERT INTO users (username, password, salt) VALUES (?, ?, ?)");
if ($userInsert===false) { return error('Server error during account creation', 500); }
$userInsert->bind_param("sss", $_POST['username'], $passHash, $salt);
if ($userInsert===false) { return error('Server error during account creation', 500); }
$userInsert->execute();
if ($userInsert===false) { return error('Server error during account creation', 500); }

echo $userInsert->insert_id;

?>