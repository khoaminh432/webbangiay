<?php
require_once __DIR__ . "/../../../dao/ProductDao.php";
$products = $table_products->view_all();
define('ROOT_DIR', dirname(__DIR__));

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $data = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'quantity' => $_POST['quantity'],
        'weight' => $_POST['weight'],
        'id_type_product' => $_POST['id_type_product'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'description' => $_POST['description']
    ];
    $addproduct = new ProductDTO($data);

    
    if ($table_products->insert($addproduct)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $data = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'quantity' => $_POST['quantity'],
        'weight' => $_POST['weight'],
        'id_type_product' => $_POST['id_type_product'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'description' => $_POST['description']
    ];
    
    if ($table_products->update($id, $data)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['action']) ){
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        if ($table_products->delete($id)) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Lấy thông tin sản phẩm để chỉnh sửa
$edit_product = null;
if (isset($_GET['action']) ){
    if ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $edit_product = $table_products->get_by_id($id);
    }
}
?>

<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class=" product-management object-management active">
    <!-- Bảng danh sách sản phẩm -->
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Trọng lượng</th>
                <th>Loại sản phẩm</th>
                <th>Trạng thái</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product->id ?></td>
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
                    <td><?= htmlspecialchars(substr($product->description ?? 'Không có mô tả', 0, 50)) . (strlen($product->description ?? '') > 50 ? '...' : '') ?></td>
                    <td class='row button-update'>
                        <a href='?page=product_management&action=edit&id=<?= $product->id ?>' class='action-btn edit-btn'>
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href='?page=product_management&action=delete&id=<?= $product->id ?>' 
                           class='action-btn delete-btn' 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và xử lý dữ liệu từ form
    $productData = [
        'name' => $_POST['name'] ?? '',
        'quantity' => isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0,
        'description' => $_POST['description'] ?? null,
        'price' => isset($_POST['price']) ? (float)$_POST['price'] : 0.0,
        'weight' => isset($_POST['weight']) ? (float)$_POST['weight'] : 0.0,
        'id_voucher' => !empty($_POST['id_voucher']) ? (int)$_POST['id_voucher'] : null,
        'id_type_product' => !empty($_POST['id_type_product']) ? (int)$_POST['id_type_product'] : null,
        'id_admin' => isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null,
        'id_supplier' => !empty($_POST['id_supplier']) ? (int)$_POST['id_supplier'] : null,
        'is_active' => isset($_POST['is_active']) ? (bool)$_POST['is_active'] : true,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Tạo đối tượng ProductDTO
    $productDTO = new ProductDTO($productData);
    $table_products->insert($productDTO);
    
}
    ?>