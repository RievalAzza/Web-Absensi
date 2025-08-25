<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Ambil parameter id user
$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("ID tidak valid");
}

// Ambil data lama dari tabel users
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan");
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $kelas_input = $_POST['kelas'];
    $pass = !empty($_POST['password']) ? md5($_POST['password']) : $data['password'];

    $sql = "UPDATE users 
            SET username='$username', kelas='$kelas_input', password='$pass' 
            WHERE id='$id'";
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
  <title>Edit Data</title>
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
    <h1 class="text-2xl font-semibold leading-tight">Edit Data Siswa</h1>
    <p class="text-sm text-neutral-300">Perbarui informasi pengguna.</p>
  </div>
  <div class="hidden sm:flex items-center gap-2">
    <a href="admin_page.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-3 py-2 text-sm hover:bg-white/15">Dashboard</a>
  </div>
</div>

<?php
// Candidate arrays resolver for flexible variable names
$__candidates = [];
if (isset($row) && is_array($row)) $__candidates[] = $row;
if (isset($user) && is_array($user)) $__candidates[] = $user;
if (isset($data) && is_array($data)) $__candidates[] = $data;
function __val($__cands, $key, $default=''){ foreach($__cands as $c){ if(isset($c[$key])) return $c[$key]; } return $default; }
?>

<section class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md shadow-soft p-4">
  <?php if (!empty($_GET['ok'])): ?>
    <div class="mb-3 rounded-xl border border-green-500/30 bg-green-500/15 text-green-100 px-4 py-3">Data berhasil diperbarui.</div>
  <?php endif; ?>
  <?php if (!empty($_GET['err'])): ?>
    <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/15 text-red-100 px-4 py-3">Terjadi kesalahan.</div>
  <?php endif; ?>

  <form method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
      <label class="block text-sm mb-1" for="username">Username</label>
      <input id="username" name="username" value="<?= htmlspecialchars(__val($__candidates,'username','')) ?>" required
             class="w-full rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
    </div>
    <div>
      <label class="block text-sm mb-1" for="kelas">Kelas</label>
      <?php $kelas_val = __val($__candidates,'kelas',''); ?>
      <select id="kelas" name="kelas" class="w-full rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <?php foreach (['X','XI','XII','-'] as $k): ?>
          <option value="<?= $k ?>" <?= $kelas_val===$k?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm mb-1" for="role">Role</label>
      <?php $role_val = __val($__candidates,'role','siswa'); ?>
      <select id="role" name="role" class="w-full rounded-lg bg-white/90 text-neutral-900 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
        <?php foreach (['siswa','admin'] as $r): ?>
          <option value="<?= $r ?>" <?= $role_val===$r?'selected':'' ?>><?= ucfirst($r) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm mb-1" for="password">Password (opsional)</label>
      <input id="password" type="password" name="password" placeholder="Kosongkan jika tidak diubah"
             class="w-full rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70">
    </div>

    <div class="sm:col-span-2 pt-2 flex items-center justify-end gap-2">
      <a href="data_siswa.php" class="rounded-lg border border-white/20 bg-white/10 backdrop-blur px-4 py-2 text-sm hover:bg-white/15">Batal</a>
      <button type="submit" class="rounded-lg bg-white text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-100">Update</button>
    </div>
  </form>
</section>

    <div class="mt-6 text-xs text-neutral-300">© Kelola.Biz</div>
  </div>
</body>
</html>