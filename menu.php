<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    
    /* Reset CSS cơ bản */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8; display: flex; flex-direction: column; min-height: 100vh; }
    
    /* Top bar*/
    .top-bar { background-color: #555; color: white; text-align: center; padding: 8px 0; font-size: 13px; font-weight: bold; }
    
    /* Header chính */
    .header-main { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 10px 60px; /* Thụt vào lề nhiều hơn */ 
        background: white; 
        border-bottom: 1px solid #eee;
        position: sticky; 
        top: 0;
        z-index: 1000;
        min-height: 60px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        flex-wrap: wrap;
        gap: 15px;
    }
    
    /* Logo bên trái */
    .logo { flex: 1; display: flex; justify-content: flex-start; }
    .logo img { height: 60px; }

    .nav-menu li a.active {
        color: #4186e0ff !important; 
        border-bottom: 2px solid #4186e0ff;
    }
    /* Menu ở giữa */
    .nav-menu { 
        display: flex; 
        list-style: none; 
        margin: 0; 
        padding: 0; 
        gap: 30px; 
        justify-content: center;
        /* Giữ nguyên chiều rộng vừa đủ để luôn ở chính giữa */
        flex: 0 1 auto; 
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
    .header-right { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        flex: 1; 
        justify-content: flex-end; 
    }
    .search-box { padding: 8px 15px; border: 1px solid #ccc; border-radius: 20px; outline: none; width: 180px; height: 25px }
    .cart-btn { text-decoration: none; color: black; font-weight: bold; font-size: 14px; }
    
    .header-right img {
        height: 24px;
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
    
    /* Giao diện sản phẩm đồng bộ */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        width: 100%;
    }
    .product-item { width: 100%; box-sizing: border-box; }
    
    @media (max-width: 1200px) {
        .product-grid { grid-template-columns: repeat(3, 1fr); }
        .nav-menu { flex-wrap: wrap; gap: 15px; order: 3; width: 100%; flex: unset; }
        .header-right { flex: 1; justify-content: flex-end; }
    }
    @media (max-width: 900px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .header-main { padding: 15px; gap: 15px; position: relative; }
        .header-right { justify-content: flex-start; width: 100%; flex-wrap: wrap; }
        .main-container { flex-direction: column !important; }
        .col-left, .col-right { width: 100% !important; flex: unset !important; box-sizing: border-box; }
        .search-box { width: 100%; max-width: 250px; }
    }
    @media (max-width: 480px) {
        .product-grid { grid-template-columns: 1fr; }
        .header-right { flex-direction: column; align-items: stretch; gap: 10px; width: 100%; }
        .search-box { max-width: 100%; }
        .nav-menu { gap: 10px; flex-direction: column; text-align: center; }
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
        <li><a href="lien_he.php" class="<?php echo ($current_page == 'lien_he.php') ? 'active' : ''; ?>">Liên hệ</a></li>
    </ul>
    
    <div class="header-right">
        <input type="text" class="search-box" placeholder="Tìm kiếm...">

        <a href="tai_khoan.php" style="display: flex; align-items: center; gap: 5px; text-decoration: none; color: #333; font-weight: bold; font-size: 14px;">
            <?php if(isset($_SESSION['user_name'])): ?>
                <img src="images/account-after.png" alt="Tài khoản" title="Tài khoản của tôi">
            <?php else: ?>
                <img src="images/icon-account.png" alt="Tài khoản" title="Tài khoản của tôi">
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

<div id="toast-container">
<?php if (isset($_SESSION['toast_success'])): ?>
<div class="toast-item toast-success show"><?php echo htmlspecialchars($_SESSION['toast_success']); ?></div>
<?php unset($_SESSION['toast_success']); endif; ?>

<?php if (isset($_SESSION['toast_error'])): ?>
<div class="toast-item toast-error show"><?php echo htmlspecialchars($_SESSION['toast_error']); ?></div>
<?php unset($_SESSION['toast_error']); endif; ?>
</div>

<style>
#toast-container {
    position: fixed;
    z-index: 9999;
    right: 30px;
    bottom: 30px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.toast-item {
    visibility: hidden; 
    min-width: 250px; 
    background-color: #333; 
    color: #fff; 
    text-align: center; 
    border-radius: 4px; 
    padding: 16px; 
    font-size: 15px; 
    box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
}

.toast-item.toast-success { background-color: #2ecc71; color: white; }
.toast-item.toast-error { background-color: #e74c3c; color: white; }

.toast-item.show {
    visibility: visible; 
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s forwards;
    animation: fadein 0.5s, fadeout 0.5s 2.5s forwards;
}

@-webkit-keyframes fadein {
    from {transform: translateY(20px); opacity: 0;} 
    to {transform: translateY(0); opacity: 1;}
}
@keyframes fadein {
    from {transform: translateY(20px); opacity: 0;}
    to {transform: translateY(0); opacity: 1;}
}
@-webkit-keyframes fadeout {
    from {opacity: 1;} 
    to {opacity: 0;}
}
@keyframes fadeout {
    from {opacity: 1;}
    to {opacity: 0;}
}
</style>

<script>
window.addEventListener('load', function() {
    var toasts = document.querySelectorAll('.toast-item.show');
    toasts.forEach(function(toast) {
        setTimeout(function(){ 
            toast.className = toast.className.replace(" show", ""); 
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    });
});

function addToCartAjax(url) {
    fetch(url + '&ajax=1')
    .then(res => res.json())
    .then(data => {
        var container = document.getElementById("toast-container");
        if (!container) {
            container = document.createElement("div");
            container.id = "toast-container";
            document.body.appendChild(container);
        }
        var toast = document.createElement("div");
        toast.className = data.status === 'success' ? 'toast-item toast-success show' : 'toast-item toast-error show';
        if (data.status === 'success') {
            toast.innerText = 'Đã thêm sản phẩm vào giỏ hàng thành công!';
            // Update cart count
            var countEl = document.querySelector('.cart-count');
            if(countEl) {
                countEl.innerText = data.cart_total;
            } else {
                var cartWrapper = document.querySelector('.cart-wrapper');
                if(cartWrapper && data.cart_total > 0) {
                    cartWrapper.innerHTML += '<span class="cart-count">' + data.cart_total + '</span>';
                }
            }
        } else {
            toast.innerText = data.msg;
        }
        
        container.appendChild(toast);
        
        setTimeout(function(){ 
            toast.className = toast.className.replace(" show", ""); 
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    })
    .catch(err => console.error(err));
}
</script>