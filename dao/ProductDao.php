<?php
require_once __DIR__ . '/../DTO/ProductDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class ProductDao {
    private $db;
    private $conn; // đừng xóa
    public function __construct() {
        $this->db = new database_sever();
        $this->conn = $this->db->conn; // đừng xóa
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
    public function get_products_with_details() {
        $sql = "SELECT p.*, tp.name as type_name, s.name as supplier_name,
                pi.image_url
                FROM products p
                LEFT JOIN type_product tp ON p.id_type_product = tp.id
                LEFT JOIN supplier s ON p.id_supplier = s.id
                LEFT JOIN product_images pi ON p.id = pi.id_product AND pi.is_primary = 1
                WHERE p.is_active = 1
                GROUP BY p.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}