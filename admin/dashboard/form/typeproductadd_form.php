<link rel="stylesheet" href="css/admin_style/form/addformpaymentmethod_style.css">
<div class="formadd-object-container column hidden">
<h1>Thêm Loại Sản Phẩm Mới</h1>
<div class="close-form-btn"><ion-icon name="close-circle-outline"></ion-icon></div>
<form class="add-form" action="admin/dashboard/table/typeproduct_management.php" method="POST">
    <div class="form-group">
        <label for="name">Tên loại sản phẩm:</label>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div class="form-group">
        <label>Trạng thái:</label>
        <div class="status-options">
            <label>
                <input type="radio" name="is_active" value="1" checked> Hoạt động
            </label>
            <label>
                <input type="radio" name="is_active" value="0"> Tạm ngừng
            </label>
        </div>
    </div>
    <button type="submit">Thêm Loại Sản Phẩm</button>
</form>
</div>