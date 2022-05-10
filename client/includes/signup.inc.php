<?php

require_once "./connect.php";
require_once "./functions.inc.php";

if (isset($_POST["submit"])) {

	// First we get the form data from the URL
	$voornaam = sanitize($_POST["voornaam"]);
	$tussenvoegsel = sanitize($_POST["tussenvoegsel"]);
	$achternaam = sanitize($_POST["achternaam"]);
	$email = sanitize($_POST["email"]);
	$username = sanitize($_POST["uid"]);
	$pwd = sanitize($_POST["pwd"]);
	$pwdrepeat = sanitize($_POST["pwdrepeat"]);

	// Then we run a bunch of error handlers to catch any user mistakes we can

	// Left inputs empty
	if (empty($voornaam) || empty($achternaam) || empty($email) || empty($username) || empty($pwd) || empty($pwdrepeat)) {
		header("location: ../components/register.php?error=emptyinput");
	}

	// Check invalid username
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		header("location: ../components/register.php?error=invaliduid");
	}

	// Check invalid email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("location: ../components/register.php?error=invalidemail");
	}

	// Check if passwords matches
	if ($pwd !== $pwdrepeat) {
		header("location: ../components/register.php?error=passwordsdontmatch");
	}

	// Is the username taken already
	if (checkIfExists($conn, "nickname", $username) !== false) {
		header("location: ../components/register.php?error=usernametaken");
		exit();
	}
	// Is the email taken already
	if (checkIfExists($conn, "email", $email) !== false) {
		header("location: ../components/register.php?error=emailtaken");
		exit();
	}

	// If we get to here, it means there are no user errors

	// Insert new user into database
	$sql = "INSERT INTO users (nickname, firstname, infix, lastname, email, password, role) VALUES (?, ?, ?, ?, ?, ?, 'user');";

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	// 	header("location: ../components/register.php?error=stmtfailed");
	// 	exit();
	}

	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "ssssss", $username, $voornaam, $tussenvoegsel, $achternaam, $email, $hashedPwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header("location: ../components/login.php");
} else {
	header("location: ../components/register.php");
}

exit();
