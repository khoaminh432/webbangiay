document.querySelector(".add-form")?.addEventListener("submit", function(e) {
    e.preventDefault(); // Ngăn reload trang

    const form = e.target;
    const formData = new FormData(form);

    fetch("handle/admin/addobject_process.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Lưu giá trị cần giữ
            const objectTitle = form.querySelector('input[name="object-add-title"]').value;
            // Reset form
            form.reset();
            // Gán lại giá trị đã lưu
            form.querySelector('input[name="object-add-title"]').value = objectTitle;
            Swal.fire({ icon: 'success', title:"Thành công", text:data.message })
            .then(result=>{location.reload();});
        } else {
            Swal.fire({ icon: 'error', title:"Lỗi", text:data.message })
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
        alert("Đã xảy ra lỗi khi thêm.");
    });
});