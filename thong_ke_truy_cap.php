<?php
session_start();
include 'ket_noi.php';

// Lấy số liệu truy cập hôm nay
$today = date('Y-m-d');
$sql_today = "SELECT so_luong FROM thong_ke_truy_cap WHERE ngay = '$today'";
$res_today = $conn->query($sql_today);
$count_today = ($res_today && $res_today->num_rows > 0) ? $res_today->fetch_assoc()['so_luong'] : 0;

// Lấy tổng số liệu truy cập
$sql_total = "SELECT SUM(so_luong) as tong FROM thong_ke_truy_cap";
$res_total = $conn->query($sql_total);
$count_total = ($res_total && $res_total->num_rows > 0) ? $res_total->fetch_assoc()['tong'] : 0;
if ($count_total == null) $count_total = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống Kê Truy Cập - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --success: #2ecc71; --warning: #f1c40f; --dark: #2b2d42; --light: #f8f9fa; }
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

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .stat-box { background: #fff; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-bottom: 4px solid var(--primary); }
        .stat-box.today { border-color: var(--success); }
        .stat-title { font-size: 16px; color: #6c757d; text-transform: uppercase; font-weight: 600; margin-bottom: 10px; }
        .stat-number { font-size: 48px; font-weight: 700; color: var(--dark); }

        /* History Table */
        .card-container { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        h3.section-title { font-size: 18px; margin-bottom: 20px; color: var(--dark); padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #6c757d; font-weight: 600; text-transform: uppercase; font-size: 13px; }
        td { font-weight: 500; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">❖ Bảng Điều Khiển</a></li>
            <li><a href="admin_sanpham.php">✧ Quản Lý Sản Phẩm</a></li>
            <li><a href="thong_ke_truy_cap.php" class="active">📊 Thống Kê Truy Cập</a></li>
            <li><a href="index.php" target="_blank">🌐 Xem Trang Web</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-navbar">
            <div class="page-title">Thống Kê Lượt Truy Cập</div>
            <div class="user-info">
                <span>Quản trị viên</span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff" alt="Admin" style="width: 40px; border-radius: 50%;">
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-box today">
                <div class="stat-title">Truy cập hôm nay</div>
                <div class="stat-number" style="color: var(--success);"><?php echo number_format($count_today); ?></div>
            </div>
            <div class="stat-box total">
                <div class="stat-title">Tổng truy cập</div>
                <div class="stat-number" style="color: var(--primary);"><?php echo number_format($count_total); ?></div>
            </div>
        </div>

        <div class="card-container">
            <h3 class="section-title">Lịch Sử Truy Cập (30 Ngày Gần Nhất)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Số lượt truy cập</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_history = "SELECT ngay, so_luong FROM thong_ke_truy_cap ORDER BY ngay DESC LIMIT 30";
                    $res_history = $conn->query($sql_history);
                    if ($res_history && $res_history->num_rows > 0) {
                        while($row = $res_history->fetch_assoc()) {
                            $formatted_date = date('d/m/Y', strtotime($row['ngay']));
                            echo "<tr>";
                            echo "<td>{$formatted_date}</td>";
                            echo "<td><strong>" . number_format($row['so_luong']) . "</strong></td>";
                            echo "<td><span style='color: var(--success); font-weight: 600;'>● Ổn định</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Chưa có dữ liệu.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
