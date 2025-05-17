document.querySelector('.filter-bar').addEventListener('click', function() {
    document.getElementById('filterModal').style.display = 'block';
    console.log(1)
});

document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const urlParams = new URLSearchParams(window.location.search);
    const mode = urlParams.get('mode');
    console.log(mode);
    
    // Lưu tham chiếu đến form
    const form = this;
    
    if (mode) {
        // Bỏ ký tự 's' cuối cùng (nếu có)
        const temp = mode.replace(/s$/, '');
        filterModal(temp, form); // Truyền form vào hàm
    }
    
    function filterModal(object, formElement) {
        // Sử dụng formElement thay vì this
        const formData = new FormData(formElement);
        
        fetch(`admin/dashboard/table/${object}_management.php`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            document.querySelector('.content-object-container').innerHTML = data;
            document.getElementById('filterModal').style.display = 'none';
        })
        .catch(error => console.error('Lỗi lọc:', error));
    }
});