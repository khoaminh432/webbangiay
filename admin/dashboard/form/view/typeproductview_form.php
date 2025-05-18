<?php

require_once __DIR__."/../../../../handle/admin/functionpermission.php";
require_once __DIR__ . "/../../../../dao/TypeProductDao.php";

if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID loại sản phẩm!</p>");
}

$typeId = (int)$_GET['id'];
$typeProduct = $table_typeproduct->get_by_id($typeId);


?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformtypeproduct.css">
<div class="typeproduct-view-model">
    <div class="typeproduct-view-card">
        <div class="card-header">
            <h2 class="card-title">Chi tiết Loại sản phẩm</h2>
            <button class="close-btn" onclick="closeObjectView()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div class="card-body">
            <div class="typeproduct-profile">
                <div class="typeproduct-identity">
                    <div class="type-badge">
                        <div class="icon-badge">
                            <ion-icon name="pricetags-outline"></ion-icon>
                        </div>
                        <div class="status-badge status-active">
                            Hoạt động
                        </div>
                    </div>
                    <h3 class="typeproduct-name"><?= htmlspecialchars($typeProduct->name) ?></h3>
                    <p class="typeproduct-id">Mã: <strong>#<?= htmlspecialchars($typeProduct->id) ?></strong></p>
                </div>

                <div class="typeproduct-details">
                    <div class="detail-section">
                        <h4 class="section-title">Thông tin cơ bản</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Tên loại</span>
                                <span class="detail-value"><?= htmlspecialchars($typeProduct->name) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Người tạo</span>
                                <span class="detail-value">Admin #<?= htmlspecialchars($typeProduct->id_admin) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Thống kê</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Số sản phẩm</span>
                                <span class="detail-value"><?=htmlspecialchars($table_typeproduct->count_product($typeId))?> sản phẩm</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Doanh thu</span>
                                <span class="detail-value"><?=htmlspecialchars($table_typeproduct->calculate_revenue($typeId))?>đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Thông tin hệ thống</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Ngày tạo</span>
                                <span class="detail-value">
                                    <?= $typeProduct->created_at ? date('d/m/Y \l\ú\c H:i', strtotime($typeProduct->created_at)) : 'Chưa xác định' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Cập nhật cuối</span>
                                <span class="detail-value">
                                    <?= $typeProduct->updated_at ? date('d/m/Y \l\ú\c H:i', strtotime($typeProduct->updated_at)) : 'Chưa cập nhật' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/admin/closeview_form.js"></script>