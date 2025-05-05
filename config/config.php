<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'nhom09_kiemtra2'; 
    private $username = 'root';         
    private $password = '';             
    private $conn = null;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name",
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
define('PAYPAL_CLIENT_ID', 'your_paypal_client_id_here');
define('PAYPAL_SECRET', 'your_paypal_secret_here');
define('PAYPAL_MODE', 'sandbox'); // hoặc 'live'

define('MOMO_PARTNER_CODE', 'your_momo_partner_code_here');
define('MOMO_ACCESS_KEY', 'your_momo_access_key_here');
define('MOMO_SECRET_KEY', 'your_momo_secret_key_here');
define('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create');
define('MOMO_RETURN_URL', 'http://localhost/kiemtr2_nhom09/public/index.php/api/momo-callback');
define('MOMO_NOTIFY_URL', 'http://localhost/kiemtr2_nhom09/public/index.php/api/momo-notify');