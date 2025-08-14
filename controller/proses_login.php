<?php
session_start();
include '../config/db.php'; // Koneksi ke database

$username = $_POST['username'];
$password = md5($_POST['password']); // Pastikan sama hashing-nya dengan yang di database

// Query cek user
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Simpan data ke session
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['kelas'] = $user['kelas'];

    // Arahkan sesuai role
    if ($user['role'] === 'admin') {
        header("Location: ../pages/admin_page.php");
    } else if ($user['role'] === 'siswa') {
        header("Location: ../pages/siswa_page.php");
    }
    exit;
} else {
    // Gagal login
    header("Location: ../auth/login.php?pesan=gagal");
    exit;
}
?>
