<?php

session_start();
require_once "../config/config.php";
require_once "./functions.inc.php";

if (!isset($_SESSION["id"]) || !isset($_GET['krabbelId']) || !isset($_GET['profile'])) {
	header("Location: ../index.php");
	exit();
}

$krabbelId = $_GET['krabbelId'];
$id = $_SESSION['id'];
$profile = $_GET['profile'];

$db = new Database();
$db->query("SELECT `posterId` FROM `krabbels` WHERE `krabbelId` = $krabbelId");
$result = $db->single();
if (!$result)
	header("Location: ../index.php");
else {
	if ($result->posterId == $id) {
		$db->query("DELETE FROM `krabbels` WHERE `krabbelId` = $krabbelId;");
		$db->execute();
		header("Location: ../index.php?content=profiel/$profile");
		// not same as poster
	} else
		header("Location: ../index.php");
}
