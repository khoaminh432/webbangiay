// Định nghĩa các hàm khởi tạo
window.contentLoadedCallback = function() {
    // Khởi tạo tabs
    if (typeof initTabs === 'function') initTabs();
    
    // Khởi tạo forms
    if (typeof initForms === 'function') initForms();
    
    // Khởi tạo các thành phần UI khác
    if (typeof initUI === 'function') initUI();
    
    // Khởi tạo tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Khởi tạo các sự kiện
    initEvents();
};

function initEvents() {
    // Các sự kiện chung
    $(document).off('click', '.dynamic-element').on('click', '.dynamic-element', handleClick);
    
    // Sự kiện riêng cho permission form
    if ($('#permissionForm').length) {
        initPermissionForm();
    }
}

function initPermissionForm() {
    $('#permissionForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        // Lấy dữ liệu form
        const formData = {};
        $(this).find('input[type="checkbox"]').each(function() {
            const name = $(this).attr('name');
            formData[name] = $(this).is(':checked');
        });

        // Gửi dữ liệu bằng AJAX
        $.ajax({
            url: 'admin/save_permissions.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: 'Đã lưu phân quyền thành công'
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi lưu phân quyền'
                });
            }
        });
    });
}

// Hàm khởi tạo tabs (ví dụ)
function initTabs() {
    $('.nav-tabs a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
}

// Hàm khởi tạo UI
function initUI() {
    // Khởi tạo các thành phần UI khác
}