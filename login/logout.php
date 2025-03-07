<?php
// Bắt đầu phiên làm việc
session_start();

// Hủy tất cả các biến phiên
$_SESSION = array();

// Nếu bạn muốn hủy phiên hoàn toàn, hãy xóa cookie phiên
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Cuối cùng, hủy phiên
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập
header("Location: ../login/login.php");
exit();
?>
