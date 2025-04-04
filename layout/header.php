<?php
    require_once __DIR__ . '/../dao/UserDao.php';
    $a = new UserDao();
    $a->delete(1);
?>      