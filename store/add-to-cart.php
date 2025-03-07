<?php
session_start();
include("../init.php"); // Đảm bảo đã bao gồm kết nối DB
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? null;
    $count = $_POST['count'] ?? 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['count'] += $count;
    } else {
        $_SESSION['cart'][$book_id] = [
            'book_id' => $book_id,
            'count' => $count,
        ];
    }

    // Kiểm tra nếu có đơn hàng đang tồn tại, nếu không thì tạo mới
    $order_id = $_SESSION['order_id'] ?? 0;
    if (!$order_id) {
        // Tạo đơn hàng mới trong bảng `m_orders`
        $user_id = $_SESSION['user_id'] ?? 'Khách'; // Nếu người dùng chưa đăng nhập, tạo dưới dạng khách vãng lai
        $total_amount = 0; // Tạm thời chưa tính tổng, sẽ tính sau khi có chi tiết
        $status = 'pending'; // Trạng thái mặc định là "chờ xử lý"

        $order_sql = "INSERT INTO m_orders (user_id, total_amount, status) VALUES (?, ?, ?)";
        $cm_pg->execute($order_sql, [$user_id, $total_amount, $status]);

        // Lấy ID đơn hàng vừa tạo
        $order_id = $cm_pg->cn->insert_id; // Sử dụng thuộc tính insert_id của mysqli
        $_SESSION['order_id'] = $order_id; // Lưu vào session để theo dõi
    }

    // Lấy thông tin chi tiết sách
    $book_info = $cm_pg->select_one("SELECT * FROM m_books WHERE book_id = ?", [$book_id]);
    if ($book_info) {
        $book_name = $book_info['book_name'];
        $book_price = $book_info['book_price'];
        $subtotal = $book_price * $count;

        // Bước 2: Thêm chi tiết đơn hàng vào bảng `d_orders`
        $d_orders_sql = "INSERT INTO d_orders (order_id, book_id, book_name, book_price, count, subtotal) 
                             VALUES (?, ?, ?, ?, ?, ?)";
        $cm_pg->execute($d_orders_sql, [$order_id, $book_id, $book_name, $book_price, $count, $subtotal]);

        // Cập nhật lại tổng tiền của đơn hàng
        $update_total_sql = "UPDATE m_orders SET total_amount = (SELECT SUM(subtotal) FROM d_orders WHERE order_id = ?) WHERE order_id = ?";
        $cm_pg->execute($update_total_sql, [$order_id, $order_id]);

        echo json_encode(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sách!']);
    }

    exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
error_log("AJAX request received for book_id: " . $book_id);
?>
