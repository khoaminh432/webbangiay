<?php
// logout.php
session_start();
$_SESSION = [];            // Xóa tất cả biến session
session_destroy();        // Hủy session
header('Location: adminlogin.php'); // Hoặc đường dẫn tới form login của bạn
exit;
?>