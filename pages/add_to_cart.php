<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../dao/ProductDao.php";
require_once __DIR__ . "/../dao/ProductSizeColorDao.php";
require_once __DIR__ . "/../dao/SizeDao.php";
require_once __DIR__ . "/../dao/ColorDao.php";

// Lấy dữ liệu từ yêu cầu AJAX
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['productId']) && isset($data['sizeId']) && isset($data['colorId'])) {
    $productId = $data['productId'];
    $sizeId = $data['sizeId'];
    $colorId = $data['colorId'];

    // Khởi tạo các DAO
    $productDao = new ProductDao();
    $pscDao = new ProductSizeColorDao();
    $sizeDao = new SizeDao();
    $colorDao = new ColorDao();

    // Lấy thông tin sản phẩm
    $product = $productDao->get_by_id($productId);
    $size = $sizeDao->get_by_id($sizeId);
    $color = $colorDao->get_by_id($colorId);

    if ($product && $size && $color) {
        // Kiểm tra số lượng tồn kho
        $availableQuantity = $pscDao->get_quantity($productId, $sizeId, $colorId);
        if ($availableQuantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng!']);
            exit;
        }

        // Kiểm tra nếu giỏ hàng chưa tồn tại trong session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tạo key duy nhất cho sản phẩm trong giỏ hàng
        $cartKey = $productId . '_' . $sizeId . '_' . $colorId;

        // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
        if (isset($_SESSION['cart'][$cartKey])) {
            // Kiểm tra số lượng trong giỏ hàng có vượt quá số lượng tồn kho không
            if ($_SESSION['cart'][$cartKey]['quantity'] >= $availableQuantity) {
                echo json_encode(['success' => false, 'message' => 'Số lượng sản phẩm trong giỏ hàng đã đạt tối đa!']);
                exit;
            }
            $_SESSION['cart'][$cartKey]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image_url' => $product->image_url ?? 'default.jpg',
                'size_id' => $size->id,
                'size_number' => $size->size_number,
                'color_id' => $color->id,
                'color_name' => $color->name
            ];
        }

        // Tính tổng số lượng sản phẩm trong giỏ hàng
        $tongSoLuong = 0;
        foreach ($_SESSION['cart'] as $item) {
            $tongSoLuong += $item['quantity'];
        }

        echo json_encode(['success' => true, 'cart_count' => $tongSoLuong]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin sản phẩm!']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
    exit;
}
?>
