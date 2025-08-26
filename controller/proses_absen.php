<?php
session_start();
include '../config/db.php';

date_default_timezone_set('Asia/Jakarta');

if ($_SESSION['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$nama_siswa = $_SESSION['username'];
$kelas = $_SESSION['kelas'];
$status = $_POST['status'];
$tanggal_jam = date('Y-m-d H:i:s');
$jam_sekarang = date('H:i:s');

// Tentukan batas jam masuk
$batas_jam = "10:00:00";
$keterangan = ($jam_sekarang > $batas_jam) ? "Terlambat" : "Tepat Waktu";

// Cek apakah sudah absen hari ini
$tanggal_hari_ini = date('Y-m-d');
$cek = mysqli_query($conn, "SELECT * FROM absen 
                            WHERE nama_siswa='$nama_siswa' 
                            AND kelas='$kelas' 
                            AND DATE(tanggal)='$tanggal_hari_ini'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Kamu sudah absen hari ini!'); window.location='../pages/siswa_page.php';</script>";
    exit;
}

// Simpan ke database
$query = "INSERT INTO absen (nama_siswa, kelas, tanggal, status, keterangan) 
          VALUES ('$nama_siswa', '$kelas', '$tanggal_jam', '$status', '$keterangan')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Absensi berhasil! ($keterangan)'); window.location='../pages/siswa_page.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
