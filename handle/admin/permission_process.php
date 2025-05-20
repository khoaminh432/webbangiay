<?php
    require_once __DIR__."/../../initAdmin.php";
    require_once __DIR__."/../../dao/RolePermissionDao.php";
$rolepermissiondao = new RolePermissionDao();

$permission =$_SERVER['REQUEST_METHOD'] === 'GET'? $_GET["permission"]:$_POST["permission"];
    if(!$rolepermissiondao->checkrole($adminDTO->position,(int)$permission)){
        echo json_encode([
            'success' => false,
            'message' => 'Giới hạn quyền.'
        ]);
        exit;
    }
?>