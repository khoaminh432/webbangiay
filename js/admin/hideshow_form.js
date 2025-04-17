// Xử lý nút Thêm Voucher
$(document).on('click', '.add-object-btn', function() {
    // Hiển thị form, ẩn bảng
    $('.formadd-object-container').addClass("active")
    $('.object-management').addClass('hidden');
    $('.object-management').removeClass('active');
    $(".formadd-object-container").removeClass("hidden");
});

// Xử lý nút đóng form (thêm nút này vào form của bạn)
$(document).on('click', '.close-form-btn', function() {
    // Ẩn form, hiện bảng
    $('.formadd-object-container').removeClass('active');
    $('.object-management').removeClass('hidden');
    $('.formadd-object-container').addClass("hidden")
    $('.object-management').addClass('active');
});