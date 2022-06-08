<?php

session_start();
require '../config/config.php';
require("./functions.inc.php");
require("./connect.php");

if (!isset($_SESSION["id"]) || !isset($_GET["profiel"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
	header("Location: ../index.php");
  exit();
}

$profile = $_GET["profiel"];
$poster = $_SESSION["id"];
$attachedToId = isset($_GET["attached"]) ? $_GET["attached"] : null;

if (isset($_POST["post"]) || isset($_POST["update"])) {
	$message = sanitize($conn, $_POST['message']);

	$krabbels = new Krabbels();
	$krabbels = $krabbels->readAll();
	$krabbelImageId = 0;

	// finds the highest value of images in database so the file to upload becomes 1 higher and duplicate files are prevented
	if ($krabbels) {
		foreach ($krabbels as $krabbel) {
			if ($krabbel->image !== null)
			{
				$tmp = intval(pathinfo($krabbel->image, PATHINFO_FILENAME));
				if ($krabbelImageId <= $tmp) { $krabbelImageId = $tmp + 1; }
			}
		}
	}
	
	// check whether a krabbel image was uploaded, if it is, 
	// enter the uploadFile function, which uploads the files and puts the right path in the database
	$returnKrabbel = "";

	if ($_FILES["krabbel"]["error"] == 0) {
		$returnKrabbel = uploadFile("krabbel", $krabbelImageId);
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

	$db = new Database();
	
	if (isset($_POST["post"]))
	{
		try {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$db->query("INSERT INTO `krabbels` (`krabbelId`, `profileId`, `posterId`, `attachedToId`, `text`, `image`, `postDate`) VALUES (:krabbelId, :profileId, :posterId, :attachedToId, :text, :image, :postDate)");
			$db->bind(':krabbelId', NULL, PDO::PARAM_INT);
			$db->bind(':profileId', $profile, PDO::PARAM_INT);
			$db->bind(':posterId', $poster, PDO::PARAM_INT);
			$db->bind(':attachedToId', $attachedToId, PDO::PARAM_INT);
			$db->bind(':text', $message, PDO::PARAM_STR);
			$db->bind(':image', $returnKrabbel, PDO::PARAM_STR);
			$db->bind(':postDate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
		
			$db->execute();
			header("Location: ../index.php?content=profiel/{$profile}");
		}
		catch (PDOException $error)
		{
			echo $error->getMessage();
			header("Location: ../index.php?content=profiel/{$profile}&error=defaultError");
		}
	} else {
		if (!isset($_GET['krabbelId']))
			header("Location: ../index.php?content=profiel/{$profile}");
		$krabbelId = $_GET['krabbelId'];
		try {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if ($returnKrabbel == null)
				$db->query("UPDATE `krabbels` SET `text` = :text, `postDate` = :postDate WHERE `krabbelId` = $krabbelId");	
			else
			{
				$db->query("UPDATE `krabbels` SET `text` = :text, `image` = :image, `postDate` = :postDate WHERE `krabbelId` = $krabbelId");
				$db->bind(':image', $returnKrabbel, PDO::PARAM_STR);
			}
			$db->bind(':text', $message, PDO::PARAM_STR);
			$db->bind(':postDate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
		
			$db->execute();
			header("Location: ../index.php?content=profiel/{$profile}");
		}
		catch (PDOException $error)
		{
			echo $error->getMessage();
			header("Location: ../index.php?content=profiel/{$profile}&error=defaultError");
		}
	}
} else {
	// default error is thrown when when post array is empty, this also happens when a file is uploaded which is bigger in size than the 'post_max_size' in php.ini
	header("Location: ../index.php?content=profiel/{$profile}&error=defaultError");
}
