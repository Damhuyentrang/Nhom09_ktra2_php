<?php
require_once __DIR__ . '/../models/product.php';
require_once 'C:/xampp/htdocs/kiemtr2_nhom09/vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;

        // Đảm bảo page không nhỏ hơn 1
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->searchProducts($search, $limit, $offset);
        $totalProducts = $this->productModel->countProducts($search);
        $pages = ceil($totalProducts / $limit);

        error_log("Index called with search: $search, page: $page, pages: $pages");

        $viewFile = __DIR__ . '/../views/product/list.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: View file not found at $viewFile");
        }
    }

    public function apiGetAllProducts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $products = $this->productModel->getAllProducts();
        if (empty($products)) {
            echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No products found']);
        } else {
            echo json_encode(['status' => 'success', 'data' => $products]);
        }
    }

    public function apiCreateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            return;
        }

        $name = $data['name'] ?? '';
        $quantity = $data['quantity'] ?? 0;
        $image_url = $data['image_url'] ?? null;
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? 0;

        // Kiểm tra dữ liệu đầu vào
        if (empty($name) || $quantity <= 0 || $price <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        // Kiểm tra giới hạn
        if ($quantity > 10000 || $price > 1000000) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Quantity or price exceeds limit']);
            return;
        }

        // Kiểm tra trùng lặp tên sản phẩm (nếu cần)
        $existingProduct = $this->productModel->getProductByName($name);
        if ($existingProduct) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Product name already exists']);
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

    public function apiUpdateProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            return;
        }

        $id = $data['id'] ?? 0;
        $name = $data['name'] ?? '';
        $quantity = $data['quantity'] ?? 0;
        $image_url = $data['image_url'] ?? null;
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? 0;

        // Kiểm tra dữ liệu đầu vào
        if ($id <= 0 || empty($name) || $quantity <= 0 || $price <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        // Kiểm tra giới hạn
        if ($quantity > 10000 || $price > 1000000) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Quantity or price exceeds limit']);
            return;
        }

        // Kiểm tra trùng lặp tên sản phẩm (nếu cần)
        $existingProduct = $this->productModel->getProductByName($name);
        if ($existingProduct && $existingProduct['id'] != $id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Product name already exists']);
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

    public function apiDeleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            return;
        }

        $id = $data['id'] ?? 0;

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        // Kiểm tra sản phẩm có tồn tại không
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
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

        // Kiểm tra quyền ghi thư mục
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
                return;
            }
        }

        if (!is_writable($uploadDir)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Upload directory is not writable']);
            return;
        }

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

        // Kiểm tra file đã tồn tại
        if (file_exists($uploadPath)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'File already exists']);
            return;
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