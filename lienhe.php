<h2>Liên hệ đặt hàng</h2>
<form method="post">
    <input type="text" name="ten" placeholder="Tên của bạn"><br><br>
    <input type="text" name="sdt" placeholder="Số điện thoại"><br><br>
    <button>Gửi</button>
</form>
<?php
if(isset($_POST["ten"])){
    echo "<p>Cảm ơn ".$_POST["ten"].", chúng tôi sẽ liên hệ sớm!</p>";
}
?>