
<link rel="stylesheet" href="css/admin_style/statistic/style.css">

<div class="tab-container">
    <div class="tab-header">
        <button type="button" class="tab-btn">Doanh thu theo sản phẩm</button>
        <button type="button" class="tab-btn">Top 5 khách hàng</button>
    </div>
    <div class="tab-content">
        <?php require_once __DIR__."/statistic_infor/Statisticfromproduct.php"; ?>
    </div>
    <div class="tab-content">
        <?php require_once __DIR__."/statistic_infor/statisticfromcustomer.php"; ?>
    </div>
</div>
<script>function reloadAdminContent(html) {
    document.getElementById('admin-content').innerHTML = html;
    if (typeof initTabs === 'function') initTabs();
    if (typeof initStatisticProductForm === 'function') initStatisticProductForm();
}
</script>