<?php
session_start();
include("../init.php"); // Kết nối cơ sở dữ liệu
$title = "Danh Sách Yêu Thích";

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION["user_id"])) {    
    echo "
        <script>
            alert('Vui lòng đăng nhập!'); 
            window.location.href = 'http://localhost/bookstore/login/login.php';
        </script>";
    exit;
}

$user_id = $_SESSION["user_id"];

// Truy vấn danh sách yêu thích
$sql = "SELECT b.detail_id,b.book_id, b.book_name, b.book_img, b.book_price FROM favorites f JOIN m_books b ON f.book_id = b.book_id WHERE f.user_id = ?";
$favorites = $cm_pg->execute($sql, [$user_id]);

// Kiểm tra nếu có sản phẩm trong danh sách yêu thích
if (!empty($favorites)) {
    $row_html = "";
    $total_amount = 0;

    // Duyệt qua danh sách yêu thích
    foreach ($favorites as $book) {
        $book_id = $book['book_id'];
        $detail_id = htmlspecialchars($book['detail_id']);
        $book_name = htmlspecialchars($book['book_name']);
        $book_img = htmlspecialchars($book['book_img']);
        $book_price = $book['book_price'];
        $total_amount += $book_price;

        // Tạo dòng hiển thị danh sách yêu thích
        $row_html .= <<<EOT
        <tr class="favorite-item-row">
            <td>{$detail_id}</td>
            <td>{$book_id}</td>
            <td><img src="../admin/{$book_img}" alt="{$book_name}" width="100"></td>
            <td>{$book_name}</td>
            <td>{$book_price}.000đ</td>
            <td>
                <a href="remove-from-favorites.php?book_id={$book_id}" class="remove-item">Xóa</a>
                <button class="cart-btn" data-id="{$book_id}">Thêm vào giỏ hàng</button>
            </td>
        </tr>
EOT;
    }

    // Tạo nội dung HTML danh sách yêu thích
    $content = <<<EOT
    <link rel="stylesheet" href="../css/style1.css">
    <script src="../js/script.js"></script>
    <div class="cart-container">
        <h1>Danh Sách Yêu Thích</h1>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Mã sách</th>
                    <th>ID sách</th>
                    <th>Ảnh</th>
                    <th>Tên sách</th>
                    <th>Giá</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                {$row_html}
            </tbody>
        </table>
        <div class="total-amount">
            <p>Tổng cộng: <strong>{$total_amount}.000đ</strong></p>
        </div>
        <div class="favorites-actions">
            <a href="http://localhost/bookstore/index.php#products" class="btn">Tiếp tục mua sắm</a>
        </div>
    </div>
EOT;

} else {
    // Nếu không có sách nào trong danh sách yêu thích
    $content = <<<EOT
    <link rel="stylesheet" href="../css/style1.css">
    <script src="../js/script.js"></script>
    <div class="cart-container">
        <h1>Danh Sách Yêu Thích</h1>
        <div class="no-favorites">
            <p>Không có sách nào trong danh sách yêu thích.</p>
        </div>
        <div class="favorites-actions">
            <a href="../index.php" class="btn">Tiếp tục mua sắm</a>
        </div>
    </div>
EOT;
}

echo header_normal($title);
echo $content;
?>
