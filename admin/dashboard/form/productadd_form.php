<?php require_once __DIR__."/../../../initAdmin.php";?>
<link rel="stylesheet" href="css/admin_style/form/addformproduct_sytle.css">
<div class="formadd-object-container column hidden ">
<h1>Add New Product</h1>
<div class="close-form-btn"><ion-icon name="close-circle-outline"></ion-icon></div>
    <form class="add-form"  >
    <input type="text" name="object-add-title" value="product" style="display:none;">    
    <div class="form-group">
            <label for="name">Product Name*:</label>
            <input type="text" id="name" name="name" >
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
                    $temp_typeproducts = $table_typeproduct->view_all();
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
                <?php require_once __DIR__."/../../../dao/VoucherDao.php";
                  $temp_vouchers = $table_vouchers->view_all();  
                ?>
                <option value="">-- No Voucher --</option>
                    <?php foreach($temp_vouchers as $voucher):?>
                    <option value=<?= $voucher->id;?>><?=$voucher->name;?></option>
                    <?php endforeach; ?>
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
        
        <input type="hidden" name="id_admin" value="<?=$adminDTO->id?>"> <!-- Giả sử admin ID là 1 -->
        
        <button type="submit">Add Product</button>
    </form>
</div>
<script src="js/admin/Create_form.js"></script>