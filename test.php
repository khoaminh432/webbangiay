<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form id="addUserForm">
        <label for="username">Username:</label>
        <input type="text" id="addusername" name="username" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="addemail" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="addpassword" name="password" required>
    </div>
    <div class="form-group">
        <label>Status:</label>
        <label><input type="radio" name="status" value="1" checked> Active</label>
        <label><input type="radio" name="status" value="0"> Inactive</label>
    </div>
    <button type="submit">Add User</button>
</form>
<script>
document.getElementById("addUserForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Ngăn reload trang

    const form = e.target;
    const formData = new FormData(form);

    fetch("add_user.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Thêm người dùng thành công!");
            form.reset(); // ❗ Reset lại form
        } else {
            alert("Thêm thất bại: " + data.message);
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
        alert("Đã xảy ra lỗi khi thêm.");
    });
});
</script>

</body>
</html>
