<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Halaman Siswa</title>
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
    
<div class="max-w-md mx-auto rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-6 mt-8">
  <div class="mb-4">
    <h1 class="text-2xl font-semibold">Halaman Siswa</h1>
    <p class="text-sm text-neutral-300">Silakan lakukan absensi.</p>
  </div>

  <form method="post" action="../controller/proses_absen.php" class="space-y-3">
    <div>
      <label class="block text-sm mb-1" for="status">Status</label>
      <select id="status" name="status" class="w-full rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" required>
        <option value="Hadir">Hadir</option>
        <option value="Sakit">Sakit</option>
        <option value="Izin">Izin</option>
      </select>
      <p class="text-xs text-neutral-300 mt-1">Otomatis <em>Terlambat</em> jika jam masuk terlewati.</p>
    </div>
    <button type="submit" class="w-full rounded-lg bg-white text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-100">Absen Sekarang</button>
  </form>

  <p class="mt-6 text-center">
    <a href="../auth/logout.php" class="text-red-200 hover:underline">Logout</a>
  </p>
</div>

    <div class="mt-6 text-xs text-neutral-300">© Kelola.Biz</div>
  </div>
</body>
</html>