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

// Ambil parameter id user
$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("ID tidak valid");
}

// Ambil data lama dari tabel users
$data = $dataHandler->getStudentById($id);

if (!$data) {
    die("Data tidak ditemukan");
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $kelas_input = $_POST['kelas'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    
    $success = $dataHandler->editStudent($id, $username, $kelas_input, $password);

    if ($success) {
        header("Location: ../pages/data_siswa.php?pesan=edit_berhasil");
    } else {
        header("Location: ../pages/data_siswa.php?pesan=edit_gagal");
    }
    exit;
}

// Jika bukan POST, tampilkan form
require_once "../pages/edit_data.php";
?>