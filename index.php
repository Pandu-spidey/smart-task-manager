<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}
include "db.php";

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = ($filter === 'pending') ? "AND status = 'Pending'" : "";

$sql = "SELECT * FROM tasks WHERE user_id = ? $where ORDER BY deadline ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Home Smart Task Manager</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Hi, <?= htmlspecialchars($username) ?> 👋</h2>
  <a href="logout.php">🚪 Logout</a>
  <a href="statistik.php">📊 Statistik</a>
  <a href="kalender.php">📅 Lihat via Kalender</a>

  <h3>📋 Daftar Tugas</h3>

  <form method="POST" action="tambah.php">
    <input type="text" name="title" placeholder="Judul tugas..." required>
    <input type="text" name="category" placeholder="Kategori..." required>
    <input type="date" name="deadline" required>
    <select name="priority">
      <option value="Low">Low</option>
      <option value="Medium">Medium</option>
      <option value="High">High</option>
    </select>
    <button type="submit">Tambah</button>
  </form>

  <p>
    <a href="index.php?filter=all">[Semua]</a> | 
    <a href="index.php?filter=pending">[Belum Selesai]</a>
  </p>

  <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
      <li>
        <strong><?= htmlspecialchars($row['title']) ?></strong>
        <small>(<?= $row['category'] ?> | <?= $row['priority'] ?> | <?= $row['deadline'] ?>)</small><br>
        Status: <?= $row['status'] ?> |
        <a href="status.php?id=<?= $row['id'] ?>">[✔]</a>
        <a href="edit.php?id=<?= $row['id'] ?>">[✏️]</a>
        <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')">[🗑️]</a>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
</body>
</html>
