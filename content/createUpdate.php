<?php

if (!isset($_SESSION["id"]) || !isset($_GET["profiel"])) {
  header("Location: ../index.php");
  exit();
}

$id = $_SESSION["id"];
$profile = $_GET["profiel"];


$db->query("SELECT * FROM `moderation` WHERE `usersId` = {$_SESSION['id']} AND `endDate` > CURRENT_TIMESTAMP() ORDER BY `endDate` DESC");
$result = $db->single();

// user is banned or timed out
if ($result) {
  header("Location: ./index.php?error={$result->modOption}");
}

// krabbelId is given when editing krabbel, NOT when new post is being created
if (isset($_GET['krabbelId'])) {
  $krabbel = new Krabbels();
  $krabbel = $krabbel->read($_GET['krabbelId']);
  // // krabbel does not exist
  // if (!$krabbel)
  //   header("Location: ../index.php");
  // else {
  //   // logged in user is not same as original poster
  //   if ($krabbel->posterId !== $id) {
  //     header("Location: ../index.php");
  //   }
  // }
}


if (isset($_GET['attached']))
  $attached = $_GET['attached'];
else
  $attached = NULL;

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
    <textarea name="message" form="form" placeholder="What's on your mind?"><?php if (isset($_GET['krabbelId'])) {
                                                                              echo "{$krabbel->text}";
                                                                            } ?></textarea>
    <?php
    if (isset($_GET['krabbelId'])) {
      if ($krabbel->image)
        // var_dump($krabbel);
        echo "<div class='img-holder'><img src='{$krabbel->image}' alt='profile img'></div>";
      else
        echo '<div class="img-holder"><img id="file-ip-1-preview"></div>';
    } else {
      echo '<div class="img-holder"><img id="file-ip-1-preview"></div>';
    }
    ?>
    <input class="mt-auto" type="file" id="krabbel" name="krabbel" accept="image/*" onchange="showPreview(event);">
    <input type="submit" name="submit">
    <?php
    if (isset($_GET['krabbelId'])) {
      echo "<input type='hidden' name='krabbelId' value='{$_GET['krabbelId']}'>
      <input type='submit' name='delete' value='delete'>
      <input type='submit' name='update' value='update'>";
    }
    ?>
  </main>
</form>