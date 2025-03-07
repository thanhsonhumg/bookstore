<?php
include '../init.php';

$title = "Quản lý tài khoản người dùng";
$message = "";
$users = [];

// Thực hiện truy vấn
$sql = "SELECT m_users.user_id, m_users.username, m_users.role, 
        d_users.name, d_users.email, d_users.phone, d_users.address, d_users.user_avt 
        FROM m_users 
        LEFT JOIN d_users ON m_users.user_id = d_users.user_id 
        ORDER BY m_users.user_id ASC";

// Thực hiện truy vấn và kiểm tra kết quả
$users_result = $cm_pg->execute($sql);

if ($users_result === false) {
    $message = "Có lỗi trong câu truy vấn.";
} else {
    // Chuyển đổi mysqli_result thành mảng
    $users = $users_result->fetch_all(MYSQLI_ASSOC);

    if (count($users) == 0) {
        $message = "Không có dữ liệu tài khoản.";
    }
}

// Tính số lượng tài khoản
$user_count = count($users);

// Cấu trúc HTML cho bảng tài khoản
$row_html = "";
foreach ($users as $index => $user) {
    $role_display = ($user['role'] == 1) ? 'Admin' : 'User';
    $row_html .= <<<EOT
    <tr class="book-row">
        <td>{$user['user_id']}</td>
        <td>{$user['username']}</td>
        <td>$role_display</td>
        <td>{$user['name']}</td>
        <td>{$user['email']}</td>
        <td>{$user['phone']}</td>
        <td>{$user['address']}</td>
    </tr>
EOT;
}

// Hiển thị thông báo nếu có lỗi hoặc không có dữ liệu
$content = <<<EOT
<div class="book-list-container">
    <h1>QUẢN LÝ TÀI KHOẢN</h1>
    <div class="book-count">Số lượng tài khoản: <span id="user_no">{$user_count}</span></div>
    <table class="book-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Vai trò</th>
                <th>Họ và tên</th>
                <th class="email">Email</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
            </tr>
        </thead>
        <tbody>
            {$row_html}
        </tbody>
    </table>
</div>
EOT;

echo header_admin($title);
echo $content;
echo footer_admin();
