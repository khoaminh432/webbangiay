<?php

require_once __DIR__ . "../../dao/ProductDao.php";
session_start();

// Khởi tạo ProductDao
$productDao = new ProductDao();

// Lấy danh sách sản phẩm
$products = $productDao->view_all();

?>

<div class="product-page">
    <h1>Danh sách sản phẩm</h1>
    <div class="product-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src='/webbangiay/img/product/<?php echo htmlspecialchars($product->id . "/" . $product->image_url ); ?>' alt="<?php echo htmlspecialchars($product->name); ?>" class="product-image">
                    <h2 class="product-name"><?php echo htmlspecialchars($product->name); ?></h2>
                    <p class="product-description"><?php echo htmlspecialchars($product->description); ?></p>
                    <p class="product-price"><?php echo number_format($product->price, 2); ?> VNĐ</p>
                    <p class="product-quantity">Available: <?php echo htmlspecialchars($product->quantity); ?></p>
                    <a href="/webbangiay/pages/product_detail.php?id=<?php echo $product->id; ?>" class="detail-button">Chi tiết sản phẩm</a>
                    <button class="add-to-cart" onclick="addToCart(<?php echo $product->id; ?>)">Thêm vào giỏ hàng</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function addToCart(productId) {
        // Gửi yêu cầu AJAX đến server
        fetch('pages/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ productId: productId })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Kiểm tra phản hồi từ server
            if (data.success) {
                alert('Thêm sản phẩm thành công!');
            } else {
                alert(data.message || 'Thêm sản phẩm thất bại');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
    }
</script>
