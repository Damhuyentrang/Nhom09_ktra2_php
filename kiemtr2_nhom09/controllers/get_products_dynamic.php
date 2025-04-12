<?php
// controllers/get_products_dynamic.php
require_once '../models/product.php';

header('Content-Type: application/json');

$productModel = new Product();
$products = $productModel->getAllProducts(); // Giả sử bạn có hàm này trong model
echo json_encode($products);
exit;
?>