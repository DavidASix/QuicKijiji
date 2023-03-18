<?php
include_once '/var/www/redOxford/config.php';
global $conn;

function error($msg, $code = 400) {
  header('Error: ' . $msg, false, $code);
  echo $msg;
  return;
}

$getDetails = $conn->query(
  "SELECT
    max(id) AS id,
    max(contact_type) AS contact_type,
    contact_details
  FROM contact_info_classifieds
  WHERE user_id = $_SESSION[user_id]
  GROUP BY contact_details");

if ($getDetails===false) { return error('Error creating contact detail', 500); }
while ($contactDetails[] = $getDetails->fetch_assoc());
array_pop($contactDetails);
$contactDetails = json_encode($contactDetails);
echo $contactDetails;

?>