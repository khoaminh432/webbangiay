<?php
require_once __DIR__ . '/../DAO/UserDao.php';
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../DTO/UserDTO.php';
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';

session_start();

// 1. Security check - verify user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

// 2. Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit_user.php?error=invalid_request");
    exit();
}

// Initialize DAOs
$db = // your database connection here
$userDao = new UserDao($db);
$informationReceiveDao = new InformationReceiveDao($db);

// Get user ID
$current_user_id = $_SESSION['user']['id'];
$user_id = $_POST['user_id'] ?? $current_user_id;

// Verify permissions
if ($user_id != $current_user_id) {
    header("Location: edit_user.php?user_id=$current_user_id&error=unauthorized");
    exit();
}

// Get existing user data
try {
    $user = $userDao->get_by_id($user_id);
    if (!$user) {
        header("Location: error.php?code=404");
        exit();
    }
} catch (Exception $e) {
    header("Location: edit_user.php?user_id=$user_id&error=db_error");
    exit();
}

// Process form data
$username = trim(htmlspecialchars($_POST['username'] ?? ''));
$email = trim(htmlspecialchars($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$address = trim(htmlspecialchars($_POST['address'] ?? ''));
$phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
$name = trim(htmlspecialchars($_POST['name'] ?? ''));

// Validate inputs
$errors = [];

if (empty($username)) {
    $errors[] = 'username_required';
} elseif (strlen($username) > 50) {
    $errors[] = 'username_too_long';
}

if (empty($email)) {
    $errors[] = 'email_required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'invalid_email';
} elseif ($email != $user->email && $userDao->email_exists($email)) {
    $errors[] = 'email_exists';
}

if (!empty($password)) {
    if (strlen($password) < 8) {
        $errors[] = 'password_short';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'password_mismatch';
    }
}

if (!empty($errors)) {
    header("Location: edit_user.php?user_id=$user_id&error=" . urlencode($errors[0]));
    exit();
}

// Prepare UserDTO
$userData = [
    'id' => $user_id,
    'username' => $username,
    'email' => $email
];

if (!empty($password)) {
    $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
}

$userDTO = new UserDTO($userData);

// Update user
try {
    $userUpdated = $userDao->update($userDTO);
    if (!$userUpdated) {
        header("Location: edit_user.php?user_id=$user_id&error=update_failed");
        exit();
    }
} catch (Exception $e) {
    header("Location: edit_user.php?user_id=$user_id&error=db_error");
    exit();
}

// Handle address information
$addressData = [
    'address' => $address,
    'name' => $name,
    'phone' => $phone,
    'id_user' => $user_id,
    'is_default' => true
];


// Redirect with success
header("Location: user_site.php?user_id=$user_id&success=1");
exit();
?>