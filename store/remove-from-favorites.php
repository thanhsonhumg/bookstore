<?php
session_start();
include("../init.php"); // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost/bookstore/login/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$book_id = $_GET['book_id']; // Lấy book_id từ URL

// Kiểm tra nếu book_id hợp lệ
if (isset($book_id) && is_numeric($book_id)) {
    // Xóa sách khỏi danh sách yêu thích
    $sql = "DELETE FROM favorites WHERE user_id = ? AND book_id = ?";
    $result = $cm_pg->execute($sql, [$user_id, $book_id]);

    // Chuyển hướng về trang danh sách yêu thích sau khi xóa
    if ($result) {
        header("Location: favorites.php");
        exit;
    } else {
        echo "<script>alert('Không thể xóa sách khỏi danh sách yêu thích.'); window.location.href = 'favorites.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID sách không hợp lệ.'); window.location.href = 'favorites.php';</script>";
    exit;
}
?>
