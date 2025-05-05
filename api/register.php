<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './config/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));
if (!$data) {
    http_response_code(400);
    echo json_encode(["message" => "Không nhận được dữ liệu JSON hợp lệ."]);
    exit;
}

$firstName = $data->first_name ?? '';
$lastName = $data->last_name ?? '';
$email = $data->email ?? '';
$password = $data->password ?? '';

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$table_name = 'Users'; // Hoặc sửa lại tên bảng chính xác trong DB

$query = "INSERT INTO $table_name
    SET first_name = :firstname,
        last_name = :lastname,
        email = :email,
        password = :password";

$stmt = $conn->prepare($query);

$stmt->bindParam(':firstname', $firstName);
$stmt->bindParam(':lastname', $lastName);
$stmt->bindParam(':email', $email);

$password_hash = password_hash($password, PASSWORD_BCRYPT);
$stmt->bindParam(':password', $password_hash);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["message" => "User was successfully registered."]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Unable to register the user."]);
}
?>
