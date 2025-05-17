<?php
require_once '../database/database_sever.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$db = new database_sever();

// Lấy tham số tìm kiếm từ POST hoặc GET
$query = isset($_POST['query']) ? trim($_POST['query']) : (isset($_GET['query']) ? trim($_GET['query']) : '');
$category = isset($_POST['category']) ? $_POST['category'] : (isset($_GET['category']) ? $_GET['category'] : '');
$size = isset($_POST['size']) ? $_POST['size'] : (isset($_GET['size']) ? $_GET['size'] : '');
$min_price = isset($_POST['min_price']) ? $_POST['min_price'] : (isset($_GET['min_price']) ? $_GET['min_price'] : '');
$max_price = isset($_POST['max_price']) ? $_POST['max_price'] : (isset($_GET['max_price']) ? $_GET['max_price'] : '');
$brand = isset($_POST['brand']) ? $_POST['brand'] : (isset($_GET['brand']) ? $_GET['brand'] : '');
$color = isset($_POST['color']) ? $_POST['color'] : (isset($_GET['color']) ? $_GET['color'] : '');

$sql = "SELECT * FROM products WHERE 1=1";
$params = array();
if (!empty($query)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$query%";
}
if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}
if (!empty($size)) {
    $sql .= " AND size = ?";
    $params[] = $size;
}
if (!empty($min_price)) {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
}
if (!empty($max_price)) {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
}
if (!empty($brand)) {
    $sql .= " AND brand = ?";
    $params[] = $brand;
}
if (!empty($color)) {
    $sql .= " AND color = ?";
    $params[] = $color;
}
$products = $db->view_table($sql, $params);

include 'search_result_items.php'; 