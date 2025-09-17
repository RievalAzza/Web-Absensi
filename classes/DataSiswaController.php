<?php
require_once __DIR__ . '/StudentManager.php';

class DataSiswaController {
    public $studentManager;

    public function __construct() {
        $this->studentManager = new StudentManager();
    }

    public function index() {
     
        $limit = 8;
        $page = isset($_GET['page_siswa']) ? (int)$_GET['page_siswa'] : 1;
        $kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
        $nama = isset($_GET['nama']) ? $_GET['nama'] : '';
        
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        
  
        $students = $this->studentManager->getAllStudents($limit, $offset, $kelas, $nama);
        $total_students = $this->studentManager->countStudents($kelas, $nama);
        $total_pages = ceil($total_students / $limit);
        
     
        $kelas_list = $this->studentManager->getKelasList();
        
        return [
            'students' => $students,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'kelas_list' => $kelas_list,
            'current_kelas' => $kelas,
            'current_nama' => $nama
        ];
    }
}
