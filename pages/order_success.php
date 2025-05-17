<?php
require_once __DIR__ . '/../DTO/BillDTO.php';
require_once __DIR__ . '/../DTO/BillProductDTO.php';
require_once __DIR__ . '/../DTO/PaymentMethodDTO.php';
require_once __DIR__ . '/../DAO/BillDao.php';
require_once __DIR__ . '/../DAO/BillProductDao.php';
require_once __DIR__ . '/../DAO/ProductDao.php';
require_once __DIR__ . '/../DAO/PaymentMethodDao.php';
require_once __DIR__ . '/../database/database_sever.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra người dùng đã đăng nhập chưa
if (empty($_SESSION['user_id'])) {
    header('Location: /webbangiay/layout/login_signup.php');
    exit();
}

// Kiểm tra có order_id không
if (empty($_GET['order_id'])) {
    header('Location: /webbangiay/index.php');
    exit();
}

$userId = (int)$_SESSION['user_id'];
$orderId = (int)$_GET['order_id'];

try {
    $db = new database_sever();
    $billDao = new BillDao();
    $billProductDao = new BillProductDao();
    $productDao = new ProductDao();
    $paymentMethodDao = new PaymentMethodDao();
    
    // Lấy thông tin đơn hàng
    $order = $billDao->get_by_id($orderId, $userId);
    
    if (!$order) {
        throw new Exception("Đơn hàng không tồn tại hoặc bạn không có quyền xem đơn hàng này");
    }
    
    // Lấy thông tin phương thức thanh toán
    $paymentMethod = $paymentMethodDao->get_by_id($order->id_payment_method);
    $paymentMethodName = $paymentMethod ? $paymentMethod->name : 'Không xác định';
    
    // Lấy danh sách sản phẩm trong đơn hàng
    $orderItems = $billProductDao->get_by_bill($orderId);
    $products = [];
    
    foreach ($orderItems as $item) {
        $product = $productDao->get_by_id($item->id_product);
        if ($product) {
            $products[] = [
                'product' => $product,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->quantity * $item->unit_price
            ];
        }
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Web bán giày</title>
    <link rel="stylesheet" href="/webbangiay/assets/css/style.css">
    
    <style>
        .order-success {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            text-align: center;
            color: #4CAF50;
            font-size: 5rem;
            margin-bottom: 1rem;
        }
        .order-details {
            margin-top: 2rem;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
        .order-items {
            margin-top: 1rem;
        }
        .order-item {
            display: flex;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .order-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 1rem;
        }
        .order-summary {
            margin-top: 2rem;
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 4px;
        }
        .btn-continue {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.8rem 1.5rem;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn-continue:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    
    <main class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <div class="text-center">
                <a href="/webbangiay/index.php" class="btn-continue">Quay về trang chủ</a>
            </div>
        <?php else: ?>
            <div class="order-success">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </div>
                <h1 class="text-center">Đặt hàng thành công!</h1>
                <p class="text-center">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
                
                <div class="order-details">
                    <h2>Thông tin đơn hàng</h2>
                    <p><strong>Mã đơn hàng:</strong> #<?php echo htmlspecialchars($order->id); ?></p>
                    <p><strong>Ngày đặt hàng:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($order->bill_date))); ?></p>
                    <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($paymentMethodName); ?></p>
                    <p><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($order->shipping_address); ?></p>
                    <p><strong>Tình trạng:</strong> <?php echo htmlspecialchars(ucfirst($order->status)); ?></p>
                    
                    <div class="order-items">
                        <h3>Sản phẩm đã đặt</h3>
                        <?php foreach ($products as $item): ?>
                            <div class="order-item">
                                <img src="/webbangiay/img/products/<?php echo htmlspecialchars($item['product']->image_url); ?>" alt="<?php echo htmlspecialchars($item['product']->name); ?>" class="order-item-img">
                                <div>
                                    <h4><?php echo htmlspecialchars($item['product']->name); ?></h4>
                                    <p>Số lượng: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                    <p>Đơn giá: <?php echo number_format($item['unit_price'], 0, ',', '.'); ?>₫</p>
                                    <p><strong>Thành tiền: <?php echo number_format($item['total'], 0, ',', '.'); ?>₫</strong></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-summary text-right">
                        <h3>Tổng cộng: <?php echo number_format($order->total_amount, 0, ',', '.'); ?>₫</h3>
                    </div>
                </div>
                
                <div class="text-center">
                    <a href="/webbangiay/index.php" class="btn-continue">Tiếp tục mua sắm</a>
                    <a href="/webbangiay/pages/bill.php" class="btn-continue" style="background: #2196F3; margin-left: 1rem;">Xem lịch sử đơn hàng</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>