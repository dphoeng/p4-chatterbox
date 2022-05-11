<main>
	<form action="../includes/login.inc.php" method="post" enctype="multipart/form-data" id="form">
		<input type="text" name="uid" placeholder="Email/Gebruikersnaam" required>
		<input type="password" name="pwd" placeholder="Wachtwoord" required>
		<button type="submit" name="submit">Log in</button>
		<a href="./register.php">Hebt u nog geen account? Klik dan hier om te registreren.</a>
		<?php
		// Error messages
		if (isset($_GET["error"])) {
			switch ($_GET["error"]) {
				case "empty-input":
					echo "<p>Fill in all fields!</p>";
					break;

				case "unknown-login":
					echo "<p>This username or email is not known in our database!</p>";
					break;

				case "wrong-login":
					echo "<p>Wrong login!</p>";
					break;
			}
		}
		?>
	</form>
</main>