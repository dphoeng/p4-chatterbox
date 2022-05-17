<?php
session_start();
require_once "./connect.php";
require_once "./functions.inc.php";

if (!isset($_SESSION["id"]))
{
	header("Location: ../index.php");
	exit();
}

if (isset($_POST["submit"])) {
	$userid = $_SESSION["id"];

	$sql = 'SELECT avatar, background FROM users';
	$result = $conn->query($sql);
	
	$imageArray = array();
	$avatarId = 0;
	$backgroundId = 0;

	// finds the highest value of images in database so the file to upload becomes 1 higher and duplicate files are prevented
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			if ($row['avatar'] !== null)
			{
				$tmp = intval(pathinfo($row['avatar'], PATHINFO_FILENAME));
				if ($avatarId <= $tmp) { $avatarId = $tmp + 1; }
			}

			if ($row['background'] !== null)
			{
				$tmp = intval(pathinfo($row['background'], PATHINFO_FILENAME));
				if ($backgroundId <= $tmp) { $backgroundId = $tmp + 1; }
			}
		}
	}

	// check whether avatar and/or background were uploaded, if it is, 
	// enter the uploadFile function, which uploads the files and puts the right path in the database
	if ($_FILES["avatar"]["error"] == 0) {
		uploadFile($conn, "avatar", $avatarId);
	}
	if ($_FILES["background"]["error"] == 0) {
		uploadFile($conn, "background", $backgroundId);
	}

	// upload the other user input into database
	$stmt = $conn->prepare("UPDATE users SET nickname = ?, birthday = ?, bio = ? WHERE usersId = ?");
	$stmt->bind_param("sssi", $_POST["nickname"], $_POST["birthday"], $_POST["bio"], $_SESSION["id"]);

	if ($stmt->execute()) {
		header("Location: ../index.php");
	} else {
		header("Location: ../index.php");
	}
}

if ($stmt->execute()) {


} else {
	header("Location: ../components/content.php");
	exit();
}
?>