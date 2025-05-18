<?php require_once __DIR__."/../../../../initAdmin.php";
require_once __DIR__."/../../../../handle/admin/functionpermission.php";?>
<?php require_once __DIR__."/../../../../dao/ProductDao.php";
require_once __DIR__."/../../../../dao/SupplierDao.php";
require_once __DIR__."/../../../../dao/TypeProductDao.php";
require_once __DIR__."/../../../../dao/VoucherDao.php";
if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID sản phẩm!</p>");
}

$object_id = (int)$_GET['id']; // Ép kiểu để tránh SQL injection
$product = $table_products->get_by_id($object_id);

// Fetch categories, suppliers, vouchers for dropdowns (you'll need to implement these)
$categories = $table_typeproduct->view_all(); // Example - implement as needed
$suppliers = $table_supplier->view_all();   // Example - implement as needed
$vouchers = $table_vouchers->view_all();      // Example - implement as needed
?>
<style>
#showSizeColorBtn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    margin-top: 10px;
    width: 100%;
    max-width: 200px;
}
#showSizeColorBtn:hover {
    background-color: #45a049;
    transform: scale(1.05);
}
#showSizeColorBtn:active {
    background-color: #3e8e41;
    transform: scale(0.98);
}
</style>

<link rel="stylesheet" href="css/admin_style/form/edit/editformproduct_style.css">
<div class="product-edit-model">
    <form id="productEditForm" class="object-edit-card">
        <input type="hidden" name="id" value="<?= $product->id ?>">
        <input type="hidden" name="object" value="product">
        <div class="card-header">
            <h2 class="card-title">Edit Product</h2>
            <div class="action-buttons">
                <button type="button" class="cancel-btn" onclick="closeObjectView()">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </div>

        <div class="card-body">
            <div class="product-edit-display row">
                <div class="product-image-edit-container">
                    <div class="image-edit-wrapper">
                        <img id="productImagePreview" src="https://via.placeholder.com/400x300?text=<?= urlencode($product->name) ?>" 
                             alt="<?= htmlspecialchars($product->name) ?>" class="product-image">
                        <div class="image-upload-overlay">
                            <label for="productImageUpload" class="upload-label">
                                <ion-icon name="camera-outline"></ion-icon>
                                <span>Change Image</span>
                            </label>
                            <input type="file" id="productImageUpload" name="image" accept="image/*" style="display:none;">
                        </div>
                    </div>
                    <div class="status-toggle">
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_active" <?= $product->is_active ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                        <span class="toggle-label">Active Status</span>
                    </div>
                    <div class="select-option">
                    <button type="button" id="showSizeColorBtn">Chọn Size Màu</button>
                    </div>
                </div>

                <div class="product-edit-details column">
                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" id="productName" name="name" value="<?= htmlspecialchars($product->name) ?>" required>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="productPrice">Price (₫)</label>
                            <input type="number" id="productPrice" name="price" step="0.01" min="0" value="<?= $product->price ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="productQuantity">Stock Quantity</label>
                            <input type="number" id="productQuantity" name="quantity" min="0" value="<?= $product->quantity ?>" required readonly   >
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="productWeight">Weight (g)</label>
                            <input type="number" id="productWeight" name="weight" min="0" value="<?= $product->weight ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="productCategory">Category</label>
                            <select id="productCategory" name="id_type_product">
                                <option value="">-- Select Type Product --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->id ?>" <?= $product->id_type_product == $category->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="productSupplier">Supplier</label>
                            <select id="productSupplier" name="id_supplier">
                                <option value="">-- Select Supplier --</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier->id ?>" <?= $product->id_supplier == $supplier->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($supplier->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="productVoucher">Voucher</label>
                            <select id="productVoucher" name="id_voucher">
                                <option value="">-- No Voucher --</option>
                                <?php foreach ($vouchers as $voucher): ?>
                                    <option value="<?= $voucher->id ?>" <?= $product->id_voucher == $voucher->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($voucher->id) ?> (<?= $voucher->deduction ?>%)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productDescription">Description</label>
                        <textarea id="productDescription" name="description" rows="4"><?= htmlspecialchars($product->description) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_admin" value="<?=$adminDTO->id?>">
    </form>
</div>

<script src="js/admin/closeview_form.js"></script>
<script src="js/admin/Edit_form.js"></script>
<script>
// Image preview functionality
document.getElementById('productImageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('productImagePreview').src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?php require_once __DIR__."/../select_size_color.php";?>
<script>document.getElementById('showSizeColorBtn').addEventListener('click', function () {
        document.getElementById('sizeColorMatrixForm').style.display = 'block';
    });
</script>