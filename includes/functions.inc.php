<?php

require_once "./connect.php";

// Check if username is in database, if so then return data
function checkIfExists($conn, $rowToCheck, $check) {
  $sql = "SELECT * FROM users WHERE $rowToCheck = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../components/register.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "s", $check);
	mysqli_stmt_execute($stmt);

	// "Get result" returns the results from a prepared statement
	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	else {
		return false;
	}

}

// sanitize user input fields, prevents code injections
function sanitize($conn, $raw_data) {
	return (trim(htmlspecialchars(mysqli_real_escape_string($conn, $raw_data))));
}

// uploads file into $file
function uploadFile($file, $imageId) {

	// server upload location
	$target_dir = "../src/img/uploads/" . $file . "/";

	// database location string
	$save_dir = "./src/img/uploads/" . $file . "/";
	$imageFileType = strtolower(pathinfo(basename($_FILES[$file]["name"]),PATHINFO_EXTENSION));
	
	$target_file = $target_dir . $imageId . '.' . $imageFileType;
	$save_file = $save_dir . $imageId . '.' . $imageFileType;
	
	// allowed types
	$typeArray = array("jpg", "png", "jpeg", "gif");
	
	$check = getimagesize($_FILES[$file]["tmp_name"]);
	if ($check !== false) {
		if ($_FILES[$file]["size"] < 5000000) {
			if (in_array($imageFileType, $typeArray)) {
				if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
					echo "The file ". htmlspecialchars(basename($_FILES[$file]["name"])). " has been uploaded.";
				} else {
					return "&error=defaultError";
				}
			} else {
				return "&error=fileTypeError";
			}
		} else {
			return "&error=sizeLimitError";
		}
	} else {
		return "&error=fileTypeError";
	}

	return $save_file;
}

?>