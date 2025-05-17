<?php
require_once __DIR__ . '/../DTO/RoleDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class RoleDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }

    public function get_all_roles() {
        $sql = "SELECT * FROM roles";
        $results = $this->db->view_table($sql);

        $roles = [];
        foreach ($results as $row) {
            $roles[] = new RoleDTO($row);
        }
        return $roles;
    }

    public function get_role_by_id($position_id) {
        $sql = "SELECT * FROM roles WHERE position_id = :position_id";
        $params = ['position_id' => $position_id];
        $results = $this->db->view_table($sql, $params);

        return !empty($results) ? new RoleDTO($results[0]) : null;
    }

    public function insert_role(RoleDTO $role) {
        $sql = "INSERT INTO roles (position_id, role_name) VALUES (:position_id, :role_name)";
        $params = [
            'position_id' => $role->position_id,
            'role_name' => $role->role_name
        ];

        try {
            return $this->db->insert_table($sql, $params);
        } catch (PDOException $e) {
            error_log("Insert Role Error: " . $e->getMessage());
            return false;
        }
    }

    public function update_role(RoleDTO $role) {
        $sql = "UPDATE roles SET role_name = :role_name WHERE position_id = :position_id";
        $params = [
            'position_id' => $role->position_id,
            'role_name' => $role->role_name
        ];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("Update Role Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete_role($position_id) {
        $sql = "DELETE FROM roles WHERE position_id = :position_id";
        $params = ['position_id' => $position_id];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("Delete Role Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
