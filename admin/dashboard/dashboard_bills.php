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
    
    <div class="content-object-container"><?php include("table/bill_management.php");?></div>
    
    
    <style>
        .filter-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        padding: 25px 30px;
        font-family: 'Segoe UI', sans-serif;
    }

    .filter-content h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        font-size: 22px;
    }

    .form-group-bill {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }

    .form-group-bill label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #444;
    }

    .form-group-bill input,
    .form-group-bill select {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group-bill input:focus,
    .form-group-bill select:focus {
        outline: none;
        border-color: #007BFF;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .apply-btn,
    .cancel-btn {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .apply-btn {
        background-color: #007BFF;
        color: #fff;
    }

    .apply-btn:hover {
        background-color: #0056b3;
    }

    .cancel-btn {
        background-color: #f0f0f0;
        color: #333;
    }

    .cancel-btn:hover {
        background-color: #ddd;
    }

</style>    
    <div class="form-view-modal" id="objectViewModal"></div>
    <div class="form-filter-modal" id="objectFilterModal">
    <div class="filter-modal" id="filterModal" style="display: none;">
    <div class="filter-content">
        <h2>Bộ lọc hóa đơn</h2>
        <form id="filterForm">
            <div class="form-group-bill">
                <label>Ngày lập từ:</label>
                <input type="date" name="start_date">
            </div>
            <div class="form-group-bill">
                <label>Đến ngày:</label>
                <input type="date" name="end_date">
            </div>
            <div class="form-group-bill">
                <label>Trạng thái:</label>
                <select name="status">
                    <option value="">Tất cả</option>
                    <option value="paid">Đã thanh toán</option>
                    <option value="unpaid">Chưa thanh toán</option>
                </select>
            </div>
            <div class="form-group-bill">
                <label>Người tạo:</label>
                <input type="text" name="creator" placeholder="Tên người lập hóa đơn">
            </div>
            <div class="button-group">
                <button type="submit" class="apply-btn">Áp dụng</button>
                <button type="button" class="cancel-btn" onclick="
                document.getElementById('filterModal').style.display='none'
                //document.getElementById('objectFilterModal').style.display='none'
                ">Hủy</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<script src="js/admin/filterobject_form.js"></script>