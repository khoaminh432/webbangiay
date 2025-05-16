<?php
require_once __DIR__ . '/../DTO/AdminDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class AdminDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    // Lấy tất cả admin
    public function view_all() {
        $sql = "SELECT * FROM admin";
        $results = $this->db->view_table($sql);
        
        $admins = [];
        foreach ($results as $row) {
            $admins[] = new AdminDTO($row);
        }
        return $admins;
    }

    // Lấy admin theo ID
    public function get_by_id($id) {
        $sql = "SELECT * FROM admin WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        if (!empty($result)) {
            return new AdminDTO($result[0]);
        }
        return null;
    }

    // Thêm admin mới
    public function insert(AdminDTO $admin) {
        $sql = "INSERT INTO admin (name, email, password, position) 
                VALUES (:name, :email, :password, :position)";
        
        $params = [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => $admin->password,
            'position' => $admin->position
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("AdminDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật admin
    public function update(AdminDTO $admin) {
        $sql = "UPDATE admin SET 
                name = :name,
                email = :email,
                position = :position
                WHERE id = :id";
        
        $params = [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'position' => $admin->position
        ];
        
        // Nếu có password mới thì cập nhật
        if (!empty($admin->password)) {
            $sql = str_replace("SET", "SET password = :password,", $sql);
            $params['password'] = $admin->password;
        }
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("AdminDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa admin
    public function delete($id) {
        $sql = "DELETE FROM admin WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("AdminDao Delete Error: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra email đã tồn tại chưa
    public function email_exists($email, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM admin WHERE email = :email";
        $params = ['email' => $email];
        
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $exclude_id;
        }
        
        $result = $this->db->view_table($sql, $params);
        return $result[0]['COUNT(*)'] > 0;
    }

    // Đăng nhập admin
    public function login($email, $password) {
        $sql = "SELECT * FROM admin WHERE email = :email LIMIT 1";
        $params = ['email' => $email,];
        
        $result = $this->db->view_table($sql, $params);
        if (empty($result)) {
            return false;
        }
        
        $admin = new AdminDTO($result[0]);
        if ($password==$admin->password) {
            return $admin;
        }
        
        return false;
    }
}
?>
<?php $table_admins = new AdminDao();
?>