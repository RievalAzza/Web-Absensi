<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

/* Helper functions (safe, not intrusive) */
function table_exists($conn, $name) {
    $name_esc = mysqli_real_escape_string($conn, $name);
    $res = mysqli_query($conn, "SHOW TABLES LIKE '$name_esc'");
    return $res && mysqli_num_rows($res) > 0;
}
function quick_count($conn, $table, $where = '') {
    if (!table_exists($conn, $table)) return 0;
    $sql = "SELECT COUNT(*) AS c FROM `$table`" . ($where ? " WHERE $where" : '');
    $res = mysqli_query($conn, $sql);
    if ($res && ($row = mysqli_fetch_assoc($res))) return (int)$row['c'];
    return 0;
}

/* Derive basic stats (best-effort; will fallback to 0 if table/fields differ) */
$total_siswa = 0;
foreach (['siswa_x','siswa_xi','siswa_xii'] as $t) {
    $total_siswa += quick_count($conn, $t);
}

$hadir_hari_ini = 0;
$izin_sakit_hari_ini = 0;
$alpa_hari_ini = 0;
$terlambat_hari_ini = 0;
$today = date('Y-m-d');

if (table_exists($conn, 'absensi')) {
    $hadir_hari_ini        = quick_count($conn, 'absensi', "tanggal = '$today' AND status = 'Hadir'");
    $izin_hari_ini         = quick_count($conn, 'absensi', "tanggal = '$today' AND status = 'Izin'");
    $sakit_hari_ini        = quick_count($conn, 'absensi', "tanggal = '$today' AND status = 'Sakit'");
    $alpa_hari_ini         = quick_count($conn, 'absensi', "tanggal = '$today' AND status = 'Alpa'");
    $terlambat_hari_ini    = quick_count($conn, 'absensi', "tanggal = '$today' AND status = 'Terlambat'");
    $izin_sakit_hari_ini   = $izin_hari_ini + $sakit_hari_ini;
}

/* Recent absensi (last 8) */
$recent = [];
if (table_exists($conn, 'absensi')) {
    $q = mysqli_query($conn, "SELECT id, tanggal, siswa_id, status, COALESCE(catatan,'') AS catatan FROM absensi ORDER BY id DESC LIMIT 8");
    if ($q) while ($r = mysqli_fetch_assoc($q)) $recent[] = $r;
}

/* Admin session data (best-effort) */
$admin_username = isset($_SESSION['username']) ? $_SESSION['username'] : 'admin';
$admin_name     = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : ucfirst($admin_username);
$admin_email    = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$role           = isset($_SESSION['role']) ? $_SESSION['role'] : 'admin';

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:"#eef2ff",100:"#e0e7ff",200:"#c7d2fe",300:"#a5b4fc",400:"#818cf8",
              500:"#6366f1",600:"#5458d6",700:"#4348b2",800:"#363a8d",900:"#2a2e6f"
            }
          },
          boxShadow: { soft:"0 8px 30px rgba(0,0,0,0.08)" }
        }
      }
    }
  </script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-neutral-50 text-neutral-900">
  <div class="min-h-dvh flex">
    <!-- Sidebar -->
    <aside class="hidden md:flex w-72 flex-col gap-2 border-r bg-white/80 backdrop-blur px-3 py-4">
      <div class="flex items-center gap-2 px-2 py-3">
        <div class="size-9 rounded-xl bg-brand-500/10 grid place-content-center text-brand-600 font-bold">AB</div>
        <div>
          <div class="font-semibold leading-tight">Absensi</div>
          <div class="text-xs text-neutral-500">Panel Admin</div>
        </div>
      </div>
      <nav class="mt-2 flex-1 text-sm">
        <a href="admin_page.php" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-neutral-100">🏠 Dashboard</a>
        <a href="absensi_list.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neutral-100">🗓️ Data Absensi</a>
        <a href="siswa_list.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neutral-100">👥 Data Siswa</a>
        <a href="rekap.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neutral-100">📊 Rekap</a>
        <a href="../auth/logout.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neutral-100 text-red-600">🚪 Logout</a>
      </nav>
      <div class="mt-auto text-xs text-neutral-500 px-2 pb-2">© Kelola.Biz</div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <header class="sticky top-0 z-10 border-b bg-white/80 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button class="md:hidden inline-flex items-center px-3 py-2 rounded-lg border hover:bg-neutral-50">☰</button>
            <h1 class="text-lg font-semibold">Dashboard</h1>
          </div>
          <div class="flex items-center gap-2" x-data="{open:false}">
            <div class="hidden md:flex items-center gap-2 rounded-lg border px-3 py-2 bg-white">
              <span class="text-neutral-400">🔎</span>
              <input type="search" placeholder="Cari cepat…" class="outline-none text-sm placeholder-neutral-400">
            </div>
            <!-- Admin avatar / profile dropdown -->
            <button @click="open=!open" class="size-9 rounded-full bg-brand-500/10 grid place-content-center">
              <?= strtoupper(substr($admin_name,0,1)) ?>
            </button>
            <div x-show="open" @click.outside="open=false" x-transition
                class="absolute right-4 top-14 w-64 rounded-xl border bg-white shadow-soft p-3">
              <div class="flex items-center gap-3">
                <div class="size-10 rounded-full bg-brand-500/10 grid place-content-center text-brand-700">
                  <?= strtoupper(substr($admin_name,0,1)) ?>
                </div>
                <div>
                  <div class="font-medium leading-tight"><?= htmlspecialchars($admin_name) ?></div>
                  <div class="text-xs text-neutral-500"><?= htmlspecialchars($admin_email ?: '@'.$admin_username) ?></div>
                </div>
              </div>
              <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                <a href="admin_profile.php" class="rounded-lg border px-3 py-2 hover:bg-neutral-50">Profil</a>
                <a href="../auth/logout.php" class="rounded-lg border px-3 py-2 hover:bg-neutral-50 text-red-600">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page container -->
      <main class="mx-auto w-full max-w-7xl p-4 space-y-4">
        <!-- Row: Profile + Quick actions -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Admin Profile Card -->
          <div class="lg:col-span-1 rounded-2xl border bg-white p-4 shadow-soft">
            <div class="flex items-center gap-3">
              <div class="size-14 rounded-2xl bg-brand-500/10 grid place-content-center text-brand-700 text-xl font-bold">
                <?= strtoupper(substr($admin_name,0,1)) ?>
              </div>
              <div>
                <div class="text-lg font-semibold"><?= htmlspecialchars($admin_name) ?></div>
                <div class="text-sm text-neutral-500"><?= htmlspecialchars($admin_email ?: '@'.$admin_username) ?></div>
                <div class="text-xs mt-1 inline-flex items-center gap-1 px-2 py-1 rounded-md bg-neutral-100">
                  🛡️ <?= htmlspecialchars(ucfirst($role)) ?>
                </div>
              </div>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
              <div class="rounded-xl border p-3">
                <div class="text-xs text-neutral-500">Total Siswa</div>
                <div class="text-lg font-semibold"><?= $total_siswa ?></div>
              </div>
              <div class="rounded-xl border p-3">
                <div class="text-xs text-neutral-500">Hadir Hari Ini</div>
                <div class="text-lg font-semibold"><?= $hadir_hari_ini ?></div>
              </div>
              <div class="rounded-xl border p-3">
                <div class="text-xs text-neutral-500">Alpa Hari Ini</div>
                <div class="text-lg font-semibold"><?= $alpa_hari_ini ?></div>
              </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
              <a href="absensi_form.php" class="rounded-lg bg-brand-600 text-white px-3 py-2 hover:bg-brand-700 text-center">+ Input Absensi</a>
              <a href="tambah_siswa.php?kelas=xi" class="rounded-lg border px-3 py-2 hover:bg-neutral-50 text-center">+ Tambah Siswa</a>
            </div>
          </div>

          <!-- Quick Panels -->
          <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border bg-white p-4 shadow-soft">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-neutral-500">Hadir Hari Ini</div>
                  <div class="text-2xl font-semibold"><?= $hadir_hari_ini ?></div>
                </div>
                <a href="absensi_list.php?status=Hadir&from=<?= $today ?>&to=<?= $today ?>" class="rounded-lg border px-3 py-2 hover:bg-neutral-50 text-sm">Lihat</a>
              </div>
              <div class="mt-3 h-2 rounded bg-neutral-100">
                <?php $total_today = $hadir_hari_ini + $izin_sakit_hari_ini + $alpa_hari_ini + $terlambat_hari_ini; $pct = $total_today ? round($hadir_hari_ini/$total_today*100) : 0; ?>
                <div class="h-2 rounded bg-brand-500" style="width: <?= $pct ?>%"></div>
              </div>
            </div>

            <div class="rounded-2xl border bg-white p-4 shadow-soft">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-neutral-500">Izin/Sakit Hari Ini</div>
                  <div class="text-2xl font-semibold"><?= $izin_sakit_hari_ini ?></div>
                </div>
                <a href="absensi_list.php?status=Izin&from=<?= $today ?>&to=<?= $today ?>" class="rounded-lg border px-3 py-2 hover:bg-neutral-50 text-sm">Lihat</a>
              </div>
              <div class="mt-3 text-xs text-neutral-500">Termasuk Sakit + Izin</div>
            </div>

            <div class="rounded-2xl border bg-white p-4 shadow-soft">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-neutral-500">Alpa Hari Ini</div>
                  <div class="text-2xl font-semibold"><?= $alpa_hari_ini ?></div>
                </div>
                <a href="absensi_list.php?status=Alpa&from=<?= $today ?>&to=<?= $today ?>" class="rounded-lg border px-3 py-2 hover:bg-neutral-50 text-sm">Lihat</a>
              </div>
              <div class="mt-3 text-xs text-neutral-500">Pantau ketidakhadiran tanpa keterangan.</div>
            </div>

            <div class="rounded-2xl border bg-white p-4 shadow-soft">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-neutral-500">Terlambat Hari Ini</div>
                  <div class="text-2xl font-semibold"><?= $terlambat_hari_ini ?></div>
                </div>
                <a href="absensi_list.php?status=Terlambat&from=<?= $today ?>&to=<?= $today ?>" class="rounded-lg border px-3 py-2 hover:bg-neutral-50 text-sm">Lihat</a>
              </div>
              <div class="mt-3 text-xs text-neutral-500">Datang melewati jam masuk.</div>
            </div>
          </div>
        </section>

        <!-- Row: Recent activity + Shortcuts + Announcements -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Recent absensi -->
          <div class="lg:col-span-2 rounded-2xl border bg-white p-4 shadow-soft">
            <div class="flex items-center justify-between mb-2">
              <h2 class="text-lg font-semibold">Aktivitas Terbaru</h2>
              <a href="absensi_list.php" class="text-sm rounded-lg border px-3 py-1.5 hover:bg-neutral-50">Semua</a>
            </div>
            <div class="overflow-auto rounded-xl border bg-white">
              <table class="min-w-[720px] w-full text-sm">
                <thead class="bg-neutral-50 text-neutral-600">
                  <tr>
                    <th class="text-left px-4 py-3">Tanggal</th>
                    <th class="text-left px-4 py-3">Siswa ID</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Catatan</th>
                    <th class="text-right px-4 py-3">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y">
                  <?php if (!empty($recent)): ?>
                    <?php foreach ($recent as $r): ?>
                      <tr class="hover:bg-neutral-50">
                        <td class="px-4 py-3"><?= htmlspecialchars($r['tanggal']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($r['siswa_id']) ?></td>
                        <td class="px-4 py-3">
                          <?php
                            $map = [
                              'Hadir' => 'bg-green-100 text-green-700',
                              'Izin' => 'bg-blue-100 text-blue-700',
                              'Sakit' => 'bg-amber-100 text-amber-700',
                              'Alpa' => 'bg-red-100 text-red-700',
                              'Terlambat' => 'bg-purple-100 text-purple-700',
                            ];
                            $cls = $map[$r['status']] ?? 'bg-neutral-100 text-neutral-700';
                          ?>
                          <span class="px-2 py-1 rounded-md text-xs font-medium <?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span>
                        </td>
                        <td class="px-4 py-3 text-neutral-600"><?= htmlspecialchars($r['catatan']) ?></td>
                        <td class="px-4 py-3 text-right">
                          <div class="inline-flex items-center gap-1">
                            <a href="absensi_edit.php?id=<?= $r['id'] ?>" class="rounded-lg border px-3 py-1.5 hover:bg-neutral-50">Edit</a>
                            <a href="absensi_delete.php?id=<?= $r['id'] ?>" class="rounded-lg border px-3 py-1.5 hover:bg-neutral-50 text-red-600" onclick="return confirm('Hapus data ini?')">Hapus</a>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-500">Belum ada aktivitas.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Shortcuts & Announcements -->
          <div class="lg:col-span-1 space-y-4">
            <div class="rounded-2xl border bg-white p-4 shadow-soft">
              <h3 class="text-base font-semibold mb-2">Aksi Cepat</h3>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <a href="absensi_form.php" class="rounded-lg border px-3 py-2 hover:bg-neutral-50">+ Absensi</a>
                <a href="tambah_siswa.php?kelas=xi" class="rounded-lg border px-3 py-2 hover:bg-neutral-50">+ Siswa</a>
                <a href="export_csv.php?type=absensi" class="rounded-lg border px-3 py-2 hover:bg-neutral-50">⬇️ Export</a>
                <a href="rekap.php" class="rounded-lg border px-3 py-2 hover:bg-neutral-50">📊 Rekap</a>
              </div>
            </div>

            <div class="rounded-2xl border bg-white p-4 shadow-soft">
              <h3 class="text-base font-semibold mb-2">Pengumuman</h3>
              <ul class="space-y-2 text-sm">
                <li class="rounded-lg border px-3 py-2">Tidak ada pengumuman.</li>
              </ul>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</body>
</html>
