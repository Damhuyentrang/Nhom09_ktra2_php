<!-- views/payment/vnpay.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán VNPay</title>
</head>
<body>
    <h2>Thanh toán qua VNPay</h2>
    <form action="/kiemtr2_nhom09/public/index.php/api/vnpay-create-payment" method="POST">
        <label>Số tiền:</label>
        <input type="number" name="amount" value="10000" required> VNĐ <br><br>

        <label>Mô tả đơn hàng:</label>
        <input type="text" name="order_desc" value="Thanh toán đơn hàng A"><br><br>

        <label>Ngân hàng:</label>
        <select name="bank_code">
            <option value="">Cổng mặc định</option>
            <option value="NCB">NCB</option>
            <option value="VISA">VISA</option>
        </select><br><br>

        <button type="submit">Thanh toán với VNPay</button>
    </form>
</body>
</html>
