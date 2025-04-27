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
                    <td class="status-method status-<?= strtolower($method->is_active) ?> ">
                        <select  name="objectId" class="styled-select status-select" data-object-id="<?=$method->id?>">
                            <option value="Method-true" <?= $method->is_active == true ? 'selected' : '' ?>>Hoạt Động</option>
                            <option value="Method-false" <?= $method->is_active == false ? 'selected' : '' ?>>Ngừng Hoạt Động</option>                
                        </select>
                    </td>
                    <td><?= date('d/m/Y', strtotime($method->created_at)) ?></td>
                    <td><?= $method->updated_at ? date('d/m/Y', strtotime($method->updated_at)) : 'Chưa cập nhật' ?></td>
                    <td class='row button-update'>
                        <button class='action-btn view-btn' data-action='view-paymentmethod' data-id='<?= $method->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update-paymentmethod' data-id='<?= $method->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete-paymentmethod' data-id='<?= $method->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="js/admin/CRUD_form.js"></script>
<script src="js/admin/checkstatus_object.js"></script>