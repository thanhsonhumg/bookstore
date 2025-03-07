<?php
session_start();

// Kiểm tra nếu có `book_id` trong URL
$book_id = $_GET['book_id'] ?? null;

if ($book_id && isset($_SESSION['cart'][$book_id])) {
    // Xóa sản phẩm khỏi giỏ hàng
    unset($_SESSION['cart'][$book_id]);

    // Chuyển hướng về trang giỏ hàng
    header("Location: cart.php");
    exit;
} else {
    echo "Không tìm thấy sản phẩm cần xóa.";
}
?>
