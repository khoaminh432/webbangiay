function initOrderDetailModal() {
    function closeModal() {
        document.getElementById('order-modal').style.display = 'none';
        document.getElementById('order-modal-overlay').style.display = 'none';
        document.getElementById('order-modal').innerHTML = '';
    }
    function bindCloseInModal() {
        var closeBtn = document.querySelector('#order-modal .close-modal');
        if (closeBtn) closeBtn.onclick = closeModal;
    }
    // Xóa sự kiện cũ để tránh nhân đôi
    document.querySelectorAll('.view-order-detail').forEach(function(link) {
        link.replaceWith(link.cloneNode(true));
    });
    document.querySelectorAll('.view-order-detail').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var orderId = this.getAttribute('data-id');
            var modal = document.getElementById('order-modal');
            var overlay = document.getElementById('order-modal-overlay');
            modal.innerHTML = '<div style="padding:32px 40px;color:#7ecbff;text-align:center;">Đang tải...</div>';
            modal.style.display = 'block';
            overlay.style.display = 'block';
            fetch('admin/statistic_infor/order_detail.php?id=' + orderId)
                .then(response => response.text())
                .then(html => {
                    modal.innerHTML = html;
                    bindCloseInModal();
                });
        });
    });
    document.getElementById('order-modal-overlay').onclick = closeModal;
    if (!window._orderDetailModalKeydown) {
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") closeModal();
        });
        window._orderDetailModalKeydown = true;
    }
}