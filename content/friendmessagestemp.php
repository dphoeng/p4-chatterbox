<?php

include("./includes/Friend.php");

if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT friends FROM users WHERE usersId = {$_SESSION['id']} AND friends IS NOT NULL";
$result = mysqli_query($conn, $sql);
$friendRows = "";
if (mysqli_num_rows($result) < 1)
{
    // no friends, no array
} else {
    $return = $result->fetch_object();
    $decoded = json_decode($return->friends);
    if (count($decoded) < 1)
    {
        // no friends, yes array
    }
    
    foreach($decoded as $friend)
    {
        $friendId = intval($friend->id);
        $friendSql = "SELECT * FROM users WHERE usersId = {$friendId}";
        $friendResult = mysqli_query($conn, $friendSql);
        if (mysqli_num_rows($friendResult) < 1)
        {
            // friend no longer exists
            $friendRows .= "<tr>
                                <td>[Deleted]</td>
                                <td>[Deleted]</td>
                                <td>[Deleted]</td>
                                <td>[Deleted]</td>
                            </tr>";
        } else {
            $friendReturn = $friendResult->fetch_object();
    
            if ($friend->request_type == "accepted")
                $accepted = "Friends";
            else if ($friend->request_type == "requested")
                $accepted = "<a href='./includes/acceptfriend.inc.php?friend=$friend->id'>Accept</a> <a href='./includes/rejectfriend.inc.php?friend=$friend->id'>Reject</a>";
            else if ($friend->request_type == "requester")
                $accepted = "You have send this user a friend request";
            else {
                $accepted =  "bnruh";
            }
    
            $friendRows .= "<tr>
                                <td>$friendReturn->nickname</td>
                                <td>$friendReturn->name</td>
                                <td>$accepted</td>
                                <td>$friend->date</td>
                            </tr>";
        }
    }
}

?>

<main>
    <div class="friendlist">
        <table>
            <thead>
                <tr>
                    <th>Nickname</th>
                    <th>Name</th>
                    <th>Accepted</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?= $friendRows ?>
            </tbody>
        </table>

    </div>

</main>