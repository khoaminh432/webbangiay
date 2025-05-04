<link rel="stylesheet" href="css/admin_style/form/addformpaymentmethod_style.css">
<div class="formadd-object-container column hidden ">
<h1>Add New Payment Method</h1>
<div class="close-form-btn"><ion-icon name="close-circle-outline"></ion-icon></div>
<form class="add-form" action="admin/dashboard/table/payment_method_management.php" method="POST" onsubmit="return validatePaymentMethodForm()">
<input type="text" name="object-add-title" value="paymentmethod" style="display:none;">    
<div class="form-group">
        <label for="name">Payment Method Name*:</label>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="fee">Processing Fee (%):</label>
            <input type="number" id="fee" name="fee" min="0" max="100" step="0.01">
        </div>
        
        <div class="form-group">
            <label for="processing_time">Processing Time (days):</label>
            <input type="number" id="processing_time" name="processing_time" min="0">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="account_name">Account Name:</label>
            <input type="text" id="account_name" name="account_name">
        </div>
        
        <div class="form-group">
            <label for="account_number">Account Number:</label>
            <input type="text" id="account_number" name="account_number">
        </div>
    </div>
    
    <div class="form-group">
        <label for="instructions">Payment Instructions:</label>
        <textarea id="instructions" name="instructions"></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="icon_class">Icon Class:</label>
            <input type="text" id="icon_class" name="icon_class" placeholder="fa fa-credit-card">
        </div>
        
        <div class="form-group">
            <label for="sort_order">Sort Order:</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="0">
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
    
    <div class="form-group">
        <label for="payment_type">Payment Type:</label>
        <select id="payment_type" name="payment_type">
            <option value="bank">Bank Transfer</option>
            <option value="card">Credit/Debit Card</option>
            <option value="ewallet">E-Wallet</option>
            <option value="cod">Cash on Delivery</option>
        </select>
    </div>
    
    <input type="hidden" name="id_admin" value="1"> <!-- Giả sử admin ID là 1 -->
    
    <button type="submit">Add Payment Method</button>
</form>
</div>


<script>
    function validatePaymentMethodForm() {
        // Lấy giá trị từ form
        const name = document.getElementById('name').value.trim();
        const fee = parseFloat(document.getElementById('fee').value);
        
        // Kiểm tra các trường bắt buộc
        if (!name) {
            alert('Please enter payment method name');
            return false;
        }
        
        if (!isNaN(fee) && (fee < 0 || fee > 100)) {
            alert('Processing fee must be between 0 and 100');
            return false;
        }
        
        // Hiển thị thông báo xác nhận
        return confirm(`Are you sure you want to add this payment method?\n\nName: ${name}`);
    }
</script>