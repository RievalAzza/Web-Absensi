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

        <?php
        // Pagination untuk Data Siswa
        $limit_siswa = 10;
        $page_siswa = isset($_GET['page_siswa']) ? (int)$_GET['page_siswa'] : 1;
        if ($page_siswa < 1) $page_siswa = 1;
        $offset_siswa = ($page_siswa - 1) * $limit_siswa;

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

        // Hitung total data
        $total_siswa = mysqli_num_rows(mysqli_query($conn, $sql_siswa));

        // Query dengan LIMIT
        $sql_siswa .= " LIMIT $limit_siswa OFFSET $offset_siswa";
        $result_siswa = mysqli_query($conn, $sql_siswa);
        ?>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Lengkap</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no_siswa = $offset_siswa + 1;
                    while ($row = mysqli_fetch_assoc($result_siswa)) {
                        echo "<tr class='border-b hover:bg-gray-50'>
                            <td class='p-3'>{$no_siswa}</td>
                            <td class='p-3'>{$row['username']}</td>
                            <td class='p-3'>{$row['kelas']}</td>
                            <td class='p-3'>{$row['role']}</td>
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

        <!-- Pagination Links -->
        <div class="mt-4 flex gap-2">
            <?php
            $total_pages_siswa = ceil($total_siswa / $limit_siswa);
            for ($i = 1; $i <= $total_pages_siswa; $i++) {
                $active = ($i == $page_siswa) ? "bg-blue-500 text-white" : "bg-gray-200";
                $url_params = $_GET;
                $url_params['page_siswa'] = $i;
                $link = "?".http_build_query($url_params);
                echo "<a href='$link' class='px-3 py-1 rounded $active'>$i</a>";
            }
            ?>
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

    <?php
    // Pagination untuk Absensi
    $limit_absen = 10;
    $page_absen = isset($_GET['page_absen']) ? (int)$_GET['page_absen'] : 1;
    if ($page_absen < 1) $page_absen = 1;
    $offset_absen = ($page_absen - 1) * $limit_absen;

    $kelas_absen = isset($_GET['kelas_absen']) ? $_GET['kelas_absen'] : '';
    $tanggal_absen = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
    $search_absen = isset($_GET['search_absen']) ? $_GET['search_absen'] : '';

    $sql_absen = "SELECT * FROM absen WHERE 1=1";
    if ($kelas_absen != '') {
        $sql_absen .= " AND kelas='$kelas_absen'";
    }
    if ($tanggal_absen != '') {
        // Filter hanya berdasarkan tanggal saja, jam diabaikan
        $sql_absen .= " AND DATE(tanggal)='$tanggal_absen'";
    }
    if ($search_absen != '') {
        $sql_absen .= " AND nama_siswa LIKE '%$search_absen%'";
    }
    $sql_absen .= " ORDER BY tanggal DESC";

    $total_absen = mysqli_num_rows(mysqli_query($conn, $sql_absen));

    $sql_absen .= " LIMIT $limit_absen OFFSET $offset_absen";
    $result_absen = mysqli_query($conn, $sql_absen);
    ?>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="p-3">No</th>
                    <th class="p-3">Nama Siswa</th>
                    <th class="p-3">Kelas</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_absen = $offset_absen + 1;
                while ($row = mysqli_fetch_assoc($result_absen)) {
                    echo "<tr class='border-b hover:bg-gray-50'>
                        <td class='p-3'>{$no_absen}</td>
                        <td class='p-3'>{$row['nama_siswa']}</td>
                        <td class='p-3'>{$row['kelas']}</td>
                        <td class='p-3'>" . date('Y-m-d', strtotime($row['tanggal'])) . "</td>
                        <td class='p-3'>{$row['status']}</td>
                        <td class='p-3'>{$row['keterangan']}</td>
                    </tr>";
                    $no_absen++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4 flex gap-2">
        <?php
        $total_pages_absen = ceil($total_absen / $limit_absen);
        for ($i = 1; $i <= $total_pages_absen; $i++) {
            $active = ($i == $page_absen) ? "bg-blue-500 text-white" : "bg-gray-200";
            $url_params = $_GET;
            $url_params['page_absen'] = $i;
            $link = "?".http_build_query($url_params);
            echo "<a href='$link' class='px-3 py-1 rounded $active'>$i</a>";
        }
        ?>
    </div>
</section>

</div>
</body>
</html>
