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
if (!isset($_POST['url']) || !isset($_POST['url'])) { return error('Malformed Request'); }
if ($_POST['url'] === '') { return error('Please enter a URL'); }
$title = $_POST['title'];
if ($title === '') { $title = 'My Alert'; }

function toInt($n) { return $n * 1; }
$contactIds = array_map('toInt', json_decode($_POST['contactIds']));

$insertAlert = $conn->prepare("INSERT INTO alerts (url, title, user_id) VALUES (?, ?, ?)");
$insertAlert->bind_param("ssi", $_POST['url'], $title, $_SESSION['user_id']);
$insertAlert->execute();

$alertRow = $insertAlert->insert_id;

$contactMethodsQuery = '';

foreach ($contactIds as $index => $contactId) {
  $contactMethodsQuery .= "INSERT INTO alert_contact_join (contact_id, alert_id) VALUES ($contactId, $alertRow); ";
}

if ($conn->multi_query($contactMethodsQuery) === TRUE) {
  echo "New records created successfully";
} else {
  error('Error creating record, try again later.', 500);
}
return;

/*

$insertAlert = $conn->prepare("INSERT INTO contact_info_classifieds (user_id, contact_type, contact_details) VALUES (?, ?, ?)");
$insertURL->bind_param("iss", $_SESSION['user_id'], $_POST['type'], $_POST['value']);
$insertURL->execute();

if ($insertURL->error) { return error('Error creating contact detail', 500); }

echo 'Contact detail inserted';
*/
?>