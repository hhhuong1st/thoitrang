<?php 
@session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>404 - Không tìm thấy trang</title>
</head>
<body style="margin: 0; padding: 0;">
    <?php include 'menu.php'; ?>
<style>
    .custom-404-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        padding: 40px 20px;
        text-align: center;
    }
    .custom-404-content {
        max-width: 600px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 60px 40px;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,1);
        animation: scaleUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .error-code {
        font-size: 120px;
        font-weight: 900;
        margin: 0;
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
        text-shadow: 0 10px 20px rgba(255, 107, 107, 0.2);
    }
    .error-title {
        font-size: 28px;
        font-weight: 700;
        color: #2D3436;
        margin: 20px 0 10px;
    }
    .error-desc {
        font-size: 16px;
        color: #636E72;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    .back-home-btn {
        display: inline-block;
        padding: 16px 36px;
        font-size: 16px;
        font-weight: 600;
        color: #ffffff;
        background: linear-gradient(135deg, #6C5CE7 0%, #A29BFE 100%);
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
    }
    .back-home-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(108, 92, 231, 0.4);
        color: #ffffff;
    }
    @keyframes scaleUp {
        0% { opacity: 0; transform: scale(0.95) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>

<div class="custom-404-wrapper">
    <div class="custom-404-content">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Ôi hỏng! Không tìm thấy trang</h2>
        <p class="error-desc">Trang hoặc thiết kế bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc hiện không tồn tại. Hãy quay lại cửa hàng để tiếp tục khám phá nhé!</p>
        <a href="index.php" class="back-home-btn">Quay về trang chủ</a>
    </div>
</div>

    <?php include 'footer.php'; ?>
</body>
</html>