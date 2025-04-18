<?php

class DatabaseService {
 
 private $db_host = "127.0.0.1";
 private $db_name = "db";
 private $db_user = "root";
 private $db_password = "";
 public $conn;

 public function getConnection(){

     $this->conn = null;

     try{
        $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
     }catch(PDOException $exception){
         echo "Connection failed: " . $exception->getMessage();
     }

     return $this->conn;
 }
}
?>