<?php

if (!isset($_SESSION["id"]))
{
	header("Location: ../index.php");
	exit();
}

$sql = "SELECT * FROM users WHERE usersId = {$_SESSION['id']}";
$result = mysqli_query($conn, $sql);
$record = mysqli_fetch_assoc($result);

?>

<main>
	<form action="../includes/uploadtest.inc.php" method="post" enctype="multipart/form-data" id="form">
		<input type="file" name="avatar">
		<input type="file" name="background">
		<input type="text" name="nickname" value="<?php echo $record['nickname']; ?>">
		<input type="date" name="birthday" value="<?php echo $record['birthday']; ?>">
		<textarea name="bio" form="form" value="<?php echo $record['bio']; ?>"></textarea>
		<button type="submit" name="submit">Publiceren</button>
	</form>
</main>