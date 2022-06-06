<?php

if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit();
} else if ($_SESSION["role"] != "admin") {

	header("Location: ../index.php");
    exit();
} else {
	$rows = "";
	
	$sql = "SELECT * FROM users";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) < 1) {
		// empty database L
	} else while ($return = $result->fetch_assoc())
	{
		$rows .= "<dialog data-popup='show-more-info'>
						<form action=''>
							sss
						</form>
					</dialog>
					<tr>
					<td><img src='{$return['avatar']}' alt='avatar'></td>
					<td>{$return['nickname']}</td>
					<td>{$return['name']}</td>
					<td>{$return['bio']}</td>
					<td><img src='{$return['background']}' alt='background'></td>
					<td>{$return['role']}</td>
					<td class='mod-options'>";
		if ($return['role'] == "user")
			$rows .= '<button data-open-popup="show-more-info" class="icon-rounded button hover medium"><svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M24 30.75 12 18.75 14.15 16.6 24 26.5 33.85 16.65 36 18.8Z"/></svg></button>';

		$rows .="</td></tr>";
	}
}



?>

<main class="side-main-content side-scroll-container">
    <div class="side-scroll-item table-container">
        <table>
            <caption>Users</caption>
            <thead>
                <tr>
                    <th></th>
                    <th>Nickname</th>
                    <th>Name</th>
                    <th>Bio</th>
                    <th>Background</th>
                    <th>Role</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?= $rows ?>
            </tbody>
        </table>
    </div>
</main>