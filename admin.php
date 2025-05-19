<?php require_once __DIR__."/initAdmin.php";?>
<?php
if(!defined("ROOT_DIR"))
{$root_dir = "webbangiay";
$lastElement = "";
$currentDir = __DIR__;
while(true){
$pathArray = explode(DIRECTORY_SEPARATOR, $currentDir);
$pathArray = array_filter($pathArray);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="css/admin_style/style.css">
    <script src="js/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="Admin-container row">
        <div class="left-menu column">
            <h1>Admin Panel</h1>
            <div class="Dashboard menu-btn" data-view="dashboard.php"><ion-icon name="clipboard-outline"></ion-icon> Bảng điều khiển</div>
            <div class="Infor_Admin menu-btn" data-view="information.php"><ion-icon name="person-outline"></ion-icon> Thông tin Admin</div>
            <div class="statictical menu-btn" data-view="dashboard_statistic.php"><ion-icon name="stats-chart-outline"></ion-icon> Thống kê doanh thu</div>
            <div class="permission menu-btn" data-view="permission_form.php"><ion-icon name="settings-outline"></ion-icon> Phân quyền</div>
            <div class="Logout" id="btnLogout">
                <ion-icon name="log-out-outline"></ion-icon> Đăng xuất
            </div>
        </div>
        <div class="right-menu column" id="admin-content">
            <?php include("admin/dashboard.php");?>
        </div>
    </div>
    <script src="js/admin/dashboard_getviewtables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="js/admin/hideshow_form.js"></script>
    <script src="js/admin/statistic_dashboard.js"></script>
    <script>

function reloadAdminContent(html) {
    document.getElementById('admin-content').innerHTML = html;
    // Gọi lại các hàm khởi tạo sự kiện cho nội dung mới
    if (typeof initTabs === 'function') initTabs();
    // Nếu có các hàm khởi tạo khác, gọi thêm ở đây
    if (typeof initStatisticProductForm === 'function') initStatisticProductForm();
    if (typeof initStatisticCustomerForm === 'function') initStatisticCustomerForm();
}

document.querySelectorAll('.menu-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        var view = this.getAttribute('data-view');
        document.querySelectorAll('.menu-btn').forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('admin-content').innerHTML = '<div style="padding:40px;text-align:center;color:#7ecbff;">Đang tải...</div>';
        fetch('admin/' + view)
            .then(res => res.text())
            .then(html => {
                console.log(html)
                reloadAdminContent(html);
                history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
            });
    });
});
document.getElementById('btnLogout').addEventListener('click', function(){
    window.location.href = 'logout.php';
});

// Gọi lại initTabs khi trang load lần đầu
window.addEventListener('DOMContentLoaded', function() {
    if (typeof initTabs === 'function') initTabs();
    if (typeof initStatisticProductForm === 'function') initStatisticProductForm();
    if (typeof initStatisticCustomerForm === 'function') initStatisticCustomerForm();
});
</script>

</body>
</html>