<?php
header('Content-Type: application/json');
require_once __DIR__ . '/dao/UserDao.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$status = $_POST['status'] ?? '1';

// Validate đơn giản
if (empty($username) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu trống']);
    exit;
}

$table_users = new UserDao();

$result = $table_users->insert(new UserDTO([
    'username' => $username,
    'email' => $email,
    'password' => $hashedPassword,
    'status' => $status
]));

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email hoặc Username đã tồn tại!']);
}
?>
