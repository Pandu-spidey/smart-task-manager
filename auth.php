<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $error = "Username sudah terdaftar.";
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        $error = "Berhasil daftar, silakan login.";
    }
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login/Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container auth-box">
  <h2>Smart Task Manager</h2>
  <form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login">Login</button>
    <button type="submit" name="register">Register</button>
  </form>
  <p class="error"><?= $error ?></p>
</div>
</body>
</html>
