<?php
require_once __DIR__ . '/../controllers/productController.php';

// 👉 Thêm nút Đăng nhập và Đăng ký
echo '<div style="margin-bottom: 20px;">
        <a href="/kiemtr2_nhom09/frontend_login.html"><button>Đăng nhập</button></a>
        <a href="/kiemtr2_nhom09/register.html"><button>Đăng ký</button></a>
      </div>';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

error_log("URI: $uri, Method: $method");
echo "Debug - URI: $uri, Method: $method<br>";

$controller = new ProductController();

$path = str_replace('/kiemtr2_nhom09/public', '', $uri);

if ($path === '/' || $path === '/index.php' || $path === '') {
    if ($method === 'GET') {
        $controller->index();
    }
} elseif ($path === '/index.php/api/products' || $path === '/api/products') {
    if ($method === 'POST') {
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
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Route not found']);
}