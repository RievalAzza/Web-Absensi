<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100 text-gray-900">
    
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, <span class="text-blue-600"><?php echo $_SESSION['username']; ?></span>!</h1>
                <p class="text-gray-600">Ini adalah halaman admin. Anda dapat melihat semua data di sini.</p>
            </div>
            <a href="../auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
        
        <!-- Data Admin -->
        <section class="mb-8">
            <div class="flex space-x-4"> 
                <h2 class="text-xl font-semibold mb-4">Data Admin </h2>

                <a href="data_siswa.php"> Data Siswa </a>
                <a href="data_absen.php"> Data Absen </a>
            </div>
            
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

</div>
</body>
</html>
