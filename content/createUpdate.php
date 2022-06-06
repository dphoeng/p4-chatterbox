  <?php

  if (!isset($_SESSION["id"]) || !isset($_GET["profiel"])) {
    header("Location: ../index.php");
    exit();
  }

  $id = $_SESSION["id"];
  $profile = $_GET["profiel"];

  if (isset($_GET['krabbelId'])) {
    if ($_GET['krabbelId'] == !$id) {
      header("Location: ../index.php");
      exit();
    } else {
      $krabbel = new Krabbels();
      $krabbel = $krabbel->read($_GET['krabbelId']);
    }
  }

  ?>
  <form action="./includes/uploadkrabbel.inc.php?profiel=<?php echo $profile;
                                                          if ($attached) echo "&attached={$attached}" ?>" class="post side-main-content" method="post" enctype="multipart/form-data" id="form">
    <header>
      <img class="icon-rounded medium" src="<?= $currentUser->avatar ?>" alt="profile img">
      <div>
        <h4>
          <?= $currentUser->nickname ?>
        </h4>
        <h5>
          <?= date('d-m-Y H:i:s') ?>
        </h5>
      </div>
    </header>
    <main>
      <textarea name="message" form="form" placeholder="What's on your mind?"></textarea>
      <?php
      if (isset($_GET['krabbelId'])) {
        // var_dump($krabbel);
        echo "<div class='img-holder'><img src='{$krabbel->image}'' alt='profile img'></div>";
      } else {
        echo '<div class="img-holder"><img id="file-ip-1-preview"></div>';
      }
      ?>
      <input class="mt-auto" type="file" id="krabbel" name="krabbel" accept="image/*" onchange="showPreview(event);">
      <input type="submit" name="submit">
    </main>
  </form>