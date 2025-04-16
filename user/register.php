<?php
require_once __DIR__ . '/../DAO/UserDao.php';
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
session_start();

// Security check - verify user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit_user.php?error=invalid_request");
    exit();
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

// Get user ID from session or POST data
$current_user_id = $_SESSION['user']['id'];
$user_id = $_POST['user_id'] ?? $current_user_id;

// Verify permissions (users can only edit their own profile unless admin)
if ($user_id != $current_user_id && !(isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'])) {
    header("Location: edit_user.php?user_id=$current_user_id&error=unauthorized");
    exit();
}

// Get existing user data
$user = $userDao->get_by_id($user_id);
if (!$user) {
    header("Location: error.php?code=404");
    exit();
}

// Process form data
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$status = isset($_POST['status']) ? 1 : 0;
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Validate inputs
$errors = [];

// Username validation
if (empty($username)) {
    $errors[] = 'username_required';
}

// Email validation
if (empty($email)) {
    $errors[] = 'email_required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'invalid_email';
} elseif ($email != $user->email && $userDao->email_exists($email)) {
    $errors[] = 'email_exists';
}

// Password validation
if (!empty($password)) {
    if (strlen($password) < 8) {
        $errors[] = 'password_short';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'password_mismatch';
    }
}

// If there are errors, redirect back with error messages
if (!empty($errors)) {
    $error = $errors[0]; // Get the first error
    header("Location: edit_user.php?user_id=$user_id&error=$error");
    exit();
}

// Update user data
$update_data = [
    'username' => $username,
    'email' => $email
];

// Only update password if provided
if (!empty($password)) {
    $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
}

// Only update status if admin
if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']) {
    $update_data['status'] = $status;
}

// Perform the update
$success = $userDao->update($user_id, $update_data);

if (!$success) {
    header("Location: edit_user.php?user_id=$user_id&error=update_failed");
    exit();
}

// Update address information
$address_data = [
    'address' => $address,
    'phone' => $phone,
    'user_id' => $user_id,
    'is_default' => 1
];


// Redirect with success message
header("Location: user_site.php?success=1");
exit();
?>