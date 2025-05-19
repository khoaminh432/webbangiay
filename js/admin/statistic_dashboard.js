
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
        // Gọi lại hàm khởi tạo form khi tab được hiển thị
        if(idx === 0 && typeof initStatisticProductForm === 'function') initStatisticProductForm();
        if(idx === 1 && typeof initStatisticCustomerForm === 'function') initStatisticCustomerForm();
    }
    btns.forEach((btn, idx) => {
        btn.onclick = function() { showTab(idx); };
    });
    showTab(0); // Hiển thị tab đầu tiên khi load
}
initTabs();