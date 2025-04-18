<?php
require_once __DIR__ . '/../models/product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function apiGetProducts() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $start = ($page - 1) * $limit;

        $products = $this->productModel->getProducts($search, $start, $limit);
        $total = $this->productModel->countProducts($search);
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
    }

    public function apiCreateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError(405, 'Method not allowed');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $quantity = $data['quantity'] ?? 0;
        $image_url = $data['image_url'] ?? null;
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? 0;

        if (empty($name) || $quantity <= 0 || $price <= 0) {
            $this->sendError(400, 'Invalid input');
            return;
        }

        $result = $this->productModel->createProduct($name, $quantity, $image_url, $description, $price);
        if ($result) {
            $this->sendSuccess('Product created');
        } else {
            $this->sendError(500, 'Failed to create product');
        }
    }

    public function apiUpdateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendError(405, 'Method not allowed');
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
            $this->sendError(400, 'Invalid input');
            return;
        }

        $result = $this->productModel->updateProduct($id, $name, $quantity, $image_url, $description, $price);
        if ($result) {
            $this->sendSuccess('Product updated');
        } else {
            $this->sendError(500, 'Failed to update product');
        }
    }

    public function apiDeleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendError(405, 'Method not allowed');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;

        if ($id <= 0) {
            $this->sendError(400, 'Invalid input');
            return;
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            $this->sendSuccess('Product deleted');
        } else {
            $this->sendError(500, 'Failed to delete product');
        }
    }

    public function apiUploadImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError(405, 'Method not allowed');
            return;
        }

        if (!isset($_FILES['image'])) {
            $this->sendError(400, 'No image uploaded');
            return;
        }

        $file = $_FILES['image'];
        $uploadDir = __DIR__ . '/../public/uploads/';
        $fileName = uniqid() . '-' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->sendError(400, 'Invalid file type');
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            $this->sendError(400, 'File too large');
            return;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $imageUrl = '/kiemtr2_nhom09/public/uploads/' . $fileName;
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
}