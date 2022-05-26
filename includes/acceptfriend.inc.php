<?php

session_start();
require("./connect.php");
include("./Friend.php");

if (!isset($_SESSION["id"]) || !isset($_GET["friend"])) {
	header("Location: ../index.php");
    exit();
}

$friend = $_GET["friend"];
$id = $_SESSION["id"];

// Accept friend request, sets accepted to true, date to current date and increments friend_count by 1
// SET function finds the path to the correct id (this can also be done in a separate SQL statement, idk yet tho), this is later used in the UPDATE to set correct data in the correct friend

$sqlNew = "SET @idPath = (SELECT SUBSTR(JSON_SEARCH(friends, 'one', '$friend', NULL, '$.friends[*].id'), 2, LOCATE('id', JSON_SEARCH(friends, 'one', '$friend', NULL, '$.friends[*].id')) - 3) FROM `users` WHERE `usersId` = $id);
        UPDATE `users` SET friends=JSON_SET(friends, CONCAT(@idPath, '.request_type'), true, CONCAT(@idPath, '.date'), CURDATE(), '$.friend_count', JSON_EXTRACT(friends, '$.friend_count') + 1) WHERE `usersId` = $id;";
$sqlNew .= "SET @idPathOther = (SELECT SUBSTR(JSON_SEARCH(friends, 'one', '$id', NULL, '$.friends[*].id'), 2, LOCATE('id', JSON_SEARCH(friends, 'one', '$id', NULL, '$.friends[*].id')) - 3) FROM `users` WHERE `usersId` = $friend);
        UPDATE `users` SET friends=JSON_SET(friends, CONCAT(@idPathOther, '.request_type'), true, CONCAT(@idPath, '.date'), CURDATE(), '$.friend_count', JSON_EXTRACT(friends, '$.friend_count') + 1) WHERE `usersId` = $friend;";

if (mysqli_multi_query($conn, $sqlNew))
{
    // success
    header("Location: ../index.php?content=friendmessagestemp");

} else {
    // fail
    echo mysqli_error($conn);
}
?>