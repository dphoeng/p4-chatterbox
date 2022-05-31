<?php
include("./connect.php");
include("./functions.inc.php");

session_start();


$email = sanitize($conn, $_POST["uid"]);
$password = sanitize($conn, $_POST["pwd"]);

if (empty($email) || empty($password)) {
	// empty post fields
	header("Location: ../index.php?content=components/login&error=empty-input");
} else {
	$sql = "SELECT * FROM `users` WHERE `email` = '$email' OR `nickname` = '$email'";
	$result = mysqli_query($conn, $sql);
	if (!mysqli_num_rows($result)) {
		// no email or username in database
		header("Location: ../index.php?content=components/login&error=unknown-login");
	} else {
		$record = mysqli_fetch_assoc($result);
		if (!password_verify($password, $record["password"])) {
			// password does not match
			header("Location: ../index.php?content=components/login&error=wrong-login");
		} else {
			$_SESSION["id"] = $record["usersId"];
			$_SESSION["role"] = $record["role"];
<<<<<<< Updated upstream
			header("Refresh: 0; ../index.php?content=home");
=======
			header("Refresh: 0; ../?home");
>>>>>>> Stashed changes
		}
	}
}
