<?php 
    session_start();
    require_once __DIR__."/dao/AdminDao.php";
    $adminDTO = $table_admins->login($_SESSION['email'],$_SESSION['password']);
?>