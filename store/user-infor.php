<?php
include '../init.php';

$title = "Thông Tin Cá Nhân";
$message = '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href = 'http://localhost/bookstore/login/login.php';</script>";
    exit;
}

// Kiểm tra user_id trong d_users
$sql = "SELECT * FROM d_users WHERE user_id = ?";
$user_info = $cm_pg->select_one($sql, [$user_id]);

if (!$user_info) {
    // Nếu không có user_id, tạo mới thông tin người dùng
    $default_user_info = [
        'user_id' => $user_id,
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'user_avt' => ''
    ];
    $cm_pg->save("d_users", $default_user_info);
    $user_info = $default_user_info; // Gán thông tin mới tạo cho $user_info
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_avt = $user_info['user_avt']; // Giữ nguyên ảnh cũ nếu không có ảnh mới

    // Kiểm tra và xử lý ảnh tải lên
    if (isset($_FILES['user_avt']) && $_FILES['user_avt']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = './user-avatar/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmpPath = $_FILES['user_avt']['tmp_name'];
        $fileName = $_FILES['user_avt']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = ['jpg', 'png', 'jpeg', 'webp'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if ($_FILES['user_avt']['size'] < 2000000) {
                $timestamp = date("Ymd_His");
                $newFileName = $uploadDir . $user_id . "_" . $timestamp . '.' . $fileExtension;

                // Nếu có ảnh cũ, xóa ảnh cũ
                if (!empty($user_info['user_avt']) && file_exists($user_info['user_avt'])) {
                    if (!unlink($user_info['user_avt'])) {
                        $message = "Không thể xóa ảnh cũ.";
                    }
                }

                // Di chuyển ảnh mới vào thư mục
                if (move_uploaded_file($fileTmpPath, $newFileName)) {
                    $user_avt = $newFileName; // Cập nhật avatar nếu có ảnh mới
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

    // Tạo mảng dữ liệu cần cập nhật
    $user_infor = [
        'user_id' => $user_id,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'user_avt' => $user_avt // Nếu không có ảnh mới, ảnh cũ vẫn được giữ nguyên
    ];

    // Điều kiện để xác định bản ghi cần cập nhật (dựa trên user_id)
    $where = ['user_id' => $user_id];

    // Gọi hàm save() để thực hiện cập nhật thông tin
    $result = $cm_pg->save("d_users", $user_infor, $where);

    if ($result) {
        $message = "Cập nhật thông tin thành công!";
        // Cập nhật lại thông tin người dùng sau khi thành công
        $user_info = compact('name', 'email', 'phone', 'address', 'user_avt');
    } else {
        $message = "Cập nhật thông tin thất bại!";
    }
}

$content = <<<EOT
<div class="user-info-container">
    <span class="message red">{$message}</span>

    <h2 class="info-title">THÔNG TIN CÁ NHÂN</h2>
    <form class="form-crud user-info-form" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name" class="form-label">Họ và tên:</label>
            <input type="text" id="name" name="name" class="form-input" value="{$user_info['name']}" required>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-input" value="{$user_info['email']}" required>
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" class="form-input" value="{$user_info['phone']}" required>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Địa chỉ:</label>
            <input type="text" id="address" name="address" class="form-input" value="{$user_info['address']}" required><br>
        </div>

        <div class="form-group">
            <label for="user_avt_img" class="form-label">Avatar:</label>
            <input type="file" id="user_avt_img" name="user_avt" accept="image/*"><br>
            <img src="{$user_info['user_avt']}" class="avatar-img" height="100px" /><br>
        </div>

        <div class="form-actions">
            <button type="submit" class="info-update-button btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>
EOT;

echo header_normal($title);
echo $content;
