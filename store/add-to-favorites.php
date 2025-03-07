<?php
session_start();
include("../init.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? null;
    // Lấy ID người dùng từ session, nếu không có thì mặc định là 0
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    if ($book_id) {
        // Thêm sách vào danh sách yêu thích
        $sql = "INSERT INTO favorites (user_id, book_id) VALUES (?, ?)";
        $result = $cm_pg->execute($sql, [$user_id, $book_id]);

        if (!$result) {
            // In ra câu lệnh SQL và lỗi
            echo json_encode(['status' => 'error', 'message' => 'Không thể thêm sách vào danh sách yêu thích: ' . $cm_pg->cn->error]);
            exit;
        }

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Đã thêm sách vào danh sách yêu thích!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể thêm sách vào danh sách yêu thích.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
    }

    exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
?>