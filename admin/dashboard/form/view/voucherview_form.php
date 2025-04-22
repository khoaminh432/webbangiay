<?php
require_once __DIR__ . "/../../../../dto/VoucherDTO.php";
require_once __DIR__ . "/../../../../dao/VoucherDao.php";

// Kiểm tra xem ID có tồn tại không
if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID voucher!</p>");
}

$voucherId = (int)$_GET['id']; // Ép kiểu để tránh SQL injection
$voucher = $table_vouchers->get_by_id($voucherId); // Giả sử có phương thức này
?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformvoucher.css">
<div class="voucher-view-model">
    <div class="voucher-view-card">
        <div class="card-header">
            <h2 class="card-title">Voucher Details</h2>
            <button class="close-btn" onclick="closeObjectView()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div class="card-body">
            <div class="voucher-profile">
                <div class="voucher-identity">
                    <div class="voucher-badge">
                        <div class="discount-badge">
                            <?= number_format($voucher->deduction, 2) ?>%
                        </div>
                        <div class="status-badge status-<?= $voucher->is_active ? 'active' : 'inactive' ?>">
                            <?= $voucher->is_active ? 'Active' : 'Inactive' ?>
                        </div>
                    </div>
                    <h3 class="voucher-name"><?= htmlspecialchars($voucher->name) ?></h3>
                    <p class="voucher-code">Code: <strong>#<?= htmlspecialchars($voucher->id) ?></strong></p>
                </div>

                <div class="voucher-details">
                    <div class="detail-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Description</span>
                                <span class="detail-value"><?= htmlspecialchars($voucher->description ?? 'N/A') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Created By</span>
                                <span class="detail-value">Admin #<?= htmlspecialchars($voucher->id_admin) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Validity Period</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Start Date</span>
                                <span class="detail-value">
                                    <?= $voucher->date_start ? date('F j, Y', strtotime($voucher->date_start)) : 'Immediate' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">End Date</span>
                                <span class="detail-value">
                                    <?= $voucher->date_end ? date('F j, Y', strtotime($voucher->date_end)) : 'No expiration' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Duration</span>
                                <span class="detail-value">
                                    <?php
                                    if ($voucher->date_start && $voucher->date_end) {
                                        $start = new DateTime($voucher->date_start);
                                        $end = new DateTime($voucher->date_end);
                                        echo $start->diff($end)->format('%a days');
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">System Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Created At</span>
                                <span class="detail-value">
                                    <?= $voucher->created_at ? date('F j, Y \a\t g:i A', strtotime($voucher->created_at)) : 'Unknown' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Updated</span>
                                <span class="detail-value">
                                    <?= $voucher->updated_at ? date('F j, Y \a\t g:i A', strtotime($voucher->updated_at)) : 'Never' ?>
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