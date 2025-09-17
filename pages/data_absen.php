<?php 
  
    require_once '../controller/AbsensiController.php';
    
    $data_absensi = [
        'result_absen' => $result_absen,
        'offset_absen' => $offset_absen,
        'page_absen' => $page_absen,
        'total_pages_absen' => $total_pages_absen,
        'total_absen' => $total_absen
    ];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Data Absensi</title>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
    theme: {
        extend: {
        boxShadow: { soft:"0 8px 30px rgba(0,0,0,.10)" }
        }
    }
    }
</script>
<meta name="color-scheme" content="light dark" />
</head>
<body class="min-h-dvh relative overflow-hidden bg-neutral-950 text-neutral-100">


<div class="absolute inset-0 -z-10">
    <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2069&auto=format&fit=crop')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-neutral-950/70"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-black/50 via-black/20 to-black/50"></div>
</div>


<div class="min-h-dvh max-w-7xl mx-auto p-4 sm:p-6">

    
    <div class="mb-4 flex items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-semibold leading-tight">Data Absensi</h1>
        <p class="text-sm text-neutral-300">Kelola dan telusuri absensi harian siswa.</p>
    </div>
    <div class="hidden sm:flex items-center gap-2">
        <a href="admin_page.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-3 py-2 text-sm hover:bg-white/15">Dashboard</a>
    </div>
    </div>

    
    <section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4 mb-4">
    <?php
        // Mengambil parameter filter dari URL
        $kelas_absen = $_GET['kelas_absen'] ?? '';
        $tanggal = $_GET['tanggal'] ?? '';
        $search_absen = $_GET['search_absen'] ?? '';
        $status = $_GET['status'] ?? '';
    ?>
    <form method="get" class="grid grid-cols-1 sm:grid-cols-6 gap-2">
        <select name="kelas_absen" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
            <option value="">Semua Kelas</option>
            <option value="X" <?= $kelas_absen=='X'?'selected':''; ?>>X</option>
            <option value="XI" <?= $kelas_absen=='XI'?'selected':''; ?>>XI</option>
            <option value="XII" <?= $kelas_absen=='XII'?'selected':''; ?>>XII</option>
        </select>
        <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <input name="search_absen" value="<?= htmlspecialchars($search_absen) ?>" class="rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" placeholder="Cari nama siswa...">
        <select name="status" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
            <option value="">Semua Status</option>
            <?php foreach (['Hadir','Izin','Sakit','Alpa'] as $st): ?>
                <option value="<?= $st ?>" <?= $status===$st?'selected':'' ?>><?= $st ?></option>
            <?php endforeach; ?>
        </select>
        <div class="sm:col-span-6 flex items-center gap-2">
            <button class="rounded-lg bg-white text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-100">Terapkan</button>
            <a href="data_absen.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">Reset</a>
        </div>
    </form>
    </section>

    
    <section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
    <div class="overflow-auto rounded-xl border border-white/10 bg-white/5">
        <table class="min-w-[760px] w-full text-sm">
        <thead class="bg-white/10 text-neutral-200">
            <tr>
            <th class="text-left px-4 py-3">No</th>
            <th class="text-left px-4 py-3">Nama</th>
            <th class="text-left px-4 py-3">Kelas</th>
            <th class="text-left px-4 py-3">Tanggal</th>
            <th class="text-left px-4 py-3">Status</th>
            <th class="text-left px-4 py-3">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
            <?php
            // Mengambil data dari variabel yang sudah di-set oleh controller
            $result_absen = $result_absen ?? null;
            $offset_absen = $offset_absen ?? 0;
            
            // Fungsi untuk menampilkan badge status
            function badge($status){
                $map = [
                'Hadir' => 'bg-green-500/20 text-green-100 border-green-400/30',
                'Izin' => 'bg-blue-500/20 text-blue-100 border-blue-400/30',
                'Sakit' => 'bg-amber-500/20 text-amber-100 border-amber-400/30',
                'Alpa' => 'bg-red-500/20 text-red-100 border-red-400/30',
                'Terlambat' => 'bg-purple-500/20 text-purple-100 border-purple-400/30',
                ];
                $cls = $map[$status] ?? 'bg-white/10 text-white border-white/20';
                return "<span class='px-2 py-1 rounded-md text-xs font-medium border $cls'>".htmlspecialchars($status)."</span>";
            }
            
            // Jika ada data, tampilkan
            if ($result_absen && mysqli_num_rows($result_absen) > 0) {
                $no_absen = $offset_absen + 1;
                while ($row = mysqli_fetch_assoc($result_absen)) {
                    echo "<tr class='hover:bg-white/5'>
                        <td class='px-4 py-3'>{$no_absen}</td>
                        <td class='px-4 py-3 font-medium'>" . htmlspecialchars($row['nama_siswa'] ?? '-') . "</td>
                        <td class='px-4 py-3'>" . htmlspecialchars($row['kelas'] ?? '-') . "</td>
                        <td class='px-4 py-3'>" . (!empty($row['tanggal']) ? date('d-m-Y', strtotime($row['tanggal'])) : '-') . "</td>
                        <td class='px-4 py-3'>" . badge($row['status'] ?? '-') . "</td>
                        <td class='px-4 py-3 text-neutral-300'>" . htmlspecialchars($row['keterangan'] ??  '-') . "</td>
                    </tr>";
                    $no_absen++;
                }
            } else {
                echo "<tr><td colspan='6' class='px-4 py-8 text-center text-neutral-300'>Tidak ada data absensi.</td></tr>";
            }
            ?>
        </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex items-center gap-2 flex-wrap">
        <?php
        // Pagination - menggunakan variabel dari controller
        $total_pages_absen = $total_pages_absen ?? 1;
        $page_absen = $page_absen ?? 1;
        
        for ($i = 1; $i <= $total_pages_absen; $i++) {
            $active = ($i == $page_absen) ? "bg-white text-neutral-900" : "bg-white/10 text-white hover:bg-white/15";
            $url_params = $_GET;
            $url_params['page_absen'] = $i;
            $link = "?".http_build_query($url_params);
            echo "<a href='$link' class='px-3 py-1.5 rounded $active border border-white/20'>$i</a>";
        }
        ?>
    </div>
    </section>

    
    <div class="mt-6 text-xs text-neutral-300">© Kelola.Biz</div>
</div>
</body>
</html>