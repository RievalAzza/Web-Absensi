<?php

require_once '../config/db.php';
require_once '../classes/AttendanceReport.php';

$database = new Database("localhost", "root", "", "absensi");

$reportManager = new AttendanceReport($database);

// Ambil filter 
$filters = [
    'kelas'   => $_GET['kelas_absen'] ?? '',
    'tanggal' => $_GET['tanggal'] ?? '',
    'search'  => $_GET['search_absen'] ?? '',
    'status'  => $_GET['status'] ?? ''
];

$limit_absen = 10;
$page_absen = isset($_GET['page_absen']) ? (int)$_GET['page_absen'] : 1;
if ($page_absen < 1) $page_absen = 1;

$reportData = $reportManager->getReport($filters, $limit_absen, $page_absen);

$result_absen      = $reportData['result_absen'];
$offset_absen      = $reportData['offset_absen'];
$page_absen        = $reportData['page_absen'];
$total_pages_absen = $reportData['total_pages_absen'];
$total_absen       = $reportData['total_absen'];

?>