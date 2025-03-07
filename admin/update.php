<?php
include("../init.php");

$message = "";
$display = "";
$book_id = $_GET['book_id'].'';

if($_POST['book_id'].'' != '') {
    $book_id = $_POST['book_id'].'';
}

$stop_flg_0 = "checked";
$stop_flg_1 = "";
$img_display = "hidden";

$title = "Chỉnh sửa thông tin sách";

if ($book_id != '') {
    $sql = "select * from m_books where book_id = '{$book_id}';";
    $book = $cm_pg->select_one($sql);

    if ($book) {
        $title = $book['book_name'];
        $published_year_disp = data2input($book['published_year']);

        if (!empty($book['book_img'])) {
            $img_display = "";
        }

        if ($book['stop_flg'] + 0 == 1) {
            $stop_flg_0 = "";
            $stop_flg_1 = "checked";
        }
    } else {
        $display = "hidden";
        $message = "Không tìm thấy dữ liệu.";
    }
} else {
    $display = "hidden";
    $message = "Không tìm thấy dữ liệu.";
}

if ($_POST['action'] == 'update') {
    $new_book = [
        'book_name' => $_POST['book_name'],
        'book_type' => $_POST['book_type'],
        'author' => $_POST['author'],
        'published_year' => input2data($_POST['published_year']),
        'book_price' => $_POST['book_price'],
        'stop_flg' => $_POST['stop_flg'] + 0,
    ];
    $detail_id = generateShortCode($new_book['book_name']);
    $new_book['detail_id'] = $detail_id;

    if (isset($_FILES['book_img']) && $_FILES['book_img']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'insert-img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmpPath = $_FILES['book_img']['tmp_name'];
        $fileName = $_FILES['book_img']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = ['jpg', 'png', 'jpeg', 'webp'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if ($_FILES['book_img']['size'] < 2000000) {
                $timestamp = date("Ymd_His");
                $newFileName = $uploadDir . $detail_id . "_" . $timestamp . '.' . $fileExtension;

                if (move_uploaded_file($fileTmpPath, $newFileName)) {
                    $new_book['book_img'] = $newFileName;

                    if (!empty($book['book_img']) && file_exists($book['book_img'])) {
                        unlink($book['book_img']);
                    }
                } else {
                    $message = "Không thể tải ảnh lên.";
                }
            } else {
                $message = "Kích thước tệp vượt quá giới hạn cho phép (2MB).";
            }
        } else {
            $message = "Định dạng ảnh không hợp lệ.";
        }
    }

    $where = ['book_id' => $book_id];
    $result = $cm_pg->save("m_books", $new_book, $where);

    if ($result) {
        header("Location: detail.php?book_id={$book_id}");
        exit;
    } else {
        $message = "Cập nhật sách không thành công.";
    }
}

if($_POST['action'] == 'delete'){
    $sql = "select book_img from m_books where book_id = '{$book_id}';";
    $book = $cm_pg->select_one($sql);

    if ($book && !empty($book['book_img']) && file_exists($book['book_img'])) {
        unlink($book['book_img']);
    }

    $sql = "delete from m_books where book_id = '{$book_id}';";
    $result = $cm_pg->execute($sql);

    if ($result) {
        echo "<script>
        alert('Xóa dữ liệu thành công');
        window.location.href = 'index.php';
    </script>";
    } else {
        $message = "Xóa thất bại. QUERY=".$_SESSION['query'];
    }
}

$content = <<<EOT
    <span class="red">{$message}</span>
        <h2>Chỉnh sửa: <span>{$book['book_name']}</span></h2>
    <form class="form-crud" action="" method="post" enctype="multipart/form-data" class="{$display}">
        <label for="book_name">Tên Sách</label>
        <input type="text" id="book_name" name="book_name" value="{$book['book_name']}" required><br>

        <label for="book_type">Thể Loại</label>
        <input type="text" id="book_type" name="book_type" value="{$book['book_type']}" required><br>

        <label for="author">Tác Giả</label>
        <input type="text" id="author" name="author" value="{$book['author']}" required><br>

        <label for="published_year">Năm xuất bản:</label>
        <input type="date" min="1900" max="2024" id="published_year" name="published_year" value="{$published_year_disp}"><br>

        <label for="book_price">Giá Sách</label>
        <input type="number" id="book_price" name="book_price" value="{$book['book_price']}" min="100" max="10000" required><br>

        <label for="book_img">Ảnh</label>
        <input type="file" id="book_img" name="book_img" accept="image/*"><br>
        <input type="hidden" id="current_img" name="current_img" value="{$book['book_img']}"><br>
        <img src="{$book['book_img']}" class="{$img_display}" height="150px"><br>

        <label>Trạng Thái</label>
        <label for="status_selling">Đang Bán</label>
        <input type="radio" id="status_selling" name="stop_flg" value="0" {$stop_flg_0}>
        <label for="status_stopped">Ngừng Bán</label>
        <input type="radio" id="status_stopped" name="stop_flg" value="1" {$stop_flg_1}>

        <input type="hidden" id="book_id" name="book_id" value="{$book_id}">
        <input type="hidden" id="action" name="action" value=""><br>
        <button class="detail-back-button" onclick="goIndex()">Quay lại</button>
        <button type="submit" id="updateBtn" class="detail-add-button">Lưu Thay Đổi</button>
        <button type="submit" id="deleteBtn" class="detail-delete-button">Xóa</button>
    </form>

    <script>
        document.getElementById("updateBtn").onclick = function() {
            document.getElementById("action").value = "update";
        };
        document.getElementById("deleteBtn").onclick = function(e) {
            e.preventDefault(); // Ngăn chặn hành vi submit mặc định
            if (confirm("Bạn có chắc chắn muốn xóa sách này không?")) {
                document.getElementById("action").value = "delete";
                document.querySelector(".form-crud").submit(); // Chỉ submit khi đã gán action đúng
            }
        };
        function goIndex() {
            event.preventDefault();
            window.location.href = 'product.php';
        }
    </script>
EOT;

echo header_admin($title);
echo $content;
echo footer_admin();
