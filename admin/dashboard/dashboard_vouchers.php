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
<link rel="stylesheet" href="css/admin_style/dashboard_object.css">

<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1><ion-icon name="pricetags-outline" class="title-icon"></ion-icon> Quản lí Voucher</h1>
            <p class="subtitle">Kiểm soát và áp dụng các chương trình khuyến mãi</p>
        </div>
        <div class="search-container">
            <div class="search-bar">
                <div class="filter-bar" title="Bộ lọc nâng cao">
                    <ion-icon name="filter-circle"></ion-icon>
                    <span class="filter-text">Lọc</span>
                </div>
                <input type="text" placeholder="Tìm kiếm voucher...">
                <button class="search-btn">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
            <button class="add-object-btn add-voucher-btn">
                <ion-icon name="add-circle-outline"></ion-icon>
                Thêm Voucher
            </button>
        </div>
    </div>
    
    <?php include("form/voucheradd_form.php");?>
    <?php include("table/voucher_management.php");?>
    <div class="form-view-modal" id="objectViewModal">
        
    </div>
</div>