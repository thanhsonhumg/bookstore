<?php
require '../init.php'; // Kết nối cơ sở dữ liệu

if (isset($_GET['book_id'])) {
    $bookId = intval($_GET['book_id']);
    
    // Lấy thông tin sách từ cơ sở dữ liệu
    $sql = "SELECT * FROM m_books WHERE book_id = ?";
    $book = $cm_pg->select_one($sql, [$bookId]);
    
    if ($book): // Nếu tìm thấy sách
        $published_year_disp = data2input($book['published_year']);
?>
<body>
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="row g-0">
                <!-- Hình ảnh sách -->
                <div class="col-md-5">
                    <img src="http://localhost/bookstore/admin/<?php echo htmlspecialchars($book['book_img']); ?>" 
                         alt="<?php echo htmlspecialchars($book['book_name']); ?>" 
                         class="img-fluid rounded-start w-100">
                </div>
                <!-- Nội dung chi tiết sách -->
                <div class="col-md-7">
                    <div class="card-body">
                        <h3 class="card-title text-primary mb-3"><?php echo htmlspecialchars($book['book_name']); ?></h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Mã sách:</strong> <?php echo $book['detail_id']; ?></li>
                            <li class="list-group-item"><strong>Tác giả:</strong> <?php echo $book['author']; ?></li>
                            <li class="list-group-item"><strong>Thể loại:</strong> <?php echo $book['book_type']; ?></li>
                            <li class="list-group-item"><strong>Năm xuất bản:</strong> <?php echo $published_year_disp; ?></li>
                            <li class="list-group-item"><strong>Giá:</strong> <?php echo number_format($book['book_price']); ?>.000đ</li>
                        </ul>
                        <div class="mt-4">
                            <h5 class="text-secondary"><strong>Mô tả:</strong></h5>
                            <p><?php echo nl2br(htmlspecialchars($book['book_desc'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    else: // Không tìm thấy sách
        echo "<div class='container my-5'><p class='text-danger text-center'>Không tìm thấy thông tin sách.</p></div>";
    endif;
} else {
    echo "<div class='container my-5'><p class='text-warning text-center'>Không có ID sách được cung cấp.</p></div>";
}
?>
