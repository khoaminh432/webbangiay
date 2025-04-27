<?php
require_once __DIR__ . "/../../dao/ProductDao.php";
require_once __DIR__ . "/../../dao/ProductImageDao.php";

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    $product = $table_products->get_by_id($productId);
    $images = $table_productImage->get_by_product_id($productId);
    
    $response = [
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $product->quantity,
        'weight' => $product->weight,
        'description' => $product->description,
        'id_type_product' => $product->id_type_product,
        'id_supplier' => $product->id_supplier,
        'is_active' => $product->is_active,
        'images' => array_map(function($img) {
            return [
                'id' => $img->id,
                'url' => $img->image_url,
                'is_primary' => $img->is_primary
            ];
        }, $images)
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}