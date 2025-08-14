<?php
session_start();

if (!isset($_SESSION['role'])) {
    // Belum login → ke login.php
    header("Location: auth/login.php");
    exit;
}

// Sudah login → arahkan sesuai role
if ($_SESSION['role'] == 'admin') {
    header("Location: pages/admin_page.php");
    exit;
} elseif ($_SESSION['role'] == 'siswa') {
    header("Location: pages/siswa_page.php");
    exit;
} else {
    // Role tidak dikenal → logout paksa
    header("Location: auth/logout.php");
    exit;
}
