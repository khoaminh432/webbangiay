<?php
// filepath: c:\xampp\htdocs\webbangiay\handle\admin\functionpermission.php
require_once __DIR__."/../../initAdmin.php";
require_once __DIR__."/../../dao/RolePermissionDao.php";
$rolepermissiondao = new RolePermissionDao();
header('Content-Type: application/json');

// Giả sử quyền của admin lưu trong $_SESSION['admin_permission'] là mảng các quyền
$permissions = isset($_SESSION['admin_permission']) ? $_SESSION['admin_permission'] : [];

// Đọc dữ liệu JSON từ request
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Kiểm tra dữ liệu đầu vào
if (!is_array($data) || !isset($data['permission'])) {
    echo json_encode(['success' => false, 'message' => 'Giới hạn quyền.']);
    exit;
}

$requiredPermission = $data['permission'];

// Lấy quyền hiện tại, nếu là mảng thì lấy phần tử đầu tiên, nếu không thì ép về int
$currentPermission = 0;
if (isset($permissions['permission'])) {
    if (is_array($permissions['permission'])) {
        $currentPermission = (int)reset($permissions['permission']);
    } else {
        $currentPermission = (int)$permissions['permission'];
    }
}

// Kiểm tra quyền
$permiss = $rolepermissiondao->checkrole($adminDTO->position, $currentPermission | (int)$requiredPermission);

if ($permiss) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn không có quyền thực hiện thao tác này.'
    ]);
}