// Handle all action buttons using event delegation
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to the table
    document.querySelector('.data-table').addEventListener('click', function(e) {
        // Find the closest button with data-action
        const button = e.target.closest('button[data-action]');
        if (!button) return;
        
        // Get action and user ID
        const action = button.getAttribute('data-action');
        const userId = button.getAttribute('data-id');
        
        // Handle different actions
        switch(action) {
            case 'view':
                handleViewAction(userId);
                break;
            case 'edit':
                editUser(userId);
                break;
            case 'delete':
                confirmDelete(userId);
                break;
            default:
                console.warn('Unknown action:', action);
        }
    });
});
// View User Function
function handleViewAction(userId) {
    $.ajax({
        url: 'get_user_details.php',
        type: 'GET',
        data: {id: userId},
        dataType: 'json',
        beforeSend: function() {
            $('#userDetails').html('<p>Đang tải dữ liệu...</p>');
            $('#userModal').show();
        },
        success: function(response) {
            if(response.success) {
                const user = response.user;
                const html = `
                    <?php $object_id = userId;
                    require_once __DIR__."/../../admin/dashboard/form/view/userview_form.php";?>
                `;
                $('#userDetails').html(html);
            } else {
                $('#userDetails').html(`<p class="error">${response.message}</p>`);
            }
        },
        error: function() {
            $('#userDetails').html('<p class="error">Lỗi khi tải dữ liệu</p>');
        }
    });
}
// Edit User Function
function editUser(userId) {
    window.location.href = `edit_user.php?id=${userId}`;
}

// Delete User Function with Confirmation
function confirmDelete(userId) {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: "Bạn sẽ không thể hoàn tác hành động này!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Có, xóa nó!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send delete request
            fetch(`delete_user.php?id=${userId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire(
                        'Đã xóa!',
                        'Người dùng đã được xóa.',
                        'success'
                    ).then(() => {
                        // Remove the row from table
                        document.querySelector(`tr[data-id="${userId}"]`).remove();
                    });
                } else {
                    Swal.fire(
                        'Lỗi!',
                        data.message || 'Không thể xóa người dùng.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Lỗi!',
                    'Đã xảy ra lỗi khi xóa người dùng.',
                    'error'
                );
                console.error('Error:', error);
            });
        }
    });
}

// Close Modal Function
function closeModal() {
    document.getElementById('userViewModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('userViewModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Add dynamic styles for user details
const style = document.createElement('style');
style.textContent = `
.user-detail-row {
    display: flex;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.detail-label {
    font-weight: 600;
    width: 120px;
    color: #555;
}
.detail-value {
    flex: 1;
}
.error-message {
    color: #dc3545;
    padding: 20px;
    text-align: center;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
`;
document.head.appendChild(style);