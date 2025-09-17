<?php

require_once "../config/db.php";
require_once "../classes/User.php";

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$database = new Database("localhost", "root", "", "absensi");
$dataHandler = new User($database);

// Ambil kelas dari query string (x, xi, xii)
$kelas = $_GET['kelas'] ?? '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $kelas_input = $_POST['kelas'];
    $password = $_POST['password'];
    
    $success = $dataHandler->addStudent($username, $kelas_input, $password);

    if ($success) {
        header("Location: ../pages/data_siswa.php?pesan=tambah_berhasil");
    } else {
        header("Location: ../pages/data_siswa.php?pesan=tambah_gagal");
    }
    exit;
}


require_once "../pages/tambah_data.php";
?>