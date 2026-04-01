<?php
session_start();
include 'ket_noi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liên hệ - Sage Fashion</title>
    <style>
        .contact-container { max-width: 800px; margin: 50px auto; padding: 40px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .contact-container h2 { font-size: 28px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;}
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333;}
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: inherit;}
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #4186e0ff; }
        .btn-submit { background: #000; color: white; padding: 15px 40px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; transition: 0.3s; margin-top: 10px;}
        .btn-submit:hover { background: #4186e0ff; }
        .success-msg { color: #28a745; font-weight: bold; margin-top: 20px; padding: 20px; background: #e9f7ef; border-radius: 4px; border: 1px solid #c3e6cb;}
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <?php include 'menu.php'; ?>

    <div class="contact-container">
        <h2>Liên hệ với chúng tôi</h2>
        <p style="color: #666; margin-bottom: 30px;">Nếu bạn có bất kỳ câu hỏi nào về sản phẩm hoặc đơn hàng, hãy gửi tin nhắn cho Sage Fashion nhé!</p>

        <?php
        // Xử lý khi khách bấm nút Gửi
        if(isset($_POST["ten"])){
            echo "<div class='success-msg'>Cảm ơn bạn <b>".$_POST["ten"]."</b>, chúng tôi đã nhận được thông tin và sẽ liên hệ lại qua số điện thoại/email bạn cung cấp trong thời gian sớm nhất!</div>";
            echo "<a href='index.php' style='display: inline-block; margin-top: 20px; color: #4186e0ff; text-decoration: none; font-weight: bold;'>← Quay lại cửa hàng</a>";
        } else {
        ?>
        <form method="post">
            <div class="form-group">
                <label>Họ và tên của bạn</label>
                <input type="text" name="ten" placeholder="Nhập đầy đủ họ tên..." required>
            </div>
            <div class="form-group">
                <label>Số điện thoại / Email</label>
                <input type="text" name="sdt" placeholder="Để lại cách thức liên lạc..." required>
            </div>
            <div class="form-group">
                <label>Nội dung tin nhắn</label>
                <textarea name="noidung" rows="5" placeholder="Bạn muốn hỏi gì..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">GỬI TIN NHẮN</button>
        </form>
        <?php } ?>
    </div>

    <?php include 'footer.php'; ?>
    
</body>
</html>