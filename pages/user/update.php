<?php
require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../../DTO/UserDTO.php';
require_once __DIR__ . '/../../DTO/InformationReceiveDTO.php';

// Error handling configuration
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/../../logs/php_errors.log');

session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    if (isAjaxRequest()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized', 'redirect' => '/webbangiay/layout/login_signup.php']);
        exit();
    } else {
        header("Location: /webbangiay/layout/login_signup.php");
        exit();
    }
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

// Check if this is an AJAX request
$isAjax = isAjaxRequest();

// Get user_id from request
$user_id = $_POST['user_id'] ?? $_SESSION['user_id'];
$current_user_id = $_SESSION['user_id'];

// Check permissions
if ($user_id != $current_user_id && !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized', 'redirect' => '/webbangiay/pages/user/profile.php?error=unauthorized']);
        exit();
    } else {
        header("Location: /webbangiay/pages/user/profile.php?error=unauthorized");
        exit();
    }
}

// Handle password change (AJAX)
if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    header('Content-Type: application/json');
    $response = [
        'success' => false,
        'message' => '',
        'redirect' => null
    ];

    try {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_POST['user_id'];

        // Validate password
        if (strlen($new_password) < 4) {
            $response['message'] = 'Mật khẩu phải có ít nhất 4 ký tự';
            echo json_encode($response);
            exit();
        }

        if ($new_password !== $confirm_password) {
            $response['message'] = 'Mật khẩu mới không khớp';
            echo json_encode($response);
            exit();
        }

        // Get user and verify current password
        $user = $userDao->get_by_id($user_id);
        if (!$user) {
            $response['message'] = 'Người dùng không tồn tại';
            echo json_encode($response);
            exit();
        }

        // Verify current password (use password_verify() in production)
        if ($current_password != $user->password) {
            $response['message'] = 'Mật khẩu hiện tại không đúng';
            echo json_encode($response);
            exit();
        }

        // Update password (use password_hash() in production)
        $hashed_password = $new_password; // In production: password_hash($new_password, PASSWORD_DEFAULT)
        $success = $userDao->update_password($user_id, $hashed_password);

        if ($success) {
            $response['success'] = true;
            $response['message'] = 'Đổi mật khẩu thành công';
            $response['redirect'] = '/webbangiay/pages/user/profile.php?success=password_changed';
        } else {
            $response['message'] = 'Cập nhật mật khẩu thất bại';
        }
    } catch (Exception $e) {
        error_log("Password Update Exception: " . $e->getMessage());
        $response['message'] = 'Lỗi hệ thống: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit();
}

// Handle regular form submission (non-AJAX)
if (isset($_POST['username']) && isset($_POST['email'])) {
    try {
        // Get form data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Validate data
        if (empty($username) || empty($email)) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=empty_fields");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=invalid_email");
            exit();
        }

        // Check if email already exists (excluding current user)
        $existingUser = $userDao->get_by_email($email);
        if ($existingUser && $existingUser->id != $user_id) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=email_exists");
            exit();
        }

        // Update user information
        $user = $userDao->get_by_id($user_id);
        if (!$user) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=user_not_found");
            exit();
        }

        // Create UserDTO with updated data
        $userData = [
            'id' => $user_id,
            'username' => $username,
            'email' => $email,
            'password' => $user->password,
            'status' => $user->status,
            'created_at' => $user->created_at,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $updatedUser = new UserDTO($userData);
        $userSuccess = $userDao->update($updatedUser);

        // Update shipping information
        $infoReceive = $informationReceiveDao->get_default_address($user_id);
        $addressSuccess = true;
        
        if ($infoReceive) {
            // Update existing address
            $infoReceive->address = $address;
            $infoReceive->name = $name;
            $infoReceive->phone = $phone;
            $infoReceive->is_default = true;
            $addressSuccess = $informationReceiveDao->update($infoReceive);
        } else {
            // Create new address if none exists
            $infoData = [
                'address' => $address,
                'name' => $name,
                'phone' => $phone,
                'id_user' => $user_id,
                'is_default' => true
            ];
            $newInfo = new InformationReceiveDTO($infoData);
            $addressSuccess = $informationReceiveDao->insert($newInfo);
        }

        if ($userSuccess && $addressSuccess) {
            header("Location: /webbangiay/pages/user/profile.php?success=update_success");
            exit();
        } else {
            $errorType = !$userSuccess ? 'user_update_failed' : 'address_update_failed';
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=$errorType");
            exit();
        }

    } catch (Exception $e) {
        error_log("Update Error: " . $e->getMessage());
        header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=system_error");
        exit();
    }
}

// If no valid action
header("Location: /webbangiay/pages/user/profile.php?error=invalid_request");
exit();

// Helper function to check for AJAX requests
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}