<?php

session_start();
ob_start();
require("./connect.php");
require("./functions.inc.php");
include("./Friend.php");

// check whether user is logged in
if (!isset($_SESSION["id"]) || !isset($_GET["profile"]))
{
	header("Location: ../index.php");
    exit;
} else if (strlen($_GET["profile"]) < 1) {
	header("Location: ../index.php");
    exit;
}

$id = $_SESSION["id"];
$profile = $_GET["profile"];

// if user is yourself
if ($id == $profile)
{
	header("Location: ../index.php?content=profiel/{$profile}");
    exit;
}

// friend class which will be converted into JSON format and put into the row of the other person
$newFriend = new Friend($id, "requested", null);
$encodedNewFriend = json_encode($newFriend);

// friend class which will be converted into JSON format and put into the row of yourself
$selfFriend = new Friend($profile, "requester", null);
$encodedSelfFriend = json_encode($selfFriend);

$result = checkIfEmpty($conn, $id, $profile, $encodedNewFriend);
if (!$result)
{
    header("Location: ../index.php?content=profiel/{$profile}");
}
$result2 = checkIfEmpty($conn, $profile, $id, $encodedSelfFriend);
if (!$result2)
{
    header("Location: ../index.php?content=profiel/{$profile}");
}

echo $result . "<br>" . $result2 . "<br>";

// no stmt because it was jank idk?, anyways there is no user input so no sanitation is needed anyways I think
if(mysqli_multi_query($conn, $result . $result2))
{
    header("Location: ../index.php?content=profiel/$profile");
} else {
    // failed
    echo mysqli_error($conn);
    header("Location: ../index.php?content=profiel/$profile");
}

?>