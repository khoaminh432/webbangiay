<?php
require_once __DIR__ . "../../dao/ProductDao.php";
session_start();
$_SESSION["cart"] = [4,2,1,3];
// Security check - verify user is logged in
// if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
//     header("Location: login.php");
//     exit();
// }
$cartid = $_SESSION["cart"];
if (count($cartid) > 0) {
    $table_products = new ProductDao();
    $products = $table_products->get_by_ids($cartid);
}
?>
<link rel="stylesheet" href="cart.css">
<?php
if (!empty($products)) {
    ?>
    <form id="checkoutForm" action="checkout.php" method="post">
    <table class="cart-table" border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) { ?>
            <tr data-id="<?php echo $product->id; ?>">
                <td>
                    <img src="<?php echo $product->image_url; ?>" alt="<?php echo $product->name; ?>" style="width:80px; height:auto;">
                </td>
                <td><?php echo $product->name; ?></td>
                <td><?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?></td>
                <td>
                    <button type="button" class="qty-btn" onclick="changeQty(<?php echo $product->id; ?>, -1)">-</button>
                    <input
                        type="number"
                        name="quantity[<?php echo $product->id; ?>]"
                        value="1"
                        min="1"
                        style="width:50px; text-align:center;"
                        id="qty-<?php echo $product->id; ?>"
                        data-price="<?php echo $product->price; ?>"
                        onchange="updateTotal()"
                    >
                    <button type="button" class="qty-btn" onclick="changeQty(<?php echo $product->id; ?>, 1)">+</button>
                </td>
                <td>
                    <span id="subtotal-<?php echo $product->id; ?>">
                        <?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?>
                    </span>
                </td>
                <td>
                    <button class="remove-item" data-id="<?php echo $product->id; ?>">Remove</button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Tổng tiền:</strong></td>
                <td colspan="2"><span id="totalAmount"></span></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:right;">
                    <input type="hidden" name="total" id="totalInput" value="">
                    <button type="submit" id="checkoutBtn">Thanh toán</button>
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
    <?php
}
?>
<script src="../js/cart.js"></script>