<?php
require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DTO/UserDTO.php';

session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$userDao = new UserDao();

$user_id = $_POST['user_id'];
$current_password = $_POST['current_password'];

// Verify current user or admin
if ($user_id != $_SESSION['user_id'] && !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Verify password
$user = $userDao->get_by_id($user_id);
if (!$user || $current_password!=$user->password) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Wrong password']);
    exit();
}

header('Content-Type: application/json');
echo json_encode(['success' => true]);