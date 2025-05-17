<?php
require_once __DIR__ . '/../DTO/Pagination.php';
require_once __DIR__ . "/../dao/ProductDao.php";

$productDao = new ProductDao();

// Xử lý tham số
$current_page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
$per_page = 8;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Lấy sản phẩm theo điều kiện
if ($category) {
    $products = $productDao->get_by_type($category);
    $total_products = count($products);
    $products = array_slice($products, ($current_page - 1) * $per_page, $per_page);
} elseif ($search) {
    $products = $productDao->search($search);
    $total_products = count($products);
    $products = array_slice($products, ($current_page - 1) * $per_page, $per_page);
} else {
    $total_products = $productDao->count_all_products();
    $products = $productDao->get_products_paginated(($current_page - 1) * $per_page, $per_page);
}

$pagination = new Pagination($total_products, $current_page, $per_page);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="product-page">
    <div class="container">
        <div class="page-header">
            <h1>
                <?php
                if ($category) {
                    echo "Danh mục: " . ucfirst($category);
                } elseif ($search) {
                    echo "Kết quả tìm kiếm cho: " . htmlspecialchars($search);
                } else {
                    echo "Tất cả sản phẩm";
                }
                ?>
            </h1>
        </div>

        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
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
                <p class="no-products">Không tìm thấy sản phẩm nào.</p>
            <?php endif; ?>
        </div>
        <div class="pagination">
            <?php if ($pagination->hasPrevious()): ?>
                <a href="?page=products&page_num=<?= $pagination->currentPage - 1 ?><?= $category ? '&category='.$category : '' ?><?= $search ? '&search='.$search : '' ?>">Trước</a>
            <?php endif; ?>
            
            <?php 
            // Hiển thị tối đa 5 trang xung quanh trang hiện tại
            $start = max(1, $pagination->currentPage - 2);
            $end = min($pagination->totalPages, $pagination->currentPage + 2);
            
            for ($i = $start; $i <= $end; $i++): ?>
                <a href="?page=products&page_num=<?= $i ?><?= $category ? '&category='.$category : '' ?><?= $search ? '&search='.$search : '' ?>" 
                class="<?= $i == $pagination->currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($pagination->hasNext()): ?>
                <a href="?page=products&page_num=<?= $pagination->currentPage + 1 ?><?= $category ? '&category='.$category : '' ?><?= $search ? '&search='.$search : '' ?>">Sau</a>
            <?php endif; ?>
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
                        <select id="modalSizeSelect">
                            <option value="">Chọn kích cỡ</option>
                        </select>
                    </div>
                    <div class="color-selector">
                        <label for="modalColorSelect">Màu sắc:</label>
                        <select id="modalColorSelect">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</div>