<?php
require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';

// Start session
session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    header("Location: /webbangiay/layout/login_signup.php");
    exit();
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

$user_id = $_SESSION['user_id'];
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $user_id = $_GET['user_id'] ?? $user_id;
}

$user = $userDao->get_by_id($user_id);
$addresses = $informationReceiveDao->get_default_address($user_id);

if (!$user) {
    header("Location: error.php?code=404");
    exit();
}

// Set variables for layout
$title = htmlspecialchars($user->username) . ' - Thông tin người dùng';
$css = 'user/profile.css';
$js = 'user/profile.js';
?>
<?php include(__DIR__ . '/../../layout/header.php'); ?>
<link rel="stylesheet" href="../../css/header.css">
<link rel="stylesheet" href="../../css/footer.css">
<link rel="stylesheet" href="../../css/profile.css">
<div class="profile-container">
    <!-- Minimalist header -->
    <div class="profile-header">
        <h1 class="profile-title">THÔNG TIN CÁ NHÂN</h1>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="/webbangiay/admin_dashboard.php" class="back-link">
                ← Quay lại
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Success message - monochrome style -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-message">
            <span>Cập nhật thông tin thành công</span>
            <span class="close-btn" onclick="this.parentElement.style.display='none'">×</span>
        </div>
    <?php endif; ?>
    
    <!-- User info section - clean layout -->
    <div class="profile-info">
        <div class="info-section">
            <h2 class="section-title">THÔNG TIN CƠ BẢN</h2>
            
            <div class="info-row">
                <span class="label">Tên đăng nhập</span>
                <span class="value"><?php echo htmlspecialchars($user->username); ?></span>
            </div>
            
            <div class="info-row">
                <span class="label">Email</span>
                <span class="value"><?php echo htmlspecialchars($user->email); ?></span>
            </div>
            
            <div class="info-row">
                <span class="label">Trạng thái</span>
                <span class="value status-<?php echo strtolower($user->status); ?>">
                    <?php echo $user->status == 'UNLOCK' ? 'Kích hoạt' : 'Vô hiệu hóa'; ?>
                </span>
            </div>
        </div>
        
        <div class="info-section">
            <h2 class="section-title">ĐỊA CHỈ</h2>
            
            <div class="info-row address-row">
                <span class="label">Địa chỉ mặc định</span>
                <div class="address-content">
                    <span class="address-value">
                        <?php 
                        if ($addresses && isset($addresses->address)) {
                            echo htmlspecialchars($addresses->address);
                        } else {
                            echo "<span class='no-address'>Chưa cập nhật địa chỉ</span>";
                        }
                        ?>
                    </span>
                    <a href="/webbangiay/index.php?page=receive&user_id=<?php echo $user_id; ?>" class="edit-link">
                        [Chỉnh sửa]
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action buttons - minimalist style -->
    <div class="action-buttons">
        <a href="/webbangiay/pages/user/edit_user.php" class="btn-edit">
            Chỉnh sửa thông tin
        </a>
      
        <a href="/webbangiay/layout/user/logout.php" class="btn-logout">
            Đăng xuất
        </a>
    </div>
</div>
<?php include(__DIR__ . '/../../layout/footer.php'); ?>

<script>
    // Auto-hide success message
    setTimeout(() => {
        const alert = document.querySelector('.alert-message');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);
</script>