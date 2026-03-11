<?php
session_start();
include 'ket_noi.php';

// Xóa từng sản phẩm
if (isset($_GET['xoa_id'])) {
    $id_xoa = $_GET['xoa_id'];
    unset($_SESSION['giohang'][$id_xoa]);
    header("Location: giohang.php");
    exit();
}

// Xóa tất cả
if (isset($_GET['xoa']) && $_GET['xoa'] == 'tatca') {
    unset($_SESSION['giohang']); 
    header("Location: giohang.php");
    exit();
}

// Thêm sản phẩm
if (isset($_GET['them_id'])) {
    $id = $_GET['them_id'];
    $kieu = isset($_GET['kieu']) ? $_GET['kieu'] : 'mua';
    
    // BƯỚC MỚI: Kiểm tra số lượng tồn kho thực tế từ database
    $sql_kho = "SELECT so_luong FROM san_pham WHERE ma_sp = $id";
    $res_kho = mysqli_query($conn, $sql_kho);
    $row_kho = mysqli_fetch_assoc($res_kho);
    $ton_kho_thuc_te = $row_kho['so_luong']; // Lấy con số trong CSDL

    // Tính số lượng mà khách đang muốn có trong giỏ
    $sl_hien_tai_trong_gio = isset($_SESSION['giohang'][$id]) ? $_SESSION['giohang'][$id] : 0;
    $sl_muon_them = $sl_hien_tai_trong_gio + 1;

    // So sánh: Nếu muốn mua nhiều hơn trong kho thì báo lỗi
    if ($sl_muon_them > $ton_kho_thuc_te) {
        echo "<script>
                alert('Rất tiếc! Trong kho chỉ còn $ton_kho_thuc_te sản phẩm, bạn không thể thêm nữa.');
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
              </script>";
        exit();
    } else {
        // Nếu còn hàng thì mới cho cộng dồn vào Session
        $_SESSION['giohang'][$id] = $sl_muon_them;
    }
    
    // Chuyển hướng như cũ
    if ($kieu == 'them') {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: giohang.php");
    }
    exit();
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
        
        /* CỘT TRÁI: FORM */
        .col-left { flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: sticky; top: 20px; }
        .col-left h3 { margin-top: 0; text-transform: uppercase; font-size: 18px; border-bottom: 1px solid #a8a5a5ff; padding-bottom: 15px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px; }

        /* CỘT PHẢI: TÓM TẮT ĐƠN HÀNG */
        .col-right { flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: sticky; top: 20px; }
        .col-right h3 { margin-top: 0; text-transform: uppercase; font-size: 18px; border-bottom: 1px solid #a8a5a5ff; padding-bottom: 15px; }
        
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .summary-table td { padding: 15px 0; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
        
        .prod-info { display: flex; align-items: center; font-size: 14px; gap: 10px; }
        .prod-img { width: 50px; height: 60px; object-fit: cover; border-radius: 4px; }
        
        /* Nút xóa từng sản phẩm */
        .btn-remove-item { color: #ccc; text-decoration: none; font-size: 18px; font-weight: bold; padding: 0 5px; transition: 0.3s; }
        .btn-remove-item:hover { color: #e74c3c; }
        
        .total-box { margin-top: 20px; padding-top: 15px; border-top: 2px solid #eee; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px; }
        .final-price { font-size: 22px; font-weight: bold; color: #e74c3c; }
        
        .btn-submit { width: 100%; background: #000; color: white; padding: 18px; border: none; border-radius: 4px; font-weight: bold; font-size: 16px; cursor: pointer; margin-top: 20px; }
        .empty-cart { text-align: center; padding: 100px; background: white; margin: 40px auto; max-width: 600px; border-radius: 8px; }
        .empty-cart p {padding-bottom:10px}
        .btn-remove-item {
        color: #e74c3c;
        text-decoration: none;
        font-size: 18px;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        }
        .btn-remove-item:hover {
            transform: scale(1.2);
            color: #c0392b;
        }
        
        .summary-table td {
            padding: 15px 0;
            border-bottom: 1px solid #f9f9f9;
            vertical-align: middle;
        }
        .prod-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .prod-details {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 2;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <?php if (!empty($_SESSION['giohang'])): ?>
    <form action="hoan-tat.php" method="post">
        <div class="main-container">
            <div class="col-left">
                <h3>Thông tin giao hàng</h3>
                <div class="form-group">
                    <label>Họ và tên người nhận</label>
                    <input type="text" name="ten" placeholder="Nhập đầy đủ họ tên" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="sdt" placeholder="Số điện thoại liên hệ" required>
                </div>
                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <textarea name="dia_chi" rows="3" placeholder="Số nhà, tên đường..." required></textarea>
                </div>
                <h3>Thanh toán</h3>
                <label style="display: flex; align-items: center; gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 4px; cursor: pointer;">
                    <input type="radio" name="pttt" value="COD" checked>
                    <span>Thanh toán khi nhận hàng (COD)</span>
                </label>
            </div>

            <div class="col-right">
                <h3>Đơn hàng (<?php echo array_sum($_SESSION['giohang']); ?> món)</h3>
                <table class="summary-table">
    <?php 
    foreach ($_SESSION['giohang'] as $id_sp => $so_luong): 
        $sql = "SELECT * FROM san_pham WHERE ma_sp = $id_sp";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0):
            $row = mysqli_fetch_assoc($result);
            $thanh_tien = $row['Gia_ban'] * $so_luong;
            $tong_cong += $thanh_tien;
    ?>
    <tr>
        <td>
            <div class="prod-row">
                <div class="prod-details">
                    <a href="giohang.php?xoa_id=<?php echo $id_sp; ?>" class="btn-remove-item" title="Xóa món này" onclick="return confirm('Bạn muốn bỏ sản phẩm này?')">
                       &#128465; 
                    </a>
                    
                    <img src="images/<?php echo $row['hinh_anh']; ?>" class="prod-img">
                    
                    <div>
                        <b style="font-size: 14px;"><?php echo $row['ten_sp']; ?></b><br>
                        <small style="color: #888;">Size: <?php echo $row['size']; ?> | SL: <?php echo $so_luong; ?></small>
                    </div>
                </div>

                <div style="text-align: right; font-weight: bold; flex: 1;">
                    <?php echo number_format($thanh_tien, 0, ',', '.'); ?>đ
                </div>
            </div>
        </td>
    </tr>
    <?php endif; endforeach; ?>
</table>

                <div class="total-box">
                    <div class="total-row"><span>Tạm tính:</span><span><?php echo number_format($tong_cong, 0, ',', '.'); ?>đ</span></div>
                    <div class="total-row"><span>Phí vận chuyển:</span><span style="color: #28a745;">Miễn phí</span></div>
                    <div class="total-row" style="margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 15px;">
                        <span style="font-weight: bold;">TỔNG CỘNG:</span>
                        <span class="final-price"><?php echo number_format($tong_cong, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">XÁC NHẬN ĐẶT HÀNG</button>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="giohang.php?xoa=tatca" style="color: #e74c3c; font-size: 13px; text-decoration: none;" onclick="return confirm('Xóa sạch giỏ hàng?')">Xóa tất cả</a>
                </div>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div class="empty-cart">
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="index.php" style="background: #000; color: white; padding: 15px 30px; text-decoration: none; border-radius: 4px; font-weight: bold;">QUAY LẠI MUA SẮM</a>
        </div>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
</body>
</html>