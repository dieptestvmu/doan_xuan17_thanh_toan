<?php
require '../config.php';

$viewType = $_GET['viewType'];
$month = $_GET['month'];
$year = $_GET['year'];

$revenueData = [];
$ticketsData = [];

if ($viewType === 'daily') {
    // Lấy dữ liệu theo ngày
    $revenueChartQuery = "SELECT DATE(NgayThanhToan) AS date, SUM(SoTien) AS total FROM thanhtoan WHERE TrangThaiThanhToan = 'Hoàn thành' AND MONTH(NgayThanhToan) = ? AND YEAR(NgayThanhToan) = ? GROUP BY DATE(NgayThanhToan)";
    $ticketsChartQuery = "SELECT DATE(ThoiGianDatVe) AS date, COUNT(*) AS total FROM vexe WHERE MONTH(ThoiGianDatVe) = ? AND YEAR(ThoiGianDatVe) = ? GROUP BY DATE(ThoiGianDatVe)";

    $stmt = $conn->prepare($revenueChartQuery);
    $stmt->bind_param('ii', $month, $year);
    $stmt->execute();
    $revenueChart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt = $conn->prepare($ticketsChartQuery);
    $stmt->bind_param('ii', $month, $year);
    $stmt->execute();
    $ticketsChart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Tạo mảng các ngày trong tháng
    $daysInMonth = [];
    $start = new DateTime("$year-$month-01");
    $end = new DateTime("$year-$month-" . $start->format('t'));
    for ($date = $start; $date <= $end; $date->modify('+1 day')) {
        $daysInMonth[] = $date->format('Y-m-d');
    }

    // Chuẩn bị dữ liệu cho biểu đồ doanh thu
    foreach ($daysInMonth as $day) {
        $revenueData[$day] = 0;
    }
    foreach ($revenueChart as $data) {
        $revenueData[$data['date']] = $data['total'];
    }

    // Chuẩn bị dữ liệu cho biểu đồ vé bán ra
    foreach ($daysInMonth as $day) {
        $ticketsData[$day] = 0;
    }
    foreach ($ticketsChart as $data) {
        $ticketsData[$data['date']] = $data['total'];
    }
} else {
    // Lấy dữ liệu theo tháng
    $revenueChartQuery = "SELECT MONTH(NgayThanhToan) AS month, SUM(SoTien) AS total FROM thanhtoan WHERE TrangThaiThanhToan = 'Hoàn thành' AND YEAR(NgayThanhToan) = ? GROUP BY MONTH(NgayThanhToan)";
    $ticketsChartQuery = "SELECT MONTH(ThoiGianDatVe) AS month, COUNT(*) AS total FROM vexe WHERE YEAR(ThoiGianDatVe) = ? GROUP BY MONTH(ThoiGianDatVe)";

    $stmt = $conn->prepare($revenueChartQuery);
    $stmt->bind_param('i', $year);
    $stmt->execute();
    $revenueChart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt = $conn->prepare($ticketsChartQuery);
    $stmt->bind_param('i', $year);
    $stmt->execute();
    $ticketsChart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Tạo mảng các tháng trong năm
    for ($i = 1; $i <= 12; $i++) {
        $revenueData[$i] = 0;
        $ticketsData[$i] = 0;
    }

    // Chuẩn bị dữ liệu cho biểu đồ doanh thu
    foreach ($revenueChart as $data) {
        $revenueData[$data['month']] = $data['total'];
    }

    // Chuẩn bị dữ liệu cho biểu đồ vé bán ra
    foreach ($ticketsChart as $data) {
        $ticketsData[$data['month']] = $data['total'];
    }
}

$response = [
    'revenueLabels' => array_keys($revenueData),
    'revenueValues' => array_values($revenueData),
    'ticketsLabels' => array_keys($ticketsData),
    'ticketsValues' => array_values($ticketsData),
];

header('Content-Type: application/json');
echo json_encode($response);
?>
