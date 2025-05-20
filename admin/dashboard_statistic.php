<?php 
// Thiết lập ROOT_DIR
if(!defined("ROOT_DIR")) {
    $root_dir = "webbangiay";
    $currentDir = __DIR__;
    while(true) {
        $pathArray = explode(DIRECTORY_SEPARATOR, $currentDir);
        $pathArray = array_filter($pathArray);
        $lastElement = end($pathArray);
        if ($lastElement == $root_dir) break;
        $currentDir = dirname($currentDir);
    }
    define('ROOT_DIR', preg_replace('/\\\\/', '/', $currentDir));
    define('ROOT_URL', str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT_DIR));
}
?>

<link rel="stylesheet" href="<?= ROOT_URL?>/css/admin_style/statistic/style.css">

<div class="tab-container">
    <div class="tab-header">
        <button type="button" class="tab-btn">Doanh thu theo sản phẩm</button>
        <button type="button" class="tab-btn">Top 5 khách hàng</button>
    </div>
    <div class="tab-content">
        <?php require_once __DIR__."/statistic_infor/Statisticfromproduct.php"; ?>
    </div>
    <div class="tab-content">
        <?php require_once __DIR__."/statistic_infor/statisticfromcustomer.php"; ?>
    </div>
</div>


