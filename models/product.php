<?php
class Product {
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/config.php';
        $db = new Database();
        $this->conn = $db->connect();
        if (!$this->conn) {
            throw new Exception("Failed to connect to database");
        }
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Lấy toàn bộ danh sách sản phẩm (dành cho danh sách động)
    public function getAllProducts() {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo ID
    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm mới
    public function createProduct($name, $quantity, $image_url, $description, $price) {
        $query = "INSERT INTO products (name, quantity, image_url, description, price) 
                  VALUES (:name, :quantity, :image_url, :description, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Cập nhật sản phẩm (PUT)
    public function updateProduct($id, $name, $quantity, $image_url, $description, $price) {
        $query = "UPDATE products 
                  SET name = :name, quantity = :quantity, image_url = :image_url, 
                      description = :description, price = :price 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Xóa sản phẩm (DELETE)
    public function deleteProduct($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Tìm kiếm và phân trang sản phẩm
    public function searchProducts($search, $limit, $offset) {
        $query = "SELECT * FROM products WHERE name LIKE :search OR description LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số sản phẩm
    public function countProducts($search) {
        $query = "SELECT COUNT(*) FROM products WHERE name LIKE :search OR description LIKE :search";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}