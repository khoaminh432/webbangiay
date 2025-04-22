<?php
header('Content-Type: application/json');
require_once 'connect_database.php';

$action = $_POST['action'] ?? '';

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

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng']);
        exit;
    }

    $status = 'UNLOCK';
    $stmt = $conn->prepare("INSERT INTO users (email, password, username) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $password, $username);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi đăng ký']);
    }
    exit;

} elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; 

    $stmt = $conn->prepare("SELECT id, username, password, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Email không tồn tại']);
        exit;
    }

    $stmt->bind_result($id, $username, $dbPassword, $status);
    $stmt->fetch();

    if ($status !== 'UNLOCK') {
        echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị khóa']);
        exit;
    }

    if ($password === $dbPassword) {
        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'redirect' => '../index.php',
            'username' => $username
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng']);
    }
    exit;

} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>
