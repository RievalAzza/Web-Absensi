<?php 
$data_siswa = include '../controller/DataSiswaController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="max-w-7xl mx-auto p-6">
<section class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <div class="flex space-x-4"> 
    
            <a href="admin_page.php">Data Admin</a>
            <h2 class="text-xl font-semibold">Data Siswa</h2>
              <a href="data_absen.php"> Data Absen </a>
          </div>
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

    <!-- Tabel -->
     
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
                $no_siswa = $data_siswa['offset_siswa'] + 1;
                while ($row = mysqli_fetch_assoc($data_siswa['result_siswa'])) {
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

    <!-- Pagination -->
    <div class="mt-4 flex gap-2">
        <?php
        for ($i = 1; $i <= $data_siswa['total_pages_siswa']; $i++) {
            $active = ($i == $data_siswa['page_siswa']) ? "bg-blue-500 text-white" : "bg-gray-200";
            $url_params = $_GET;
            $url_params['page_siswa'] = $i;
            $link = "?".http_build_query($url_params);
            echo "<a href='$link' class='px-3 py-1 rounded $active'>$i</a>";
        }
        ?>
    </div>
</section>

</div>
</body>
</html>