<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to the website</title>
    <link rel="stylesheet" href="../css/login_signup.css">
</head>
<body>
    <div class="wrapper">
        <div class="form-box login">
            <h2>Đăng nhập</h2>
            <form action="login">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Mật khẩu</label>
                </div> 
                <div class="remember-forgot">
                    <a href="#">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="btn">Đăng nhập</button>
                <div class="login-register">
                    <p>Chưa có tài khoản? <a href="#" class="register-link"> Đăng ký</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <h2>Đăng ký</h2>
            <form action="register">
                <div class="input-box">
                    <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                    <input type="text" name="username" required>
                    <label>Tên người dùng: </label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                    <input type="password" required name="password">
                    <label>Mật khẩu: </label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                    <input type="password" required name="confirm_password">
                    <label>Xác nhận mật khẩu: </label>
                </div>  
                <button type="submit" class="btn">Đăng ký</button>
                <div class="login-register">
                    <p>Đã có tài khoản? <a href="#" class="login-link"> Đăng nhập</a>
                    </p>
                </div>
            </form>
        </div>
    </div> 

    <script src="../js/login_signup.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>