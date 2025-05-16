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
    public function getID() {
        $sql = "SELECT id FROM products ORDER BY id ASC";
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
    public function get_by_type($typeId) {
        $sql = "SELECT * FROM products WHERE id_type_product = :type_id AND is_active = TRUE";
        $params = ['type_id' => $typeId];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }
    public function get_by_id($id) {
        $sql = "SELECT A.*, B.image_url 
                FROM products AS A
                LEFT JOIN product_images AS B ON A.id = B.id_product AND B.is_primary = 1
                WHERE A.id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new ProductDTO($result[0]) : null;
    }

    public function get_by_ids($ids) {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT products.id, products.name, price, quantity, image_url  FROM products 
                LEFT JOIN product_images ON products.id = product_images.id_product AND product_images.is_primary = 1 
                WHERE products.id IN ($placeholders)";
        $result = $this->db->view_table($sql, $ids);

        $products = [];
        foreach ($result as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
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
        $sql = "INSERT INTO products (id,name, quantity, description, price, weight, id_voucher, id_type_product, id_admin, id_supplier, is_active, image_url) 
                VALUES (:id,:name, :quantity, :description, :price, :weight, :id_voucher, :id_type_product, :id_admin, :id_supplier, :is_active, :image_url)";
        
        $params = [
            "id" =>$this->getID(),
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
            "image_url" =>$product->image_url
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
            "image_url" => $product->image_url
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
        $sql = "DELETE FROM products WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
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
    public function check_billproduct($id_product){
        require_once __DIR__."/BillProductDao.php";
        $temp = $table_billproducts->get_by_product($id_product);
        return empty($temp);
    }
    public function get_by_voucher($id_voucher){
        $sql = "SELECT * FROM products WHERE id_voucher = :id_voucher";
        $params = ['id_voucher' => $id_voucher];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
    }
    public function exists_by_dto(ProductDTO $product) {
        $sql = "SELECT COUNT(*) as total 
                FROM products 
                WHERE name = :name 
                  AND price = :price 
                  AND quantity = :quantity 
                  AND id_type_product = :id_type_product 
                  AND id_supplier = :id_supplier";
        
        $params = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'id_type_product' => $product->id_type_product,
            'id_supplier' => $product->id_supplier,
        ];
        
        try {
            $result = $this->db->view_table($sql, $params);
            return isset($result[0]['total']) && $result[0]['total'] > 0;
        } catch (PDOException $e) {
            error_log("ProductDao exists_by_dto Error: " . $e->getMessage());
            return false;
        }
    }
    
}   
?>
<?php $table_products=new ProductDao();?>