<?php
require_once __DIR__ . "/../../../../dao/UserDao.php";
$user = $table_users->get_by_id(2);
?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformuser.css">

<div class="user-view-modal">
    <div class="user-view-card">
        <div class="card-header">
            <h2 class="card-title">User Profile</h2>
            <button class="close-btn" onclick="closeUserView()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div class="card-body">
            <div class="user-profile">
                <div class="avatar-container">
                    <div class="avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username) ?>&background=<?= dechex(crc32($user->id) % 0xFFFFFF) ?>&color=fff&size=200" 
                             alt="User Avatar" class="user-avatar">
                        <div class="status-badge status-<?= strtolower($user->status) ?>">
                            <?= ucfirst($user->status) ?>
                        </div>
                    </div>
                    <h3 class="user-name"><?= htmlspecialchars($user->username) ?></h3>
                    <p class="user-email"><?= htmlspecialchars($user->email) ?></p>
                </div>

                <div class="user-details">
                    <div class="detail-section">
                        <h4 class="section-title">Account Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">User ID</span>
                                <span class="detail-value">#<?= htmlspecialchars($user->id) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status</span>
                                <span class="detail-value status-value status-<?= strtolower($user->status) ?>">
                                    <?= ucfirst($user->status) ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Created</span>
                                <span class="detail-value">
                                    <?= $user->created_at ? date('F j, Y \a\t g:i A', strtotime($user->created_at)) : 'Unknown' ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Updated</span>
                                <span class="detail-value">
                                    <?= $user->updated_at ? date('F j, Y \a\t g:i A', strtotime($user->updated_at)) : 'Never' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4 class="section-title">Security</h4>
                        <div class="security-info">
                            <div class="security-item">
                                <ion-icon name="shield-checkmark-outline" class="security-icon"></ion-icon>
                                <span>Password last changed: <?= $user->updated_at ? date('M Y', strtotime($user->updated_at)) : 'Unknown' ?></span>
                            </div>
                            <div class="security-item">
                                <ion-icon name="mail-unread-outline" class="security-icon"></ion-icon>
                                <span>Email <?= filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'verified' : 'unverified' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>



<script>
function closeUserView() {
    document.querySelector('.user-view-modal').style.animation = 'fadeIn 0.3s ease reverse forwards';
    setTimeout(() => {
        // Remove modal or navigate back
        window.history.back();
    }, 300);
}

function editUser(userId) {
    // Smooth transition before redirect
    document.querySelector('.user-view-modal').style.opacity = '0';
    setTimeout(() => {
        window.location.href = `edit_user.php?id=${userId}`;
    }, 300);
}

function disableUser(userId) {
    if (confirm('Are you sure you want to disable this account? The user will no longer be able to log in.')) {
        fetch(`disable_user.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Account disabled successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            });
    }
}

function enableUser(userId) {
    if (confirm('Are you sure you want to enable this account?')) {
        fetch(`enable_user.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Account enabled successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            });
    }
}

function messageUser(email) {
    window.location.href = `mailto:${email}`;
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>