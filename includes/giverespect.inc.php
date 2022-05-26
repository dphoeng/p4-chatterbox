<?php

session_start();
require("./connect.php");

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

$sql = "SELECT `last_res_given` FROM `users` WHERE `usersId` = $id;";
$result = mysqli_query($conn, $sql);
$record = mysqli_fetch_assoc($result);

// if last_res_given field is not empty
if ($record["last_res_given"])
{
    // checks whether the difference between last_res_given and right now is less than 1 day or not
    $lastTime = new DateTime($record["last_res_given"]);
    $diff = $lastTime->diff(new DateTime(date('Y-m-d H:i:s')));
    if ($diff->d < 1)
    {
        header("Location: ../index.php?content=profiel/{$profile}&error=tooSoon");
        exit;
    }
}

$sql = "UPDATE `users` SET `respect` = `respect` + 1 WHERE `usersId` = $profile;";
$sql .= "UPDATE `users` SET `last_res_given` = NOW() WHERE `usersId` = $id;";
if (mysqli_multi_query($conn, $sql))
{
    header("Location: ../index.php?content=profiel/$profile");
} else {
    echo mysqli_error($conn);
}

?>