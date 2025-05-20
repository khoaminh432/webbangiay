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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/admin_style/style.css">
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/admin_style/loading.css">
    <link rel="stylesheet" href="css/admin_style/dashboard.css">
<link rel="stylesheet" href="css/admin_style/form/hide_show_form.css">

<link rel="stylesheet" href="css/admin_style/form/view/style.css">
    <script src="<?= ROOT_URL ?>/js/jquery-3.7.1.min.js"></script>
</head>
<body>
    <script src="<?= ROOT_URL ?>/js/admin/main.js"></script>
    <script src="<?= ROOT_URL ?>/js/admin/callbacks.js"></script>
    
    <script src="js/admin/dashboard_getviewtables.js"></script>
    
    <div id="order-modal-overlay" style="display:none;
position:fixed;
top:0;left:0;
width:100vw;
height:100vh;
background:rgba(24,28,36,0.7);
z-index:9998;"></div>
<div id="order-modal" 
style="display:none;
position:fixed;top:50%;
left:50%;
transform:translate(-50%,-50%);
z-index:9999;min-width:350px;
max-width:90vw;
max-height:90vh;
overflow:auto;
border-radius:14px;
box-shadow:0 8px 32px rgba(0,0,0,0.35);
background:#232837;"></div>

    <script src="js/admin/exchange_admin.js"></script>
    <script src="js/admin/statistic_dashboard.js"></script>
<script src="js/admin/statistic.js"></script>
    
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
            <?php include(ROOT_DIR."/admin/dashboard.php");?>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gán lại sự kiện cho các nút "Xem chi tiết"
    document.querySelectorAll('.view-order-detail').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var orderId = this.getAttribute('data-id');
            var modal = document.getElementById('order-modal');
            var overlay = document.getElementById('order-modal-overlay');
            if (!modal || !overlay) return;
            modal.innerHTML = '<div style="padding:32px 40px;color:#7ecbff;text-align:center;">Đang tải...</div>';
            modal.style.display = 'block';
            overlay.style.display = 'block';
            fetch('admin/statistic_infor/order_detail.php?id=' + orderId)
                .then(response => response.text())
                .then(html => {
                    modal.innerHTML = html;
                    var closeBtn = modal.querySelector('.close-modal');
                    if (closeBtn) closeBtn.onclick = closeModal;
                });
        });
    });
    // Đóng modal khi click overlay hoặc nhấn Escape
    function closeModal() {
        document.getElementById('order-modal').style.display = 'none';
        document.getElementById('order-modal-overlay').style.display = 'none';
        document.getElementById('order-modal').innerHTML = '';
    }
    var overlay = document.getElementById('order-modal-overlay');
    if (overlay) overlay.onclick = closeModal;
    if (!window._orderDetailModalKeydown) {
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") closeModal();
        });
        window._orderDetailModalKeydown = true;
    }
});
</script>

    
<script src="js/admin/CRUD_form.js"></script>
<script src="js/admin/checkstatus_object.js"></script>
<script src="js/admin/hideshow_form.js"></script>

    

</body>
</html>