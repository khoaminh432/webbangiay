<?php
require_once __DIR__ . '/../DTO/BillDTO.php';
require_once __DIR__ . '/../DTO/BillProductDTO.php';
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';
require_once __DIR__ . '/../DAO/BillDao.php';
require_once __DIR__ . '/../DAO/BillProductDao.php';
require_once __DIR__ . '/../DAO/ProductDao.php';
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../database/database_sever.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sendJsonResponse($success, $message, $redirect = null) {
    header('Content-Type: application/json');
    $response = ['success' => $success, 'message' => $message];
    if ($redirect) {
        $response['redirect'] = $redirect;
    }
    echo json_encode($response);
    exit();
}

// Kiểm tra giỏ hàng trước khi xử lý
if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    sendJsonResponse(false, 'Giỏ hàng của bạn đang trống!', '/webbangiay/index.php?page=products');
}

// Kiểm tra đăng nhập
if (empty($_SESSION['user_id'])) {
    sendJsonResponse(false, 'Bạn cần đăng nhập để thanh toán!', '/webbangiay/pages/login.php');
}   

// Kiểm tra dữ liệu đầu vào
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['address_id']) || empty($_POST['payment_method'])) {
    sendJsonResponse(false, 'Thông tin thanh toán không hợp lệ!', '/webbangiay/pages/checkout.php');
}

$userId = (int)$_SESSION['user_id'];
$addressId = (int)$_POST['address_id'];
$paymentMethodId = (int)$_POST['payment_method'];

try {
    $db = new database_sever();
    $infoDao = new InformationReceiveDao();
    $productDao = new ProductDao();
    
    // Kiểm tra địa chỉ giao hàng
    $address = $infoDao->get_by_id($addressId, $userId);
    if (!$address) {
        throw new Exception("Địa chỉ giao hàng không hợp lệ!");
    }

    // Tính toán tổng tiền và kiểm tra số lượng
    $totalAmount = 0;
    $validatedItems = [];
    
    foreach ($_SESSION['cart'] as $productId => $item) {
        $product = $productDao->get_by_id($productId);
        if (!$product) {
            throw new Exception("Sản phẩm không tồn tại!");
        }
        
        if ($product->quantity < $item['quantity']) {
            throw new Exception("Sản phẩm {$product->name} chỉ còn {$product->quantity} sản phẩm!");
        }
        
        $itemTotal = $item['price'] * $item['quantity'];
        $totalAmount += $itemTotal;
        
        $validatedItems[] = [
            'product' => $product,
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ];
    }

    // Tạo đơn hàng
    $billDao = new BillDao();
    $billProductDao = new BillProductDao();
    
    $shippingAddress = "{$address->name} | {$address->phone} | {$address->address}";
    
    $bill = new BillDTO([
        'id_user' => $userId,
        'id_payment_method' => $paymentMethodId,
        'total_amount' => $totalAmount,
        'shipping_address' => $shippingAddress,
        'status' => 'pending',
        'bill_date' => date('Y-m-d H:i:s')
    ]);

    // Xử lý transaction
    $db->conn->beginTransaction();
    
    try {
        $billId = $billDao->insert($bill);
        if (!$billId) {
            throw new Exception("Không thể tạo đơn hàng!");
        }

        foreach ($validatedItems as $item) {
            $billProduct = new BillProductDTO([
                'id_bill' => $billId,
                'id_product' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ]);
            
            if (!$billProductDao->insert($billProduct)) {
                throw new Exception("Không thể thêm sản phẩm vào đơn hàng!");
            }
            
            // Cập nhật số lượng tồn kho
            $item['product']->quantity -= $item['quantity'];
            if (!$productDao->update($item['product'])) {
                throw new Exception("Không thể cập nhật số lượng tồn kho!");
            }
        }
        
        $db->conn->commit();
        unset($_SESSION['cart']);
        
        header("Location: /webbangiay/pages/order_success.php?order_id=" . $billId);
        exit();
    } catch (Exception $e) {
        $db->conn->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("Checkout Error - User: {$userId} - " . $e->getMessage());
    sendJsonResponse(false, $e->getMessage(), '/webbangiay/pages/checkout.php');
}