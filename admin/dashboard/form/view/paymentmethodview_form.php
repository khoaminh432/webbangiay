<?php

require_once __DIR__."/../../../../handle/admin/functionpermission.php";
require_once __DIR__."/../../../../dao/PaymentMethodDao.php";

if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID phương thức thanh toán!</p>");
}

$object_id = (int)$_GET['id']; // Ép kiểu để tránh SQL injection
$paymentMethod = $table_paymentmethode->get_by_id($object_id);
?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformpaymentmethod.css">
<div class="payment-method-view-model">
    <div class="payment-method-view-card">
        <div class="card-header">
            <h2 class="card-title">Payment Method Details</h2>
            <button class="close-btn" onclick="closeObjectView()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div class="card-body">
            <div class="payment-method-display row">
                <div class="payment-method-details column">
                    <h3 class="payment-method-name"><?= htmlspecialchars($paymentMethod->name) ?></h3>
                    
                    <div class="row">
                        <div class="detail-section">
                            <h4 class="section-title">Basic Information</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Payment Method ID</span>
                                    <span class="detail-value">#<?= htmlspecialchars($paymentMethod->id) ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status</span>
                                    <span class="detail-value status-<?= $paymentMethod->is_active ? 'active' : 'inactive' ?>">
                                        <?= $paymentMethod->is_active ? 'Active' : 'Inactive' ?>
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
                                        <?= $paymentMethod->created_at ? date('F j, Y \a\t g:i A', strtotime($paymentMethod->created_at)) : 'Unknown' ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Last Updated</span>
                                    <span class="detail-value">
                                        <?= $paymentMethod->updated_at ? date('F j, Y \a\t g:i A', strtotime($paymentMethod->updated_at)) : 'Never' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/admin/closeview_form.js"></script>
<script src="js/admin/checkstatus_object.js"></script>