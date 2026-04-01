<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "shop_thoi_trang"; 

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập font chữ tiếng Việt
$conn->set_charset("utf8mb4");

// --- Bắt đầu script Theo dõi truy cập ---
// 1. Tạo bảng thong_ke_truy_cap nếu chưa có
$sql_create_table = "CREATE TABLE IF NOT EXISTS thong_ke_truy_cap (
    ngay DATE PRIMARY KEY,
    so_luong INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
$conn->query($sql_create_table);

// 2. Tăng số lượng truy cập hôm nay
// Chỉ tăng nếu người dùng là phiên mới (chưa có session 'has_visited')
// Kiểm tra và khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['has_visited'])) {
    $today = date('Y-m-d');
    
    // Kiểm tra xem đã có bản ghi cho ngày hôm nay chưa
    $sql_check = "SELECT so_luong FROM thong_ke_truy_cap WHERE ngay = '$today'";
    $result_check = $conn->query($sql_check);
    
    if ($result_check && $result_check->num_rows > 0) {
        // Đã có thì update + 1
        $conn->query("UPDATE thong_ke_truy_cap SET so_luong = so_luong + 1 WHERE ngay = '$today'");
    } else {
        // Chưa có thì insert bản ghi mới
        $conn->query("INSERT INTO thong_ke_truy_cap (ngay, so_luong) VALUES ('$today', 1)");
    }
    
    // Đánh dấu người dùng đã tính lượt truy cập trong phiên làm việc này
    $_SESSION['has_visited'] = true;
}
// --- Kết thúc script Theo dõi truy cập ---
?>