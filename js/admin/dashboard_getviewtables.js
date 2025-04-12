$(document).ready(function() {
    // Xử lý click menu
    $('.ajax-load').click(function() {
        // Lấy mode từ data attribute
        const mode = $(this).data('mode');
        console.log(mode)
        // Gọi hàm load content
        loadDashboardContent(mode);
        
        // Active menu
        $('.ajax-load').removeClass('active');
        $(this).addClass('active');
    });
    const contentCache = {};
    // Hàm tải nội dung
    function loadDashboardContent(mode) {
        
        $.ajax({
            url: `admin/dashboard/dashboard_${mode}.php`,
            type: 'GET',
            dataType: 'html',
            
            success: function(data) {
                $('#content-area').html(data);
            },
            error: function(xhr, status, error) {
                $('#content-area').html(`
                    <div class="error">
                        <p>Lỗi tải nội dung: ${error}</p>
                        <button onclick="location.reload()">Thử lại</button>
                    </div>
                `);
            }
        });
        
    }

    // Tải trang mặc định (users) khi khởi động
    loadDashboardContent('users');
});


// Xử lý khi người dùng dùng nút back/forward
$(window).on('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const mode = urlParams.get('mode') || 'users';
    loadDashboardContent(mode);
});
// Tải trước các trang khi hover
$('.ajax-load').hover(function() {
    const mode = $(this).data('mode');
    $.get(`dashboard/dashboard_${mode}.php`);
}, function() {});


