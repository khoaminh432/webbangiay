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
?>
<link rel="stylesheet" href="../css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<header class="header">
    <div class="top-header">
        <div class="container">
            <div class="logo">
                <a href="/webbangiay/index.php?page=home">
                    <img src="img/zappos-logo.png" alt="Logo" class="logo-image">
                </a>
            </div>
            <div class="search-bar">

                <form action="/webbangiay/pages/search.php" method="GET">
                    <input type="text" name="query" placeholder="Search for shoes, clothes, etc." />
                    <button type="submit"><i class="fas fa-search"></i>Tìm kiếm</button>
                </form>
            </div>
            <div class="user-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <img src="/webbangiay/img/user-icon.png" alt="User" class="user-icon">
                        <div class="dropdown-menu">
                            <a href="/webbangiay/pages/user/profile.php">Tài khoản của tôi</a>
                            <a href="/webbangiay/pages/orders.php">Đơn hàng</a>
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