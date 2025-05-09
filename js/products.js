document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút Quick View
    const quickViewButtons = document.querySelectorAll('.quick-view');
    const modal = document.getElementById('quickViewModal');
    const closeBtn = document.querySelector('.close');
    const modalAddToCart = document.getElementById('modalAddToCart');

    // Xử lý nút Add to Cart
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            fetchProductDetails(productId); // Mở modal để chọn size, màu
            modal.style.display = 'block';
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
            const sizeId = document.getElementById('modalSizeSelect').value;
            const colorId = document.getElementById('modalColorSelect').value;
            if (!sizeId || !colorId) {
                showNotification('Vui lòng chọn kích cỡ và màu sắc!', 'error');
                return;
            }
            addToCart(productId, sizeId, colorId);
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

            // Lấy danh sách size và màu sắc
            fetch(`/webbangiay/pages/get_product_options.php?id=${productId}`)
                .then(response => response.json())
                .then(options => {
                    // Đổ size
                    const sizeSelect = document.getElementById('modalSizeSelect');
                    sizeSelect.innerHTML = '<option value="">Chọn kích cỡ</option>';
                    if (options.sizes) {
                        options.sizes.forEach(size => {
                            sizeSelect.innerHTML += `<option value="${size.id}">${size.size_number}</option>`;
                        });
                    }
                    // Đổ màu
                    const colorSelect = document.getElementById('modalColorSelect');
                    colorSelect.innerHTML = '<option value="">Chọn màu sắc</option>';
                    if (options.colors) {
                        options.colors.forEach(color => {
                            colorSelect.innerHTML += `<option value="${color.id}">${color.name}</option>`;
                        });
                    }
                });
        })
        .catch(error => {
            console.error('Error fetching product:', error); // Log chi tiết lỗi
            alert('Có lỗi xảy ra khi tải thông tin sản phẩm');
        });
}

function addToCart(productId, sizeId, colorId) {
    // if (!isLoggedIn()) {
    //     window.location.href = '/webbangiay/pages/login.php';
    //     return;
    // }

    fetch('/webbangiay/pages/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            productId: productId,
            sizeId: sizeId,
            colorId: colorId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Đã thêm sản phẩm vào giỏ hàng!');
            updateCartCount(data.cart_count);
            document.getElementById('quickViewModal').style.display = 'none';
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
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 4px;
        color: white;
        font-weight: 500;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification.success {
        background-color: #4CAF50;
    }

    .notification.error {
        background-color: #f44336;
    }

    .product-options {
        margin: 20px 0;
    }

    .size-selector,
    .color-selector {
        margin-bottom: 15px;
    }

    .size-selector label,
    .color-selector label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .size-selector select,
    .color-selector select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .color-selector option {
        padding: 5px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 800px;
        border-radius: 8px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .product-details {
        display: flex;
        gap: 30px;
    }

    .product-image {
        flex: 1;
        max-width: 400px;
    }

    .product-image img {
        width: 100%;
        height: auto;
        border-radius: 4px;
    }

    .product-info {
        flex: 1;
    }

    .product-info h2 {
        margin-top: 0;
        margin-bottom: 15px;
    }

    .product-info p {
        margin: 10px 0;
    }

    .add-to-cart-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
        width: 100%;
    }

    .add-to-cart-btn:hover {
        background-color: #45a049;
    }

    .login-to-buy-btn {
        display: inline-block;
        background-color: #f44336;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 4px;
        margin-top: 20px;
        width: 100%;
        text-align: center;
    }

    .login-to-buy-btn:hover {
        background-color: #da190b;
    }
`;
document.head.appendChild(style); 