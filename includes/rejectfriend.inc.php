<?php

session_start();
require("./connect.php");

if (!isset($_SESSION["id"]) || !isset($_GET["friend"])) {
	header("Location: ../index.php");
    exit();
}

$friend = $_GET["friend"];
$id = $_SESSION["id"];

// removed friend from friendlist
$sql = "UPDATE `users` SET `friends` = JSON_REMOVE(`friends`, SUBSTR(JSON_SEARCH(`friends`, 'one', '$friend', NULL, '$.friends[*].id'), 2, LOCATE('id', JSON_SEARCH(`friends`, 'one', '$friend', NULL, '$.friends[*].id')) - 3)) WHERE `usersId` = $id;";
$sql .= "UPDATE `users` SET `friends` = JSON_REMOVE(`friends`, SUBSTR(JSON_SEARCH(`friends`, 'one', '$id', NULL, '$.friends[*].id'), 2, LOCATE('id', JSON_SEARCH(`friends`, 'one', '$id', NULL, '$.friends[*].id')) - 3)) WHERE `usersId` = $friend;";

if (mysqli_multi_query($conn, $sql))
{
    // success
    header("Location: ../index.php?content=friendmessagestemp");

} else {
    // fail
    echo mysqli_error($conn);
}

?>