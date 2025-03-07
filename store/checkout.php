<?php
session_start();
include("../init.php"); // Đảm bảo đã bao gồm kết nối DB
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra giỏ hàng
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        echo "<script>alert('Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');</script>";
        exit;
    }

    // Kiểm tra kết nối DB
    if (!$cm_pg) {
        echo "<script>alert('Lỗi kết nối cơ sở dữ liệu.');</script>";
        exit;
    }

    // Lấy địa chỉ nhận hàng từ form
    $shipping_address = $_POST['shipping_address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $shipping_address = htmlspecialchars($shipping_address, ENT_QUOTES, 'UTF-8');

    if (empty($shipping_address)) {
        echo "<script>alert('Vui lòng nhập địa chỉ nhận hàng.');</script>";
        exit;
    }
    if (empty($phone)) {
        echo "<script>alert('Vui lòng nhập số điện thoại.');</script>";
        exit;
    }

    // Tính tổng tiền từ giỏ hàng
    $total_amount = 0;
    foreach ($cart as $book_id => $item) {
        $book_info = $cm_pg->select_one("SELECT book_price FROM m_books WHERE book_id = ?", [$book_id]);
        if ($book_info) {
            $total_amount += $book_info['book_price'] * $item['count'];
        }
    }

    // Tạo đơn hàng
    $user_id = $_SESSION['user_id'] ?? 0; // Sử dụng 0 cho khách vãng lai
    $order_sql = "INSERT INTO m_orders (user_id, total_amount, shipping_address,phone, status) VALUES ('$user_id', '$total_amount', '$shipping_address','$phone', 'pending')";
    $cm_pg->execute($order_sql);
    $order_id = $cm_pg->cn->insert_id; // Lấy ID đơn hàng vừa tạo

    
    if (!$order_id) {
        echo "<script>alert('Không thể tạo đơn hàng. Vui lòng thử lại sau.');</script>";
        exit;
    }

    // Thêm chi tiết đơn hàng vào bảng d_orders
    foreach ($cart as $book_id => $item) {
        $count = $item['count'];
        $book_info = $cm_pg->select_one("SELECT book_price FROM m_books WHERE book_id = $book_id");

        if ($book_info) {
            $price = $book_info['book_price'];
            $result = $cm_pg->execute(
                "INSERT INTO d_orders (order_id, book_id, count, price) VALUES ('$order_id', '$book_id', '$count', '$price')"
            );

            if (!$result) {
                error_log("Không thể thêm chi tiết đơn hàng cho book_id: $book_id - Lỗi: " . $cm_pg->cn->error);
            }
        } else {
            error_log("Không tìm thấy thông tin sách cho book_id: $book_id");
        }
    }

    // Xóa giỏ hàng
    unset($_SESSION['cart']);

    // Thông báo thành công và chuyển hướng
    echo "<script>
        alert('Đặt hàng thành công!');
        window.location.href = '../index';
    </script>";
    exit;
}

// Nếu không phải POST
http_response_code(400);
echo "<script>alert('Yêu cầu không hợp lệ.');</script>";

?>
