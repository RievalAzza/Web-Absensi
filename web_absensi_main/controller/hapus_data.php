<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

$kelas = $_GET['kelas'] ?? '';
$id = $_GET['id'] ?? 0;

if (!in_array(strtoupper($kelas), ['X', 'XI', 'XII'])) {
    die("Kelas tidak valid");
}

$tabel = "siswa_" . strtolower($kelas);

// Hapus data
mysqli_query($conn, "DELETE FROM $tabel WHERE id='$id'");

header("Location: ../pages/admin_page.php");
exit;
