<?php
require_once __DIR__ . '/../controllers/productController.php';

// Đặt header JSON cho tất cả các phản hồi API
header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

error_log("URI: $uri, Method: $method");

$controller = new ProductController();

$path = str_replace('/kiemtr2_nhom09/public', '', $uri);

if ($path === '/' || $path === '/index.php' || $path === '') {
    if ($method === 'GET') {
        // index() không cần header JSON vì nó render HTML
        header('Content-Type: text/html');
        $controller->index();
    }
} elseif ($path === '/index.php/api/products' || $path === '/api/products') {
    if ($method === 'GET') {
        $controller->apiGetAllProducts();
    } elseif ($method === 'POST') {
        $controller->apiCreateProduct();
    } elseif ($method === 'PUT') {
        $controller->apiUpdateProduct();
    } elseif ($method === 'DELETE') {
        $controller->apiDeleteProduct();
    }
} elseif ($path === '/index.php/api/upload-image' || $path === '/api/upload-image') {
    if ($method === 'POST') {
        $controller->apiUploadImage();
    }
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Route not found']);
}