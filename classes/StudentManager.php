<?php
require_once '../config/db.php';

class StudentManager {
    private $conn;

    public function __construct() {
        $database = new Database("localhost", "root", "", "absensi");
        $this->conn = $database->getConnection();
    }

    public function getAllStudents(int $limit, int $offset, string $kelas = '', string $nama = '') {
      
        $query = "SELECT * FROM users WHERE role='siswa'";
        
        // filter kelas 
        if (!empty($kelas)) {
            $kelas_escaped = mysqli_real_escape_string($this->conn, $kelas);
            $query .= " AND kelas = '$kelas_escaped'";
        }
        
        // filter nama
        if (!empty($nama)) {
            $nama_escaped = mysqli_real_escape_string($this->conn, $nama);
            $query .= " AND (username LIKE '%$nama_escaped%' OR nama_lengkap LIKE '%$nama_escaped%')";
        }
        
        $query .= " ORDER BY kelas ASC, username ASC LIMIT $limit OFFSET $offset";
        
        $result = mysqli_query($this->conn, $query);

        $students = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        return $students;
    }

    public function countStudents(string $kelas = '', string $nama = '') {
        $query = "SELECT COUNT(*) as total FROM users WHERE role='siswa'";
        
        if (!empty($kelas)) {
            $kelas_escaped = mysqli_real_escape_string($this->conn, $kelas);
            $query .= " AND kelas = '$kelas_escaped'";
        }
        
        if (!empty($nama)) {
            $nama_escaped = mysqli_real_escape_string($this->conn, $nama);
            $query .= " AND (username LIKE '%$nama_escaped%' OR nama_lengkap LIKE '%$nama_escaped%')";
        }
        
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return (int)$row['total'];
    }
    
    public function getKelasList() {
        $query = "SELECT DISTINCT kelas FROM users WHERE role='siswa' AND kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC";
        $result = mysqli_query($this->conn, $query);
        
        $kelas_list = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $kelas_list[] = $row['kelas'];
            }
        }
        return $kelas_list;
    }
}