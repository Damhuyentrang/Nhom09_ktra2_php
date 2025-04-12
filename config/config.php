<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'nhom09_kiemtra2'; 
    private $username = 'root';         
    private $password = '';  
    private $port = '3306'; // Default MySQL port           
    private $conn = null;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;port=$this->port",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}