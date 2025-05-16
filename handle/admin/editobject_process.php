<?php


header('Content-Type: application/json');
$success = false;
$result = "Chưa chọn";
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Phương thức không hợp lệ");
    }
    $object = $_POST['object'];
    switch ($object){
        
    case "product":
            require_once __DIR__."/../../dao/ProductDao.php";
            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $price = (float)$_POST['price'];
            $quantity = $table_products->get_by_id($id)->quantity;
            $weight = (float)$_POST['weight'];
            $id_type_product = (int)$_POST['id_type_product'];
            $id_supplier = (int)$_POST['id_supplier'];
            $id_voucher = $_POST['id_voucher'] ? (int)$_POST['id_voucher'] : null;
            $description = $_POST['description'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $id_admin = $_POST['id_admin'] ? (int)$_POST['id_admin'] : null;
            // Handle image upload if any
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $targetDir = __DIR__ . "/../../img/";
                $filename = time() . "_" . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $filename;
            
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    throw new Exception("Lỗi khi lưu ảnh!");
                }
            
                // Lưu đường dẫn ảnh
                $image_path = "img/" . $filename;
            }
        
            global $table_products;
            $product = $table_products->get_by_id($id);
            if (!$product) {
                throw new Exception("Sản phẩm không tồn tại");
            }
            $productDTO = new ProductDTO([
                "id" => $id,
                "name" => $name,
                "price" => $price,
                "quantity" => $quantity,
                "weight" => $weight,
                "id_type_product" => $id_type_product,
                "id_supplier" => $id_supplier,
                "id_voucher" => $id_voucher,
                "description" => $description,
                "id_admin" => $id_admin,
                "is_active" => $is_active,
                "image_url" => $image_path ?? $product->image_url  // giữ ảnh cũ nếu không thay đổi
            ]);

            $success = $table_products->update($productDTO);
            $message = $success?"Sửa sản phẩm thành công":"Sửa sản phẩm thất bại";
            break;
    case "typeproduct":
            require_once __DIR__."/../../dao/TypeProductDao.php";
            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $id_admin = $_POST['id_admin'] ? (int)$_POST['id_admin'] : null;

            global $table_typeproduct;
            $typeProduct = $table_typeproduct->get_by_id($id);
            if (!$typeProduct) {
                throw new Exception("Loại sản phẩm không tồn tại");
            }

            $typeProductDTO = new TypeProductDTO([
                "id" => $id,
                "name" => $name,
                "is_active" => $is_active,
                "id_admin" => $id_admin
            ]);
            
            $success = $table_typeproduct->update($typeProductDTO);
            $message = $success ? "Cập nhật loại sản phẩm thành công" : "Cập nhật loại sản phẩm thất bại";
            break;
    case "voucher":
            require_once __DIR__."/../../dao/VoucherDao.php";
            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $deduction = (float)$_POST['deduction'];
            $description = $_POST['description'] ?? null;
            $date_start = $_POST['date_start'] ? $_POST['date_start'] : null;
            $date_end = isset($_POST['no_expiration']) ? null : ($_POST['date_end'] ? $_POST['date_end'] : null);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $id_admin = (int)$_POST['id_admin'];

            global $table_vouchers;
            $voucher = $table_vouchers->get_by_id($id);
            if (!$voucher) {
                throw new Exception("Voucher không tồn tại");
            }
            
            $voucherDTO = new VoucherDTO([
                "id" => $id,
                "name" => $name,
                "deduction" => $deduction,
                "description" => $description,
                "date_start" => $date_start,
                "date_end" => $date_end,
                "is_active" => $is_active,
                "id_admin" => $id_admin
            ]);
            
            $success = $table_vouchers->update($voucherDTO);
            $message = $success ? "Cập nhật voucher thành công" : "Cập nhật voucher thất bại";
            break;
    
    case "user":
        require_once __DIR__."/../../dao/UserDao.php";
        $id = (int)$_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $password = $_POST['password'] ?? null;

        global $table_users;
        $user = $table_users->get_by_id($id);
        if (!$user) {
            throw new Exception("User not found");
        }
        
        // Prepare update data
        $updateData = [
            "id" => $id,
            "username" => $username,
            "email" => $email,
            "status" => $status,
        ];

        // Only update password if provided
        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        $userDTO = new UserDTO($updateData);
        $success = $table_users->update($userDTO);
        $message = $success ? "Cập nhật người dùng thành công" : "Cập nhật thất bại";
        break;
    case "paymentmethod":
        require_once __DIR__."/../../dao/PaymentMethodDao.php";
        $id = (int)$_POST['id'];
        $name = $_POST['name'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $id_admin = (int)$_POST['id_admin'];

        global $table_paymentmethode;
        $paymentMethod = $table_paymentmethode->get_by_id($id);
        if (!$paymentMethod) {
            throw new Exception("Payment method not found");
        }

        $paymentMethodDTO = new PaymentMethodDTO([
            "id" => $id,
            "name" => $name,
            "is_active" => $is_active,
            "id_admin" => $id_admin
        ]);

        $success = $table_paymentmethode->update($paymentMethodDTO);
        $message = $success ? "Cập nhật phương thức thanh toán thành công" : "Cập nhật thất bại";
        break;
}
    
    // Trả kết quả về client
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
} catch (Exception $e) {
    // Trả kết quả về client
    echo json_encode([
        'success' => $success,
        'message' => "yêu cầu không hợp lệ"
    ]);
    exit;
}
?>
