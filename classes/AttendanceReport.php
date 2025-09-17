<?php
require_once "../config/db.php";

class AttendanceReport {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database;
    }

    public function getReport(array $filters = [], int $limit_absen = 10, int $page_absen = 1) {
        $conn = $this->db->getConnection();

        $offset_absen = ($page_absen - 1) * $limit_absen;
        $params = [];
        $types = '';

        $sql_absen = "SELECT * FROM absen WHERE 1=1";
        $sql_count = "SELECT COUNT(*) as total FROM absen WHERE 1=1";

        if (!empty($filters['kelas'])) {
            $sql_absen .= " AND kelas = ?";
            $sql_count .= " AND kelas = ?";
            $params[] = $filters['kelas'];
            $types .= 's';
        }
        if (!empty($filters['tanggal'])) {
            $sql_absen .= " AND DATE(tanggal) = ?";
            $sql_count .= " AND DATE(tanggal) = ?";
            $params[] = $filters['tanggal'];
            $types .= 's';
        }
        if (!empty($filters['search'])) {
            $sql_absen .= " AND nama_siswa LIKE ?";
            $sql_count .= " AND nama_siswa LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
            $types .= 's';
        }
        if (!empty($filters['status'])) {
            $sql_absen .= " AND status LIKE ?";
            $sql_count .= " AND status LIKE ?";
            $params[] = '%' . $filters['status'] . '%';
            $types .= 's';
        }

        // Hitung total items
        $stmt_count = mysqli_prepare($conn, $sql_count);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt_count, $types, ...$params);
        }
        mysqli_stmt_execute($stmt_count);
        $total_result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_count));
        $total_absen = $total_result['total'];

        // Tambahkan ORDER BY, LIMIT, dan OFFSET
        $sql_absen .= " ORDER BY tanggal DESC LIMIT ? OFFSET ?";
        $params[] = $limit_absen;
        $params[] = $offset_absen;
        $types .= 'ii';

        // Eksekusi query utama
        $stmt = mysqli_prepare($conn, $sql_absen);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result_absen = mysqli_stmt_get_result($stmt);

        $total_pages_absen = ceil($total_absen / $limit_absen);
        
        return [
            'result_absen'       => $result_absen,
            'offset_absen'       => $offset_absen,
            'page_absen'         => $page_absen,
            'total_pages_absen'  => $total_pages_absen,
            'total_absen'        => $total_absen,
            'limit_absen'        => $limit_absen
        ];
    }
}