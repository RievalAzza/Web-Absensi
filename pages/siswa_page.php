<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Siswa</title>
</head>
<body>
    <h1>Halo, <?php echo $_SESSION['username']; ?> (Kelas <?php echo $_SESSION['kelas']; ?>)</h1>
    <form action="../controller/proses_absen.php" method="POST">
        <label>Status Kehadiran:</label>
        <select name="status" required>
            <option value="Hadir">Hadir</option>
            <option value="Sakit">Sakit</option>
            <option value="Izin">Izin</option>
        </select>
        <br><br>
        <button type="submit">Absen Sekarang</button>
    </form>
    <p><a href="../auth/logout.php">Logout</a></p>
</body>
</html>
