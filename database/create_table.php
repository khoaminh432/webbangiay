<?php 
    include("database_sever.php");
    $database = new database_sever();
    
    
    $sql = "INSERT INTO product (id, name, gia) VALUES (:id, :name, :gia)";
    $param = [4,"giay the thao",15000];
    $database->insert_table($sql,$param);
?>