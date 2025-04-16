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
if (isset($_SESSION['user']['is_admin']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}

$user = $userDao->get_by_id($user_id);
$addresses = $informationReceiveDao->get_default_address($user_id);

if (!$user) {
    header("Location: error.php?code=404");
    exit();
}

// Error messages mapping
const ERROR_MESSAGES = [
    'email_exists' => 'Email này đã được sử dụng bởi tài khoản khác',
    'password_mismatch' => 'Mật khẩu xác nhận không khớp',
    'password_short' => 'Mật khẩu phải có ít nhất 8 ký tự',
    'invalid_email' => 'Email không hợp lệ',
    'update_failed' => 'Cập nhật thất bại, vui lòng thử lại'
];

$error = $_GET['error'] ?? '';
$errorMessage = $error ? (ERROR_MESSAGES[$error] ?? 'Có lỗi xảy ra khi cập nhật') : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin - <?php echo htmlspecialchars($user->username); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #45a049;
            --danger-color: #f44336;
            --danger-hover: #d32f2f;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --success-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }
        
        .edit-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .edit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .edit-title {
            margin: 0;
            color: var(--primary-color);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 5px;
            position: relative;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            cursor: pointer;
            font-weight: bold;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: var(--primary-color);
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        @media (max-width: 768px) {
            .edit-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h1 class="edit-title"><i class="fas fa-user-edit"></i> Chỉnh sửa thông tin</h1>
            <a href="user_site.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-error">
                <span><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errorMessage); ?></span>
                <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        
        <form action="update.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            
            <div class="form-group">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="<?php echo htmlspecialchars($user->username); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?php echo htmlspecialchars($user->email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Mật khẩu mới</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Để trống nếu không đổi mật khẩu">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                </div>
                <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label"><i class="fas fa-lock"></i> Xác nhận mật khẩu</label>
                <div class="password-container">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                </div>
            </div>
            
            <?php if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']): ?>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-toggle-on"></i> Trạng thái tài khoản</label>
                <div class="status-toggle">
                    <label class="switch">
                        <input type="checkbox" name="status" <?php echo $user->status ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                    <span><?php echo $user->status ? 'Đang kích hoạt' : 'Đang vô hiệu hóa'; ?></span>
                </div>
            </div>
            <?php endif; ?>
            
          
            
       
            
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="user_site.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Update status text when toggle changes
        const statusToggle = document.querySelector('input[name="status"]');
        if (statusToggle) {
            statusToggle.addEventListener('change', function() {
                const statusText = this.nextElementSibling.nextElementSibling;
                statusText.textContent = this.checked ? 'Đang kích hoạt' : 'Đang vô hiệu hóa';
            });
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password && password.length < 6) {
                e.preventDefault();
                alert('Mật khẩu phải có ít nhất 8 ký tự!');
                return;
            }
            
            if (password && password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                return;
            }
        });
    </script>
</body>
</html>