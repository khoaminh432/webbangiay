<?php
require_once __DIR__ . "../../dao/ProductDao.php";
session_start();

// Lọc các phần tử không hợp lệ trong giỏ hàng
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) {
        return is_array($item) && isset($item['id'], $item['name'], $item['price'], $item['quantity']);
    });
}

// Xử lý AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = ['success' => false];

    // Xử lý xóa sản phẩm
    if ($_POST['action'] === 'remove' && isset($_POST['productId'])) {
        $productId = $_POST['productId'];
        unset($_SESSION['cart'][$productId]);
        $response['success'] = true;
        $response['totalPrice'] = array_reduce($_SESSION['cart'], function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    // Xử lý cập nhật số lượng
    if ($_POST['action'] === 'update' && isset($_POST['productId'], $_POST['quantity'])) {
        $productId = $_POST['productId'];
        $quantity = max(1, intval($_POST['quantity'])); // Số lượng tối thiểu là 1
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
            $response['success'] = true;
            $response['itemTotal'] = $_SESSION['cart'][$productId]['price'] * $quantity;
            $response['totalPrice'] = array_reduce($_SESSION['cart'], function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);
        }
    }

    echo json_encode($response);
    exit;
}

$totalPrice = array_reduce($_SESSION['cart'], function ($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);
?>

<link rel="stylesheet" href="cart.css">

<h1>Giỏ hàng của bạn</h1>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
            <th>Thực hiện</th>
        </tr>
    </thead>
    <tbody id="cart-items">
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <tr data-id="<?php echo $item['id']; ?>">
                <td>
                    <img src="/webbangiay/img/product/<?php echo htmlspecialchars($item['image_url'] ?? 'default.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                         width="50">
                </td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>
                    <input type="number" class="quantity-input" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" style="width: 50px;">
                </td>
                <td><?php echo number_format($item['price'], 2); ?> VNĐ</td>
                <td class="item-total"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> VNĐ</td>
                <td>
                    <button class="remove-button">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Total Price: <span id="total-price"><?php echo number_format($totalPrice, 2); ?></span> VNĐ</h3>
<form action="/webbangiay/pages/checkout.php" method="GET">
    <button type="submit" class="checkout-button">Tiến hành thanh toán</button>
</form>
