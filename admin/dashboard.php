<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<link rel="stylesheet" href="css/admin_style/dashboard.css">

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

