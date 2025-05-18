<?php
session_start();
require_once __DIR__ . '/../DAO/BillDAO.php';
require_once __DIR__ . '/../DAO/BillProductDAO.php';
require_once __DIR__ . '/../DAO/ProductDAO.php';
require_once __DIR__ . '/../DAO/ColorDAO.php';
require_once __DIR__ . '/../DAO/SizeDAO.php';
require_once __DIR__ . '/../DAO/PaymentMethodDao.php';
require_once __DIR__ . '/../database/database_sever.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$billDao = new BillDao();
$billProductDao = new BillProductDao();
$productDao = new ProductDao();
$colorDao = new ColorDao();
$sizeDao = new SizeDao();

$bills = $billDao->get_by_user($userId);

// Initialize variables for modal
$selectedBill = null;
$billProducts = [];
$showModal = false;

if (isset($_GET['bill_id'])) {
    $billId = $_GET['bill_id'];
    
    // Khởi tạo DAO cần thiết
    $paymentMethodDao = new PaymentMethodDao();

    $selectedBill = $billDao->get_by_id($billId);
    
    $BillStatus = $selectedBill->status;
    if ($selectedBill && $selectedBill->id_user == $userId) {
        $paymentMethod = $paymentMethodDao->get_by_id($selectedBill->id_payment_method);
        $paymentMethodName = $paymentMethod ? $paymentMethod->name : 'Không xác định';
        $billProducts = $billProductDao->get_by_bill($billId);
        $products =$productDao->get_by_ids(array_column($billProducts, 'id_product'));
        // Lấy thông tin sản phẩm từ DAO
        $imageUrls = [];
        foreach ($products as $product) {
            $imageUrls[$product->id] = $product->image_url;
        }
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
    <style>
        .color-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            color: white;
            font-size: 12px;
            text-shadow: 0 1px 1px rgba(0,0,0,0.3);
        }
        
        .order-products td, .order-products th {
            padding: 12px 8px;
            vertical-align: middle;
        }
        
        .product-name {
            min-width: 200px;
        }
    </style>
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
  <?php
    $total = 0; // ✅ Khởi tạo tổng tiền
  ?>
  <div class="modal show" id="orderModal">
    <div class="modal-content" style="max-width: 800px; margin: 4rem auto; background: #fff; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.2); padding: 2rem; position: relative;">
      <button onclick="closeModal()" style="position: absolute; top: 10px; right: 20px; font-size: 24px; background: none; border: none; cursor: pointer;">&times;</button>
     <?php 
   
switch ($BillStatus) {
    case 'pending':
        echo '<div class="success-icon" style="text-align:center; color:rgb(204, 135, 31); font-size: 4rem;">
            <i class="fa-solid fa-hourglass-half"></i>
              </div>';
        break;
        
    case 'completed':
      echo '<div class="success-icon" style="text-align:center; color:rgb(0, 243, 73); font-size: 4rem;">
            <i class="fa-solid fa-circle-check"></i>
              </div>';
        break;
        
    case 'cancelled':
 echo '<div class="success-icon" style="text-align:center; color:rgb(231, 10, 10); font-size: 4rem;">
<i class="fa-solid fa-xmark"></i>              </div>';        break;
      case 'processing':
        echo '<div class="success-icon" style="text-align:center; color:rgb(43, 31, 204); font-size: 4rem;">
            <i class="fa-solid fa-truck"></i>
              </div>';
        break;
    default:
        $statusClass = '';
        break;
}
?>

      <h2 class="text-center">Chi tiết đơn hàng #<?= htmlspecialchars($selectedBill->id) ?></h2>
      <p class="text-center">Cảm ơn bạn đã mua sắm tại YourStore!</p>

      <div class="order-details" style="margin-top: 2rem;">
        <p><strong>Ngày đặt hàng:</strong> <?= htmlspecialchars($selectedBill->bill_date) ?></p>
        <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($paymentMethodName); ?></p>
        <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($selectedBill->shipping_address) ?></p>
        <p><strong>Trạng thái:</strong> 
          <?php 
            switch ($selectedBill->status) {
              case 'pending': echo 'Đang xử lý'; break;
              case 'completed': echo 'Hoàn thành'; break;
              case 'cancelled': echo 'Đã hủy'; break;
              default: echo htmlspecialchars($selectedBill->status);
            }
          ?>
        </p>
      </div>

      <div class="order-items" style="margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1rem;">
        <h3>Sản phẩm đã đặt</h3>
        <?php foreach ($billProducts as $product): 
          $subtotal = $product->quantity * $product->unit_price;
          $total += $subtotal; // ✅ Cộng dồn tổng tiền
          $productDetail = $productDao->get_by_id($product->id_product);
          $colorDetail = property_exists($product, 'id_color') ? $colorDao->get_by_id($product->id_color) : null;
          $sizeDetail = property_exists($product, 'id_size') ? $sizeDao->get_by_id($product->id_size) : null;
        ?>
          <div class="order-item" style="display: flex; border-bottom: 1px solid #eee; padding: 1rem 0;">
                            <td>

            <div style="flex-grow: 1;">
              <h4 style="margin: 0;"><?= htmlspecialchars($productDetail->name) ?></h4>
              <p style="margin: 4px 0;">Màu: <?= $colorDetail ? "<span class='color-badge' style='background-color: {$colorDetail->hex_code}; color: #fff; padding: 2px 6px; border-radius: 12px;'>{$colorDetail->name}</span>" : '-' ?></p>
              <p style="margin: 4px 0;">Kích cỡ: <?= htmlspecialchars($sizeDetail->size_number ?? '-') ?></p>
              <p style="margin: 4px 0;">Số lượng: <?= $product->quantity ?></p>
              <p style="margin: 4px 0;">Đơn giá: <?= number_format($product->unit_price, 0, ',', '.') ?>₫</p>
              <p style="margin: 4px 0;"><strong>Thành tiền: <?= number_format($subtotal, 0, ',', '.') ?>₫</strong></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="order-summary" style="margin-top: 2rem; background: #f9f9f9; padding: 1rem; border-radius: 6px;">
        <table style="width: 100%;">
          <tr>
            <td><strong>Tạm tính:</strong></td>
            <td style="text-align: right;"><?= number_format($total, 0, ',', '.') ?>₫</td>
          </tr>
          <tr>
            <td><strong>Phí vận chuyển:</strong></td>
            <td style="text-align: right;">0₫</td>
          </tr>
          <tr>
            <td><strong>Tổng cộng:</strong></td>
            <td style="text-align: right; font-size: 1.2rem; font-weight: bold;"><?= number_format($total, 0, ',', '.') ?>₫</td>
          </tr>
        </table>
      </div>

      <div style="text-align:center;">
        <a href="/webbangiay/index.php" class="btn-continue" style="margin-top: 2rem; display: inline-block; background: #4CAF50; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none;">Tiếp tục mua sắm</a>
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