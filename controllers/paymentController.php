<?php
require_once __DIR__ . '/../models/product.php';
require_once 'C:/xampp/htdocs/kiemtr2_nhom09/vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentController {
    private $productModel;
    private $paypalClient;

    public function __construct() {
        $this->productModel = new Product();

        // Cấu hình PayPal Client
        $clientId = PAYPAL_CLIENT_ID;
        $clientSecret = PAYPAL_SECRET;
        $environment = PAYPAL_MODE === 'sandbox'
            ? new SandboxEnvironment($clientId, $clientSecret)
            : new ProductionEnvironment($clientId, $clientSecret);
        
        $this->paypalClient = new PayPalHttpClient($environment);
    }

    public function apiCreatePayment() {
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

        $productId = $data['product_id'] ?? 0;
        $quantity = $data['quantity'] ?? 1;

        $products = $this->productModel->getAllProducts();
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] == $productId) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            return;
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => number_format($product['price'] * $quantity, 2, '.', '')
                    ],
                    "description" => "Payment for " . $product['name']
                ]
            ],
            "application_context" => [
                "return_url" => "http://localhost/kiemtr2_nhom09/public/index.php/api/execute-payment?success=true",
                "cancel_url" => "http://localhost/kiemtr2_nhom09/public/index.php/api/execute-payment?success=false"
            ]
        ];

        try {
            $response = $this->paypalClient->execute($request);
            $approvalUrl = null;
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    $approvalUrl = $link->href;
                    break;
                }
            }
            if ($approvalUrl) {
                echo json_encode(['status' => 'success', 'redirect_url' => $approvalUrl]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Unable to get approval URL']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function apiExecutePayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $success = isset($_GET['success']) && $_GET['success'] === 'true';
        $orderId = $_GET['token'] ?? '';

        if (!$success || empty($orderId)) {
            header('Location: /kiemtr2_nhom09/public/views/product/payment_result.php?status=failed');
            exit;
        }

        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');

        try {
            $response = $this->paypalClient->execute($request);
            if ($response->result->status === 'COMPLETED') {
                header('Location: /kiemtr2_nhom09/public/views/product/payment_result.php?status=success');
            } else {
                header('Location: /kiemtr2_nhom09/public/views/product/payment_result.php?status=failed&error=Payment not completed');
            }
        } catch (Exception $e) {
            header('Location: /kiemtr2_nhom09/public/views/product/payment_result.php?status=failed&error=' . urlencode($e->getMessage()));
        }
        exit;
    }

    public function apiCreateMomoPayment() {
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

        $productId = $data['product_id'] ?? 0;
        $quantity = $data['quantity'] ?? 1;

        $products = $this->productModel->getAllProducts();
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] == $productId) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            return;
        }

        $orderId = time() . "_" . $productId;
        $amount = (int)($product['price'] * $quantity * 23000); // Chuyển USD sang VND
        $orderInfo = "Thanh toán cho " . $product['name'];
        $redirectUrl = MOMO_RETURN_URL;
        $ipnUrl = MOMO_NOTIFY_URL;
        $requestId = time() . "";
        $requestType = "captureWallet";

        $rawHash = "accessKey=" . MOMO_ACCESS_KEY .
                   "&amount=" . $amount .
                   "&extraData=" .
                   "&ipnUrl=" . $ipnUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . MOMO_PARTNER_CODE .
                   "&redirectUrl=" . $redirectUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, MOMO_SECRET_KEY);

        $momoData = [
            'partnerCode' => MOMO_PARTNER_CODE,
            'partnerName' => "Kiemtr2 Nhom09",
            'storeId' => "Kiemtr2Nhom09",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => base64_encode(json_encode(['product_id' => $productId, 'quantity' => $quantity])),
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $ch = curl_init(MOMO_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($momoData));
        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);
        if (isset($responseData['payUrl'])) {
            echo json_encode(['status' => 'success', 'redirect_url' => $responseData['payUrl']]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $responseData['message'] ?? 'Failed to create Momo payment']);
        }
    }

    public function apiMomoCallback() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $resultCode = $_GET['resultCode'] ?? null;
        $extraData = isset($_GET['extraData']) ? json_decode(base64_decode($_GET['extraData']), true) : [];

        if ($resultCode == '0') {
            header('Location: /kiemtr2_nhom09/public/views/product/payment_result.php?status=success');
        } else {
            $message = $_GET['message'] ?? 'Payment failed';
            header('Location: /kiemtr2_nhom09/public/views/product/payment_result.php?status=failed&error=' . urlencode($message));
        }
        exit;
    }

    public function apiMomoNotify() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $resultCode = $data['resultCode'] ?? null;

        $rawHash = "accessKey=" . MOMO_ACCESS_KEY .
                   "&amount=" . $data['amount'] .
                   "&extraData=" . $data['extraData'] .
                   "&message=" . $data['message'] .
                   "&orderId=" . $data['orderId'] .
                   "&orderInfo=" . $data['orderInfo'] .
                   "&orderType=" . $data['orderType'] .
                   "&partnerCode=" . $data['partnerCode'] .
                   "&payType=" . $data['payType'] .
                   "&requestId=" . $data['requestId'] .
                   "&responseTime=" . $data['responseTime'] .
                   "&resultCode=" . $data['resultCode'] .
                   "&transId=" . $data['transId'];
        $signature = hash_hmac("sha256", $rawHash, MOMO_SECRET_KEY);

        if ($signature !== $data['signature']) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
            return;
        }

        if ($resultCode == 0) {
            $extraData = json_decode(base64_decode($data['extraData']), true);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $data['message']]);
        }
    }
}