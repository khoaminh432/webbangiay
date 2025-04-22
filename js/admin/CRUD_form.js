$(document).ready(function() {
    // Xử lý sự kiện click trên các nút action
    $('.action-btn').click(function() {
        const action = $(this).data('action'); // 'view', 'update', 'delete'
        const userId = $(this).data('id'); // ID của user
        console.log(action,userId)
        const check_test = action.split("-")
        switch (check_test[0]) {
            case 'view':
                viewObject(userId,check_test[1]);
                break;
            case 'update':
                
                break;
            case 'delete':
                
                break;
        }
    });

    // Hàm xem chi tiết user (Modal/Popup)
    function viewObject(userId,check_test) {
        $.ajax({
            url: `admin/dashboard/form/view/${check_test}view_form.php`,
            type: 'GET',
            data: { id: userId },
            success: function(response) {
                // Mở modal và hiển thị dữ liệu
                
                $('#objectViewModal').html(response).fadeIn();
            },
            error: function(xhr) {
                alert('Lỗi khi tải dữ liệu!');
            }
        });
    }

    
});