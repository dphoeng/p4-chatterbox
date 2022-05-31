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
    if (isset($_GET['ret']))
        header("Location: ../index.php?content={$_GET['ret']}");
    else
        header("Location: ../index.php?content=vrienden");

} else {
    // fail
    echo mysqli_error($conn);
}

?>