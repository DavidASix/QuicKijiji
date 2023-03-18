<?php
  include '../../config.php';
  session_destroy();

  $killToken = $conn->prepare("UPDATE auth_tokens SET valid = 0 WHERE token = ?");
  $killToken->bind_param("s", $_COOKIE['token']);
  $killToken->execute();

  setcookie("token", "", time() - 3600);
  header('Location: ' . baseUrl . '/classifieds');

?>