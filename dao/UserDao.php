<?php
require_once __DIR__ . '/../DTO/UserDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class UserDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    // Lấy tất cả người dùng
    public function view_all() {
        $sql = "SELECT * FROM users";
        $results = $this->db->view_table($sql);
        
        $users = [];
        foreach ($results as $row) {
            $users[] = new UserDTO($row);
        }
        return $users;
    }
    public function getID() {
        $sql = "SELECT id FROM users ORDER BY id ASC";
        $result = $this->db->view_table($sql);
    
        $expectedID = 1;
        foreach ($result as $row) {
            if ((int)$row['id'] != $expectedID) {
                return $expectedID;
            }
            $expectedID++;
        }
    
        // Nếu không thiếu ID nào, trả về ID tiếp theo
        return $expectedID;
    }
    // Lấy người dùng theo ID
    public function get_by_id($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        if (!empty($result)) {
            return new UserDTO($result[0]);
        }
        return null;
    }

    // Thêm người dùng mới
    public function insert(UserDTO $user) {
        $sql = "INSERT INTO users (id,email, password, status, username) 
                VALUES (:id,:email, :password, :status, :username)";
        
        $params = [
            "id" => $this->getID(),
            'email' => $user->email,
            'password' => $user->password,
            'status' => $user->status,
            'username' => $user->username
        ];
        
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("UserDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật người dùng
    public function update(UserDTO $user) {
        $sql = "UPDATE users SET 
                email = :email,
                status = :status,
                username = :username
                WHERE id = :id";
        
        $params = [
            'id' => $user->id,
            'email' => $user->email,
            'status' => $user->status,
            'username' => $user->username
        ];
        
        // Nếu có password mới thì cập nhật
        if (!empty($user->password)) {
            $sql = str_replace("SET", "SET password = :password,", $sql);
            $params['password'] =$user->password;
        }
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("UserDao Update Error: " . $e->getMessage());
            return false;
        }
    }
    public function update_status(int $iduser,$status) {
        $sql = "UPDATE users SET 
                status = :status
                WHERE id = :id";
        
        $params = [
            'id' => $iduser,
            'status' => $status,
        ];
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("UserDao Update Error: " . $e->getMessage());
            return false;
        }
    }
    // Xóa người dùng
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("UserDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
    public function username_exists($username, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = ['username' => $username];
        
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $exclude_id;
        }
        
        $result = $this->db->view_table($sql, $params);
        return $result[0]['COUNT(*)'] > 0;
    }
    // Kiểm tra email đã tồn tại chưa
    public function email_exists($email, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $params = ['email' => $email];
        
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $exclude_id;
        }
        
        $result = $this->db->view_table($sql, $params);
        return $result[0]['COUNT(*)'] > 0;
    }
    //huy
    public function get_by_email($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $params = ['email' => $email];
        $result = $this->db->view_table($sql, $params);
        
        if (!empty($result)) {
            return new UserDTO($result[0]);
        }
        return null;
    }
    public function check_bill($id_user){
        require_once __DIR__."/BillDao.php";
        $temp = $table_bills->get_by_user($id_user);
        return empty($temp);
    }
    
      
}
?>
<?php $table_users = new UserDao();?>