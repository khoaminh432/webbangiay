
<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        $title = 'Home';
        $css = 'home.css';
        $js = 'home.js';
        $childView = './pages/home.php';
        break;
    case 'cart':
        $title = 'Cart';
        $css = 'cart.css';
        $js = 'cart.js';
        $childView = './pages/cart.php';
        break;
    case 'products':
        $title = 'Products';
        $css = 'products.css';
        $js = 'products.js';
        $childView = './pages/products.php';
        break;
    default:
        $title = 'Home';
        $css = 'home.css';
        $js = 'home.js';
        $childView = './pages/home.php';
        break;
    case 'checkout':
        $title = 'Checkout';
        $css = 'checkout.css';
        $js = 'checkout.js';
        $childView = './pages/checkout.php';
        break;
 
    
}

include('./layout/layout.php');
?>