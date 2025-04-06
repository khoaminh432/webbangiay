<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí hóa đơn</h1>
        </div>
        <div class="search-bar">
            <div class="filter-bar"><ion-icon name="filter-circle"></ion-icon></div>
            <input type="text" placeholder="Tìm kiếm hóa đơn...">
            <button class="search-btn">
            <ion-icon name="search-outline"></ion-icon> <!-- Icon tìm kiếm từ Font Awesome -->
            </button>
        </div>
    </div>
    
    <?php include("table/bill_management.php");?>
</div>