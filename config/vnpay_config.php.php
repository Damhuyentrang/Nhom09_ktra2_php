<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Cấu hình VNPay
$vnp_TmnCode = "W5479ZZI"; // Mã định danh merchant kết nối (Terminal Id)
$vnp_HashSecret = "E1XCHU7WJ5SC04IZ35JGXTKW3EBW14EL"; // Secret key (bỏ dấu cách thừa ở đầu chuỗi)
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

$vnp_Returnurl = "http://localhost/kiemtr2_nhom09/public/index.php/api/vnpay-return";

// (Optional) API cho refund hoặc tra cứu
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

// Thời gian hết hạn thanh toán (có thể dùng trong request nếu cần)
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
