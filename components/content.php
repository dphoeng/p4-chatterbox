<?php
if (isset($_GET["content"])) {
  include("./" . $_GET["content"] . ".php");
} else {
  var_dump($_SESSION);
  if (!isset($_SESSION["id"])) {
    include("./components/login.php");
  } else {
    $_GET["content"] = './content/home.php';
    include("./content/home.php");
  }
}
