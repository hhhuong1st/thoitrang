<?php 
session_start();
include 'ket_noi.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Fashion</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    

    <div class="banner" style="width: 100%; overflow: hidden; background-color: #f0f0f0;">
        <img src="images/banner.jpeg" alt="Banner" style="width: 100%; height: auto; display: block;">
    </div>

    <div class="container" style="max-width: 1200px; margin: 50px auto; padding: 0 20px;">
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 30px;">SẢN PHẨM MỚI NHẤT</h2>
        
        <div class="product-grid">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM san_pham WHERE ma_sp BETWEEN 1 AND 6");
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    include 'item_sanpham.php';
                }
            } else {
                echo "<p style='width: 100%; text-align: center;'>Hiện chưa có sản phẩm nào.</p>";
            }
            ?>
        </div>
    </div>

    <!-- PHẦN BANNER PICKLEBALL MỚI THIẾT KẾ -->
    <div class="banner" style="position: relative; width: 100%; overflow: hidden; background-color: #f8f8f8; margin-top: 60px;">
        <img src="images/banner-1.jpg" alt="Pickleball Banner" style="width: 100%; height: auto; display: block;">
        
        <!-- LỚP PHỦ NỘI DUNG TRÊN BANNER -->
        <div style="position: absolute; bottom: 10%; left: 5%; color: white; text-align: left; pointer-events: none;">
            <h1 style="font-size: 4.5vw; margin: 0; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);">PICKLEBALL</h1>
            <div style="margin-top: 20px; pointer-events: auto;">
                <a href="#pickleball-section" style="display: inline-block; background: white; color: black; padding: 12px 40px; border-radius: 30px; text-decoration: none; font-weight: 700; font-size: 14px; text-transform: uppercase; transition: 0.3s;">MUA NGAY</a>
            </div>
        </div>
    </div>

    <div id="pickleball-section" class="container" style="max-width: 1200px; margin: 50px auto; padding: 0 20px;">
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 30px;">SẢN PHẨM PICKLEBALL</h2>
        
        <div class="product-grid">
            <?php
            $result_pk = mysqli_query($conn, "SELECT * FROM san_pham WHERE ten_sp LIKE '%Pickleball%' OR ten_sp LIKE '%PKB%'");
            if (mysqli_num_rows($result_pk) > 0) {
                while($row = mysqli_fetch_assoc($result_pk)) {
                    include 'item_sanpham.php';
                }
            } else {
                echo "<p style='width: 100%; text-align: center;'>Hiện chưa có sản phẩm Pickleball nào.</p>";
            }
            ?>
        </div>
    </div>

    <!-- PHẦN BANNER CHẠY BỘ MỚI THIẾT KẾ -->
    <div class="banner" style="position: relative; width: 100%; overflow: hidden; background-color: #f8f8f8; margin-top: 60px;">
        <img src="images/banner-2.jpg" alt="Running Banner" style="width: 100%; height: auto; display: block;">
        
        <!-- LỚP PHỦ NỘI DUNG TRÊN BANNER -->
        <div style="position: absolute; bottom: 10%; left: 5%; color: white; text-align: left; pointer-events: none;">
            <h1 style="font-size: 4.5vw; margin: 0; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);">ĐỒ CHẠY BỘ</h1>
            <div style="margin-top: 20px; pointer-events: auto;">
                <a href="#running-section" style="display: inline-block; background: white; color: black; padding: 12px 40px; border-radius: 30px; text-decoration: none; font-weight: 700; font-size: 14px; text-transform: uppercase; transition: 0.3s;">MUA NGAY</a>
            </div>
        </div>
    </div>

    <div id="running-section" class="container" style="max-width: 1200px; margin: 50px auto; padding: 0 20px;">
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 30px;">SẢN PHẨM CHẠY BỘ</h2>
        
        <div class="product-grid">
            <?php
            $result_rn = mysqli_query($conn, "SELECT * FROM san_pham WHERE ma_sp BETWEEN 11 AND 14");
            if (mysqli_num_rows($result_rn) > 0) {
                while($row = mysqli_fetch_assoc($result_rn)) {
                    include 'item_sanpham.php';
                }
            } else {
                echo "<p style='width: 100%; text-align: center;'>Hiện chưa có sản phẩm chạy bộ nào.</p>";
            }
            ?>
        </div>
    </div>

    <!-- PHẦN BANNER MÙA ĐÔNG MỚI -->
    <div class="container" style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <div style="width: 100%; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <img src="images/ao-moi-mua-dong.jpg" alt="Winter Collection" style="width: 100%; height: auto; display: block;">
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>