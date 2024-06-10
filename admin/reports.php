<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Báo Cáo và Thống Kê</title>
    <link rel="stylesheet" href="css_admin/admin_styles.css">
    <script src="js_admin/admin_scripts.js"></script>
</head>
<body>
    <?php
    session_start();
    require '../config.php';
    require 'includes_admin/admin_header.php';
    require 'includes_admin/admin_sidebar.php';

    // Kiểm tra xem người dùng có phải là admin không
    if ($_SESSION['quyenhanid'] != 1) {
        header("Location: ../index.php");
        exit();
    }

    // Lấy ngày hiện tại
    $currentDate = date('Y-m-d');

    // Xử lý thêm báo cáo
    if (isset($_POST['add_report'])) {
        $loaiBaoCao = $_POST['loaiBaoCao'];
        $noiDungBaoCao = $_POST['noiDungBaoCao'];
        $ngayTaoBaoCao = $_POST['ngayTaoBaoCao'];
        $nhanVienID = $_POST['nhanVienID'];
        $sql = "INSERT INTO BaoCaoThongKe (LoaiBaoCao, NoiDungBaoCao, NgayTaoBaoCao, NhanVienID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $loaiBaoCao, $noiDungBaoCao, $ngayTaoBaoCao, $nhanVienID);
        $stmt->execute();
    }

    // Xử lý cập nhật báo cáo
    if (isset($_POST['update_report'])) {
        $baoCaoID = $_POST['baoCaoID'];
        $loaiBaoCao = $_POST['loaiBaoCao'];
        $noiDungBaoCao = $_POST['noiDungBaoCao'];
        $ngayTaoBaoCao = $_POST['ngayTaoBaoCao'];
        $nhanVienID = $_POST['nhanVienID'];
        $sql = "UPDATE BaoCaoThongKe SET LoaiBaoCao = ?, NoiDungBaoCao = ?, NgayTaoBaoCao = ?, NhanVienID = ? WHERE BaoCaoID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssii', $loaiBaoCao, $noiDungBaoCao, $ngayTaoBaoCao, $nhanVienID, $baoCaoID);
        $stmt->execute();
    }

    // Xử lý xóa báo cáo
    if (isset($_GET['delete'])) {
        $baoCaoID = $_GET['delete'];
        $sql = "DELETE FROM BaoCaoThongKe WHERE BaoCaoID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $baoCaoID);
        $stmt->execute();
    }

    // Xử lý tìm kiếm báo cáo
    $searchQuery = "";
    if (isset($_POST['search'])) {
        $searchQuery = $_POST['searchQuery'];
        $sql = "SELECT * FROM BaoCaoThongKe WHERE LoaiBaoCao LIKE ? OR NoiDungBaoCao LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%" . $searchQuery . "%";
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
    } else {
        $sql = "SELECT * FROM BaoCaoThongKe";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách nhân viên
    $sqlNhanVien = "SELECT * FROM NhanVien";
    $resultNhanVien = $conn->query($sqlNhanVien);
    $nhanVienOptions = [];
    while ($row = $resultNhanVien->fetch_assoc()) {
        $nhanVienOptions[] = $row;
    }

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

        .selection-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .selection-container label,
        .selection-container select,
        .selection-container button {
            margin: 5px;
        }

        .selection-container label {
            font-weight: bold;
        }

        .selection-container select {
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .selection-container select:focus {
            border-color: #007bff;
        }

        .selection-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 16px;
            margin: 5px 10px;
        }

        .selection-container button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .selection-container h2 {
            margin: 10px 0;
            color: #333;
            font-size: 18px;
        }

        /* Styles for Export Form */
        #exportFormContainer {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
        }

        #exportFormContainer h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #0071c1;
        }

        #exportFormContainer .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        #exportFormContainer label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        #exportFormContainer select, #exportFormContainer input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #exportFormContainer button {
            background-color: #0071c1;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #exportFormContainer button:hover {
            background-color: #005999;
        }

        /* Styles for close button */
        #exportFormContainer .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #333;
        }

        #exportFormContainer .close-btn:hover {
            color: #007bff;
        }
    </style>
    <div class="main-content">
        <h1>Quản Lý Báo Cáo và Thống Kê</h1>

        <div class="selection-container">
            <label for="monthSelect">Chọn tháng:</label>
            <select id="monthSelect">
                <option value="1">Tháng 1</option>
                <option value="2">Tháng 2</option>
                <option value="3">Tháng 3</option>
                <option value="4">Tháng 4</option>
                <option value="5">Tháng 5</option>
                <option value="6">Tháng 6</option>
                <option value="7">Tháng 7</option>
                <option value="8">Tháng 8</option>
                <option value="9">Tháng 9</option>
                <option value="10">Tháng 10</option>
                <option value="11">Tháng 11</option>
                <option value="12">Tháng 12</option>
            </select>

            <label for="yearSelect">Chọn năm:</label>
            <select id="yearSelect">
                <?php for ($year = 2020; $year <= date('Y'); $year++): ?>
                    <option value="<?= $year ?>"><?= $year ?></option>
                <?php endfor; ?>
            </select>

            <h2>Thống kê theo</h2>

            <button id="viewDailyBtn">Ngày</button>
            <button id="viewMonthlyBtn">Tháng</button>

            <button id="exportExcelBtn">Xuất Excel</button>
        </div>

        <div class="charts" style="margin-top: 20px;">
            <h2>Biểu đồ doanh thu</h2>
            <canvas id="revenueChart"></canvas>

            <h2>Biểu đồ số lượng vé bán ra theo thời gian</h2>
            <canvas id="ticketsChart"></canvas>
        </div>

        <!-- Form Xuất Báo Cáo (Ẩn) -->
        <div id="exportFormContainer" class="container">
            <button class="close-btn" onclick="document.getElementById('exportFormContainer').style.display='none'">&times;</button>
            <h1>Xuất Báo Cáo Doanh Thu và Số Lượng Vé Bán</h1>
            <form id="reportForm" action="export_report.php" method="post">
                <div class="form-group">
                    <label for="reportType">Chọn loại báo cáo:</label>
                    <select id="reportType" name="reportType" required>
                        <option value="daily">Hàng ngày</option>
                        <option value="monthly">Hàng tháng</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="startDate">Chọn ngày bắt đầu:</label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>
                <div class="form-group">
                    <label for="endDate">Chọn ngày kết thúc:</label>
                    <input type="date" id="endDate" name="endDate" required>
                </div>
                <button type="submit">Xuất Báo Cáo</button>
            </form>
        </div>

    </div>

    <script>
        document.getElementById('exportExcelBtn').addEventListener('click', () => {
    document.getElementById('exportFormContainer').style.display = 'block';
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Đặt tháng và năm hiện tại khi trang được tải
    document.addEventListener('DOMContentLoaded', () => {
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1; // Tháng bắt đầu từ 0 nên cần +1
        const currentYear = currentDate.getFullYear();

        document.getElementById('monthSelect').value = currentMonth;
        document.getElementById('yearSelect').value = currentYear;

        // Cập nhật biểu đồ cho tháng và năm hiện tại
        updateCharts('daily', currentMonth, currentYear);
    });

    document.getElementById('viewDailyBtn').addEventListener('click', () => {
        const month = document.getElementById('monthSelect').value;
        const year = document.getElementById('yearSelect').value;
        updateCharts('daily', month, year);
    });

    document.getElementById('viewMonthlyBtn').addEventListener('click', () => {
        const year = document.getElementById('yearSelect').value;
        updateCharts('monthly', null, year);
    });

    function updateCharts(viewType, month, year) {
        fetch(`report_data.php?viewType=${viewType}&month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                const revenueChartCtx = document.getElementById('revenueChart').getContext('2d');
                const ticketsChartCtx = document.getElementById('ticketsChart').getContext('2d');

                revenueChart.data.labels = data.revenueLabels;
                revenueChart.data.datasets[0].data = data.revenueValues;
                revenueChart.update();

                ticketsChart.data.labels = data.ticketsLabels;
                ticketsChart.data.datasets[0].data = data.ticketsValues;
                ticketsChart.update();
            });
    }

    const revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Doanh thu',
                data: [],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            }]
        }
    });

    const ticketsChart = new Chart(document.getElementById('ticketsChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Số lượng vé bán ra',
                data: [],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: true
            }]
        }
    });

    document.getElementById('exportExcelBtn').addEventListener('click', () => {
        document.getElementById('exportFormContainer').style.display = 'block';
    });
</script>

    <?php
    require 'includes_admin/admin_footer.php';
    ?>
</body>
</html>
