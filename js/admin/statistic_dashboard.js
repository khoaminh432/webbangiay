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

// Hàm khởi tạo form thống kê sản phẩm (nếu có)
function initStatisticProductForm() {
    const form = document.getElementById('statistic-product-form');
    const resultContainer = document.getElementById('statistic-product-result');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);

        resultContainer.innerHTML = '<p style="text-align:center;color:#ccc;">Đang tải dữ liệu...</p>';

        fetch('admin/statistic_infor/Statisticfromproduct.php?' + new URLSearchParams(formData).toString(), {
            method: 'GET'
        })
        .then(res => res.text())
        .then(html => {
            resultContainer.innerHTML = html;
            // Vẽ lại biểu đồ nếu có script trong kết quả
            const scripts = resultContainer.querySelectorAll('script');
            scripts.forEach(scr => eval(scr.innerText));
        })
        .catch(err => {
            resultContainer.innerHTML = '<p style="color:red;">Lỗi khi tải dữ liệu.</p>';
            console.error(err);
        });
    });
}
// Hàm khởi tạo form thống kê khách hàng (nếu có)
function initStatisticCustomerForm() {
    var form = document.getElementById('statistic-customer-form');
    if(form) {
        form.onsubmit = function(e) {
            e.preventDefault();
            var formData = new FormData(form);
            var params = new URLSearchParams(formData).toString();
            fetch('admin/statistic_infor/statisticfromcustomer.php?' + params)
                .then(res => res.text())
                .then(html => {
                    var temp = document.createElement('div');
                    temp.innerHTML = html;
                    var result = temp.querySelector('#statistic-customer-result');
                    document.getElementById('statistic-customer-result').innerHTML = result ? result.innerHTML : '';
                });
        };
    }
}

// Gọi lại khi trang load hoặc khi nội dung được reload động
document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    initStatisticProductForm();
    initStatisticCustomerForm();
});