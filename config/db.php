<?php 

Class Database {
    private $host;
    private $user;
    private $pass;
    private $db;
    private $conn;

    public function __construct(string $host, string $user, string $pass, string $db) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;

        $this->connect($this->conn);
    }

    private function connect(){
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

        
        if(!$this->conn){
            die("Koneksi Gagal: " . mysqli_connect_error());
        } 
    }

    public function getConnection() {
        return $this->conn;
    }
}

$database = new Database("localhost", "root", "", "absensi");
$connection = $database->getConnection();
