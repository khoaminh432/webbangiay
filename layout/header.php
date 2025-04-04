<?php
    require_once __DIR__ . '/../dao/';
    $a = new UserDao();
    $a->delete(1);
?>      