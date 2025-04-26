// Lắng nghe sự kiện change cho tất cả select
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const objectId = this.dataset.objectId;
        const status1 = this.value;
        const choose_status = status1.split("-")
        const status = choose_status[1]
        console.log(`ID: ${objectId} cập nhật thành: ${status} của ${choose_status[0]}`);
        
        // Gọi API cập nhật
        updateBillStatus(objectId, status,choose_status[0]);
    });
    function updateBillStatus(billId, status, object) {
    fetch('handle/admin/update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            object_id: billId,
            status: status,
            object: object
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Cập nhật thành công');
        } else {
            console.error('Lỗi:', data.message);
        }
    });
}});