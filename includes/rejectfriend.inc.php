<?php

session_start();
require("./connect.php");

if (!isset($_SESSION["id"]) || !isset($_GET["friend"])) {
	header("Location: ../index.php");
    exit();
}

$friend = $_GET["friend"];
$id = $_SESSION["id"];

$sql = "UPDATE users SET friends=JSON_REMOVE(friends, SUBSTR(JSON_SEARCH(friends, 'one', '$friend', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '$friend', NULL, '$[*].id')) - 2)) WHERE usersId = $id;";
$sql .= "UPDATE users SET friends=JSON_REMOVE(friends, SUBSTR(JSON_SEARCH(friends, 'one', '$id', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '$id', NULL, '$[*].id')) - 2)) WHERE usersId = $friend;";

if (mysqli_multi_query($conn, $sql))
{
    // success
    header("Location: ../index.php?content=friendmessagestemp");

} else {
    // fail
    echo mysqli_error($conn);
}

?>