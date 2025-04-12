<link rel="stylesheet" href="css/admin_style/form/form_style.css">
<div class="formadd-object-container column hidden">
<h1>Add New User</h1>
    <form class="add-form" action="../table/account_management.php" method="POST">
        <div class="form-group">
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
            <div class="status-options">
                <label>
                    <input type="radio" name="status" value="1" checked> Active
                </label>
                <label>
                    <input type="radio" name="status" value="0"> Inactive
                </label>
            </div>
        </div>
        
        <button type="submit">Add User</button>
    </form>
</div>