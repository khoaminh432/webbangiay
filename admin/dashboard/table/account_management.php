<?php
    require_once __DIR__ . "/../../../dao/UserDao.php";
    $table_users = new UserDao();
    $users = $table_users->view_all();
    ?>
    <link rel="stylesheet" href="css/admin_style/dashboard/account_management.css">
    <link rel="stylesheet" href="css/admin_style/dashboard//table_main.css">
    <div class="account-management">
    <table>
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
    <?= $user->status ?>
</td>
        <td>1</td>
        <td class='row button-update'>
        <button class='action-btn view-btn' data-action='view' data-id='<?= $bill->id ?>'>
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class='action-btn edit-btn' data-action='update' data-id='<?= $bill->id ?>'>
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class='action-btn delete-btn' data-action='delete' data-id='<?= $bill->id ?>'>
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
        </td>
        </tr>
        
            
        <?php endforeach; ?></tbody>
    </table>
</div>