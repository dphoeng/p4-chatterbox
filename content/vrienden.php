<?php

if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT friends FROM users WHERE usersId = {$_SESSION['id']} AND friends IS NOT NULL";
$result = mysqli_query($conn, $sql);
$friendRows = "";
$requestRows = "";
$requestedRows = "";

if (mysqli_num_rows($result) < 1) {
    // no friends, no array
} else {
    $return = $result->fetch_object();
    $decoded = json_decode($return->friends);

    foreach ($decoded->friends as $friend) {
        // retrieve info of friend
        $friendId = intval($friend->id);
        $friendSql = "SELECT * FROM users WHERE usersId = {$friendId}";
        $friendResult = mysqli_query($conn, $friendSql);
        if (mysqli_num_rows($friendResult) < 1) {
            // friend no longer exists
            $friendRows .= "<tr>
                                <td>[Deleted]</td>
                                <td>[Deleted]</td>
                                <td>[Deleted]</td>
                                <td>[Deleted]</td>
                            </tr>";
        } else {
            $friendReturn = $friendResult->fetch_object();

            // check friend status
            switch ($friend->request_type) {
                case "friends":
                    $friendRows .= "<tr>
										<td><img src='$friendReturn->avatar' alt='avatar'></td>
										<td>$friendReturn->nickname</td>
										<td>$friendReturn->name</td>
										<td><a href='./includes/rejectfriend.inc.php?friend=$friend->id'>Remove friend</a></td>
										<td>$friend->date</td>
									</tr>";
                    break;

                case "requested":
                    $requestRows .= "<tr>
										<td><img src='$friendReturn->avatar' alt='avatar'></td>
										<td>$friendReturn->nickname</td>
										<td>$friendReturn->name</td>
										<td><a href='./includes/acceptfriend.inc.php?friend=$friend->id'>Accept</a> <a href='./includes/rejectfriend.inc.php?friend=$friend->id'>Reject</a></td>
										<td>$friend->date</td>
									</tr>";
                    break;

                case "requester":
                    $requestedRows .= "<tr>
											<td><img src='$friendReturn->avatar' alt='avatar'></td>
											<td>$friendReturn->nickname</td>
											<td>$friendReturn->name</td>
											<td><a href='./includes/rejectfriend.inc.php?friend=$friend->id'>Remove req</a></td>
											<td>$friend->date</td>
										</tr>";
                    break;

                default:
                    echo "error?";
                    break;
            }
        }
    }
}

?>

<main class="side-main-content friend-page">
    <div class="friendlist">
        <div class="nav">
            <ul class="space-around">
                <li><a href="#1">Friends</a></li>
                <li><a href="#2">Friend Requests</a></li>
                <li><a href="#3">Outgoing Requests</a></li>
            </ul>
        </div>
    </div>
    <div class="side-scroll-container">

        <div class="side-scroll-item table-container">
            <table id="1">
                <caption>Friends</caption>
                <thead>
                    <tr>
                        <th></th>
                        <th>Nickname</th>
                        <th>Name</th>
                        <th></th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $friendRows ?>
                </tbody>
            </table>
        </div>
        <div class="side-scroll-item table-container">
            <table id="2">
                <caption>Friend requests</caption>
                <thead>
                    <tr>
                        <th></th>
                        <th>Nickname</th>
                        <th>Name</th>
                        <th></th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $requestRows ?>
                </tbody>
            </table>

        </div>
        <!-- other users you requested to -->
        <div class="side-scroll-item table-container">
            <table id="3">
                <caption>Requested friends</caption>
                <thead>
                    <tr>
                        <th></th>
                        <th>Nickname</th>
                        <th>Name</th>
                        <th></th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $requestedRows ?>
                </tbody>
            </table>

        </div>

    </div>
</main>