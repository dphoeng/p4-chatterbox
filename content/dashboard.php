<?php

if (!isset($_SESSION["id"])) {
	header("Location: ../index.php");
	exit();
} else if ($_SESSION["role"] != "admin") {

		header("Location: ../index.php");
	exit();
}
$allUsers = new Users();
$allUsers = $allUsers->readAll();
?>

<main class="side-main-content side-scroll-container">
	<div class="friendlist side-scroll-item table-container">
		<table>
			<caption>Users</caption>
			<thead>
				<tr>
					<th></th>
					<th>Nickname</th>
					<th>Name</th>
					<th>Bio</th>
					<th>Role</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($allUsers as $user) : ?>
					<dialog data-popup="extra-userinfo-<?= $user->usersId ?>">
						<form action="./includes/moderation.inc.php?id=<?= $user->usersId; ?>" method="post" id="form">
							<h3>User info</h3>
							<p>
								<strong>Nickname:</strong> <?= $user->nickname ?>
							</p>
							<p>
								<strong>Name:</strong> <?= $user->name ?>
							</p>
							<p>
								<strong>Bio:</strong> <?= $user->bio ?>
							</p>
							<p>
								<strong>Background:</strong> <?= $user->background ?>
							</p>
							<p>
								<strong>Role:</strong> <?= $user->role ?>
							</p>
							<div class="display-flex">
								<input class="hover" type="datetime-local" name="datetime" min="<?= date("Y-m-d\Th:i"); ?>" required>
								<input type="text" name="reason" placeholder="Reason">
							</div>
							<div class="display-flex">
								<input class="hover" type="submit" name="timeout" value="Timeout">
								<input class="hover" type="submit" name="ban" value="Ban">
							</div>
							<button class="hover" data-popup-close="extra-userinfo-<?= $user->usersId ?>">Close</button>
						</form>
					</dialog>
					<tr>
						<td><?= $user->usersId ?></td>
						<td><?= $user->nickname ?></td>
						<td><?= $user->name ?></td>
						<td><?= $user->bio ?></td>
						<td><?= $user->role ?></td>
						<td>
							<button class="hover" data-popup-open="extra-userinfo-<?= $user->usersId ?>">more</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</main>