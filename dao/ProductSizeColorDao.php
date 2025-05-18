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
        
    }public function set_quantity($productId, $sizeId, $colorId, $newQuantity, $newReserved = null) {
    $sql = "UPDATE product_size_color 
            SET quantity = :new_quantity,
                updated_at = NOW()";

    $params = [
        'new_quantity' => $newQuantity,
        'product_id' => $productId,
        'size_id' => $sizeId,
        'color_id' => $colorId
    ];

    // Nếu truyền reserved mới, thì cập nhật luôn
    if ($newReserved !== null) {
        $sql .= ", reserved = :new_reserved";
        $params['new_reserved'] = $newReserved;
    }

    $sql .= " WHERE id_product = :product_id
              AND id_size = :size_id
              AND id_color = :color_id";

    try {
        $affected = $this->db->update_table($sql, $params);
        return $affected > 0;
    } catch (PDOException $e) {
        error_log("Set Quantity Error: " . $e->getMessage());
        return false;
    }
}
public function get_by_product_size_color($productId, $sizeId, $colorId) {
    $sql = "SELECT * FROM product_size_color 
            WHERE id_product = :product_id AND id_size = :size_id AND id_color = :color_id";

    $results = $this->db->view_table($sql, [
        'product_id' => $productId,
        'size_id' => $sizeId,
        'color_id' => $colorId
    ]);
    return !empty($results) ? new ProductSizeColorDTO($results[0]) : null;
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


// Add validation to insert method
public function insert(ProductSizeColorDTO $psc) {
    // Validate required fields
    if (empty($psc->id_product) || empty($psc->id_size) || 
        empty($psc->id_color) || !is_numeric($psc->quantity)) {
        error_log("Missing required fields in ProductSizeColor insert");
        return false;
    }

    $sql = "INSERT INTO product_size_color 
            (id, id_product, id_size, id_color, quantity, created_at, updated_at)
            VALUES (:id, :id_product, :id_size, :id_color, :quantity, NOW(), NOW())";

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
public function get_version($productId, $sizeId, $colorId) {
    $sql = "SELECT version FROM product_size_color 
            WHERE id_product = :product_id
            AND id_size = :size_id
            AND id_color = :color_id";
    
    $params = [
        'product_id' => $productId,
        'size_id' => $sizeId,
        'color_id' => $colorId
    ];
    
    try {
        $result = $this->db->view_table($sql, $params);
        return isset($result[0]['version']) ? (int)$result[0]['version'] : 1;
    } catch (PDOException $e) {
        error_log("Get Version Error: " . $e->getMessage());
        return 1; // Trả về version mặc định nếu có lỗi
    }
}
    // Cập nhật số lượng theo id_product, id_size, id_color (nếu tồn tại)
   public function update_quantity($productId, $sizeId, $colorId, $quantityChange) {
    $sql = "UPDATE product_size_color 
            SET quantity = quantity + :quantity_change,
                version = version + 1
            WHERE id_product = :product_id
            AND id_size = :size_id
            AND id_color = :color_id
            AND version = :current_version";
    
    // Lấy version hiện tại trước
    $current_version = $this->get_version($productId, $sizeId, $colorId);
    
    
    $params = [
        'product_id' => $productId,
        'size_id' => $sizeId,
        'color_id' => $colorId,
        'quantity_change' => $quantityChange,
        'current_version' => $current_version
    ];
    
    try {
        $affected = $this->db->update_table($sql, $params);
        if ($affected === 0) {
            throw new Exception("Phiên bản dữ liệu đã thay đổi");
        }
        return true;
    } catch (PDOException $e) {
        error_log("Update Quantity Error: " . $e->getMessage());
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

    public function getVariantWithDetails($id) {
        $sql = "SELECT psc.*, c.name as color_name, c.hex_code, s.size_number
                FROM product_size_color psc
                LEFT JOIN colors c ON psc.id_color = c.id
                LEFT JOIN sizes s ON psc.id_size = s.id
                WHERE psc.id = :id";
        
        $params = ['id' => $id];
        $results = $this->db->view_table($sql, $params);
        
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Lấy tất cả biến thể của sản phẩm với thông tin màu và size
     */
    public function getProductVariantsWithDetails($productId) {
        $sql = "SELECT psc.*, c.name as color_name, c.hex_code, s.size_number
                FROM product_size_color psc
                LEFT JOIN colors c ON psc.id_color = c.id
                LEFT JOIN sizes s ON psc.id_size = s.id
                WHERE psc.id_product = :product_id";
        
        $params = ['product_id' => $productId];
        return $this->db->view_table($sql, $params);
    }

    /**
     * Lấy thông tin tồn kho theo màu sắc
     */
    public function getInventoryByColor($productId, $colorId) {
        $sql = "SELECT psc.id_size, psc.quantity, s.size_number
                FROM product_size_color psc
                JOIN sizes s ON psc.id_size = s.id
                WHERE psc.id_product = :product_id 
                AND psc.id_color = :color_id";
        
        $params = [
            'product_id' => $productId,
            'color_id' => $colorId
        ];
        return $this->db->view_table($sql, $params);
    }

    /**
     * Lấy thông tin tồn kho theo kích cỡ
     */
  public function reserveStock($productId, $sizeId, $colorId, $quantity) {
    // Lấy số lượng còn lại (quantity - reserved)
    $available = $this->get_available_quantity($productId, $sizeId, $colorId);
    if ($available < $quantity) {
        return false; // Không đủ hàng
    }

    // Cập nhật tồn kho: giảm quantity, tăng reserved
    $sql = "UPDATE product_size_color 
            SET quantity = quantity - :quantity,
                reserved = reserved + :quantity,
                updated_at = NOW()
            WHERE id_product = :product_id
            AND id_size = :size_id
            AND id_color = :color_id
            AND (quantity - reserved) >= :quantity";
    $params = [
        'product_id' => $productId,
        'size_id' => $sizeId,
        'color_id' => $colorId,
        'quantity' => $quantity
    ];
    try {
        $affected = $this->db->update_table($sql, $params);
        return $affected > 0;
    } catch (PDOException $e) {
        error_log("Reserve Stock Error: " . $e->getMessage());
        return false;
    }
}
    public function getInventoryBySize($productId, $sizeId) {
        $sql = "SELECT psc.id_color, psc.quantity, c.name as color_name, c.hex_code
                FROM product_size_color psc
                JOIN colors c ON psc.id_color = c.id
                WHERE psc.id_product = :product_id 
                AND psc.id_size = :size_id";
        
        $params = [
            'product_id' => $productId,
            'size_id' => $sizeId
        ];
        return $this->db->view_table($sql, $params);
    }

    /**
     * Kiểm tra tồn tại và trả về ID nếu có
     */
    public function findVariantId($productId, $colorId, $sizeId) {
        $sql = "SELECT id FROM product_size_color
                WHERE id_product = :product_id
                AND id_color = :color_id
                AND id_size = :size_id";
        
        $params = [
            'product_id' => $productId,
            'color_id' => $colorId,
            'size_id' => $sizeId
        ];
        
        $result = $this->db->view_table($sql, $params);
        return !empty($result) ? $result[0]['id'] : null;
    }
    public function get_available_quantity($productId, $sizeId, $colorId) {
    $sql = "SELECT (quantity - reserved) as available 
            FROM product_size_color 
            WHERE id_product = :product_id 
            AND id_size = :size_id 
            AND id_color = :color_id
            FOR UPDATE"; // Khóa bản ghi khi đọc
    
    $params = [
        'product_id' => $productId,
        'size_id' => $sizeId,
        'color_id' => $colorId
    ];
    
    try {
        $result = $this->db->view_table($sql, $params);
        return isset($result[0]['available']) ? (int)$result[0]['available'] : 0;
    } catch (PDOException $e) {
        error_log("Get Available Quantity Error: " . $e->getMessage());
        return 0;
    }
}
}
?>
<?php $psc_table = new ProductSizeColorDao(); ?>
