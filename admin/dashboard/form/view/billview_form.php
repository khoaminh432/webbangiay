<?php
require_once __DIR__ . "/../../../../dao/BillDao.php";
require_once __DIR__ . "/../../../../dao/UserDao.php";
require_once __DIR__ . "/../../../../dao/PaymentMethodDao.php";
require_once __DIR__ . "/../../../../dao/BillProductDao.php";

require_once __DIR__ . "/../../../../dao/ProductDao.php";
if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID hóa đơn!</p>");
}

$billId = (int)$_GET['id'];
$bill = $table_bills->get_by_id($billId);
$user = $table_users->get_by_id($bill->id_user);
$paymentMethod = $table_paymentmethode->get_by_id($bill->id_payment_method);
$orderItems = $table_billproducts->get_by_bill($billId);
?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformbills.css">
<div class="compact-bill-view">
    <div class="compact-header">
        <h3>Hóa đơn #<?= $bill->id ?></h3>
        <button class="compact-close" onclick="closeObjectView()">
            <ion-icon name="close-outline"></ion-icon>
        </button>
    </div>

    <div class="compact-body">
        <div class="bill-summary">
            <div class="summary-item">
                <span>Trạng thái:</span>
                <span class="status-badge status-<?= strtolower($bill->status) ?>">
                    <?= ucfirst($bill->status) ?>
                </span>
            </div>
            <div class="summary-item">
                <span>Ngày tạo:</span>
                <span><?= date('d/m/Y H:i', strtotime($bill->created_at)) ?></span>
            </div>
            <div class="summary-item">
                <span>Tổng tiền:</span>
                <span class="total-amount"><?= number_format($bill->total_amount, 0, ',', '.') ?>₫</span>
            </div>
        </div>

        <div class="compact-section">
            <h4>Thông tin khách hàng</h4>
            <div class="compact-grid">
                <div class="compact-item">
                    <span>Khách hàng:</span>
                    <span><?= htmlspecialchars($user->username ?? 'Khách vãng lai') ?></span>
                </div>
                <div class="compact-item">
                    <span>Địa chỉ:</span>
                    <span><?= htmlspecialchars($bill->shipping_address) ?></span>
                </div>
            </div>
        </div>

        <div class="compact-section">
            <h4>Thanh toán</h4>
            <div class="compact-grid">
                <div class="compact-item">
                    <span>Phương thức:</span>
                    <span><?= htmlspecialchars($paymentMethod->name ?? 'Không xác định') ?></span>
                </div>
            </div>
        </div>

        <div class="compact-section items-section">
            <h4>Sản phẩm (<?= count($orderItems) ?>)</h4>
            <div class="items-list">
                <?php foreach ($orderItems as $item): 
                    $product = $table_products->get_by_id($item->id_product);
                ?>
                <div class="row" style="gap: 10px;">
                    <div>ID: <?= $item->id_product;?></div>
                    <div>
                    số lượng: <?= $item->quantity;?>
                </div>
                </div>
                
                <div class="item-row">
                    
                    <div class="item-info">
                        <span class="item-name"><?= htmlspecialchars($product->name) ?></span>
                        <span class="item-price"><?= number_format($product->price, 0, ',', '.') ?>₫ × <?= $item->quantity ?></span>
                    </div>
                    <span class="item-total"><?= number_format($product->price * $item->quantity, 0, ',', '.') ?>₫</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="compact-footer">
        <button class="compact-btn print-btn" onclick="window.print()">
            <ion-icon name="print-outline"></ion-icon>
            In
        </button>
        <?php if ($bill->status === 'pending'): ?>
            <button class="compact-btn confirm-btn" onclick="confirmBill(<?= $bill->id ?>)">
                <ion-icon name="checkmark-outline"></ion-icon>
                Xác nhận
            </button>
            <button class="compact-btn cancel-btn" onclick="cancelBill(<?= $bill->id ?>)">
                <ion-icon name="close-outline"></ion-icon>
                Hủy
            </button>
        <?php endif; ?>
    </div>
</div>

<script src="js/admin/closeview_form.js"></script>
