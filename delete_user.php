<?php
require_once __DIR__ . "/dao/UserDao.php";

// Đảm bảo luôn trả về JSON
header('Content-Type: application/json');

try {
    // Chỉ chấp nhận phương thức DELETE
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception('Phương thức không hợp lệ', 405);
    }

    // Lấy ID từ URL
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        throw new Exception('Thiếu ID tài khoản', 400);
    }
    // Thực hiện xóa
    $userDao = new UserDao();
    $result = $userDao->delete($id);

    if (!$result) {
        throw new Exception('Xóa tài khoản thất bại', 500);
    }

    // Trả về kết quả thành công
    echo json_encode([
        'success' => true,
        'message' => 'Xóa tài khoản thành công'
    ]);
    
} catch (Exception $e) {
    // Trả về lỗi
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}