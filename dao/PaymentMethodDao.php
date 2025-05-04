<?php
require_once __DIR__ . '/../DTO/PaymentMethodDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class PaymentMethodDao {
    private $db;
    
    public function __construct() {
        $this->db = new database_sever();
    }

    public function view_all() {
        $sql = "SELECT * FROM payment_method";
        $results = $this->db->view_table($sql);
        
        $methods = [];
        foreach ($results as $row) {
            $methods[] = new PaymentMethodDTO($row);
        }
        return $methods;
    }

    public function get_by_id($id) {
        $sql = "SELECT * FROM payment_method WHERE id = :id";
        $params = ['id' => $id];
        $result = $this->db->view_table($sql, $params);
        
        return !empty($result) ? new PaymentMethodDTO($result[0]) : null;
    }
    public function getID() {
        $sql = "SELECT id FROM payment_method ORDER BY id ASC";
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
    public function insert(PaymentMethodDTO $method) {
        $sql = "INSERT INTO payment_method (id,name, is_active) VALUES (:id,:name, :is_active)";
        $params = [
            "id" => $this->getID(),
            'name' => $method->name,
            'is_active' => $method->is_active
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("PaymentMethodDao Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update(PaymentMethodDTO $method) {
        $sql = "UPDATE payment_method SET name = :name, is_active = :is_active WHERE id = :id";
        $params = [
            'id' => $method->id,
            'name' => $method->name,
            'is_active' => $method->is_active
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PaymentMethodDao Update Error: " . $e->getMessage());
            return false;
        }
    }
    public function update_active(int $id_method,$is_active) {
        $sql = "UPDATE payment_method SET is_active = :is_active WHERE id = :id";
        $params = [
            'id' => $id_method,
            'is_active' => $is_active
        ];
        
        try {
            return $this->db->update_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PaymentMethodDao Update Error: " . $e->getMessage());
            return false;
        }
    }
    public function delete($id) {
        $sql = "DELETE FROM payment_method WHERE id = :id";
        $params = ['id' => $id];
        
        try {
            return $this->db->delete_table($sql, $params);
        } catch (PDOException $e) {
            error_log("PaymentMethodDao Delete Error: " . $e->getMessage());
            return false;
        }
    }
    public function check_bill($id_method){
        require_once __DIR__."/BillDao.php";
        $temp = $table_bills->get_by_method($id_method);
        return empty($temp);
    }
}
?>
<?php $table_paymentmethode = new PaymentMethodDao();?>