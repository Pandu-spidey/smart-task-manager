<?php
session_start();
include "db.php";

if (isset($_POST['title'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, category, deadline, priority) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $category, $deadline, $priority);
    $stmt->execute();
}
header("Location: index.php");
?>
