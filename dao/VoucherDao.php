<?php
require_once __DIR__ . '/../DTO/dto.php';
require_once __DIR__ . '/../database/database_sever.php';

class VoucherDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM voucher";
        $results = $this->db->view_table($sql);
        
        $vouchers = [];
        foreach ($results as $row) {
            $vouchers[] = new VoucherDTO($row);
        }
        return $vouchers;
    }

    public function get_active_vouchers() {
        $currentDate = date('Y-m-d');
        $sql = "SELECT * FROM voucher WHERE is_active = TRUE 
                AND date_start <= :current_date 
                AND (date_end IS NULL OR date_end >= :current_date)";
        $params = ['current_date' => $currentDate];
        $results = $this->db->view_table($sql, $params);
        
        $vouchers = [];
        foreach ($results as $row) {
            $vouchers[] = new VoucherDTO($row);
        }
        return $vouchers;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM voucher WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new VoucherDTO($result[0]) : null;
    }

    public function insert(VoucherDTO $voucher) {
        $sql = "INSERT INTO voucher (name, deduction, description, date_start, date_end, id_admin, is_active) 
                VALUES (:name, :deduction, :description, :date_start, :date_end, :id_admin, :is_active)";
        
        $params = [
            'name' => $voucher->name,
            'deduction' => $voucher->deduction,
            'description' => $voucher->description,
            'date_start' => $voucher->date_start,
            'date_end' => $voucher->date_end,
            'id_admin' => $voucher->id_admin,
            'is_active' => $voucher->is_active
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("VoucherDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update(VoucherDTO $voucher) {
        $sql = "UPDATE voucher SET 
                name = :name,
                deduction = :deduction,
                description = :description,
                date_start = :date_start,
                date_end = :date_end,
                id_admin = :id_admin,
                is_active = :is_active
                WHERE id = :id";
        
        $params = [
            'id' => $voucher->id,
            'name' => $voucher->name,
            'deduction' => $voucher->deduction,
            'description' => $voucher->description,
            'date_start' => $voucher->date_start,
            'date_end' => $voucher->date_end,
            'id_admin' => $voucher->id_admin,
            'is_active' => $voucher->is_active
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("VoucherDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM voucher WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("VoucherDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
?>