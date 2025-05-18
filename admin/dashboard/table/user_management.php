<?php
require_once __DIR__ . "/../../../dao/UserDao.php";

$table_users = new UserDao();

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$status = $_POST['status'] ?? '';

// Lấy tất cả user
$users = $table_users->view_all();

// Lọc user
$filtered_users = array_filter($users, function($user) use ($username, $email, $status) {
    // Kiểm tra username (nếu có điều kiện)
    if (!empty($username) && stripos($user->username, $username) === false) {
        return false;
    }
    // Kiểm tra email (nếu có điều kiện)
    if (!empty($email) && stripos($user->email, $email) === false) {
        return false;
    }
    
    // Kiểm tra status (nếu có điều kiện)
    if (!empty($status) && strtolower($user->status) !== strtolower($status)) {
        return false;
    }
    
    // Nếu tất cả điều kiện đều thỏa mãn (hoặc không có điều kiện)
    return true;
});
?>
<?php require_once __DIR__."/../form/useradd_form.php";?>
<script src="js/admin/CRUD_form.js"></script>
<script src="js/admin/checkstatus_object.js"></script>
    <link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">
    <div class=" account-management object-management active">
    <table class="data-table">
        <thead>
        <tr>
        <th>ID</th>
        <th>Tên tài khoảng</th>
        <th>Email</th>
        <th>Trạng thái</th>
        <th>Quyền</th>
        <th>Hoạt động</th></tr>
    </thead>    
    <tbody>
        <?php if (empty($filtered_users)):?>
        <tr><td colspan="8" style="text-align: center; font-size: 1.5em;">
                Không có người dùng
            </td></tr>
        <?php else: ?>
        <?php foreach($filtered_users as $user): ?>
        <tr data-id="<?= $user->id ?>">
        <td><?= $user->id ?></td>
        <td><?= $user->username ?></td>
        <td><?= $user->email ?></td>
        <td class="status-bill status-<?= strtolower($user->status) ?> ">
                        <select  name="objectId" class="styled-select status-select" data-object-id="<?=$user->id?>">
                            <option value="User-UNLOCK"  <?= $user->status == "UNLOCK" ? 'selected' : '' ?>>UNLOCK</option>
                            <option value="User-LOCK"  <?= $user->status == "LOCK" ? 'selected' : '' ?>>LOCK</option>                
            </select>
        </td>
        
        <td>1</td>
        <td class='row button-update'>
        <button class='action-btn view-btn' data-action='view-user' data-id='<?= $user->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update-user' data-id='<?= $user->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete-user' data-id='<?= $user->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
        </td>
        </tr>        
        <?php endforeach; ?>
        <?php endif;?>
    </tbody>
    </table>
</div>

