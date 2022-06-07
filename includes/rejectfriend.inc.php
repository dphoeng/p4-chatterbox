<?php

session_start();
require "../config/config.php";
require "./functions.inc.php";

if (!isset($_SESSION["id"]) || !isset($_GET["friend"])) {
	header("Location: ../index.php");
    exit();
}

$friend = $_GET["friend"];
$id = $_SESSION["id"];

$db = new Database();

// removed friend from friendlist
$sql = "UPDATE `users` SET `friends` = JSON_REMOVE(`friends`, SUBSTR(JSON_SEARCH(`friends`, 'one', '$friend', NULL, '$.friends[*].id'), 2, LOCATE('id', JSON_SEARCH(`friends`, 'one', '$friend', NULL, '$.friends[*].id')) - 3)) WHERE `usersId` = $id;";
$sql .= "UPDATE `users` SET `friends` = JSON_REMOVE(`friends`, SUBSTR(JSON_SEARCH(`friends`, 'one', '$id', NULL, '$.friends[*].id'), 2, LOCATE('id', JSON_SEARCH(`friends`, 'one', '$id', NULL, '$.friends[*].id')) - 3)) WHERE `usersId` = $friend;";

$db->query($sql);
if ($db->execute())
{
	// success
	if (isset($_GET['ret']))
    	header("Location: ../index.php?content={$_GET['ret']}");
    else
        header("Location: ../index.php?content=vrienden");
} else {
    // fail
    echo mysqli_error($conn);
}

?>