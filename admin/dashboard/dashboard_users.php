
<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí người dùng</h1>
        </div>
        <div class="search-container">
            <div class="search-bar">
                <div class="filter-bar" title="Bộ lọc nâng cao">
                    <ion-icon name="filter-circle"></ion-icon>
                    <span class="filter-text">Lọc</span>
                </div>
                <input type="text" placeholder="Tìm kiếm người dùng...">
                <button class="search-btn">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
            <button class="add-object-btn add-user-btn">
                <ion-icon name="add-circle-outline"></ion-icon>
                Thêm người dùng
            </button>
        </div>
    </div>
    <?php require_once __DIR__."/form/view/userview_form.php";?>
    <?php require_once __DIR__."/form/useradd_form.php";?>
    
    <?php require_once __DIR__."/table/user_management.php";?>
</div>
