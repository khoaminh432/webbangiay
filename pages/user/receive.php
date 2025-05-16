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
    $addressParts = [
        trim(htmlspecialchars($_POST['address_detail'])),
        trim(htmlspecialchars($_POST['ward'])),
        trim(htmlspecialchars($_POST['district'])),
        trim(htmlspecialchars($_POST['province']))
    ];
    $fullAddress = implode(', ', array_filter($addressParts));
    
    $newAddress = new InformationReceiveDTO([
        'address' => $fullAddress,
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
    $addressParts = [
        trim(htmlspecialchars($_POST['address_detail'])),
        trim(htmlspecialchars($_POST['ward'])),
        trim(htmlspecialchars($_POST['district'])),
        trim(htmlspecialchars($_POST['province']))
    ];
    $fullAddress = implode(', ', array_filter($addressParts));
    
    $updatedAddress = new InformationReceiveDTO([
        'id' => $_POST['id'],
        'address' => $fullAddress,
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
    <link rel="stylesheet" href="../../css/receive.css">
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
    <!-- Thay đổi modal thêm/sửa địa chỉ -->
        <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-map-marker-alt me-2"></i><span id="modalTitle">Thêm địa chỉ mới</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="action" id="formAction" value="add">
                            <input type="hidden" name="id" id="editId">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="editName" required maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" id="editPhone" required pattern="[0-9]{10,11}">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-select" name="province" id="provinceSelect" required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        <option value="Bình Dương">Bình Dương</option>
                                        <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                                        <option value="Hà Nội">Hà Nội</option>
                                        <!-- Thêm các tỉnh thành khác -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select class="form-select" name="district" id="districtSelect" required>
                                        <option value="">Chọn quận/huyện</option>
                                        <!-- Sẽ được cập nhật động -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                    <select class="form-select" name="ward" id="wardSelect" required>
                                        <option value="">Chọn phường/xã</option>
                                        <!-- Sẽ được cập nhật động -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address_detail" id="addressDetail" rows="2" required maxlength="255" placeholder="Số nhà, tên đường, tòa nhà..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ đầy đủ</label>
                                <div class="form-control" id="fullAddressPreview" style="min-height: 50px; background-color: #f8f9fa;">
                                    <!-- Hiển thị preview địa chỉ -->
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_default" id="isDefault">
                                <label class="form-check-label" for="isDefault">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Thêm địa chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require_once __DIR__."/../../layout/footer.php"?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
    // Lấy danh sách tỉnh/thành phố khi trang tải
    document.addEventListener('DOMContentLoaded', function() {
        fetch('https://provinces.open-api.vn/api/p/')
            .then(res => res.json())
            .then(data => {
                const provinceSelect = document.getElementById('provinceSelect');
                provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            });
    });

    // Khi chọn tỉnh/thành phố, lấy quận/huyện
    document.getElementById('provinceSelect').addEventListener('change', function() {
        const provinceCode = this.value;
        const districtSelect = document.getElementById('districtSelect');
        const wardSelect = document.getElementById('wardSelect');
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        if (provinceCode) {
            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    data.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                });
        }
        updateAddressPreview();
    });
    //sử dụng api provinces.open-api.vn để lấy danh sách quận huyện
    // Khi chọn quận/huyện, lấy phường/xã
    document.getElementById('districtSelect').addEventListener('change', function() {
        const districtCode = this.value;
        const wardSelect = document.getElementById('wardSelect');
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        if (districtCode) {
            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    data.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                });
        }
        updateAddressPreview();
    });

    // Cập nhật preview địa chỉ
    function updateAddressPreview() {
        const provinceSelect = document.getElementById('provinceSelect');
        const districtSelect = document.getElementById('districtSelect');
        const wardSelect = document.getElementById('wardSelect');
        const detail = document.getElementById('addressDetail').value;

        const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
        const district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
        const ward = wardSelect.options[wardSelect.selectedIndex]?.text || '';

        let fullAddress = [];
        if (detail) fullAddress.push(detail);
        if (ward) fullAddress.push(ward);
        if (district) fullAddress.push(district);
        if (province) fullAddress.push(province);

        document.getElementById('fullAddressPreview').textContent = fullAddress.join(', ');
    }

    document.getElementById('wardSelect').addEventListener('change', updateAddressPreview);
    document.getElementById('addressDetail').addEventListener('input', updateAddressPreview);


        // Xử lý modal
        const addressModal = document.getElementById('addressModal');
        
        // Mở modal thêm mới
        document.querySelector('[data-bs-target="#addAddressModal"]').addEventListener('click', function() {
            document.getElementById('formAction').value = 'add';
            document.getElementById('modalTitle').textContent = 'Thêm địa chỉ mới';
            document.getElementById('submitBtn').textContent = 'Thêm địa chỉ';
            
            // Reset form
            document.getElementById('editId').value = '';
            document.getElementById('editName').value = '';
            document.getElementById('editPhone').value = '';
            document.getElementById('provinceSelect').value = '';
            document.getElementById('districtSelect').innerHTML = '<option value="">Chọn quận/huyện</option>';
            document.getElementById('wardSelect').innerHTML = '<option value="">Chọn phường/xã</option>';
            document.getElementById('addressDetail').value = '';
            document.getElementById('isDefault').checked = false;
            document.getElementById('fullAddressPreview').textContent = '';
            
            // Đổi target của button để mở modal mới
            this.setAttribute('data-bs-target', '#addressModal');
        });

        // Mở modal chỉnh sửa
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-bs-target="#editAddressModal"]')) {
                const button = e.target.closest('[data-bs-target="#editAddressModal"]');
                
                document.getElementById('formAction').value = 'update';
                document.getElementById('modalTitle').textContent = 'Chỉnh sửa địa chỉ';
                document.getElementById('submitBtn').textContent = 'Lưu thay đổi';
                
                // Lấy dữ liệu từ button
                document.getElementById('editId').value = button.getAttribute('data-id');
                document.getElementById('editName').value = button.getAttribute('data-name');
                document.getElementById('editPhone').value = button.getAttribute('data-phone');
                document.getElementById('isDefault').checked = button.getAttribute('data-is_default') === '1';
                
                // Phân tích địa chỉ (giả sử địa chỉ lưu dạng "Địa chỉ cụ thể, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố")
                const fullAddress = button.getAttribute('data-address').split(', ');
                if (fullAddress.length >= 4) {
                    document.getElementById('addressDetail').value = fullAddress[0];
                    document.getElementById('wardSelect').innerHTML = `<option value="${fullAddress[1]}" selected>${fullAddress[1]}</option>`;
                    document.getElementById('districtSelect').innerHTML = `<option value="${fullAddress[2]}" selected>${fullAddress[2]}</option>`;
                    document.getElementById('provinceSelect').value = fullAddress[3];
                }
                
                updateAddressPreview();
                
                // Đổi target của button để mở modal mới
                button.setAttribute('data-bs-target', '#addressModal');
            }
        });

        // Tự động đóng thông báo sau 5 giây (giữ nguyên)
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
   
    </script>
</body>
</html>