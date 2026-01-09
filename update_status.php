<?php
session_start();
include "config/db.php";

if (isset($_POST["task_id"]) && isset($_POST["status"])) {
    $task_id = $_POST["task_id"];
    $status = $_POST["status"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ":status" => $status,
        ":id" => $task_id,
        ":user_id" => $user_id
    ]);

    echo "updated";
}
