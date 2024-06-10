<?php
require 'vendor/autoload.php'; // Đảm bảo bạn đã cài đặt PhpSpreadsheet qua Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function exportExcel($data) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Thiết lập tiêu đề cột
    $sheet->setCellValue('A1', 'Tháng');
    $sheet->setCellValue('B1', 'Số lượng vé bán ra');
    $sheet->setCellValue('C1', 'Tổng doanh thu');

    // Thêm dữ liệu vào các hàng
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item['month']);
        $sheet->setCellValue('B' . $row, $item['tickets_sold']);
        $sheet->setCellValue('C' . $row, $item['revenue']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'bao_cao_doanh_thu.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');
    exit;
}

// Kết nối cơ sở dữ liệu và truy vấn dữ liệu
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "doanxuan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT MONTH(NgayThanhToan) AS month, COUNT(*) AS tickets_sold, SUM(SoTien) AS revenue
        FROM thanhtoan
        GROUP BY MONTH(NgayThanhToan)";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$conn->close();

// Xuất tệp Excel khi người dùng nhấn nút
if (isset($_POST['export_excel'])) {
    exportExcel($data);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Báo cáo doanh thu</title>
</head>
<body>
    <form method="post">
        <button type="submit" name="export_excel">Xuất Excel</button>
    </form>
</body>
</html>
