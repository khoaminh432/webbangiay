<?php
// Tự động xác định thư mục gốc (giả sử có thư mục 'vendor' hoặc 'public' làm mốc)
$root_dir = "webbangiay";
$lastElement = "";
$currentDir = __DIR__;
while(true){
    $pathArray = explode(DIRECTORY_SEPARATOR, $currentDir);
    $pathArray = array_filter($pathArray); // Loại bỏ phần tử rỗng
    $lastElement = array_slice($pathArray, -1)[0];
    if ($lastElement==$root_dir)
        break;
    $currentDir = dirname($currentDir);
}
define('ROOT_DIR', $currentDir);
include(ROOT_DIR."/admin.php")
?>
