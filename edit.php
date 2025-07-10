<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: auth.php");
  exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}

$id = $_GET['id'];

// Ambil data tugas yang mau diedit
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "Tugas tidak ditemukan atau bukan milikmu.";
  exit;
}

$task = $result->fetch_assoc();

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = $_POST['title'];
  $category = $_POST['category'];
  $deadline = $_POST['deadline'];
  $priority = $_POST['priority'];

  $update = $conn->prepare("UPDATE tasks SET title=?, category=?, deadline=?, priority=? WHERE id=? AND user_id=?");
  $update->bind_param("ssssii", $title, $category, $deadline, $priority, $id, $user_id);
  $update->execute();

  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Tugas</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Edit Tugas</h2>
  <form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    <input type="text" name="category" value="<?= htmlspecialchars($task['category']) ?>" required>
    <input type="date" name="deadline" value="<?= $task['deadline'] ?>" required>
    <select name="priority">
      <option value="Low" <?= ($task['priority'] === 'Low') ? 'selected' : '' ?>>Low</option>
      <option value="Medium" <?= ($task['priority'] === 'Medium') ? 'selected' : '' ?>>Medium</option>
      <option value="High" <?= ($task['priority'] === 'High') ? 'selected' : '' ?>>High</option>
    </select>
    <button type="submit">Simpan Perubahan</button>
    <a href="index.php" style="display:inline-block;margin-top:10px;">⬅️ Kembali</a>
  </form>
</div>
</body>
</html>
