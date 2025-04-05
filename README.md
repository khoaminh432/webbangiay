
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