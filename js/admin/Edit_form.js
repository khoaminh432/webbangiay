document.querySelector(".object-edit-card").addEventListener("submit",function(e) {
    e.preventDefault(); // Ngăn reload

    const form = e.target;
    const formData = new FormData(form);
    Swal.fire({
            title: "Bạn có chắc chắn muốn sửa sản phẩm này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
          }).then(result => {
            if (result.isConfirmed) {
              fetch("handle/admin/editobject_process.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())  // hoặc .text() tùy theo API trả về
                .then(data => {
                    if (data.success) {
                        Swal.fire("Thành công", data.message, "success")
                        .then(result=>{location.reload();});;
                        //closeObjectView(); // hoặc load lại danh sách sản phẩm
                    } else {
                        Swal.fire("Lỗi", data.message || "Xóa thất bại!", "error");
                    }
                })
                .catch(error => {
                    console.error("Lỗi khi gửi form:", error);
                    alert("Đã xảy ra lỗi!");
                });
            }
          });    
    
});