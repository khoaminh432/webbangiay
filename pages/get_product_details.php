<?php
require_once __DIR__ . "/../dao/ProductDao.php";

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit;
}

$productId = intval($_GET['id']);
$productDao = new ProductDao();
$products = $productDao->get_products_with_details();

// Tìm sản phẩm theo ID
$product = null;
foreach ($products as $p) {
    if ($p->id == $productId) {
        $product = $p;
        break;
    }
}

if ($product) {
    echo json_encode([
        'success' => true,
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'weight' => $product->weight,
            'image_url' => $product->image_url,
            'type_name' => $product->type_name,
            'supplier_name' => $product->supplier_name
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}
?> 