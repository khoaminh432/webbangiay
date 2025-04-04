<?php
    require_once __DIR__ . '/../dao/UserDao.php';
    $a = new UserDao();
    $array = $a->view_all();
    foreach($array as $item){
        echo $item->id."\n";
    }
?>      