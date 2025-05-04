<?php
require_once __DIR__ . "../../dao/ProductDao.php";

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$productDao = new ProductDao();
$product = $productDao->get_by_id($productId);

if (!$product) {
    echo '<p>Product not found. <a href="/webbangiay/index.php?page=products">Go back to products</a></p>';
    return;
}
?>

<div class="product-detail-page">
    <h1><?php echo htmlspecialchars($product->name); ?></h1>
    <div class="product-detail-container">
        <img src="/webbangiay/img/product/<?php echo htmlspecialchars($product->id . "/" . $product->image_url); ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="product-detail-image">
        <div class="product-detail-info">
            <p><strong>Description:</strong> <?php echo htmlspecialchars($product->description); ?></p>
            <p><strong>Price:</strong> <?php echo number_format($product->price, 2); ?> VNĐ</p>
            <p><strong>Available Quantity:</strong> <?php echo htmlspecialchars($product->quantity); ?></p>
            <button class="add-to-cart" onclick="addToCart(<?php echo $product->id; ?>)">Thêm vào giỏ hàng</button>
        </div>
    </div>
    <a href="/webbangiay/index.php?page=products" class="back-to-products">Quay lại danh sách sản phẩm</a>
</div>