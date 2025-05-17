$(document).ready(function () {
    $('#permissionForm').on('submit', function (e) {
        e.preventDefault(); // Ngăn form submit mặc định

        var formData = $(this).serialize(); // Lấy toàn bộ dữ liệu form

        $.ajax({
            url: "handle/admin/save_permission_matrix.php", // Đường dẫn submit form
            type: 'POST',
            data: formData,
            success: function (response) {
                Swal.fire('Thành công', response, 'success');
                console.log(response);
            },
            error: function (xhr, status, error) {
                alert("Có lỗi xảy ra: " + error);
                console.error(xhr.responseText);
            }
        });
    });
});