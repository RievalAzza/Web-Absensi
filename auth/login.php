<?php
session_start();


if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../pages/admin_page.php");
        exit;
    } elseif ($_SESSION['role'] == 'siswa') {
        header("Location: ../pages/siswa_page.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login — Absensi</title>


<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
    theme: {
        extend: {
        boxShadow: {
            soft: "0 8px 30px rgba(0,0,0,.10)"
        }
        }
    }
    }
</script>

<meta name="color-scheme" content="light dark" />
</head>
<body class="min-h-dvh relative overflow-hidden bg-neutral-950 text-neutral-100">

<!-- Background image + overlay (lebih gelap) -->
<div class="absolute inset-0 -z-10">
    <!-- Ganti URL berikut dengan gambar kamu sendiri jika perlu -->
    <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1545665277-5937489579c6?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
    <!-- Overlay gelap -->
    <div class="absolute inset-0 bg-neutral-950/70"></div>
    <!-- Grain/gradient halus untuk kedalaman -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/50 via-black/20 to-black/50"></div>
</div>

<!-- Container -->
<div class="min-h-dvh grid place-items-center p-4">
    <div class="w-full max-w-md">
    <!-- Brand / Logo kecil -->
    <div class="mb-4 flex items-center gap-3">
        <div class="size-10 rounded-xl bg-white/10 backdrop-blur grid place-content-center text-white font-bold">AB</div>
        <div class="text-lg font-semibold">Absensi</div>
    </div>

    <!-- Card -->
    <div class="rounded-2xl bg-white/10 backdrop-blur-md shadow-soft border border-white/15 p-6">
        <div class="mb-4">
        <h1 class="text-2xl font-semibold leading-tight">Masuk</h1>
        <p class="text-sm text-neutral-300 mt-1">Silakan login untuk melanjutkan.</p>
        </div>

        <?php if (!empty($_GET['err'])): ?>
        <div class="mb-3 rounded-xl border border-red-500/30 bg-red-500/15 text-red-100 px-4 py-3">
            <?= htmlspecialchars($_GET['err']) === '1' ? 'Username atau password salah.' : 'Terjadi kesalahan.' ?>
        </div>
        <?php endif; ?>
        <form method="post" action="../controller/proses_login.php" class="space-y-4">
        <div>
            <label class="block text-sm mb-1" for="username">Username</label>
            <input id="username" name="username" placeholder="Masukkan username" required
            class="w-full rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70 focus:border-white" />
        </div>

        <div>
            <label class="block text-sm mb-1" for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="••••••••" required
            class="w-full rounded-lg bg-white/90 text-neutral-900 placeholder-neutral-500 border border-white/50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-white/70 focus:border-white" />
        </div>

        <button type="submit"
                class="w-full rounded-lg px-4 py-2 text-sm font-medium bg-white text-neutral-900 hover:bg-neutral-100 active:bg-neutral-200 transition">
            Login
        </button>
        </form>

        <!-- Bantuan kecil -->
        <div class="mt-4 text-[13px] text-neutral-300 leading-relaxed">
            <code>bg-neutral-950/70</code> di atas.
        </div>
    </div>

    <!-- Footer kecil -->
    <div class="mt-4 text-xs text-neutral-300">
        © Kelola.Biz • Tampilan login gelap untuk kenyamanan visual
    </div>
    </div>
</div>
</body>
</html>
