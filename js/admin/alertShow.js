// js/admin/alertShow.js
export default class AlertManager {
  confirm(message, onConfirm) {
    Swal.fire({
      title: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Đồng ý',
      cancelButtonText: 'Hủy'
    }).then(result => {
      if (result.isConfirmed) {
        onConfirm();
      }
    });
  }

  success(title = "Thành công", text = "") {
    Swal.fire({ icon: 'success', title, text });
  }

  error(title = "Lỗi", text = "") {
    Swal.fire({ icon: 'error', title, text });
  }
}
