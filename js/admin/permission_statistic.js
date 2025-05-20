document.addEventListener('DOMContentLoaded', function() {
    // ...các code khác...

    // Phân quyền cho nút "Phân quyền"
    document.querySelector('.permission.menu-btn').addEventListener('click', function(e) {
        // Kiểm tra quyền qua API
        fetch('handle/admin/checkAdmin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ permission: 'admin' }) // 'admin' là mã quyền đặc biệt cho admin, bạn có thể thay đổi nếu hệ thống dùng số
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                e.preventDefault();
                Swal.fire('Lỗi', data.message || 'Chỉ admin mới được truy cập chức năng này!', 'error');
            } else {
                // Nếu có quyền, cho phép chuyển trang như bình thường
                // Nếu bạn dùng AJAX để load tab, hãy gọi hàm load tab ở đây
                loadAdminView('permission_form.php', this);
            }
        })
        .catch(() => {
            e.preventDefault();
            Swal.fire('Lỗi', 'Không thể kiểm tra quyền.', 'error');
        });
    });
});

function loadAdminView(view, btn) {
    document.querySelectorAll('.menu-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('admin-content').innerHTML = '<div style="padding:40px;text-align:center;color:#7ecbff;">Đang tải...</div>';
    fetch('admin/' + view)
        .then(res => res.text())
        .then(html => {
            reloadAdminContent(html, view);
            history.pushState({ view: view }, '', '?view=' + encodeURIComponent(view));
        });
}