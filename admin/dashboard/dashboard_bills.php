<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí hóa đơn</h1>
        </div>
        <div class="search-container">
            <div class="search-bar">
                <div class="filter-bar" title="Bộ lọc nâng cao">
                    <ion-icon name="filter-circle"></ion-icon>
                    <span class="filter-text">Lọc</span>
                </div>
                <input type="text" placeholder="Tìm kiếm Hóa Đơn...">
                <button class="search-btn">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
            
        </div>
    </div>
    
    <?php include("table/bill_management.php");?>
    <div class="form-view-modal" id="objectViewModal">
        
    </div>
</div>