<?php
require_once __DIR__ . '/../DTO/ProductImage.php';
require_once __DIR__ . '/../database/database_sever.php';

class ProductImageDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM product_images WHERE is_active = TRUE";
        $results = $this->db->view_table($sql);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductImageDTO($row);
        }
        return $products;
    }

    public function get_by_type($typeId) {
        $sql = "SELECT * FROM product_images WHERE id_type_product = :type_id AND is_active = TRUE";
        $params = ['type_id' => $typeId];
        $results = $this->db->view_table($sql, $params);
        
        $products = [];
        foreach ($results as $row) {
            $products[] = new ProductImageDTO($row);
        }
        return $products;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM product_images WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new ProductImageDTO($result[0]) : null;
    }

    public function insert(ProductImageDTO $productImage) {
        $sql = "INSERT INTO products (image_url,id_product,is_primary,created_at) 
                VALUES (:name, :quantity, :description, :price, :weight, :id_voucher, :id_type_product, :id_admin, :id_supplier, :is_active)";
        
        $params = [
            'image_url' => $productImage->image_url,
            'id_product' => $productImage->id_product,
            'is_primary' => $productImage->is_primary,
            'created_at' => $productImage->created_at,
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("ProductDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update(ProductImageDTO $product) {
        $sql = "UPDATE product_images SET 
                image_url = :image_url,
                id_product = :id_product,
                is_primary = :is_primary,
                
                WHERE id = :id";
        
        $params = [
            'id' => $product->id,
            'image_url' => $product->image_url,
            'id_product' => $product->id_product,
            'is_primary' => $product->is_primary,
            'created_at' => $product->created_at,
            
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
        $sql = "UPDATE product_images SET is_primary = FALSE WHERE id = :id";
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
<?php $table_productImage = new ProductImagedao();?>