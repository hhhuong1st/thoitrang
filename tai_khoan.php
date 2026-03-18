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
        // Lưu đầy đủ 5 thông tin vào database
        $sql = "INSERT INTO tai_khoan (ho_ten, email, mat_khau, dien_thoai, dia_chi) 
                VALUES ('$ho_ten', '$email', '$mat_khau', '$sdt', '$dia_chi')";
        if (mysqli_query($conn, $sql)) {
            $thong_bao = "<p style='color: green;'>Đăng ký thành công! Vui lòng đăng nhập.</p>";
        } else {
            $thong_bao = "<p style='color: red;'>Lỗi: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Xử lý Đăng Nhập (Giữ nguyên logic cũ)
if (isset($_POST['dang_nhap'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mat_khau_nhap = $_POST['mat_khau'];
    $sql = "SELECT * FROM tai_khoan WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($mat_khau_nhap, $row['mat_khau'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['ho_ten'];
            header("Location: tai_khoan.php");
            exit();
        } else { $thong_bao = "<p style='color: red;'>Sai mật khẩu!</p>"; }
    } else { $thong_bao = "<p style='color: red;'>Email không tồn tại!</p>"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tài khoản - Fashion Shop</title>
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
        .profile-box { text-align: center; padding: 50px; background: white; max-width: 600px; margin: 50px auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-logout { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #e74c3c; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <?php if(isset($_SESSION['user_name'])): ?>
        <div class="profile-box">
            <h2>Xin chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
            <p>Chào mừng bạn quay trở lại với cửa hàng.</p>
            <a href="dang_xuat.php" class="btn-logout">Đăng xuất</a>
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