<?php
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để thanh toán!'); window.location.href='/webbangiay/pages/login.php';</script>";
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<div class="checkout-container"><p>Giỏ hàng của bạn đang trống. <a href="/webbangiay/index.php?page=products">Hãy thêm sản phẩm rồi quay lại</a></p></div>';
    return;
}

$userId = $_SESSION['user_id'];
$infoDao = new InformationReceiveDao();
$addresses = $infoDao->get_by_user($userId);

$totalPrice = 0;
?>

<link rel="stylesheet" href="../css/checkout.css">
<div class="checkout-container">
    <h1>Thanh toán</h1>
    <table class="checkout-table">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td>
                        <img src="/webbangiay/img/product/<?php echo htmlspecialchars($item['id'] . "/" . $item['image_url']); ?>"
                             alt="<?php echo htmlspecialchars($item['name']); ?>" class="checkout-img">
                    </td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ</td>
                </tr>
                <?php $totalPrice += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="total">Tổng tiền tất cả sản phẩm: <span><?php echo number_format($totalPrice, 0, ',', '.'); ?> VNĐ</span></h3>

    <h2>Thông tin giao hàng</h2>
    <form action="/webbangiay/pages/process_checkout.php" method="POST" class="checkout-form">
        <div class="form-group">
            <label for="address_id">Chọn địa chỉ nhận hàng:</label>
            <select id="address_id" name="address_id" required>
                <option value="">-- Chọn địa chỉ --</option>
                <?php foreach ($addresses as $address): ?>
            <option value="<?php echo $address->id; ?>" <?php if ($address->is_default) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($address->name . ' | ' . $address->phone . ' | ' . $address->address . ($address->is_default ? ' (Mặc định)' : '')); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="payment_method">Phương thức thanh toán:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cod">Thanh toán khi nhận hàng</option>
                <option value="online">Thanh toán trực tuyến</option>
            </select>
        </div>

        <button type="submit" class="checkout-btn">Xác nhận đặt hàng</button>
    </form>
</div>