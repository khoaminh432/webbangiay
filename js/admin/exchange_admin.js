// Khởi tạo cache cho các view
const adminViewCache = {};

// Hàm reload nội dung vào #admin-content và cache lại
function reloadAdminContent(html, view) {
    document.getElementById('admin-content').innerHTML = html;
    // Gọi lại các hàm khởi tạo nếu có
    if (typeof initOrderDetailModal === 'function') initOrderDetailModal();
    if (typeof initTabs === 'function') initTabs();
    if (typeof initStatisticProductForm === 'function') initStatisticProductForm();
    if (typeof initStatisticCustomerForm === 'function') initStatisticCustomerForm();
    // Lưu cache
    if (view) adminViewCache[view] = html;
}

// Gán sự kiện cho các nút menu
document.querySelectorAll('.menu-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        var view = this.getAttribute('data-view');
        if (view ==="permission_form.php") {
            // Kiểm tra quyền qua API
            fetch('handle/admin/checkAdmin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ permission: 'admin' }) // 'admin' là mã quyền đặc biệt cho admin, bạn có thể thay đổi nếu hệ thống dùng số
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire('Lỗi', data.message || 'Chỉ admin mới được truy cập chức năng này!', 'error');
                    return;
                }
                document.querySelectorAll('.menu-btn').forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        // Nếu đã cache thì chỉ lấy ra
        if (adminViewCache[view]) {
            reloadAdminContent(adminViewCache[view], view);
            history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
            return;
        }
        // Nếu chưa cache thì fetch
        document.getElementById('admin-content').innerHTML = '<div style="padding:40px;text-align:center;color:#7ecbff;">Đang tải...</div>';
        fetch('admin/' + view)
            .then(res => res.text())
            .then(html => {
                reloadAdminContent(html, view);
                history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
            });
            })
            .catch(() => {
                Swal.fire('Lỗi', 'Không thể kiểm tra quyền.', 'error');
            });
            return;
        }
        else{
        document.querySelectorAll('.menu-btn').forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        // Nếu đã cache thì chỉ lấy ra
        if (adminViewCache[view]) {
            reloadAdminContent(adminViewCache[view], view);
            history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
            return;
        }
        // Nếu chưa cache thì fetch
        document.getElementById('admin-content').innerHTML = '<div style="padding:40px;text-align:center;color:#7ecbff;">Đang tải...</div>';
        fetch('admin/' + view)
            .then(res => res.text())
            .then(html => {
                reloadAdminContent(html, view);
                history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
            });}
    });
});

// Xử lý back/forward trình duyệt
window.addEventListener('popstate', function(event) {
    var view = (event.state && event.state.view) ? event.state.view : 'dashboard.php';
    if (adminViewCache[view]) {
        reloadAdminContent(adminViewCache[view], view);
        return;
    }
    document.getElementById('admin-content').innerHTML = '<div style="padding:40px;text-align:center;color:#7ecbff;">Đang tải...</div>';
    fetch('admin/' + view)
        .then(res => res.text())
        .then(html => {
            reloadAdminContent(html, view);
        });
});

// Đăng xuất
document.getElementById('btnLogout').addEventListener('click', function(){
    window.location.href = 'logout.php';
});

// Sau khi load nội dung vào #admin-content:
function reloadAdminContent(html, view) {
    document.getElementById('admin-content').innerHTML = html;
    // Gọi lại các hàm khởi tạo tab và form thống kê
    if (typeof initTabs === 'function') initTabs();
    if (typeof initStatisticProductForm === 'function') initStatisticProductForm();
    if (typeof initStatisticCustomerForm === 'function') initStatisticCustomerForm();
    if (typeof initOrderDetailModal === 'function') initOrderDetailModal();
}
document.querySelectorAll('.menu-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        var view = this.getAttribute('data-view');
        // ...các dòng khác...
        fetch('admin/' + view)
            .then(res => res.text())
            .then(html => {
                reloadAdminContent(html, view);
                history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
            });
    });
});