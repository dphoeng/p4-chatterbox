<?php

session_start();
ob_start();
require("./connect.php");
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

// Example JSON format
// [{
//     "id":"1",
//     "date":"2022-02-22",
//     "accepted":true
// },{
//     "id":"2",
//     "date":null,
//     "accepted":false
// }]

$sql = "SELECT friends FROM users WHERE usersId = {$_GET['profile']}";
$result = mysqli_query($conn, $sql);
if (!mysqli_num_rows($result))
{
    // user doesn't exist
} else {
    $record = mysqli_fetch_assoc($result);
}

$decodedJSON = json_decode($record['friends']);

$newFriend = new Friend($id, false, null);
$encodedFriend = json_encode($newFriend);

// turn each object into a friend object and place into array
if (isset($decodedJSON))
{
    foreach($decodedJSON as $friend)
    {
        // if the user you're trying to befriend has already received a request
        // TODO: check whether this friend has already send YOU a request
        if (intval($friend->id) == $id)
        {
            header("Location: ../index.php?content=profiel/{$profile}");
            exit;
        }
    }
    $sql = "UPDATE users SET friends=JSON_ARRAY_APPEND(friends, '$', CAST('{$encodedFriend}' AS JSON)) WHERE usersId=$profile";
} else {
    // create new JSON_ARRAY if there is non yet (basically when field is empty)
    $sql = "UPDATE users SET friends=JSON_ARRAY_APPEND(JSON_ARRAY(), '$', CAST('{$encodedFriend}' AS JSON)) WHERE usersId=$profile";
}

// shows all friends
// SELECT JSON_PRETTY(friends) FROM `users`

// no stmt because it was jank idk?, anyways there is no user input so no sanitation is needed anyways I think
if(mysqli_query($conn, $sql))
{
    header("Location: ../index.php?content=profiel/$profile");
} else {
    // failed
    // echo mysqli_error($conn);
}

// remove friend
// UPDATE users SET friends=JSON_REMOVE(friends, SUBSTR(JSON_SEARCH(friends, 'one', \'$id\', NULL, '$[*].id'), 2, LOCATE('.', JSON_SEARCH(friends, 'one', '1', NULL, '$[*].id')) - 2)) WHERE usersId = 3;
                                            //   LEFT(JSON_UNQUOTE(JSON_SEARCH(friends, 'one', '1', NULL, '$[*].id')), LOCATE('.', JSON_UNQUOTE(JSON_SEARCH(friends, 'one', '1', NULL, '$[*].id'))) - 1)

?>