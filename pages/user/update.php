<?php
require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../../DTO/UserDTO.php';
require_once __DIR__ . '/../../DTO/InformationReceiveDTO.php';

// Error reporting configuration
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/../../logs/php_errors.log');

session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    header("Location: /webbangiay/layout/login_signup.php");
    exit();
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

// Get user_id from request
$user_id = $_POST['user_id'] ?? $_SESSION['user_id'];
$current_user_id = $_SESSION['user_id'];

// Check permissions
if ($user_id != $current_user_id && !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header("Location: /webbangiay/pages/user/profile.php?error=unauthorized");
    exit();
}

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

// Handle password change
if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    try {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_POST['user_id'];

        // Validate
        if (strlen($new_password) < 8) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=password_length");
            exit();
        }

        if ($new_password !== $confirm_password) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=password_mismatch");
            exit();
        }

        // Get user and verify current password
        $user = $userDao->get_by_id($user_id);
        if (!$user) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=user_not_found");
            exit();
        }

        // Note: In production, use password_verify() here
        if ($current_password != $user->password) {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=wrong_password");
            exit();
        }

        // Update password
        // Note: In production, use password_hash() here
        $hashed_password = $new_password;
        $success = $userDao->update_password($user_id, $hashed_password);

        if ($success) {
            header("Location: /webbangiay/pages/user/profile.php?success=password_changed");
            exit();
        } else {
            header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=password_update_failed");
            exit();
        }
    } catch (Exception $e) {
        error_log("Password Update Exception: " . $e->getMessage());
        header("Location: /webbangiay/pages/user/edit_user.php?user_id=$user_id&error=system_error");
        exit();
    }
}

// If no valid action
header("Location: /webbangiay/pages/user/profile.php?error=invalid_request");
exit();