<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Admin</title>
</head>
<body>

<?php
$no_admin = 1; 
$nosis_x = 1;
$nosis_xi = 1;
$nosis_xii = 1;
?>
    <h1>Selamat Datang Admin, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Ini adalah halaman admin. Anda dapat melihat semua data di sini.</p>
    <p><a href="../auth/logout.php">Logout</a></p>

    <!-- Data Admin -->
    <h2>Data Admin</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM admin");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$no_admin}</td>
                <td>{$row['nama']}</td>
            </tr>";
            $no_admin++;
        }
        ?>
    </table>

    <!-- Data Siswa -->
    <h2>Data Siswa Kelas X</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Kelas</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM siswa_x");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$nosis_x}</td>
                <td>{$row['nama_lengkap']}</td>
                <td>{$row['kelas']}</td>
            </tr>";
            $nosis_x++;
        }
        ?>
    </table>

    <h2>Data Siswa Kelas XI</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Kelas</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM siswa_xi");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$nosis_xi}</td>
                <td>{$row['nama_lengkap']}</td>
                <td>{$row['kelas']}</td>
            </tr>";
            $nosis_xi++;
        }
        ?>
    </table>

    <h2>Data Siswa Kelas XII</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Kelas</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM siswa_xii");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$nosis_xii}</td>
                <td>{$row['nama_lengkap']}</td>
                <td>{$row['kelas']}</td>
            </tr>";
            $nosis_xii++;
        }
        ?>
    </table>

    <!-- Data Absensi -->
    <h2>Data Absensi</h2>
    <form method="GET" action="">
        <label>Kelas:</label>
        <select name="kelas">
            <option value="">Semua</option>
            <option value="X" <?= isset($_GET['kelas']) && $_GET['kelas']=='X'?'selected':''; ?>>X</option>
            <option value="XI" <?= isset($_GET['kelas']) && $_GET['kelas']=='XI'?'selected':''; ?>>XI</option>
            <option value="XII" <?= isset($_GET['kelas']) && $_GET['kelas']=='XII'?'selected':''; ?>>XII</option>
        </select>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">

        <label>Cari Nama:</label>
        <input type="text" name="search" placeholder="Cari nama..." value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">

        <button type="submit">Filter</button>
    </form>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Tanggal</th>
            <th>Status</th>
        </tr>
        <?php
        $kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';
        $tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
        $search_filter = isset($_GET['search']) ? $_GET['search'] : '';

        $sql = "SELECT * FROM absen WHERE 1=1";

        if ($kelas_filter != '') {
            $sql .= " AND kelas = '$kelas_filter'";
        }

        if ($tanggal_filter != '') {
            $sql .= " AND tanggal = '$tanggal_filter'";
        }

        if ($search_filter != '') {
            $sql .= " AND nama_siswa LIKE '%$search_filter%'";
        }

        $result = mysqli_query($conn, $sql);
        $no_absen = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$no_absen}</td>
                <td>{$row['nama_siswa']}</td>
                <td>{$row['kelas']}</td>
                <td>{$row['tanggal']}</td>
                <td>{$row['status']}</td>
            </tr>";
            $no_absen++;
        }
        ?>
    </table>

</body>
</html>
