<?php
require_once __DIR__ . '/../DTO/BillDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class BillDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }
    public function view_all() {
        $sql = "SELECT * FROM bill";
        $results = $this->db->view_table($sql);
        
        $users = [];
        foreach ($results as $row) {
            $users[] = new BillDTO($row);
        }
        return $users;
    }
    public function get_by_user($userId) {
        $sql = "SELECT * FROM bill WHERE id_user = :id_user ORDER BY bill_date DESC";
        $params = ['id_user' => $userId];
        $results = $this->db->view_table($sql, $params);
        
        $bills = [];
        foreach ($results as $row) {
            $bills[] = new BillDTO($row);
        }
        return $bills;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM bill WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new BillDTO($result[0]) : null;
    }

    public function insert(BillDTO $bill) {
        $sql = "INSERT INTO bill (status, id_user, id_payment_method, total_amount, shipping_address) 
                VALUES (:status, :id_user, :id_payment_method, :total_amount, :shipping_address)";
        
        $params = [
            'status' => $bill->status,
            'id_user' => $bill->id_user,
            'id_payment_method' => $bill->id_payment_method,
            'total_amount' => $bill->total_amount,
            'shipping_address' => $bill->shipping_address
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("BillDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update_status($billId, $status) {
        $sql = "UPDATE bill SET status = :status WHERE id = :id";
        $params = [
            'id' => $billId,
            'status' => $status
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("BillDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM bill WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("BillDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
?>