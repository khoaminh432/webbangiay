<?php
session_start();
require_once __DIR__ . '/../../DAO/UserDao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /webbangiay/layout/error.php?code=405");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /webbangiay/layout/login_signup.php");
    exit();
}

$user_id = $_POST['user_id'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if ($user_id != $_SESSION['user_id']) {
    header("Location: /webbangiay/user/edit_user.php?user_id=$user_id&error=unauthorized");
    exit();
}

if (strlen($new_password) < 8) {
    header("Location: /webbangiay/user/edit_user.php?user_id=$user_id&error=password_short");
    exit();
}

if ($new_password !== $confirm_password) {
    header("Location: /webbangiay/user/edit_user.php?user_id=$user_id&error=password_mismatch");
    exit();
}

// Update password
$userDao = new UserDao();
$success = $userDao->update_password($user_id, $new_password);

if ($success) {
    header("Location: /webbangiay/user/edit_user.php?user_id=$user_id&success=1");
} else {
    header("Location: /webbangiay/user/edit_user.php?user_id=$user_id&error=update_failed");
}
exit();