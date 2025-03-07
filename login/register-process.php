<?php
include "../common/connect.php";

mysqli_set_charset($conn, "utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Bước 1: Kiểm tra xem tài khoản đã tồn tại chưa
    $stmt = mysqli_prepare($conn, "SELECT * FROM m_users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Nếu tài khoản đã tồn tại
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "Tên tài khoản đã tồn tại!";
    } else {

        // Bước 3: Chuẩn bị câu lệnh SQL để chèn dữ liệu vào bảng `m_users`
        $sql = "INSERT INTO m_users (username, password) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($conn, $sql);

        // Gán giá trị tài khoản và mật khẩu vào câu lệnh SQL
        mysqli_stmt_bind_param($insert_stmt, "ss", $username, $password);

        // Thực thi câu lệnh và kiểm tra kết quả
        if (mysqli_stmt_execute($insert_stmt)) {
            echo "Đăng ký thành công!";
            echo '<br><a href="login.php">Quay lại trang đăng nhập.</a>';
        } else {
            echo "Đã xảy ra lỗi: " . mysqli_stmt_error($insert_stmt);
        }

        // Đóng câu lệnh chèn
        mysqli_stmt_close($insert_stmt);
    }


    // Đóng câu lệnh kiểm tra tài khoản và kết nối
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
