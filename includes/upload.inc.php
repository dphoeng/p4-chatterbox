<?php
session_start();
require_once "./connect.php";
require_once "./functions.inc.php";

if (!isset($_SESSION["id"]))
{
	header("Location: ../index.php");
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
	$sql = "UPDATE users SET nickname = ?, birthday = ?, bio = ?";
	$returnAvatar = "";
	$returnBackground = "";

	if ($_FILES["avatar"]["error"] == 0) {
		$returnAvatar = uploadFile("avatar", $avatarId);
		if ($returnAvatar[0] != "&")
		{
			array_push($imageArray, $returnAvatar);
			$sql .= ", avatar = ?";
		} else {
			header("Location: ../index.php?content=profielEdit&error={$returnAvatar}");
			exit;
		}
	} else if ($_FILES["background"]["error"] == 1) {
		header("Location: ../index.php?content=profielEdit&error=sizeLimitError");
		exit;
	}

	if ($_FILES["background"]["error"] == 0) {
		$returnBackground = uploadFile("background", $backgroundId);
		if ($returnBackground[0] != "&")
		{
			array_push($imageArray, $returnBackground);
			$sql .= ", background = ?";
		} else {
			header("Location: ../index.php?content=profielEdit&error={$returnBackground}");
			exit;
		}
	} else if ($_FILES["background"]["error"] == 1) {
		header("Location: ../index.php?content=profielEdit&error=sizeLimitError");
		exit;
	}

	$sql .= " WHERE usersId = ?";

	array_push($imageArray, $_SESSION["id"]);

	// upload the other user input into database
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sss" . str_repeat('s', count($imageArray) - 1) . "i", $_POST["nickname"], $_POST["birthday"], $_POST["bio"], ...$imageArray);

	if ($stmt->execute()) {
		header("Location: ../index.php?content=profielEdit");
	} else {
		header("Location: ../index.php?content=profielEdit&error=defaultError");
	}
} else {
	// default error is thrown when when post array is empty, this also happens when a file is uploaded which is bigger in size than the 'post_max_size' in php.ini
	header("Location: ../index.php?content=profielEdit&error=defaultError");
}
?>