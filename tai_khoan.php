<?php
session_start();
include 'ket_noi.php';

$thong_bao = "";

// Xử lý Đăng Ký
if (isset($_POST['dang_ky'])) {
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $dia_chi = mysqli_real_escape_string($conn, $_POST['dia_chi']);
    $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT); 

    $check_email = mysqli_query($conn, "SELECT * FROM tai_khoan WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $thong_bao = "<p style='color: red;'>Email này đã được đăng ký!</p>";
    } else {
        $sql = "INSERT INTO tai_khoan (ho_ten, email, mat_khau, dien_thoai, dia_chi) 
                VALUES ('$ho_ten', '$email', '$mat_khau', '$sdt', '$dia_chi')";
        if (mysqli_query($conn, $sql)) {
            $thong_bao = "<p style='color: green;'>Đăng ký thành công! Vui lòng đăng nhập.</p>";
        } else {
            $thong_bao = "<p style='color: red;'>Lỗi: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Xử lý Đăng Nhập
if (isset($_POST['dang_nhap'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mat_khau_nhap = $_POST['mat_khau'];

    $sql = "SELECT * FROM tai_khoan WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pass_db = $row['mat_khau'];

        // Kiểm tra mật khẩu (Hỗ trợ cả Bcrypt và MD5)
        if (password_verify($mat_khau_nhap, $pass_db) || md5($mat_khau_nhap) == $pass_db) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['ho_ten'];
            $_SESSION['role'] = isset($row['vai_tro']) ? (int)$row['vai_tro'] : 0;
            
            // Chuyển hướng sang trang chủ sau khi đăng nhập thành công
            header("Location: index.php"); 
            exit();
        } else { 
            $thong_bao = "<p style='color: red;'>Sai mật khẩu!</p>"; 
        }
    } else { 
        $thong_bao = "<p style='color: red;'>Email không tồn tại!</p>"; 
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tài khoản - Coolmate</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; margin: 0; }
        .account-container { max-width: 900px; margin: 50px auto; display: flex; gap: 30px; padding: 0 20px; }
        .box { flex: 1; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .box h3 { margin-top: 0; text-transform: uppercase; font-size: 18px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;}
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background: #000; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn-submit:hover { background: #4186e0ff; }
        
        /* Profile & Order History */
        .profile-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .profile-box { text-align: center; padding: 40px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .btn-logout { display: inline-block; margin-top: 15px; padding: 8px 20px; background: #e74c3c; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
        
        .order-history { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .order-table th, .order-table td { border-bottom: 1px solid #eee; padding: 15px; text-align: left; }
        .order-table th { background-color: #f9f9f9; color: #333; font-size: 14px; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; background: #e1f5fe; color: #01579b; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <?php if(isset($_SESSION['user_name'])): ?>
        <div class="profile-container">
            <div class="profile-box">
                <img src="images/account-after.png" style="height: 60px; margin-bottom: 10px;">
                <h2>Xin chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <p style="color: #666;">Chào mừng bạn quay trở lại với gian hàng Thời Trang.</p>
                <div style="margin-top: 15px; display: flex; gap: 15px; justify-content: center;">
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                    <a href="admin_dashboard.php" style="padding: 10px 20px; background: #e74c3c; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px;">Quản trị hệ thống (Admin)</a>
                    <?php endif; ?>
                    <a href="dang_xuat.php" style="padding: 10px 20px; background: #555; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px;">Đăng xuất</a>
                </div>
            </div>

            <div class="order-history">
                <h3 style="margin-top: 0; border-bottom: 2px solid #f4f4f4; padding-bottom: 15px;">Lịch sử đơn hàng</h3>
                <?php
                $id_user = $_SESSION['user_id'];
                $sql_orders = "SELECT * FROM don_hang WHERE id_tai_khoan = $id_user ORDER BY ngay_dat DESC";
                $res_orders = mysqli_query($conn, $sql_orders);

                if (mysqli_num_rows($res_orders) > 0): ?>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($order = mysqli_fetch_assoc($res_orders)): ?>
                                <tr>
                                    <td style="font-weight: bold;">#<?php echo $order['ma_dh']; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($order['ngay_dat'])); ?></td>
                                    <td style="color: #e74c3c; font-weight: bold;"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <?php 
                                            // 0: Chờ xác nhận, 1: Đang giao, 2: Hoàn thành, 3: Đã hủy
                                            $tt = isset($order['trang_thai']) ? (int)$order['trang_thai'] : 0;
                                            if ($tt == 0) echo '<span class="status-badge" style="background:#fff3cd; color:#856404;">Chờ xác nhận</span>';
                                            elseif ($tt == 1) echo '<span class="status-badge" style="background:#cce5ff; color:#004085;">Đang giao hàng</span>';
                                            elseif ($tt == 2) echo '<span class="status-badge" style="background:#d4edda; color:#155724;">Hoàn thành</span>';
                                            elseif ($tt == 3) echo '<span class="status-badge" style="background:#f8d7da; color:#721c24;">Đã hủy</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="chi_tiet_don_hang.php?id=<?php echo $order['ma_dh']; ?>" 
                                           style="color: #4186e0; text-decoration: none; font-size: 14px; font-weight: bold;">Xem chi tiết</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 30px; color: #999;">
                        <p>Bạn chưa có đơn hàng nào.</p>
                        <a href="index.php" style="color: #4186e0; text-decoration: none;">Mua sắm ngay!</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div style="text-align: center; margin-top: 20px;"><?php echo $thong_bao; ?></div>
        <div class="account-container">
            <div class="box">
                <h3>Đăng nhập</h3>
                <form method="post">
                    <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="form-group"><label>Mật khẩu</label><input type="password" name="mat_khau" required></div>
                    <button type="submit" name="dang_nhap" class="btn-submit">ĐĂNG NHẬP</button>
                </form>
            </div>
            <div class="box">
                <h3>Đăng ký mới</h3>
                <form method="post">
                    <div class="form-group"><label>Họ và tên</label><input type="text" name="ho_ten" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="form-group"><label>Số điện thoại</label><input type="text" name="sdt" required></div>
                    <div class="form-group"><label>Địa chỉ</label><input type="text" name="dia_chi" required></div>
                    <div class="form-group"><label>Mật khẩu</label><input type="password" name="mat_khau" required></div>
                    <button type="submit" name="dang_ky" class="btn-submit" style="background: #4186e0ff;">ĐĂNG KÝ</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
</body>
</html>