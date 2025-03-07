<?php
include '../init.php';

$title = "Chi tiết Sách";
$message = "";
$display = "";
$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : "";

if ($book_id != "") {

    $sql = "SELECT * FROM m_books WHERE book_id = '{$book_id}'ORDER BY book_id ASC";
    $book_detail = $cm_pg->select_one($sql);

    if ($book_detail) {
        $title = $book_detail['book_name'];
        $published_year_disp = data2input($book_detail['published_year']);
        $book_price_disp = number_format($book_detail['book_price']) . ".000đ";
    } else {
        $display = "hidden";
        $message = "Không tìm thấy dữ liệu sách.";
    }
} else {
    $display = "hidden";
    $message = "ID sách không hợp lệ.";
}

$content = <<<EOT
    <span class="detail-error-message">{$message}</span>
    <h2>Thông tin: <span>{$book_detail['book_name']}</span></h2>

    <!-- Container chứa ảnh và bảng -->
    <div class="detail-container">
        <!-- Phần ảnh -->
        <div class="detail-image">
            <img src="{$book_detail['book_img']}" alt="{$book_detail['book_name']}" height="300px">
        </div>

        <!-- Bảng thông tin sách -->
        <table class="detail-table {$display}">
            <tr>
                <td><b>ID sách</b></td>
                <td>{$book_detail['book_id']}</td>
            </tr>
            <tr>
                <td><b>Mã sách</b></td>
                <td>{$book_detail['detail_id']}</td>
            </tr>
            <tr>
                <td><b>Tên sách</b></td>
                <td>{$book_detail['book_name']}</td>
            </tr>
            <tr>
                <td><b>Thể loại</b></td>
                <td>{$book_detail['book_type']}</td>
            </tr>
            <tr>
                <td><b>Tác giả</b></td>
                <td>{$book_detail['author']}</td>
            </tr>
            <tr>
                <td><b>Năm xuất bản</b></td>
                <td>{$published_year_disp}</td>
            </tr>
            <tr>
                <td><b>Giá bán</b></td>
                <td>{$book_price_disp}</td>
            </tr>
            <tr>
                <td>Tình trạng</td>
                <td>{$stop_flg_arr[$book_detail['stop_flg']]}</td>
            </tr>
        </table>
        <div class="detail-buttons">
            <a href="product" class="detail-back-button {$display}">Quay lại</a>
            <a href="update.php?book_id={$book_detail['book_id']}" class="detail-add-button {$display}">Sửa thông tin</a>
            <a href="delete.php?book_id={$book_detail['book_id']}" class="detail-delete-button {$display}" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này không?')">Xóa sách</a>
        </div>
    </div>
    <div class="detail-block"></div>
    <script>
        // JavaScript để quay lại trang trước
        function goBack() {
            window.history.back();
        }
    </script>
EOT;



echo header_admin($title);
echo $content;
echo footer_admin();
?>
