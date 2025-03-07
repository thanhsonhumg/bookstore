<?php
session_start();
include("../init.php");
$title = "Quản lý Đơn Hàng";
// Lấy danh sách đơn hàng từ cơ sở dữ liệu
$sql = "SELECT * FROM m_orders ORDER BY created_at desc;";
$result = $cm_pg->execute($sql);

$order_no = 0; // Tổng số đơn hàng
$order_arr = array();

while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if (!empty($item['order_id'])) {
        $order_arr[] = $item;
        $order_no++;
    }
}

$row_html = "";

// Duyệt qua danh sách đơn hàng và tạo các dòng hiển thị
foreach ($order_arr as $index => $order) {
    $order_id = $order['order_id'];
    $user_id = $order['user_id'] ?? "Khách";
    $total_amount = number_format($order['total_amount'], 0) . ".000đ";
    $phone = $order['phone'] ?: "Chưa cung cấp";
    $shipping_address = $order['shipping_address'] ?: "Chưa cung cấp";
    $status = $order['status'];
    $created_at = $order['created_at'];

    // Lấy username từ user_id
    if ($user_id !== "Khách") {
        // Sử dụng select_one để lấy username từ bảng m_users
        $user_sql = "SELECT username FROM m_users WHERE user_id = {$user_id}";
        $user_data = $cm_pg->select_one($user_sql);

        // Nếu có kết quả, lấy username
        $username = $user_data ? $user_data['username'] : "Khách";
    } else {
        $username = "Khách";
    }

    // Tạo danh sách chọn cho trạng thái
    $status_options = [
        'pending' => 'Đang xử lý',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];

    $status_select = '<select class="order-status" data-order-id="' . $order_id . '">';
    foreach ($status_options as $key => $value) {
        $selected = ($status === $key) ? 'selected' : '';
        $status_select .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
    }
    $status_select .= '</select>';

    $row_html .= <<<EOT
    <tr class="book-row">
        <td>{$order_id}</td>
        <td>{$username}</td>
        <td>{$total_amount}</td>
        <td>{$phone}</td>
        <td>{$shipping_address}</td>
        <td>{$status_select}</td>
        <td>{$created_at}</td>
        <td>
            <a class="a-detail" href="order-detail.php?order_id={$order_id}">Xem</a>
        </td>
    </tr>
EOT;
}

$content = <<<EOT
<div class="book-list-container">
    <h1>QUẢN LÝ ĐƠN HÀNG</h1>
    <div class="book-count">Số lượng đơn hàng: <span id="order_no">{$order_no}</span></div>
    <table class="book-table">
        <thead>
            <tr>
                <th>ID Đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ giao hàng</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            {$row_html}
        </tbody>
    </table>
</div>
<div class="block h50"></div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelectors = document.querySelectorAll('.order-status');

        statusSelectors.forEach(selector => {
            selector.addEventListener('change', function () {
                const orderId = this.getAttribute('data-order-id');
                const newStatus = this.value;

                fetch('update-order-status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ order_id: orderId, status: newStatus }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Trạng thái đơn hàng đã được cập nhật!');
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Không thể cập nhật trạng thái đơn hàng.');
                });
            });
        });
    });
</script>
EOT;

// Hiển thị nội dung
echo header_admin($title);
echo $content;
echo footer_admin();
