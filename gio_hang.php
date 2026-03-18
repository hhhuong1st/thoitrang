<?php
session_start();
include 'ket_noi.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem giỏ hàng!'); window.location.href = 'tai_khoan.php';</script>";
    exit();
}

// LẤY DỮ LIỆU NGƯỜI DÙNG ĐỂ ĐIỀN FORM
$id_user = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM tai_khoan WHERE id = $id_user");
$user_data = mysqli_fetch_assoc($user_query);
// Logic giảm số lượng
if (isset($_GET['giam_id'])) {
    $id = $_GET['giam_id'];
    if ($_SESSION['gio_hang'][$id] > 1) {
        $_SESSION['gio_hang'][$id] -= 1;
    } else {
        unset($_SESSION['gio_hang'][$id]); // Nếu giảm xuống 0 thì xóa khỏi giỏ
    }
    header("Location: gio_hang.php"); exit();
}

// Logic tăng số lượng (có kiểm tra kho)
if (isset($_GET['tang_id'])) {
    $id = $_GET['tang_id'];
    $sql_kho = "SELECT so_luong FROM san_pham WHERE ma_sp = $id";
    $res_kho = mysqli_query($conn, $sql_kho);
    $row_kho = mysqli_fetch_assoc($res_kho);
    
    if ($_SESSION['gio_hang'][$id] < $row_kho['so_luong']) {
        $_SESSION['gio_hang'][$id] += 1;
    } else {
        echo "<script>alert('Số lượng trong kho đã đạt giới hạn!'); window.location.href = 'gio_hang.php';</script>"; exit();
    }
    header("Location: gio_hang.php"); exit();
}
// Logic xử lý giỏ hàng (giữ nguyên các phần xóa/thêm)
if (isset($_GET['xoa_id'])) {
    $id_xoa = $_GET['xoa_id'];
    unset($_SESSION['gio_hang'][$id_xoa]);
    header("Location: gio_hang.php"); exit();
}
if (isset($_GET['xoa']) && $_GET['xoa'] == 'tatca') {
    unset($_SESSION['gio_hang']); header("Location: gio_hang.php"); exit();
}
if (isset($_GET['them_id'])) {
    $id = $_GET['them_id'];
    $sql_kho = "SELECT so_luong FROM san_pham WHERE ma_sp = $id";
    $res_kho = mysqli_query($conn, $sql_kho);
    $row_kho = mysqli_fetch_assoc($res_kho);
    $sl_muon_them = (isset($_SESSION['gio_hang'][$id]) ? $_SESSION['gio_hang'][$id] : 0) + 1;
    if ($sl_muon_them > $row_kho['so_luong']) {
        echo "<script>alert('Kho không đủ hàng!'); window.location.href = '".$_SERVER['HTTP_REFERER']."';</script>"; exit();
    } else {
        $_SESSION['gio_hang'][$id] = $sl_muon_them;
    }
    header("Location: gio_hang.php"); exit();
}

$tong_cong = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng & Thanh toán</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; margin: 0; }
        .main-container { max-width: 1200px; margin: 40px auto; display: flex; gap: 30px; padding: 0 20px; align-items: flex-start; }
        .col-left { flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .col-right { flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 15px 0; border-bottom: 1px solid #f9f9f9; }
        .prod-img { width: 50px; height: 60px; object-fit: cover; border-radius: 4px; }
        .final-price { font-size: 22px; font-weight: bold; color: #e74c3c; }
        .btn-submit { width: 100%; background: #000; color: white; padding: 18px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <?php if (!empty($_SESSION['gio_hang'])): ?>
    <form action="hoan_tat_thanh_toan.php" method="post">
        <div class="main-container">
            <div class="col-left">
                <h3>Thông tin giao hàng</h3>
                <div class="form-group">
                    <label>Họ và tên người nhận</label>
                    <input type="text" name="ten" value="<?php echo htmlspecialchars($user_data['ho_ten']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="sdt" value="<?php echo htmlspecialchars($user_data['dien_thoai']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <textarea name="dia_chi" rows="3" required><?php echo htmlspecialchars($user_data['dia_chi']); ?></textarea>
                </div>
                <h3>Thanh toán</h3>
                <label style="display: flex; align-items: center; gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 4px;">
                    <input type="radio" name="pttt" value="COD" checked> <span>Thanh toán khi nhận hàng (COD)</span>
                </label>
            </div>
            <div class="col-right">
                <h3>Đơn hàng (<?php echo array_sum($_SESSION['gio_hang']); ?> món)</h3>
                <table class="summary-table">
                    <?php foreach ($_SESSION['gio_hang'] as $id_sp => $so_luong): 
                        $res = mysqli_query($conn, "SELECT * FROM san_pham WHERE ma_sp = $id_sp");
                        $row = mysqli_fetch_assoc($res);
                        $thanh_tien = $row['gia_ban'] * $so_luong; $tong_cong += $thanh_tien; ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="images/<?php echo $row['hinh_anh']; ?>" class="prod-img">
                                <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
    <span style="font-size: 13px; color: #666;">Số lượng:</span>
    <div style="display: flex; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
        <a href="gio_hang.php?giam_id=<?php echo $id_sp; ?>" 
           style="padding: 2px 8px; background: #f8f8f8; text-decoration: none; color: #333; border-right: 1px solid #ddd;">-</a>
        
        <span style="padding: 2px 10px; font-size: 14px; background: #fff; min-width: 20px; text-align: center;">
            <?php echo $so_luong; ?>
        </span>
        
        <a href="gio_hang.php?tang_id=<?php echo $id_sp; ?>" 
           style="padding: 2px 8px; background: #f8f8f8; text-decoration: none; color: #333; border-left: 1px solid #ddd;">+</a>
    </div>
    
    <a href="gio_hang.php?xoa_id=<?php echo $id_sp; ?>" 
       style="color: #e74c3c; text-decoration: none; font-size: 12px; margin-left: 10px;">Xóa</a>
</div>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($thanh_tien, 0, ',', '.'); ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div style="margin-top: 20px; text-align: right;">
                    <p>Tổng cộng: <span class="final-price"><?php echo number_format($tong_cong, 0, ',', '.'); ?>đ</span></p>
                </div>
                <button type="submit" class="btn-submit">XÁC NHẬN ĐẶT HÀNG</button>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div style="text-align: center; padding: 100px;">Giỏ hàng trống. <a href="index.php">Tiếp tục mua sắm</a></div>
    <?php endif; ?>
    <?php include 'footer.php'; ?>
</body>
</html>