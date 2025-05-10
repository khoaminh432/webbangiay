<?php
session_start();
require_once __DIR__ . '/../DAO/BillDAO.php';
require_once __DIR__ . '/../DAO/BillProductDAO.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$billDao = new BillDao();
$billProductDao = new BillProductDao();

$bills = $billDao->get_by_user($userId);

// Initialize variables for modal
$selectedBill = null;
$billProducts = [];
$showModal = false;

if (isset($_GET['bill_id'])) {
    $billId = $_GET['bill_id'];
    $selectedBill = $billDao->get_by_id($billId);
    
    if ($selectedBill && $selectedBill->id_user == $userId) {
        $billProducts = $billProductDao->get_by_bill($billId);
        $showModal = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng | YourStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/bill.css">
</head>
<body>
  <?php include(__DIR__ . '/../layout/header.php'); ?>
  
  <div class="container">
    <div class="page-header">
      <h1 class="page-title">Lịch sử đơn hàng</h1>
    </div>
    
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Danh sách đơn hàng</h2>
      </div>
      <div class="card-body">
        <?php if (empty($bills)): ?>
          <div class="empty-state">
            <i class="far fa-clipboard"></i>
            <h3>Bạn chưa có đơn hàng nào</h3>
            <p>Hãy bắt đầu mua sắm để tận hưởng những ưu đãi từ chúng tôi</p>
            <a href="/" class="btn btn-primary">Mua sắm ngay</a>
          </div>
        <?php else: ?>
          <div class="order-list">
            <?php foreach ($bills as $bill): ?>
              <a href="?bill_id=<?= htmlspecialchars($bill->id) ?>" class="order-item">
                <div class="order-item-header">
                  <div class="order-item-title">
                    Đơn hàng #<?= htmlspecialchars($bill->id) ?>
                  </div>
                  <div class="order-item-meta">
                    <span><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($bill->bill_date) ?></span>
                    <span class="order-item-status status-<?= htmlspecialchars($bill->status) ?>">
                      <i class="fas fa-circle"></i>
                      <?php 
                        switch ($bill->status) {
                          case 'pending': echo 'Đang xử lý'; break;
                          case 'completed': echo 'Hoàn thành'; break;
                          case 'cancelled': echo 'Đã hủy'; break;
                          default: echo htmlspecialchars($bill->status);
                        }
                      ?>
                    </span>
                  </div>
                </div>
                <div class="order-item-body">
                  <div class="order-item-details">
                    <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($bill->shipping_address) ?></span>
                  </div>
                  <span class="order-item-amount"><?= number_format($bill->total_amount, 0, ',', '.') ?>₫</span>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Order Detail Modal -->
  <?php if ($showModal): ?>
    <div class="modal show" id="orderModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Chi tiết đơn hàng #<?= htmlspecialchars($selectedBill->id) ?></h3>
          <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
          <div class="order-detail">
            <div class="order-detail-header">
              <div class="order-detail-card">
                <div class="order-detail-card-title">Ngày đặt hàng</div>
                <div class="order-detail-card-value"><?= htmlspecialchars($selectedBill->bill_date) ?></div>
              </div>
              <div class="order-detail-card">
                <div class="order-detail-card-title">Trạng thái</div>
                <div class="order-detail-card-value">
                  <span class="order-item-status status-<?= htmlspecialchars($selectedBill->status) ?>">
                    <i class="fas fa-circle"></i>
                    <?php 
                      switch ($selectedBill->status) {
                        case 'pending': echo 'Đang xử lý'; break;
                        case 'completed': echo 'Hoàn thành'; break;
                        case 'cancelled': echo 'Đã hủy'; break;
                        default: echo htmlspecialchars($selectedBill->status);
                      }
                    ?>
                  </span>
                </div>
              </div>
              <div class="order-detail-card">
                <div class="order-detail-card-title">Phương thức thanh toán</div>
                <div class="order-detail-card-value">Thanh toán khi nhận hàng</div>
              </div>
              <div class="order-detail-card">
                <div class="order-detail-card-title">Địa chỉ giao hàng</div>
                <div class="order-detail-card-value"><?= htmlspecialchars($selectedBill->shipping_address) ?></div>
              </div>
            </div>
            
            <h3>Sản phẩm</h3>
            <table class="order-products">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th class="text-right">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $total = 0;
                foreach ($billProducts as $product): 
                  $subtotal = $product->quantity * $product->unit_price;
                  $total += $subtotal;
                ?>
                  <tr>
                    <td data-label="Sản phẩm" class="product-name">Sản phẩm #<?= htmlspecialchars($product->id_product) ?></td>
                    <td data-label="Đơn giá" class="product-price"><?= number_format($product->unit_price, 0, ',', '.') ?>₫</td>
                    <td data-label="Số lượng"><?= htmlspecialchars($product->quantity) ?></td>
                    <td data-label="Thành tiền" class="text-right product-total"><?= number_format($subtotal, 0, ',', '.') ?>₫</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            
            <div class="order-summary">
              <table class="order-summary-table">
                <tr>
                  <td>Tạm tính:</td>
                  <td class="text-right"><?= number_format($total, 0, ',', '.') ?>₫</td>
                </tr>
                <tr>
                  <td>Phí vận chuyển:</td>
                  <td class="text-right">0₫</td>
                </tr>
                <tr>
                  <td>Tổng cộng:</td>
                  <td class="text-right"><?= number_format($total, 0, ',', '.') ?>₫</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
  
  <?php include(__DIR__ . '/../layout/footer.php'); ?>
  
  <script>
    // Close modal function
    function closeModal() {
      window.location.href = window.location.pathname;
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
      const modal = document.getElementById('orderModal');
      if (event.target === modal) {
        closeModal();
      }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
      }
    });
  </script>
</body>
</html>