<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Ambil parameter id user
$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("ID tidak valid");
}

// Ambil data lama dari tabel users
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan");
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $kelas_input = $_POST['kelas'];
    $pass = !empty($_POST['password']) ? md5($_POST['password']) : $data['password'];

    $sql = "UPDATE users 
            SET username='$username', kelas='$kelas_input', password='$pass' 
            WHERE id='$id'";
    mysqli_query($conn, $sql);

    header("Location: admin_page.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Data User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Edit Data User
        </h2>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Username / Nama Lengkap:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Kelas:</label>
                <select name="kelas" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">- Pilih Kelas -</option>
                    <option value="X" <?= $data['kelas'] == 'X' ? 'selected' : '' ?>>X</option>
                    <option value="XI" <?= $data['kelas'] == 'XI' ? 'selected' : '' ?>>XI</option>
                    <option value="XII" <?= $data['kelas'] == 'XII' ? 'selected' : '' ?>>XII</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password (kosongkan jika tidak diubah):</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-end gap-3">
                <a href="admin_page.php" 
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
