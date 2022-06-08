<main class="side-scroll-container side-main-content">
	<form class="side-scroll-item login" id="SignIN" action="../includes/login.inc.php" method="post" enctype="multipart/form-data">
		<div>
			<input type="text" name="uid" placeholder="Email/Gebruikersnaam" required>
			<input type="password" name="pwd" placeholder="Wachtwoord" required>
			<button type="submit" name="submit">Log in</button>
			<a href="#SignUP">Hebt u nog geen account? Klik dan hier om te registreren.</a>
		</div>
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

				case "banned-user":
					echo "<p>This account has been banned!</p>";
					break;
			}
		}
		?>
	</form>
	<form class="side-scroll-item login" id="SignUP" action="../includes/signup.inc.php" method="post">
		<div>
			<input type="text" name="naam" placeholder="Volledige naam" required>
			<input type="mail" name="email" placeholder="E-Mail" required>
			<input type="text" name="uid" placeholder="Gebruikersnaam" maxlength="16" required>
			<input type="password" name="pwd" placeholder="Wachtwoord" required>
			<input type="password" name="pwdrepeat" placeholder="Wachtwoord Herhalen" required>
			<button type="submit" name="submit">Registreer</button>
			<a href="#SignIN">Hebt u al een account? Klik dan hier om in te loggen.</a>
		</div>
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