<?php
require_once __DIR__ . '/../../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../../DTO/InformationReceiveDTO.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: /webbangiay/layout/login_signup.php");
    exit();
}

$userId = $_SESSION['user_id'];
$infoDao = new InformationReceiveDao();

// Xử lý các thao tác CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add':
                $newAddress = new InformationReceiveDTO([
                    'address' => trim(htmlspecialchars($_POST['address'])),
                    'name' => trim(htmlspecialchars($_POST['name'])),
                    'phone' => trim(htmlspecialchars($_POST['phone'])),
                    'id_user' => $userId,
                    'is_default' => isset($_POST['is_default'])
                ]);
                
                if ($newAddress->is_default) {
                    $infoDao->reset_default_addresses($userId);
                }
                
                $infoDao->insert($newAddress);
                break;
                
            case 'update':
                $updatedAddress = new InformationReceiveDTO([
                    'id' => $_POST['id'],
                    'address' => trim(htmlspecialchars($_POST['address'])),
                    'name' => trim(htmlspecialchars($_POST['name'])),
                    'phone' => trim(htmlspecialchars($_POST['phone'])),
                    'id_user' => $userId,
                    'is_default' => isset($_POST['is_default'])
                ]);
                
                if ($updatedAddress->is_default) {
                    $infoDao->reset_default_addresses($userId);
                }
                
                $infoDao->update($updatedAddress);
                break;
                
            case 'delete':
                $infoDao->delete($_POST['id']);
                break;
                
            case 'set_default':
                $infoDao->reset_default_addresses($userId);
                $infoDao->set_default_address($_POST['id'], $userId);
                break;
        }
        
        $_SESSION['success_message'] = "Thao tác thành công!";
        header("Location: /webbangiay/pages/user/receive.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Lỗi: " . $e->getMessage();
        header("Location: /webbangiay/pages/user/receive.php");
        exit();
    }
}

// Lấy danh sách địa chỉ
$defaultAddress = $infoDao->get_default_address($userId);
$allAddresses = $infoDao->get_by_user($userId);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Địa Chỉ Nhận Hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <style>
/* CSS chỉnh sửa cho giao diện thẳng hàng và đẹp hơn */
.address-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 25px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

/* Header section */
.address-header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.address-header-section h2 {
    font-size: 1.6rem;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.address-header-section h2 i {
    margin-right: 10px;
    color: #3498db;
}

/* Button styles */
.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
}

.btn-outline-secondary {
    border: 1px solid #95a5a6;
    color: #7f8c8d;
}

.btn-outline-secondary:hover {
    background-color: #ecf0f1;
}

.btn-primary {
    background-color: #3498db;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

/* Address sections */
.section-title {
    font-size: 1.3rem;
    color: #34495e;
    margin-bottom: 20px;
    padding-bottom: 8px;
    border-bottom: 1px solid #ecf0f1;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 10px;
    color: #f39c12;
}

/* Address card */
.address-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
    transition: all 0.3s ease;
}

.address-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.default-address {
    border-left: 4px solid #2ecc71;
    background-color: #f8fff9;
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.address-header h5 {
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    font-size: 1.1rem;
}

.badge-default {
    background-color: #2ecc71;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.address-card p {
    margin-bottom: 8px;
    color: #7f8c8d;
    display: flex;
    align-items: center;
}

.address-card p i {
    margin-right: 10px;
    width: 18px;
    text-align: center;
    color: #95a5a6;
}

/* Action buttons */
.address-actions {
    margin-top: 18px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-outline-primary {
    color: #3498db;
    border-color: #3498db;
}

.btn-outline-primary:hover {
    background-color: #3498db;
    color: white;
}

.btn-outline-success {
    color: #2ecc71;
    border-color: #2ecc71;
}

.btn-outline-success:hover {
    background-color: #2ecc71;
    color: white;
}

.btn-outline-danger {
    color: #e74c3c;
    border-color: #e74c3c;
}

.btn-outline-danger:hover {
    background-color: #e74c3c;
    color: white;
}

/* Alert messages */
.alert {
    border-radius: 6px;
    padding: 12px 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.alert i {
    margin-right: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .address-container {
        padding: 15px;
    }
    
    .address-header-section {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .address-actions {
        flex-direction: column;
    }
    
    .address-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Modal styling */
.modal-header {
    border-bottom: 1px solid #ecf0f1;
    padding: 15px 20px;
}

.modal-title {
    font-weight: 600;
    display: flex;
    align-items: center;
}

.modal-title i {
    margin-right: 10px;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: 1px solid #ecf0f1;
    padding: 15px 20px;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
}

.form-control {
    border-radius: 6px;
    padding: 10px;
    border: 1px solid #bdc3c7;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.form-check-input:checked {
    background-color: #3498db;
    border-color: #3498db;
}
</style>
</head>
<body>
    <?php require_once __DIR__."/../../layout/header.php"?>
    <?php require_once __DIR__."/../../layout/topmenu.php"?>
    <?php require_once __DIR__."/../../layout/mainmenu.php"?>
    
    <div class="address-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-map-marker-alt me-2"></i>Quản lý địa chỉ nhận hàng</h2>
            <a href="/webbangiay/pages/user/profile.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <!-- Thêm địa chỉ mới -->
        <div class="text-end mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                <i class="fas fa-plus me-1"></i>Thêm địa chỉ mới
            </button>
        </div>
        
        <!-- Địa chỉ mặc định -->
        <div class="mb-4">
            <h4 class="mb-3"><i class="fas fa-star me-2"></i>Địa chỉ mặc định</h4>
            <?php if ($defaultAddress): ?>
                <div class="address-card default-address">
                    <div class="address-header">
                        <h5><?= htmlspecialchars($defaultAddress->name) ?></h5>
                        <span class="badge badge-default">Mặc định</span>
                    </div>
                    <p><i class="fas fa-phone me-2"></i><?= htmlspecialchars($defaultAddress->phone) ?></p>
                    <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($defaultAddress->address) ?></p>
                    <div class="address-actions">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAddressModal" 
                                data-id="<?= $defaultAddress->id ?>"
                                data-name="<?= htmlspecialchars($defaultAddress->name) ?>"
                                data-phone="<?= htmlspecialchars($defaultAddress->phone) ?>"
                                data-address="<?= htmlspecialchars($defaultAddress->address) ?>"
                                data-is_default="1">
                            <i class="fas fa-edit me-1"></i>Sửa
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>Bạn chưa có địa chỉ mặc định
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Danh sách địa chỉ khác -->
        <div class="mb-4">
            <h4 class="mb-3"><i class="fas fa-list me-2"></i>Địa chỉ khác</h4>
            
            <?php 
            $otherAddresses = array_filter($allAddresses, function($addr) use ($defaultAddress) {
                return !$addr->is_default && (!$defaultAddress || $addr->id != $defaultAddress->id);
            });
            ?>
            
            <?php if (!empty($otherAddresses)): ?>
                <div class="row">
                    <?php foreach ($otherAddresses as $address): ?>
                        <div class="col-md-6 mb-3">
                            <div class="address-card">
                                <div class="address-header">
                                    <h5><?= htmlspecialchars($address->name) ?></h5>
                                </div>
                                <p><i class="fas fa-phone me-2"></i><?= htmlspecialchars($address->phone) ?></p>
                                <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($address->address) ?></p>
                                <div class="address-actions">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAddressModal" 
                                            data-id="<?= $address->id ?>"
                                            data-name="<?= htmlspecialchars($address->name) ?>"
                                            data-phone="<?= htmlspecialchars($address->phone) ?>"
                                            data-address="<?= htmlspecialchars($address->address) ?>"
                                            data-is_default="0">
                                        <i class="fas fa-edit me-1"></i>Sửa
                                    </button>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="set_default">
                                        <input type="hidden" name="id" value="<?= $address->id ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check-circle me-1"></i>Đặt mặc định
                                        </button>
                                    </form>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa địa chỉ này?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $address->id ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt me-1"></i>Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Bạn chưa có địa chỉ nào khác
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Thêm Địa Chỉ Mới -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Thêm địa chỉ mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required maxlength="100">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="phone" required pattern="[0-9]{10,11}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" rows="3" required maxlength="255"></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_default" id="addIsDefault">
                            <label class="form-check-label" for="addIsDefault">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh Sửa Địa Chỉ -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Chỉnh sửa địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="editName" required maxlength="100">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="phone" id="editPhone" required pattern="[0-9]{10,11}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" id="editAddress" rows="3" required maxlength="255"></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_default" id="editIsDefault">
                            <label class="form-check-label" for="editIsDefault">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once __DIR__."/../../layout/footer.php"?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Xử lý modal chỉnh sửa
        document.getElementById('editAddressModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const modal = this;
            
            modal.querySelector('#editId').value = button.getAttribute('data-id');
            modal.querySelector('#editName').value = button.getAttribute('data-name');
            modal.querySelector('#editPhone').value = button.getAttribute('data-phone');
            modal.querySelector('#editAddress').value = button.getAttribute('data-address');
            modal.querySelector('#editIsDefault').checked = button.getAttribute('data-is_default') === '1';
        });
        
        // Tự động đóng thông báo sau 5 giây
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
    </script>
</body>
</html>