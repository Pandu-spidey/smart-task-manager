<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id'])) {
  header("Location: auth.php");
  exit;
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Ambil data kategori
$kategori = [];
$jumlah = [];
$sql = "SELECT category, COUNT(*) as total FROM tasks WHERE user_id=? GROUP BY category";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $kategori[] = $row['category'];
  $jumlah[] = $row['total'];
}

// Ambil data status
$status = ['Pending', 'Completed'];
$status_data = [];
foreach ($status as $s) {
  $q = $conn->prepare("SELECT COUNT(*) as total FROM tasks WHERE user_id=? AND status=?");
  $q->bind_param("is", $user_id, $s);
  $q->execute();
  $r = $q->get_result()->fetch_assoc();
  $status_data[] = $r['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Statistik Tugas</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
  <h2>📊 Statistik Tugas <?= htmlspecialchars($username) ?></h2>
  <a href="index.php">⬅️ Kembali ke Dashboard</a>

  <canvas id="kategoriChart" style="margin-top:30px;"></canvas>
  <canvas id="statusChart" style="margin-top:50px;"></canvas>
</div>

<script>
const kategoriChart = new Chart(document.getElementById('kategoriChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($kategori) ?>,
    datasets: [{
      label: 'Jumlah Tugas per Kategori',
      data: <?= json_encode($jumlah) ?>,
      backgroundColor: 'rgba(54, 162, 235, 0.6)'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: { display: true, text: 'Kategori Tugas' }
    }
  }
});

const statusChart = new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: ['Pending', 'Completed'],
    datasets: [{
      label: 'Status',
      data: <?= json_encode($status_data) ?>,
      backgroundColor: ['#f39c12', '#2ecc71']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: { display: true, text: 'Status Tugas' }
    }
  }
});
</script>
</body>
</html>
