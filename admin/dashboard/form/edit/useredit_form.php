<?php require_once __DIR__ . "/../../../../initAdmin.php";
require_once __DIR__ . "/../../../../dao/UserDao.php";

if (!isset($_GET['id'])) {
    die("<p class='error'>Thiếu ID người dùng!</p>");
}

$userId = (int)$_GET['id'];
$user = $table_users->get_by_id($userId);
?>

<link rel="stylesheet" href="css/admin_style/form/edit/editformuser_style.css">
<div class="user-edit-model">
    <form id="userEditForm" class="object-edit-card">
        <input type="hidden" name="id" value="<?= $user->id ?>">
        <input type="hidden" name="object" value="user">
        <div class="card-header">
            <h2 class="card-title">Edit User</h2>
            <div class="action-buttons">
                <button type="button" class="cancel-btn" onclick="closeObjectView()">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </div>

        <div class="card-body">
            <div class="user-edit-display row">
                <div class="avatar-container">
                    <div class="avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username) ?>&background=<?= dechex(crc32($user->id) % 0xFFFFFF) ?>&color=fff&size=200" 
                             alt="User Avatar" class="user-avatar">
                    </div>
                </div>

                <div class="user-edit-details column">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->username) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="LOCK" <?= $user->status === 'LOCK' ? 'selected' : '' ?>>LOCK</option>
                            <option value="UNCLOCK" <?= $user->status === 'UNLOCK' ? 'selected' : '' ?>>UNLOCK</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">New Password (optional)</label>
                        <input type="password" id="password" name="password" placeholder="Leave blank to keep current">
                    </div>
                    
                    <div class="form-group">
                        <label for="created_at">Created At</label>
                        <input type="text" id="created_at" value="<?= date('Y-m-d H:i:s', strtotime($user->created_at)) ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="updated_at">Last Updated</label>
                        <input type="text" id="updated_at" value="<?= $user->updated_at ? date('Y-m-d H:i:s', strtotime($user->updated_at)) : 'Never' ?>" disabled>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="js/admin/closeview_form.js"></script>
<script src="js/admin/Edit_form.js"></script>
