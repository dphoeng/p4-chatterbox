<?php
session_start();

if (isset($_SESSION["id"])) {
	header("Location: ../index.php");
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title></title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>


<body>

	<main>
		<form action="../includes/login.inc.php" method="post" enctype="multipart/form-data" id="form">
			<input type="text" name="uid" placeholder="Email/Gebruikersnaam" required>
			<input type="password" name="pwd" placeholder="Wachtwoord" required>
			<button type="submit" name="submit">Log in</button>
			<a href="./register.php">Hebt u nog geen account? Klik dan hier om te registreren.</a>
			<?php
			// Error messages
			if (isset($_GET["error"])) {
				switch ($_GET["error"])
				{
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

</body>

</html>