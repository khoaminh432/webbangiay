<?php require_once __DIR__."/../../../../initAdmin.php";
require_once __DIR__."/../../../../handle/admin/functionpermission.php";?>
<?php
require_once __DIR__ . "/../../../../dto/VoucherDTO.php";
require_once __DIR__ . "/../../../../dao/VoucherDao.php";
require_once __DIR__ . "/../../../../dao/AdminDao.php";

if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID voucher!</p>");
}

$voucherId = (int)$_GET['id'];
$voucher = $table_vouchers->get_by_id($voucherId);
$admin = $table_admins->get_by_id($voucher->id_admin); // Assuming you have an AdminDao

?>
<link rel="stylesheet" href="css/admin_style/form/edit/editformvoucher_style.css">
<div class="voucher-edit-model">
    <form id="voucherEditForm" class="object-edit-card">
        <input type="hidden" name="id" value="<?= $voucher->id ?>">
        <input type="hidden" name="object" value="voucher">
        <div class="card-header">
            <h2 class="card-title">Edit Voucher</h2>
            <div class="action-buttons">
                <button type="button" class="cancel-btn" onclick="closeObjectView()">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </div>

        <div class="card-body">
            <div class="voucher-profile">
                <div class="voucher-identity">
                    <div class="voucher-badge">
                        <div class="discount-input">
                            <input type="number" name="deduction" min="0" max="100" step="0.01" 
                                   value="<?= number_format($voucher->deduction, 2) ?>" required>%
                        </div>
                        <div class="status-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" <?= $voucher->is_active ? 'checked' : '' ?>>
                                <span class="slider round"></span>
                            </label>
                            <span class="toggle-label"><?= $voucher->is_active ? 'Active' : 'Inactive' ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="voucherName">Voucher Name</label>
                        <input type="text" id="voucherName" name="name" 
                               value="<?= htmlspecialchars($voucher->name) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="voucherCode">Voucher Code</label>
                        <input type="text" id="voucherCode" name="code" 
                               value="<?= htmlspecialchars($voucher->id) ?>" readonly>
                    </div>
                </div>

                <div class="voucher-details">
                    <div class="detail-section">
                        <h4 class="section-title">Basic Information</h4>
                        <div class="form-group">
                            <label for="voucherDescription">Description</label>
                            <textarea id="voucherDescription" name="description" rows="3"><?= htmlspecialchars($voucher->description ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="voucherAdmin">Created By</label>
                            <select id="voucherAdmin" name="id_admin">
                                    <option value="<?= $admin->id ?>" <?= $voucher->id_admin == $admin->id ? 'selected' : '' ?>>
                                        Admin #<?= htmlspecialchars($admin->id) ?> <?= htmlspecialchars($admin->name) ?> (<?= htmlspecialchars($admin->email) ?>)
                                    </option>
                            </select>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Validity Period</h4>
                        <div class="date-grid">
                            <div class="form-group">
                                <label for="startDate">Start Date</label>
                                <input type="date" id="startDate" name="date_start" 
                                       value="<?= $voucher->date_start ? htmlspecialchars($voucher->date_start) : '' ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="endDate">End Date</label>
                                <input type="date" id="endDate" name="date_end" 
                                       value="<?= $voucher->date_end ? htmlspecialchars($voucher->date_end) : '' ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="no_expiration" id="noExpiration" 
                                       <?= !$voucher->date_end ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                No expiration date
                            </label>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">System Information</h4>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Created At</span>
                                <span class="info-value">
                                    <?= $voucher->created_at ? date('F j, Y \a\t g:i A', strtotime($voucher->created_at)) : 'Unknown' ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Last Updated</span>
                                <span class="info-value">
                                    <?= $voucher->updated_at ? date('F j, Y \a\t g:i A', strtotime($voucher->updated_at)) : 'Never' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="js/admin/closeview_form.js"></script>
<script src="js/admin/Edit_form.js"></script>
<script>
// Toggle end date field based on no expiration checkbox
document.getElementById('noExpiration').addEventListener('change', function() {
    const endDateField = document.getElementById('endDate');
    endDateField.disabled = this.checked;
    if (this.checked) {
        endDateField.value = '';
    }
});

// Initialize end date field state
document.addEventListener('DOMContentLoaded', function() {
    const noExpiration = document.getElementById('noExpiration');
    const endDateField = document.getElementById('endDate');
    endDateField.disabled = noExpiration.checked;
});
</script>