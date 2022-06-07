<?php

require_once "./connect.php";
require_once "./functions.inc.php";

if (isset($_POST["submit"])) {

	// First we get the form data from the URL
	$naam = sanitize($conn, $_POST["naam"]);
	$email = sanitize($conn, $_POST["email"]);
	$username = sanitize($conn, $_POST["uid"]);
	$pwd = sanitize($conn, $_POST["pwd"]);
	$pwdrepeat = sanitize($conn, $_POST["pwdrepeat"]);

	// Then we run a bunch of error handlers to catch any user mistakes we can

	// Left inputs empty
	if (empty($naam) || empty($email) || empty($username) || empty($pwd) || empty($pwdrepeat)) {
		header("location: ../index.php?content=login&error=emptyinput");
	}

	// Check invalid username
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		header("location: ../index.php?content=login&error=invaliduid");
	}

	// Check invalid email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("location: ../index.php?content=login&error=invalidemail");
	}

	// Check if passwords matches
	if ($pwd !== $pwdrepeat) {
		header("location: ../index.php?content=login&error=passwordsdontmatch");
	}

	// Is the username taken already
	if (checkIfExists($conn, "nickname", $username) !== false) {
		header("location: ../index.php?content=login&error=usernametaken");
		exit();
	}
	// Is the email taken already
	if (checkIfExists($conn, "email", $email) !== false) {
		header("location: ../index.php?content=login&error=emailtaken");
		exit();
	}

	// If we get to here, it means there are no user errors

	// Insert new user into database
	$sql = "INSERT INTO users (nickname, name, email, password, avatar, role) VALUES (?, ?, ?, ?, '../src/img/default_avatar.png', 'user');";

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../index.php?content=login&error=stmtfailed");
		exit();
	}

	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "ssss", $username, $naam, $email, $hashedPwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header("location: ../index.php");
} else {
	header("location: ../index.php");
}

exit();
