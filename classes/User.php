<?php
require_once "../config/db.php";

class User {
    private $db;
    
    public function __construct(Database $database) {
        $this->db = $database;
    }

    public function addStudent($username, $kelas, $password) {
        $conn = $this->db->getConnection();
        
        $username = mysqli_real_escape_string($conn, $username);
        $kelas = mysqli_real_escape_string($conn, $kelas);
        $password = md5($password);
        
        $sql = "INSERT INTO users (username, password, kelas, role) 
                VALUES ('$username', '$password', '$kelas', 'siswa')";
        
        return mysqli_query($conn, $sql);
    }

    public function editStudent($id, $username, $kelas, $password = null) {
        $conn = $this->db->getConnection();
        
       
        $id = (int)$id;
        $username = mysqli_real_escape_string($conn, $username);
        $kelas = mysqli_real_escape_string($conn, $kelas);
    
        if ($password) {
            $password = md5($password);
            $sql = "UPDATE users 
                    SET username='$username', kelas='$kelas', password='$password' 
                    WHERE id='$id'";
        } else {
            $sql = "UPDATE users 
                    SET username='$username', kelas='$kelas' 
                    WHERE id='$id'";
        }
        
        return mysqli_query($conn, $sql);
    }

  
    public function deleteStudent($id) {
        $conn = $this->db->getConnection();
        
        $id = (int)$id;
        $sql = "DELETE FROM users WHERE id = ? AND role = 'siswa'";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $success;
    }

    public function getStudentById($id) {
        $conn = $this->db->getConnection();
        
        $id = (int)$id;
        $query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
        
        return mysqli_fetch_assoc($query);
    }
}
?>