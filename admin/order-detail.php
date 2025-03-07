<?php
include("../init.php");
$title = "Chi tiết Đơn Hàng";
// Lấy `order_id` từ URL
$order_id = $_GET['order_id'] ?? 0;

// Kiểm tra và lấy thông tin đơn hàng
$order = $cm_pg->select_one("SELECT * FROM m_orders WHERE order_id = ?", [$order_id]);
if (!$order) {
    die("Không tìm thấy đơn hàng.");
}

// Lấy thông tin người dùng từ `user_id` trong đơn hàng
$user = $cm_pg->select_one("SELECT * FROM m_users WHERE user_id = ?", [$order['user_id']]);
if (!$user) {
    $user['username']="Không đăng nhập";
} else {
    $d_user = $cm_pg->select_one("SELECT * FROM d_users WHERE user_id = ?", [$order['user_id']]);
}
//Status tiếng việt
$status= "";
if($order['status']=='pending'){
    $status="Đang xử lý";
} else if($order['status']=='completed') {
    $status="Hoàn thành";
} else {
    $status="Đã hủy";
}
// Lấy danh sách chi tiết sản phẩm trong đơn hàng
$sql = "
    SELECT od.*, b.book_name, b.book_img 
    FROM d_orders od
    LEFT JOIN m_books b ON od.book_id = b.book_id
    WHERE od.order_id = ?
";
$d_orders = $cm_pg->execute($sql, [$order_id]);

$row_html = "";
$total_items = 0;

// Duyệt qua danh sách chi tiết sản phẩm và tạo dòng hiển thị
foreach ($d_orders as $detail) {
    $book_id = $detail['book_id'];
    $total_items += $detail['count'];
    $book_img = $detail['book_img'];
    $book_name = $detail['book_name'];
    $count = (int)$detail['count'];
    $price = number_format($detail['price'], 0) . ".000đ";

    $row_html .= <<<EOT
    <tr class="book-row">
        <td><a href="detail?book_id=$book_id"><img src="{$book_img}" alt="{$book_name}" width="100"><a/></td>
        <td>{$book_name}</td>
        <td>{$count}</td>
        <td>{$price}</td>
    </tr>
EOT;
}

// Tạo nội dung HTML
$content = <<<EOT
<div class="book-list-container">
    <h1>CHI TIẾT ĐƠN HÀNG #{$order_id}</h1>

    <!-- Thông tin chung -->
    <div class="order-info">
        <p><strong>Tài khoản:</strong> {$user['username']}</p>
        <p><strong>Tên khách hàng:</strong> {$d_user['name']}</p>
        <p><strong>Tổng số sản phẩm:</strong> {$total_items}</p>
        <p><strong>Tổng tiền:</strong> {$order['total_amount']}.000đ</p>
        <p><strong>Trạng thái:</strong> $status</p>
        <p><strong>Ngày tạo:</strong> {$order['created_at']}</p>
    </div>

    <!-- Danh sách chi tiết sản phẩm -->
    <table class="book-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sách</th>
                <th>Số lượng</th>
                <th>Giá</th>
            </tr>
        </thead>
        <tbody>
            {$row_html}
        </tbody>
    </table>
</div>
<div class="block h50"></div>
EOT;

// Hiển thị nội dung
echo header_admin($title);
echo $content;
echo footer_admin();
?>
