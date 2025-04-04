
-> cách thực thi hàm
database/db.php/database_sever
1.insert_table
# $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)"
# $param = ['username' => 'john_doe','email' => 'john@example.com','password' => password_hash('secure123', PASSWORD_DEFAULT)];
2.view_table
# string_query = "câu truy vấn"
# return về một mảng dữ liệu trong bảng đó
# dùng for each để duyệt
# $tables = $database->view_table("select * from product")
#    foreach($tables as $table){
#        echo $table["id"]."-".$table["name"]."-".$table["gia"]."-".$table["loai"]."\n";   }
3.sửa lỗi no such dir
# webbangiay/
# ├── FOLDER1/
# │   └── file1.php (chứa tất cả các class DTO)
# ├── FOLDER2/
# │   └── file1.php (chứa class database_sever)
# ├── dao/
# │   └── file1.php
# └── index.php
require_once __DIR__ . '/../folder1/file1.php'
(khai báo khi sử dụng 2 hoặc nhiều forder khác nhau)
