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

    $sql = "INSERT INTO bill_products 
            (id_bill, id_product, quantity, unit_price" 
            . (isset($billProduct->id_color) ? ", id_color" : "") 
            . (isset($billProduct->id_size) ? ", id_size" : "") 
            . ") VALUES 
            (:id_bill, :id_product, :quantity, :unit_price"
            . (isset($billProduct->id_color) ? ", :id_color" : "") 
            . (isset($billProduct->id_size) ? ", :id_size" : "") 
            . ")";
    
    $params = [
        'id_bill' => $billProduct->id_bill,
        'id_product' => $billProduct->id_product,
        'quantity' => $billProduct->quantity,
        'unit_price' => $billProduct->unit_price
    ];
    
    if (isset($billProduct->id_color)) {
        if (!is_numeric($billProduct->id_color)) {
            error_log("Invalid color ID in BillProduct insert");
            return false;
        }
        $params['id_color'] = $billProduct->id_color;
    }
    
    if (isset($billProduct->id_size)) {
        if (!is_numeric($billProduct->id_size)) {
            error_log("Invalid size ID in BillProduct insert");
            return false;
        }
        $params['id_size'] = $billProduct->id_size;
    }
    
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
