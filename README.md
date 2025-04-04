
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