<?php
    require_once __DIR__ . "/../webbangiay/dao/UserDao.php";
    $tables = new UserDao();
    $users = $tables->view_all();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../webbangiay/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/notification_form.js"></script>
</head>
<body>
    <div class="row container">
    <div class="left-menu">
        <div class="title">Admin Panel</div>
        <div class="task">
        <div class="Dashboard-leftmenu"><ion-icon name="home-outline"></ion-icon> Dashboard</div>
        <div class="Users"><ion-icon name="person-outline"></ion-icon> Users</div>
        <div class="Setting"><ion-icon name="settings-outline"></ion-icon> Setting</div>
        <div class="logout"><ion-icon name="log-out-outline"></ion-icon> logout</div>
        </div>
    </div>
    <div class="right-menu column">
        <header class="decorate-center-flex-text">
            <h1 >Welcome to Admin Dashboard</h1>
        </header>
        <div class="mid-right-menu">
            <h1>Quản Lý Tài Khoảng</h1>
            <div class="search-bar-mid-right-menu">
                <input type="text" placeholder="nhập ID tìm kiếm....."><button>tìm kiếm</button>
            </div>
            .
            <table>
                <thead>
                <tr>
                <th>ID</th>
                <th>Tên tài khoảng</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Quyền</th>
                <th>Hoạt động</th>
            </tr>   </thead>
            <tbody><?php foreach($users as $user): ?>
        <tr data-id="<?= $user->id ?>">
        <td><?= $user->id ?></td>
        <td><?= $user->username ?></td>
        <td><?= $user->email ?></td>
        <td><?= $user->status ?></td>
        <td>1</td>
        <td class='row button-update'>
            <button class='action-btn' data-action='update' data-id='<?= $user->id ?>' style='background:rgb(72, 210, 72);'>sửa</button>
            <button class='action-btn' data-action='delete' data-id='<?= $user->id ?>' style='background:rgb(237, 74, 74);'>xóa</button>
        </td>
        </tr></tbody>
            
        <?php endforeach; ?>
            </table>
        </div>
    </div>
    </div>
    <script>
// Xử lý sự kiện cho tất cả nút có class action-btn


document.querySelectorAll('.action-btn').forEach(button => {
    button.addEventListener('click', function() {
        const action = this.dataset.action;
        const userId = this.dataset.id;
        
        if (action === 'delete') {
            // Hiển thị hộp thoại xác nhận
            Swal.fire({
        title: 'Bạn có chắc chắn?',
text: "Bạn sẽ không thể hoàn tác hành động này!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Xóa',
cancelButtonText: 'Hủy'
    })
            .then((result) => {
                if (result.isConfirmed) {
                    // Gọi AJAX để xóa
                    deleteUser(userId);
                }
            });
        } else if (action === 'update') {
            // Xử lý sửa ở đây
            alert('Chức năng sửa sẽ được thực hiện');
        }
    });
});

// Hàm gọi AJAX để xóa user
function deleteUser(userId) {
    // Hiển thị loading
    const deleteBtn = document.querySelector(`.action-btn[data-action="delete"][data-id="${userId}"]`);
    const originalText = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<span class="spinner">Đang xóa...</span>';
    deleteBtn.disabled = true;
    
    // Gọi API xóa
    fetch(`delete_user.php?id=${userId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        // Kiểm tra response có phải JSON không
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                throw new Error(text || 'Invalid response from server');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Xóa dòng khỏi bảng
            document.querySelector(`tr[data-id="${userId}"]`).remove();
            
            Swal.fire(
                'Đã xóa!',
                'Tài khoản đã được xóa thành công.',
                'success'
            );
        } else {
            throw new Error(data.message || 'Không thể xóa tài khoản');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire(
            'Lỗi!',
            error.message || 'Đã xảy ra lỗi khi xóa tài khoản',
            'error'
        );
    })
    .finally(() => {
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
    });
}
</script>
</body>
</html>


