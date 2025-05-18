<?php 
    session_start();
    require_once __DIR__."/dao/AdminDao.php";
    
// auth.php

function getLoggedInAdmin() {
    // Nếu chưa đăng nhập
    if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
        return null;
    }

    // Tạo thể hiện DAO nếu cần
    global $table_admins;
    if (!isset($table_admins)) {
        $table_admins = new AdminDao(); // đảm bảo khởi tạo đúng class
    }

    return $table_admins->login($_SESSION['email'], $_SESSION['password']);
}
$adminDTO = getLoggedInAdmin();
?>