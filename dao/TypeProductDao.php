<?php
require_once __DIR__ . '/../DTO/dto.php';
require_once __DIR__ . '/../database/database_sever.php';

class TypeProductDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM type_product";
        $results = $this->db->view_table($sql);
        
        $types = [];
        foreach ($results as $row) {
            $types[] = new TypeProductDTO($row);
        }
        return $types;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM type_product WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new TypeProductDTO($result[0]) : null;
    }

    public function insert(TypeProductDTO $type) {
        $sql = "INSERT INTO type_product (name, id_admin) VALUES (:name, :id_admin)";
        $params = [
            'name' => $type->name,
            'id_admin' => $type->id_admin
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("TypeProductDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update(TypeProductDTO $type) {
        $sql = "UPDATE type_product SET name = :name, id_admin = :id_admin WHERE id = :id";
        $params = [
            'id' => $type->id,
            'name' => $type->name,
            'id_admin' => $type->id_admin
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("TypeProductDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM type_product WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("TypeProductDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
?>