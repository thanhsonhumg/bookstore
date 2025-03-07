<?php
include '../init.php';
// mysqli_set_charset($cm_pg, "utf8");
$title = "Thêm sách mới";
$book_id = $_GET['book_id'];
$message ='';

if ($_POST['action'] == 'add-new'){
    $new_book = array();


    $new_book['detail_id'] = ($_POST['detail_id']);
    $new_book['book_name'] = $_POST['book_name'];
    $new_book['book_type'] = $_POST['book_type'];
    $new_book['author'] = $_POST['author'];
    $new_book['published_year'] = input2data($_POST['published_year']);
    $new_book['book_price'] = $_POST['book_price'];

    // ktra detail_id
    $sql = "SELECT detail_id FROM m_books WHERE detail_id = '{$new_book['detail_id']}'";
    $exist_detailID = $cm_pg->select_one($sql);

    if ($exist_detailID) {
        $message = "Mã sách (detail_id) đã tồn tại. Vui lòng nhập mã khác.";
        echo "<script>alert('$message'); window.history.back();</script>";
        exit;
    }
    
    if (isset($_FILES['book_img']) && $_FILES['book_img']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'insert-img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmpPath = $_FILES['book_img']['tmp_name'];
        $fileName = $_FILES['book_img']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if ($_FILES['book_img']['size'] < 2000000) {
                $timestamp = date("Ymd_His");
                $newFileName = $uploadDir . $new_book['detail_id'] . "_" . $timestamp . '.' . $fileExtension;
                
                if (move_uploaded_file($fileTmpPath, $newFileName)) {
                    $new_book['book_img'] = $newFileName;
                } else {
                    $message = "Lỗi khi di chuyển tệp tải lên.";
                }
            } else {
                $message = "Kích thước tệp vượt quá giới hạn cho phép (2MB).";
            }
        } else {
            $message = "Định dạng tệp không hợp lệ. Chỉ cho phép jpg, gif, png, jpeg, webp.";
        }
    }
    // var_dump($new_book);
    $result = $cm_pg->save("m_books", $new_book);

    $redirect = "Location: detail?book_id={$new_book['book_id']}";

    if ($result) {
        echo "<script>
            alert('Thêm thông tin chi tiết sách thành công!'); 
            window.location.href='index';
        </script>";
        exit;
    } else {
        $message = "Thêm thất bại. QUERY=" . $_SESSION['query'];
    }
}

$content = <<<EOT
    <span class="red">{$message}</span>

    <h2>THÊM SÁCH MỚI</h2>
    <form class="form-crud" action="" method="post" enctype="multipart/form-data">
        <label for="detail_id">Mã sách</label>
        <input type="text" id="detail_id" name="detail_id" value="{$_POST['detail_id']}" required><br>

        <label for="book_name">Tên sách</label>
        <input type="text" id="book_name" name="book_name" value="{$_POST['book_name']}" required><br>

        <label for="book_type">Thể loại sách</label>
        <input type="text" id="book_type" name="book_type" value="{$_POST['book_type']}"><br>

        <label for="author">Tác giả</label>
        <input type="text" id="author" name="author" value="{$_POST['author']}"><br>

        <label for="published_year">Năm xuất bản:</label>
        <input type="date" min="1900" max="2024" id="published_year" name="published_year" value="{$_POST['published_year']}"><br>

        <label for="book_img">Ảnh bìa sách</label>
        <input type="file" id="book_img" name="book_img" accept="image/*" ><br>

        <label for="book_price">Giá sách (K-VND)</label>
        <input type="number" id="book_price" name="book_price" value="{$_POST['book_price']}"><br>

        <input type="hidden" id="action" name="action" value="add-new"><br>

        <button class="detail-back-button" onclick="goBack()">Quay lại</button>
        <button type="reset" class="detail-clear-button" onclick="clearForm()">Nhập lại</button>
        <button type="submit" class="detail-add-button">Thêm mới</button>

    </form>

    <script>
        function goBack() {
            window.history.back();
        }
        function clearForm() {
            if (confirm("Bạn có chắc chắn muốn xóa tất cả dữ liệu đã nhập không?")) {
                document.getElementById("bookForm").reset();
            }
        }
    </script>
EOT;
echo header_admin($title);
echo $content;
echo footer_admin();
?>
