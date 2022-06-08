<?php

if (!isset($_SESSION["id"]) || !isset($currentUser)) {
  header("Location: ../index.php");
  exit();
}

$ids = $_SESSION["id"];
$decoded = json_decode($currentUser->friends);
foreach ($decoded->friends as $friend) {
  // var_dump($friend);
  if ($friend->request_type === 'friends')
    $ids .= ", " . $friend->id;
}

$krabbels = new Krabbels();
$krabbels = $krabbels->getPosts($ids, true);
// foreach ($krabbels as $krabbel)
// {
//   getPost($krabbel);
// }
// foreach ($krabbels as $krabbel)
// {
//   echo getPost($conn, $krabbel);
// }

?>

<div class="posts side-main-content showScroll">
  <?= $krabbels ?>
</div>