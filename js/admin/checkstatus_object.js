let allowStatusChange = false;

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('status-select')) {
        permission = 5
        console.log(permission)
        // Gọi API kiểm tra quyền
        fetch('handle/admin/admin_permissionstatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ permission: permission }) // Thay "4" bằng mã quyền phù hợp nếu cần
        })

        .then(res => res.json())
        .then(data => {
            if (data.success) {
                allowStatusChange = true;
            } else {
                allowStatusChange = false;
                Swal.fire('Lỗi', data.message || 'Bạn không có quyền thực hiện thao tác này.', 'error');
            }
        })
        .catch(() => {
            allowStatusChange = false;
            Swal.fire('Lỗi', 'Không thể kiểm tra quyền.', 'error');
        });
    }
});

document.addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('status-select')) {
        if (!allowStatusChange) {
            // Không cho phép change nếu chưa click
            e.preventDefault();
            return;
        }
        allowStatusChange = false; // Reset flag

        // Thực hiện logic thay đổi trạng thái ở đây
        const selectElement = e.target;
        const [object, status] = selectElement.value.split("-");
        const objectId = selectElement.dataset.objectId;
        updateBillStatus(objectId, status, object);
    }
});
function updateBillStatus(billId, status, object) {
    fetch('handle/admin/update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ object_id: billId, status, object })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Cập nhật thành công');
        } else {
            Swal.fire('Lỗi', data.message || 'Cập nhật thất bại', 'error');
        }
    })
    .catch(error => {
        console.error('Lỗi fetch:', error);
        Swal.fire('Lỗi', 'Không thể kết nối máy chủ.', 'error');
    });
}