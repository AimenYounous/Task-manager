<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    exit("Unauthorized");
}

$task_id = $_POST["task_id"];
$title = $_POST["title"];
$desc = $_POST["description"];
$priority = $_POST["priority"];

$stmt = $conn->prepare("UPDATE tasks SET title=:title, description=:desc, priority=:priority WHERE id=:id AND user_id=:user_id");
$stmt->execute([
    ":title" => $title,
    ":desc" => $desc,
    ":priority" => $priority,
    ":id" => $task_id,
    ":user_id" => $_SESSION["user_id"]
]);

echo "Task updated successfully";
