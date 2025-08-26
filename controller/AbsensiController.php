<?php
include '../config/db.php'; // koneksi ke database

// Pagination
$limit_absen = 10;
$page_absen = isset($_GET['page_absen']) ? (int)$_GET['page_absen'] : 1;
if ($page_absen < 1) $page_absen = 1;
$offset_absen = ($page_absen - 1) * $limit_absen;

// Ambil filter
$kelas_absen   = isset($_GET['kelas_absen']) ? $_GET['kelas_absen'] : '';
$tanggal_absen = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$search_absen  = isset($_GET['search_absen']) ? $_GET['search_absen'] : '';
$status  = isset($_GET['status']) ? $_GET['status'] : '';

// Query
$sql_absen = "SELECT * FROM absen WHERE 1=1";
if ($kelas_absen != '') {
    $sql_absen .= " AND kelas='$kelas_absen'";
}
if ($tanggal_absen != '') {
    $sql_absen .= " AND DATE(tanggal)='$tanggal_absen'";
}   
if ($search_absen != '') {
    $sql_absen .= " AND nama_siswa LIKE '%$search_absen%'";
}
if ($status != '') {
    $sql_absen .= " AND status LIKE '%$status%'";
}
$sql_absen .= " ORDER BY tanggal DESC";

// Hitung total
$total_absen = mysqli_num_rows(mysqli_query($conn, $sql_absen));

// Tambahkan limit & offset
$sql_absen .= " LIMIT $limit_absen OFFSET $offset_absen";
$result_absen = mysqli_query($conn, $sql_absen);

// Total halaman
$total_pages_absen = ceil($total_absen / $limit_absen);

// Kirim data ke halaman (return array)
return [
    'result_absen'       => $result_absen,
    'offset_absen'       => $offset_absen,
    'page_absen'         => $page_absen,
    'total_pages_absen'  => $total_pages_absen,
];
