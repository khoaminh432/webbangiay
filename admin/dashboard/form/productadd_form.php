<link rel="stylesheet" href="css/admin_style/form/addformproduct_sytle.css">
<h1>Add New Product</h1>
    <form class="add-form" action="admin/dashboard/table/product_management.php" method="POST" onsubmit="return validateProductForm()">
        <div class="form-group">
            <label for="name">Product Name*:</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="price">Price*:</label>
                <input type="number" id="price" name="price" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity*:</label>
                <input type="number" id="quantity" name="quantity" min="0" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" id="weight" name="weight" min="0" step="0.01">
            </div>
            
            <div class="form-group">
                <label for="id_type_product">Product Type:</label>
                <select id="id_type_product" name="id_type_product">
                    <?php require_once __DIR__."/../../../dao/TypeProductDao.php";
                    $temp_teble_typeproducts = new TypeProductDao();
                    $temp_typeproducts = $temp_teble_typeproducts->view_all();
                    ?>
                    <option value="">-- Select Type --</option>
                    <?php foreach($temp_typeproducts as $typeproduct):?>
                    <option value=<?= $typeproduct->id;?>><?=$typeproduct->name;?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="id_voucher">Voucher:</label>
                <select id="id_voucher" name="id_voucher">
                    <option value="">-- No Voucher --</option>
                    <option value="1">Voucher 1</option>
                    <option value="2">Voucher 2</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_supplier">Supplier:</label>
                <select id="id_supplier" name="id_supplier">
                    <?php require_once __DIR__."/../../../dao/SupplierDao.php";
                    $temp_table_suppliers = new SupplierDao();
                    $temp_suppliers = $temp_table_suppliers->view_all();
                    ?>
                <option value="">-- Select Supplier --</option>
                    <?php foreach($temp_suppliers as $supplier):?>
                    <option value=<?= $supplier->id;?>><?=$supplier->name;?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>Status:</label>
            <div class="status-options">
                <label>
                    <input type="radio" name="is_active" value="1" checked> Active
                </label>
                <label>
                    <input type="radio" name="is_active" value="0"> Inactive
                </label>
            </div>
        </div>
        
        <input type="hidden" name="id_admin" value="1"> <!-- Giả sử admin ID là 1 -->
        
        <button type="submit">Add Product</button>
    </form>

    <script>
        function validateProductForm() {
            // Lấy giá trị từ form
            const name = document.getElementById('name').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const quantity = parseInt(document.getElementById('quantity').value);
            
            // Kiểm tra các trường bắt buộc
            if (!name) {
                alert('Please enter product name');
                return false;
            }
            
            if (isNaN(price) || price <= 0) {
                alert('Please enter a valid price (greater than 0)');
                return false;
            }
            
            if (isNaN(quantity) || quantity < 0) {
                alert('Please enter a valid quantity (non-negative number)');
                return false;
            }
            
            // Hiển thị thông báo xác nhận
            return confirm(`Are you sure you want to add this product?\n\nName: ${name}\nPrice: $${price.toFixed(2)}\nQuantity: ${quantity}`);
        }
    </script>
