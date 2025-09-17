<?php

class Auth {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database;
    }

    public function login(string $username, string $password) {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['kelas'] = $user['kelas'];

            $this->redirectByRole($user['role']);
        } else {
            header("Location: ../auth/login.php?pesan=gagal");
            exit;
        }
    }

    private function redirectByRole(string $role) {
        if ($role === 'admin') {
            header("Location: ../pages/admin_page.php");
        } else if ($role === 'siswa') {
            header("Location: ../pages/siswa_page.php");
        }
        exit;
    }
}