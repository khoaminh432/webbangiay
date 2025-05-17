<?php
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class InformationReceiveDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    // Lấy tất cả địa chỉ của người dùng
    public function get_by_user($userId) {
        $sql = "SELECT * FROM information_receive WHERE id_user = :id_user";
        $params = ['id_user' => $userId];
        $results = $this->db->view_table($sql, $params);
        
        $addresses = [];
        foreach ($results as $row) {
            $addresses[] = new InformationReceiveDTO($row);
        }
        return $addresses;
    }

    // Lấy địa chỉ mặc định của người dùng
    public function get_default_address($userId) {
        $sql = "SELECT * FROM information_receive WHERE id_user = :id_user AND is_default = TRUE LIMIT 1";
        $params = ['id_user' => $userId];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new InformationReceiveDTO($result[0]) : null;
    }

    // Thêm địa chỉ mới
    public function insert(InformationReceiveDTO $info) {
        $sql = "INSERT INTO information_receive (address, name, phone, id_user, is_default) 
                VALUES (:address, :name, :phone, :id_user, :is_default)";
        
        $params = [
            'address' => $info->address,
            'name' => $info->name,
            'phone' => $info->phone,
            'id_user' => $info->id_user,
            'is_default' => $info->is_default
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("InformationReceiveDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật địa chỉ
    public function update(InformationReceiveDTO $info) {
        $sql = "UPDATE information_receive SET 
                address = :address,
                name = :name,
                phone = :phone,
                is_default = :is_default
                WHERE id = :id";
        
        $params = [
            'id' => $info->id,
            'address' => $info->address,
            'name' => $info->name,
            'phone' => $info->phone,
            'is_default' => $info->is_default
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("InformationReceiveDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa địa chỉ
    public function delete($id) {
        $sql = "DELETE FROM information_receive WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("InformationReceiveDao Delete Error: " . $e->getMessage());
            return false;
        }
    }

    // Đặt tất cả địa chỉ của người dùng thành không mặc định
    public function reset_default_addresses($userId) {
        $sql = "UPDATE information_receive SET is_default = FALSE WHERE id_user = :id_user";
        $params = ['id_user' => $userId];
        return $this->db->update_table($sql, $params);
    }

    // Đặt một địa chỉ cụ thể làm mặc định
    public function set_default_address($id, $userId) {
        $this->reset_default_addresses($userId);
        
        $sql = "UPDATE information_receive SET is_default = TRUE WHERE id = :id AND id_user = :id_user";
        $params = [
            'id' => $id,
            'id_user' => $userId
        ];
        return $this->db->update_table($sql, $params);
    }
    public function get_by_id($id, $userId = null) {
    $sql = "SELECT * FROM information_receive WHERE id = :id";
    $params = ['id' => $id];
    
    // Nếu có userId, kiểm tra địa chỉ thuộc về user đó
    if ($userId !== null) {
        $sql .= " AND id_user = :user_id";
        $params['user_id'] = $userId;
    }
    
    $result = $this->db->view_table($sql, $params);
    
    if (!empty($result)) {
        return new InformationReceiveDTO($result[0]); // Trả về đối tượng DTO
    }
    
    return null; // Trả về null nếu không tìm thấy
}
}
?><?php $table_information_receive = new InformationReceiveDao();?>