<?php
require '../config.php';
require '../vendor/autoload.php'; // PhpSpreadsheet autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Nhận dữ liệu từ form
$reportType = $_POST['reportType'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

if ($reportType == 'monthly') {
    // Lấy dữ liệu báo cáo từ cơ sở dữ liệu
    $startMonth = date('Y-m', strtotime($startDate));
    $endMonth = date('Y-m', strtotime($endDate));

    $sql = "SELECT DATE_FORMAT(NgayThanhToan, '%Y-%m') AS month, SUM(SoTien) AS totalRevenue, COUNT(*) AS totalTickets 
            FROM thanhtoan 
            WHERE TrangThaiThanhToan = 'Đã thanh toán' AND DATE_FORMAT(NgayThanhToan, '%Y-%m') BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(NgayThanhToan, '%Y-%m')";

            
// $sql = "SELECT DATE_FORMAT(NgayThanhToan, '%Y-%m') AS month, COUNT(VeXe.VeXeID) AS TongSoVe, SUM(ThanhToan.SoTien) AS TongDoanhThu
// FROM VeXe JOIN ThanhToan ON VeXe.VeXeID = ThanhToan.VeXeID WHERE VeXe.ThoiGianDatVe >= '2024-05-01'
// AND VeXe.ThoiGianDatVe <= '2024-08-03' GROUP BY ThoiGian ORDER BY ThoiGian;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $startMonth, $endMonth);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tạo một đối tượng Spreadsheet
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template.xlsx');
    $sheet = $spreadsheet->getActiveSheet();

    // Ghi dữ liệu vào file Excel
    $row = 12; // Bắt đầu từ hàng 12
    while ($data = $result->fetch_assoc()) {
        $monthYear = date('m/Y', strtotime($data['month']));
        $totalTickets = $data['totalTickets'];
        $totalRevenue = $data['totalRevenue'];

        // Merge & Center
        $sheet->mergeCells("A$row:B$row");
        $sheet->mergeCells("D$row:F$row");

        // Ghi dữ liệu
        $sheet->setCellValue("A$row", "Tháng " . date('m', strtotime($data['month'])) . " năm " . date('Y', strtotime($data['month'])));
        $sheet->setCellValue("C$row", $totalTickets);
        $sheet->setCellValue("D$row", $totalRevenue);

        // Tăng hàng để ghi dữ liệu cho tháng tiếp theo
        $row++;
    }

    // Lưu file Excel và gửi cho người dùng tải về
    $writer = new Xlsx($spreadsheet);
    $filename = 'BaoCao_DoanhThu_VeBan_' . date('Ymd_His') . '.xlsx';
    $writer->save($filename);

    // Gửi file cho người dùng tải về
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    readfile($filename);

    // Xóa file tạm sau khi gửi
    unlink($filename);
    exit();
}
?>
