<?php
require_once __DIR__ . "/../dao/ProductDao.php";

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $productDao = new ProductDao();
    $product = $productDao->get_by_id($_GET['id']);
    
    if ($product) {
        $product = (array)$product;  // Chuyển đổi thành mảng
        
        // Lấy ảnh chính của sản phẩm
        $sql = "SELECT image_url FROM product_images WHERE id_product = ? AND is_primary = 1 LIMIT 1";
        $stmt = $productDao->getConnection()->prepare($sql);
        $stmt->execute([$product['id']]);
        $image = $stmt->fetch(PDO::FETCH_OBJ);
        $product['image_url'] = $image ? $image->image_url : null;
        
        // Lấy thông tin loại sản phẩm
        $sql = "SELECT name FROM type_product WHERE id = ?";
        $stmt = $productDao->getConnection()->prepare($sql);
        $stmt->execute([$product['id_type_product']]);
        $type = $stmt->fetch(PDO::FETCH_OBJ);
        $product['type_name'] = $type ? $type->name : '';
        
        // Lấy thông tin nhà cung cấp
        $sql = "SELECT name FROM supplier WHERE id = ?";
        $stmt = $productDao->getConnection()->prepare($sql);
        $stmt->execute([$product['id_supplier']]);
        $supplier = $stmt->fetch(PDO::FETCH_OBJ);
        $product['supplier_name'] = $supplier ? $supplier->name : '';
        
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
} 