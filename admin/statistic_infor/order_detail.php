<?php
require_once __DIR__ . '/../../dao/StatisticDao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Không tìm thấy hóa đơn.</p>";
    exit;
}
require_once __DIR__ . "/../../dao/UserDao.php";
$table_users = new UserDao();
$orderId = (int)$_GET['id'];
$statisticDao = new StatisticDao();
$bill = $statisticDao->getOrderDetail($orderId);

if (!$bill) {
    echo "<p>Không tìm thấy hóa đơn.</p>";
    exit;
}

?>
    <style>
    .order-detail-container {
    max-width: 700px;
    margin: 40px auto;
    background: #232837;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.25);
    padding: 32px 40px 40px 40px;
}
h2 {
    color: #7ecbff;
    margin-bottom: 18px;
    letter-spacing: 1px;
}
.info {
    margin-bottom: 18px;
}
.info span {
    display: inline-block;
    min-width: 120px;
    color: #a0aec0;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: #232837;
    color: #e6e6e6;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 10px;
}
th, td {
    padding: 10px 8px;
    border: 1px solid #353b4b;
}
th {
    background: #1a202c;
    color: #7ecbff;
    font-weight: 600;
}
tr:hover td {
    background: #2d3748;
}
.total {
    text-align: right;
    font-weight: bold;
    color: #7ecbff;
    padding-top: 12px;
    font-size: 17px;
}
a.back {
    display: inline-block;
    margin-bottom: 18px;
    color: #7ecbff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
a.back:hover {
    text-decoration: underline;
    color: #4f8cff;
}
@media (max-width: 700px) {
    .order-detail-container {
        padding: 16px 6px;
    }
    th, td {
        padding: 7px 4px;
        font-size: 13px;
    }
}
</style>
<div class="order-detail-container">
    <a class="back" href="javascript:history.back()">&larr; Quay lại</a>
    <h2>Chi tiết hóa đơn #<?php echo $orderId; ?></h2>
    <div class="info">
        <?php $user = $table_users->get_by_id($bill->id_user);?>
        <div><span>Khách hàng:</span> <?php echo htmlspecialchars($user->username); ?> (<?php echo htmlspecialchars($user->email); ?>)</div>
        <div><span>Ngày mua:</span> <?php echo $bill->created_at; ?></div>
        <div><span>Địa chỉ:</span> <?php echo htmlspecialchars($bill->shipping_address); ?></div>
        <div><span>Trạng thái:</span> <?php echo htmlspecialchars($bill->status); ?></div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php require_once __DIR__."/../../dao/BillProductDao.php";
            $table_billproducts = new BillProductDao();
            require_once __DIR__."/../../dao/ProductDao.php";
            $table_products = new ProductDao();

            ?>
            <?php foreach ($table_billproducts->get_by_bill($bill->id) as $prod): ?>
                <tr>
                    <td><?php echo htmlspecialchars($table_products->get_by_id($prod->id_product)->name); ?></td>
                    <td><?php echo $prod->quantity; ?></td>
                    <td><?php echo number_format($prod->unit_price); ?> VNĐ</td>
                    <td><?php echo number_format($prod->quantity*$prod->unit_price); ?> VNĐ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="total">
        Tổng tiền: <?php echo number_format($bill->total_amount); ?> VNĐ
    </div>
</div>