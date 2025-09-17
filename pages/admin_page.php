<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$database = new Database("localhost", "root", "", "absensi");
$conn = $database->getConnection();

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
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={theme:{extend:{boxShadow:{soft:"0 8px 30px rgba(0,0,0,.10)"}}}};</script>
  <meta name="color-scheme" content="light dark" />
</head>
<body class="min-h-dvh relative overflow-hidden bg-neutral-950 text-neutral-100">
  <div class="absolute inset-0 -z-10">
    <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2069&auto=format&fit=crop')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-neutral-950/70"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-black/50 via-black/20 to-black/50"></div>
  </div>
  <div class="min-h-dvh max-w-7xl mx-auto p-4 sm:p-6">
    
<!-- Header -->
<div class="mb-4 flex items-center justify-between gap-3">
  <div>
    <h1 class="text-2xl font-semibold leading-tight">Dashboard Admin</h1>
    <p class="text-sm text-neutral-300">Ringkasan kegiatan dan data terkini.</p>
  </div>
  <div class="hidden sm:flex items-center gap-2">
    <a href="../auth/logout.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-3 py-2 text-sm hover:bg-white/15">Logout</a>
  </div>
</div>

<?php
// Build quick stats based on current DB structure
date_default_timezone_set('Asia/Jakarta');
$today = date('Y-m-d');

function qcount($conn, $sql){
  $res = mysqli_query($conn, $sql);
  if($res && ($r=mysqli_fetch_row($res))) return (int)$r[0];
  return 0;
}

$total_siswa = qcount($conn, "SELECT COUNT(*) FROM users WHERE role='siswa'");
$kelasX = qcount($conn, "SELECT COUNT(*) FROM users WHERE role='siswa' AND kelas='X'");
$kelasXI = qcount($conn, "SELECT COUNT(*) FROM users WHERE role='siswa' AND kelas='XI'");
$kelasXII = qcount($conn, "SELECT COUNT(*) FROM users WHERE role='siswa' AND kelas='XII'");

$hadir_today      = qcount($conn, "SELECT COUNT(*) FROM absen WHERE DATE(tanggal)='$today' AND status='Hadir'");
$izin_today       = qcount($conn, "SELECT COUNT(*) FROM absen WHERE DATE(tanggal)='$today' AND status='Izin'");
$sakit_today      = qcount($conn, "SELECT COUNT(*) FROM absen WHERE DATE(tanggal)='$today' AND status='Sakit'");
$alfa_today       = qcount($conn, "SELECT COUNT(*) FROM absen WHERE DATE(tanggal)='$today' AND status IN ('Alfa','Alpa')");
$total_today = $hadir_today + $izin_today + $sakit_today + $alfa_today;
$pct_hadir = $total_today ? round($hadir_today/$total_today*100) : 0;

// Recent
$recent = [];
$rq = mysqli_query($conn, "SELECT id, nama_siswa, kelas, tanggal, status, COALESCE(keterangan,'') keterangan FROM absen ORDER BY id DESC LIMIT 8");
if($rq){ while($r=mysqli_fetch_assoc($rq)) $recent[]=$r; }

$admin_username = isset($_SESSION['username'])?$_SESSION['username']:'admin';
$admin_role = $_SESSION['role'] ?? 'admin';
?>

<!-- Row: Profile + Stats -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <!-- Profile -->
  <div class="lg:col-span-1 rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
    <div class="flex items-center gap-3">
      <div class="size-14 rounded-2xl bg-white/20 grid place-content-center text-white text-xl font-bold">
        <?= strtoupper(substr($admin_username,0,1)) ?>
      </div>
      <div>
        <div class="text-lg font-semibold"><?= htmlspecialchars($admin_username) ?></div>
        <div class="text-xs mt-1 inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white/10 border border-white/20">
          🛡️ <?= htmlspecialchars(ucfirst($admin_role)) ?>
        </div>
      </div>
    </div>
    <div class="mt-4 grid grid-cols-3 gap-2 text-center">
      <div class="rounded-xl border border-white/15 p-3">
        <div class="text-xs text-neutral-300">Siswa</div>
        <div class="text-lg font-semibold"><?= $total_siswa ?></div>
      </div>
      <div class="rounded-xl border border-white/15 p-3">
        <div class="text-xs text-neutral-300">Kelas X</div>
        <div class="text-lg font-semibold"><?= $kelasX ?></div>
      </div>
      <div class="rounded-xl border border-white/15 p-3">
        <div class="text-xs text-neutral-300">Kelas XI/XII</div>
        <div class="text-lg font-semibold"><?= $kelasXI + $kelasXII ?></div>
      </div>
    </div>
    <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
      <a href="data_siswa.php?" class="rounded-lg bg-white text-neutral-900 px-3 py-2 hover:bg-neutral-100 text-center">Data Kelas</a>
      <a href="data_absen.php" class="rounded-lg bg-white text-neutral-900 px-3 py-2 hover:bg-neutral-100 text-center">📋 Data Absen</a>
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-neutral-300">Hadir Hari Ini</div>
          <div class="text-2xl font-semibold"><?= $hadir_today ?></div>
        </div>
        <a href="data_absen.php?tanggal=<?= $today ?>&search_absen=&kelas_absen=&page_absen=1" class="rounded-lg border border-white/20 bg-white/10 px-3 py-2 hover:bg-white/15 text-sm">Lihat</a>
      </div>
      <div class="mt-3 h-2 rounded bg-white/10 border border-white/10">
        <div class="h-2 rounded bg-white" style="width: <?= $pct_hadir ?>%"></div>
      </div>
    </div>

    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-neutral-300">Izin/Sakit</div>
          <div class="text-2xl font-semibold"><?= $izin_today + $sakit_today ?></div>
        </div>
        <div class="text-xs text-neutral-300">Izin: <?= $izin_today ?> · Sakit: <?= $sakit_today ?></div>
      </div>
    </div>

    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-neutral-300">Alfa Hari Ini</div>
          <div class="text-2xl font-semibold"><?= $alfa_today ?></div>
        </div>
        <div class="text-xs text-neutral-300">Tanpa keterangan</div>
      </div>
    </div>

    <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-neutral-300">Total Hari Ini</div>
          <div class="text-2xl font-semibold"><?= $total_today ?></div>
        </div>
        <a href="data_absen.php?tanggal=<?= $today ?>" class="rounded-lg border border-white/20 bg-white/10 px-3 py-2 hover:bg-white/15 text-sm">Detail</a>
      </div>
    </div>
  </div>
</section>

<!-- Recent Activity -->
<section class="mt-4 rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
  <div class="flex items-center justify-between mb-2">
    <h2 class="text-lg font-semibold">Aktivitas Terbaru</h2>
    <a href="data_absen.php" class="text-sm rounded-lg border border-white/20 bg-white/10 px-3 py-1.5 hover:bg-white/15">Semua</a>
  </div>
  <div class="overflow-auto rounded-xl border border-white/10 bg-white/5">
    <table class="min-w-[760px] w-full text-sm">
      <thead class="bg-white/10 text-neutral-200">
        <tr>
          <th class="text-left px-4 py-3">Tanggal</th>
          <th class="text-left px-4 py-3">Nama</th>
          <th class="text-left px-4 py-3">Kelas</th>
          <th class="text-left px-4 py-3">Status</th>
          <th class="text-left px-4 py-3">Keterangan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/10">
        <?php if(!empty($recent)): foreach($recent as $r): ?>
        <tr class="hover:bg-white/5">
          <td class="px-4 py-3"><?= htmlspecialchars($r['tanggal']) ?></td>
          <td class="px-4 py-3 font-medium"><?= htmlspecialchars($r['nama_siswa']) ?></td>
          <td class="px-4 py-3"><?= htmlspecialchars($r['kelas']) ?></td>
          <td class="px-4 py-3"><span class="px-2 py-1 rounded-md text-xs font-medium border border-white/20 bg-white/10"><?= htmlspecialchars($r['status']) ?></span></td>
        <td class="px-4 py-3 text-neutral-300"><?= htmlspecialchars($r['keterangan']) ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-300">Belum ada aktivitas.</td></tr>
        <?php endif; ?>
    </tbody>
    </table>
</div>
</section>

    <div class="mt-6 text-xs text-neutral-300">© Kelola.Biz</div>
</div>
</body>
</html>