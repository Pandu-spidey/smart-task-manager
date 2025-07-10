<?php
session_start();
include "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $conn->query("DELETE FROM tasks WHERE id = $id AND user_id = $user_id");
}
header("Location: index.php");
?>
