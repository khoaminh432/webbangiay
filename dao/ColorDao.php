<?php
require_once __DIR__ . '/../DTO/ColorDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class ColorDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM colors";
        $results = $this->db->view_table($sql);
        
        $colors = [];
        foreach ($results as $row) {
            $colors[] = new ColorDTO($row);
        }
        return $colors;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM colors WHERE id = :id";
        $params = ['id' => $id];
        $results = $this->db->view_table($sql, $params);

        return !empty($results) ? new ColorDTO($results[0]) : null;
    }

    public function insert(ColorDTO $color) {
        $sql = "INSERT INTO colors (name, hex_code, created_at, updated_at) 
                VALUES (:name, :hex_code, NOW(), NOW())";
        
        $params = [
            'name' => $color->name,
            'hex_code' => $color->hex_code,
        ];

        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("ColorDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update(ColorDTO $color) {
        $sql = "UPDATE colors SET 
                    name = :name, 
                    hex_code = :hex_code, 
                    updated_at = NOW()
                WHERE id = :id";
        
        $params = [
            'id' => $color->id,
            'name' => $color->name,
            'hex_code' => $color->hex_code,
        ];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ColorDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM colors WHERE id = :id";
        $params = ['id' => $id];

        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ColorDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
