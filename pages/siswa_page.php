<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Halo, <?= $_SESSION['username']; ?> <span class="text-blue-600">(Kelas <?= $_SESSION['kelas']; ?>)</span>
        </h1>

        <form action="../controller/proses_absen.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Status Kehadiran:</label>
                <select name="status" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                </select>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Absen Sekarang
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="../auth/logout.php" class="text-red-500 hover:underline">Logout</a>
        </p>
    </div>

</body>
</html>
