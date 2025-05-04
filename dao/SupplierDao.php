<?php
require_once __DIR__ . '/../DTO/SupplierDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class SupplierDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM supplier";
        $results = $this->db->view_table($sql);
        
        $suppliers = [];
        foreach ($results as $row) {
            $suppliers[] = new SupplierDTO($row);
        }
        return $suppliers;
    }

    public function get_active_suppliers() {
        $sql = "SELECT * FROM supplier WHERE status = 1";
        $results = $this->db->view_table($sql);
        
        $suppliers = [];
        foreach ($results as $row) {
            $suppliers[] = new SupplierDTO($row);
        }
        return $suppliers;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM supplier WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new SupplierDTO($result[0]) : null;
    }

    public function insert(SupplierDTO $supplier) {
        $sql = "INSERT INTO supplier (address, phone, name, status, id_admin) 
                VALUES (:address, :phone, :name, :status, :id_admin)";
        
        $params = [
            'address' => $supplier->address,
            'phone' => $supplier->phone,
            'name' => $supplier->name,
            'status' => $supplier->status,
            'id_admin' => $supplier->id_admin
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("SupplierDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update(SupplierDTO $supplier) {
        $sql = "UPDATE supplier SET 
                address = :address,
                phone = :phone,
                name = :name,
                status = :status,
                id_admin = :id_admin
                WHERE id = :id";
        
        $params = [
            'id' => $supplier->id,
            'address' => $supplier->address,
            'phone' => $supplier->phone,
            'name' => $supplier->name,
            'status' => $supplier->status,
            'id_admin' => $supplier->id_admin
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("SupplierDao Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM supplier WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("SupplierDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
    public function getID() {
        $sql = "SELECT id FROM supplier ORDER BY id ASC";
        $result = $this->db->view_table($sql);
    
        $expectedID = 1;
        foreach ($result as $row) {
            if ((int)$row['id'] != $expectedID) {
                return $expectedID;
            }
            $expectedID++;
        }
    
        // Nếu không thiếu ID nào, trả về ID tiếp theo
        return $expectedID;
    }
}
?>
<?php $table_supplier = new SupplierDao();?>