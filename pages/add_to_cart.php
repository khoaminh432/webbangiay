<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
require_once __DIR__ . "../../dao/ProductDao.php";

// Lấy dữ liệu từ yêu cầu AJAX
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['productId'])) {
    $productId = $data['productId'];

    // Khởi tạo ProductDao để lấy thông tin sản phẩm
    $productDao = new ProductDao();
    $product = $productDao->get_by_id($productId);

    if ($product) {
        // Kiểm tra nếu giỏ hàng chưa tồn tại trong session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image_url' => $product->image_url ?? 'default.jpg'
            ];
        } 
        // Phản hồi thành công
        echo json_encode(['success' => true]);
    } else {
        // Phản hồi thất bại nếu không tìm thấy sản phẩm
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }
} else {
    // Phản hồi thất bại nếu không có productId
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}