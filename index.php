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
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: flex-start;">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM san_pham");
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
    <?php include 'footer.php'; ?>
</body>
</html>