<?php
include("../init.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = $data['order_id'] ?? null;
    $status = $data['status'] ?? null;

    if (!$order_id || !$status) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ', 'debug' => compact('order_id', 'status')]);
        exit;
    }

    // Debug thêm trước khi thực hiện câu SQL
    error_log("Order ID: $order_id, Status: $status");

    // Cập nhật trạng thái đơn hàng
    $sql = "UPDATE m_orders SET status = ? WHERE order_id = ?";
    $stmt = $cm_pg->cn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $status, $order_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái', 'debug' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Chuẩn bị câu truy vấn thất bại', 'debug' => $cm_pg->cn->error]);
    }
    exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
