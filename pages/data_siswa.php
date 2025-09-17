<?php
require_once '../classes/DataSiswaController.php';

$controller = new DataSiswaController();
$data = $controller->index(); 

// Extract data dari controller
$students = $data['students'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
$kelas_list = $data['kelas_list'];
$current_kelas = $data['current_kelas'];
$current_nama = $data['current_nama'];
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={theme:{extend:{boxShadow:{soft:"0 8px 30px rgba(0,0,0,.10)"}}}};</script>
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
    <h1 class="text-2xl font-semibold leading-tight">Data Siswa</h1>
    <p class="text-sm text-neutral-300">Daftar siswa berdasarkan kelas dan pencarian.</p>
  </div>
  <div class="hidden sm:flex items-center gap-2">
    <a href="admin_page.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-3 py-2 text-sm hover:bg-white/15">Dashboard</a>
  </div>
</div>

<section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4 mb-4">
  <form method="get" class="grid grid-cols-1 sm:grid-cols-5 gap-2">
    <input type="hidden" name="page_siswa" value="1">
    
    <select name="kelas" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
      <option value="">Semua Kelas</option>
      <?php foreach ($kelas_list as $kelas): ?>
        <option value="<?= htmlspecialchars($kelas) ?>" <?= $kelas == $current_kelas ? 'selected' : '' ?>>
          <?= htmlspecialchars($kelas) ?>
        </option>
      <?php endforeach; ?>
    </select>
    
    <input name="nama" value="<?= htmlspecialchars($current_nama) ?>" class="sm:col-span-2 rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" placeholder="Cari nama siswa…">
    
    <div class="sm:col-span-2 flex items-center gap-2">
      <button class="rounded-lg bg-white text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-100">Terapkan</button>
      <a href="data_siswa.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">Reset</a>
      <a href="tambah_data.php?kelas=<?= urlencode($current_kelas ?: 'X') ?>" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">+ Tambah</a>
    </div>
  </form>
</section>

<section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
  <div class="overflow-auto rounded-xl border border-white/10 bg-white/5">
    <table class="min-w-[720px] w-full text-sm">
      <thead class="bg-white/10 text-neutral-200">
        <tr>
          <th class="text-left px-4 py-3">#</th>
          <th class="text-left px-4 py-3">Nama Lengkap</th>
          <th class="text-left px-4 py-3">Kelas</th>
          <th class="text-left px-4 py-3">Role</th>
          <th class="text-right px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/10">
        <?php
        $limit_siswa = 12;
        $offset_siswa = ($current_page - 1) * $limit_siswa;
        $no = $offset_siswa + 1;
        ?>
        <?php if (!empty($students)): ?>
          <?php foreach ($students as $row): ?>
            <tr class="hover:bg-white/5">
              <td class="px-4 py-3"><?= $no++ ?></td>
              <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['username']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['kelas']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['role']) ?></td>
              <td class="px-4 py-3 text-right">
                <div class="flex justify-end gap-2">
                  <a href="edit_data.php?id=<?= $row['id'] ?>" class="rounded-lg border border-white/20 bg-white/10 px-3 py-1.5 hover:bg-white/15">Edit</a>
                  <a href="../controller/hapus_data.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')" class="rounded-lg border border-red-500/20 bg-red-500/10 px-3 py-1.5 hover:bg-red-500/15 text-red-300">Hapus</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-300">Tidak ada data siswa yang ditemukan.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination Sederhana -->
  <?php if ($total_pages > 1): ?>
  <div class="mt-4 flex items-center justify-center gap-2 flex-wrap">
    <?php if ($current_page > 1): ?>
      <a href="?<?= http_build_query(array_merge($_GET, ['page_siswa' => $current_page - 1])) ?>" class="px-3 py-1.5 rounded bg-white/10 text-white hover:bg-white/15 border border-white/20">
        &laquo; Prev
      </a>
    <?php endif; ?>
    
    <span class="px-3 py-1.5 text-neutral-300">Halaman <?= $current_page ?> dari <?= $total_pages ?></span>
    
    <?php if ($current_page < $total_pages): ?>
      <a href="?<?= http_build_query(array_merge($_GET, ['page_siswa' => $current_page + 1])) ?>" class="px-3 py-1.5 rounded bg-white/10 text-white hover:bg-white/15 border border-white/20">
        Next &raquo;
      </a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</section>

    <div class="mt-6 text-xs text-neutral-300">© Kelola.Biz</div>
  </div>
</body>
</html>