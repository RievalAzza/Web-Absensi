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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center flex items-center justify-center" 
    style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1740&q=80');">

    <div class="bg-white/20 backdrop-blur-md rounded-xl shadow-lg p-8 w-full max-w-sm border border-white/30">
        <h2 class="text-3xl font-bold text-white mb-6 text-center drop-shadow-lg">Login</h2>
        
        <form action="../controller/proses_login.php" method="post">
            <input type="text" name="username" placeholder="Nama Lengkap" required
                class="w-full px-4 py-2 mb-4 bg-white/30 border border-white/40 rounded-lg text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 backdrop-blur-sm">
            
            <input type="password" name="password" placeholder="Password" required
                class="w-full px-4 py-2 mb-6 bg-white/30 border border-white/40 rounded-lg text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 backdrop-blur-sm">
            
            <button type="submit"
                    class="w-full py-2 bg-blue-500/80 hover:bg-blue-600/90 text-white font-semibold rounded-lg transition-colors duration-300">
                Login
            </button>
        </form>
    </div>

</body>
</html>
