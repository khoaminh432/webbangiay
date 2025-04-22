// Hàm đóng modal
function closeObjectView() {
    
    $('#objectViewModal').fadeOut(); // Hoặc sử dụng hide() nếu muốn
    
}

// Có thể thêm sự kiện click ra ngoài modal để đóng
$(document).mouseup(function(e) {
    const modal = $('#objectViewModal');
    if (!modal.is(e.target) && modal.has(e.target).length === 0) {
        modal.fadeOut();
    }
});