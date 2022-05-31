<?php

session_start();
ob_start();
require('./includes/connect.php');

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
  <dialog data-popup="create-post">
    <?php
    $sql = "SELECT nickname,avatar FROM users WHERE usersId = " . $_SESSION['id'];
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      // error page if user does not exist
    }
    $record = mysqli_fetch_assoc($result);
    ?>
    <form class="post" data-post-id="1">
      <header>
        <img class="icon-rounded medium" src="<?php echo $record['avatar'] ?>" alt="profile img">
        <div>
          <h4>
            <?php echo $record['nickname'] ?>
          </h4>
          <h5>
            <?php echo date('d-m-Y H:i:s') ?>
          </h5>
        </div>
      </header>
      <main>
        <textarea name="post-content" placeholder="What's on your mind?"></textarea>
        <div>
          <img class="full-height" src="./img/2.png" alt="post img">
          <!-- upload foto -->
        </div>
        <input type="file" id="upload" name="upload" accept="image/*">
        <button type="submit">
          post
        </button>
      </main>
    </form>
  </dialog>

  <?php if (isset($_SESSION["id"])) {
    include('./content/navbar.php');
  } ?>

  <div class="main-content">
    <?php include('./components/content.php'); ?>
  </div>
</body>
<script src="./src/js/include.js"></script>

</html>