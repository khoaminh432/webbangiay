<?php
require_once __DIR__ . '/../../dao/StatisticDao.php';
$from = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'asc' : 'desc';

$statisticDao = new StatisticDao();
$topCustomers = [];
if ($from && $to) {
    $topCustomers = $statisticDao->topCustomersWithOrders($from, $to, 5, $order);
}
?>
   
    <style>
    
    .statistic-container {
        max-width: 950px;
        margin: 40px auto;
        background: #232837;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.25);
        padding: 32px 40px 40px 40px;
    }
    h2 {
        text-align: center;
        color: #7ecbff;
        margin-bottom: 32px;
        letter-spacing: 1px;
    }
    .statistic-formcustomer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 18px;
        margin-bottom: 36px;
        flex-wrap: wrap;
    }
    .statistic-formcustomer label {
        font-weight: 500;
        color: #a0aec0;
    }
    .statistic-formcustomer input[type="date"], .statistic-formcustomer select {
        padding: 8px 12px;
        border: 1px solid #353b4b;
        border-radius: 6px;
        font-size: 15px;
        background: #232837;
        color: #e6e6e6;
        transition: border 0.2s;
    }
    .statistic-formcustomer input[type="date"]:focus, .statistic-formcustomer select:focus {
        border: 1.5px solid #7ecbff;
        outline: none;
    }
    .statistic-formcustomer button[type="submit"] {
        padding: 9px 22px;
        background: linear-gradient(90deg, #4f8cff 60%, #38c6ff 100%);
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(79,140,255,0.08);
        transition: background 0.2s, transform 0.15s;
    }
    .statistic-formcustomer button[type="submit"]:hover {
        background: linear-gradient(90deg, #38c6ff 60%, #4f8cff 100%);
        transform: translateY(-2px) scale(1.03);
    }
    .customer-block {
        margin-bottom: 32px;
        background: #232837;
        border-radius: 14px;
        padding: 22px 28px 18px 28px;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.13);
        border-left: 5px solid #4f8cff;
        transition: box-shadow 0.2s, border-left 0.2s;
    }
    .customer-block:hover {
        box-shadow: 0 6px 24px rgba(44, 62, 80, 0.23);
        border-left: 5px solid #38c6ff;
    }
    .customer-block h3 {
        margin: 0 0 10px 0;
        color: #7ecbff;
        font-size: 20px;
        letter-spacing: 0.5px;
    }
    .total-spent {
        font-weight: bold;
        color: #38c6ff;
        margin-top: 8px;
        margin-bottom: 12px;
        font-size: 16px;
    }
    .order-table {
        width: 100%;
        border-collapse: collapse;
        background: #232837;
        margin-top: 8px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(44, 62, 80, 0.10);
        color: #e6e6e6;
    }
    .order-table th, .order-table td {
        padding: 10px 8px;
        border: 1px solid #353b4b;
        text-align: left;
    }
    .order-table th {
        background: #1a202c;
        color: #7ecbff;
        font-weight: 600;
    }
    .order-table tr:hover td {
        background: #2d3748;
    }
    .order-table a {
        color: #7ecbff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    .order-table a:hover {
        color: #38c6ff;
        text-decoration: underline;
    }
    p {
        text-align: center;
        color: #ff7675;
        font-weight: 500;
        margin-top: 32px;
    }
    @media (max-width: 700px) {
        .statistic-container {
            padding: 16px 4px;
        }
        .customer-block {
            padding: 12px 6px 10px 12px;
        }
        .order-table th, .order-table td {
            padding: 7px 4px;
            font-size: 13px;
        }
        .statistic-formcustomer {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
<div class="statistic-container">
    <h2>Thống kê Top 5 Khách Hàng Mua Nhiều Nhất</h2>
    <form class="statistic-formcustomer" method="GET" action="">
        <label>Từ ngày:</label>
        <input type="date" name="from_date" required value="<?php echo $from; ?>">
        <label>Đến ngày:</label>
        <input type="date" name="to_date" required value="<?php echo $to; ?>">
        <label>Sắp xếp:</label>
        <select name="order">
            <option value="desc" <?php if($order=='desc') echo 'selected'; ?>>Giảm dần</option>
            <option value="asc" <?php if($order=='asc') echo 'selected'; ?>>Tăng dần</option>
        </select>
        <button type="submit">Thống kê</button>
    </form>

    <?php if (!empty($topCustomers)): ?>
        <?php foreach ($topCustomers as $cus): ?>
            <div class="customer-block">
                <h3>
                    <?php echo htmlspecialchars($cus['username']); ?> 
                    (<?php echo htmlspecialchars($cus['email']); ?>)
                </h3>
                <div class="total-spent">
                    Tổng mua: <?php echo number_format($cus['total_spent']); ?> VNĐ
                </div>
                <?php if (!empty($cus['orders'])): ?>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày mua</th>
                                <th>Tổng tiền</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cus['orders'] as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo $order['created_at']; ?></td>
                                    <td><?php echo number_format($order['total_amount']); ?> VNĐ</td>
                                    <td>
                                    <a href="#" class="view-order-detail" 
                                    data-id="<?php echo $order['id']; ?>">Xem chi tiết</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Không có đơn hàng trong thời gian này.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php elseif ($from && $to): ?>
        <p>Không có dữ liệu trong khoảng thời gian này.</p>
    <?php endif; ?>
</div>
<div id="order-modal-overlay" style="display:none;
position:fixed;
top:0;left:0;
width:100vw;
height:100vh;
background:rgba(24,28,36,0.7);
z-index:9998;"></div>
<div id="order-modal" 
style="display:none;
position:fixed;top:50%;
left:50%;
transform:translate(-50%,-50%);
z-index:9999;min-width:350px;
max-width:90vw;
max-height:90vh;
overflow:auto;
border-radius:14px;
box-shadow:0 8px 32px rgba(0,0,0,0.35);
background:#232837;"></div>
<script src="js/admin/statistic.js"></script>