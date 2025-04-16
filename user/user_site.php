<?php
require_once __DIR__ . '/../DAO/UserDao.php';
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
session_start();

// Security check - verify user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

// Use session user_id as primary, GET parameter as fallback only for admins
$user_id = $_SESSION['user']['id'];
if (isset($_SESSION['user']['is_admin'])) {
    $user_id = $_GET['user_id'] ?? $user_id;
}
$user = $userDao->get_by_id($user_id);
$addresses = $informationReceiveDao->get_default_address($user_id);

if (!$user) {
    header("Location: error.php?code=404");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user->username); ?> - Thông tin người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #45a049;
            --danger-color: #f44336;
            --danger-hover: #d32f2f;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
        }
        
        body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 20px;
}

.profile-container {
  width: 100%;
   
    margin: 0 auto;
  margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.profile-info .info-row {
    display: flex;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.profile-info .label {
    font-weight: bold;
    width: 150px;
    color: #555;
}

.profile-info .value {
    flex: 1;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

.btn {
    display: inline-block;
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    text-align: center;
    margin-right: 10px;
}

.btn:hover {
    background-color: #45a049;
}

.btn.cancel {
    background-color: #f44336;
}

.btn.cancel:hover {
    background-color: #d32f2f;
}

.btn.logout {
    background-color: #ff9800;
}

.btn.logout:hover {
    background-color: #e68a00;
}

.action-buttons {
    margin-top: 30px;
    text-align: center;
}

.alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert.success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
}

.alert.error {
    background-color: #f2dede;
    color: #a94442;
    border: 1px solid #ebccd1;
} 
        .address-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .address-value {
            flex: 1;
            min-width: 200px;
        }
        
        .address-btn {
            padding: 0.4rem 0.8rem;
            border-radius: 5px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .address-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1 class="profile-title">Thông tin cá nhân</h1>
            <?php if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']): ?>
                <a href="admin_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <span>Cập nhật thông tin thành công!</span>
                <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        
        <div class="profile-info">
            <div class="info-row">
                <span class="label"><i class="fas fa-user"></i> Tên đăng nhập:</span>
                <span class="value"><?php echo htmlspecialchars($user->username); ?></span>
            </div>
            
            <div class="info-row">
                <span class="label"><i class="fas fa-envelope"></i> Email:</span>
                <span class="value"><?php echo htmlspecialchars($user->email); ?></span>
            </div>
            
            <div class="info-row">
                <span class="label"><i class="fas fa-circle"></i> Trạng thái:</span>
                <span class="value <?php echo $user->status == 'UNLOCK' ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo $user->status == 'UNLOCK' ? 'Kích hoạt' : 'Vô hiệu hóa'; ?>
                </span>
            </div>
            
            <div class="info-row">
                <span class="label"><i class="fas fa-map-marker-alt"></i> Địa chỉ:</span>
                <div class="address-container">
                    <span class="address-value">
                        <?php 
                        if ($addresses && isset($addresses->address)) {
                            echo htmlspecialchars($addresses->address);
                        } else {
                            echo "<span style='color:#999'>Chưa cập nhật địa chỉ</span>";
                        }
                        ?>
                    </span>
                    <a href="receive.php?user_id=<?php echo $user_id; ?>" class="address-btn">
                        <i class="fas fa-edit"></i> Quản lý
                    </a>
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="edit_user.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa thông tin
            </a>
          
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </div>

    <script>
        // Auto-hide success message after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>