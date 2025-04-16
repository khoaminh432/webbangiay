<?php
require_once __DIR__ . '/../DAO/InformationReceiveDao.php';
require_once __DIR__ . '/../DTO/InformationReceiveDTO.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user']['id'];
$infoDao = new InformationReceiveDao();

// Xử lý các thao tác CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add':
                $newAddress = new InformationReceiveDTO([
                    'address' => trim($_POST['address']),
                    'name' => trim($_POST['name']),
                    'phone' => trim($_POST['phone']),
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
                    'address' => trim($_POST['address']),
                    'name' => trim($_POST['name']),
                    'phone' => trim($_POST['phone']),
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
        
        // Hiển thị thông báo thành công
        $_SESSION['success_message'] = "Thao tác thành công!";
        header("Location: receive.php");
        exit();
        
    } catch (Exception $e) {
        $errorMessage = "Lỗi: " . $e->getMessage();
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
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-hover: #45a049;
            --danger-color: #f44336;
            --warning-color: #ffc107;
            --text-color: #333;
            --light-gray: #f5f5f5;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .address-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .default-address {
            border: 2px solid var(--primary-color);
            background-color: rgba(76, 175, 80, 0.05);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                margin: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-map-marker-alt"></i> Quản Lý Địa Chỉ Nhận Hàng</h2>
            <a href="user_site.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $errorMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Địa chỉ mặc định -->
        <div class="mb-4">
            <h4 class="mb-3"><i class="fas fa-star"></i> Địa chỉ mặc định</h4>
            <?php if ($defaultAddress): ?>
                <div class="address-card default-address">
                    <h5><?= htmlspecialchars($defaultAddress->name) ?></h5>
                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($defaultAddress->phone) ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($defaultAddress->address) ?></p>
                    <div class="action-buttons">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editAddressModal" 
                                data-id="<?= $defaultAddress->id ?>"
                                data-name="<?= htmlspecialchars($defaultAddress->name) ?>"
                                data-phone="<?= htmlspecialchars($defaultAddress->phone) ?>"
                                data-address="<?= htmlspecialchars($defaultAddress->address) ?>"
                                data-is_default="1">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle"></i> Bạn chưa có địa chỉ mặc định
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Danh sách địa chỉ khác -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-list"></i> Địa chỉ khác</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fas fa-plus"></i> Thêm địa chỉ mới
                </button>
            </div>
            
            <?php if (count($allAddresses) > 1 || (count($allAddresses) === 1 && !$defaultAddress)): ?>
                <div class="row">
                    <?php foreach ($allAddresses as $address): ?>
                        <?php if (!$address->is_default): ?>
                            <div class="col-md-6 mb-3">
                                <div class="address-card">
                                    <h5><?= htmlspecialchars($address->name) ?></h5>
                                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($address->phone) ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($address->address) ?></p>
                                    <div class="action-buttons">
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editAddressModal" 
                                                data-id="<?= $address->id ?>"
                                                data-name="<?= htmlspecialchars($address->name) ?>"
                                                data-phone="<?= htmlspecialchars($address->phone) ?>"
                                                data-address="<?= htmlspecialchars($address->address) ?>"
                                                data-is_default="0">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="action" value="set_default">
                                            <input type="hidden" name="id" value="<?= $address->id ?>">
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-check-circle"></i> Đặt mặc định
                                            </button>
                                        </form>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa địa chỉ này?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $address->id ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Bạn chưa có địa chỉ nào khác
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
                        <h5 class="modal-title"><i class="fas fa-plus"></i> Thêm địa chỉ mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" name="address" rows="3" required></textarea>
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
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Chỉnh sửa địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone" id="editPhone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" name="address" id="editAddress" rows="3" required></textarea>
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