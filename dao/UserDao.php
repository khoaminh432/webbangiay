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
        $sql = "INSERT INTO users (email, password, status, username) 
                VALUES (:email, :password, :status, :username)";
        
        $params = [
            'email' => $user->email,
            'password' => password_hash($user->password, PASSWORD_DEFAULT),
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
            $params['password'] = password_hash($user->password, PASSWORD_DEFAULT);
        }
        
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
    
}
?>