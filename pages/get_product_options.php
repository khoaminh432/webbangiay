<?php
require_once __DIR__ . "/../dao/SizeDao.php";
require_once __DIR__ . "/../dao/ColorDao.php";
require_once __DIR__ . "/../dao/ProductSizeColorDao.php";

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit;
}

$productId = intval($_GET['id']);

// Lấy danh sách size và màu sắc có sẵn cho sản phẩm
$pscDao = new ProductSizeColorDao();
$sizeDao = new SizeDao();
$colorDao = new ColorDao();

// Lấy tất cả size và màu
$allSizes = $sizeDao->view_all();
$allColors = $colorDao->view_all();

// Lọc ra các size và màu có sẵn cho sản phẩm
$availableSizes = [];
$availableColors = [];

foreach ($allSizes as $size) {
    foreach ($allColors as $color) {
        $quantity = $pscDao->get_quantity($productId, $size->id, $color->id);
        if ($quantity > 0) {
            if (!in_array($size->id, array_column($availableSizes, 'id'))) {
                $availableSizes[] = [
                    'id' => $size->id,
                    'size_number' => $size->size_number,
                    'description' => $size->description
                ];
            }
            if (!in_array($color->id, array_column($availableColors, 'id'))) {
                $availableColors[] = [
                    'id' => $color->id,
                    'name' => $color->name,
                    'hex_code' => $color->hex_code
                ];
            }
        }
    }
}

echo json_encode([
    'success' => true,
    'sizes' => $availableSizes,
    'colors' => $availableColors
]);
?> 