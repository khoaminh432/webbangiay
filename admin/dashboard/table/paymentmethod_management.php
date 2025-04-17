<?php
require_once __DIR__ . "/../../../dao/PaymentMethodDao.php";
$paymentMethods = $table_paymentmethode->view_all();
?>
<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">

<div class=" payment-method-management object-management active">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paymentMethods as $method): ?>
                <tr>
                    <td><?= $method->id ?></td>
                    <td>
                        <div class="method-name">
                            <?= htmlspecialchars($method->name) ?>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge <?= $method->is_active ? 'active' : 'inactive' ?>">
                            <?= $method->is_active ? 'Hoạt động' : 'Tạm ngừng' ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($method->created_at)) ?></td>
                    <td><?= $method->updated_at ? date('d/m/Y', strtotime($method->updated_at)) : 'Chưa cập nhật' ?></td>
                    <td class='row button-update'>
                        <button class='action-btn view-btn' data-action='view' data-id='<?= $method->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update' data-id='<?= $method->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete' data-id='<?= $method->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>