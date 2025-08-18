<?php
include '../config/db.php'; // koneksi database

// Pagination
$limit_siswa = 10;
$page_siswa = isset($_GET['page_siswa']) ? (int)$_GET['page_siswa'] : 1;
if ($page_siswa < 1) $page_siswa = 1;
$offset_siswa = ($page_siswa - 1) * $limit_siswa;

// Filter
$kelas_filter  = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';

// Query dasar
$sql_siswa = "SELECT * FROM users WHERE role='siswa'";
if ($kelas_filter != '') {
    $sql_siswa .= " AND kelas='$kelas_filter'";
}
if ($search_filter != '') {
    $sql_siswa .= " AND username LIKE '%$search_filter%'";
}
$sql_siswa .= " ORDER BY kelas ASC, username ASC";

// Hitung total
$total_siswa = mysqli_num_rows(mysqli_query($conn, $sql_siswa));

// Query dengan limit
$sql_siswa .= " LIMIT $limit_siswa OFFSET $offset_siswa";
$result_siswa = mysqli_query($conn, $sql_siswa);

// Total halaman
$total_pages_siswa = ceil($total_siswa / $limit_siswa);

// Kirim data ke view
return [
    'result_siswa'      => $result_siswa,
    'offset_siswa'      => $offset_siswa,
    'page_siswa'        => $page_siswa,
    'total_pages_siswa' => $total_pages_siswa,
];
