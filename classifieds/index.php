
<?php
include_once '/var/www/redOxford/config.php'; 

include rootPath . '/src/server/check-auth-token.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <?php include_once rootPath.'/src/includes/site-head.php'; ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="/classifieds/classifieds.css" rel="stylesheet" type="text/css">
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>

<body class="bg-light">
  <?php
    include rootPath . '/src/components/navigation-menu.php';
    if(empty($_SESSION['user_id'])) {
      include './login_html.php';
    } else {
      include './manage_requests_html.php';
    }
     include rootPath.'/src/components/site-footer.php'
  ?>
</body>

<script>
// ToolTip Bootstrap function

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => (new bootstrap.Tooltip(tooltipTriggerEl)));
</script>

</html>
