<?php 
$data_siswa = include '../controller/DataSiswaController.php';
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
  <?php
    $kelas  = $_GET['kelas'] ?? '';
    $search = $_GET['search'] ?? '';
  ?>
  <form method="get" class="grid grid-cols-1 sm:grid-cols-5 gap-2">
    <select name="kelas" class="rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
      <option value="">Semua Kelas</option>
      <?php foreach (['X','XI','XII'] as $k): ?>
        <option value="<?= $k ?>" <?= $kelas===$k?'selected':'' ?>><?= $k ?></option>
      <?php endforeach; ?>
    </select>
    <input name="search" value="<?= htmlspecialchars($search) ?>" class="sm:col-span-2 rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" placeholder="Cari nama siswa…">
    <div class="sm:col-span-2 flex items-center gap-2">
      <button class="rounded-lg bg-white text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-100">Terapkan</button>
      <a href="data_siswa.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">Reset</a>
      <a href="tambah_data.php?kelas=<?= urlencode($kelas ?: 'X') ?>" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">+ Tambah</a>
    </div>
  </form>
</section>

<section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
  <div class="overflow-auto rounded-xl border border-white/10 bg-white/5">
    <table class="min-w-[720px] w-full text-sm">
      <thead class="bg-white/10 text-neutral-200">
        <tr>
          <th class="text-left px-4 py-3">#</th>
          <th class="text-left px-4 py-3">Username</th>
          <th class="text-left px-4 py-3">Kelas</th>
          <th class="text-left px-4 py-3">Role</th>
          <th class="text-right px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/10">
        <?php $no = ($data_siswa['offset_siswa'] ?? 0) + 1; ?>
        <?php if (!empty($data_siswa['result_siswa'])): ?>
          <?php while ($row = mysqli_fetch_assoc($data_siswa['result_siswa'])): ?>
            <tr class="hover:bg-white/5">
              <td class="px-4 py-3"><?= $no++ ?></td>
              <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['username']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['kelas']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['role']) ?></td>
              <td class="px-4 py-3 text-right">
                <a href="edit_data.php?id=<?= $row['id'] ?>" class="rounded-lg border border-white/20 bg-white/10 px-3 py-1.5 hover:bg-white/15">Edit</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-300">Belum ada data.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="mt-4 flex items-center gap-2 flex-wrap">
    <?php
    for ($i = 1; $i <= ($data_siswa['total_pages_siswa'] ?? 1); $i++) { 
        $active = ($i == ($data_siswa['page_siswa'] ?? 1)) ? "bg-white text-neutral-900" : "bg-white/10 text-white hover:bg-white/15";
        $url_params = $_GET;
        $url_params['page_siswa'] = $i;
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