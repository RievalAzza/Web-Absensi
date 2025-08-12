<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

$kelas = $_GET['kelas'] ?? '';

if (!in_array($kelas, ['x', 'xi', 'xii'])) {
    die("Kelas tidak valid");
}

// Tentukan nama tabel berdasarkan kelas
$tabel = "siswa_" . $kelas;

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $kelas_input = $_POST['kelas'];
    $pass = md5($_POST['password']);

    $sql = "INSERT INTO $tabel (nama_lengkap, kelas, password) VALUES ('$nama_lengkap', '$kelas_input', '$pass')";
    mysqli_query($conn, $sql);

    header("Location: admin_page.php");
    exit;
}
?>

<h2>Tambah Data Siswa Kelas <?= strtoupper($kelas) ?></h2>
<form method="POST">
    <label>Nama Lengkap:</label>
    <input type="text" name="nama_lengkap" required><br>

    <label>Kelas:</label>
    <input type="text" name="kelas" value="<?= strtoupper($kelas) ?>" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Simpan</button>
</form>
