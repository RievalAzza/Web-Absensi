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
    <title>Document</title>
</head>
<body>
    <form action="../controller/proses_login.php" method="post">
        <h2>Login</h2>
        <input type="text" name="username", placeholder="Nama Lengkap" required> <br>
        <input type="password" name="password", placeholder="Password" required> <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>