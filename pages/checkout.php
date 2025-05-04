<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p>Giỏ hàng của bạn đang trống. <a href="/webbangiay/index.php?page=products">Hãy thêm sản phẩm rồi quay lại</a></p>';
    return;
}

$totalPrice = 0;
?>

<h1>Checkout</h1>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo number_format($item['price'], 2); ?> VNĐ</td>
                <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> VNĐ</td>
            </tr>
            <?php $totalPrice += $item['price'] * $item['quantity']; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Tổng tiền tất cả sản phẩm: <?php echo number_format($totalPrice, 2); ?> VNĐ</h3>

<h2>Thông tin giao hàng</h2>
<form action="/webbangiay/pages/process_checkout.php" method="POST">
    <label for="name">Họ và tên:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="address">Địa chỉ:</label>
    <textarea id="address" name="address" required></textarea><br><br>

    <label for="phone">Số điện thoại:</label>
    <input type="text" id="phone" name="phone" required><br><br>

    <label for="payment_method">Phương thức thanh toán:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="cod">Thanh toán khi nhận hàng</option>
        <option value="online">Thanh toán trực truyến</option>
    </select><br><br>

    <button type="submit">Đặt hàng</button>
</form>