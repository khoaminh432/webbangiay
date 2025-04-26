<?php
require_once __DIR__ . '/../DTO/ProductDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class ProductDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    // Lấy tất cả sản phẩm (có thể thêm điều kiện is_active)
    public function view_all($includeInactive = false) {
        $sql = "SELECT * FROM products";
        if (!$includeInactive) {
            $sql .= " WHERE is_active = TRUE";
        }
        $results = $this->db->view_table($sql);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }

    // Lấy sản phẩm theo nhà cung cấp
    public function get_by_supplier($supplierId, $includeInactive = false) {
        $sql = "SELECT * FROM products WHERE id_supplier = :supplier_id";
        if (!$includeInactive) {
            $sql .= " AND is_active = TRUE";
        }
        
        $params = ['supplier_id' => $supplierId];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }

    // Lấy sản phẩm theo loại
    public function get_by_type($typeId, $includeInactive = false) {
        $sql = "SELECT * FROM products WHERE id_type_product = :type_id";
        if (!$includeInactive) {
            $sql .= " AND is_active = TRUE";
        }
        
        $params = ['type_id' => $typeId];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }

    // Lấy sản phẩm theo ID
    public function get_by_id($id, $includeInactive = false) {
        $sql = "SELECT * FROM products WHERE id = :id";
        if (!$includeInactive) {
            $sql .= " AND is_active = TRUE";
        }
        
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new ProductDTO($result[0]) : null;
    }

    // Tìm kiếm sản phẩm
    public function search($keyword, $includeInactive = false) {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword OR description LIKE :keyword";
        if (!$includeInactive) {
            $sql .= " AND is_active = TRUE";
        }
        
        $params = ['keyword' => '%' . $keyword . '%'];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }

    // Thêm sản phẩm mới
    public function insert(ProductDTO $product) {
        $sql = "INSERT INTO products (name, quantity, description, price, weight, id_voucher, id_type_product, id_admin, id_supplier, is_active, image_url) 
                VALUES (:name, :quantity, :description, :price, :weight, :id_voucher, :id_type_product, :id_admin, :id_supplier, :is_active, :image_url)";
        
        $params = [
            'name' => $product->name,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'price' => $product->price,
            'weight' => $product->weight,
            'id_voucher' => $product->id_voucher,
            'id_type_product' => $product->id_type_product,
            'id_admin' => $product->id_admin,
            'id_supplier' => $product->id_supplier,
            'is_active' => $product->is_active,
            'image_url' => $product->image_url
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("ProductDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật thông tin sản phẩm
    public function update(ProductDTO $product) {
        $sql = "UPDATE products SET 
                name = :name,
                quantity = :quantity,
                description = :description,
                price = :price,
                weight = :weight,
                id_voucher = :id_voucher,
                id_type_product = :id_type_product,
                id_admin = :id_admin,
                id_supplier = :id_supplier,
                is_active = :is_active,
                image_url = :image_url
                WHERE id = :id";
        
        $params = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'price' => $product->price,
            'weight' => $product->weight,
            'id_voucher' => $product->id_voucher,
            'id_type_product' => $product->id_type_product,
            'id_admin' => $product->id_admin,
            'id_supplier' => $product->id_supplier,
            'is_active' => $product->is_active,
            'image_url' => $product->image_url
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ProductDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật số lượng sản phẩm
    public function update_quantity($productId, $quantity) {
        $sql = "UPDATE products SET quantity = :quantity WHERE id = :id";
        $params = [
            'id' => $productId,
            'quantity' => $quantity
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ProductDao Update Quantity Error: " . $e->getMessage());
            return false;
        }
    }
    // Cập nhật số lượng sản phẩm
    public function update_active($productId, $is_active) {
        $sql = "UPDATE products SET is_active = :is_active WHERE id = :id";
        $params = [
            'id' => $productId,
            'is_active' => $is_active
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ProductDao Update Quantity Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa mềm sản phẩm (chuyển is_active = FALSE)
    public function delete($id) {
        $sql = "UPDATE products SET is_active = FALSE WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ProductDao Delete Error: " . $e->getMessage());
            return false;
        }
    }

    // Khôi phục sản phẩm đã xóa (chuyển is_active = TRUE)
    public function restore($id) {
        $sql = "UPDATE products SET is_active = TRUE WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ProductDao Restore Error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy sản phẩm nổi bật (ví dụ: sắp xếp theo số lượng bán)
    public function get_featured_products($limit = 5) {
        $sql = "SELECT p.* FROM products p 
                LEFT JOIN order_details od ON p.id = od.id_product 
                WHERE p.is_active = TRUE
                GROUP BY p.id 
                ORDER BY SUM(od.quantity) DESC 
                LIMIT :limit";
        
        $params = ['limit' => $limit];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }

    // Lấy sản phẩm mới nhất
    public function get_newest_products($limit = 5) {
        $sql = "SELECT * FROM products 
                WHERE is_active = TRUE 
                ORDER BY id DESC 
                LIMIT :limit";
        
        $params = ['limit' => $limit];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }
}
?>
<?php $table_products=new ProductDao();?>