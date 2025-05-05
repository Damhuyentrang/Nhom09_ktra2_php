<?php
require_once __DIR__ . '/../../../config/vnpay_config.php'; // chứa các thông tin cấu hình

$vnp_TxnRef = rand(10000, 99999); // Mã đơn hàng
$vnp_OrderInfo = $_POST['order_desc'] ?? 'Thanh toán đơn hàng';
$vnp_OrderType = 'billpayment';
$vnp_Amount = $_POST['amount'] * 100; // nhân 100 vì VNPay cần đơn vị là VND * 100
$vnp_Locale = 'vn';
$vnp_BankCode = $_POST['bank_code'] ?? '';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$vnp_Returnurl = $vnp_Returnurl; // đã có trong config

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
);

if (!empty($vnp_BankCode)) {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

ksort($inputData);
$query = "";
$hashdata = "";
foreach ($inputData as $key => $value) {
    $hashdata .= urlencode($key) . "=" . urlencode($value) . '&';
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}
$hashdata = rtrim($hashdata, '&');
$query = rtrim($query, '&');
$vnp_Url = $vnp_Url . "?" . $query;
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

header('Location: ' . $vnp_Url);
exit;
