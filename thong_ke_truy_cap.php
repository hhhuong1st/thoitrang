<?php
session_start();
include 'ket_noi.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê người truy cập - Fashion</title>
    <style>
        .tracker-container { max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .tracker-title { text-align: center; color: #333; margin-bottom: 30px; }
        .stats-grid { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-box { flex: 1; padding: 20px; border-radius: 8px; text-align: center; color: white; font-weight: bold; }
        .stat-box.today { background-color: #4CAF50; }
        .stat-box.total { background-color: #2196F3; }
        .stat-number { font-size: 36px; margin-top: 10px; }
        .history-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .history-table th, .history-table td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        .history-table th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="tracker-container">
        <h2 class="tracker-title">Thống kê lượng người truy cập</h2>

        <?php
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

        <div class="stats-grid">
            <div class="stat-box today">
                <div>Truy cập hôm nay</div>
                <div class="stat-number"><?php echo number_format($count_today); ?></div>
            </div>
            <div class="stat-box total">
                <div>Tổng lượt truy cập</div>
                <div class="stat-number"><?php echo number_format($count_total); ?></div>
            </div>
        </div>

        <h3 style="margin-top: 40px; color: #555;">Lịch sử truy cập 30 ngày gần nhất</h3>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Số lượt truy cập</th>
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
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Chưa có dữ liệu.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
