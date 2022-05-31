<?php
if (!isset($_SESSION["id"])) {
  include("./content/login.php");
  exit;
}
if (isset($_GET["content"])) {
  $url = explode("/", $_GET['content']);
  if (file_exists("./content/" . $url[0]) . ".php") {
    include("./content/" . $url[0] . ".php");
  }
} else {
  include("./content/home.php");
}
