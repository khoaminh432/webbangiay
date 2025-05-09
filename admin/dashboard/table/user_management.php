
<?php
// Tự động xác định thư mục gốc (giả sử có thư mục 'vendor' hoặc 'public' làm mốc)
if(!defined("ROOT_DIR"))
{$root_dir = "webbangiay";
$lastElement = "";
$currentDir = __DIR__;
while(true){
$pathArray = explode(DIRECTORY_SEPARATOR, $currentDir);
$pathArray = array_filter($pathArray); // Loại bỏ phần tử rỗng
$lastElement = array_slice($pathArray, -1)[0];
if ($lastElement==$root_dir)
    break;
$currentDir = dirname($currentDir);
}
define('ROOT_DIR', preg_replace('/\\\\/', '/', $currentDir));}

?>
<?php
    require_once ROOT_DIR."/dao/UserDao.php";
    
    $users = $table_users->view_all();
    ?>
    <link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">
    <div class=" account-management object-management active">
    <table class="data-table">
        <thead><tr>
        <th>ID</th>
        <th>Tên tài khoảng</th>
        <th>Email</th>
        <th>Trạng thái</th>
        <th>Quyền</th>
        <th>Hoạt động</th></tr>
    </thead>    
    <tbody><?php foreach($users as $user): ?>
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
        
            
        <?php endforeach; ?></tbody>
    </table>
</div>

<script src="js/admin/CRUD_form.js"></script>
<script src="js/admin/checkstatus_object.js"></script>