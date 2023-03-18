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
if (!isset($_POST['alertId'])) { return error('Malformed Request'); }
if (!is_numeric($_POST['alertId'])) { return error('Please enter a URL'); }

$deleteAlert = $conn->prepare("DELETE FROM alerts WHERE user_id = ? AND id = ?");
$deleteAlert->bind_param("ii", $_SESSION['user_id'], $_POST['alertId']);
$deleteAlert->execute();

echo 'Successfully Deleted';

return;

/*

$insertAlert = $conn->prepare("INSERT INTO contact_info_classifieds (user_id, contact_type, contact_details) VALUES (?, ?, ?)");
$insertURL->bind_param("iss", $_SESSION['user_id'], $_POST['type'], $_POST['value']);
$insertURL->execute();

if ($insertURL->error) { return error('Error creating contact detail', 500); }

echo 'Contact detail inserted';
*/
?>