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

// Accept friend request, sets accepted to true and date to current date
// SET function finds the path to the correct id (this can also be done in a separate SQL statement, idk yet tho), this is later used in the UPDATE to set correct data in the correct friend

// SET @idPath = (SELECT SUBSTR(JSON_SEARCH(friends, 'one', '1', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '1', NULL, '$[*].id')) - 2) FROM users WHERE usersId = 3);
// UPDATE users SET friends=JSON_SET(friends, CONCAT(@idPath, '.accepted'), true, CONCAT(@idPath, '.date'), CURDATE()) WHERE usersId = 3;

$sqlNew = "SET @idPath = (SELECT SUBSTR(JSON_SEARCH(friends, 'one', '$friend', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '$friend', NULL, '$[*].id')) - 2) FROM users WHERE usersId = $id);
        UPDATE users SET friends=JSON_SET(friends, CONCAT(@idPath, '.request_type'), true, CONCAT(@idPath, '.date'), CURDATE()) WHERE usersId = $id;";
$sqlNew .= "SET @idPathOther = (SELECT SUBSTR(JSON_SEARCH(friends, 'one', '$id', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '$id', NULL, '$[*].id')) - 2) FROM users WHERE usersId = $friend);
        UPDATE users SET friends=JSON_SET(friends, CONCAT(@idPathOther, '.request_type'), true, CONCAT(@idPath, '.date'), CURDATE()) WHERE usersId = $friend;";

if (mysqli_multi_query($conn, $sqlNew))
{
    // success
    header("Location: ../index.php?content=friendmessagestemp");

} else {
    // fail
    echo mysqli_error($conn);
}

?>