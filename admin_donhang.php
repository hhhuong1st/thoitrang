<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}
include 'ket_noi.php';

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $ma_dh = (int)$_POST['ma_dh'];
    $trang_thai = (int)$_POST['trang_thai'];
    
    // Kiem tra trang thai cu
    $sql_old = "SELECT trang_thai FROM don_hang WHERE ma_dh = $ma_dh";
    $res_old = mysqli_query($conn, $sql_old);
    $old_st = mysqli_fetch_assoc($res_old)['trang_thai'];
    
    if ($old_st != 3 && $trang_thai == 3) {
        // Hủy đơn -> Hoàn kho
        $res_ct = mysqli_query($conn, "SELECT ma_sp, so_luong FROM chi_tiet_don_hang WHERE ma_dh = $ma_dh");
        while($ct = mysqli_fetch_assoc($res_ct)) {
            $sp = $ct['ma_sp']; $sl = $ct['so_luong'];
            mysqli_query($conn, "UPDATE san_pham SET so_luong = so_luong + $sl WHERE ma_sp = $sp");
        }
    } elseif ($old_st == 3 && $trang_thai != 3) {
        // Đã hủy nhưng mở lại trạng thái thường -> Trừ kho
        $res_ct = mysqli_query($conn, "SELECT ma_sp, so_luong FROM chi_tiet_don_hang WHERE ma_dh = $ma_dh");
        while($ct = mysqli_fetch_assoc($res_ct)) {
            $sp = $ct['ma_sp']; $sl = $ct['so_luong'];
            mysqli_query($conn, "UPDATE san_pham SET so_luong = so_luong - $sl WHERE ma_sp = $sp");
        }
    }

    $sql_update = "UPDATE don_hang SET trang_thai = $trang_thai WHERE ma_dh = $ma_dh";
    mysqli_query($conn, $sql_update);
    header("Location: admin_donhang.php"); exit();
}

$sql_dh = "SELECT * FROM don_hang ORDER BY ngay_dat DESC";
$res_dh = $conn->query($sql_dh);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đơn Hàng - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --warning: #f72585; --dark: #2b2d42; --light: #f8f9fa; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Public Sans', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: #f4f7fe; color: #2b2d42; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 24px; font-weight: 700; color: var(--primary); text-align: center; border-bottom: 1px solid #eee; }
        .nav-links { list-style: none; padding: 20px 0; flex: 1; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: block; padding: 15px 25px; color: #6c757d; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: rgba(67, 97, 238, 0.1); color: var(--primary); border-right: 4px solid var(--primary); }
        
        /* Main Content */
        .main-content { flex: 1; padding: 30px; display: flex; flex-direction: column; }
        .top-navbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #fff; padding: 15px 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .page-title { font-size: 20px; font-weight: 700; }
        
        .content-box { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        .table th, .table td { border-bottom: 1px solid #eee; padding: 15px 10px; text-align: left; }
        .table th { background: #f8f9fa; font-weight: 600; color: #6c757d; text-transform: uppercase; font-size: 13px; }
        .table tbody tr:hover { background: #fdfdfe; }
        
        .status-select { padding: 8px; border-radius: 6px; border: 1px solid #ddd; outline: none; font-weight: bold; cursor: pointer; }
        .btn-update { padding: 8px 15px; border-radius: 6px; border: none; background: #2ecc71; color: white; font-weight: bold; cursor: pointer; }
        .btn-update:hover { background: #27ae60; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">❖ Bảng Điều Khiển</a></li>
            <li><a href="admin_sanpham.php">✧ Quản Lý Sản Phẩm</a></li>
            <li><a href="admin_donhang.php" class="active">🛒 Quản Lý Đơn Hàng</a></li>
            <li><a href="thong_ke_truy_cap.php">📊 Thống Kê Truy Cập</a></li>
            <li><a href="index.php" target="_blank">🌐 Xem Trang Web</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="top-navbar">
            <div class="page-title">🛒 Quản Lý Đơn Hàng Mới Nhất</div>
        </div>

        <div class="content-box">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách Hàng</th>
                        <th>Điện Thoại</th>
                        <th>Ngày Đặt</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($res_dh) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($res_dh)): 
                            $tt = isset($row['trang_thai']) ? (int)$row['trang_thai'] : 0;
                        ?>
                            <tr>
                                <td><b>#<?php echo $row['ma_dh']; ?></b></td>
                                <td><?php echo htmlspecialchars($row['ten_kh']); ?></td>
                                <td><?php echo htmlspecialchars($row['dien_thoai']); ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($row['ngay_dat'])); ?></td>
                                <td style="color: #e74c3c; font-weight: bold;"><?php echo number_format($row['tong_tien'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <form method="post" style="display:flex; gap:10px;">
                                        <input type="hidden" name="ma_dh" value="<?php echo $row['ma_dh']; ?>">
                                        <select name="trang_thai" class="status-select" style="
                                            <?php if($tt==0) echo 'background:#fff3cd; color:#856404;';
                                                elseif($tt==1) echo 'background:#cce5ff; color:#004085;';
                                                elseif($tt==2) echo 'background:#d4edda; color:#155724;';
                                                elseif($tt==3) echo 'background:#f8d7da; color:#721c24;';
                                            ?>
                                        ">
                                            <option value="0" <?php echo $tt==0?'selected':''; ?>>Chờ xác nhận</option>
                                            <option value="1" <?php echo $tt==1?'selected':''; ?>>Đang giao hàng</option>
                                            <option value="2" <?php echo $tt==2?'selected':''; ?>>Hoàn thành</option>
                                            <option value="3" <?php echo $tt==3?'selected':''; ?>>Hủy đơn</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn-update">Lưu</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="chi_tiet_don_hang.php?id=<?php echo $row['ma_dh']; ?>&admin=1" target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: none;">Xem chi tiết</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align: center; color: #999;">Chưa có đơn hàng nào!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
