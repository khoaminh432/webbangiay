document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút Quick View
    const quickViewButtons = document.querySelectorAll('.quick-view');
    const modal = document.getElementById('quickViewModal');
    const closeBtn = document.querySelector('.close');
    const modalAddToCart = document.getElementById('modalAddToCart');

    // Xử lý nút Add to Cart
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            addToCart(productId);
        });
    });

    quickViewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            fetchProductDetails(productId);
            modal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    if (modalAddToCart) {
        modalAddToCart.addEventListener('click', function() {
            const productId = this.dataset.id;
            addToCart(productId);
        });
    }
});

function fetchProductDetails(productId) {
    console.log('Fetching product ID:', productId); // Log ID sản phẩm

    fetch(`/webbangiay/api/get_product.php?id=${productId}`)
        .then(response => {
            console.log('Response status:', response.status); // Log status response
            return response.json();
        })
        .then(product => {
            console.log('Product data:', product); // Log toàn bộ dữ liệu sản phẩm

            // Kiểm tra từng phần tử trước khi gán
            const modalImage = document.getElementById('modalProductImage');
            const modalName = document.getElementById('modalProductName');
            const modalType = document.getElementById('modalProductType');
            const modalSupplier = document.getElementById('modalProductSupplier');
            const modalDescription = document.getElementById('modalProductDescription');
            const modalWeight = document.getElementById('modalProductWeight');
            const modalPrice = document.getElementById('modalProductPrice');

            // Kiểm tra và gán giá trị
            if (modalImage) {
                modalImage.src = product.image_url 
                    ? `/webbangiay/img/product/${product.id}/${product.image_url}`
                    : '/webbangiay/img/product/default.jpg';
            }

            if (modalName) modalName.textContent = product.name || '';
            if (modalType) modalType.textContent = `Loại sản phẩm: ${product.type_name || ''}`;
            if (modalSupplier) modalSupplier.textContent = `Nhà cung cấp: ${product.supplier_name || ''}`;
            if (modalDescription) modalDescription.textContent = product.description || '';
            if (modalWeight) modalWeight.textContent = `Trọng lượng: ${product.weight || 0}kg`;
            if (modalPrice) modalPrice.textContent = `${Number(product.price || 0).toLocaleString('vi-VN')} VNĐ`;

            // Hiển thị modal
            const modal = document.getElementById('quickViewModal');
            if (modal) {
                modal.style.display = 'block';
            }

            // Cập nhật nút thêm vào giỏ hàng
            const modalAddToCart = document.getElementById('modalAddToCart');
            if (modalAddToCart) {
                modalAddToCart.dataset.id = product.id;
            }
        })
        .catch(error => {
            console.error('Error fetching product:', error); // Log chi tiết lỗi
            alert('Có lỗi xảy ra khi tải thông tin sản phẩm');
        });
}

function addToCart(productId) {
    if (!isLoggedIn()) {
        window.location.href = '/webbangiay/pages/login.php';
        return;
    }

    fetch('/webbangiay/pages/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            productId: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Đã thêm sản phẩm vào giỏ hàng!');
            updateCartCount(data.cart_count);
        } else {
            showNotification(data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
    });
}

function isLoggedIn() {
    return document.querySelector('.add-to-cart') !== null;
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}