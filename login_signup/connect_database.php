<?php
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$dbname = 'bangiay'; 
$port = 3306;       

$conn = new mysqli($host, $user, $password, $dbname, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>