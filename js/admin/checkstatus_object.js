document.addEventListener('change', function (e) {
    // Trước hết, chỉ check quyền nếu user vừa thay đổi status-select
    if (!(e.target && e.target.classList.contains('status-select'))) return;

    // Gửi request kiểm tra quyền
    fetch('handle/admin/functionpermission.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ permission: "4" })
    })
    .then(res => res.json())
    .then(data => {
        if (data) {
            $('#objectViewModal').html(data).fadeIn();
        }
        // Có quyền -> tiếp tục update
        const selectElement = e.target;
        const [object, status] = selectElement.value.split("-");
        const objectId = selectElement.dataset.objectId;
        updateBillStatus(objectId, status, object);
    })
    .catch(err => {
        console.error("Lỗi kiểm tra quyền:", err);
    });
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
