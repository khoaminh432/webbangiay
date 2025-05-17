<?php
require_once __DIR__ . '/../DTO/BillDTO.php';
require_once __DIR__ . '/../DTO/BillProductDTO.php';
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';
require_once __DIR__ . '/../DAO/BillDao.php';
require_once __DIR__ . '/../DAO/BillProductDao.php';
require_once __DIR__ . '/../DAO/ProductDao.php';
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../DAO/ProductSizeColorDao.php';
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

// Kiểm tra giỏ hàng
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
    $pscDao = new ProductSizeColorDao();
    
    // Kiểm tra địa chỉ giao hàng
    $address = $infoDao->get_by_id($addressId, $userId);
    if (!$address) {
        throw new Exception("Địa chỉ giao hàng không hợp lệ!");
    }

    // Tính toán tổng tiền và kiểm tra số lượng
    $totalAmount = 0;
    $validatedItems = [];
    
    foreach ($_SESSION['cart'] as $productKey => $item) {
        $parts = explode('_', $productKey);
        $productId = (int)$parts[0];
        $colorId = isset($parts[1]) ? (int)$parts[1] : null;
        $sizeId = isset($parts[2]) ? (int)$parts[2] : null;

        $product = $productDao->get_by_id($productId);
        if (!$product) {
            throw new Exception("Sản phẩm #$productId không tồn tại!");
        }
        
        // Kiểm tra số lượng tồn kho
        if ($colorId !== null && $sizeId !== null) {
            $available = $pscDao->get_quantity($productId, $sizeId, $colorId);
            if ($available < $item['quantity']) {
                throw new Exception("Sản phẩm {$product->name} (Màu: $colorId, Size: $sizeId) chỉ còn $available sản phẩm!");
            }
        } else {
            if ($product->quantity < $item['quantity']) {
                throw new Exception("Sản phẩm {$product->name} chỉ còn {$product->quantity} sản phẩm!");
            }
        }
        
        $validatedItems[] = [
            'product' => $product,
            'product_key' => $productKey,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'color_id' => $colorId,
            'size_id' => $sizeId
        ];
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // Tạo đơn hàng
    $billDao = new BillDao();
    $billProductDao = new BillProductDao();
    
    $bill = new BillDTO([
        'id_user' => $userId,
        'id_payment_method' => $paymentMethodId,
        'total_amount' => $totalAmount,
        'shipping_address' => "{$address->name} | {$address->phone} | {$address->address}",
        'status' => 'pending',
        'bill_date' => date('Y-m-d H:i:s')
    ]);

    // Bắt đầu transaction
    $db->conn->beginTransaction();
    
    try {
        // 1. Tạo bill
        $billId = $billDao->insert($bill);
        if (!$billId) {
            throw new Exception("Không thể tạo đơn hàng!");
        }

        // 2. Thêm sản phẩm vào bill
        foreach ($validatedItems as $item) {
            $billProductData = [
                'id_bill' => $billId,
                'id_product' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ];
            
            // Thêm màu và size nếu có
            if ($item['color_id'] !== null) {
                $billProductData['id_color'] = $item['color_id'];
            }
            if ($item['size_id'] !== null) {
                $billProductData['id_size'] = $item['size_id'];
            }
            
            $billProduct = new BillProductDTO($billProductData);
            
            if (!$billProductDao->insert($billProduct)) {
                throw new Exception("Không thể thêm sản phẩm #{$item['product']->id} vào đơn hàng!");
            }
            
            // 3. Cập nhật tồn kho
            if ($item['color_id'] !== null && $item['size_id'] !== null) {
                // Giảm số lượng trong product_size_color
                if (!$pscDao->update_quantity(
                    $item['product']->id,
                    $item['size_id'],
                    $item['color_id'],
                    -$item['quantity']
                )) {
                    throw new Exception("Không thể cập nhật tồn kho chi tiết!");
                }
            } else {
                // Giảm số lượng trong product
                $item['product']->quantity -= $item['quantity'];
                if (!$productDao->update($item['product'])) {
                    throw new Exception("Không thể cập nhật tồn kho chung!");
                }
            }
        }
        
        $db->conn->commit();
        unset($_SESSION['cart']);
        
        header("Location: /webbangiay/pages/order_success.php?order_id=$billId");
        exit();
        
    } catch (Exception $e) {
        $db->conn->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("Checkout Error: " . $e->getMessage());
    sendJsonResponse(false, $e->getMessage(), '/webbangiay/pages/checkout.php');
}