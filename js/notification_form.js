import Swal from "https://cdn.jsdelivr.net/npm/sweetalert2@11";
export function showDeleteWarning() {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
text: "Bạn sẽ không thể hoàn tác hành động này!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Xóa',
cancelButtonText: 'Hủy'
    })
}