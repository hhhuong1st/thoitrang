<?php
session_start();
include 'ket_noi.php';

// 1. Kiểm tra đăng nhập và mã đơn hàng
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$ma_dh = mysqli_real_escape_string($conn, $_GET['id']);
$id_user = $_SESSION['user_id'];

// 2. Lấy thông tin chung của đơn hàng
$is_admin = isset($_GET['admin']) && $_GET['admin'] == 1 && isset($_SESSION['role']) && $_SESSION['role'] == 1;
if ($is_admin) {
    $sql_dh = "SELECT * FROM don_hang WHERE ma_dh = $ma_dh";
} else {
    $sql_dh = "SELECT * FROM don_hang WHERE ma_dh = $ma_dh AND id_tai_khoan = $id_user";
}
$res_dh = mysqli_query($conn, $sql_dh);
$order_info = mysqli_fetch_assoc($res_dh);

if (!$order_info) {
    echo "Đơn hàng không tồn tại hoặc bạn không có quyền xem.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?php echo $ma_dh; ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; margin: 0; }
        .detail-container { max-width: 800px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .order-header { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-item b { display: block; color: #555; margin-bottom: 5px; }
        .product-table { width: 100%; border-collapse: collapse; }
        .product-table th, .product-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .product-table th { color: #888; font-size: 14px; }
        .total-row { text-align: right; font-size: 18px; margin-top: 20px; font-weight: bold; color: #e74c3c; }
        .btn-back { text-decoration: none; color: #4186e0; font-weight: bold; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="detail-container">
        <a href="tai_khoan.php" class="btn-back">← Quay lại lịch sử đơn hàng</a>
        
        <div class="order-header">
            <h2 style="margin:0;">Chi tiết đơn hàng #<?php echo $ma_dh; ?></h2>
            <?php 
                $tt = isset($order_info['trang_thai']) ? (int)$order_info['trang_thai'] : 0;
                if ($tt == 0) echo '<span style="background:#fff3cd; color:#856404; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold;">Chờ xác nhận</span>';
                elseif ($tt == 1) echo '<span style="background:#cce5ff; color:#004085; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold;">Đang giao hàng</span>';
                elseif ($tt == 2) echo '<span style="background:#d4edda; color:#155724; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold;">Hoàn thành</span>';
                elseif ($tt == 3) echo '<span style="background:#f8d7da; color:#721c24; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold;">Đã hủy</span>';
            ?>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <b>Thông tin nhận hàng:</b>
                <?php echo htmlspecialchars($order_info['ten_kh']); ?><br>
                SĐT: <?php echo htmlspecialchars($order_info['dien_thoai']); ?><br>
                Địa chỉ: <?php echo htmlspecialchars($order_info['dia_chi_kh']); ?>
            </div>
            <div class="info-item">
                <b>Thời gian đặt:</b>
                <?php echo date("d/m/Y H:i", strtotime($order_info['ngay_dat'])); ?><br>
                <b>Phương thức:</b> <?php echo $order_info['phuong_thuc_ttoan']; ?>
            </div>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th style="text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 3. Lấy danh sách sản phẩm trong đơn hàng
                $sql_ct = "SELECT ct.*, sp.ten_sp, sp.hinh_anh 
                           FROM chi_tiet_don_hang ct 
                           JOIN san_pham sp ON ct.ma_sp = sp.ma_sp 
                           WHERE ct.ma_dh = $ma_dh";
                $res_ct = mysqli_query($conn, $sql_ct);
                
                while($item = mysqli_fetch_assoc($res_ct)): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="images/<?php echo $item['hinh_anh']; ?>" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <span><?php echo $item['ten_sp']; ?></span>
                        </div>
                    </td>
                    <td><?php echo number_format($item['gia_ban'], 0, ',', '.'); ?>đ</td>
                    <td><?php echo $item['so_luong']; ?></td>
                    <td style="text-align: right; font-weight: bold;"><?php echo number_format($item['thanh_tien'], 0, ',', '.'); ?>đ</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total-row">
            Tổng thanh toán: <?php echo number_format($order_info['tong_tien'], 0, ',', '.'); ?>đ
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>