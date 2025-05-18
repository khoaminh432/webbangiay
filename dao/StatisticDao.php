<?php
require_once __DIR__ . '/../database/database_sever.php';

class StatisticDao {
    private $db;

    public function __construct() {
        $this->db = new database_sever();
    }

    // Thống kê doanh thu theo sản phẩm trong khoảng thời gian
    public function revenueByProduct($fromDate, $toDate) {
        $sql = "SELECT 
                    p.id, p.name, SUM(bp.quantity) as total_quantity, SUM(bp.quantity * bp.unit_price) as total_revenue
                FROM bill_products bp
                JOIN products p ON bp.id_product = p.id
                JOIN bill b ON bp.id_bill = b.id
                WHERE b.created_at BETWEEN :from AND :to
                GROUP BY p.id, p.name
                ORDER BY total_revenue DESC";
        $params = [
            'from' => $fromDate . ' 00:00:00',
            'to' => $toDate . ' 23:59:59'
        ];
        return $this->db->view_table($sql, $params);
    }
    public function getOrderDetail($orderId) {
        // Lấy hóa đơn
        require_once __DIR__ . '/BillDao.php';
        $billDao = new BillDao();
        $bill = $billDao->get_by_id($orderId); // BillDTO

        if (!$bill) return null;

        // Lấy user
        require_once __DIR__ . '/UserDao.php';
        $userDao = new UserDao();
        $user = $userDao->get_by_id($bill->id_user);

        // Lấy danh sách sản phẩm trong hóa đơn
        require_once __DIR__ . '/BillProductDao.php';
        $billProductDao = new BillProductDao();
        $billProducts = $billProductDao->get_by_bill($orderId); // array BillProductDTO

        // Lấy thông tin sản phẩm
        require_once __DIR__ . '/ProductDao.php';
        $productDao = new ProductDao();

        $products = [];
        foreach ($billProducts as $bp) {
            $product = $productDao->get_by_id($bp->id_product);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $bp->quantity,
                    'unit_price' => $bp->unit_price,
                    'total' => $bp->quantity * $bp->unit_price
                ];
            }
        }

        return $bill; // BillDTO với user và products
    }
    public function topCustomersWithOrders($fromDate, $toDate, $limit = 5, $order = 'desc') {
    $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';
    $sql = "SELECT 
                u.id, u.username, u.email, SUM(b.total_amount) as total_spent
            FROM bill b
            JOIN users u ON b.id_user = u.id
            WHERE b.created_at BETWEEN :from AND :to
            GROUP BY u.id, u.username, u.email
            ORDER BY total_spent $order
            LIMIT :limit";
    $stmt = $this->db->conn->prepare($sql);
    $stmt->bindValue(':from', $fromDate . ' 00:00:00');
    $stmt->bindValue(':to', $toDate . ' 23:59:59');
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy đơn hàng cho từng khách
    foreach ($customers as &$cus) {
        $sqlOrder = "SELECT id, created_at, total_amount FROM bill 
                     WHERE id_user = :uid AND created_at BETWEEN :from AND :to
                     ORDER BY created_at DESC";
        $stmtOrder = $this->db->conn->prepare($sqlOrder);
        $stmtOrder->bindValue(':uid', $cus['id']);
        $stmtOrder->bindValue(':from', $fromDate . ' 00:00:00');
        $stmtOrder->bindValue(':to', $toDate . ' 23:59:59');
        $stmtOrder->execute();
        $cus['orders'] = $stmtOrder->fetchAll(PDO::FETCH_ASSOC);
    }
    return $customers;
}
    // Thống kê 5 khách hàng mua nhiều nhất trong khoảng thời gian
    public function topCustomers($fromDate, $toDate, $limit = 5) {
        $sql = "SELECT 
                    u.id, u.username, u.email, SUM(b.total_amount) as total_spent
                FROM bill b
                JOIN users u ON b.id_user = u.id
                WHERE b.created_at BETWEEN :from AND :to
                GROUP BY u.id, u.username, u.email
                ORDER BY total_spent DESC
                LIMIT :limit";
        $params = [
            'from' => $fromDate . ' 00:00:00',
            'to' => $toDate . ' 23:59:59',
            'limit' => (int)$limit
        ];
        // view_table không hỗ trợ bindValue với PDO::PARAM_INT, nên dùng prepare thủ công:
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindValue(':from', $params['from']);
        $stmt->bindValue(':to', $params['to']);
        $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>