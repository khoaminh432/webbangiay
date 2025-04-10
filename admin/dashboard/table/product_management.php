<?php
    require_once __DIR__ . "/../../../dao/ProductDao.php";
    $table_product = new ProductDao();
    $products = $table_product->view_all();
    define('ROOT_DIR', dirname(__DIR__));

?>
<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">
<link rel="stylesheet" href="css/admin_style/dashboard/product_management.css">
<div class="product-management">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Trọng lượng</th>
                <th>Loại sản phẩm</th>
                <th>Trạng thái</th>
                <th>mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                
                <tr>
                    <td><?= $product->id?></td>
                    <td>
                        <div class="product-name">
                            <?= $product->name ?>
                            
                        </div>
                    </td>
                    <td><?= number_format($product->price, 0, ',', '.') ?>đ</td>
                    <td><?= $product->quantity ?></td>
                    <td><?= $product->weight ?>g</td>
                    <td><?= $product->id_type_product ?></td>
                    <td>
                        <span class="status-badge <?= $product->is_active ? 'active' : 'inactive' ?>">
                            <?= $product->is_active ? 'Hoạt động' : 'Ngừng bán' ?>
                        </span>
                    </td>
                    <td><?=htmlspecialchars($product->description ?? 'Không có mô tả')?></td>
                    <td class='row button-update'>
                    <button class='action-btn view-btn' data-action='view' data-id='<?= $bill->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update' data-id='<?= $bill->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete' data-id='<?= $bill->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
        </td>   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
