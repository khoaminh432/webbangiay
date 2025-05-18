<?php if (empty($products)): ?>
    <p>Không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm.</p>
<?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <?php 
            $product = (object)$product;
            ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if (!empty($product->image_url)): ?>
                        <img src='/webbangiay/img/product/<?php echo htmlspecialchars($product->image_url); ?>'
                            alt="<?php echo htmlspecialchars($product->name ?? ''); ?>">
                    <?php else: ?>
                        <img src='/webbangiay/img/product/default.jpg'
                            alt="<?php echo htmlspecialchars($product->name ?? ''); ?>">
                    <?php endif; ?>
                    <div class="product-actions">
                        <button class="quick-view" data-id="<?php echo $product->id ?? ''; ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="add-to-cart" data-id="<?php echo $product->id ?? ''; ?>">
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
                    <h3 class="product-name"><?php echo htmlspecialchars($product->name ?? ''); ?></h3>
                    <p class="product-price"><?php echo isset($product->price) ? number_format($product->price, 0, ',', '.') : '0'; ?> VNĐ</p>
                    <p class="product-type"><?php echo htmlspecialchars($product->type_name ?? ''); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
<?php endif; ?>