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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Tambah Data Siswa Kelas <?= strtoupper($kelas) ?>
        </h2>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Kelas:</label>
                <input type="text" name="kelas" value="<?= strtoupper($kelas) ?>" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password:</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-end gap-3">
                <a href="admin_page.php" 
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>

</body>
</html>
