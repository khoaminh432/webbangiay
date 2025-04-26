<?php
require_once __DIR__ . '/../DAO/UserDAO.php';
require_once __DIR__ . '/../DTO/UserDTO.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

$userDao = new UserDao();

if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải từ 6 ký tự trở lên']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Xác nhận mật khẩu không đúng']);
        exit;
    }

    if ($userDao->email_exists($email)) {
        echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng']);
        exit;
    }

    $newUser = new UserDTO([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'status' => 'UNLOCK' 
    ]);

    $insertId = $userDao->insert($newUser);

    if ($insertId) {
        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi đăng ký']);
    }

} elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $userDao->get_by_email($email);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Email không tồn tại']);
        exit;
    }

    if ($user->status === 'LOCK') {
        echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị khóa']);
        exit;
    }

    if ($password === $user->password) {
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;

        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'redirect' => '../index.php',
            'username' => $user->username
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
