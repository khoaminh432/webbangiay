<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tongSoLuong = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $tongSoLuong += $item['quantity'];
    }
}

require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../database/database_sever.php';
$productDao = new ProductDao();
$db = new database_sever();
$typeProducts = $db->view_table('SELECT * FROM type_product');
?>
<link rel="stylesheet" href="../css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<header class="header">
    <div class="top-header">
        <div class="container">
            <div class="logo">
                <a href="/webbangiay/index.php?page=home">
                    <img src="/webbangiay/img/zappos-logo.png" alt="Logo" class="logo-image">
                </a>
            </div>
            <div class="search-bar">
                <div class="search-toggle">
                    <button type="button" id="toggleSearch" class="toggle-btn">Tìm kiếm nâng cao</button>
                </div>
                <form action="/webbangiay/pages/search.php" method="GET" id="searchForm">
                    <div class="basic-search">
                        <input type="text" name="query" placeholder="Tìm kiếm giày, quần áo, v.v." />
                        <button type="submit"><i class="fas fa-search"></i>Tìm kiếm</button>
                    </div>
                    <div class="advanced-search" style="display: none;">
                        <div class="search-row">
                            <input type="text" name="query" placeholder="Từ khóa tìm kiếm" />
                        </div>
                        <div class="search-row">
                            <select name="category">
                                <option value="">Chọn loại sản phẩm</option>
                                <?php foreach ($typeProducts as $type): ?>
                                    <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="search-row">
                            <input type="number" name="min_price" placeholder="Giá từ" />
                            <input type="number" name="max_price" placeholder="Giá đến" />
                        </div>
                        <div class="search-row">
                            <button type="submit" class="search-btn"><i class="fas fa-search"></i>Tìm kiếm</button>
                            <button type="reset" class="reset-btn"><i class="fas fa-redo"></i>Đặt lại</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="user-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <img src="/webbangiay/img/user-icon.png" alt="User" class="user-icon">
                        <div class="dropdown-menu">
                            <a href="/webbangiay/pages/user/profile.php">Tài khoản của tôi</a>
                            <a href="/webbangiay/pages/bill.php">Đơn hàng</a>
                            <a href="/webbangiay/pages/user/logout.php">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/webbangiay/layout/login_signup.php" class="login-btn">Đăng nhập</a>
                    <a href="/webbangiay/layout/login_signup.php" class="register-btn">Đăng ký</a>
                <?php endif; ?>

                <a href="/webbangiay/index.php?page=cart" class="cart-btn" style="position:relative;">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $tongSoLuong; ?></span>
                </a>
            </div>
        </div>
    </div>
    <nav class="main-nav">
        <div class="container">
            <ul>
                <li><a href="/webbangiay/index.php?page=home">Trang chủ</a></li>
                <li><a href="/webbangiay/index.php?page=products">Sản phẩm</a></li>

            </ul>
        </div>
    </nav>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleSearch');
    const basicSearch = document.querySelector('.basic-search');
    const advancedSearch = document.querySelector('.advanced-search');
    const searchForm = document.getElementById('searchForm');
    let isAdvanced = false;

    // Xử lý chuyển đổi giữa tìm kiếm thường và nâng cao
    toggleBtn.addEventListener('click', function() {
        isAdvanced = !isAdvanced;
        basicSearch.style.display = isAdvanced ? 'none' : 'flex';
        advancedSearch.style.display = isAdvanced ? 'block' : 'none';
        toggleBtn.textContent = isAdvanced ? 'Tìm kiếm thường' : 'Tìm kiếm nâng cao';
    });

    // Xử lý submit form tìm kiếm
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Lấy giá trị từ form
        const formData = new FormData(searchForm);
        const params = new URLSearchParams();

        // Xử lý tìm kiếm thường
        if (!isAdvanced) {
            const query = formData.get('query');
            if (query && query.trim() !== '') {
                params.append('query', query.trim());
            }
        } 
        // Xử lý tìm kiếm nâng cao
        else {
            // Thêm từ khóa tìm kiếm nếu có
            const query = formData.get('query');
            if (query && query.trim() !== '') {
                params.append('query', query.trim());
            }

            // Thêm các bộ lọc nếu được chọn
            const filters = ['category', 'size', 'brand', 'color'];
            filters.forEach(filter => {
                const value = formData.get(filter);
                if (value && value !== '') {
                    params.append(filter, value);
                }
            });

            // Thêm khoảng giá nếu được nhập
            const minPrice = formData.get('min_price');
            const maxPrice = formData.get('max_price');
            if (minPrice && minPrice !== '') {
                params.append('min_price', minPrice);
            }
            if (maxPrice && maxPrice !== '') {
                params.append('max_price', maxPrice);
            }
        }

        // Chuyển hướng đến trang kết quả tìm kiếm
        window.location.href = `/webbangiay/pages/search.php?${params.toString()}`;
    });

    // Xử lý nút đặt lại trong tìm kiếm nâng cao
    const resetBtn = document.querySelector('.reset-btn');
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const inputs = advancedSearch.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'number') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        });
    });

    // Thêm xử lý validate giá
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');

    function validatePrice() {
        const minPrice = parseInt(minPriceInput.value) || 0;
        const maxPrice = parseInt(maxPriceInput.value) || 0;

        if (maxPrice > 0 && minPrice > maxPrice) {
            alert('Giá tối thiểu không được lớn hơn giá tối đa');
            minPriceInput.value = '';
            maxPriceInput.value = '';
        }
    }

    minPriceInput.addEventListener('change', validatePrice);
    maxPriceInput.addEventListener('change', validatePrice);
});
</script>