<?php
// API endpoint
$url = 'http://127.0.0.1:8000/api/login.php';

// Dữ liệu cần gửi (ví dụ giả định)
$data = array(
    "email" => "example@gmail.com",
    "password" => "yourpassword123"
);

// Khởi tạo CURL
$ch = curl_init($url);

// Thiết lập phương thức POST, headers, và body
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Thực thi CURL và lấy kết quả
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Đóng CURL
curl_close($ch);

// Hiển thị kết quả
echo "<h3>HTTP Status Code: $httpCode</h3>";
echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
?>
