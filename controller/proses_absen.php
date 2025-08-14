<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$nama_siswa = $_SESSION['username'];
$kelas = $_SESSION['kelas'];
$status = $_POST['status'];
$tanggal = date('Y-m-d');

// Cek apakah sudah absen hari ini
$cek = mysqli_query($conn, "SELECT * FROM absen 
                            WHERE nama_siswa='$nama_siswa' 
                            AND kelas='$kelas' 
                            AND tanggal='$tanggal'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Kamu sudah absen hari ini!'); window.location='../pages/siswa_page.php';</script>";
    exit;
}

// Simpan ke database
$query = "INSERT INTO absen (nama_siswa, kelas, tanggal, status) 
          VALUES ('$nama_siswa', '$kelas', '$tanggal', '$status')";
if (mysqli_query($conn, $query)) {
    echo "<script>alert('Absensi berhasil!'); window.location='../pages/siswa_page.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
