document.addEventListener('DOMContentLoaded', function() {
    const cartItems = document.getElementById('cart-items');
    if (!cartItems) return;

    // Xử lý nút tăng/giảm số lượng
    cartItems.addEventListener('click', function(e) {
        if (e.target.classList.contains('quantity-btn')) {
            const input = e.target.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            
            if (e.target.classList.contains('plus')) {
                input.value = currentValue + 1;
            } else if (e.target.classList.contains('minus') && currentValue > 1) {
                input.value = currentValue - 1;
            }
            
            updateQuantity(input);
        }
    });

    // Xử lý input số lượng
    cartItems.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateQuantity(e.target);
        }
    });

    // Xử lý nút xóa
    cartItems.addEventListener('click', function(e) {
        if (e.target.closest('.remove-button')) {
            const row = e.target.closest('tr');
            const productId = row.dataset.id;
            removeItem(productId, row);
        }
    });

    function updateQuantity(input) {
        const row = input.closest('tr');
        const productId = row.dataset.id;
        const quantity = parseInt(input.value);

        fetch('/webbangiay/pages/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update&productId=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật tổng tiền sản phẩm
                row.querySelector('.item-total').textContent = 
                    `${Number(data.itemTotal).toLocaleString('vi-VN')} VNĐ`;
                document.getElementById('total-price').textContent = 
                    `${Number(data.totalPrice).toLocaleString('vi-VN')} VNĐ`;
                
                // Cập nhật số lượng trong giỏ hàng ở header
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.totalQuantity;
                }
                updateCartCount();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function removeItem(productId, row) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) return;

        fetch('/webbangiay/pages/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=remove&productId=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                document.getElementById('total-price').textContent = 
                    `${Number(data.totalPrice).toLocaleString('vi-VN')} VNĐ`;
                
                // Cập nhật số lượng trong giỏ hàng ở header
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.totalQuantity;
                }
                
                // Kiểm tra nếu giỏ hàng trống
                if (document.querySelectorAll('#cart-items tr').length === 0) {
                    location.reload();
                }
                updateCartCount();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Hàm cập nhật số lượng trong giỏ hàng ở header
    function updateCartCount() {
        fetch('/webbangiay/pages/cart.php?action=getCount')
            .then(response => response.json())
            .then(data => {
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.count;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Giả sử bạn có nút "Thêm vào giỏ hàng" với class .add-to-cart-btn
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var productId = this.dataset.productId;
            fetch('/webbangiay/pages/add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'productId=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    alert('Đã thêm vào giỏ hàng!');
                }
            });
        });
    });
});