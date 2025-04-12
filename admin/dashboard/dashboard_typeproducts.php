<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí loại sản phẩm</h1>
        </div>
        <div class="search-container">
            <div class="search-bar">
                <div class="filter-bar" title="Bộ lọc nâng cao">
                    <ion-icon name="filter-circle"></ion-icon>
                    <span class="filter-text">Lọc</span>
                </div>
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                <button class="search-btn">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
            <button class="add-product-btn">
                <ion-icon name="add-circle-outline"></ion-icon>
                Thêm Loại Sản Phẩm
                
            </button>
        </div>
        
    </div>
    <?php require_once __DIR__."/form/typeproductadd_form.php";?>
    <?php require_once __DIR__."/table/typeproduct_management.php";?>
</div>