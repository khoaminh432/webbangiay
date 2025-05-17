
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
// Giả sử web root là thư mục 'public' trong 'webbangiay'
$web_root_relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR . '/css/admin_style/dashboard.css');

?>
<link rel="stylesheet" href="<?= $web_root_relative_path ?>">
<link rel="stylesheet" href="css/admin_style/form/hide_show_form.css">

<link rel="stylesheet" href="css/admin_style/form/view/style.css">
<div class="top-menu column">
    <div class="title-dashboard"><h1>Chào mừng bạn đến với trang Dashboard</h1></div>
    <div class="dashboard-nav row">
        <div class="nav-button ajax-load active column" data-mode="users">
            <ion-icon name="people-outline"></ion-icon>
            <span>Người dùng</span>
        </div>
        <div class="nav-button ajax-load column" data-mode="products">
            <ion-icon name="cube-outline"></ion-icon>
            <span>Sản phẩm</span>
        </div>
        <div class="nav-button ajax-load column" data-mode="vouchers">
            <ion-icon name="pricetag-outline"></ion-icon>
            <span>Voucher</span>
        </div>
        <div class="nav-button ajax-load column" data-mode="bills">
            <ion-icon name="receipt-outline"></ion-icon>
            <span>Hóa đơn</span>
        </div>
        <div class="nav-button ajax-load column" data-mode="paymentmethods">
            <ion-icon name="card-outline"></ion-icon>
            <span>Thanh toán</span>
        </div>
        <div class="nav-button ajax-load column" data-mode="typeproducts">
            <ion-icon name="card-outline"></ion-icon>
            <span>Loại Sản Phẩm</span>
        </div>
    </div>
</div>
<div class="main-content">
            <div id="content-area" >
                <!-- Nội dung sẽ được tải động ở đây -->
                <?php require_once __DIR__ . "/dashboard/dashboard_users.php";?>
            </div>

        </div>
        
