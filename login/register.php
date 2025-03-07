<?php
include("../init.php");
$title = "Đăng nhập";
$content =<<<content
<link rel="stylesheet" href="../css/style1.css">
<link rel="stylesheet" href="../css/login.css">
    <div class="login-container">
        <h1>ĐĂNG KÝ</h1>
        <form class="form-login" action="register-process.php" method="POST" onsubmit="return validateRegister()">
            <div class="form-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" class="username" id="username" name="username" placeholder="Tài khoản" required><br>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" class="password" id="password" name="password" placeholder="Mật khẩu" required><br>
            </div>
            <div class="form-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" id="confirm_password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>
            <button type="submit" class="btn-login">Đăng ký</button>
        </form>
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
content;

echo header_normal($title);
echo $content;
