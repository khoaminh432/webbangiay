<?php
require_once __DIR__ . '/../DTO/BillProductDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class BillProductDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function get_by_bill($billId) {
        $sql = "SELECT * FROM bill_products WHERE id_bill = :id_bill";
        $params = ['id_bill' => $billId];
        $results = $this->db->view_table($sql, $params);
        
        $items = [];
        foreach ($results as $row) {
            $items[] = new BillProductDTO($row);
        }
        return $items;
    }

   
  public function insert(BillProductDTO $billProduct) {
    // Validate required fields
    if (empty($billProduct->id_bill) || empty($billProduct->id_product) || 
        empty($billProduct->quantity) || empty($billProduct->unit_price)) {
        error_log("Missing required fields in BillProduct insert");
        return false;
    }

    // Bắt đầu xây dựng SQL
    $columns = ['id_bill', 'id_product', 'quantity', 'unit_price'];
    $placeholders = [':id_bill', ':id_product', ':quantity', ':unit_price'];
    $params = [
        'id_bill' => $billProduct->id_bill,
        'id_product' => $billProduct->id_product,
        'quantity' => $billProduct->quantity,
        'unit_price' => $billProduct->unit_price
    ];

    // Thêm id_color nếu hợp lệ
    if (!is_null($billProduct->id_color)) {
        if (!is_numeric($billProduct->id_color)) {
            error_log("Invalid color ID in BillProduct insert");
            return false;
        }
        $columns[] = 'id_color';
        $placeholders[] = ':id_color';
        $params['id_color'] = $billProduct->id_color;
    }

    // Thêm id_size nếu hợp lệ
    if (!is_null($billProduct->id_size)) {
        if (!is_numeric($billProduct->id_size)) {
            error_log("Invalid size ID in BillProduct insert");
            return false;
        }
        $columns[] = 'id_size';
        $placeholders[] = ':id_size';
        $params['id_size'] = $billProduct->id_size;
    }

    // Ghép chuỗi câu SQL
    $sql = "INSERT INTO bill_products (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";

    try {
        $result = $this->db->insert_table($sql, $params);
        if (!$result) {
            error_log("Insert BillProduct failed: " . print_r($params, true));
            return false;
        }
        return $result;
    } catch (PDOException $e) {
        error_log("BillProductDAO Insert Error: " . $e->getMessage());
        return false;
    }
}

    public function delete_by_bill($billId) {
        $sql = "DELETE FROM bill_products WHERE id_bill = :id_bill";
        $params = ['id_bill' => $billId];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("BillProductDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
    public function get_by_product($id_product) {
        $sql = "SELECT * FROM bill_products WHERE id_product = :id_product";
        $params = ['id_product' => $id_product];
        $results = $this->db->view_table($sql, $params);
        
        $items = [];
        foreach ($results as $row) {
            $items[] = new BillProductDTO($row);
        }
        return $items;
    }
    
}
?>
<?php $table_billproducts = new BillProductDao();?>
