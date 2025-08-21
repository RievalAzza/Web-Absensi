<?php 
    // Tetap: ambil data dari controller (jangan diubah logic-nya)
    $data_absensi = include '../controller/AbsensiController.php';
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
        $q      = $_GET['q']     ?? ($data_absensi['query']  ?? '');
        $from   = $_GET['from']  ?? ($data_absensi['from']   ?? '');
        $to     = $_GET['to']    ?? ($data_absensi['to']     ?? '');
        $status = $_GET['status']?? ($data_absensi['status'] ?? '');
    ?>
    <form method="get" class="grid grid-cols-1 sm:grid-cols-5 gap-2">
        <input name="q" value="<?= htmlspecialchars($q) ?>" class="sm:col-span-2 rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" placeholder="Cari nama / NIS / kelas…">
        <input type="date" name="from" value="<?= htmlspecialchars($from) ?>" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <input type="date" name="to" value="<?= htmlspecialchars($to) ?>" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <select name="status" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <option value="">Semua Status</option>
        <?php foreach (['Hadir','Izin','Sakit','Alpa','Terlambat'] as $st): ?>
            <option value="<?= $st ?>" <?= $status===$st?'selected':'' ?>><?= $st ?></option>
        <?php endforeach; ?>
        </select>
        <div class="sm:col-span-5 flex items-center gap-2">
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
            <th class="text-left px-4 py-3">Tanggal</th>
            <th class="text-left px-4 py-3">Nama</th>
            <th class="text-left px-4 py-3">Kelas</th>
            <th class="text-left px-4 py-3">Status</th>
            <th class="text-left px-4 py-3">Catatan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
            <?php
            
            $rows = [];
            foreach (['rows','items','data','absensi'] as $k) {
                if (!empty($data_absensi[$k]) && is_array($data_absensi[$k])) { $rows = $data_absensi[$k]; break; }
            }
            
            function field($r, $cands, $default='-'){
                foreach ($cands as $c){ if (isset($r[$c]) && $r[$c] !== '') return $r[$c]; }
                return $default;
            }
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
            ?>
            <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $r): ?>
                <?php
                $tanggal = field($r, ['tanggal','date','tgl']);
                $nama    = field($r, ['nama','nama_lengkap','siswa_nama','student_name']);
                $kelas   = strtoupper(field($r, ['kelas','class','grade'], ''));
                $statusV = field($r, ['status','kehadiran','attendance']);
                $catatan = field($r, ['catatan','note','keterangan'], '-');
                ?>
                <tr class="hover:bg-white/5">
                <td class="px-4 py-3"><?= htmlspecialchars($tanggal) ?></td>
                <td class="px-4 py-3 font-medium"><?= htmlspecialchars($nama ?: '-') ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($kelas ?: '-') ?></td>
                <td class="px-4 py-3"><?= $statusV ? badge($statusV) : '-' ?></td>
                <td class="px-4 py-3 text-neutral-300"><?= htmlspecialchars($catatan) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-300">Belum ada data.</td></tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex items-center gap-2 flex-wrap">
        <?php
        for ($i = 1; $i <= ($data_absensi['total_pages_absen'] ?? 1); $i++) {
            $active = ($i == ($data_absensi['page_absen'] ?? 1)) ? "bg-white text-neutral-900" : "bg-white/10 text-white hover:bg-white/15";
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
