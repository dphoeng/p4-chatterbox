<?php

if (!isset($_SESSION["id"])) {
  header("Location: ../index.php");
  exit();
}

$krabbels = new Krabbels();
$krabbels = $krabbels->getPosts("all", false);
// foreach ($krabbels as $krabbel)
// {
//   echo getPost($krabbel);
// }
?>
<!-- <button class="icon-rounded medium button ml-auto">
  <svg width="18" height="5" viewBox="0 0 18 5" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M2.25 0.421814C1.0125 0.421814 0 1.43431 0 2.67181C0 3.90931 1.0125 4.92181 2.25 4.92181C3.4875 4.92181 4.5 3.90931 4.5 2.67181C4.5 1.43431 3.4875 0.421814 2.25 0.421814ZM15.75 0.421814C14.5125 0.421814 13.5 1.43431 13.5 2.67181C13.5 3.90931 14.5125 4.92181 15.75 4.92181C16.9875 4.92181 18 3.90931 18 2.67181C18 1.43431 16.9875 0.421814 15.75 0.421814ZM9 0.421814C7.7625 0.421814 6.75 1.43431 6.75 2.67181C6.75 3.90931 7.7625 4.92181 9 4.92181C10.2375 4.92181 11.25 3.90931 11.25 2.67181C11.25 1.43431 10.2375 0.421814 9 0.421814Z" fill="white" />
  </svg>
</button> -->
<div class="posts side-main-content showScroll">
  <?= $krabbels ?>
</div>