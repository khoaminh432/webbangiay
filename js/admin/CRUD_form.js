
$(document).ready(function() {

    // Xử lý sự kiện click trên các nút action
    $('.action-btn').click(function() {
        const action = $(this).data('action'); // 'view', 'update', 'delete'
        const userId = $(this).data('id'); // ID của user
        console.log(action,userId)
        const check_test = action.split("-")
        switch (check_test[0]) {
            
            case 'view':
                viewObject(userId,check_test[1])
                console.log("vvvie")
                break;
            case 'update':
                editObject(userId,check_test[1])
                console.log("update");
                break;
            case 'delete':

                console.log(check_test[1])
                deleteobject(userId,check_test[1])
                break;
            default:
                console.log("chưa chọn")
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
    function editObject(userId,check_test){
        $.ajax({
            url: `admin/dashboard/form/edit/${check_test}edit_form.php`,
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
    function deleteobject(objectid,check_test){
        
        Swal.fire({
            title: "Bạn có chắc chắn muốn xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
          }).then(result => {
            if (result.isConfirmed) {
              onConfirm(objectid,check_test);
            }
          });    
          function onConfirm(objectId,check_test){
            $.ajax({
                url: `handle/admin/deleteobject_process.php`,
                type: 'GET',
                data: { 
                    id: objectId,
                    check: check_test
                },
                dataType: "json", // Đúng chính tả
                success: function(response) {
                    const row = document.querySelector(`tr[data-id="${objectId}"]`);
                    console.log(response.success,response.message)
                    try{if (response.success) {
                        row.remove( )
                        Swal.fire("Thành công", response.message, "success");
                    } else {
                        Swal.fire("Lỗi", response.message || "Xóa thất bại!", "error");
                    }}
                    catch (error){
                    console.error("Đã xảy ra lỗi:", error.message);}
                    
                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire("Lỗi", "Kết nối máy chủ thất bại!", "error");
                }
            });
            }
        
        
    }
    
});
