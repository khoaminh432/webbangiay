<?php
require_once '../database/database_sever.php';
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../DTO/Pagination.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$productDao = new ProductDao();

// Lấy tham số tìm kiếm
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

// Lấy tất cả sản phẩm với thông tin đầy đủ
$products = $productDao->get_products_with_details();

// Tìm kiếm thường: chỉ theo tên
if (!empty($query) && empty($category) && empty($min_price) && empty($max_price)) {
    $products = array_filter($products, function ($product) use ($query) {
        return stripos($product->name, $query) !== false;
    });
}
// Tìm kiếm nâng cao: theo tên, phân loại, khoảng giá
else if (!empty($query) || !empty($category) || !empty($min_price) || !empty($max_price)) {
    $products = array_filter($products, function ($product) use ($query, $category, $min_price, $max_price) {
        $match = true;
        if (!empty($query)) {
            $match = $match && (stripos($product->name, $query) !== false);
        }
        if (!empty($category)) {
            $match = $match && ($product->id_type_product == $category);
        }

        if (!empty($min_price)) {
            $match = $match && ($product->price >= $min_price);
        }
        if (!empty($max_price)) {
            $match = $match && ($product->price <= $max_price);
        }
        return $match;
    });
}
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 8;
$totalItems = $productDao->countFilteredProducts($query, $category, $min_price, $max_price);
$pagination = new Pagination($totalItems, $currentPage, $perPage);
$products = $productDao->getFilteredProductsWithPagination(
    $query, 
    $category, 
    $min_price, 
    $max_price, 
    $pagination->getOffset(), 
    $pagination->getLimit()
);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php include '../layout/header.php'; ?>

    <div class="container">
        <h1>Kết quả tìm kiếm</h1>
        <div id="searchResults">
            <?php include 'search_result_items.php'; ?>

            <div class="pagination">
                <?php if ($pagination->hasPrevious()): ?>
                    <a href="?query=<?= urlencode($query) ?>&category=<?= urlencode($category) ?>&min_price=<?= urlencode($min_price) ?>&max_price=<?= urlencode($max_price) ?>&page=<?= $pagination->currentPage - 1 ?>">Trước</a>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $pagination->currentPage - 2);
                $end = min($pagination->totalPages, $pagination->currentPage + 2);    
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?query=<?= urlencode($query) ?>&category=<?= urlencode($category) ?>&min_price=<?= urlencode($min_price) ?>&max_price=<?= urlencode($max_price) ?>&page=<?= $i ?>" 
                    class="<?= $i == $pagination->currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination->hasNext()): ?>
                    <a href="?query=<?= urlencode($query) ?>&category=<?= urlencode($category) ?>&min_price=<?= urlencode($min_price) ?>&max_price=<?= urlencode($max_price) ?>&page=<?= $pagination->currentPage + 1 ?>">Sau</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>

    <script src="../js/search_ajax.js"></script>

    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px;
            transition: bottom 0.3s;
        }

        .product-image:hover .product-actions {
            bottom: 0;
        }

        .product-actions button,
        .product-actions a {
            background: none;
            border: none;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            padding: 5px 10px;
            transition: color 0.3s;
        }

        .product-actions button:hover,
        .product-actions a:hover {
            color: #4CAF50;
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            margin: 0 0 10px 0;
            font-size: 1.1em;
            height: 2.4em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-price {
            color: #e44d26;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-type {
            color: #666;
            font-size: 0.9em;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .loading {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
        }

        .error {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #f44336;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #f1f1f1;
        }
    </style>
</body>

</html>