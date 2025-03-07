<?php
// dashboard.php
include('../init.php'); // Kết nối cơ sở dữ liệu

$title = "Admin Dashboard";

// Lấy dữ liệu thống kê
$totalOrders = $cm_pg->select_one("SELECT COUNT(*) AS total_orders FROM m_orders")['total_orders'];
$totalRevenue = $cm_pg->select_one("SELECT SUM(total_amount) AS total_revenue FROM m_orders WHERE status = 'completed'")['total_revenue'];
$orderStatus = $cm_pg->execute("SELECT status, COUNT(*) AS count FROM m_orders GROUP BY status");
$monthlyRevenue = $cm_pg->execute("
    SELECT MONTH(created_at) AS month, YEAR(created_at) AS year, SUM(total_amount) AS revenue
    FROM m_orders 
    WHERE status = 'completed'
    GROUP BY YEAR(created_at), MONTH(created_at)
");

// Lấy số lượng đơn hàng đã hủy
$cancelledOrders = $cm_pg->select_one("SELECT COUNT(*) AS cancelled_orders FROM m_orders WHERE status = 'cancelled'")['cancelled_orders'];

// Xử lý dữ liệu cho biểu đồ
$labels = [];
$data = [];
foreach ($monthlyRevenue as $row) {
    $labels[] = $row['month'] . '/' . $row['year'];
    $data[] = $row['revenue'];
}

// Tạo mảng trạng thái theo thứ tự muốn hiển thị
$statusOrder = ['completed' => 'Đã hoàn thành', 'pending' => 'Đang xử lý', 'cancelled' => 'Đã hủy'];

// Mảng tạm để lưu trữ số lượng theo từng trạng thái
$statusCounts = [
    'completed' => 0,
    'pending' => 0,
    'cancelled' => $cancelledOrders // Đơn hàng đã hủy lấy từ cơ sở dữ liệu
];

// Đếm số lượng đơn hàng theo trạng thái
foreach ($orderStatus as $status) {
    if (isset($statusCounts[$status['status']])) {
        $statusCounts[$status['status']] = $status['count'];
    }
}

// Tạo HTML cho các phần thống kê
$orderStatusHtml = "";
foreach ($statusOrder as $key => $statusText) {
    $orderStatusHtml .= "<li>{$statusText}: {$statusCounts[$key]} đơn</li>";
}

// Nội dung HTML chính (Sử dụng PHP trực tiếp trong phần HTML)
$content = "
    <style>
        h1 {
            color: #333;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stats div {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 8px;
            width: 30%;
            text-align: center;
            background: #f9f9f9;
        }

        canvas {
            max-width: 100%;
            height: 400px; /* Đảm bảo biểu đồ có chiều cao */
        }
    </style>
    <h1>Thống kê đơn hàng và doanh thu</h1>

    <div class='stats'>
        <div>
            <h3>Tổng số đơn hàng</h3>
            <p>{$totalOrders}</p>
        </div>
        <div>
            <h3>Tổng doanh thu</h3>
            <p>" . number_format($totalRevenue, 0) . ",000 VNĐ</p> 
        </div>
        <div>
            <h3>Trạng thái đơn hàng</h3>
            <ul>
                {$orderStatusHtml}
            </ul>
        </div>
    </div>

    <h2>Doanh thu theo tháng</h2>
    <canvas id='revenueChart'></canvas>

    <script src='https://cdn.jsdelivr.net/npm/chart.js@3.8.0'></script>
    <script>
        // Đảm bảo mã JS chạy sau khi DOM đã tải
        document.addEventListener('DOMContentLoaded', function() {
            // Chuẩn bị dữ liệu cho biểu đồ
            const labels = " . json_encode($labels) . ";
            const data = " . json_encode($data) . ";

            // Vẽ biểu đồ doanh thu
            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu ( 000 VNĐ )',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Doanh thu theo tháng'
                        }
                    }
                }
            });
        });
    </script>
";


// Gọi header
echo header_admin($title);
// Hiển thị nội dung
echo $content;
// Gọi footer
echo footer_admin();
