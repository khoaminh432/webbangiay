<?php
require_once __DIR__ . '/../../DAO/UserDao.php';
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../../DTO/UserDTO.php';
require_once __DIR__ . '/../../DTO/InformationReceiveDTO.php';

session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    header("Location: /webbangiay/layout/login_signup.php");
    exit();
}

$userDao = new UserDao();
$informationReceiveDao = new InformationReceiveDao();

// Get user ID from session (or from GET if admin)
$current_user_id = $_SESSION['user_id'];
$user_id = $_GET['user_id'] ?? $current_user_id;

// Verify permissions
if ($user_id != $current_user_id && !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header("Location: /webbangiay/index.php?page=info&error=unauthorized");
    exit();
}

$user = $userDao->get_by_id($user_id);
$address = $informationReceiveDao->get_default_address($user_id);

if (!$user) {
    header("Location: /webbangiay/index.php?page=info&error=user_not_found");
    exit();
}

$title = "Chỉnh sửa thông tin - " . htmlspecialchars($user->username);
?>
<?php include(__DIR__ . '/../../layout/header.php'); ?>
<link rel="stylesheet" href="../../css/header.css">
<link rel="stylesheet" href="../../css/footer.css">
<link rel="stylesheet" href="../../css/profile.css">
<div class="profile-container">
    <div class="profile-header">
        <h1 class="profile-title">CHỈNH SỬA THÔNG TIN</h1>
        <a href="/webbangiay/index.php?page=info" class="back-link">
            ← Quay lại
        </a>
    </div>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert-message error">
            <?php 
            $errors = [
                'password_mismatch' => 'Mật khẩu mới không khớp',
                'password_short' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'update_failed' => 'Cập nhật thất bại',
                'db_error' => 'Lỗi cơ sở dữ liệu',
                'invalid_email' => 'Email không hợp lệ',
                'email_exists' => 'Email đã tồn tại',
                'unauthorized' => 'Không có quyền truy cập',
                'user_not_found' => 'Người dùng không tồn tại',
                'wrong_password' => 'Mật khẩu hiện tại không đúng'
            ];
            echo $errors[$_GET['error']] ?? 'Có lỗi xảy ra';
            ?>
            <span class="close-btn" onclick="this.parentElement.style.display='none'">×</span>
        </div>
    <?php endif; ?>
    
    <form action="/webbangiay/pages/user/update.php" method="POST" class="edit-form">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        
        <div class="info-section">
            <h2 class="section-title">THÔNG TIN TÀI KHOẢN</h2>
            
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->username); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
            </div>
            
            <div class="form-group">
                <button type="button" class="btn-change-password" onclick="showPasswordModal()">
                    ĐỔI MẬT KHẨU
                </button>
            </div>
        </div>
        
        <div class="info-section">
            <h2 class="section-title">THÔNG TIN LIÊN HỆ</h2>
            
            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input type="text" id="name" name="name" value="<?php echo isset($address->name) ? htmlspecialchars($address->name) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" id="phone" name="phone" value="<?php echo isset($address->phone) ? htmlspecialchars($address->phone) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" value="<?php echo isset($address->address) ? htmlspecialchars($address->address) : ''; ?>">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-save">LƯU THAY ĐỔI</button>
            <a href="/webbangiay/pages/user/profile.php" class="btn-cancel">HỦY BỎ</a>
        </div>
    </form>
</div>

<!-- Password Verification Modal -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="hidePasswordModal()">&times;</span>
        <h2>XÁC MINH MẬT KHẨU</h2>
        <p>Vui lòng nhập mật khẩu hiện tại để tiếp tục</p>
        
        <form id="verifyPasswordForm" method="POST" action="/webbangiay/pages/user/verify_password.php">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            
         <div class="form-group">
    <input type="password" id="current_password" name="current_password" placeholder="Mật khẩu hiện tại" required>
</div>
            
            <div class="modal-actions">
                <button type="submit" class="btn-verify">XÁC MINH</button>
                <button type="button" class="btn-cancel" onclick="hidePasswordModal()">HỦY</button>
            </div>
        </form>
    </div>
</div>

<!-- New Password Modal (hidden until verification) -->
<div id="newPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="hideNewPasswordModal()">&times;</span>
        <h2>ĐẶT MẬT KHẨU MỚI</h2>
        <p>Vui lòng nhập mật khẩu mới và xác nhận</p>
        
        <form id="newPasswordForm">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            
            <div class="form-group">
    <input type="password" id="new_password" name="new_password" placeholder="Mật khẩu mới" required>
</div>
<div class="form-group">
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
</div>
            
            <div class="modal-actions">
                <button type="button" class="btn-save" onclick="updatePassword()">LƯU MẬT KHẨU</button>
                <button type="button" class="btn-cancel" onclick="hideNewPasswordModal()">HỦY</button>
            </div>
        </form>
    </div>
</div>

<?php include(__DIR__ . '/../../layout/footer.php'); ?>

<script>
    // Show password verification modal
    function showPasswordModal() {
        document.getElementById('passwordModal').style.display = 'block';
    }
    
    // Hide password verification modal
    function hidePasswordModal() {
        document.getElementById('passwordModal').style.display = 'none';
    }
    
    // Show new password modal
    function showNewPasswordModal() {
        hidePasswordModal();
        document.getElementById('newPasswordModal').style.display = 'block';
    }
    
    // Hide new password modal
    function hideNewPasswordModal() {
        document.getElementById('newPasswordModal').style.display = 'none';
    }
    
    // Handle password verification form submission
    document.getElementById('verifyPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNewPasswordModal();
            } else {
                alert(data.message || 'Mật khẩu không đúng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xác minh mật khẩu');
        });
    });
    
    // Handle new password submission
    // Handle new password submission
function updatePassword() {
    const btn = document.querySelector('#newPasswordModal .btn-save');
    btn.disabled = true;
    btn.textContent = 'Đang xử lý...';

    const formData = new FormData();
    formData.append('user_id', '<?php echo $user_id; ?>');
    formData.append('current_password', document.getElementById('current_password').value);
    formData.append('new_password', document.getElementById('new_password').value);
    formData.append('confirm_password', document.getElementById('confirm_password').value);

    fetch('/webbangiay/pages/user/update.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error(`Invalid JSON: ${text.substring(0, 100)}`);
        }
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            hideNewPasswordModal();
            // Clear password fields
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_password').value = '';
        } else {
            alert(data.message || 'Đổi mật khẩu thất bại');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi hệ thống: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'LƯU MẬT KHẨU';
    });
}
    // Auto-hide error message after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-message');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);
</script>