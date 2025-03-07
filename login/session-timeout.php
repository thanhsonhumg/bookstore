<?php
session_start();

// Danh sách các trang không yêu cầu đăng nhập
$excluded_pages = [
    'http://localhost/bookstore/index.php',   // Trang chủ
    'http://localhost/bookstore/login/login.php',   // Trang đăng nhập
    'http://localhost/bookstore/login/register.php' // Trang đăng ký
];

// Lấy URL hiện tại
$current_page = $_SERVER['PHP_SELF'];

// Kiểm tra nếu URL hiện tại nằm trong danh sách loại trừ
if (!in_array($current_page, $excluded_pages)) {
    // Kiểm tra session đăng nhập
    if (!isset($_SESSION['0'])) {
        header('Location: /bookstore/login/login.php?message=Đăng nhập để tiếp tục!');
        exit;
    }

    // Kiểm tra thời gian hoạt động
    if (time() - $_SESSION['last_activity'] > 3600) { // 3600 giây = 1 giờ
        session_destroy();
        header('Location: /bookstore/login/login.php?message=Phiên đăng nhập đã hết hạn!');
        exit;
    }
    $_SESSION['last_activity'] = time(); // Cập nhật thời gian hoạt động
}
?>
