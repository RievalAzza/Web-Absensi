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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $success = $dataHandler->deleteStudent($id);

    if ($success) {
        header("Location: ../pages/data_siswa.php?pesan=hapus_berhasil");
    } else {
        header("Location: ../pages/data_siswa.php?pesan=hapus_gagal");
    }
    exit;
} else {
    header("Location: ../pages/data_siswa.php?pesan=hapus_gagal");
    exit;
}
?>