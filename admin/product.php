<?php
include("../init.php");
$title = "Admin";
$sql = "SELECT * FROM m_books ORDER BY book_id asc;";
$result = $cm_pg->execute($sql);

$book_no = 0;
$book_arr = array();
while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if (!empty($item['book_id'])) {
        $book_arr[] = $item;
        $book_no++;
    }
}

$row_html = "";
foreach ($book_arr as $index => $book) {
    $disp_index = $index + 1;

    $book_status_class = "";
    if ($book['stop_flg'] + 0 == 1) {
        $book_status_class = "red";
    } else {
        $book_status_class = "green";
    }

    $status_text = ($book['stop_flg'] + 0 == 1) ? "Ngừng bán" : "Đang bán";

    $published_year_disp = data2input($book['published_year']);

    $row_html .= <<<EOT
    <tr class="book-row">
        <td>{$book['book_id']}</td>
        <td><a href="detail.php?book_id={$book['book_id']}"><img src="{$book['book_img']}" alt="{$book['book_name']}" width="100"></a></td>
        <td>{$book['book_name']}</td>
        <td>{$book['book_price']}.000đ</td>
        <td><span class="{$book_status_class}">{$status_text}</span></td>
        <td>
            <a class="a-detail" href="detail.php?book_id={$book['book_id']}">Xem</a>
            <a class="a-update"href="update.php?book_id={$book['book_id']}">Sửa</a>
            <a class="a-delete" href="delete.php?book_id={$book['book_id']}" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này không?')">Xóa</a>        </td>
    </tr>
EOT;
}

$content = <<<EOT
            <div class="book-list-container">
                <h1>SẢN PHẨM</h1>
                <div class="btn-add-container">
                    <a href="add.php" class="btn-add">Thêm sách mới</a><br>
                </div>
                <div class="book-count">Số lượng sách: <span id="book_no">{$book_no}</span></div>
                <table class="book-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên sách</th>
                            <th>Giá</th>
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

echo header_admin($title);
echo $content;
echo footer_admin();
