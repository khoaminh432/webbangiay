<?php
require_once __DIR__ . '/../DTO/dto.php';
require_once __DIR__ . '/../database/database_sever.php';

class InformationReceiveDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

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

    public function get_default_address($userId) {
        $sql = "SELECT * FROM information_receive WHERE id_user = :id_user AND is_default = TRUE LIMIT 1";
        $params = ['id_user' => $userId];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new InformationReceiveDTO($result[0]) : null;
    }

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
}
?>