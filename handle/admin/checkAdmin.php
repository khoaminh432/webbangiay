<?php
require_once __DIR__."/../../initAdmin.php";
header('Content-Type: application/json');
$isAdmin = false;
require_once __DIR__."/../../dao/RoleDao.php";
$roledao = new RoleDao();
if (strtolower($roledao->get_role_by_id($adminDTO->position)->role_name)==="admin")
$isAdmin = true;
if ($isAdmin) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Chỉ admin mới được thực hiện thao tác này.'
    ]);
}
?>