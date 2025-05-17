<?php 
require_once __DIR__."/../../dao/RolePermissionDao.php";
$rolePermissionDao = new RolePermissionDao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matrix = $_POST['matrix'] ?? [];

    foreach ($matrix as $roleId => $permissions) {
        $rolePermissionDao->delete_by_position($roleId);
        foreach ($permissions as $permissionId => $checked) {
            // Lưu quyền được chọn
            // Ví dụ: $rolePermissionDao->add_permission($roleId, $permissionId);
            $rolePermissionDao->insert(new RolePermissionDTO([
                "position_id"=>$roleId,
                "permission_id"=>$permissionId
            ]));
        }
    }
    echo "Lưu thành công!";
    exit;
    
}
?>