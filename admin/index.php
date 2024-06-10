<link rel="stylesheet" href="css_admin/admin_styles.css">
<script src="js_admin/admin_scripts.js"></script>

<?php
session_start();
require '../config.php';
require 'includes_admin/admin_header.php';
?>

<div class="container">
    <?php
    require 'includes_admin/admin_sidebar.php';

    // Kiểm tra xem người dùng có phải là admin không
    if ($_SESSION['quyenhanid'] != 1) {
        header("Location: ../index.php");
        exit();
    }

    // Lấy ngày hiện tại
    $currentDate = date('Y-m-d');

    // Thống kê tổng quan trong tháng hiện tại
    $totalTicketsQuery = "SELECT COUNT(*) AS total FROM vexe WHERE MONTH(ThoiGianDatVe) = MONTH(CURDATE()) AND YEAR(ThoiGianDatVe) = YEAR(CURDATE())";
    $totalRevenueQuery = "SELECT SUM(SoTien) AS total FROM thanhtoan WHERE TrangThaiThanhToan = 'Hoàn thành' AND MONTH(NgayThanhToan) = MONTH(CURDATE()) AND YEAR(NgayThanhToan) = YEAR(CURDATE())";
    $totalBusesQuery = "SELECT COUNT(*) AS total FROM xe";
    $totalIncidentsQuery = "SELECT COUNT(*) AS total FROM suco WHERE MONTH(NgayXayRaSuCo) = MONTH(CURDATE()) AND YEAR(NgayXayRaSuCo) = YEAR(CURDATE())";
    $totalCanceledTicketsQuery = "SELECT COUNT(*) AS total FROM loghuyve WHERE MONTH(NgayHuy) = MONTH(CURDATE()) AND YEAR(NgayHuy) = YEAR(CURDATE())";
    $totalPromotionsQuery = "SELECT COUNT(*) AS total FROM khuyenmai WHERE NgayKetThuc >= CURDATE()";
    $totalRoutesQuery = "SELECT COUNT(*) AS total FROM tuyenxe";

    $totalTickets = $conn->query($totalTicketsQuery)->fetch_assoc()['total'];
    $totalRevenue = $conn->query($totalRevenueQuery)->fetch_assoc()['total'];
    $totalBuses = $conn->query($totalBusesQuery)->fetch_assoc()['total'];
    $totalIncidents = $conn->query($totalIncidentsQuery)->fetch_assoc()['total'];
    $totalCanceledTickets = $conn->query($totalCanceledTicketsQuery)->fetch_assoc()['total'];
    $totalPromotions = $conn->query($totalPromotionsQuery)->fetch_assoc()['total'];
    $totalRoutes = $conn->query($totalRoutesQuery)->fetch_assoc()['total'];

    // Thống kê biểu đồ
    $revenueChartQuery = "SELECT DATE(NgayThanhToan) AS date, SUM(SoTien) AS total FROM thanhtoan WHERE TrangThaiThanhToan = 'Hoàn thành' AND MONTH(NgayThanhToan) = MONTH(CURDATE()) AND YEAR(NgayThanhToan) = YEAR(CURDATE()) GROUP BY DATE(NgayThanhToan)";
    $ticketsChartQuery = "SELECT DATE(ThoiGianDatVe) AS date, COUNT(*) AS total FROM vexe WHERE MONTH(ThoiGianDatVe) = MONTH(CURDATE()) AND YEAR(ThoiGianDatVe) = YEAR(CURDATE()) GROUP BY DATE(ThoiGianDatVe)";

    $revenueChart = $conn->query($revenueChartQuery)->fetch_all(MYSQLI_ASSOC);
    $ticketsChart = $conn->query($ticketsChartQuery)->fetch_all(MYSQLI_ASSOC);

    // Tạo mảng các ngày trong tháng hiện tại
    $daysInMonth = [];
    $start = new DateTime('first day of this month');
    $end = new DateTime('last day of this month');
    for ($date = $start; $date <= $end; $date->modify('+1 day')) {
        $daysInMonth[] = $date->format('Y-m-d');
    }

    // Chuẩn bị dữ liệu cho biểu đồ doanh thu
    $revenueData = [];
    foreach ($daysInMonth as $day) {
        $revenueData[$day] = 0;
    }
    foreach ($revenueChart as $data) {
        $revenueData[$data['date']] = $data['total'];
    }

    // Chuẩn bị dữ liệu cho biểu đồ vé bán ra
    $ticketsData = [];
    foreach ($daysInMonth as $day) {
        $ticketsData[$day] = 0;
    }
    foreach ($ticketsChart as $data) {
        $ticketsData[$data['date']] = $data['total'];
    }
    ?>
<style>
/* General Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 1400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.custom-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
    display: inline-block;
    margin: 5px 0;
    transition: background-color 0.3s, transform 0.3s;
    font-size: 14px;
}

.custom-button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.custom-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: box-shadow 0.3s;
}

.custom-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: #007bff;
}

.form-inline {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.form-inline input[type="text"] {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-inline input[type="text"]:focus {
    border-color: #007bff;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.custom-table th, .custom-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
    transition: background-color 0.3s;
}

.custom-table th {
    background-color: #f4f4f4;
    color: #333;
}

.custom-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.custom-table tr:hover {
    background-color: #f1f1f1;
}

.custom-table th {
    padding-top: 12px;
    padding-bottom: 12px;
    background-color: #007bff;
    color: white;
}

.small-button {
    padding: 5px 10px;
    font-size: 12px;
    margin: 2px;
}

/* Dashboard Styles */
.dashboard-stats {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 10px;
    margin-bottom: 20px;
}

.stat-item {
    flex: 1;
    min-width: 150px;
    background-color: #007bff;
    color: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s, transform 0.3s;
    /* padding-top: 80px; */
    /* margin-top: */
}

.stat-item:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.stat-item h2 {
    font-size: 19px;
    margin: 10px 0;
}

.stat-item p {
    font-size: 31px;
    font-weight: bold;
    margin: 20px 0;
}

.charts {
    margin-top: 20px;
}

.charts h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.charts canvas {
    max-width: 100%;
    margin: 0 auto;
    display: block;
}
</style>


    <div class="main-content">
        <h1>Bảng Điều Khiển</h1>
        <div class="dashboard-stats">
            <div class="stat-item">
                <p><?= $totalTickets ?></p>
                <h2>Tổng số vé bán ra</h2>
            </div>
            <div class="stat-item">
                <p><?= number_format($totalRevenue, 0, ',', '.') ?> VND</p>
                <h2>Tổng doanh thu</h2>
            </div>
            <div class="stat-item">
                <p><?= $totalBuses ?></p>
                <h2>Số lượng xe</h2>
            </div>
            <div class="stat-item">
                <p><?= $totalIncidents ?></p>
                <h2>Số lượng sự cố</h2>
            </div>
            <div class="stat-item">
                <p><?= $totalCanceledTickets ?></p>
                <h2>Số lượng vé bị hủy</h2>
            </div>
            <div class="stat-item">
                <p><?= $totalPromotions ?></p>
                <h2>Tổng số khuyến mãi đang có</h2>
            </div>
            <div class="stat-item">
                <p><?= $totalRoutes ?></p>
                <h2>Tổng số tuyến xe</h2>
            </div>
        </div>

        <div class="charts">
            <h2>Biểu đồ doanh thu</h2>
            <canvas id="revenueChart" width="1125" height="562" style="display: block; box-sizing: border-box; height: 450px; width: 900px;"></canvas>
            <h2>Biểu đồ số lượng vé bán ra theo thời gian</h2>
            <canvas id="ticketsChart" width="1125" height="562" style="display: block; box-sizing: border-box; height: 450px; width: 900px;"></canvas>
<!--             
            ></canvas> -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueLabels = <?= json_encode(array_keys($revenueData)) ?>;
    const revenueValues = <?= json_encode(array_values($revenueData)) ?>;
    const ticketsLabels = <?= json_encode(array_keys($ticketsData)) ?>;
    const ticketsValues = <?= json_encode(array_values($ticketsData)) ?>;

    const revenueChartCtx = document.getElementById('revenueChart').getContext('2d');
    const ticketsChartCtx = document.getElementById('ticketsChart').getContext('2d');

    new Chart(revenueChartCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu',
                data: revenueValues,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            }]
        }
    });

    new Chart(ticketsChartCtx, {
        type: 'line',
        data: {
            labels: ticketsLabels,
            datasets: [{
                label: 'Số lượng vé bán ra',
                data: ticketsValues,
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: true
            }]
        }
    });
</script>

<?php
require 'includes_admin/admin_footer.php';
?>
