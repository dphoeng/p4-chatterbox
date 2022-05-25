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

$sql = "SELECT friends FROM users WHERE usersId = {$friend}";
$result = mysqli_query($conn, $sql);
if (!mysqli_num_rows($result))
{
    // user doesn't exist
} else {
    $record = mysqli_fetch_assoc($result);
}

$decodedJSON = json_decode($record['friends']);

$newFriend = new Friend($id, true, date("Y-m-d"));
$encodedFriend = json_encode($newFriend);

// turn each object into a friend object and place into array
if (isset($decodedJSON))
{
    $sql = "UPDATE users SET friends=JSON_ARRAY_APPEND(friends, '$', CAST('{$encodedFriend}' AS JSON)) WHERE usersId=$friend";
} else {
    // create new JSON_ARRAY if there is non yet (basically when field is empty)
    $sql = "UPDATE users SET friends=JSON_ARRAY_APPEND(JSON_ARRAY(), '$', CAST('{$encodedFriend}' AS JSON)) WHERE usersId=$friend";
}

$sqlNew = "SET @idPath = (SELECT SUBSTR(JSON_SEARCH(friends, 'one', '$friend', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '$friend', NULL, '$[*].id')) - 2) FROM users WHERE usersId = $id);
        UPDATE users SET friends=JSON_SET(friends, CONCAT(@idPath, '.accepted'), true, CONCAT(@idPath, '.date'), CURDATE()) WHERE usersId = $id;";
$sqlNew .= $sql;

if (mysqli_multi_query($conn, $sqlNew))
{
    // success
    header("Location: ../index.php?content=friendmessagestemp");

} else {
    // fail
    echo mysqli_error($conn);
}

?>