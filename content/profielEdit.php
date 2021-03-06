<?php

if (!isset($_SESSION["id"])) {
	header("Location: ../index.php");
	exit();
}



$sql = "SELECT * FROM users WHERE usersId = {$_SESSION['id']}";
$result = mysqli_query($conn, $sql);
$record = mysqli_fetch_assoc($result);

?>

<main class="edit-profiel side-main-content">
	<h3>Edit profiel</h3>
	<form action="../includes/upload.inc.php" method="post" enctype="multipart/form-data" id="form">
		<label for="avatar">avatar</label>
		<input type="file" id="avatar" name="avatar" onchange="showPreview(event);"> <?php if ($record['avatar']) {
																																										echo "<img src='.{$record['avatar']}' id='file-ip-1-preview'>";
																																									} ?>
		<input type="file" name="background" onchange="showPreview(event);"> <?php if ($record['background']) {
																																						echo "<img src='.{$record['background']}' id='file-ip-1-preview'>";
																																					} ?>
		<input type="text" name="nickname" value="<?php echo $record['nickname']; ?>">
		<input type="date" name="birthday" value="<?php echo $record['birthday']; ?>">
		<textarea name="bio" form="form" value="<?php echo $record['bio']; ?>"><?php echo $record['bio']; ?></textarea>
		<button type="submit" name="submit">Publiceren</button>
	</form>
</main>
<?php if (isset($_GET["error"])) {
	switch ($_GET["error"]) {
		case "sizeLimitError":
			echo '<script type="text/javascript">alert("Your file exceeds the 5mb size limit!");</script>';
			break;

		case "defaultError":
			echo '<script type="text/javascript">alert("Something went wrong, try again later or contact an admin.");</script>';
			break;
	}
}
?>