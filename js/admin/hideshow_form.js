// Xử lý nút Thêm Voucher với kiểm tra quyền
$(document).on('click', '.add-object-btn', function(e) {
    e.preventDefault();
    // Gọi API kiểm tra quyền
    fetch('handle/admin/permisionhideshow.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ permission: 4 }) // 4 là mã quyền thêm, thay đổi nếu cần
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            Swal.fire('Lỗi', data.message || 'Bạn không có quyền thực hiện thao tác này.', 'error');
            return;
        }
        // Có quyền, hiển thị form, ẩn bảng
        $('.formadd-object-container').addClass("active")
        $('.object-management').addClass('hidden');
        $('.object-management').removeClass('active');
        $(".formadd-object-container").removeClass("hidden");
    })
    .catch(() => {
        Swal.fire('Lỗi', 'Không thể kiểm tra quyền.', 'error');
    });
});

// Xử lý nút đóng form (thêm nút này vào form của bạn)
$(document).on('click', '.close-form-btn', function() {
    // Ẩn form, hiện bảng
    $('.formadd-object-container').removeClass('active');
    $('.object-management').removeClass('hidden');
    $('.formadd-object-container').addClass("hidden")
    $('.object-management').addClass('active');
});