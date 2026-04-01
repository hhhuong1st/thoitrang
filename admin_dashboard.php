<?php
session_start();
include 'ket_noi.php';

// Các truy vấn thống kê nhanh cho Admin
$sql_sp = "SELECT COUNT(*) as sum_sp FROM san_pham";
$res_sp = $conn->query($sql_sp);
$tong_sp = $res_sp->fetch_assoc()['sum_sp'] ?? 0;

$sql_dh = "SELECT COUNT(*) as sum_dh FROM don_hang";
$res_dh = $conn->query($sql_dh);
$tong_dh = $res_dh->fetch_assoc()['sum_dh'] ?? 0;

$sql_tk = "SELECT SUM(so_luong) as sum_tk FROM thong_ke_truy_cap";
$res_tk = $conn->query($sql_tk);
$tong_tk = $res_tk->fetch_assoc()['sum_tk'] ?? 0;
if ($tong_tk == null) $tong_tk = 0;

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Quản trị website</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --secondary: #3f37c9; --success: #4cc9f0; --warning: #f72585; --dark: #2b2d42; --light: #f8f9fa; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Public Sans', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: #f4f7fe; color: #2b2d42; }
        
        /* Sidebar */
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
        
        /* Dashboard Cards */
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; transition: 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
        .card-info h3 { font-size: 14px; color: #8d99ae; text-transform: uppercase; margin-bottom: 10px; }
        .card-info .number { font-size: 32px; font-weight: 700; color: var(--dark); }
        .card-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; }
        .icon-blue { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
        .icon-green { background: rgba(76, 201, 240, 0.1); color: var(--success); }
        .icon-pink { background: rgba(247, 37, 133, 0.1); color: var(--warning); }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php" class="active">❖ Bảng Điều Khiển</a></li>
            <li><a href="admin_sanpham.php">✧ Quản Lý Sản Phẩm</a></li>
            <li><a href="thong_ke_truy_cap.php">📊 Thống Kê Truy Cập</a></li>
            <li><a href="index.php" target="_blank">🌐 Xem Trang Web</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-navbar">
            <div class="page-title">Bảng Điều Khiển Hạ Tầng</div>
            <div class="user-info">
                <span>Quản trị viên</span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff" alt="Admin" style="width: 40px; border-radius: 50%;">
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <div class="card-info">
                    <h3>Tổng Sản Phẩm</h3>
                    <div class="number"><?php echo number_format($tong_sp); ?></div>
                </div>
                <div class="card-icon icon-blue">📦</div>
            </div>
            
            <div class="card">
                <div class="card-info">
                    <h3>Tổng Đơn Hàng</h3>
                    <div class="number"><?php echo number_format($tong_dh); ?></div>
                </div>
                <div class="card-icon icon-green">📋</div>
            </div>
            
            <div class="card">
                <div class="card-info">
                    <h3>Tổng Lượt Truy Cập</h3>
                    <div class="number"><?php echo number_format($tong_tk); ?></div>
                </div>
                <div class="card-icon icon-pink">👁</div>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <h2 style="margin-bottom: 15px;">Chào mừng đến với hệ thống quản trị</h2>
            <p style="color: #6c757d; line-height: 1.6;">Tại đây bạn có thể quản lý các sản phẩm của hệ thống web, theo dõi lượng truy cập và xử lý các số liệu thống kê.</p>
            <br>
            <a href="admin_sanpham.php" style="display: inline-block; background: var(--primary); color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: 0.3s;">Quản Lý Ngay →</a>
        </div>
    </main>

</body>
</html>
