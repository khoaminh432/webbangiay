document.addEventListener('DOMContentLoaded', function () {
    const cartItems = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');

    // Xử lý xóa sản phẩm
    cartItems.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-button')) {
            const row = event.target.closest('tr');
            const productId = row.getAttribute('data-id');

            fetch('/webbangiay/pages/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'remove',
                    productId: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                    totalPriceElement.textContent = new Intl.NumberFormat().format(data.totalPrice) + ' VNĐ';
                } else {
                    alert('Failed to remove product.');
                }
            });
        }
    });

    // Xử lý cập nhật số lượng
    cartItems.addEventListener('change', function (event) {
        if (event.target.classList.contains('quantity-input')) {
            const row = event.target.closest('tr');
            const productId = row.getAttribute('data-id');
            const quantity = event.target.value;

            fetch('/webbangiay/pages/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'update',
                    productId: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const itemTotal = row.querySelector('.item-total');
                    itemTotal.textContent = new Intl.NumberFormat().format(data.itemTotal) + ' VNĐ';
                    totalPriceElement.textContent = new Intl.NumberFormat().format(data.totalPrice) + ' VNĐ';
                } else {
                    alert('Failed to update quantity.');
                }
            });
        }
    });
});