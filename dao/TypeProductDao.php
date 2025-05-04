<?php
require_once __DIR__ . '/../DTO/TypeProduct.php';
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

    /**
     * Đếm số lượng sản phẩm thuộc loại sản phẩm
     */
    public function count_product($id) {
        $sql = "SELECT COUNT(*) as product_count FROM products WHERE id_type_product = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? $result[0]['product_count'] : 0;
    }

    /**
     * Tính tổng doanh thu của loại sản phẩm
     */
    public function calculate_revenue($id) {
        $sql = "SELECT SUM(bp.quantity * bp.unit_price) as total_revenue 
                FROM bill_products bp
                JOIN products p ON bp.id_product = p.id
                WHERE p.id_type_product = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? $result[0]['total_revenue'] : 0;
    }
    public function getID() {
        $sql = "SELECT id FROM type_product ORDER BY id ASC";
        $result = $this->db->view_table($sql);
    
        $expectedID = 1;
        foreach ($result as $row) {
            if ((int)$row['id'] != $expectedID) {
                return $expectedID;
            }
            $expectedID++;
        }
    
        // Nếu không thiếu ID nào, trả về ID tiếp theo
        return $expectedID;
    }
    /**
     * Lấy thống kê chi tiết: số lượng sản phẩm và doanh thu
     */
    public function get_stats($id) {
        $sql = "SELECT 
                    COUNT(p.id) as product_count,
                    SUM(od.quantity * od.price) as total_revenue
                FROM products p
                LEFT JOIN order_details od ON p.id = od.product_id
                WHERE p.type_id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? $result[0] : ['product_count' => 0, 'total_revenue' => 0];
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM type_product WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new TypeProductDTO($result[0]) : null;
    }

    public function insert(TypeProductDTO $type) {
        $sql = "INSERT INTO type_product (id,name, id_admin) VALUES (:id,:name, :id_admin)";
        $params = [
            "id" =>$this->getID(),
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
    public function check_product($id_typeproduct){
        require_once __DIR__."/ProductDao.php";
        $temp = $table_products->get_by_type($id_typeproduct);
        return empty($temp);
    }
}
?>
<?php $table_typeproduct = new TypeProductDao();?>