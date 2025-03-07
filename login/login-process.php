<?php
session_start();
require '../init.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $csrf_token = $_POST['csrf_token'];


    if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    unset($_SESSION['csrf_token']);

    $sql = "SELECT user_id, username, password, role FROM m_users WHERE username = '$username'";
    $user = $cm_pg->select_one($sql);

    if ($user) {
        if ($password = $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            if ($user['role'] == 1) {
                header("Location: http://localhost/bookstore/admin/dashboard.php");
            } else {
                header("Location: http://localhost/bookstore/index.php");
            }
            exit;
        } else {
            // Sai mật khẩu
            header("Location: login.php?message=Sai tên đăng nhập hoặc mật khẩu");
            exit;
        }
    } else {
        // Tài khoản không tồn tại
        header("Location: login.php?message=Sai tên đăng nhập hoặc mật khẩu");
        exit;
    }
} else {
    // Truy cập không hợp lệ
    header("Location: login.php");
    exit;
}
