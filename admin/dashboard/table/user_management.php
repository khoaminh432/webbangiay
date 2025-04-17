<?php
    require_once __DIR__ . "/../../../dao/UserDao.php";
    
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
        <td class="status-user status-<?= strtolower($user->status) ?>">
    <span><?= $user->status ?></span>
</td>
        <td>1</td>
        <td class='row button-update'>
        <button class='action-btn view-btn' data-action='view' data-id='<?= $user->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update' data-id='<?= $user->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete' data-id='<?= $user->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
        </td>
        </tr>
        
            
        <?php endforeach; ?></tbody>
    </table>
</div>