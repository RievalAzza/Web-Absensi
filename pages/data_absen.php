<?php 
    $data_absensi = include '../controller/AbsensiController.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="max-w-7xl mx-auto p-6">
<section>

          <div class="flex space-x-4"> 
              <a href="admin_page.php">Data Admin</a>
              <a href="data_siswa.php">Data Siswa</a>
                <h2 class="text-xl font-semibold mb-4">Data Absensi</h2>
            </div>

    <!-- Form Filter -->
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

    <!-- Tabel -->
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
                $no_absen = $data_absensi['offset_absen'] + 1;
                while ($row = mysqli_fetch_assoc($data_absensi['result_absen'])) {
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

    <!-- Pagination -->
    <div class="mt-4 flex gap-2">
        <?php
        for ($i = 1; $i <= $data_absensi['total_pages_absen']; $i++) {
            $active = ($i == $data_absensi['page_absen']) ? "bg-blue-500 text-white" : "bg-gray-200";
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