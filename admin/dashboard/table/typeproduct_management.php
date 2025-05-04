<?php
require_once __DIR__ . "/../../../dao/TypeProductDao.php";
$typeProducts = $table_typeproduct->view_all();
define('ROOT_DIR', dirname(__DIR__));
?>

<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">

<div class=" type-product-management object-management active">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên loại sản phẩm</th>
                <th>Người tạo</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($typeProducts as $type): ?>
                <tr data-id="<?= $type->id ?>">
                    <td><?= $type->id ?></td>
                    <td>
                        <div class="type-name">
                            <?= htmlspecialchars($type->name) ?>
                        </div>
                    </td>
                    <td>
                        <?php 
                        // Giả sử có hàm lấy thông tin admin từ id
                        require_once __DIR__."/../../../dao/AdminDao.php";
                        $temp_admin = new AdminDao();
                        $adminName = $temp_admin->get_by_id($type->id_admin)->name;
                        echo $adminName ?? 'Unknown';
                        ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($type->created_at)) ?></td>
                    <td><?= $type->updated_at ? date('d/m/Y', strtotime($type->updated_at)) : 'Chưa cập nhật' ?></td>
                    <td class=' row button-update'>
                        <button class='action-btn view-btn' data-action='view-typeproduct' data-id='<?= $type->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update-typeproduct' data-id='<?= $type->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete-typeproduct' data-id='<?= $type->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="js/admin/CRUD_form.js"></script>