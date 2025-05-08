<?php require_once __DIR__."/../../../../dao/ProductDao.php";
require_once __DIR__."/../../../../dao/SupplierDao.php";
if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID sản phẩm!</p>");
}

$object_id = (int)$_GET['id']; // Ép kiểu để tránh SQL injection
$product= $table_products->get_by_id($object_id,true);
?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformproducts.css">
<div class="product-view-model">
<div class="product-view-card">
        <div class="card-header">
            <h2 class="card-title">Product Details</h2>
            <button class="close-btn" onclick="closeObjectView()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div class="card-body">
            <div class="product-display row">
                
            <div class="product-image-container">
                    <div class="image-wrapper">
                        <?php echo urlencode($product->name)?>
                        <img src="https://via.placeholder.com/200x300?text=<?= urlencode($product->name) ?>" 
                             alt="<?= htmlspecialchars($product->name) ?>" class="product-image">
                        <div class="status-badge status-<?= $product->is_active ? 'active' : 'inactive' ?>">
                            <?= $product->is_active ? 'Active' : 'Inactive' ?>
                        </div>
                    </div>
                    
                    <div class="price-tag">
                        <?= number_format($product->price, 2) ?> ₫
                    </div>
                </div>

                <div class="product-details column">
                <h3 class="product-nameview"><?= htmlspecialchars($product->name) ?></h3>
                    <div class="row">
                    <div class="detail-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Product ID</span>
                                <span class="detail-value">#<?= htmlspecialchars($product->id) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Stock Quantity</span>
                                <span class="detail-value"><?= htmlspecialchars($product->quantity) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Weight</span>
                                <span class="detail-value"><?= htmlspecialchars($product->weight) ?> g</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Voucher</span>
                                <span class="detail-value">
                                    <?= $product->id_voucher ? '#' . htmlspecialchars($product->id_voucher) : 'None' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Category & Relationships</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Category</span>
                                <span class="detail-value">
                                    <?php require_once __DIR__."/../../../../dao/TypeProductDao.php";
                                        $name_typeproduct = $table_typeproduct->get_by_id($product->id_type_product)->name;
                                    ?>
                                    <?= $product->id_type_product ? '#' . htmlspecialchars($product->id_type_product) : 'Uncategorized'
                                    ?>
                                    <?=($name_typeproduct)?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Added By</span>
                                <span class="detail-value">
                                <?php require_once __DIR__."/../../../../dao/AdminDao.php";
                                        $anme_admin = $table_admins->get_by_id($product->id_admin)->name;
                                    ?>
                                    <?= $product->id_admin ? '#' . htmlspecialchars($product->id_admin) : 'System' ?>
                                    <?=($anme_admin)?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Supplier</span>
                                <span class="detail-value">
                                    <?php  require_once __DIR__."/../../../../dao/SupplierDao.php";
                                        $name_supplier = $table_supplier->get_by_id($product->id_supplier)->name;
                                    ?>
                                    <?= $product->id_supplier ? '#' . htmlspecialchars($product->id_supplier) : 'None' ."" ?>
                                    <?= ($name_supplier)?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="detail-section">
                        <h4 class="section-title">Timestamps</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Created</span>
                                <span class="detail-value">
                                    <?= $product->created_at ? date('F j, Y \a\t g:i A', strtotime($product->created_at)) : 'Unknown' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Updated</span>
                                <span class="detail-value">
                                    <?= $product->updated_at ? date('F j, Y \a\t g:i A', strtotime($product->updated_at)) : 'Never' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    
                    <div class="detail-section">
                        <h4 class="section-title">Description</h4>
                        <div class="description-content">
                            <?= $product->description ? nl2br(htmlspecialchars($product->description)) : 'No description available' ?>
                        </div>
                    </div>
                    
                    </div>
                </div>
            </div>
        </div>

       
    </div>
</div>
<script src="js/admin/closeview_form.js"></script>