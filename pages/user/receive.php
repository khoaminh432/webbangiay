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
        // Xử lý dữ liệu đầu vào chung
        $addressParts = array_filter([
            trim(htmlspecialchars($_POST['address_detail'] ?? '')),
            trim(htmlspecialchars($_POST['ward_name'] ?? '')),
            trim(htmlspecialchars($_POST['district_name'] ?? '')),
            trim(htmlspecialchars($_POST['province_name'] ?? ''))
        ]);
        $fullAddress = implode(', ', $addressParts);
        
        $data = [
            'address' => $fullAddress,
            'name' => trim(htmlspecialchars($_POST['name'] ?? '')),
            'phone' => trim(htmlspecialchars($_POST['phone'] ?? '')),
            'id_user' => $userId,
            'is_default' => isset($_POST['is_default'])
        ];
        
        switch ($action) {
            case 'add':
                $newAddress = new InformationReceiveDTO($data);
                if ($newAddress->is_default) {
                    $infoDao->reset_default_addresses($userId);
                }
                $infoDao->insert($newAddress);
                break;
                
            case 'update':
                $data['id'] = $_POST['id'];
                $updatedAddress = new InformationReceiveDTO($data);
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
$otherAddresses = array_filter($allAddresses, fn($addr) => !$addr->is_default && (!$defaultAddress || $addr->id != $defaultAddress->id));
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
    
    <div class="address-container container py-4">
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
        
        <div class="text-end mb-4">
            <button class="btn btn-primary" id="addAddressBtn">
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
                        <span class="badge bg-primary">Mặc định</span>
                    </div>
                    <p><i class="fas fa-phone me-2"></i><?= htmlspecialchars($defaultAddress->phone) ?></p>
                    <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($defaultAddress->address) ?></p>
                    <div class="address-actions">
                        <button class="btn btn-sm btn-outline-primary edit-address-btn"
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
                                    <button class="btn btn-sm btn-outline-primary edit-address-btn"
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

    <!-- Modal Địa Chỉ -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" id="addressForm">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span id="modalTitle">Thêm địa chỉ mới</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="editId">
                        <input type="hidden" name="province_name" id="provinceName">
                        <input type="hidden" name="district_name" id="districtName">
                        <input type="hidden" name="ward_name" id="wardName">

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
                                <select class="form-select" id="provinceSelect" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select" id="districtSelect" required disabled>
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select" id="wardSelect" required disabled>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address_detail" id="addressDetail" rows="2" required maxlength="255"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ đầy đủ</label>
                            <div class="form-control" id="fullAddressPreview" style="min-height: 50px; background-color: #f8f9fa;"></div>
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

    <?php require_once __DIR__."/../../layout/footer.php"?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addressModal = new bootstrap.Modal(document.getElementById('addressModal'));
        const provinceSelect = document.getElementById('provinceSelect');
        const districtSelect = document.getElementById('districtSelect');
        const wardSelect = document.getElementById('wardSelect');
        
        // Load tỉnh/thành phố
        fetch('https://provinces.open-api.vn/api/p/')
            .then(res => res.json())
            .then(provinces => {
                provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                provinces.forEach(province => {
                    const option = new Option(province.name, province.code);
                    provinceSelect.add(option);
                });
            });
        
        // Xử lý khi chọn tỉnh
        provinceSelect.addEventListener('change', function() {
            const provinceCode = this.value;
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            districtSelect.disabled = !provinceCode;
            wardSelect.disabled = true;
            
            if (provinceCode) {
                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                    .then(res => res.json())
                    .then(province => {
                        province.districts.forEach(district => {
                            const option = new Option(district.name, district.code);
                            districtSelect.add(option);
                        });
                        districtSelect.disabled = false;
                        updateAddressPreview();
                    });
            }
        });
        
        // Xử lý khi chọn quận/huyện
        districtSelect.addEventListener('change', function() {
            const districtCode = this.value;
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = !districtCode;
            
            if (districtCode) {
                fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                    .then(res => res.json())
                    .then(district => {
                        district.wards.forEach(ward => {
                            const option = new Option(ward.name, ward.code);
                            wardSelect.add(option);
                        });
                        wardSelect.disabled = false;
                        updateAddressPreview();
                    });
            }
        });
        
        // Cập nhật preview địa chỉ
        function updateAddressPreview() {
            const province = provinceSelect.options[provinceSelect.selectedIndex]?.text;
            const district = districtSelect.options[districtSelect.selectedIndex]?.text;
            const ward = wardSelect.options[wardSelect.selectedIndex]?.text;
            const detail = document.getElementById('addressDetail').value.trim();
            
            document.getElementById('provinceName').value = province || '';
            document.getElementById('districtName').value = district || '';
            document.getElementById('wardName').value = ward || '';
            
            const addressParts = [];
            if (detail) addressParts.push(detail);
            if (ward && ward !== "Chọn phường/xã") addressParts.push(ward);
            if (district && district !== "Chọn quận/huyện") addressParts.push(district);
            if (province && province !== "Chọn tỉnh/thành phố") addressParts.push(province);
            
            document.getElementById('fullAddressPreview').textContent = addressParts.join(', ');
        }
        
        // Theo dõi thay đổi để cập nhật preview
        wardSelect.addEventListener('change', updateAddressPreview);
        document.getElementById('addressDetail').addEventListener('input', updateAddressPreview);
        
        // Mở modal thêm mới
        document.getElementById('addAddressBtn').addEventListener('click', function() {
            document.getElementById('formAction').value = 'add';
            document.getElementById('modalTitle').textContent = 'Thêm địa chỉ mới';
            document.getElementById('submitBtn').textContent = 'Thêm địa chỉ';
            document.getElementById('addressForm').reset();
            document.getElementById('editId').value = '';
            document.getElementById('fullAddressPreview').textContent = '';
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            addressModal.show();
        });
        
        // Mở modal chỉnh sửa
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-address-btn')) {
                const btn = e.target.closest('.edit-address-btn');
                
                document.getElementById('formAction').value = 'update';
                document.getElementById('modalTitle').textContent = 'Chỉnh sửa địa chỉ';
                document.getElementById('submitBtn').textContent = 'Lưu thay đổi';
                document.getElementById('editId').value = btn.dataset.id;
                document.getElementById('editName').value = btn.dataset.name;
                document.getElementById('editPhone').value = btn.dataset.phone;
                document.getElementById('isDefault').checked = btn.dataset.is_default === '1';
                
                // Phân tích địa chỉ
                const addressParts = btn.dataset.address.split(', ');
                if (addressParts.length >= 4) {
                    document.getElementById('addressDetail').value = addressParts[0];
                    
                    // Tìm và chọn tỉnh
                    const provinceName = addressParts[3];
                    for (let i = 0; i < provinceSelect.options.length; i++) {
                        if (provinceSelect.options[i].text === provinceName) {
                            provinceSelect.value = provinceSelect.options[i].value;
                            provinceSelect.dispatchEvent(new Event('change'));
                            break;
                        }
                    }
                    
                    // Sau khi load quận/huyện xong, tìm và chọn
                    setTimeout(() => {
                        const districtName = addressParts[2];
                        for (let i = 0; i < districtSelect.options.length; i++) {
                            if (districtSelect.options[i].text === districtName) {
                                districtSelect.value = districtSelect.options[i].value;
                                districtSelect.dispatchEvent(new Event('change'));
                                break;
                            }
                        }
                        
                        // Sau khi load phường/xã xong, tìm và chọn
                        setTimeout(() => {
                            const wardName = addressParts[1];
                            for (let i = 0; i < wardSelect.options.length; i++) {
                                if (wardSelect.options[i].text === wardName) {
                                    wardSelect.value = wardSelect.options[i].value;
                                    updateAddressPreview();
                                    break;
                                }
                            }
                        }, 500);
                    }, 500);
                }
                
                addressModal.show();
            }
        });
        
        // Tự động đóng thông báo sau 5 giây
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                bootstrap.Alert.getInstance(alert)?.close();
            });
        }, 5000);
    });
    </script>
</body>
</html>