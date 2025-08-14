<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

// Ambil kelas dari query string (x, xi, xii)
$kelas = $_GET['kelas'] ?? '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $kelas_input = mysqli_real_escape_string($conn, $_POST['kelas']);
    $pass = md5($_POST['password']);

    // Insert ke tabel users
    $sql = "INSERT INTO users (username, password, kelas, role) 
            VALUES ('$username', '$pass', '$kelas_input', 'siswa')";
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
                <label class="block text-gray-700 font-medium mb-1">Username / Nama Lengkap:</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Kelas:</label>
                <select name="kelas" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII">XII</option>
                </select>
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
