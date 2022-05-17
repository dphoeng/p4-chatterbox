<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/open-props" />
  <link rel="stylesheet" href="https://unpkg.com/open-props/normalize.min.css" />
  <link rel="stylesheet" href="./src/style/css/style.css">
  <link rel="shortcut icon" href="./src/img/Logo.png" type="image/x-icon">

  <title>Chatter box</title>

  <script defer src="./src/js/app.js"></script>
</head>

<body>

  <?php
  if (isset($_SESSION["id"])) {
    include('./content/navbar.php');
  }
  ?>

  <?php include('./components/content.php'); ?>

</body>

</html>