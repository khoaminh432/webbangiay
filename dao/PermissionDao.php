<?php
require_once __DIR__ . '/../DTO/PermissionDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class PermissionDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }

    public function get_all_permissions() {
        $sql = "SELECT * FROM permissions";
        $results = $this->db->view_table($sql);

        $permissions = [];
        foreach ($results as $row) {
            $permissions[] = new PermissionDTO($row);
        }
        return $permissions;
    }

    public function get_permission_by_id($permission_id) {
        $sql = "SELECT * FROM permissions WHERE permission_id = :permission_id";
        $params = ['permission_id' => $permission_id];
        $results = $this->db->view_table($sql, $params);

        return !empty($results) ? new PermissionDTO($results[0]) : null;
    }

    public function insert_permission(PermissionDTO $permission) {
        $sql = "INSERT INTO permissions (permission_id, permission_name) VALUES (:permission_id, :permission_name)";
        $params = [
            'permission_id' => $permission->permission_id,
            'permission_name' => $permission->permission_name
        ];

        try {
            return $this->db->insert_table($sql, $params);
        } catch (PDOException $e) {
            error_log("Insert Permission Error: " . $e->getMessage());
            return false;
        }
    }

    public function update_permission(PermissionDTO $permission) {
        $sql = "UPDATE permissions SET permission_name = :permission_name WHERE permission_id = :permission_id";
        $params = [
            'permission_id' => $permission->permission_id,
            'permission_name' => $permission->permission_name
        ];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("Update Permission Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete_permission($permission_id) {
        $sql = "DELETE FROM permissions WHERE permission_id = :permission_id";
        $params = ['permission_id' => $permission_id];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("Delete Permission Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
