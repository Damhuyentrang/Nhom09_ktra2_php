<<<<<<< HEAD
<?php
=======
<?php 

>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
require_once __DIR__ . '/../controllers/productController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

error_log("URI: $uri, Method: $method");
<<<<<<< HEAD
//echo "Debug - URI: $uri, Method: $method<br>";
=======
echo "Debug - URI: $uri, Method: $method<br>";
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)

$controller = new ProductController();

$path = str_replace('/kiemtr2_nhom09/public', '', $uri);

if ($path === '/' || $path === '/index.php' || $path === '') {
    if ($method === 'GET') {
<<<<<<< HEAD
        require_once __DIR__ . '/../views/product/list.php';
    }
} elseif ($path === '/index.php/api/products' || $path === '/api/products') {
    if ($method === 'GET') {
        $controller->apiGetProducts();
    } elseif ($method === 'POST') {
=======
        $controller->index();
    }
} elseif ($path === '/index.php/api/products' || $path === '/api/products') {
    if ($method === 'POST') {
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
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
<<<<<<< HEAD
}
=======
}
?>
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
