<?php
require_once __DIR__ . '/../DTO/RolePermissionDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class RolePermissionDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }
    // Xóa tất cả phân quyền theo position_id (vai trò)
    public function delete_by_position($position_id) {
        $sql = "DELETE FROM role_permissions WHERE position_id = :position_id";
        $params = ['position_id' => $position_id];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("RolePermissionDao DeleteByPosition Error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy tất cả phân quyền
    public function view_all() {
        $sql = "SELECT * FROM role_permissions";
        $results = $this->db->view_table($sql);

        $items = [];
        foreach ($results as $row) {
            $items[] = new RolePermissionDTO($row);
        }
        return $items;
    }
    public function exists($position_id, $permission_id) {
        $sql = "SELECT COUNT(*) AS cnt FROM role_permissions WHERE position_id = :position_id AND permission_id = :permission_id";
        $params = [
            'position_id'   => $position_id,
            'permission_id' => $permission_id
        ];
        $result = $this->db->view_table($sql, $params);
        if (isset($result[0]['cnt'])) {
            return $result[0]['cnt'] > 0;
        }
        return false;
    }
    // Lấy phân quyền theo vai trò
    public function get_by_role($position_id) {
        $sql = "SELECT * FROM role_permissions WHERE position_id = :position_id";
        $params = ['position_id' => $position_id];
        $results = $this->db->view_table($sql, $params);

        $items = [];
        foreach ($results as $row) {
            $items[] = new RolePermissionDTO($row);
        }
        return $items;
    }
    public function checkrole($position_id, $permission_input) {
    // 1. Nếu $permission_input là mảng, lấy phần tử đầu tiên
    if (is_array($permission_input)) {
        if (empty($permission_input)) {
            return false;
        }
        // Bạn có thể thay đổi logic này nếu cần support nhiều ID
        $permission_input = reset($permission_input);
    }

    // 2. Loại bỏ khoảng trắng và kiểm tra numeric
    $permission_input = trim((string)$permission_input);
    if (!is_numeric($permission_input)) {
        // không phải số -> chắc chắn không trùng
        return false;
    }

    // Ép kiểu thành số (int hoặc float tuỳ ý)
    $permission_id = (int)$permission_input;

    // 3. Lấy danh sách permission hiện có của role
    $rolePermissions = $this->get_by_role($position_id);
    if (empty($rolePermissions)) {
        return false;
    }

    // 4. So sánh
    foreach ($rolePermissions as $rp) {
        if ($rp->permission_id === $permission_id) {
            return true;
        }
    }

    return false;
}

    // Lấy phân quyền theo permission
    public function get_by_permission($permission_id) {
        $sql = "SELECT * FROM role_permissions WHERE permission_id = :permission_id";
        $params = ['permission_id' => $permission_id];
        $results = $this->db->view_table($sql, $params);

        $items = [];
        foreach ($results as $row) {
            $items[] = new RolePermissionDTO($row);
        }
        return $items;
    }

    // Thêm phân quyền mới
    public function insert(RolePermissionDTO $item) {
        $sql = "INSERT INTO role_permissions (position_id, permission_id) VALUES (:position_id, :permission_id)";
        $params = [
            'position_id'   => $item->position_id,
            'permission_id' => $item->permission_id
        ];
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("RolePermissionDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa phân quyền (xoá bản ghi)
    public function delete($position_id, $permission_id) {
        $sql = "DELETE FROM role_permissions WHERE position_id = :position_id AND permission_id = :permission_id";
        $params = [
            'position_id'   => $position_id,
            'permission_id' => $permission_id
        ];
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("RolePermissionDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
