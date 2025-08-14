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
        <h2 class="text-xl font-semibold mb-4">Data Admin</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama</th>
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
                        </tr>";
                        $no_admin++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Data Siswa -->
    <section class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Data Siswa</h2>
            <a href="tambah_data.php" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">+ Tambah Siswa</a>
        </div>

        <!-- Filter Kelas -->
        <form method="GET" action="" class="mb-4 flex gap-4">
            <select name="kelas" class="border rounded px-3 py-2">
                <option value="">Semua Kelas</option>
                <option value="X" <?= isset($_GET['kelas']) && $_GET['kelas']=='X'?'selected':''; ?>>X</option>
                <option value="XI" <?= isset($_GET['kelas']) && $_GET['kelas']=='XI'?'selected':''; ?>>XI</option>
                <option value="XII" <?= isset($_GET['kelas']) && $_GET['kelas']=='XII'?'selected':''; ?>>XII</option>
            </select>
            <input type="text" name="search" placeholder="Cari nama..." value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>" class="border rounded px-3 py-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
        </form>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Lengkap</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';
                    $search_filter = isset($_GET['search']) ? $_GET['search'] : '';

                    $sql_siswa = "SELECT * FROM users WHERE role='siswa'";
                    if ($kelas_filter != '') {
                        $sql_siswa .= " AND kelas='$kelas_filter'";
                    }
                    if ($search_filter != '') {
                        $sql_siswa .= " AND username LIKE '%$search_filter%'";
                    }
                    $sql_siswa .= " ORDER BY kelas ASC, username ASC";

                    $result = mysqli_query($conn, $sql_siswa);
                    $no_siswa = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='border-b hover:bg-gray-50'>
                            <td class='p-3'>{$no_siswa}</td>
                            <td class='p-3'>{$row['username']}</td>
                            <td class='p-3'>{$row['kelas']}</td>
                            <td class='p-3'>
                                <a href='edit_data.php?id={$row['id']}' class='text-yellow-500 hover:underline mr-2'>Edit</a>
                                <a href='../controller/hapus_data.php?id={$row['id']}' onclick=\"return confirm('Yakin hapus data ini?')\" class='text-red-500 hover:underline'>Hapus</a>
                            </td>
                        </tr>";
                        $no_siswa++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Data Absensi -->
    <section>
        <h2 class="text-xl font-semibold mb-4">Data Absensi</h2>
        <form method="GET" action="" class="bg-white p-4 rounded-lg shadow mb-4 flex flex-wrap gap-4">
            <div>
                <label class="block text-gray-700 text-sm mb-1">Kelas:</label>
                <select name="kelas_absen" class="border rounded px-3 py-2">
                    <option value="">Semua</option>
                    <option value="X" <?= isset($_GET['kelas_absen']) && $_GET['kelas_absen']=='X'?'selected':''; ?>>X</option>
                    <option value="XI" <?= isset($_GET['kelas_absen']) && $_GET['kelas_absen']=='XI'?'selected':''; ?>>XI</option>
                    <option value="XII" <?= isset($_GET['kelas_absen']) && $_GET['kelas_absen']=='XII'?'selected':''; ?>>XII</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm mb-1">Tanggal:</label>
                <input type="date" name="tanggal" value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>" class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 text-sm mb-1">Cari Nama:</label>
                <input type="text" name="search_absen" placeholder="Cari nama..." value="<?= isset($_GET['search_absen']) ? $_GET['search_absen'] : ''; ?>" class="border rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
            </div>
        </form>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Siswa</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kelas_absen = isset($_GET['kelas_absen']) ? $_GET['kelas_absen'] : '';
                    $tanggal_absen = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
                    $search_absen = isset($_GET['search_absen']) ? $_GET['search_absen'] : '';

                    $sql_absen = "SELECT * FROM absen WHERE 1=1";
                    if ($kelas_absen != '') {
                        $sql_absen .= " AND kelas='$kelas_absen'";
                    }
                    if ($tanggal_absen != '') {
                        $sql_absen .= " AND tanggal='$tanggal_absen'";
                    }
                    if ($search_absen != '') {
                        $sql_absen .= " AND nama_siswa LIKE '%$search_absen%'";
                    }
                    $sql_absen .= " ORDER BY tanggal DESC"; // terbaru di atas

                    $result = mysqli_query($conn, $sql_absen);
                    $no_absen = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='border-b hover:bg-gray-50'>
                            <td class='p-3'>{$no_absen}</td>
                            <td class='p-3'>{$row['nama_siswa']}</td>
                            <td class='p-3'>{$row['kelas']}</td>
                            <td class='p-3'>{$row['tanggal']}</td>
                            <td class='p-3'>{$row['status']}</td>
                        </tr>";
                        $no_absen++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

</body>
</html>
