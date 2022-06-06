<?php

session_start();
require("./functions.inc.php");
require("./connect.php");

if (!isset($_SESSION["id"]) || !isset($_GET["profiel"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
	header("Location: ../index.php");
  exit();
}

$profile = $_GET["profiel"];
$poster = $_SESSION["id"];
$attachedToId = isset($_GET["attached"]) ? $_GET["attached"] : null;

if (isset($_POST["submit"])) {
	$userid = $_SESSION["id"];

	$message = sanitize($conn, $_POST['message']);

	$sql = 'SELECT image FROM krabbels';
	$result = $conn->query($sql);
	
	$krabbelId = 0;

	// finds the highest value of images in database so the file to upload becomes 1 higher and duplicate files are prevented
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			if ($row['image'] !== null)
			{
				$tmp = intval(pathinfo($row['image'], PATHINFO_FILENAME));
				if ($krabbelId <= $tmp) { $krabbelId = $tmp + 1; }
			}
		}
	}

	// check whether a krabbel image was uploaded, if it is, 
	// enter the uploadFile function, which uploads the files and puts the right path in the database
	$sql = "INSERT INTO `krabbels` (`krabbelId`, `profileId`, `posterId`, `attachedToId`, `text`, `image`, `postDate`) VALUES (NULL, ?, ?, ?, ?, ?, ?);";
	$returnKrabbel = "";

	if ($_FILES["krabbel"]["error"] == 0) {
		$returnKrabbel = uploadFile("krabbel", $krabbelId);
		if ($returnKrabbel[0] == "&")
		{
			header("Location: ../index.php?content=profiel/{$profile}&error={$returnKrabbel}");
			exit;
		}
	} else if ($_FILES["krabbel"]["error"] == 1) {
		header("Location: ../index.php?content=profiel/{$profile}&error=sizeLimitError");
		exit;
	} else if ($_FILES["krabbel"]["error"] == 4)
	{
		$returnKrabbel = null;
		// no image and no text was uploaded, empty krabbel
		if (strlen($message) < 1)
		{
			header("Location: ../index.php?content=profiel/{$profile}&error=emptyKrabbel");
			exit;
		}
	}

	// upload the other user input into database
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("iiisss", $profile, $poster, $attachedToId, $message, $returnKrabbel, date("Y-m-d H:i:s"));

		
	if ($stmt->execute()) {
		// exit;
		header("Location: ../index.php?content=profiel/{$profile}");
	} else {
		// var_dump($_POST);
		// echo mysqli_stmt_error($stmt);
		// exit;
		header("Location: ../index.php?content=profiel/{$profile}&error=defaultError");
	}
} else {
	// default error is thrown when when post array is empty, this also happens when a file is uploaded which is bigger in size than the 'post_max_size' in php.ini
	header("Location: ../index.php?content=profiel/{$profile}&error=defaultError");
}
