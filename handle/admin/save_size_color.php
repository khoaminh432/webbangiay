<?php
require_once __DIR__ . '/../../dao/ProductSizeColorDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $combinations = $_POST['size_color_combinations'] ?? [];
    $quantities = $_POST['quantities'] ?? [];
    $pscDao = new ProductSizeColorDao();

    // Xóa các cặp cũ
    $pscDao->delete_by_product($product_id);

    // Thêm các cặp mới
    foreach ($combinations as $combo) {
        [$size_id, $color_id] = explode('_', $combo);
        $quantity = isset($quantities[$combo]) ? intval($quantities[$combo]) : 0;
        $sizecolorDTO = new ProductSizeColorDTO([
            "id_product"=> $product_id,
            "id_size" => $size_id,
            "id_color" =>$color_id,
            "quantity" => $quantity
        ]);
        $pscDao->insert($sizecolorDTO);
    }

    echo json_encode(['success' => true, 'message' => 'Lưu thành công']);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?>