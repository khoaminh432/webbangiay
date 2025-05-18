<?php
    require_once __DIR__."/../../initAdmin.php";
    require_once __DIR__."/../../dao/RolePermissionDao.php";
$rolepermissiondao = new RolePermissionDao();
$permission =$_SERVER['REQUEST_METHOD'] === 'GET'? $_GET["permission"]:$_POST["permission"];
    function checkrole(){
        global $rolepermissiondao,$adminDTO,$permission;
        return !$rolepermissiondao->checkrole($adminDTO->position,$permission);}
    if (checkrole()){
    echo "<script>Swal.fire('Lỗi','Giới hạn quyền.','error');</script>";
    exit;
}
?>