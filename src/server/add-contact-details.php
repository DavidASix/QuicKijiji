<?php
include_once '/var/www/redOxford/config.php';
global $conn;

function error($msg, $code = 400) {
  header('Error: ' . $msg, false, $code);
  echo $msg;
  return;
}

if (!isset($_SESSION['user_id'])) { return error('Please log in', 401); }
// Check user input is not malformed
if (!isset($_POST['type'])) { return error('Malformed Request'); }
if ($_POST['type'] !== 'email' && $_POST['type'] !== 'phone') { return error('Malformed Request'); }
if (!isset($_POST['value']) || $_POST['value'] === "") { return error('Please enter your ' . $_POST['type'] . '.'); }


$insertContactDetails = $conn->prepare("INSERT INTO contact_info_classifieds (user_id, contact_type, contact_details) VALUES (?, ?, ?)");
$insertContactDetails->bind_param("iss", $_SESSION['user_id'], $_POST['type'], $_POST['value']);
$insertContactDetails->execute();

if ($insertContactDetails->error) { return error('Error creating contact detail', 500); }

echo 'Contact detail inserted';

?>