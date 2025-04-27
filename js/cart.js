function formatCurrency(num) {
    return num.toLocaleString('vi-VN') + ' VNĐ';
}

function updateTotal() {
    let total = 0;
    // Lấy tất cả các input số lượng trong bảng giỏ hàng
    document.querySelectorAll('input[name^="quantity["]').forEach(function(input) {
        let id = input.name.match(/\d+/)[0];
        let price = parseInt(document.getElementById('qty-' + id).getAttribute('data-price'));
        let qty = parseInt(input.value) || 1;
        let subtotal = price * qty;
        document.getElementById('subtotal-' + id).innerText = formatCurrency(subtotal);
        total += subtotal;
    });
    document.getElementById('totalAmount').innerText = formatCurrency(total);
    document.getElementById('totalInput').value = total;
}

function changeQty(id, delta) {
    let input = document.getElementById('qty-' + id);
    let value = parseInt(input.value) || 1;
    value += delta;
    if (value < 1) value = 1;
    input.value = value;
    updateTotal();
}

// Cập nhật tổng tiền khi trang vừa load
window.onload = function () {
    updateTotal();
};

