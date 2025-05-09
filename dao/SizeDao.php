<?php
require_once __DIR__ . '/../DTO/SizeDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class SizeDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }

    // Lấy tất cả kích cỡ
    public function view_all() {
        $sql = "SELECT * FROM sizes";
        $results = $this->db->view_table($sql);
        
        $sizes = [];
        foreach ($results as $row) {
            $sizes[] = new SizeDTO($row);
        }
        return $sizes;
    }

    // Lấy kích cỡ theo ID
    public function get_by_id($id) {
        $sql = "SELECT * FROM sizes WHERE id = :id";
        $params = ['id' => $id];
        $results = $this->db->view_table($sql, $params);

        return !empty($results) ? new SizeDTO($results[0]) : null;
    }

    // Thêm kích cỡ mới
    public function insert(SizeDTO $size) {
        $sql = "INSERT INTO sizes (size_number, description, created_at, updated_at)
                VALUES (:size_number, :description, NOW(), NOW())";

        $params = [
            'size_number' => $size->size_number,
            'description' => $size->description,
        ];

        try {
            return $this->db->insert_table($sql, $params);
        } catch (PDOException $e) {
            error_log("SizeDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật kích cỡ
    public function update(SizeDTO $size) {
        $sql = "UPDATE sizes SET 
                    size_number = :size_number,
                    description = :description,
                    updated_at = NOW()
                WHERE id = :id";

        $params = [
            'id' => $size->id,
            'size_number' => $size->size_number,
            'description' => $size->description
        ];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("SizeDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa kích cỡ
    public function delete($id) {
        $sql = "DELETE FROM sizes WHERE id = :id";
        $params = ['id' => $id];

        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("SizeDao Delete Error: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra tồn tại kích cỡ
    public function exists(SizeDTO $size) {
        $sql = "SELECT COUNT(*) as total FROM sizes WHERE size_number = :size_number AND description = :description";

        $params = [
            'size_number' => $size->size_number,
            'description' => $size->description
        ];

        try {
            $result = $this->db->view_table($sql, $params);
            return isset($result[0]['total']) && $result[0]['total'] > 0;
        } catch (PDOException $e) {
            error_log("SizeDao Exists Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
<?php $table_sizes = new SizeDao(); ?>
