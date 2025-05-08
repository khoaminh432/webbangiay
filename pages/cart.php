<?php
require_once __DIR__ . "../../dao/ProductDao.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Bạn chưa đăng nhập, hãy đăng nhập để sử dụng giỏ hàng');
        window.location.href = '/webbangiay/layout/login_signup.php';
    </script>";
    exit();
}

// Lọc các phần tử không hợp lệ trong giỏ hàng
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) {
        return is_array($item) && isset($item['id'], $item['name'], $item['price'], $item['quantity']);
    });
}

// Xử lý yêu cầu lấy số lượng sản phẩm
if (isset($_GET['action']) && $_GET['action'] === 'getCount') {
    $tongSoLuong = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $tongSoLuong += $item['quantity'];
        }
    }
    echo json_encode(['count' => $tongSoLuong]);
    exit;
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
        
        // Tính tổng số lượng sản phẩm
        $totalQuantity = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalQuantity += $item['quantity'];
        }
        $response['totalQuantity'] = $totalQuantity;
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
            
            // Tính tổng số lượng sản phẩm
            $totalQuantity = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalQuantity += $item['quantity'];
            }
            $response['totalQuantity'] = $totalQuantity;
        }
    }

    echo json_encode($response);
    exit;
}

// Đảm bảo $_SESSION['cart'] luôn là mảng, kể cả khi trống
$cartItems = $_SESSION['cart'] ?? [];

// Tính tổng tiền
$total = array_reduce($cartItems, function ($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);

?>

<link rel="stylesheet" href="../css/cart.css">

<div class="cart-container">
    <h1>Giỏ hàng của bạn</h1>
    
    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <p>Giỏ hàng của bạn đang trống</p>
            <a href="/webbangiay/index.php?page=products" class="continue-shopping">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <table class="cart-table">
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
                    <?php foreach ($cartItems as $item): ?>
                        <tr data-id="<?php echo $item['id']; ?>">
                            <td>
                                <img src="/webbangiay/img/product/<?php echo htmlspecialchars($item['id'] . "/" . $item['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                                    class="product-image">
                            </td>
                            <td class="product-name"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>
                                <div class="quantity-control">
                                    <button class="quantity-btn minus">-</button>
                                    <input type="number" class="quantity-input" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                                    <button class="quantity-btn plus">+</button>
                                </div>
                            </td>
                            <td class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                            <td class="item-total"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ</td>
                            <td>
                                <button class="remove-button" title="Xóa sản phẩm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Tổng tiền:</span>
                    <span id="total-price"><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
                </div>
                <div class="checkout-buttons">
                    <a href="/webbangiay/index.php?page=products" class="continue-shopping">Tiếp tục mua sắm</a>
                    <a href="/webbangiay/index.php?page=checkout" class="checkout-button">Tiến hành thanh toán</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="../js/cart.js" defer></script>
