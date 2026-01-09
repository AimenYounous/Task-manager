<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    exit("Unauthorized");
}

$task_id = $_POST["task_id"];

$stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    ":id" => $task_id,
    ":user_id" => $_SESSION["user_id"]
]);

echo "✅ Task deleted successfully";
?>
