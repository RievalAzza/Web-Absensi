<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$kelasList = ['X', 'XI', 'XII'];
$kelasData = [];

foreach ($kelasList as $kelas) {
    $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='siswa' AND kelas='$kelas'");
    $data = mysqli_fetch_assoc($query);
    $kelasData[] = [
        'nama' => $kelas,
        'total' => $data['total']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">Admin Dashboard</h1>
            <div class="flex items-center gap-4">
                <a href="data_siswa.php" class="hover:underline">Data Siswa</a>
                <a href="data_absen.php" class="hover:underline">Data Absen</a>
                <a href="../auth/logout.php" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-7xl mx-auto p-6 w-full">
        <!-- Profile Admin -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Profile Card -->
            <div class="bg-white shadow rounded-lg p-6 flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=0D8ABC&color=fff" 
                     alt="Profile" class="w-16 h-16 rounded-full shadow">
                <div>
                    <h2 class="text-xl font-bold"><?php echo $_SESSION['username']; ?></h2>
                    <p class="text-gray-600">Role: Admin</p>
                </div>
            </div>

            <!-- Kelas Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-3">Kelas</h2>
                <div class="space-y-2">
                    <?php foreach ($kelasData as $kelas): ?>
                        <div class="flex justify-between bg-gray-100 p-3 rounded">
                            <span class="font-medium"><?php echo $kelas['nama']; ?></span>
                            <span class="text-blue-600"><?php echo $kelas['total']; ?> siswa</span>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- Data Admin -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Data Admin</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-blue-500 text-white">
                            <th class="p-3">No</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no_admin = 1; 
                        $result = mysqli_query($conn, "SELECT * FROM users WHERE role='admin'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr class='border-b hover:bg-gray-50'>
                                <td class='p-3'>{$no_admin}</td>
                                <td class='p-3'>{$row['username']}</td>
                                <td class='p-3'>{$row['role']}</td>
                            </tr>";
                            $no_admin++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4 mt-auto">
        <p>&copy; <?php echo date("Y"); ?> Sistem Informasi Sekolah. All rights reserved.</p>
    </footer>

</body>
</html>
