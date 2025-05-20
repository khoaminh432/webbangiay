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
$permiss = $rolepermissiondao->checkrole($adminDTO->position,($permissions['permission'] ?? 0) | (int)$requiredPermission);
// Kiểm tra quyền
if ($permiss) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false , 'message' => 'Bạn không có quyền thực hiện thao tác này.'.$permissions]);
}?>