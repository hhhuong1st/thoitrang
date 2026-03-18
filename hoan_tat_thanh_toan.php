<?php
session_start();
include 'ket_noi.php';

// Kiểm tra nếu chưa đăng nhập hoặc giỏ hàng trống thì không cho phép thanh toán
if (!isset($_SESSION['user_id']) || empty($_SESSION['giohang'])) {
    header("Location: index.php");
    exit();
}

// Khởi tạo các biến để hiển thị ở giao diện thông báo
$ten_kh = "";
$sdt = "";
$ma_dh = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Lấy dữ liệu từ Form và Session
    $id_tk = $_SESSION['user_id']; 
    $ten_kh = mysqli_real_escape_string($conn, $_POST['ten']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $dia_chi = mysqli_real_escape_string($conn, $_POST['dia_chi']);
    $pttt = mysqli_real_escape_string($conn, $_POST['pttt']);
    $tong_tien = 0;

    // 2. Tính toán tổng giá trị đơn hàng (Sử dụng gia_ban chữ thường)
    foreach ($_SESSION['giohang'] as $id_sp => $so_luong) {
        $sql_gia = "SELECT gia_ban FROM san_pham WHERE ma_sp = $id_sp";
        $res_gia = mysqli_query($conn, $sql_gia);
        if ($row_gia = mysqli_fetch_assoc($res_gia)) {
            $tong_tien += $row_gia['gia_ban'] * $so_luong;
        }
    }

    // 3. Chèn vào bảng don_hang (Liên kết trực tiếp id_tai_khoan)
    $sql_dh = "INSERT INTO don_hang (id_tai_khoan, ten_kh, dia_chi_kh, dien_thoai, phuong_thuc_ttoan, tong_tien) 
               VALUES ($id_tk, '$ten_kh', '$dia_chi', '$sdt', '$pttt', $tong_tien)";
    
    if (mysqli_query($conn, $sql_dh)) {
        $ma_dh = mysqli_insert_id($conn); 

        // --- THÊM ĐOẠN CODE NÀY ĐỂ CẬP NHẬT TÀI KHOẢN ---
        // Tự động cập nhật SĐT và Địa chỉ vào bảng tai_khoan nếu chúng đang trống hoặc thay đổi
        $sql_update_tk = "UPDATE tai_khoan SET dien_thoai = '$sdt', dia_chi = '$dia_chi' WHERE id = $id_tk";
        mysqli_query($conn, $sql_update_tk);
        // ----------------------------------------------

        // 4. Chèn vào bảng chi_tiet_don_hang và cập nhật kho
        foreach ($_SESSION['giohang'] as $id_sp => $so_luong) {
            $sql_sp = "SELECT gia_ban FROM san_pham WHERE ma_sp = $id_sp";
            $res_sp = mysqli_query($conn, $sql_sp);
            $row_sp = mysqli_fetch_assoc($res_sp);
            
            $gia_ban = $row_sp['gia_ban']; 
            $thanh_tien = $gia_ban * $so_luong;

            // Lưu chi tiết
            $sql_ct = "INSERT INTO chi_tiet_don_hang (ma_dh, ma_sp, so_luong, gia_ban, thanh_tien) 
                       VALUES ($ma_dh, $id_sp, $so_luong, $gia_ban, $thanh_tien)";
            mysqli_query($conn, $sql_ct);

            // Cập nhật giảm số lượng trong kho sản phẩm
            $sql_update_kho = "UPDATE san_pham SET so_luong = so_luong - $so_luong WHERE ma_sp = $id_sp";
            mysqli_query($conn, $sql_update_kho);
        }

        // Xóa giỏ hàng sau khi đặt thành công
        unset($_SESSION['giohang']);
    } else {
        die("Lỗi đặt hàng: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công - Coolmate</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f8f8; margin: 0; }
        .success-wrapper { min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .success-box { background: white; width: 100%; max-width: 600px; padding: 50px 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; }
        .success-icon { font-size: 80px; color: #28a745; margin-bottom: 20px; }
        .success-box h2 { color: #333; text-transform: uppercase; font-weight: 800; }
        .order-id { color: #e74c3c; font-size: 20px; font-weight: bold; }
        .btn-home { display: inline-block; margin-top: 30px; padding: 15px 40px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        .btn-home:hover { background: #4186e0ff; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="success-wrapper">
        <div class="success-box">
            <div class="success-icon">✔️</div>
            <h2>ĐẶT HÀNG THÀNH CÔNG!</h2>
            <p>Cảm ơn <b><?php echo htmlspecialchars($ten_kh); ?></b> đã tin dùng Coolmate.</p>
            <p>Mã đơn hàng: <span class="order-id">#<?php echo $ma_dh; ?></span></p>
            <p>Số điện thoại nhận hàng: <b><?php echo htmlspecialchars($sdt); ?></b></p>
            <p>Chúng tôi sẽ sớm liên hệ để giao hàng đến bạn.</p>
            <a href="index.php" class="btn-home">TIẾP TỤC MUA SẮM</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>