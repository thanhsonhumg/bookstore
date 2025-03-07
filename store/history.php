<?php
// Khởi động session và kiểm tra người dùng đăng nhập
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit();
}

// Kết nối cơ sở dữ liệu
require_once '../init.php';

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu đơn hàng
$query = "SELECT o.order_id, o.order_date, o.total_amount, o.status
          FROM m_orders o
          WHERE o.user_id = :user_id
          ORDER BY o.created_at DESC";
$orders = $cm_pg->select_one($query, ['user_id' => $user_id]);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Lịch sử mua hàng</h1>
        <?php if (!empty($orders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td><?= number_format($order['total_amount'], 0, ',', '.') ?>₫</td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><a href="order-detail.php?order_id=<?= $order['order_id'] ?>">Xem</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Bạn chưa có đơn hàng nào!</p>
        <?php endif; ?>
    </div>
</body>
</html>
