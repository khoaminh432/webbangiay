
# -> cách thực thi hàm database/db.php/database_sever
# 1.insert_table
- $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)"
- $param = ['username' => 'john_doe','email' => 'john@example.com','password' => password_hash('secure123', PASSWORD_DEFAULT)];
# 2.view_table
- string_query = "câu truy vấn"
- return về một mảng dữ liệu trong bảng đó
- dùng for each để duyệt
- $tables = $database->view_table("select * from product")
-    foreach($tables as $table){
-       echo $table["id"]."-".$table["name"]."-".$table["gia"]."-".$table["loai"]."\n";   }
# 3.sửa lỗi no such dir
webbangiay/
├── FOLDER1/
│   └── file1.php (chứa tất cả các class DTO)
├── FOLDER2/
│   └── file1.php (chứa class database_sever)
├── dao/
│   └── file1.php
└── index.php
require_once __DIR__ . '/../folder1/file1.php';
(khai báo khi sử dụng 2 hoặc nhiều forder khác nhau)
# 4.thứ tự insert data
- Bảng users (Người dùng)
- Bảng admin (Quản trị viên)
- Bảng type_product (Loại sản phẩm)
- Bảng payment_method (Phương thức thanh toán)
- Bảng information_receive (Thông tin nhận hàng)
- Bảng supplier (Nhà cung cấp)
- Bảng voucher (Mã giảm giá)
- Bảng products (Sản phẩm)
- Bảng product_images (Hình ảnh sản phẩm)
- Bảng bill (Hóa đơn)
- Bảng bill_detail (Chi tiết hóa đơn)
- Bảng bill_products (Sản phẩm trong hóa đơn)
# 5.thư viện hiện thông báo
- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> chi tiết hỏi AI
# 6. cách lấy icon
- dán đường link này vào cần icon
- (<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>)
link lấy icon (https://ionic.io/ionicons)

# 7. cách sử dụng file Dao,DTO
- Dao nếu muôn lấy mảng đối tượng từ database ta chỉ cần tạo một đối tượng Dao sau đó truy cập vào các hàm của đối tượng này.
- DTO mỗi một phần tử của mảng Dao là một đối tượng của DTO
- vd khi muốn lấy người dùng:
+ '(<?php
    require_once __DIR__ . "/../../../dao/UserDao.php";
    
    $users = $table_users->view_all();//xem tất cả người dùng
    $table_users->insert(UserDTO);// chèn một người dùng vào hệ thống
    $table_users->update(UserDTO);// chỉnh sửa thông tin người dùng đó
    $table_users->delete($iduser); //xóa thông tin người dùng đó
    ?>)'
- lấy hóa đơn 
+ '(<?php
require_once __DIR__ . "/../../../dao/BillDao.php";
$bills = $table_bills->view_all();
?>)'

- lấy phương thức thanh toán 
+ '(<?php
require_once __DIR__ . "/../../../dao/PaymentMethodDao.php";
$paymentMethods = $table_paymentmethode->view_all();
?>)'

- lấy sản phẩm ' 
+ (<?php
require_once __DIR__ . "/../../../dao/ProductDao.php";
$products = $table_productss->view_all();?>)'

- lấy loại sản phẩm 
+ ' (<?php
require_once __DIR__ . "/../../../dao/TypeProductDao.php";
$typeProducts = $table_typeproduct->view_all();?>)'

- lấy voucher 
+ ' (<?php
require_once __DIR__ . "/../../../dao/VoucherDao.php";
$vouchers = $table_vouchers->view_all();?>)'

- lấy nhà cung cấp 
+ '(<?php 
require_once __DIR__ . "/../../../dao/SupplierDao.php";
$supplier = $table_supplier->view_all();?>)'

- thông tin nhận hàng 
+ '(<?php
    require_once __DIR__ . "/../../../dao/InformationReceiveDao.php";
    $InformationReceive = $table_informationreceive->view_all();
?>)'
- lấy bill-product 
+ '(<?php 
    require_once __DIR__ . "/../../../dao/BillProductDao.php";
    $billproducts = $table_billproducts->view_all();
?>)'
# 8.cập nhật file root php khi sử dụng khác thư mục
<?php
// Tự động xác định thư mục gốc 
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
?>
khi dùng mình chỉ cần 
ví dụ: ta đang ở trong admin/dashboard.php
mà muốn lấy file (dao/AdminDao.php)
include (ROOT_DIR."dao/AdminDao.php");
hoặc
require_once ROOT_DIR."dao/AdminDao.php";
