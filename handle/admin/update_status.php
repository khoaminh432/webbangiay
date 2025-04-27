<?php
header('Content-Type: application/json');
// 3. Lấy dữ liệu từ AJAX request
$data = json_decode(file_get_contents("php://input"), true);

// 4. Validate dữ liệu đầu vào
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận phương thức POST']);
    exit;
}
if (empty($data['object_id']) || empty($data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin object_id hoặc status']);
    exit;
}

$objectId = $data['object_id'];
$newStatus = $data['status'];
$object = $data["object"];
// 5. Kiểm tra trạng thái hợp lệ
/*$validStatuses = ['processing', 'shipping', 'completed', 'cancelled'];
if (!in_array($newStatus, $validStatuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
    exit;
}*/

try {
    switch($object){
        case "Bill":
            require_once __DIR__ . ("/../../dao/$object"."Dao.php");
            //Cập nhật trạng thái trong database
            $result = $table_bills->update_status($objectId, $newStatus);
    
            break;
        case "User":
            require_once __DIR__ . ("/../../dao/$object"."Dao.php");
            $result = $table_users->update_status($objectId,$newStatus);
            break;
        case "Product":
            require_once __DIR__ . ("/../../dao/Product"."Dao.php");
            if($newStatus=="true")
                $newStatus = true;
            else $newStatus = false;
            $result = $table_products->update_active($objectId,$newStatus);
            break;
        case "Voucher":
            require_once __DIR__ . ("/../../dao/VoucherDao.php");
            if($newStatus=="true")
                $newStatus = true;
            else $newStatus = false;
            $result = $table_vouchers->update_active($objectId,$newStatus);
        case "Method":
            require_once __DIR__ . ("/../../dao/PaymentMethodDao.php");
            if($newStatus=="true")
                $newStatus = true;
            else $newStatus = false;
            $result = $table_paymentmethode->update_active($objectId,$newStatus);
            break;
        default:
            break;
    }
    
    if ($result) {
        // 7. Trả về response thành công
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => [
                'object_id' => $objectId,
                'new_status' => $newStatus
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Không thể cập nhật trạng thái'
        ]);
    }
} catch (PDOException $e) {
    // 8. Xử lý lỗi database
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi database: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // 9. Xử lý các lỗi khác
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi: ' . $e->getMessage()
    ]);
}
?>