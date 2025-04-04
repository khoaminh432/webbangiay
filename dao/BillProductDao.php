<?php
require_once __DIR__ . '/../DTO/dto.php';
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

    public function insert(BillProductDTO $item) {
        $sql = "INSERT INTO bill_products (id_bill, id_product, quantity, unit_price) 
                VALUES (:id_bill, :id_product, :quantity, :unit_price)";
        
        $params = [
            'id_bill' => $item->id_bill,
            'id_product' => $item->id_product,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("BillProductDao Insert Error: " . $e->getMessage());
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
}
?>