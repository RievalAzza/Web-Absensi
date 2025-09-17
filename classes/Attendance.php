<?php

class Attendance {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database;
    }

    public function recordAttendance(array $userData, string $status) {
        date_default_timezone_set('Asia/Jakarta');

        $nama_siswa = $userData['username'];
        $kelas = $userData['kelas'];
        $tanggal_jam = date('Y-m-d H:i:s');
        $jam_sekarang = date('H:i:s');

        $batas_jam = "10:00:00";
        $keterangan = ($jam_sekarang > $batas_jam) ? "Terlambat" : "Tepat Waktu";
        
        $conn = $this->db->getConnection();
 
        $tanggal_hari_ini = date('Y-m-d');
        $sql_cek = "SELECT id FROM absen WHERE nama_siswa = ? AND kelas = ? AND DATE(tanggal) = ?";
        $stmt_cek = mysqli_prepare($conn, $sql_cek);
        mysqli_stmt_bind_param($stmt_cek, "sss", $nama_siswa, $kelas, $tanggal_hari_ini);
        mysqli_stmt_execute($stmt_cek);
        mysqli_stmt_store_result($stmt_cek);

        if (mysqli_stmt_num_rows($stmt_cek) > 0) {
            return ['success' => false, 'message' => 'Kamu sudah absen hari ini!'];
        }

        $sql_insert = "INSERT INTO absen (nama_siswa, kelas, tanggal, status, keterangan) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "sssss", $nama_siswa, $kelas, $tanggal_jam, $status, $keterangan);

        if (mysqli_stmt_execute($stmt_insert)) {
            return ['success' => true, 'message' => "Absensi berhasil! ($keterangan)"];
        } else {
            return ['success' => false, 'message' => "Error: " . mysqli_error($conn)];
        }
    }
}