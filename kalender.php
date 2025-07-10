<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id'])) {
  header("Location: auth.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$tanggal_terpilih = $_GET['date'] ?? date('Y-m-d');

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=? AND deadline=? ORDER BY priority DESC");
$stmt->bind_param("is", $user_id, $tanggal_terpilih);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kalender Tugas</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
<div class="container">
  <h2>📅 Kalender Deadline Tugas</h2>
  <a href="index.php">⬅️ Kembali ke Dashboard</a>

  <form method="GET" style="margin-top: 20px;">
    <input type="text" id="datepicker" name="date" value="<?= $tanggal_terpilih ?>" placeholder="Pilih tanggal..." required>
    <button type="submit">Lihat</button>
  </form>

  <ul style="margin-top: 30px;">
    <h4>Tugas pada <?= date('d M Y', strtotime($tanggal_terpilih)) ?>:</h4>
    <?php if ($result->num_rows === 0): ?>
      <li>Tidak ada tugas.</li>
    <?php else: ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          <strong><?= htmlspecialchars($row['title']) ?></strong>
          (<?= $row['category'] ?> | <?= $row['priority'] ?>)
          [<?= $row['status'] ?>]
        </li>
      <?php endwhile; ?>
    <?php endif; ?>
  </ul>
</div>

<script>
flatpickr("#datepicker", {
  dateFormat: "Y-m-d"
});
</script>
</body>
</html>
