<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

// Ambil kelas dari query string (x, xi, xii)
$kelas = $_GET['kelas'] ?? '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $kelas_input = mysqli_real_escape_string($conn, $_POST['kelas']);
    $pass = md5($_POST['password']);

    // Insert ke tabel users
    $sql = "INSERT INTO users (username, password, kelas, role) 
            VALUES ('$username', '$pass', '$kelas_input', 'siswa')";
    mysqli_query($conn, $sql);

    header("Location: admin_page.php");
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tambah Data</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={theme:{extend:{boxShadow:{soft:"0 8px 30px rgba(0,0,0,.10)"}}}};</script>
  <meta name="color-scheme" content="light dark" />
</head>
<body class="min-h-dvh relative overflow-hidden bg-neutral-950 text-neutral-100">
  <div class="absolute inset-0 -z-10">
    <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=2067&auto=format&fit=crop')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-neutral-950/70"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-black/50 via-black/20 to-black/50"></div>
  </div>
  <div class="min-h-dvh max-w-7xl mx-auto p-4 sm:p-6">
    
<div class="mb-4 flex items-center justify-between gap-3">
  <div>
    <h1 class="text-2xl font-semibold leading-tight">Tambah Data Siswa</h1>
    <p class="text-sm text-neutral-300">Masukkan informasi siswa dengan benar.</p>
  </div>
  <div class="hidden sm:flex items-center gap-2">
    <a href="admin_page.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-3 py-2 text-sm hover:bg-white/15">Dashboard</a>
  </div>
</div>

<section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
  <form method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm mb-1" for="username">Username</label>
      <input id="username" name="username" required
             class="w-full rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" placeholder="cth: Wahyu">
    </div>
    <div>
      <label class="block text-sm mb-1" for="kelas">Kelas</label>
      <select id="kelas" name="kelas" class="w-full rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <?php foreach (['X','XI','XII','-'] as $k): ?>
          <option value="<?= $k ?>" <?= (($_GET['kelas'] ?? '')===$k)?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="sm:col-span-2">
      <label class="block text-sm mb-1" for="password">Password</label>
      <input id="password" type="password" name="password" required
             class="w-full rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70" placeholder="Minimal 6 karakter">
      <p class="mt-1 text-[12px] text-neutral-300">Catatan: saat ini tersimpan MD5 sesuai logic asli.</p>
    </div>
    <div class="sm:col-span-2 pt-2 flex items-center justify-end gap-2">
      <a href="data_siswa.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">Batal</a>
      <button type="submit" class="rounded-lg bg-white text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-100">Simpan</button>
    </div>
  </form>
</section>

    <div class="mt-6 text-xs text-neutral-300">© Kelola.Biz</div>
  </div>
</body>
</html>