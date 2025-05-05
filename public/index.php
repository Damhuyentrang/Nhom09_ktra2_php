<?php
session_start();
require_once __DIR__ . '/../controllers/productController.php';
require_once __DIR__ . '/../controllers/paymentController.php';
require_once __DIR__ . '/../controllers/cartController.php';
require_once __DIR__ . '/../models/product.php';

// Đảm bảo header đúng để render HTML
header('Content-Type: text/html; charset=UTF-8');

try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    $controller = new ProductController();
    $paymentController = new PaymentController();
    $cartController = new CartController();

    // Xử lý các yêu cầu API
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    // Xử lý các API của giỏ hàng
    if (strpos($requestUri, '/kiemtr2_nhom09/public/index.php/api/') === 0) {
        $cartController->handleRequest();
        exit;
    }

    // Xử lý các API khác
    if (isset($uri[2]) && $uri[2] === 'api') {
        $action = $uri[3] ?? '';

        // Các route liên quan đến thanh toán
        if ($action === 'create-payment') {
            if ($method !== 'POST') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $paymentController->apiCreatePayment();
        } elseif ($action === 'execute-payment') {
            $paymentController->apiExecutePayment();
        } elseif ($action === 'create-momo-payment') {
            if ($method !== 'POST') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $paymentController->apiCreateMomoPayment();
        } elseif ($action === 'momo-callback') {
            $paymentController->apiMomoCallback();
        } elseif ($action === 'momo-notify') {
            $paymentController->apiMomoNotify();
        }
        // Các route liên quan đến sản phẩm
        elseif ($action === 'get-all-products') {
            if ($method !== 'GET') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $controller->apiGetAllProducts();
        } elseif ($action === 'create-product') {
            if ($method !== 'POST') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $controller->apiCreateProduct();
        } elseif ($action === 'update-product') {
            if ($method !== 'PUT') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $controller->apiUpdateProduct();
        } elseif ($action === 'delete-product') {
            if ($method !== 'DELETE') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $controller->apiDeleteProduct();
        } elseif ($action === 'upload-image') {
            if ($method !== 'POST') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                exit;
            }
            $controller->apiUploadImage();
        } 
        elseif ($action === 'vnpay-create-payment') {
            require_once __DIR__ . '/../controllers/payment/vnpay/vnpay_create_payment.php';
        } elseif ($action === 'vnpay-return') {
            require_once __DIR__ . '/../controllers/payment/vnpay/vnpay_return.php';
        } elseif ($action === 'vnpay-ipn') {
            require_once __DIR__ . '/../controllers/payment/vnpay/vnpay_ipn.php';
        } elseif ($action === 'vnpay-refund') {
            require_once __DIR__ . '/../controllers/payment/vnpay/vnpay_refund.php';
        }
        // ✅ Nếu bạn có view tĩnh để hiển thị giao diện test VNPay
        elseif ($requestUri === '/kiemtr2_nhom09/public/index.php/vnpay') {
            require_once __DIR__ . '/../views/payment/vnpay.php';
        }
    
        else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Route not found']);
            exit;
        }
    } else {
        // Xử lý các yêu cầu giao diện
        // Chỉ kiểm tra path, bỏ qua query string
        if (preg_match('#^/kiemtr2_nhom09/public(/index\.php)?(/)?$#', $requestUri)) {
            error_log("Rendering index with requestUri: $requestUri");
            $controller->index();
        } elseif ($requestUri === '/kiemtr2_nhom09/public/index.php/cart') {
            // Hiển thị trang giỏ hàng
            error_log("Rendering cart with requestUri: $requestUri");
            require_once __DIR__ . '/../views/product/cart.php';
        } else {
            // Trả về lỗi 404 nếu đường dẫn không hợp lệ
            error_log("404 Not Found with requestUri: $requestUri");
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 Not Found</h1>";
            echo "The requested URL was not found on this server.";
        }
        
    }
} catch (Exception $e) {
    error_log("Error in index.php: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>500 Internal Server Error</h1>";
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
}
?>