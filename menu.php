<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    
    /* Reset CSS cơ bản */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8; }
    
    /* Top bar*/
    .top-bar { background-color: #555; color: white; text-align: center; padding: 8px 0; font-size: 13px; font-weight: bold; }
    
    /* Header chính */
    .header-main { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 10px 50px; 
        background: white; 
        border-bottom: 1px solid #eee;
        position: relative; 
        height: 60px;
    }
    
    /* Logo bên trái */
    .logo img { height: 60px; }

    .nav-menu li a.active {
        color: #4186e0ff !important; 
        border-bottom: 2px solid #4186e0ff;
    }
    /* Menu ở giữa*/
    .nav-menu { 
        display: flex; 
        list-style: none; 
        margin: 0; 
        padding: 0; 
        gap: 40px; 
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    .nav-menu li a { 
        text-decoration: none; 
        color: #000; 
        font-weight: 700; 
        font-size: 15px; 
        text-transform: uppercase; 
        transition: 0.2s; 
    }
    .nav-menu li a:hover { color: #e74c3c; }
    
    /* Khu vực bên phải */
    .header-right { display: flex; align-items: center; gap: 20px; }
    .search-box { padding: 8px 15px; border: 1px solid #ccc; border-radius: 20px; outline: none; width: 180px; height: 25px }
    .cart-btn { text-decoration: none; color: black; font-weight: bold; font-size: 14px; }

    .header-right { 
        display: flex; 
        align-items: center; 
        gap: 15px; /* Khoảng cách giữa các icon */
    }
    
    .header-right img {
        height: 24px; /* Độ cao của icon*/
        width: auto;
        cursor: pointer;
        transition: 0.3s;
    }

    .header-right img:hover {
        opacity: 0.7; /* Hiệu ứng mờ nhẹ khi di chuột vào */
    }

    .cart-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: black;
    }

    .cart-count {
        background: #e74c3c;
        color: white;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 50%;
        position: absolute;
        top: -8px;
        right: -8px;
        font-weight: bold;
    }
</style>

<div class="top-bar">Miễn phí vận chuyển cho đơn hàng từ 200K</div>
<header class="header-main">
    <div class="logo">
        <a href="index.php"><img src="images/logo.png" alt="Logo"></a>
    </div>
    
    <ul class="nav-menu">
        <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Trang chủ</a></li>
        <li><a href="nam.php" class="<?php echo ($current_page == 'nam.php') ? 'active' : ''; ?>">Nam</a></li>
        <li><a href="nu.php" class="<?php echo ($current_page == 'nu.php') ? 'active' : ''; ?>">Nữ</a></li>
        <li><a href="thong_ke_truy_cap.php" class="<?php echo ($current_page == 'thong_ke_truy_cap.php') ? 'active' : ''; ?>">Thống kê</a></li>
        <li><a href="lien_he.php" class="<?php echo ($current_page == 'lien_he.php') ? 'active' : ''; ?>">Liên hệ</a></li>
    </ul>
    
    <div class="header-right">
        <input type="text" class="search-box" placeholder="Tìm kiếm...">

        <a href="tai_khoan.php" style="display: flex; align-items: center; gap: 5px; text-decoration: none; color: #333; font-weight: bold; font-size: 14px;">
            <img src="images/icon-account.png" alt="Tài khoản" title="Tài khoản của tôi">
            <?php if(isset($_SESSION['user_name'])): ?>
                <span>Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <?php endif; ?>
        </a>

        <a href="gio_hang.php" class="cart-wrapper">
            <img src="images/icon-cart.png" alt="Giỏ hàng">
            <?php 
            $soluong = isset($_SESSION['gio_hang']) ? array_sum($_SESSION['gio_hang']) : 0;
            if($soluong > 0) {
                echo '<span class="cart-count">'.$soluong.'</span>';
            }
            ?>
        </a>
    </div>
</header>