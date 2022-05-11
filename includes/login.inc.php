<?php
include("./connect.php");
include("./functions.inc.php");

session_start();


$email = sanitize($_POST["uid"]);
$password = sanitize($_POST["pwd"]);

if (empty($email) || empty($password)) {
	// empty post fields
	header("Location: ../components/login.php?error=empty-input");
} else {
	$sql = "SELECT * FROM `users` WHERE `email` = '$email' OR `nickname` = '$email'";
	$result = mysqli_query($conn, $sql);
	if (!mysqli_num_rows($result)) {
		// no email or username in database
		header("Location: ../components/login.php?error=unknown-login");
	} else {
		$record = mysqli_fetch_assoc($result);
		if (!password_verify($password, $record["password"])) {
			// password does not match
			header("Location: ../components/login.php?error=wrong-login");
		} else {
			$_SESSION["id"] = $record["usersId"];
			$_SESSION["role"] = $record["role"];
			header("Location: ../components/test.php");
		}
	}
}
