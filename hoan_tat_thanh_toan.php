<?php
session_start();
include 'ket_noi.php';

// Khởi tạo các biến mặc định
$ten_kh = "";
$sdt = "";
$ma_dh = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION['giohang'])) {
    
    $ten_kh = $_POST['ten'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];
    $pttt = $_POST['pttt'];
    $tong_tien = 0;

    // Sửa thành Gia_ban
    foreach ($_SESSION['giohang'] as $id_sp => $so_luong) {
        $sql_gia = "SELECT Gia_ban FROM san_pham WHERE ma_sp = $id_sp";
        $res_gia = mysqli_query($conn, $sql_gia);
        $row_gia = mysqli_fetch_assoc($res_gia);
        $tong_tien += $row_gia['Gia_ban'] * $so_luong;
    }

    $sql_kh = "INSERT INTO khach_hang (ten_kh, dia_chi_kh, dien_thoai) VALUES ('$ten_kh', '$dia_chi', '$sdt')";
    mysqli_query($conn, $sql_kh);
    $ma_kh = mysqli_insert_id($conn); 

    $sql_dh = "INSERT INTO don_hang (ma_kh, ten_kh, dia_chi_kh, phuong_thuc_ttoan, tong_tien) VALUES ($ma_kh, '$ten_kh', '$dia_chi', '$pttt', $tong_tien)";
    mysqli_query($conn, $sql_dh);
    $ma_dh = mysqli_insert_id($conn); 

    foreach ($_SESSION['giohang'] as $id_sp => $so_luong) {
        // Sửa thành Gia_ban
        $sql_sp = "SELECT Gia_ban FROM san_pham WHERE ma_sp = $id_sp";
        $res_sp = mysqli_query($conn, $sql_sp);
        $row_sp = mysqli_fetch_assoc($res_sp);
        
        $gia_ban = $row_sp['Gia_ban']; 
        $thanh_tien = $gia_ban * $so_luong;

        $sql_ct = "INSERT INTO chi_tiet_don_hang (ma_dh, ma_sp, so_luong, gia_ban, thanh_tien) VALUES ($ma_dh, $id_sp, $so_luong, $gia_ban, $thanh_tien)";
        mysqli_query($conn, $sql_ct);

        $sql_update_kho = "UPDATE san_pham SET so_luong = so_luong - $so_luong WHERE ma_sp = $id_sp";
        mysqli_query($conn, $sql_update_kho);
    }

    unset($_SESSION['giohang']);
    
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công - Sage Fashion</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f8f8; margin: 0; padding: 0;}
        .success-wrapper { min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 50px 20px;}
        .success-box { background: white; width: 100%; max-width: 600px; padding: 50px 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; }
        .success-icon { font-size: 80px; color: #28a745; margin-bottom: 20px; line-height: 1;}
        .success-box h2 { color: #333; font-size: 28px; margin-bottom: 15px; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;}
        .success-box p { font-size: 16px; color: #666; line-height: 1.6; margin-bottom: 10px;}
        .order-id { color: #e74c3c; font-size: 18px; font-weight: bold;}
        .btn-home { display: inline-block; margin-top: 30px; padding: 15px 40px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; transition: 0.3s;}
        .btn-home:hover { background: #4186e0ff; transform: translateY(-2px);}
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="success-wrapper">
        <div class="success-box">
            <div class="success-icon">✔️</div>
            <h2>ĐẶT HÀNG THÀNH CÔNG!</h2>
            <p>Cảm ơn <b><?php echo htmlspecialchars($ten_kh); ?></b> đã mua sắm tại Sage Fashion.</p>
            <p>Mã đơn hàng của bạn là: <span class="order-id">#<?php echo htmlspecialchars($ma_dh); ?></span></p>
            <p>Chúng tôi sẽ liên hệ với bạn qua số điện thoại <b><?php echo htmlspecialchars($sdt); ?></b> trong thời gian sớm nhất để xác nhận giao hàng.</p>
            <a href="index.php" class="btn-home">TIẾP TỤC MUA SẮM</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>