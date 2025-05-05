<?php
require_once __DIR__ . '/../models/product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

<<<<<<< HEAD
    public function apiGetProducts() {
=======
    public function index() {
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $start = ($page - 1) * $limit;

        $products = $this->productModel->getProducts($search, $start, $limit);
        $total = $this->productModel->countProducts($search);
<<<<<<< HEAD
        $totalPages = ceil($total / $limit);

        $response = [
            'products' => array_map(function($product) {
                return [
                    'id' => (int)$product['id'],
                    'name' => htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'),
                    'quantity' => (int)$product['quantity'],
                    'image_url' => $product['image_url'] ?? null,
                    'description' => htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8'),
                    'price' => number_format((float)$product['price'], 2, '.', '')
                ];
            }, $products),
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $total
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
=======
        $pages = ceil($total / $limit);

        $viewFile = __DIR__ . '/../views/product/list.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("Error: View file not found at $viewFile");
        }
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
    }

    public function apiCreateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
<<<<<<< HEAD
            $this->sendError(405, 'Method not allowed');
=======
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $quantity = $data['quantity'] ?? 0;
        $image_url = $data['image_url'] ?? null;
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? 0;

        if (empty($name) || $quantity <= 0 || $price <= 0) {
<<<<<<< HEAD
            $this->sendError(400, 'Invalid input');
=======
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $result = $this->productModel->createProduct($name, $quantity, $image_url, $description, $price);
        if ($result) {
<<<<<<< HEAD
            $this->sendSuccess('Product created');
        } else {
            $this->sendError(500, 'Failed to create product');
        }
    }

    public function apiUpdateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendError(405, 'Method not allowed');
=======
            echo json_encode(['status' => 'success', 'message' => 'Product created']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to create product']);
        }
    }

    // API PUT để cập nhật sản phẩm
    public function apiUpdateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;
        $name = $data['name'] ?? '';
        $quantity = $data['quantity'] ?? 0;
        $image_url = $data['image_url'] ?? null;
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? 0;

        if ($id <= 0 || empty($name) || $quantity <= 0 || $price <= 0) {
<<<<<<< HEAD
            $this->sendError(400, 'Invalid input');
=======
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $result = $this->productModel->updateProduct($id, $name, $quantity, $image_url, $description, $price);
        if ($result) {
<<<<<<< HEAD
            $this->sendSuccess('Product updated');
        } else {
            $this->sendError(500, 'Failed to update product');
        }
    }

    public function apiDeleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendError(405, 'Method not allowed');
=======
            echo json_encode(['status' => 'success', 'message' => 'Product updated']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
        }
    }

    // API DELETE để xóa sản phẩm
    public function apiDeleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;

        if ($id <= 0) {
<<<<<<< HEAD
            $this->sendError(400, 'Invalid input');
=======
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
<<<<<<< HEAD
            $this->sendSuccess('Product deleted');
        } else {
            $this->sendError(500, 'Failed to delete product');
=======
            echo json_encode(['status' => 'success', 'message' => 'Product deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
        }
    }

    public function apiUploadImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
<<<<<<< HEAD
            $this->sendError(405, 'Method not allowed');
=======
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        if (!isset($_FILES['image'])) {
<<<<<<< HEAD
            $this->sendError(400, 'No image uploaded');
=======
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'No image uploaded']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        $file = $_FILES['image'];
        $uploadDir = __DIR__ . '/../public/uploads/';
        $fileName = uniqid() . '-' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
<<<<<<< HEAD
            $this->sendError(400, 'Invalid file type');
=======
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
<<<<<<< HEAD
            $this->sendError(400, 'File too large');
=======
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File too large']);
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
            return;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $imageUrl = '/kiemtr2_nhom09/public/uploads/' . $fileName;
<<<<<<< HEAD
            $this->sendSuccess('Image uploaded', ['image_url' => $imageUrl]);
        } else {
            $this->sendError(500, 'Failed to upload image');
        }
    }

    private function sendSuccess($message, $extra = []) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array_merge(['status' => 'success', 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function sendError($code, $message) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'error', 'message' => $message], JSON_UNESCAPED_UNICODE);
        exit;
    }
=======
            echo json_encode(['status' => 'success', 'image_url' => $imageUrl]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
        }
    }
>>>>>>> b77a01e (Khởi tạo project và tổ chức lại thư mục)
}