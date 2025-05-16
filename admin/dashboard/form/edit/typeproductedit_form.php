<?php require_once __DIR__."/../../../../initAdmin.php";?>
<?php
require_once __DIR__ . "/../../../../dao/TypeProductDao.php";
require_once __DIR__ . "/../../../../dao/AdminDao.php";

if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID loại sản phẩm!</p>");
}

$typeId = (int)$_GET['id'];

$typeProduct = $table_typeproduct->get_by_id($typeId);
$admin = $table_admins->get_by_id($typeId); // Assuming you have an AdminDao
?>
<link rel="stylesheet" href="css/admin_style/form/edit/editformtypeproduct_style.css">
<div class="typeproduct-edit-model">
    <form id="typeproductEditForm" class="object-edit-card">
        <input type="hidden" name="id" value="<?= $typeProduct->id ?>">
        <input type="hidden" name="object" value="typeproduct">
        <div class="card-header">
            <h2 class="card-title">Chỉnh sửa Loại sản phẩm</h2>
            <div class="action-buttons">
                <button type="button" class="cancel-btn" onclick="closeObjectView()">Hủy</button>
                <button type="submit" class="save-btn">Lưu thay đổi</button>
            </div>
        </div>

        <div class="card-body">
            <div class="typeproduct-profile">
                <div class="typeproduct-identity">
                    <div class="type-badge">
                        <div class="icon-badge">
                            <ion-icon name="pricetags-outline"></ion-icon>
                        </div>
                        <div class="status-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" <?= $typeProduct->is_active ? 'checked' : '' ?>>
                                <span class="slider round"></span>
                            </label>
                            <span class="toggle-label"><?= $typeProduct->is_active ? 'Hoạt động' : 'Ngừng hoạt động' ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="typeproductName">Tên loại sản phẩm</label>
                        <input type="text" id="typeproductName" name="name" 
                               value="<?= htmlspecialchars($typeProduct->name) ?>" required>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Mã loại</span>
                        <span class="info-value">#<?= htmlspecialchars($typeProduct->id) ?></span>
                    </div>
                </div>

                <div class="typeproduct-details">
                    <div class="detail-section">
                        <h4 class="section-title">Thông tin cơ bản</h4>
                        <div class="form-group">
                            <label for="typeproductAdmin">Người tạo</label>
                            <select id="typeproductAdmin" name="id_admin">
                                
                                    <option value="<?= $admin->id ?>" <?= $typeProduct->id_admin == $admin->id ? 'selected' : '' ?>>
                                        Admin #<?= htmlspecialchars($admin->id) ?> <?= htmlspecialchars($admin->name) ?>(<?= htmlspecialchars($admin->email) ?>)
                                    </option>
                                
                            </select>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Thống kê (readonly)</h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Số sản phẩm</span>
                                <span class="stat-value"><?=htmlspecialchars($table_typeproduct->count_product($typeId))?> sản phẩm</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Doanh thu</span>
                                <span class="stat-value"><?=htmlspecialchars($table_typeproduct->calculate_revenue($typeId))?>đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Thông tin hệ thống</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Ngày tạo</span>
                                <span class="info-value">
                                    <?= $typeProduct->created_at ? date('d/m/Y \l\ú\c H:i', strtotime($typeProduct->created_at)) : 'Chưa xác định' ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Cập nhật cuối</span>
                                <span class="info-value">
                                    <?= $typeProduct->updated_at ? date('d/m/Y \l\ú\c H:i', strtotime($typeProduct->updated_at)) : 'Chưa cập nhật' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="js/admin/closeview_form.js"></script>
<script src="js/admin/Edit_form.js"></script>