// Sử dụng event delegation để đảm bảo hoạt động sau khi lọc
document.addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('status-select')) {
        const selectElement = e.target;
        const objectId = selectElement.dataset.objectId;
        const status1 = selectElement.value;
        const choose_status = status1.split("-");
        const status = choose_status[1];
        const object = choose_status[0];

        console.log(`ID: ${objectId} cập nhật thành: ${status} của ${object}`);

        // Gọi API cập nhật
        updateBillStatus(objectId, status, object);
    }
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
            console.log('✅ Cập nhật thành công');
        } else {
            console.error('❌ Lỗi:', data.message);
        }
    })
    .catch(error => {
        console.error('❌ Lỗi fetch:', error);
    });
}
