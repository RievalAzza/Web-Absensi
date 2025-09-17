<?php
session_start();

if ($_SESSION['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/db.php'; 
require_once '../classes/Attendance.php';

$database = new Database("localhost", "root", "", "absensi");

$attendance = new Attendance($database);

$userData = [
    'username' => $_SESSION['username'],
    'kelas' => $_SESSION['kelas']
];
$status = $_POST['status'];

$result = $attendance->recordAttendance($userData, $status);

if ($result['success']) {
    echo "<script>alert('" . $result['message'] . "'); window.location='../pages/siswa_page.php';</script>";
} else {
    echo "<script>alert('" . $result['message'] . "'); window.location='../pages/siswa_page.php';</script>";
}