<?php 
session_start();
include 'ket_noi.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thời Trang Nam</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    

    <div class="container" style="max-width: 1200px; margin: 50px auto; padding: 0 20px;">
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 30px;">THỜI TRANG NAM</h2>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: flex-start;">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM san_pham WHERE ma_dm = 1");
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    include 'item_sanpham.php';
                }
            } else {
                echo "<p style='width: 100%; text-align: center;'>Hiện chưa có sản phẩm nam nào.</p>";
            }
            ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>