<?php
require_once __DIR__ . "/../../../dao/BillDao.php";
require_once __DIR__ . "/../../../dao/UserDao.php";
require_once __DIR__ . "/../../../dao/PaymentMethodDao.php";

$bills = $table_bills->view_all();
?>

<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">
<div class="bill-management object-management active">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ngày tạo</th>
                <th>Khách hàng</th>
                <th>Phương thức TT</th>
                <th>Tổng tiền</th>
                <th>Địa chỉ giao</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bills as $bill): 
                $user = $table_users->get_by_id($bill->id_user);
                $paymentMethod = $table_paymentmethode->get_by_id($bill->id_payment_method);
            ?>
                <tr data-id="<?= $bill->id ?>">
                    <td><?= $bill->id ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($bill->bill_date)) ?></td>
                    <td>
                        <?= htmlspecialchars($user->username ?? 'N/A') ?>
                        <div class="user-email"><?= htmlspecialchars($user->email ?? '') ?></div>
                    </td>
                    <td><?= htmlspecialchars($paymentMethod->name ?? 'N/A') ?></td>
                    <td><?= number_format($bill->total_amount, 0, ',', '.') ?>đ</td>
                    <td><?= htmlspecialchars($bill->shipping_address) ?></td>
                    <td class="status-bill status-<?= strtolower($bill->status) ?>">
                        <?= $bill->status ?>
                    </td>
                    <td class='row button-update'>
                        <button class='action-btn view-btn' data-action='view-bill' data-id='<?= $bill->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        
                        <button class='action-btn delete-btn' data-action='delete-bill' data-id='<?= $bill->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="js/admin/CRUD_form.js"></script>