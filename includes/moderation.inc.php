<?php

session_start();
require '../config/config.php';
require_once("./functions.inc.php");

// check user privileges and whether there's a getter
if (!isset($_SESSION["id"]) || !isset($_GET["id"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
	header("Location: ../index.php");
	exit();
} else if ($_SESSION["role"] != "admin") {
	header("Location: ../index.php");
	exit();
}

$option = "";

// which form was used?
if (!isset($_POST["ban"]))
{
    if (!isset($_POST["timeout"]))
    {
        header("Location: ../index.php?content=dashboard");
        exit();
    } else
        $option = "timeout";
} else
    $option = "ban";

$id = $_GET["id"];

$db = new Database();

try {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $db->query("INSERT INTO `moderation` (`modId`, `usersId`, `modOption`, `reason`, `endDate`, `appeal`) VALUES (:modId, :usersId, :modOption, :reason, :endDate, :appeal)");
    $db->bind(':modId', NULL, PDO::PARAM_INT);
    $db->bind(':usersId', $id, PDO::PARAM_INT);
    $db->bind(':modOption', $option, PDO::PARAM_STR);
    $db->bind(':reason', $_POST['reason'], PDO::PARAM_STR);
    $db->bind(':endDate', $_POST['datetime'], PDO::PARAM_STR);
    $db->bind(':appeal', NULL, PDO::PARAM_STR);

    $db->execute();
    header("Location: ../index.php?content=dashboard");
}
catch (PDOException $error)
{
    echo $error->getMessage();
    header("Location: ../index.php?content=dashboard");
}
?>