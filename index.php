<?php
include "init.php";
$title = "Trang chủ";
$dot = "..";
echo header_normal($title);
?>
<!-- section trang chủ -->
<section class="home" id="home">

    <div class="content">
        <h3>Sách</h3>
        <span>đọc và suy ngẫm</span>
        <p>Mỗi cuốn sách là một cuộc hành trình đến với một thế giới mới.</p>
        <a href="#products" class="btn">Mua sắm ngay</a>
    </div>
</section>
<!-- ------- -->

<!-- section giới thiệu -->
<section class="about" id="about">

    <h1 class="heading">Giới thiệu <span>về chúng tôi</span> </h1>

    <div class="row">

        <div class="video-container">
            <video src="./imgs/get-img/about-video.mp4" loop autoplay muted></video>
            <h3>Những cuốn sách bán chạy nhất</h3>
        </div>

        <div class="content">
            <h3>Tại sao bạn nên chọn chúng tôi?</h3>
            <p>Chúng tôi cam kết mang đến dịch vụ chăm sóc khách hàng chu đáo và hỗ trợ tận tình, sẵn sàng giải đáp mọi thắc mắc và giúp bạn tìm kiếm những cuốn sách phù hợp nhất.</p>
            <p>Kho sách đa dạng với nhiều thể loại phong phú, từ sách kinh điển đến sách mới xuất bản, đáp ứng mọi sở thích của độc giả với mức giá hợp lý và nhiều ưu đãi hấp dẫn.</p>
            <a href="#products" class="btn">Tìm hiểu thêm</a>
        </div>

    </div>

</section>
<!-- ------- -->

<!-- section icons -->
<section class="icons-container">
    <div class="icons">
        <img src="./imgs/get-img/icon1.png" alt="">
        <div class="info">
            <h3>Miễn phí vận chuyển</h3>
            <span>Cho tất cả các đơn hàng</span>
        </div>
    </div>
    <div class="icons">
        <img src="./imgs/get-img/icon2.png" alt="">
        <div class="info">
            <h3>Hoàn trả trong vòng 10 ngày</h3>
            <span>Đảm bảo hoàn tiền</span>
        </div>
    </div>
    <div class="icons">
        <img src="./imgs/get-img/icon3.png" alt="">
        <div class="info">
            <h3>Nhiều quà tặng hấp dẫn</h3>
            <span>Cho tất cả các đơn hàng</span>
        </div>
    </div>
    <div class="icons">
        <img src="./imgs/get-img/icon4.png" alt="">
        <div class="info">
            <h3>Thanh toán an toàn</h3>
            <span>Bảo mật thông tin</span>
        </div>
    </div>

</section>

<!-- ------- -->
<!-- section sản phẩm  -->
<?php
// Số sách hiển thị trên mỗi trang
$books_per_page = 12;

// Lấy trang hiện tại từ tham số GET, nếu không có thì mặc định là trang 1
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Tính toán vị trí bắt đầu của dữ liệu cho trang hiện tại
$offset = ($current_page - 1) * $books_per_page;

// Lấy tổng số sách từ database
$sql = "SELECT COUNT(*) as no FROM m_books";
$book_no = $cm_pg->select_one($sql);
$total_books = $book_no['no'] + 0;

// Tính tổng số trang
$total_pages = ceil($total_books / $books_per_page);

// Lấy dữ liệu sách cho trang hiện tại
$sql = "SELECT * FROM m_books LIMIT $books_per_page OFFSET $offset";
$result = $cm_pg->execute($sql);

$book_arr = [];
while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($item) {
        $book_arr[] = $item;
    }
}
?>

<section class="products" id="products">
    <h1 class="heading">Những sản phẩm <span>mới nhất</span></h1>

    <div class="box-container">
        <?php if (!empty($book_arr)): ?>
            <?php foreach ($book_arr as $row): ?>
                <div class="box">
                    <span class="discount">-10%</span>
                    <div class="image">
                        <img src="admin/<?php echo $row["book_img"]; ?>" alt="<?php echo $row["book_name"]; ?>">
                        <div class="icons">
                            <a title="Yêu thích" href="#" class="fas fa-heart favorite-btn" data-id="<?php echo $row['book_id']; ?>"></a>
                            <button class="cart-btn" data-id="<?php echo $row['book_id']; ?>" data-name="<?php echo htmlspecialchars($row['book_name']); ?>">Thêm vào giỏ hàng</button>
                            <a title="Xem thông tin" href="#" class="fa-regular fa-eye view-book" data-id="<?php echo $row['book_id']; ?>" data-bs-toggle="modal" data-bs-target="#bookDetailModal"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3><?php echo $row["book_name"]; ?></h3>
                        <div class="price">
                            <?php echo number_format($row["book_price"]); ?>,000đ
                            <span><?php echo number_format($row["book_price"] * 1.1); ?>,000đ</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm nào trong cơ sở dữ liệu.</p>
        <?php endif; ?>
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
</section>
<?php
// Hiển thị phân trang
echo '<div class="pagination">';
for ($page = 1; $page <= $total_pages; $page++) {
    if ($page == $current_page) {
        echo '<strong>' . $page . '</strong> ';
    } else {
        echo '<a href="index.php?page=' . $page . '#products">' . $page . '</a> ';
    }
}
echo '</div>';


?>
<!-- ------- -->
<!-- section thể loại -->
<section class="review" id="review">
    <h1 class="heading"> Nhận xét từ <span>khách hàng</span> </h1>

    <div class="box-container">

        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>The store's service is very good, the staff are friendly and enthusiastic to support customers. Books were delivered quickly and carefully packaged. I am very satisfied and will continue to support the store!</p>
            <div class="user">
                <img src="imgs/get-img/Mike-Tyson.jpg" alt="">
                <div class="user-info">
                    <h3>Mike Tyson</h3>
                    <span>Người mua hàng</span>
                </div>
            </div>
            <span class="fas fa-quote-right"></span>
        </div>
        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>この書店のサービスはとても良く、スタッフはフレンドリーで熱心に顧客をサポートします。本は迅速に配送され、丁寧に梱包されていました。とても満足していますので、これからもお店を応援していきます！</p>
            <div class="user">
                <img src="imgs/get-img/Songoku.jpg" alt="">
                <div class="user-info">
                    <h3>Son Goku</h3>
                    <span>Người mua hàng</span>
                </div>
            </div>
            <span class="fas fa-quote-right"></span>
        </div>
        <div class="box">
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>Dịch vụ của cửa hàng sách rất tốt, nhân viên thân thiện và nhiệt tình hỗ trợ khách hàng. Sách được giao nhanh chóng và đóng gói cẩn thận. Tôi rất hài lòng và sẽ tiếp tục ủng hộ cửa hàng!</p>
            <div class="user">
                <img src="imgs/get-img/Son-Tung-2-R.jpeg" alt="">
                <div class="user-info">
                    <h3>Nguyễn Thanh Tùng</h3>
                    <span>Người mua hàng</span>
                </div>
            </div>
            <span class="fas fa-quote-right"></span>
        </div>
    </div>
</section>
<!-- ------- -->
<!-- section liên hệ -->
<section class="contact" id="contact">
    <h1 class="heading">Liên hệ <span>với chúng tôi</span> </h1>

    <div class="row">
        <form action="">
            <input type="text" placeholder="Tên" class="box" name="name">
            <input type="email" placeholder="Email" class="box" name="email">
            <input type="number" placeholder="Số điện thoại" class="box" name="number">
            <textarea name="message" class="box" placeholder="Lời nhắn" cols="30" rows="10"></textarea>
            <input type="submit" value="Gửi" class="btn">
        </form>
        <div class="image">
            <img src="imgs/get-img/contact.png" alt="">
        </div>
    </div>
</section>

<?php
echo footer_normal();
?>