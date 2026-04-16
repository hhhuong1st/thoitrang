<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}
include 'ket_noi.php';

// 1. XỬ LÝ XÓA SẢN PHẨM
if (isset($_GET['xoa_id'])) {
    $id_xoa = $_GET['xoa_id'];
    $sql_xoa = "DELETE FROM san_pham WHERE ma_sp = $id_xoa";
    if (mysqli_query($conn, $sql_xoa)) {
        header("Location: them_xoa_sua.php");
        exit();
    }
}

// 2. XỬ LÝ THÊM SẢN PHẨM MỚI
if (isset($_POST['them_sp'])) {
    $ten = mysqli_real_escape_string($conn, $_POST['ten_sp']);
    $gia = $_POST['gia_ban'];
    $size = $_POST['size'];
    $sl = $_POST['so_luong'];
    $dm = $_POST['ma_dm'];
    
    // Xử lý upload ảnh
    $hinh_anh = $_FILES['hinh_anh']['name'];
    $target = "images/" . basename($hinh_anh);
    move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target);

    $sql_them = "INSERT INTO san_pham (ten_sp, gia_ban, size, so_luong, ma_dm, hinh_anh) 
                 VALUES ('$ten', $gia, '$size', $sl, $dm, '$hinh_anh')";
    mysqli_query($conn, $sql_them);
    header("Location: them_xoa_sua.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm - Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #eee; }
        .form-add { background: #eee; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input, select { padding: 8px; margin: 5px; }
        .btn-xoa { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>QUẢN LÝ SẢN PHẨM</h2>
    <a href="index.php">← Quay lại trang chủ</a>

    <div class="form-add">
        <h3>Thêm sản phẩm mới</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="ten_sp" placeholder="Tên sản phẩm" required>
            <input type="number" name="gia_ban" placeholder="Giá bán" required>
            <input type="text" name="size" placeholder="Size (S, M, L...)" required style="width: 80px;">
            <input type="number" name="so_luong" placeholder="Số lượng kho" required style="width: 80px;">
            <select name="ma_dm">
                <option value="1">Thời trang Nam</option>
                <option value="2">Thời trang Nữ</option>
            </select>
            <br>
            Ảnh sản phẩm: <input type="file" name="hinh_anh" required>
            <button type="submit" name="them_sp" style="background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer;">THÊM MỚI</button>
        </form>
    </div>

    <h3>Danh sách sản phẩm</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Kho</th>
            <th>Thao tác</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM san_pham ORDER BY ma_sp DESC");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['ma_sp']}</td>
                    <td><img src='images/{$row['hinh_anh']}' width='50'></td>
                    <td>{$row['ten_sp']}</td>
                    <td>".number_format($row['gia_ban'], 0, ',', '.')."đ</td>
                    <td>{$row['so_luong']}</td>
                    <td>
                        <a href='them_xoa_sua.php?xoa_id={$row['ma_sp']}' class='btn-xoa' onclick='return confirm(\"Chắc chắn xóa?\")'>Xóa</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>