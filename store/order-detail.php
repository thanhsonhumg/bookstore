<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit();
}

require_once '../init.php';

if (!isset($_GET['order_id'])) {
    header('Location: history.php');
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Kiểm tra xem đơn hàng có thuộc về user không
$query = "SELECT o.order_id, o.created_at, o.total_amount, o.status
          FROM m_orders o
          WHERE o.order_id = :order_id AND o.user_id = :user_id";
$order = $cm_pg->select_one($query, ['order_id' => $order_id, 'user_id' => $user_id]);

if (!$order) {
    header('Location: history.php');
    exit();
}

// Lấy thông tin chi tiết đơn hàng
$query_details = "SELECT od.book_id, b.title, od.count, od.price
                  FROM d_orders od
                  JOIN m_books b ON od.book_id = b.book_id
                  WHERE od.order_id = :order_id";
$order_details = $cm_pg->select_one($query_details, ['order_id' => $order_id]);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Chi tiết đơn hàng #<?= htmlspecialchars($order['order_id']) ?></h1>
        <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
        <p><strong>Tổng tiền:</strong> <?= number_format($order['total_amount'], 0, ',', '.') ?>₫</p>
        <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>

        <h2>Sản phẩm:</h2>
        <table>
            <thead>
                <tr>
                    <th>Mã sách</th>
                    <th>Tên sách</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_details as $detail): ?>
                    <tr>
                        <td><?= htmlspecialchars($detail['book_id']) ?></td>
                        <td><?= htmlspecialchars($detail['title']) ?></td>
                        <td><?= htmlspecialchars($detail['count']) ?></td>
                        <td><?= number_format($detail['price'], 0, ',', '.') ?>₫</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
