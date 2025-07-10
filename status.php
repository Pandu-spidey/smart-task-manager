<?php
session_start();
include "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Get current status
    $q = $conn->query("SELECT status FROM tasks WHERE id = $id AND user_id = $user_id");
    $current = $q->fetch_assoc()['status'];
    $new = ($current === 'Pending') ? 'Completed' : 'Pending';

    $conn->query("UPDATE tasks SET status = '$new' WHERE id = $id AND user_id = $user_id");
}
header("Location: index.php");
?>
