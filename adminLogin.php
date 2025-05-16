<?php session_start();
require_once __DIR__ . "/dao/AdminDao.php";

// Xử lý form đăng nhập
$message = '';
$messageType = ''; // 'success' hoặc 'error'
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $adminDTO = $table_admins->login($email, $password);
    if ($adminDTO!=false) {
        $_SESSION['email'] = $adminDTO->email;
        $_SESSION["password"] = $adminDTO->password;
        $message = 'Đăng nhập thành công!';
        $messageType = 'success';
        // Có thể chuyển hướng sau 2s
        header("Location: admin.php");
    } else {
        $message = 'Email hoặc mật khẩu không đúng.';
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #141e30, #243b55);
      height: 100vh; display: flex; justify-content: center; align-items: center; }
    .login-wrapper {
      background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 20px;
      padding: 40px; width: 360px; box-shadow: 0 0 25px rgba(0,0,0,0.3); color: #fff;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); }}
    .login-wrapper h2 { text-align: center; margin-bottom: 30px; font-weight: 600; }
    .form-group { position: relative; margin-bottom: 24px; }
    .form-group input {
      width: 100%; padding: 14px 44px 14px 18px; border-radius: 12px;
      background-color: rgba(255,255,255,0.08); border: none; font-size: 15px;
      color: #fff; outline: none; transition: all 0.3s ease;
    }
    .form-group input:focus { background-color: rgba(255,255,255,0.12); }
    .form-group i { position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
      color: #bbb; pointer-events: none; }
    .message {
      text-align: center; padding: 12px; margin-bottom: 20px;
      border-radius: 8px; font-size: 14px;
    }
    .message.success { background-color: rgba(40,167,69,0.2); color: #28a745; }
    .message.error { background-color: rgba(220,53,69,0.2); color: #dc3545; }
    .login-wrapper button {
      width: 100%; padding: 14px; border: none; border-radius: 12px;
      background: linear-gradient(135deg, #00b4db, #0083b0);
      font-size: 16px; font-weight: 600; color: white; cursor: pointer;
      transition: background 0.3s ease;
    }
    .login-wrapper button:hover { background: linear-gradient(135deg, #0083b0, #00b4db); }
    .footer-text { text-align: center; font-size: 13px; color: #ccc; margin-top: 20px; }
    .footer-text a { color: #00b4db; text-decoration: none; }
    .footer-text a:hover { text-decoration: underline; }
    @media (max-width: 420px) { .login-wrapper { width: 90%; padding: 30px; }}
  </style>
</head>
<body>
  <form class="login-wrapper" action="adminLogin.php" method="post">
    <h2>Chào mừng trở lại 👋</h2>
    <?php if ($message): ?>
      <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="form-group">
      <input type="email" name="email" id="email" placeholder="Email" required />
      <i class="fas fa-envelope"></i>
    </div>
    <div class="form-group">
      <input type="password" name="password" id="password" placeholder="Mật khẩu" required />
      <i class="fas fa-lock"></i>
    </div>
    <button type="submit" name="login">Đăng nhập</button>
    
  </form>
</body>
</html>
