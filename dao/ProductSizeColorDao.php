<?php
require_once __DIR__ . '/../DTO/ProductSizeColor.php';
require_once __DIR__ . '/../database/database_sever.php';

class ProductSizeColorDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }
    public function getID() {
        $sql = "SELECT id FROM product_size_color ORDER BY id ASC";
        $result = $this->db->view_table($sql);
    
        $expectedID = 1;
        foreach ($result as $row) {
            if ((int)$row['id'] != $expectedID) {
                return $expectedID;
            }
            $expectedID++;
        }
        return $expectedID;
    }
    // Lấy tất cả bản ghi
    public function view_all() {
        $sql = "SELECT * FROM product_size_color";
        $results = $this->db->view_table($sql);

        $list = [];
        foreach ($results as $row) {
            $list[] = new ProductSizeColorDTO($row);
        }
        return $list;
    }
    public function getname($id_product,$id_color,$id_size){
        
    }
    // Xóa tất cả các bản ghi theo id_product
    public function delete_by_product($id_product) {
        $sql = "DELETE FROM product_size_color WHERE id_product = :id_product";
        $params = ['id_product' => $id_product];

        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PSC Delete by Product Error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy theo ID
    public function get_by_id($id) {
        $sql = "SELECT * FROM product_size_color WHERE id = :id";
        $params = ['id' => $id];
        $results = $this->db->view_table($sql, $params);

        return !empty($results) ? new ProductSizeColorDTO($results[0]) : null;
    }

    // Thêm mới
    public function insert(ProductSizeColorDTO $psc) {
        $sql = "INSERT INTO product_size_color (id,id_product, id_size, id_color, quantity, created_at, updated_at)
                VALUES (:id,:id_product, :id_size, :id_color, :quantity, NOW(), NOW())";

        $params = [
            "id" => $this->getID(),
            'id_product' => $psc->id_product,
            'id_size' => $psc->id_size,
            'id_color' => $psc->id_color,
            'quantity' => $psc->quantity
        ];

        try {
            return $this->db->insert_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PSC Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật số lượng
    public function update(ProductSizeColorDTO $psc) {
        $sql = "UPDATE product_size_color SET 
                    id_product = :id_product,
                    id_size = :id_size,
                    id_color = :id_color,
                    quantity = :quantity,
                    updated_at = NOW()
                WHERE id = :id";

        $params = [
            'id' => $psc->id,
            'id_product' => $psc->id_product,
            'id_size' => $psc->id_size,
            'id_color' => $psc->id_color,
            'quantity' => $psc->quantity
        ];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PSC Update Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa bản ghi
    public function delete($id) {
        $sql = "DELETE FROM product_size_color WHERE id = :id";
        $params = ['id' => $id];

        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PSC Delete Error: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra bản ghi có tồn tại theo product, size, color
    public function exists($id_product, $id_size, $id_color) {
        $sql = "SELECT COUNT(*) as total FROM product_size_color 
                WHERE id_product = :id_product AND id_size = :id_size AND id_color = :id_color";

        $params = [
            'id_product' => $id_product,
            'id_size' => $id_size,
            'id_color' => $id_color
        ];

        try {
            $result = $this->db->view_table($sql, $params);
            return isset($result[0]['total']) && $result[0]['total'] > 0;
        } catch (PDOException $e) {
            error_log("PSC Exists Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật số lượng theo id_product, id_size, id_color (nếu tồn tại)
    public function update_quantity($id_product, $id_size, $id_color, $quantity) {
        $sql = "UPDATE product_size_color SET 
                    quantity = :quantity,
                    updated_at = NOW()
                WHERE id_product = :id_product AND id_size = :id_size AND id_color = :id_color";

        $params = [
            'id_product' => $id_product,
            'id_size' => $id_size,
            'id_color' => $id_color,
            'quantity' => $quantity
        ];

        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PSC Update Quantity Error: " . $e->getMessage());
            return false;
        }
    }
    // Lấy số lượng theo id_product, id_size, id_color
    public function get_quantity($id_product, $id_size, $id_color) {
        $sql = "SELECT quantity FROM product_size_color 
                WHERE id_product = :id_product AND id_size = :id_size AND id_color = :id_color";

        $params = [
            'id_product' => $id_product,
            'id_size' => $id_size,
            'id_color' => $id_color
        ];

        try {
            $result = $this->db->view_table($sql, $params);
            return isset($result[0]['quantity']) ? (int)$result[0]['quantity'] : 0;
        } catch (PDOException $e) {
            error_log("PSC Get Quantity Error: " . $e->getMessage());
            return 0;
        }
    }

}
?>
<?php $psc_table = new ProductSizeColorDao(); ?>
