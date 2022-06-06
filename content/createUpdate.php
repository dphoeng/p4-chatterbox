  <?php

  if (!isset($_SESSION["id"]) || !isset($_GET["profiel"])) {
    header("Location: ../index.php");
    exit();
  }

  $id = $_SESSION["id"];
  $profile = $_GET["profiel"];

// for future use to place comments on comments
$attached = isset($_GET["attached"]) ? $_GET["attached"] : null;

  $sql = "SELECT nickname,avatar FROM users WHERE usersId = " . $id;
  $result = mysqli_query($conn, $sql);
  $record = mysqli_fetch_assoc($result);
  if (isset($_GET['krabbelId'])) {
    $sqlImg = "SELECT image FROM krabbels WHERE krabbelId = " . $_GET['krabbelId'];
    $resultimg = mysqli_query($conn, $sqlImg);
    $recordimg = mysqli_fetch_assoc($resultimg);
  }
  ?>
  <form action="./includes/uploadkrabbel.inc.php?profiel=<?php echo $profile; if ($attached) echo "&attached={$attached}" ?>" class="post side-main-content" method="post" enctype="multipart/form-data" id="form">
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
      <textarea name="message" form="form" placeholder="What's on your mind?"></textarea>
      <?php
      if (isset($_GET['krabbelId'])) {
        // var_dump($recordimg);
        echo '<div class="img-holder"><img class="icon-rounded medium" src="' . $recordimg['image'] . '" alt="profile img"></div>';
      } else {
        echo '<div class="img-holder"><img id="file-ip-1-preview"></div>';
      }
      ?>
      <input class="mt-auto" type="file" id="krabbel" name="krabbel" accept="image/*" onchange="showPreview(event);">
      <input type="submit" name="submit">
    </main>
  </form>