
<link rel="stylesheet" href="css/admin_style/dashboard_object.css">
<div class="dashboard-bar">
    <div class="header-container">
        <div class="title">
            <h1>Quản lí sản phẩm</h1>
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
            <button class="add-object-btn add-product-btn">
                <ion-icon name="add-circle-outline"></ion-icon>
                Thêm Sản Phẩm
            </button>
        </div>
        
    </div>
    <script src="js/admin/filterobject_form.js"></script>
    
    <div class="content-object-container">
        

        <?php require_once __DIR__."/table/product_management.php";?>
    </div>
    <div class="form-view-modal" id="objectViewModal"></div>
    <div class="form-filter-modal" id="objectFilterModal">
    <div class="filter-modal" id="filterModal" style="display: none;">
        <div class="filter-content">
            <h2>Bộ lọc Sản Phẩm</h2>
            <form id="filterForm">
                <div class="form-group-bill">
                    <label>Tên sản phẩm:</label>
                    <input type="text" name="product_name" placeholder="Nhập tên sản phẩm">
                </div>
                <!-- Price Range -->
                <div class="row"><div class="form-group-bill">
                    <label>Giá từ:</label>
                    <input type="number" name="min_price" placeholder="Giá thấp nhất" min="0">
                </div>
                <div class="form-group-bill">
                    <label>Đến giá:</label>
                    <input type="number" name="max_price" placeholder="Giá cao nhất" min="0">
                </div>
                <div class="form-group-bill">
                    <label>Số lượng tồn:</label>
                    <select name="stock_status">
                        <option value="">Tất cả</option>
                        <option value="21">Còn hàng</option>
                        <option value="20">Sắp hết hàng</option>
                        <option value="0">Hết hàng</option>
                    </select>
                </div>
            </div>
                
                <!-- Stock Quantity -->
                
                
                
                
                <!-- Search by name -->
                <div class="row">
                    
                <div class="form-group-bill">
                    <label>Loại sản phẩm:</label>
                    <select name="product_type">
                        <option value="">Tất cả</option>
                        <?php
                        require_once __DIR__."/../../dao/TypeProductDao.php";
                        $table_typeproduct = new TypeProductDao();
                        $types = $table_typeproduct->view_all();
                        foreach ($types as $type): ?>
                            <option value="<?= $type->id ?>"><?= htmlspecialchars($type->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Status -->
                <div class="form-group-bill">
                    <label>Trạng thái:</label>
                    <select name="status">
                        <option value="">Tất cả</option>
                        <option value="1">Đang bán</option>
                        <option value="0">Ngừng bán</option>
                    </select>
                </div>
                <!-- Weight range -->
                <div class="form-group-bill">
                    <label>Trọng lượng từ (g):</label>
                    <input type="number" name="min_weight" placeholder="Trọng lượng nhỏ nhất" min="0">
                </div>
                <div class="form-group-bill">
                    <label>Đến (g):</label>
                    <input type="number" name="max_weight" placeholder="Trọng lượng lớn nhất" min="0">
                </div></div>
                
                <!-- Buttons -->
                <div class="button-group">
                    <button type="submit" class="apply-btn">Áp dụng</button>
                    <button type="button" class="cancel-btn" 
                            onclick="document.getElementById('filterModal').style.display='none'">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <style>
        /* === FILTER MODAL STYLE === */   
.filter-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: auto;
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
            position: relative;
            padding-bottom: 10px;
        }

        .filter-content h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: #007BFF;
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
            font-size: 14px;
        }

        .form-group-bill input,
        .form-group-bill select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group-bill input:focus,
        .form-group-bill select:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            gap: 15px;
        }

        .apply-btn,
        .cancel-btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }

        .apply-btn {
            background-color: #007BFF;
            color: #fff;
        }

        .apply-btn:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
        }

        .cancel-btn {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .cancel-btn:hover {
            background-color: #e2e2e2;
            transform: translateY(-2px);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .filter-modal {
                width: 90%;
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }
        }
        .row{
            display: flex;
            flex-direction: row;
        }
        </style>
    
    
    

</div>
