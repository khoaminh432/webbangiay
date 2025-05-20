<?php
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';
require_once __DIR__ . '/../DAO/PaymentMethodDao.php';
require_once __DIR__ . '/../DAO/BillDao.php';
require_once __DIR__ . '/../DAO/BillProductDao.php';
require_once __DIR__ . '/../DAO/ProductSizeColorDao.php';

require_once __DIR__ . '/../DAO/ProductDao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để thanh toán!'); window.location.href='/webbangiay/pages/login.php';</script>";
    exit();
}

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location.href='/webbangiay/index.php?page=products';</script>";
    exit();
}

// Kiểm tra dữ liệu form
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['address_id']) || !isset($_POST['payment_method'])) {
    echo "<script>alert('Thông tin thanh toán không hợp lệ!'); window.location.href='/webbangiay/pages/checkout.php';</script>";
    exit();
}

$userId = $_SESSION['user_id'];
$addressId = $_POST['address_id'];
$paymentMethodId = $_POST['payment_method'];

// Kiểm tra địa chỉ có thuộc về user không
$infoDao = new InformationReceiveDao();
$address = $infoDao->get_by_id($addressId, $userId);  
if (!$address) {
    echo "<script>alert('Địa chỉ không hợp lệ!'); window.location.href='/webbangiay/pages/checkout.php';</script>";
    exit();
}

// Kiểm tra phương thức thanh toán
$paymentMethodDao = new PaymentMethodDao();
$paymentMethod = $paymentMethodDao->get_by_id($paymentMethodId);
if (!$paymentMethod || $paymentMethod->is_active != 1) {
    echo "<script>alert('Phương thức thanh toán không hợp lệ!'); window.location.href='/webbangiay/pages/checkout.php';</script>";
    exit();
}

// Tính tổng tiền
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

try {
    $billDao = new BillDao();
    $billProductDao = new BillProductDao();
    $productDao = new ProductDao();
    $pscDao = new ProductSizeColorDao();


    // Tạo hóa đơn
    $bill = new BillDTO([
        'status' => 'pending',
        'id_user' => $userId,
        'id_payment_method' => $paymentMethodId,
        'total_amount' => $totalPrice,
        'shipping_address' => $address->address
    ]);

    $billId = $billDao->insert($bill);
    if (!$billId) {
        throw new Exception("Không thể tạo hóa đơn");
    }

    // Thêm từng sản phẩm vào bảng bill_product
    foreach ($_SESSION['cart'] as $item) {
        $billProduct = new BillProductDTO([
            'id_bill' => $billId,
            'id_product' => $item['id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['price'],
            'id_color' => $item['color_id'] ?? null,
            'id_size' => $item['size_id'] ?? null
        ]);

        if (!$billProductDao->insert($billProduct)) {
            throw new Exception("Không thể thêm sản phẩm vào hóa đơn");
        }

        // Cập nhật tồn kho
        // Lấy tồn kho hiện tại trong bảng product_size_color
$currentPSC = $pscDao->get_by_product_size_color($item['id'], $item['size_id'], $item['color_id']);

if (!$currentPSC) {
    throw new Exception("Không tìm thấy biến thể sản phẩm (product_size_color)");
}

$newQuantity = $currentPSC->quantity - $item['quantity'];
if ($newQuantity < 0) $newQuantity = 0; // không âm

$pscDao->set_quantity($item['id'], $item['size_id'], $item['color_id'], $newQuantity);

    }

    // Xóa giỏ hàng
    unset($_SESSION['cart']);

// Chuyển hướng đến trang cảm ơn
echo "<script>window.location.href='/webbangiay/pages/order_success.php?order_id=" . $billId . "';</script>";
    exit();

} catch (Exception $e) {
    echo "<script>alert('Có lỗi xảy ra khi đặt hàng: " . addslashes($e->getMessage()) . "'); window.location.href='/webbangiay/pages/checkout.php';</script>";
    exit();
}
?>
