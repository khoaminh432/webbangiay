$(document).ready(function() {
    // Sử dụng event delegation để đảm bảo các nút động cũng được xử lý
    const container = $(document);

    // Xử lý sự kiện click trên các nút action (view, update, delete)
    container.on('click', '.action-btn', function() {
        const action = $(this).data('action'); // ví dụ 'view-bill', 'delete-bill'
        const itemId = $(this).data('id');    // ID của đối tượng
        console.log('Action:', action, 'ID:', itemId);

        const [cmd, type] = action.split('-');
        switch (cmd) {
            case 'view':
                viewObject(itemId, type);
                break;
            case 'update':
                editObject(itemId, type);
                break;
            case 'delete':
                deleteObject(itemId, type);
                break;
            default:
                console.log('Không xác định action');
        }
    });

    // Hàm xem chi tiết đối tượng (Modal/Popup)
    function viewObject(id, type) {
        $.ajax({
            url: `admin/dashboard/form/view/${type}view_form.php`,
            type: 'GET',
            data: { id },
            success: function(response) {
                $('#objectViewModal').html(response).fadeIn();
            },
            error: function() {
                Swal.fire('Lỗi', 'Không tải được dữ liệu', 'error');
            }
        });
    }

    // Hàm sửa đối tượng (Modal/Popup)
    function editObject(id, type) {
        $.ajax({
            url: `admin/dashboard/form/edit/${type}edit_form.php`,
            type: 'GET',
            data: { id },
            success: function(response) {
                $('#objectViewModal').html(response).fadeIn();
            },
            error: function() {
                Swal.fire('Lỗi', 'Không tải được dữ liệu', 'error');
            }
        });
    }

    // Hàm xóa đối tượng với xác nhận
    function deleteObject(id, type) {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'handle/admin/deleteobject_process.php',
                    type: 'GET',
                    data: { id, check: type },
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            $(`tr[data-id=\"${id}\"]`).remove();
                            Swal.fire('Thành công', res.message, 'success');
                        } else {
                            Swal.fire('Lỗi', res.message || 'Xóa thất bại', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Lỗi', 'Kết nối máy chủ thất bại', 'error');
                    }
                });
            }
        });
    }
});
