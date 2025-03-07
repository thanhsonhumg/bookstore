<?php
session_start();
include("../init.php"); // Kết nối cơ sở dữ liệu
date_default_timezone_set('Asia/Ho_Chi_Minh');

$title = "Giỏ Hàng";

// Kiểm tra nếu người dùng đã đăng nhập
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href = 'http://localhost/bookstore/login/login.php';</script>";
    exit;
}

// Lấy thông tin người dùng từ bảng d_users
$sql = "SELECT * FROM d_users WHERE user_id = ?";
$user_info = $cm_pg->select_one($sql, [$user_id]);

if (!$user_info) {
    echo "<script>alert('Không tìm thấy thông tin người dùng.'); window.location.href = '../index';</script>";
    exit;
}

// Kiểm tra nếu giỏ hàng có sản phẩm
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $row_html = "";
    $total_amount = 0;

    // Duyệt qua các sản phẩm trong giỏ hàng
    foreach ($_SESSION['cart'] as $book_id => $item) {
        $count = $item['count'];

        // Lấy thông tin sách từ cơ sở dữ liệu
        $sql = "SELECT * FROM m_books WHERE book_id = ?";
        $book_info = $cm_pg->select_one($sql, [$book_id]);

        if ($book_info) {
            $detail_id = $book_info['detail_id'];
            $book_name = htmlspecialchars($book_info['book_name'], ENT_QUOTES, 'UTF-8');
            $book_img = htmlspecialchars($book_info['book_img'], ENT_QUOTES, 'UTF-8');
            $book_price = $book_info['book_price'];
            $total_price = $book_price * $count;
            $total_amount += $total_price;

            // Tạo dòng thông tin giỏ hàng
            $row_html .= <<<EOT
            <tr class="cart-item-row">
                <td>{$detail_id}</td>
                <td><img src="../admin/{$book_img}" alt="{$book_name}" width="100"></td>
                <td>{$book_name}</td>
                <td>{$count}</td>
                <td>{$book_price}.000đ</td>
                <td>{$total_price}.000đ</td>
                <td>
                    <a href="remove-from-cart.php?book_id={$book_id}" class="remove-item">Xóa</a>
                </td>
            </tr>
EOT;
        }
    }

    // Tạo nội dung HTML giỏ hàng
    $content = <<<EOT
    <link rel="stylesheet" href="../css/style1.css">
    <script src="../js/script.js"></script>
    <div class="cart-container">
        <h1>Giỏ Hàng</h1>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Mã sách</th>
                    <th>Ảnh</th>
                    <th>Tên sách</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng cộng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                {$row_html}
            </tbody>
        </table>
        <div class="total-amount">
            <p>Tổng tiền: <strong>{$total_amount}.000đ</strong></p>
        </div>
        <div class="checkout-btn">
            <div class="checkout-address">
                <h3>Thông tin giao hàng</h3>
                <p><strong>Địa chỉ:</strong> {$user_info['address']}</p>
                <p><strong>Số điện thoại:</strong> {$user_info['phone']}</p>
                <form action="checkout.php" method="POST">
                    <input type="hidden" name="shipping_address" value="{$user_info['address']}">
                    <input type="hidden" name="phone" value="{$user_info['phone']}">
                    <button type="submit" class="btn">Đặt hàng</button>
                </form>
            </div>
        </div>
    </div>
EOT;

} else {
    $content = <<<EOT
    <div class="cart-null">
        <p>Giỏ hàng của bạn đang trống.</p>
    </div>
EOT;
}

echo header_normal($title);
echo $content;
?>
