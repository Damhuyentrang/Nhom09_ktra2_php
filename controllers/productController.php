<?php
require_once __DIR__ . '/../models/product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $start = ($page - 1) * $limit;

        $products = $this->productModel->getProducts($search, $start, $limit);
        $total = $this->productModel->countProducts($search);
        $pages = ceil($total / $limit);

        $viewFile = __DIR__ . '/../views/product/list.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            die("Error: View file not found at $viewFile");
        }
    }

    public function apiCreateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? '';
        $quantity = $data['quantity'] ?? 0;
        $image_url = $data['image_url'] ?? null;
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? 0;

        if (empty($name) || $quantity <= 0 || $price <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        $result = $this->productModel->createProduct($name, $quantity, $image_url, $description, $price);
        if ($result) {
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
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        $result = $this->productModel->updateProduct($id, $name, $quantity, $image_url, $description, $price);
        if ($result) {
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
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Product deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
        }
    }

    public function apiUploadImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        if (!isset($_FILES['image'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'No image uploaded']);
            return;
        }

        $file = $_FILES['image'];
        $uploadDir = __DIR__ . '/../public/uploads/';
        $fileName = uniqid() . '-' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File too large']);
            return;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $imageUrl = '/kiemtr2_nhom09/public/uploads/' . $fileName;
            echo json_encode(['status' => 'success', 'image_url' => $imageUrl]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
        }
    }
}