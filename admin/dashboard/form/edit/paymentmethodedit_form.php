<?php 


require_once __DIR__ . "/../../../../dao/PaymentMethodDao.php";
require_once __DIR__."/../../../../initAdmin.php";
require_once __DIR__."/../../../../handle/admin/functionpermission.php";

if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID phương thức thanh toán!</p>");
}

$object_id = (int)$_GET['id'];
$paymentMethod = $table_paymentmethode->get_by_id($object_id);
?>

<link rel="stylesheet" href="css/admin_style/form/edit/editformpaymentmethod_style.css">

<div class="paymentmethod-edit-model">
    <form id="paymentMethodEditForm" class="object-edit-card">
        <input type="hidden" name="id" value="<?= $paymentMethod->id ?>">
        <input type="hidden" name="object" value="paymentmethod">

        <div class="card-header">
            <h2 class="card-title">Edit Payment Method</h2>
            <div class="action-buttons">
                <button type="button" class="cancel-btn" onclick="closeObjectView()">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label for="pmName">Name</label>
                <input type="text" id="pmName" name="name" value="<?= htmlspecialchars($paymentMethod->name) ?>" required>
            </div>

            <div class="form-group status-toggle">
                <label class="toggle-switch">
                    <input type="checkbox" name="is_active" <?= $paymentMethod->is_active ? 'checked' : '' ?>>
                    <span class="slider round"></span>
                </label>
                <span class="toggle-label">Active</span>
            </div>

            <div class="form-group">
                <label for="createdAt">Created At</label>
                <input type="text" id="createdAt" value="<?= $paymentMethod->created_at ? date('Y-m-d H:i:s', strtotime($paymentMethod->created_at)) : 'Unknown' ?>" readonly>
            </div>

            <div class="form-group">
                <label for="updatedAt">Last Updated</label>
                <input type="text" id="updatedAt" value="<?= $paymentMethod->updated_at ? date('Y-m-d H:i:s', strtotime($paymentMethod->updated_at)) : 'Never' ?>" readonly>
            </div>
        </div>
        <input type="hidden" name="id_admin" value="<?= $adminDTO->id ?>">
    </form>
</div>

<script src="js/admin/closeview_form.js"></script>
<script src="js/admin/Edit_form.js"></script>
