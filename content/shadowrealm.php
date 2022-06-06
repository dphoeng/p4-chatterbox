<?php

session_start();
require '../config/config.php';
require_once("../includes/functions.inc.php");

$db = new Database();
$db->query("SELECT * FROM `moderation` WHERE `usersId` = {$_SESSION['id']} AND `endDate` > CURRENT_TIMESTAMP() AND `modOption` = 'ban' ORDER BY `endDate` DESC");
$resultBan = $db->single();

$banished = $resultBan->endDate;

?>

You have been banished to the shadow realm until: <?= $banished; ?>