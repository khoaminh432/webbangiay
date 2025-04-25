<?php
require_once __DIR__ . "/../../../dao/VoucherDao.php";
$vouchers = $table_vouchers->view_all();

?>
<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">

<div class=" voucher-management object-management active">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên voucher</th>
                <th>Giảm giá</th>
                <th>Mô tả</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Người tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vouchers as $voucher): ?>
                <tr>
                    <td><?= htmlspecialchars($voucher->id) ?></td>
                    <td><?= htmlspecialchars($voucher->name) ?></td>
                    <td><?= number_format($voucher->deduction, 2) ?>%</td>
                    <td><?= htmlspecialchars($voucher->description ?? 'Không có mô tả') ?></td>
                    <td><?= date('d/m/Y', strtotime($voucher->date_start)) ?></td>
                    <td><?= $voucher->date_end ? date('d/m/Y', strtotime($voucher->date_end)) : 'Không giới hạn' ?></td>
                    <td><?= htmlspecialchars($voucher->id_admin) ?></td>
                    <td>
                        <span class="status-badge <?= $voucher->is_active ? 'active' : 'inactive' ?>">
                            <?= $voucher->is_active ? 'Hoạt động' : 'Ngừng áp dụng' ?>
                        </span>
                    </td>
                    <td class='row button-update'>
                    <button class='action-btn view-btn' data-action='view-voucher' data-id='<?= $voucher->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update-voucher' data-id='<?= $voucher->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete-voucher' data-id='<?= $voucher->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="js/admin/CRUD_form.js"></script>