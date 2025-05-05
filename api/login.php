<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './config/database.php';
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

// Cấu hình Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Kết nối DB
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

// Đọc và giải mã JSON từ body
$input = file_get_contents("php://input");
$data = json_decode($input);

// Ghi log để debug nếu JSON lỗi
if (!$data) {
    file_put_contents("debug_login_input.log", $input);
}

// Kiểm tra input hợp lệ
if (!$data || !isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode([
        "message" => "Invalid input. Expecting JSON with 'email' and 'password'.",
        "received" => $data
    ]);
    exit;
}

$email = trim($data->email);
$password = trim($data->password);

// Truy vấn người dùng theo email
$query = "SELECT id, first_name, last_name, password FROM Users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $email);
$stmt->execute();

// Kiểm tra kết quả
if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // So sánh password
    if (password_verify($password, $row['password'])) {
        $secret_key = "YOUR_SECRET_KEY"; // Đặt key bí mật thực tế trong biến môi trường
        $issuedAt   = time();
        $expire     = $issuedAt + 3600;

        $token = [
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => $issuedAt,
            "exp" => $expire,
            "data" => [
                "id" => $row['id'],
                "first_name" => $row['first_name'],
                "last_name" => $row['last_name'],
                "email" => $email
            ]
        ];

        $jwt = JWT::encode($token, $secret_key, 'HS256');

        http_response_code(200);
        echo json_encode([
            "message" => "Successful login.",
            "jwt" => $jwt
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Login failed. Incorrect password."]);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "User not found with email: $email"]);
}
?>
