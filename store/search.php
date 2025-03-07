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

// Tạo HTML cho danh sách sách
$row_html = "";

foreach ($books as $book) {
    $book_id = $book['book_id'];
    $book_name = htmlspecialchars($book['book_name']);
    $book_img = "../admin/" . $book['book_img'];
    $price = number_format($book['book_price'], 0) . ",000đ";
    $old_price = number_format($book['book_price'] * 1.1, 0) . ",000đ";

    $row_html .= <<<EOT
    <div class="box">
        <span class="discount">-10%</span>
        <div class="image">
            <img src="{$book_img}" alt="{$book_name}">
            <div class="icons">
                <a title="Yêu thích" href="#" class="fas fa-heart favorite-btn" data-id="$book_id"></a>
                <button 
                    class="cart-btn" 
                    data-id="{$book_id}" 
                    data-name="{$book_name}">
                    Thêm vào giỏ hàng
                </button>
                <a href="#" 
                    class="fa-regular fa-eye view-book" 
                    data-id="{$book_id}" 
                    data-bs-toggle="modal" 
                    data-bs-target="#bookDetailModal">
                </a>
            </div>
        </div>
        <div class="content">
            <h3>{$book_name}</h3>
            <div class="price">
                {$price} <span>{$old_price}</span>
            </div>
        </div>
    </div>
    <!-- Modal để hiển thị thông tin chi tiết sách -->
    <div class="modal fade" id="bookDetailModal" tabindex="-1" aria-labelledby="bookDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookDetailModalLabel">Chi tiết sách</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="book-info">Đang tải thông tin sách...</p>
                </div>
            </div>
        </div>
    </div>
EOT;
}

// Phần hiển thị kết quả
$results_summary = $book_count > 0 
    ? "<div class='search-summary'><p>Số lượng sách tìm thấy: <strong>{$book_count}</strong></p></div>"
    : "<div class='search-summary'><p>Không tìm thấy sách nào với từ khóa: <strong>{$search_query}</strong></p></div>";

$content = <<<EOT
<main>
<section class="products" id="products">
    <h1 class="heading">Kết quả tìm kiếm cho "<span>{$search_query}</span>"</h1>
    <div class="search-summary">
        {$results_summary}
    </div>
    <div class="box-container">
        {$row_html}
    </div>
</section>
</main>
EOT;

// Hiển thị nội dung
echo header_normal($title);
echo $content;
?>
