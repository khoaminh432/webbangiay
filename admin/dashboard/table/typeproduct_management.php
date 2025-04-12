<?php
require_once __DIR__ . "/../../../dao/TypeProductDao.php";
$typeProductDao = new TypeProductDao();
$typeProducts = $typeProductDao->view_all();
define('ROOT_DIR', dirname(__DIR__));
?>

<?php //thêm thông tin từ form

// Xử lý khi form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typeProductData = [
        'name' => $_POST['name'] ?? '',
        'id_admin' => 1 // Giả sử admin ID là 1 (trong thực tế lấy từ session)
    ];
    
    $typeProduct = new TypeProductDTO($typeProductData);
    // TODO: Thêm code lưu vào CSDL ở đây
    $typeProductDao->insert($typeProduct);
    // Chuyển hướng sau khi thêm thành công
    header('Location: typeproduct_management.php?success=1');
    exit();
}
?>
<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">

<div class="type-product-management">
    <div class="table-header">
        <h2>Quản lý Loại Sản Phẩm</h2>
        <button class="add-new-btn" onclick="openAddTypeProductModal()">
            <ion-icon name="add-outline"></ion-icon> Thêm mới
        </button>
    </div>
    
    <table>
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
                <tr>
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
                    <td class='action-buttons'>
                        <button class='action-btn view-btn' data-action='view' data-id='<?= $type->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update' data-id='<?= $type->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete' data-id='<?= $type->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Thêm/Sửa -->
<div id="typeProductModal" class="modal">
    <!-- Nội dung modal sẽ được thêm bằng JS -->
</div>

<script>
// Script xử lý tương tự như payment method
function openAddTypeProductModal() {
    // Gọi AJAX để lấy form thêm loại sản phẩm
    fetch('admin/get_type_product_form.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('typeProductModal').innerHTML = html;
            openModal('typeProductModal');
        });
}

function openEditTypeProductModal(id) {
    // Gọi AJAX để lấy form sửa với dữ liệu hiện có
    fetch(`admin/get_type_product_form.php?id=${id}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('typeProductModal').innerHTML = html;
            openModal('typeProductModal');
        });
}
</script>