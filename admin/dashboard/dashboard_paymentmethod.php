<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí phương thức thanh toán</h1>
        </div>
        <div class="search-bar">
            <div class="filter-bar"><ion-icon name="filter-circle"></ion-icon></div>
            <input type="text" placeholder="Tìm kiếm phương thức...">
            <button class="search-btn">
            <ion-icon name="search-outline"></ion-icon> <!-- Icon tìm kiếm từ Font Awesome -->
            </button>
        </div>
    </div>
    
    <?php include("table/paymentmethod_management.php");?>
</div>