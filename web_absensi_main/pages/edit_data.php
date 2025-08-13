<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Edit Data Siswa (<?= strtoupper(str_replace('siswa_', '', $tabel)) ?>)
        </h2>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Kelas:</label>
                <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas']) ?>" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password (kosongkan jika tidak diubah):</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-end gap-3">
                <a href="admin_page.php?kelas=<?= $kelas ?>" 
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Update
                </button>
            </div>
        </form>
    </div>

</body>
</html>
