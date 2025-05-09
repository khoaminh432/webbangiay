<?php
require_once __DIR__ . "../../dao/ProductDao.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$productDao = new ProductDao();

// Lấy tất cả sản phẩm
$allProducts = $productDao->get_products_with_details();

// Chuyển đổi thành mảng để dễ xử lý
$productsArray = array_values((array)$allProducts);

// Xáo trộn mảng sản phẩm
shuffle($productsArray);

// Lấy 8 sản phẩm đầu tiên sau khi xáo trộn
$randomProducts = array_slice($productsArray, 0, 8);

// Đảm bảo không có sản phẩm trùng lặp
$uniqueProducts = [];
$usedIds = [];

foreach ($randomProducts as $product) {
    if (!in_array($product->id, $usedIds)) {
        $uniqueProducts[] = $product;
        $usedIds[] = $product->id;
    }
}

// Nếu số lượng sản phẩm unique ít hơn 8, lấy thêm từ danh sách gốc
if (count($uniqueProducts) < 8) {
    foreach ($productsArray as $product) {
        if (!in_array($product->id, $usedIds)) {
            $uniqueProducts[] = $product;
            $usedIds[] = $product->id;
            if (count($uniqueProducts) >= 8) {
                break;
            }
        }
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../css/home.css">
<script src="../js/home.js" defer></script>

<div class="home-page">
    <!-- Slide -->
    <div class="container">
        <div class="slider">
            <div class="slide">
                <img src="img/slide_1.png" alt="Slide 1">
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="featured-products">
        <div class="container">
            <h2>Sản phẩm nổi bật</h2>
            <div class="product-grid">
                <?php if (!empty($uniqueProducts)): ?>
                    <?php foreach ($uniqueProducts as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if (!empty($product->image_url)): ?>
                                    <img src='/webbangiay/img/product/<?php echo htmlspecialchars($product->id . "/" . $product->image_url); ?>'
                                        alt="<?php echo htmlspecialchars($product->name); ?>">
                                <?php else: ?>
                                    <img src='/webbangiay/img/product/default.jpg'
                                        alt="<?php echo htmlspecialchars($product->name); ?>">
                                <?php endif; ?>
                                <div class="product-actions">
                                    <button class="quick-view" data-id="<?php echo $product->id; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button class="add-to-cart" data-id="<?php echo $product->id; ?>">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="/webbangiay/layout/login_signup.php" class="login-to-buy">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product->name); ?></h3>
                                <p class="product-price"><?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ</p>
                                <p class="product-type"><?php echo htmlspecialchars($product->type_name); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có sản phẩm nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="product-details">
            <div class="product-image">
                <img src="" alt="" id="modalProductImage">
            </div>
            <div class="product-info">
                <h2 id="modalProductName"></h2>
                <p class="product-type" id="modalProductType"></p>
                <p class="product-supplier" id="modalProductSupplier"></p>
                <p class="product-description" id="modalProductDescription"></p>
                <p class="product-weight" id="modalProductWeight"></p>
                <p class="product-price" id="modalProductPrice"></p>
                <div class="product-options">
                    <div class="size-selector">
                        <label for="modalSizeSelect">Kích cỡ:</label>
                        <select id="modalSizeSelect" required>
                            <option value="">Chọn kích cỡ</option>
                        </select>
                    </div>
                    <div class="color-selector">
                        <label for="modalColorSelect">Màu sắc:</label>
                        <select id="modalColorSelect" required>
                            <option value="">Chọn màu sắc</option>
                        </select>
                    </div>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="add-to-cart-btn" id="modalAddToCart">Thêm vào giỏ hàng</button>
                <?php else: ?>
                    <a href="/webbangiay/pages/login.php" class="login-to-buy-btn">Đăng nhập để mua hàng</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>