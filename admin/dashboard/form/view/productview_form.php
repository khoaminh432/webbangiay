<?php require_once __DIR__."/../../../../dao/ProductDao.php";
$product= $table_products->get_by_id($object_id);
?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformproduct.css">
<div class="product-view-model">
<div class="product-view-card">
        <div class="card-header">
            <h2 class="card-title">Product Details</h2>
            <button class="close-btn" onclick="closeProductView()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div class="card-body">
            <div class="product-display row">
                
            <div class="product-image-container">
                    <div class="image-wrapper">
                        <img src="https://via.placeholder.com/400x300?text=<?= urlencode($product->name) ?>" 
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
                <h3 class="product-name"><?= htmlspecialchars($product->name) ?></h3>
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
                                    <?= $product->id_type_product ? '#' . htmlspecialchars($product->id_type_product) : 'Uncategorized' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Added By</span>
                                <span class="detail-value">
                                    <?= $product->id_admin ? 'Admin #' . htmlspecialchars($product->id_admin) : 'System' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Supplier</span>
                                <span class="detail-value">
                                    <?= $product->id_supplier ? 'Supplier #' . htmlspecialchars($product->id_supplier) : 'None' ?>
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
    

<script>
function closeProductView() {
    document.querySelector('.product-view-modal').style.animation = 'fadeIn 0.3s ease reverse forwards';
    setTimeout(() => {
        window.history.back();
    }, 300);
}

function editProduct(productId) {
    document.querySelector('.product-view-modal').style.opacity = '0';
    setTimeout(() => {
        window.location.href = `edit_product.php?id=${productId}`;
    }, 300);
}

function deactivateProduct(productId) {
    if (confirm('Are you sure you want to deactivate this product? It will no longer be visible to customers.')) {
        fetch(`deactivate_product.php?id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Product deactivated successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            });
    }
}

function activateProduct(productId) {
    if (confirm('Are you sure you want to activate this product? It will become visible to customers.')) {
        fetch(`activate_product.php?id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Product activated successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            });
    }
}

function manageInventory(productId) {
    document.querySelector('.product-view-modal').style.opacity = '0';
    setTimeout(() => {
        window.location.href = `manage_inventory.php?id=${productId}`;
    }, 300);
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>