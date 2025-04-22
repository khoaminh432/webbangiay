<?php
require_once __DIR__ . "/../../../../dao/UserDao.php";
// Kiểm tra xem ID có tồn tại không
if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID người dùng!</p>");
}

$userId = (int)$_GET['id']; // Ép kiểu để tránh SQL injection
$user = $table_users->get_by_id($userId);

?>
<link rel="stylesheet" href="css/admin_style/form/view/viewformuser.css">
<div class="user-view-model">
<div class="user-view-card">
        <div class="card-header">
            <h2 class="card-title">User Profile</h2>
            <button class="close-btn" onclick="closeObjectView()">
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
<script src="js/admin/closeview_form.js"></script>