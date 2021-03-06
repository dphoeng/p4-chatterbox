<?php

session_start();
ob_start();
require './includes/connect.php';
require './includes/functions.inc.php';
require './config/config.php';

if (isset($_SESSION['id'])) {
  $currentUser = new Users();
  $currentUser = $currentUser->read($_SESSION['id']);

  $db = new Database();
  $db->query("SELECT * FROM `moderation` WHERE `usersId` = {$_SESSION['id']} AND `endDate` > CURRENT_TIMESTAMP() AND `modOption` = 'ban' ORDER BY `endDate` DESC");
  $resultBan = $db->single();

  // user is banned or timed out
  if ($resultBan) {
    header("Location: ./content/shadowrealm.php");
  }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./src/style/css/style.css">
  <link rel="shortcut icon" href="./src/img/Logo.png" type="image/x-icon">

  <title>Chatter box</title>

  <script defer src="./src/js/app.js"></script>
</head>

<body class="default-grid">
  <?php if (isset($_SESSION["id"])) {
    include('./content/navbar.php');
  } ?>

  <div class="main-content">
    <?php include('./components/content.php'); ?>
  </div>
</body>
<script src="./src/js/include.js"></script>

</html>