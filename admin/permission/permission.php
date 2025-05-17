<?php
 require_once __DIR__."/../../dao/RoleDao.php";
 require_once __DIR__."/../../dao/PermissionDao.php";
 require_once __DIR__."/../../dao/RolePermissionDao.php";
 $roleDao = new RoleDao();
 $permissionDao = new PermissionDao();
 $roles = $roleDao->get_all_roles();
 $permissions = $permissionDao->get_all_permissions();
 $rolePermissionDao = new RolePermissionDao();
?>
<style>
    .permission-form {
        background-color: #1e1e2f;
        color: #f0f0f0;
        font-family: 'Segoe UI', sans-serif;
        padding: 30px;
        border-radius: 10px;
        max-width: 100%;
        overflow-x: auto;
    }

    .permission-form h1 {
        text-align: center;
        color: #00bfff;
        margin-bottom: 20px;
    }

    .permission-form table {
        border-collapse: collapse;
        width: 100%;
        background-color: #2c2c3a;
        border: 1px solid #444;
        border-radius: 8px;
        overflow: hidden;
    }

    .permission-form table thead th {
        background-color: #3a3a4f;
        padding: 12px;
        font-weight: bold;
        border: 1px solid #555;
        color: #ffffff;
    }

    .permission-form table tbody td {
        border: 1px solid #555;
        padding: 10px;
        text-align: center;
    }

    .permission-form table tbody td:first-child {
        text-align: left;
        background-color: #333344;
        font-weight: 500;
    }

    .permission-form input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #00bfff;
    }

    .permission-form button[type="submit"] {
        display: block;
        margin: 20px auto 0;
        background-color: #00bfff;
        color: white;
        font-size: 16px;
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .permission-form button[type="submit"]:hover {
        background-color: #0099cc;
    }

    /* Responsive - optional */
    @media (max-width: 768px) {
        .permission-form table {
            font-size: 12px;
        }
        .permission-form button[type="submit"] {
            width: 100%;
        }
    }
</style>

<form id="permissionForm" class="permission-form">

    <h1>Phân Quyền Người Dùng</h1>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Permission \ Role</th>
                <?php foreach ($permissions as $permission): ?>
                    <th ><?= htmlspecialchars($permission->permission_name) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($roles as $role): ?>
        <?php if (strtolower($role->role_name) === "admin") continue; ?>
        <tr>
            <td><?= htmlspecialchars($role->role_name) ?></td>
            <?php foreach ($permissions as $permission): ?>
                <td style="text-align: center;">
                    <input 
                        type="checkbox" 
                        name="matrix[<?= $role->position_id ?>][<?= $permission->permission_id ?>]" 
                        <?= $rolePermissionDao->exists($role->position_id, $permission->permission_id) ? 'checked' : '' ?>
                    >
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>
    <br>
    <button type="submit">Lưu phân quyền</button>
</form>
<script src="js/admin/permissionForm.js"></script>