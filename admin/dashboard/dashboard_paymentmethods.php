
<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí phương thức thanh toán</h1>
        </div>
        <div class="search-container">
        <div class="search-bar">
            <div class="filter-bar"><ion-icon name="filter-circle"></ion-icon>
            <span class="filter-text">Lọc</span></div>
            <input type="text" placeholder="Tìm kiếm phương thức...">
            <button class="search-btn">
            <ion-icon name="search-outline"></ion-icon> <!-- Icon tìm kiếm từ Font Awesome -->
            
            </button></div>
            <button class="add-object-btn add-voucher-btn">
                <ion-icon name="add-circle-outline"></ion-icon>
                Thêm phương thức thanh toán
            </button>
        
        </div>
        
    </div>
    <div class="form-view-modal">

    </div>
    <?php include("form/paymentmethodadd_form.php");?>
    <?php include("table/paymentmethod_management.php");?>
    
</div>