<?php
require_once __DIR__ . '/../DTO/dto.php';
require_once __DIR__ . '/../database/database_sever.php';

class ProductDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM products WHERE is_active = TRUE";
        $results = $this->db->view_table($sql);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductDTO($row);
        }
        return $products;
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
        $sql = "SELECT * FROM products WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new ProductDTO($result[0]) : null;
    }

    public function insert(ProductDTO $product) {
        $sql = "INSERT INTO products (name, quantity, description, price, weight, id_voucher, id_type_product, id_admin, id_supplier, is_active) 
                VALUES (:name, :quantity, :description, :price, :weight, :id_voucher, :id_type_product, :id_admin, :id_supplier, :is_active)";
        
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
            'is_active' => $product->is_active
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("ProductDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

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
                is_active = :is_active
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
            'is_active' => $product->is_active
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("ProductDao Update Error: " . $e->getMessage());
            return false;
        }
    }

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
}
?>