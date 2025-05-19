<?php require_once __DIR__."/initAdmin.php";?>
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin management</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/admin_style/style.css">
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/admin_style/loading.css">
    <script src="<?= ROOT_URL ?>/js/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="Admin-container row">
        <div class="left-menu column">
            <h1>Admin Panel</h1>
            <div class="Dashboard menu-btn" data-view="dashboard.php"><ion-icon name="clipboard-outline"></ion-icon> Bảng điều khiển</div>
            <div class="Infor_Admin menu-btn" data-view="information.php"><ion-icon name="person-outline"></ion-icon> Thông tin Admin</div>
            <div class="statictical menu-btn" data-view="dashboard_statistic.php"><ion-icon name="stats-chart-outline"></ion-icon> Thống kê doanh thu</div>
            <div class="permission menu-btn" data-view="permission_form.php"><ion-icon name="settings-outline"></ion-icon> Phân quyền</div>
            <div class="Setting menu-btn" data-view="dashboard/setting.php"><ion-icon name="settings-outline"></ion-icon> Cài đặt</div>
            <div class="Logout" id="btnLogout">
                <ion-icon name="log-out-outline"></ion-icon> Đăng xuất
            </div>
        </div>
        <div class="right-menu column" id="admin-content">
            <?php include(ROOT_DIR."/admin/dashboard.php");?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= ROOT_URL ?>/js/admin/main.js"></script>
    <script src="<?= ROOT_URL ?>/js/admin/callbacks.js"></script>
</body>
</html>