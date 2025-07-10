<?php
session_start();
session_unset();    // Hapus semua variabel sesi
session_destroy();  // Hancurkan sesi aktif
header("Location: auth.php"); // Redirect ke halaman login
exit;
