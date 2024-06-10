<?php
require '../config.php';
require '../vendor/autoload.php'; // Đảm bảo đường dẫn đến autoload.php của Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Thiết lập tiêu đề các cột
$sheet->setCellValue('A1', 'Thời gian');
$sheet->setCellValue('B1', 'Số lượng vé bán ra');
$sheet->setCellValue('C1', 'Tổng doanh thu');

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT DATE_FORMAT(NgayThanhToan, '%M %Y') AS ThoiGian, COUNT(*) AS SoLuongVe, SUM(SoTien) AS TongDoanhThu FROM thanhtoan WHERE TrangThaiThanhToan = 'Hoàn thành' GROUP BY DATE_FORMAT(NgayThanhToan, '%Y-%m')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rowNumber = 2; // Bắt đầu từ dòng thứ 2 vì dòng 1 là tiêu đề
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, 'Tháng ' . date('n', strtotime($row['ThoiGian'])) . ' ' . date('Y', strtotime($row['ThoiGian'])));
        $sheet->setCellValue('B' . $rowNumber, $row['SoLuongVe']);
        $sheet->setCellValue('C' . $rowNumber, number_format($row['TongDoanhThu'], 2) . ' ₫');
        $rowNumber++;
    }
}

// Tạo file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'bao_cao_doanh_thu.xlsx';

// Trả file về trình duyệt để tải xuống
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($filename) .'"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit();
?>
