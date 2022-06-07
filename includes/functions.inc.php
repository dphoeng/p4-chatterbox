<?php

// Check if username is in database, if so then return data
function checkIfExists($conn, $rowToCheck, $check)
{
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
	} else {
		return false;
	}
}

// sanitize user input fields, prevents code injections
function sanitize($conn, $raw_data)
{
	return (trim(htmlspecialchars(mysqli_real_escape_string($conn, $raw_data))));
}

// uploads file into $file
function uploadFile($file, $imageId)
{

	// server upload location
	$target_dir = "../src/img/uploads/" . $file . "/";

	// database location string
	$save_dir = "./src/img/uploads/" . $file . "/";
	$imageFileType = strtolower(pathinfo(basename($_FILES[$file]["name"]), PATHINFO_EXTENSION));

	$target_file = $target_dir . $imageId . '.' . $imageFileType;
	$save_file = $save_dir . $imageId . '.' . $imageFileType;

	// allowed types
	$typeArray = array("jpg", "png", "jpeg", "gif");

	$check = getimagesize($_FILES[$file]["tmp_name"]);
	if ($check !== false) {
		if ($_FILES[$file]["size"] < 5000000) {
			if (in_array($imageFileType, $typeArray)) {
				if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
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

// checks if the row for JSON friends is empty
function checkIfEmpty($conn, $id, $otherId, $encoded)
{
	$sql = "SELECT friends FROM users WHERE usersId = {$otherId}";
	$result = mysqli_query($conn, $sql);
	if (!mysqli_num_rows($result)) {
		// user doesn't exist
	} else {
		$record = mysqli_fetch_assoc($result);
	}

	$decodedJSON = json_decode($record['friends']);

	// turn each object into a friend object and place into array
	if (isset($decodedJSON)) {
		// friends array of object
		foreach ($decodedJSON->friends as $friend) {
			// if the user you're trying to befriend has already received a request from you
			if (intval($friend->id) == $id) {
				return null;
			}
		}
		return "UPDATE users SET friends=JSON_ARRAY_APPEND(friends, '$.friends', CAST('{$encoded}' AS JSON)) WHERE usersId=$otherId;";
	} else {
		// create new JSON_OBJECT if there is non yet (NULL)
		return "UPDATE users SET friends=JSON_OBJECT('friend_count', 0, 'friends', JSON_ARRAY(CAST('{$encoded}' AS JSON))) WHERE usersId = $otherId;";
	}
}
