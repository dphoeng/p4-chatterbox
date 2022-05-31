<?php

if (isset($_SESSION["id"])) {
	header("Location: ../index.php");
	exit();
}

?>

<main>
	<form action="../includes/signup.inc.php" method="post" id="form">
		<input type="text" name="naam" placeholder="Volledige naam" required>
		<input type="mail" name="email" placeholder="E-Mail" required>
		<input type="text" name="uid" placeholder="Gebruikersnaam" maxlength="16" required>
		<input type="password" name="pwd" placeholder="Wachtwoord" required>
		<input type="password" name="pwdrepeat" placeholder="Wachtwoord Herhalen" required>
		<button type="submit" name="submit">Registreer</button>
		<a href="?content=login">Hebt u al een account? Klik dan hier om in te loggen.</a>
	</form>
	<?php
	if (isset($_GET["error"])) {
		switch ($_GET["error"]) {
			case "emptyinput":
				echo "<p>Fill in all fields!</p>";
				break;

			case "invaliduid":
				echo "<p>Choose a proper username!</p>";
				break;

			case "invalidemail":
				echo "<p>Choose a proper email!</p>";
				break;

			case "passwordsdontmatch":
				echo "<p>Passwords don't match!</p>";
				break;

			case "stmtfailed":
				echo "<p>Something went wrong!</p>";
				break;

			case "usernametaken":
				echo "<p>Username already taken!</p>";
				break;

			case "emailtaken":
				echo "<p>Email already taken!</p>";
				break;
		}
	} ?>
</main>