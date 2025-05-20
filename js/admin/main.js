// Hàm tải lại script
function reloadScripts(container) {
    const scripts = container.querySelectorAll('script');
    
    scripts.forEach(oldScript => {
        const newScript = document.createElement('script');
        
        // Copy attributes
        Array.from(oldScript.attributes).forEach(attr => {
            newScript.setAttribute(attr.name, attr.value);
        });
        
        // Copy nội dung script
        if (oldScript.src) {
            newScript.src = oldScript.src;
        } else {
            newScript.textContent = oldScript.textContent;
        }
        
        // Thay thế script cũ
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
}

// Hàm tải nội dung AJAX
async function loadContent(url, container) {
    try {
        // Hiển thị trạng thái loading
        container.innerHTML = '<div class="loading-indicator">Đang tải...</div>';
        
        // Lấy nội dung
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const html = await response.text();
        
        // Tạo DOM ảo để parse HTML
        const virtualDOM = document.createElement('div');
        virtualDOM.innerHTML = html;
        
        // Render nội dung
        container.innerHTML = virtualDOM.innerHTML;
        
        // Khởi tạo lại scripts
        reloadScripts(container);
        
        // Gọi callback khởi tạo
        if (window.contentLoadedCallback) {
            window.contentLoadedCallback();
        }
        
    } catch (error) {
        console.error('Load content error:', error);
        container.innerHTML = `
            <div class="error-message">
                <i class="icon-warning"></i>
                <p>Lỗi khi tải nội dung: ${error.message}</p>
                <button class="retry-btn">Thử lại</button>
            </div>
        `;
        
        // Thêm sự kiện retry
        container.querySelector('.retry-btn')?.addEventListener('click', () => loadContent(url, container));
    }
}

// Sự kiện khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    // Sử dụng cho menu chính
    $(document).on('click', '.menu-btn', function() {
        const view = $(this).data('view');
        loadContent(`admin/${view}`, document.getElementById('admin-content'));
    });

    // Sử dụng cho dashboard tabs
    $(document).on('click', '.nav-button.ajax-load', function() {
        const mode = $(this).data('mode');
        loadContent(`admin/dashboard/dashboard_${mode}.php`, document.getElementById('content-area'));
    });

    // Sự kiện logout
    $('#btnLogout').on('click', function() {
        window.location.href = 'logout.php';
    });

    // Khởi tạo ban đầu
    if (window.contentLoadedCallback) {
        window.contentLoadedCallback();
    }
});