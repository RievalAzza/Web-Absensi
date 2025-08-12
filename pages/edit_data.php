<?php
include '../config/db.php';

// Ambil parameter kelas & id
$kelas = $_GET['kelas'] ?? '';
$id = $_GET['id'] ?? '';

if (!in_array($kelas, ['X', 'XI', 'XII'])) {
    die("Kelas tidak valid");
}

$tabel = "siswa_" . strtolower($kelas);

// Ambil data lama
$query = mysqli_query($conn, "SELECT * FROM $tabel WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan");
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $kelas_input = $_POST['kelas'];
    $pass = !empty($_POST['password']) ? md5($_POST['password']) : $data['password'];

    $sql = "UPDATE $tabel 
            SET nama_lengkap='$nama_lengkap', kelas='$kelas_input', password='$pass' 
            WHERE id='$id'";
    mysqli_query($conn, $sql);

    header("Location: admin_page.php?kelas=$kelas");
    exit;
}
?>

<!-- Form Edit -->
<h2>Edit Data Siswa (<?= strtoupper(str_replace('siswa_', '', $tabel)) ?>)</h2>
<form method="POST">
    <label>Nama Lengkap:</label>
    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required><br>

    <label>Kelas:</label>
    <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas']) ?>" required><br>

    <label>Password (kosongkan jika tidak diubah):</label>
    <input type="password" name="password"><br>

    <button type="submit">Update</button>
</form>
