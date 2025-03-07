<?php
include "../init.php"; // Kết nối database và cấu hình

$title = "Kết quả Tìm Kiếm";
$dot1 = ".";

// Lấy từ khóa tìm kiếm từ URL
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Khởi tạo danh sách sách
$books = [];
$book_count = 0;

if (!empty($search_query)) {
    // Xử lý chuỗi tìm kiếm để tránh lỗi SQL Injection
    $search_query_escaped = $cm_pg->cn->real_escape_string($search_query);

    // Truy vấn danh sách sách
    $sql = "SELECT * FROM m_books 
            WHERE book_name LIKE '%$search_query_escaped%' 
            OR author LIKE '%$search_query_escaped%' 
            OR book_type LIKE '%$search_query_escaped%' 
            ORDER BY book_name ASC";
    $result = $cm_pg->execute($sql);

    // Lưu kết quả truy vấn vào mảng
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $books[] = $row;
        $book_count++;
    }
}

// Tạo HTML cho bảng danh sách sách
$row_html = "";
foreach ($books as $book) {
    $book_id = $book['book_id'];
    $detail_id = $book['detail_id'];
    $book_name = htmlspecialchars($book['book_name']);
    $book_img = $book['book_img'];
    $book_price = number_format($book['book_price'], 0) . ".000đ";
    $author = htmlspecialchars($book['author']);
    $published_year = data2input($book['published_year']);
    $book_type = htmlspecialchars($book['book_type']);
    $stop_flg = $book['stop_flg'] + 0;

    // Trạng thái sách
    $book_status_class = $stop_flg === 1 ? "red" : "green";
    $status_text = $stop_flg === 1 ? "Ngừng bán" : "Đang bán";

    $row_html .= <<<EOT
    <tr class="book-row">
        <td>{$book_id}</td>
        <td>{$detail_id}</td>
        <td><a href="detail.php?book_id={$book_id}"><img src="{$book_img}" alt="{$book_name}" width="100"></a></td>
        <td>{$book_name}</td>
        <td>{$book_price}</td>
        <td>{$author}</td>
        <td>{$published_year}</td>
        <td>{$book_type}</td>
        <td><span class="{$book_status_class}">{$status_text}</span></td>
        <td>
            <a class="a-detail" href="detail.php?book_id={$book_id}">Xem</a>
            <a class="a-update" href="update.php?book_id={$book_id}">Sửa</a>
            <a class="a-delete" href="delete.php?book_id={$book_id}" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này không?')">Xóa</a>
        </td>
    </tr>
EOT;
}

// Phần hiển thị kết quả tìm kiếm
$results_summary = $book_count > 0 
    ? "<div class='book-count'>Số lượng sách tìm thấy: <strong>{$book_count}</strong></div>"
    : "<div class='book-count'>Không tìm thấy sách nào với từ khóa: <strong>{$search_query}</strong></div>";

// Tạo nội dung HTML chính
$content = <<<EOT
<div class="book-list-container">
    <h1>Kết quả tìm kiếm cho "<span>{$search_query}</span>"</h1>
    <div class="btn-add-container">
        <a href="add.php" class="btn-add">Thêm sách mới</a><br>
    </div>
    {$results_summary}
    <table class="book-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã sách</th>
                <th>Ảnh</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>Tác giả</th>
                <th>Năm xuất bản</th>
                <th>Thể loại</th>
                <th>Tình trạng</th>
                <th>Thao tác</th>
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
