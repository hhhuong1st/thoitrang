<?php
session_start();
include 'ket_noi.php';

// 1. XỬ LÝ XÓA SẢN PHẨM
if (isset($_GET['xoa_id'])) {
    $id_xoa = intval($_GET['xoa_id']);
    $sql_xoa = "DELETE FROM san_pham WHERE ma_sp = $id_xoa";
    if (mysqli_query($conn, $sql_xoa)) {
        header("Location: admin_sanpham.php?msg=deleted");
        exit();
    }
}

// 2. XỬ LÝ LẤY THÔNG TIN ĐỂ LUÔN FILL VÀO FORM (NẾU ĐANG SỬA)
$is_edit = false;
$edit_data = [];
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $result_edit = mysqli_query($conn, "SELECT * FROM san_pham WHERE ma_sp = $edit_id");
    if ($result_edit && $result_edit->num_rows > 0) {
        $edit_data = mysqli_fetch_assoc($result_edit);
        $is_edit = true;
    }
}

// 3. XỬ LÝ CẬP NHẬT SẢN PHẨM
if (isset($_POST['sua_sp'])) {
    $ma_sp = intval($_POST['ma_sp']);
    $ten = mysqli_real_escape_string($conn, $_POST['ten_sp']);
    $gia = floatval($_POST['gia_ban']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $sl = intval($_POST['so_luong']);
    $dm = intval($_POST['ma_dm']);
    
    // Nếu có upload ảnh mới thì cập nhật, không thì giữ nguyên
    if (!empty($_FILES['hinh_anh']['name'])) {
        $hinh_anh = $_FILES['hinh_anh']['name'];
        $target = "images/" . basename($hinh_anh);
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target);
        
        $sql_sua = "UPDATE san_pham SET ten_sp='$ten', gia_ban=$gia, size='$size', so_luong=$sl, ma_dm=$dm, hinh_anh='$hinh_anh' WHERE ma_sp=$ma_sp";
    } else {
        $sql_sua = "UPDATE san_pham SET ten_sp='$ten', gia_ban=$gia, size='$size', so_luong=$sl, ma_dm=$dm WHERE ma_sp=$ma_sp";
    }
    
    mysqli_query($conn, $sql_sua);
    header("Location: admin_sanpham.php?msg=updated");
    exit();
}

// 4. XỬ LÝ THÊM SẢN PHẨM MỚI
if (isset($_POST['them_sp'])) {
    $ten = mysqli_real_escape_string($conn, $_POST['ten_sp']);
    $gia = floatval($_POST['gia_ban']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $sl = intval($_POST['so_luong']);
    $dm = intval($_POST['ma_dm']);
    
    // Xử lý upload ảnh
    $hinh_anh = $_FILES['hinh_anh']['name'];
    $target = "images/" . basename($hinh_anh);
    if(move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target)) {
        // Success upload
    }
    
    $sql_them = "INSERT INTO san_pham (ten_sp, gia_ban, size, so_luong, ma_dm, hinh_anh) 
                 VALUES ('$ten', $gia, '$size', $sl, $dm, '$hinh_anh')";
    mysqli_query($conn, $sql_them);
    header("Location: admin_sanpham.php?msg=added");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --secondary: #3f37c9; --warning: #f39c12; --danger: #ef233c; --success: #2ecc71; --dark: #2b2d42; --light: #f8f9fa; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Public Sans', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: #f4f7fe; color: #2b2d42; }
        
        /* Sidebar (giống admin_dashboard) */
        .sidebar { width: 260px; background: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 24px; font-weight: 700; color: var(--primary); text-align: center; border-bottom: 1px solid #eee; }
        .nav-links { list-style: none; padding: 20px 0; flex: 1; }
        .nav-links li { margin-bottom: 5px; }
        .nav-links a { display: block; padding: 15px 25px; color: #6c757d; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: rgba(67, 97, 238, 0.1); color: var(--primary); border-right: 4px solid var(--primary); }
        .nav-links a i { margin-right: 10px; width: 20px; text-align: center; }
        
        /* Main Content */
        .main-content { flex: 1; padding: 30px; display: flex; flex-direction: column; }
        .top-navbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #fff; padding: 15px 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .page-title { font-size: 20px; font-weight: 700; }
        .user-info { display: flex; align-items: center; gap: 15px; font-weight: 600; }

        /* Form container */
        .card-container { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        h3.section-title { font-size: 18px; margin-bottom: 20px; color: var(--dark); padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group.full { grid-column: span 2; }
        label { font-weight: 600; font-size: 14px; color: #555; }
        input, select { padding: 12px; border: 1px solid #ddd; border-radius: 6px; outline: none; transition: 0.3s; font-size: 14px; }
        input:focus, select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1); }
        .btn-submit { background: var(--primary); color: white; padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 15px; grid-column: span 2; transition: 0.3s; }
        .btn-submit:hover { background: var(--secondary); transform: translateY(-2px); }

        /* Table container */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #6c757d; font-weight: 600; text-transform: uppercase; font-size: 13px; }
        td img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; }
        .btn-delete { color: var(--danger); text-decoration: none; font-weight: 600; font-size: 14px; padding: 5px 10px; background: rgba(239, 35, 60, 0.1); border-radius: 4px; transition: 0.3s; display: inline-block; }
        .btn-delete:hover { background: var(--danger); color: white; }
        
        .btn-edit { color: var(--warning); text-decoration: none; font-weight: 600; font-size: 14px; padding: 5px 10px; background: rgba(243, 156, 18, 0.1); border-radius: 4px; transition: 0.3s; margin-right: 5px; display: inline-block; }
        .btn-edit:hover { background: var(--warning); color: white; }
        
        .alert { padding: 15px 20px; margin-bottom: 20px; border-radius: 8px; font-weight: 600; }
        .alert-success { background: rgba(46, 204, 113, 0.1); color: var(--success); border: 1px solid rgba(46, 204, 113, 0.2); }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">❖ Bảng Điều Khiển</a></li>
            <li><a href="admin_sanpham.php" class="active">✧ Quản Lý Sản Phẩm</a></li>
            <li><a href="thong_ke_truy_cap.php">📊 Thống Kê Truy Cập</a></li>
            <li><a href="index.php" target="_blank">🌐 Xem Trang Web</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-navbar">
            <div class="page-title">Quản Lý Danh Mục Sản Phẩm</div>
            <div class="user-info">
                <span>Quản trị viên</span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff" alt="Admin" style="width: 40px; border-radius: 50%;">
            </div>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
            <div class="alert alert-success">✅ Thêm sản phẩm thành công!</div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">✅ Xóa sản phẩm thành công!</div>
        <?php endif; ?>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">✅ Cập nhật sản phẩm thành công!</div>
        <?php endif; ?>

        <!-- Form Add/Edit Product -->
        <div class="card-container">
            <h3 class="section-title"><?php echo $is_edit ? "Sửa Cập Nhật Sản Phẩm #$edit_id" : "Thêm Sản Phẩm Mới"; ?></h3>
            <form method="post" enctype="multipart/form-data" class="form-grid">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="ma_sp" value="<?php echo $edit_data['ma_sp']; ?>">
                <?php endif; ?>

                <div class="form-group full">
                    <label>Tên sản phẩm *</label>
                    <input type="text" name="ten_sp" placeholder="Nhập tên sản phẩm..." value="<?php echo $is_edit ? htmlspecialchars($edit_data['ten_sp']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Giá bán (VNĐ) *</label>
                    <input type="number" name="gia_ban" placeholder="VD: 250000" value="<?php echo $is_edit ? $edit_data['gia_ban'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Số lượng kho *</label>
                    <input type="number" name="so_luong" placeholder="VD: 50" value="<?php echo $is_edit ? $edit_data['so_luong'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Kích cỡ (Size)</label>
                    <input type="text" name="size" placeholder="S, M, L, XL..." value="<?php echo $is_edit ? htmlspecialchars($edit_data['size']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Danh mục *</label>
                    <select name="ma_dm">
                        <?php
                            $dm_res = mysqli_query($conn, "SELECT * FROM danh_muc");
                            while($dm = mysqli_fetch_assoc($dm_res)) {
                                $selected = ($is_edit && $edit_data['ma_dm'] == $dm['ma_dm']) ? "selected" : "";
                                echo "<option value='{$dm['ma_dm']}' $selected>{$dm['ten_dm']}</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Hình ảnh sản phẩm <?php echo $is_edit ? "(Bỏ trống nếu không muốn đổi ảnh mới)" : "*"; ?></label>
                    <?php if($is_edit && !empty($edit_data['hinh_anh'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="images/<?php echo $edit_data['hinh_anh']; ?>" alt="Current Image" style="max-height: 80px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="hinh_anh" accept="image/*" <?php echo $is_edit ? '' : 'required'; ?>>
                </div>
                
                <div class="form-group full" style="display: flex; gap: 15px;">
                    <button type="submit" name="<?php echo $is_edit ? 'sua_sp' : 'them_sp'; ?>" class="btn-submit" style="flex: 1;"><?php echo $is_edit ? 'CẬP NHẬT SẢN PHẨM' : 'LƯU SẢN PHẨM MỚI'; ?></button>
                    <?php if($is_edit): ?>
                        <a href="admin_sanpham.php" style="padding: 12px 25px; text-decoration: none; border: 1px solid #ccc; border-radius: 6px; color: #555; font-weight: 600; text-align: center; display: inline-block;">HỦY BỎ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Product List -->
        <div class="card-container">
            <h3 class="section-title">Danh Sách Sản Phẩm</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá Bán</th>
                        <th>Kho</th>
                        <th style="text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM san_pham ORDER BY ma_sp DESC");
                    while($row = mysqli_fetch_assoc($result)) {
                        $gia_format = number_format($row['gia_ban'], 0, ',', '.');
                        echo "<tr>
                                <td>#{$row['ma_sp']}</td>
                                <td><img src='images/{$row['hinh_anh']}' alt='Product'></td>
                                <td style='font-weight: 600; color: #2b2d42;'>{$row['ten_sp']}</td>
                                <td style='color: var(--primary); font-weight: bold;'>{$gia_format}đ</td>
                                <td>{$row['so_luong']}</td>
                                <td style='text-align: right;'>
                                    <a href='admin_sanpham.php?edit_id={$row['ma_sp']}' class='btn-edit'>Sửa</a>
                                    <a href='admin_sanpham.php?xoa_id={$row['ma_sp']}' class='btn-delete' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này không?\")'>Thùng rác</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
