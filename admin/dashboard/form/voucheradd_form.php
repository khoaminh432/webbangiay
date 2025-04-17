<link rel="stylesheet" href="css/admin_style/form/addformvoucher_style.css">
<div class="formadd-object-container column hidden" >
<h1>Add New Voucher</h1>
<div class="close-form-btn"><ion-icon name="close-circle-outline"></ion-icon></div>
<form class="add-form" action="admin/dashboard/table/voucher_management.php" method="POST" onsubmit="return validateVoucherForm()">
    <div class="form-group">
        <label for="name">Voucher Name*:</label>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="deduction">Deduction Value*:</label>
            <input type="number" id="deduction" name="deduction" min="0" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label for="min_order">Minimum Order:</label>
            <input type="number" id="min_order" name="min_order" min="0" step="0.01">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="date_start">Start Date:</label>
            <input type="date" id="date_start" name="date_start">
        </div>
        
        <div class="form-group">
            <label for="date_end">End Date:</label>
            <input type="date" id="date_end" name="date_end">
        </div>
    </div>
    
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="code">Voucher Code:</label>
            <input type="text" id="code" name="code">
        </div>
        
        <div class="form-group">
            <label for="usage_limit">Usage Limit:</label>
            <input type="number" id="usage_limit" name="usage_limit" min="0">
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
    
    <button type="submit">Add Voucher</button>
</form>
</div>