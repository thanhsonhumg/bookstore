<?php
include("../init.php");
session_start();
$title = "Đăng nhập";
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$csrf_token = $_SESSION['csrf_token']; // Lưu token vào biến để sử dụng trong form
$content = <<<content
<link rel="stylesheet" href="../css/style1.css">
<link rel="stylesheet" href="../css/login.css">
<div class="login-container">
    <h1>ĐĂNG NHẬP</h1>
    <form class="form-login" action="login-process.php" method="POST">
        <div class="form-group">
            <i class="fa-solid fa-user"></i>
            <input type="text" class="username" id="username" name="username" placeholder="Tài khoản" required><br>
        </div>
        <div class="form-group">
            <i class="fa-solid fa-key"></i>
            <input type="password" class="password" id="password" name="password" placeholder="Mật khẩu" required><br>
        </div>
        <input type="hidden" name="csrf_token" value="$csrf_token">
        <button type="submit" class="btn-login">Đăng nhập</button>
        <div class="form-group">
            <div class="form-group">
                <a href="forgot_password.php">Quên mật khẩu?</a>
            </div>
        </div>
    </form>
    <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    <p style="color: red; text-align: center;">$message</p>
</div>
content;

echo header_normal($title);
echo $content;
?>
