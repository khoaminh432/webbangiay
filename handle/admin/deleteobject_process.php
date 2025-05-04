<?php
// Trả kết quả về dạng JSON
header('Content-Type: application/json');

// Biến mặc định
$message = 'chưa chọn';
$success = false;

// Kiểm tra phương thức GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Nhận dữ liệu từ request
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $check = $_GET['check'] ?? null;

    // Kiểm tra dữ liệu đầu vào
    if (!$id || !$check) {
        echo json_encode([
            'success' => false,
            'message' => 'Thiếu thông tin ID hoặc loại đối tượng.'
        ]);
        exit;
    }

    // Xử lý xóa theo loại đối tượng
    switch ($check) {
        case "product":
            require_once __DIR__ . "/../../dao/ProductDao.php";
            $productDao = new ProductDao();

            // Kiểm tra sản phẩm đã có trong hóa đơn chưa
            if (!$productDao->check_billproduct($id)) {
                $message = "Sản phẩm đã có trong giao dịch, không thể xóa.";
            } else {
                $success = $productDao->delete($id);
                $message = $success ? "Xóa sản phẩm thành công." : "Không thể xóa sản phẩm.";
            }
            break;

        case "user":
            require_once __DIR__ . "/../../dao/UserDao.php";
            $userDao = new UserDao();
            if ($userDao->check_bill($id)) {
                $success = $userDao->delete($id);
                $message = $success ? "Xóa người dùng thành công." : "Không thể xóa người dùng.";
            } else {
                $message = "Người dùng đã có giao dịch, không thể xóa.";
            }
            break;

        case "typeproduct":
            require_once __DIR__ . "/../../dao/TypeProductDao.php";
            $typeDao = new TypeProductDao();

            // Kiểm tra loại sản phẩm có sản phẩm con không
            if (!$typeDao->check_product($id)) {
                $message = "Loại sản phẩm còn sản phẩm, không thể xóa.";
            } else {
                $success = $typeDao->delete($id);
                $message = $success ? "Xóa loại sản phẩm thành công." : "Không thể xóa loại sản phẩm.";
            }
            break;
            case "voucher":
                require_once __DIR__ . "/../../dao/VoucherDao.php";
                $voucherDao = new VoucherDao();
            
                // Kiểm tra voucher có liên quan hóa đơn không
                if (!$voucherDao->check_product($id)) {
                    $message = "Voucher đang được sử dụng, không thể xóa.";
                } else {
                    $success = $voucherDao->delete($id);
                    $message = $success ? "Xóa voucher thành công." : "Không thể xóa voucher.";
                }
                break;
            
            
            
            case "paymentmethod":
                require_once __DIR__ . "/../../dao/PaymentMethodDao.php";
                $paymentDao = new PaymentMethodDao();
            
                // Kiểm tra phương thức thanh toán có hóa đơn không
                if (!$paymentDao->check_bill($id)) {
                    $message = "Phương thức đang được sử dụng, không thể xóa.";
                } else {
                    $success = $paymentDao->delete($id);
                    $message = $success ? "Xóa phương thức thanh toán thành công." : "Không thể xóa phương thức thanh toán.";
                }
                break;
            
        default:
            $message = "Loại đối tượng không hợp lệ.";
    }

    // Trả kết quả về client
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

// Nếu không phải GET
echo json_encode([
    'success' => false,
    'message' => 'Yêu cầu không hợp lệ.'
]);
exit;
