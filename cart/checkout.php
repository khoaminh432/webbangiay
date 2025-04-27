<?php
session_start();
require_once __DIR__ . "../../dao/ProductDao.php";

if (empty($_POST['quantity'])) {
    header('Location: cart.php');
    exit();
}

$total = isset($_POST['total']) ? intval($_POST['total']) : 0;
$quantities = $_POST['quantity'];
$productIds = array_keys($quantities);

$products = [];
if (!empty($productIds)) {
    $table_products = new ProductDao();
    $products = $table_products->get_by_ids($productIds);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <h2>Thông tin thanh toán</h2>
    <h3>Sản phẩm đã chọn</h3>
    <table class="cart-table" border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): 
            $qty = isset($quantities[$product->id]) ? (int)$quantities[$product->id] : 1;
            $subtotal = $qty * $product->price;
        ?>
            <tr>
                <td><img src="<?php echo $product->image_url; ?>" alt="<?php echo $product->name; ?>" style="width:60px"></td>
                <td><?php echo $product->name; ?></td>
                <td><?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?></td>
                <td><?php echo $qty; ?></td>
                <td><?php echo number_format($subtotal, 0, ',', '.') . ' VNĐ'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <form action="process_checkout.php" method="post">
        <label>Họ tên:</label>
        <input type="text" name="fullname" required><br><br>
        <label>Địa chỉ:</label>
        <input type="text" name="address" required><br><br>
        <label>Số điện thoại:</label>
        <input type="text" name="phone" required><br><br>
        <label>Tổng tiền:</label>
        <strong><?php echo number_format($total, 0, ',', '.') . ' VNĐ'; ?></strong>
        <input type="hidden" name="total" value="<?php echo $total; ?>">
        <?php foreach ($quantities as $pid => $qty): ?>
            <input type="hidden" name="quantity[<?php echo $pid; ?>]" value="<?php echo $qty; ?>">
        <?php endforeach; ?>
        <br><br>
        <button type="submit">Xác nhận đặt hàng</button>
    </form>
    <p><a href="cart.php">Quay lại giỏ hàng</a></p>
</body>
</html>