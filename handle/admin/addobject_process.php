<?php
header('Content-Type: application/json');

try {
    $object = $_POST["object-add-title"] ?? "";
    $result = false;
    
    $message = "";

    if (empty($object)) {
        echo json_encode(["success" => false, "message" => "Thiếu thông tin object"]);
        exit;
    }

    switch ($object) {
        case "user":
            require_once __DIR__ . '/../../dao/UserDao.php';
            $table_users = new UserDao();
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $status = $_POST['status'] ?? '1';

            if ($table_users->email_exists($email)) {
                $message = "Email đã tồn tại rồi";
            } elseif ($table_users->username_exists($username)) {
                $message = "Username đã tồn tại rồi";
            } else {
                $userDTOadd = new UserDTO([
                    
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'status' => $status
                ]);
                $result = $table_users->insert($userDTOadd);
                $message = $result ? "Thêm người dùng thành công" : "Thêm thất bại";
            }
            break;

        case "product":
            require_once __DIR__ . '/../../dao/ProductDao.php';
            $table_products = new ProductDao();
            $name = $_POST['name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $weight = $_POST['weight'] ?? null;
            $id_type_product = $_POST['id_type_product'] ?: null;
            $description = $_POST['description'] ?? null;
            $id_voucher = $_POST['id_voucher'] ?: null;
            $id_supplier = $_POST['id_supplier'] ?: null;
            $is_active = $_POST['is_active'];
            $id_admin = $_POST['id_admin'];
            $productDTOadd = new ProductDTO([
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'weight' => $weight,
                'id_type_product' => $id_type_product,
                'description' => $description,
                'id_voucher' => $id_voucher,
                'id_supplier' => $id_supplier,
                'is_active' => $is_active,
                'id_admin' => $id_admin,
                'image_url' => $_POST['image_url'] ?? null // ← BỔ SUNG
            ]);
            if ($table_products->exists_by_dto($productDTOadd))
                $message = "Sản phẩm này đã tồn tại rồi!";
            else
            {
            $result = $table_products->insert($productDTOadd);
            $message = $result ? "Thêm sản phẩm thành công" : "Thêm thất bại";}
            
            break;
        case "typeproduct":
            require_once __DIR__."/../../dao/TypeProductDao.php";
            $table_typeproduct = new TypeProductDao();
            $nametype = $_POST["name"];
            $id_admin = $_POST["id_admin"];
            $status = $_POST["is_active"];
            $typeproductDTO = new TypeProductDTO([
                "name" => $nametype,
                "is_active" => $status,
                "id_admin" =>$id_admin
            ]);
            $result = $table_typeproduct->insert($typeproductDTO);
            $message = $result ? "Thêm loại sản phẩm thành công" : "Thêm thất bại";
            break;
        default:
            echo json_encode(["success" => false, "message" => "Object không hợp lệ"]);
            exit;
    }

    echo json_encode([
        'success' => $result,
        'message' => $message
    ]);
    exit;
    

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi máy chủ: ' . $e->getMessage()
    ]);
    exit;
    
}
?>
