<?php
require_once __DIR__ . '/../models/product.php';

class CartController {
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productModel = new Product();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Hiển thị 10 sản phẩm mỗi trang
        $offset = ($page - 1) * $limit;

        $cartItems = $this->getCartItems($search, $limit, $offset);
        $totalItems = $this->countCartItems($search);
        $pages = ceil($totalItems / $limit);

        $viewFile = __DIR__ . '/../views/Cart.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: Cart view file not found at $viewFile");
        }
    }

    private function getCartItems($search, $limit, $offset) {
        $cart = $_SESSION['cart'];
        $items = [];

        if (empty($cart)) {
            return [];
        }

        $productIds = array_keys($cart);
        $products = $this->productModel->getProductsByIds($productIds);
        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product['id']] = $product;
        }

        foreach ($cart as $productId => $item) {
            if (isset($productsById[$productId])) {
                $item['product_id'] = $productId;
                $items[] = $item;
            }
        }

        if (!empty($search)) {
            $items = array_filter($items, function($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }

        return array_slice($items, $offset, $limit);
    }

    private function countCartItems($search) {
        $cart = $_SESSION['cart'];
        $items = [];

        if (empty($cart)) {
            return 0;
        }

        $productIds = array_keys($cart);
        $products = $this->productModel->getProductsByIds($productIds);
        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product['id']] = $product;
        }

        foreach ($cart as $productId => $item) {
            if (isset($productsById[$productId])) {
                $items[] = $item;
            }
        }

        if (!empty($search)) {
            $items = array_filter($items, function($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }

        return count($items);
    }

    public function handleRequest() {
        header('Content-Type: application/json');

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uriParts = explode('/', trim($uri, '/'));

        $action = end($uriParts);

        if ($method === 'POST' && $action === 'add-to-cart') {
            $this->addToCart();
        } elseif ($method === 'GET' && $action === 'cart') {
            $this->getCart();
        } elseif ($method === 'PUT' && $action === 'update-cart') {
            $this->updateCart();
        } elseif ($method === 'DELETE' && $action === 'remove-from-cart') {
            $this->removeFromCart();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            http_response_code(400);
        }
    }

    private function addToCart() {
        ob_clean();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['product_id']) || !isset($input['quantity'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing product_id or quantity']);
            http_response_code(400);
            return;
        }

        $productId = (string)$input['product_id'];
        $quantity = (int)$input['quantity'];

        if ($quantity <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Quantity must be greater than 0']);
            http_response_code(400);
            return;
        }

        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            http_response_code(404);
            return;
        }

        if ($product['quantity'] < $quantity) {
            echo json_encode(['status' => 'error', 'message' => 'Not enough stock available']);
            http_response_code(400);
            return;
        }

        $cart = &$_SESSION['cart'];
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($product['quantity'] < $newQuantity) {
                echo json_encode(['status' => 'error', 'message' => 'Not enough stock available']);
                http_response_code(400);
                return;
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }

        error_log("Added to cart: " . print_r($_SESSION['cart'], true));
        echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
    }

    private function getCart() {
        ob_clean();

        $cart = $_SESSION['cart'];
        if (empty($cart)) {
            echo json_encode(['status' => 'success', 'data' => []]);
            return;
        }

        $cartItems = [];
        foreach ($cart as $item) {
            $cartItems[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ];
        }

        error_log("Cart data: " . print_r($cart, true));
        echo json_encode(['status' => 'success', 'data' => array_values($cartItems)]);
    }

    private function updateCart() {
        ob_clean();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['product_id']) || !isset($input['quantity'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing product_id or quantity']);
            http_response_code(400);
            return;
        }

        $productId = (string)$input['product_id'];
        $quantity = (int)$input['quantity'];

        if ($quantity <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Quantity must be greater than 0']);
            http_response_code(400);
            return;
        }

        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            http_response_code(404);
            return;
        }

        if ($product['quantity'] < $quantity) {
            echo json_encode(['status' => 'error', 'message' => 'Not enough stock available']);
            http_response_code(400);
            return;
        }

        $cart = &$_SESSION['cart'];
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            error_log("Updated cart: " . print_r($_SESSION['cart'], true));
            echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found in cart']);
            http_response_code(404);
        }
    }

    private function removeFromCart() {
        ob_clean();

        $input = json_decode(file_get_contents('php://input'), true);
        $cart = &$_SESSION['cart'];

        if (isset($input['clear_all']) && $input['clear_all'] === true) {
            $_SESSION['cart'] = [];
            error_log("Cart cleared");
            echo json_encode(['status' => 'success', 'message' => 'Cart cleared']);
            return;
        }

        if (!isset($input['product_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing product_id']);
            http_response_code(400);
            return;
        }

        $productId = (string)$input['product_id'];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            error_log("Removed from cart: " . print_r($_SESSION['cart'], true));
            echo json_encode(['status' => 'success', 'message' => 'Item removed']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found in cart']);
            http_response_code(404);
        }
    }
}