<?php

require_once "./connect.php";

// Check if username is in database, if so then return data
function checkIfExists($conn, $rowToCheck, $check) {
  $sql = "SELECT * FROM users WHERE $rowToCheck = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	// header("location: ../components/register.php?error=stmtfailed");
		// exit();
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
function sanitize($raw_data) {
	global $conn;
	return (trim(htmlspecialchars(mysqli_real_escape_string($conn, $raw_data))));
}

?>