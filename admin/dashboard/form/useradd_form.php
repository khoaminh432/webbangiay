<?php require_once __DIR__."/../../../initAdmin.php";?>
<link rel="stylesheet" href="css/admin_style/form/addformuser_style.css">
<div class="formadd-object-container column hidden">
<h1>Add New User</h1>
<div class="close-form-btn"><ion-icon name="close-circle-outline"></ion-icon></div>
    <form class="add-form" >
        <input type="text" name="object-add-title" value="user" style="display:none;">
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
<script src="js/admin/Create_form.js"></script>