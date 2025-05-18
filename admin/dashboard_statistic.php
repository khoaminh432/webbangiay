
<div class="tab-container">
    <div class="tab-header">
        <button type="button" class="tab-btn">Doanh thu theo sản phẩm</button>
        <button type="button" class="tab-btn">Top 5 khách hàng</button>
    </div>
    <div class="tab-content">
        <?php require_once __DIR__.'/statistic_infor/Statisticfromproduct.php'; ?>
    </div>
    <div class="tab-content">
        <?php require_once __DIR__.'/statistic_infor/statisticfromcustomer.php'; ?>
    </div>
</div>
<script>
function initTabs() {
    var container = document.querySelector('.tab-container');
    if (!container) return;
    var btns = container.querySelectorAll('.tab-header button');
    var tabs = container.querySelectorAll('.tab-content');
    function showTab(idx) {
        tabs.forEach((tab, i) => {
            tab.classList.toggle('active', i === idx);
            btns[i].classList.toggle('active', i === idx);
        });
    }
    btns.forEach((btn, idx) => {
        btn.onclick = function() { showTab(idx); };
    });
    showTab(0);
}
initTabs();
</script>
<link rel="stylesheet" href="css/admin_style/statistic/style.css">