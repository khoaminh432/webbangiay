<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<link rel="stylesheet" href="css/admin_style/dashboard.css">
<div class="top-menu column">
                <div class="title-dashboard"><h1>Chào mừng bạn đến với trang Dashboard</h1></div>
                <div class="dashboard-nav row">
            <div class="nav-button active column" data-view="users">
                <ion-icon name="people-outline"></ion-icon>
                <span>Người dùng</span>
            </div>
            <div class="nav-button column" data-view="products">
                <ion-icon name="cube-outline"></ion-icon>
                <span>Sản phẩm</span>
            </div>
            <div class="nav-button column" data-view="vouchers">
                <ion-icon name="pricetag-outline"></ion-icon>
                <span>Voucher</span>
            </div>
            <div class="nav-button column" data-view="bills">
                <ion-icon name="receipt-outline"></ion-icon>
                <span>Hóa đơn</span>
            </div>
            <div class="nav-button column" data-view="payments">
                <ion-icon name="card-outline"></ion-icon>
                <span>Thanh toán</span>
            </div>
        </div>
            </div>
            <div class="main-menu">
                <?php include("dashboard/dashboard_paymentmethod.php");?>
            </div>