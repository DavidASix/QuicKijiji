<?php
include_once '../config.php';
if(empty($_SESSION['user_id'])) {
  header('Location: ' . baseUrl . '/classifieds');
}
?>