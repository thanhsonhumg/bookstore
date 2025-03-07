<?php
include("../init.php");

$message = "";

// Kiểm tra book_id từ URL
if (isset($_GET['book_id']) && !empty($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Lấy thông tin sách để kiểm tra và xóa ảnh nếu có
    $sql = "SELECT book_img FROM m_books WHERE book_id = '{$book_id}';";
    $book = $cm_pg->select_one($sql);

    if ($book) {
        // Xóa ảnh liên quan nếu tồn tại
        if (!empty($book['book_img']) && file_exists($book['book_img'])) {
            unlink($book['book_img']);
        }

        // Xóa dữ liệu sách từ database
        $sql = "DELETE FROM m_books WHERE book_id = '{$book_id}';";
        $result = $cm_pg->execute($sql);

        if ($result) {
            $message = "alert('Xóa sách thành công.');";
        } else {
            $message = "alert('Xóa sách thất bại.');";
        }
    } else {
        $message = "alert('Không tìm thấy sách cần xóa.');";
    }
} else {
    $message = "alert('Thiếu thông tin book_id.');";
}

// Điều hướng quay lại trang chính
echo "<script>{$message} window.location.href='product.php';</script>";
exit;
