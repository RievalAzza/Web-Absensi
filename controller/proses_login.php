<?php
session_start();
include '../config/db.php'; // Pastikan file conn ke database

$username = $_POST['username'];
$password = md5($_POST['password']);


// Cek di tabel admin
$sql = "SELECT * FROM admin WHERE nama='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'admin';
    header("Location: ../pages/admin_page.php");
    exit;
}

// Daftar tabel yang akan dicek
$tabel_siswa = [
    'siswa_x'   => 'X',
    'siswa_xi'  => 'XI',
    'siswa_xii' => 'XII'
];

// Cek di semua tabel siswa
foreach ($tabel_siswa as $tabel => $kelas) {
    $sql = "SELECT * FROM $tabel WHERE nama_lengkap='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'siswa';
        $_SESSION['kelas'] = $kelas;
        header("Location: ../pages/siswa_page.php");
        exit;
    }
}

// Kalau tidak ada yang cocok
header("Location: ../auth/login.php?pesan=gagal");
exit;
?>
