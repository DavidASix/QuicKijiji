<?php
include_once '/var/www/redOxford/config.php';
global $conn;

function error($msg, $code = 400) {
  header('Error: ' . $msg, false, $code);
  echo $msg;
  return;
}

if (!empty($_COOKIE['token'])) {
  $getToken = $conn->prepare("SELECT * FROM auth_tokens WHERE token = ? LIMIT 1");
  $getToken->bind_param("s", $_COOKIE['token']);
  $getToken->execute();
  $token = $getToken->get_result();
  $token = $token->fetch_assoc();

  if ($token['valid']) {


    $user = $conn->query("SELECT * FROM users WHERE id = $token[user_id] LIMIT 1");
    $user = $user->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Set cookies with username and password
    $new_token = bin2hex(random_bytes(32));
    setcookie('token', $new_token, strtotime("+ 1 year"), "/", "dave6.com", false, true);
    $conn->query("UPDATE auth_tokens SET valid = 0 WHERE token = '$_COOKIE[token]'");
    $conn->query("INSERT INTO auth_tokens (user_id, token, service) VALUES ('$user[id]', '$new_token', 'classifieds')");

  }
}



?>