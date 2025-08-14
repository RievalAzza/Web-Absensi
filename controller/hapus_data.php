<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

$id = $_GET['id'] ?? 0;

// Cegah hapus admin
mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='siswa'");

header("Location: ../pages/admin_page.php");
exit;
