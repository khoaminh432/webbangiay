

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
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin management</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="css/admin_style/style.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    
</head>
<body>
    <div class="Admin-container row">
        
        <div class="left-menu column">
        <h1>Admin Panel</h1>
        <div class="Dashboard"><ion-icon name="clipboard-outline"></ion-icon> Bảng điều khiển</div>
        <div class="Infor_Admin"><ion-icon name="person-outline"></ion-icon> Thông tin Admin</div>
        <div class="statictical"><ion-icon name="stats-chart-outline"></ion-icon> Thống kê doanh thu</div>
        <div class="Setting"><ion-icon name="settings-outline"></ion-icon> Cài đặt</div>
        <div class="Logout"><ion-icon name="log-out-outline"></ion-icon> Đăng xuất</div>
    </div>
    
        <div class="right-menu column">
            <?php include("admin/dashboard.php");?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/admin/dashboard_getviewtables.js"></script>
    <script src="js/admin/hideshow_form.js"></script>
</body>
</html>