<?php
require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../../DTO/UserDTO.php';
require_once __DIR__ . '/../../DTO/InformationReceiveDTO.php';
require_once __DIR__ . '/../../helpers/validation.php';

session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    header("Location: /webbangiay/layout/login_signup.php");
    exit();
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

// Get user_id from request
$user_id = $_POST['user_id'] ?? null;
$current_user_id = $_SESSION['user_id'];

// Check permissions
if ($user_id != $current_user_id && !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header("Location: /webbangiay/index.php?page=info&error=unauthorized");
    exit();
}
$validationFile = __DIR__ . '/../../helpers/validation.php';
if (!file_exists($validationFile)) {
    error_log("Validation file not found: " . $validationFile);
    throw new Exception("Validation helper file not found");
}
require_once $validationFile;
// Handle user information update
if (isset($_POST['username']) && isset($_POST['email'])) {
    try {
        // Get form data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Validate data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=invalid_email");
            exit();
        }

        // Check if email already exists (excluding current user)
        $existingUser = $userDao->get_by_email($email);
        if ($existingUser && $existingUser->id != $user_id) {
            header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=email_exists");
            exit();
        }

        // Update user information
        $user = $userDao->get_by_id($user_id);
        if (!$user) {
            header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=user_not_found");
            exit();
        }

        // Create UserDTO with updated data
        $userData = [
            'id' => $user_id,
            'username' => $username,
            'email' => $email,
            'password' => $user->password, // Keep existing password
            'status' => $user->status,    // Keep existing status
            'created_at' => $user->created_at,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $updatedUser = new UserDTO($userData);
        $success = $userDao->update($updatedUser);

        if (!$success) {
            header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=update_failed");
            exit();
        }

        // Update shipping information
        $infoReceive = $informationReceiveDao->get_default_address($user_id);
        if (!$infoReceive) {
            // Create new InformationReceiveDTO if no default address exists
            $infoData = [
                'address' => $address,
                'name' => $name,
                'phone' => $phone,
                'id_user' => $user_id,
                'is_default' => true
            ];
            $infoReceive = new InformationReceiveDTO($infoData);
            $success = $informationReceiveDao->insert($infoReceive);
        } else {
            // Update existing address
            $infoReceive->address = $address;
            $infoReceive->name = $name;
            $infoReceive->phone = $phone;
            $infoReceive->is_default = true; // Ensure it remains default
            $success = $informationReceiveDao->update($infoReceive);
        }

        if (!$success) {
            header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=update_failed");
            exit();
        }

        header("Location: /webbangiay/index.php?page=info&user_id=$user_id&success=update_success");
        exit();

    } catch (Exception $e) {
        error_log("Update Error: " . $e->getMessage());
        header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=db_error");
        exit();
    }
}
// Handle password change
// Handle password change
if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    header('Content-Type: application/json');
    
    try {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_POST['user_id'];

        // Validate
        if (strlen($new_password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 8 ký tự']);
            exit();
        }

        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu mới không khớp']);
            exit();
        }

        // Get user and verify current password
        $user = $userDao->get_by_id($user_id);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
            exit();
        }

        if ($current_password!= $user->password) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
            exit();
        }

        // Update password
        $hashed_password = $new_password;
        $success = $userDao->update_password($user_id, $hashed_password);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật mật khẩu thất bại']);
        }
    } catch (Exception $e) {
        error_log("Password Update Exception: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit();
}

// Thêm ngay sau <?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../../DTO/UserDTO.php';
require_once __DIR__ . '/../../DTO/InformationReceiveDTO.php';

// Tắt hiển thị lỗi ra trình duyệt (chỉ log vào file)
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/../../logs/php_errors.log');

// ... phần còn lại của code như trước ...

// Trong phần xử lý password, đảm bảo luôn trả về JSON
if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    try {
        // ... code xử lý như trước ...
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi hệ thống',
            'error' => $e->getMessage() // Chỉ trả về trong môi trường dev
        ]);
        exit();
    }
}
// If no valid action
header("Location: /webbangiay/index.php?page=edit&user_id=$user_id&error=invalid_request");
exit();