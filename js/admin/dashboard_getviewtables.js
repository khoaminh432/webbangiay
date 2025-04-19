$(document).ready(function() {
    const contentCache = {};
    let currentMode = 'users'; // Theo dõi trạng thái hiện tại
    
    // Xử lý click menu
    $('.ajax-load').click(function(e) {
        e.preventDefault(); // Ngăn hành vi mặc định nếu là thẻ <a>
        const mode = $(this).data('mode');
        if (mode !== currentMode) { // Chỉ load nếu khác mode hiện tại
            loadDashboardContent(mode, true);
        }
    });
    
    // Hàm tải nội dung
    function loadDashboardContent(mode, pushState = false) {
        currentMode = mode; // Cập nhật mode hiện tại
        
        // Kiểm tra cache trước
        if (contentCache[mode]) {
            updateContent(mode, contentCache[mode], pushState);
            return;
        }
        
        $.ajax({
            url: `admin/dashboard/dashboard_${mode}.php`, // Đã sửa đường dẫn
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                contentCache[mode] = data;
                
                updateContent(mode, data, pushState);
            },
            error: function(xhr) {
                $('#content-area').html(`
                    <div class="error">
                        <p>Lỗi tải nội dung: ${xhr.statusText}</p>
                        <button onclick="location.reload()">Thử lại</button>
                    </div>
                `);
            }
        });
    }
    
    // Cập nhật nội dung và trạng thái
    function updateContent(mode, content, pushState = false) {
        $('#content-area').html(content);
        // Active menu (cải tiến hiệu suất)
        $('.ajax-load').removeClass('active')
                      .filter(`[data-mode="${mode}"]`).addClass('active');
        
        // Cập nhật URL
        if (pushState) {
            const url = new URL(window.location);
            url.searchParams.set('mode', mode);
            window.history.pushState({ mode }, '', url);
        }
    }
    
    // Xử lý popstate (tối ưu)
    $(window).on('popstate', function(e) {
        if (e.originalEvent.state) {
            const mode = e.originalEvent.state.mode || getModeFromURL();
            if (mode && mode !== currentMode) {
                loadDashboardContent(mode);
            }
        }
    });
    
    // Lấy mode từ URL
    function getModeFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('mode') || 'users';
    }
    
    // Khởi tạo
    function initialize() {
        const mode = getModeFromURL();
        loadDashboardContent(mode);
    }
    
    // Tải trước khi hover (tùy chọn)
    $('.ajax-load').hover(
        function() {
            const mode = $(this).data('mode');
            if (!contentCache[mode]) {
                $.get(`admin/dashboard/dashboard_${mode}.php`)
                 .done(data => contentCache[mode] = data);
            }
        }, 
        function() {}
    );
    
    initialize();
});