<style>
    /* Nút Mua ngay */
    .btn-mua {
        display: block;
        margin-bottom: 8px;
        background: #4186e0ff;
        color: white;
        text-decoration: none;
        padding: 10px;
        font-weight: bold;
        border-radius: 4px;
        transition: 0.3s;
    }
    .btn-mua:hover {
        background: #2d5e9eff;
    }

    /* Nút Thêm vào giỏ */
    .btn-them {
        display: block;
        background: #fff;
        color: #000;
        border: 1px solid #000;
        text-decoration: none;
        padding: 10px;
        font-weight: bold;
        border-radius: 4px;
        transition: 0.3s;
    }
    .btn-them:hover {
        background: #504f4fff;
        border: 1px solid #504f4fff;
        color: #ffffffff;
    }
</style>

<div class='product-item' style="background: white; padding: 15px; text-align: center; width: calc(25% - 20px); box-sizing: border-box; box-shadow: 0 4px 8px rgba(0,0,0,0.05); border-radius: 8px;">
    <img src='images/<?php echo $row['hinh_anh']; ?>' style="width: 100%; height: 280px; object-fit: cover; border-radius: 4px;">
    <p style="margin: 15px 0 5px; font-size: 16px; color: #333;"><b><?php echo $row['ten_sp']; ?></b></p>
    
    <div class='price' style="color: #e74c3c; font-weight: bold; font-size: 18px; margin-bottom: 15px;">
        <?php echo number_format($row['gia_ban'], 0, ',', '.'); ?>đ
    </div>
    
    <?php if(isset($_SESSION['user_name'])): ?>
        <a href="giohang.php?them_id=<?php echo $row['ma_sp']; ?>&kieu=mua" class="btn-mua">Mua ngay</a>
        <a href="giohang.php?them_id=<?php echo $row['ma_sp']; ?>&kieu=them" class="btn-them" onclick="alert('Đã thêm sản phẩm vào giỏ hàng thành công!')">Thêm vào giỏ hàng</a>
    <?php else: ?>
        <a href="tai_khoan.php" class="btn-mua" onclick="alert('Bạn cần đăng nhập để mua sản phẩm này!');">Mua ngay</a>
        <a href="tai_khoan.php" class="btn-them" onclick="alert('Bạn cần đăng nhập để thêm vào giỏ hàng!');">Thêm vào giỏ hàng</a>
    <?php endif; ?>
</div>