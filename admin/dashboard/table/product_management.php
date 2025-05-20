
<?php
// Tự động xác định thư mục gốc (giả sử có thư mục 'vendor' hoặc 'public' làm mốc)
if(!defined("ROOT_DIR"))
{$root_dir = "webbangiay";
$lastElement = "";
$currentDir = __DIR__;
while(true){
$pathArray = explode(DIRECTORY_SEPARATOR, $currentDir);
$pathArray = array_filter($pathArray); // Loại bỏ phần tử rỗng
$lastElement = array_slice($pathArray, -1)[0];
if ($lastElement==$root_dir)
    break;
$currentDir = dirname($currentDir);
}
define('ROOT_DIR', preg_replace('/\\\\/', '/', $currentDir));}

?>
<?php
require_once __DIR__ . "/../../../dao/ProductDao.php";
$table_products = new ProductDao();

// Lấy dữ liệu lọc
$min_price = $_POST['min_price'] ?? null;
$max_price = $_POST['max_price'] ?? null;
$product_type = $_POST['product_type'] ?? '';
$status = $_POST['status'] ?? '';
$product_name = $_POST['product_name'] ?? '';
$min_weight = $_POST['min_weight'] ?? null;
$max_weight = $_POST['max_weight'] ?? null;
$stock_status = $_POST['stock_status'] ?? '';
$products= $table_products->view_all(true);
// Hàm xử lý logic lọc tùy theo điều kiện
$filters = [
    'min_price' => $min_price,
    'max_price' => $max_price,
    'product_type' => $product_type,
    'status' => $status,
    'product_name' => $product_name,
    'min_weight' => $min_weight,
    'max_weight' => $max_weight,
    'stock_status' => $stock_status,
];
$filter_products =array_filter($products, function($product) use ($filters){
        if (!empty($filters['min_price']) &&
        intval($filters['min_price'])>$product->price ) {
            return false;
        }
        if (!empty($filters['max_price']) && 
        intval($filters['max_price'])< $product->price) {
            return false;
        }
        if (!empty($filters['min_weight']) && 
        intval($filters['min_weight'])> $product->weight) {
            return false;
        }
        if (!empty($filters['max_weight']) && 
        intval($filters['max_weight'])< $product->weight) {
            return false;
        }
        if (!empty($filters['product_type']) && 
        intval(($filters['product_type']))!== $product->id_type_product) {
            return false;
        }
        if (!empty($filters['status']) && 
        ((int)($filters['status']))!== ($product->is_active)) {
            return false;
        }
        if (!empty($filters['product_name'])  && 
        stripos(($filters['product_name']), $product->name)===false) {
            return false;
        }
        if (isset($filters['stock_status']) && $filters['stock_status'] !== '') {
        $qty = $product->quantity;
        $temp = intval($filters['stock_status']);
        switch ($filters['stock_status']) {
            case '21':  // Còn hàng (>10)
                if ($qty <= $temp) return false;
                break;
            case '20':  // Sắp hết (1-10)
                if ($qty < 1 || $qty > $temp) return false;
                break;
            case '0':   // Hết hàng
                if ($qty !== 0) return false;
                break;
        }
    }
        return true;
        });

// Render lại bảng HTML tương tự phần tbody ở file chính
?>
<?php require_once __DIR__."/../form/productadd_form.php";?>
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
            <?php if (empty($filter_products)):?>
        <tr><td colspan="8" style="text-align: center; font-size: 1.5em;">
                Không có Loại sản phẩm
            </td></tr>
            <?php else: ?>
            <?php foreach ($filter_products as $product): ?>
                <tr data-id="<?= $product->id ?>">
                    <td><?= $product->id ?></td>
                    <td>
                        <div class="product-name">
                            <?= $product->name ?>
                        </div>
                    </td>
                    <td><?= number_format($product->price, 0, ',', '.') ?>đ</td>
                    <td><?= $product->quantity ?></td>
                    <td><?= $product->weight ?>g</td>
                    <td><?php require_once __DIR__."/../../../dao/TypeProductDao.php";
                        $name_type = $table_typeproduct->get_by_id($product->id_type_product)->name;
                    ?>
                        <?= $product->id_type_product." ($name_type)" ?>
                        </td>
                    <td class="status-product status-<?= strtolower($product->is_active) ?> ">
                        <select  name="objectId" class="styled-select status-select" data-object-id="<?=$product->id?>">
                            <option value="Product-true" <?= $product->is_active == true ? 'selected' : '' ?>>Hoạt Động</option>
                            <option value="Product-false" <?= $product->is_active == false ? 'selected' : '' ?>>Ngừng Bán</option>                
                        </select>
                    </td>
                    <td><?= htmlspecialchars(substr($product->description ?? 'Không có mô tả', 0, 50)) . (strlen($product->description ?? '') > 50 ? '...' : '') ?></td>
                    <td class='row button-update'>
                        <button class='action-btn view-btn' data-action='view-product' data-id='<?= $product->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update-product' data-id='<?= $product->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete-product' data-id='<?= $product->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif;?>
        </tbody>
    </table>
</div>
