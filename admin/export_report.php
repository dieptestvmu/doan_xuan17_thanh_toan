<?php
require '../config.php';
require '../vendor/autoload.php'; // PhpSpreadsheet autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Định dạng ngày theo kiểu "Ngày 07 tháng 06 năm 2024"
function formatDateForReport($date) {
    return strftime('Ngày %d tháng %m năm %Y', strtotime($date));
}

// Nhận dữ liệu từ form
$reportType = $_POST['reportType'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Tạo một đối tượng Spreadsheet
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template.xlsx');
$sheet = $spreadsheet->getActiveSheet();

// Lấy ngày hiện tại
$currentDate = date('Y-m-d');
$formattedCurrentDate = formatDateForReport($currentDate);

// Cập nhật ô D16 với ngày hiện tại và giữ nguyên định dạng
$sheet->setCellValue('D16', $formattedCurrentDate);

function addEmptyCells($sheet, $row) {
    $sheet->insertNewRowBefore($row, 1);

    // Áp dụng style cho hàng mới chèn để tắt border dọc bên trong
    $styleArray = [
        'borders' => [
            'left' => [
                'borderStyle' => Border::BORDER_NONE,
            ],
            'right' => [
                'borderStyle' => Border::BORDER_NONE,
            ],
            'bottom' => [
                'borderStyle' => Border::BORDER_NONE,
            ],
            'vertical' => [
                'borderStyle' => Border::BORDER_NONE,
            ],
        ],
    ];
    $sheet->getStyle("A$row:F$row")->applyFromArray($styleArray);
}

// Hàm để áp dụng style cho một ô
function applyCellStyle($sheet, $cell) {
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
        'font' => [
            'name' => 'Times New Roman',
            'size' => 12,
        ],
    ];
    $sheet->getStyle($cell)->applyFromArray($styleArray);
}

if ($reportType == 'monthly') {
    // Lấy dữ liệu báo cáo từ cơ sở dữ liệu
    $startMonth = date('Y-m', strtotime($startDate));
    $endMonth = date('Y-m', strtotime($endDate));

    $sql = "SELECT DATE_FORMAT(NgayThanhToan, '%Y-%m') AS month, SUM(SoTien) AS totalRevenue, COUNT(*) AS totalTickets 
            FROM thanhtoan 
            WHERE TrangThaiThanhToan = 'Đã thanh toán' AND DATE_FORMAT(NgayThanhToan, '%Y-%m') BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(NgayThanhToan, '%Y-%m')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $startMonth, $endMonth);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ghi dữ liệu vào file Excel
    $row = 12; // Bắt đầu từ hàng 12
    $months = [];

    while ($data = $result->fetch_assoc()) {
        $months[$data['month']] = [
            'totalTickets' => $data['totalTickets'],
            'totalRevenue' => $data['totalRevenue']
        ];
    }

    $period = new DatePeriod(
        new DateTime($startDate),
        new DateInterval('P1M'),
        new DateTime($endDate . ' +1 month')
    );

    foreach ($period as $dt) {
        $month = $dt->format('Y-m');
        $totalTickets = $months[$month]['totalTickets'] ?? 0;
        $totalRevenue = $months[$month]['totalRevenue'] ?? 0;

        // Merge & Center
        $sheet->mergeCells("A$row:B$row");
        $sheet->mergeCells("D$row:F$row");

        // Ghi dữ liệu
        $sheet->setCellValue("A$row", "Tháng " . date('m', strtotime($month)) . " năm " . date('Y', strtotime($month)));
        $sheet->setCellValue("C$row", $totalTickets);
        $sheet->setCellValue("D$row", $totalRevenue);

        // Áp dụng style cho các ô
        applyCellStyle($sheet, "A$row");
        applyCellStyle($sheet, "B$row");
        applyCellStyle($sheet, "C$row");
        applyCellStyle($sheet, "D$row");
        applyCellStyle($sheet, "E$row");
        applyCellStyle($sheet, "F$row");

        // Áp dụng định dạng Accounting cho cột doanh thu
$sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('#,##0 "đ"');


        // Tăng hàng để ghi dữ liệu cho tháng tiếp theo
        $row++;
        addEmptyCells($sheet, $row);
    }

    //////////////////////////////////////////////////////////
// Tính tổng doanh thu
$totalRevenueSum = array_sum(array_column($months, 'totalRevenue'));

// Merge & Center
$sheet->mergeCells("A$row:B$row");
$sheet->mergeCells("D$row:F$row");

// Ghi dữ liệu dòng tổng
$sheet->setCellValue("A$row", "Tổng:");
$sheet->setCellValue("D$row", $totalRevenueSum);

// Áp dụng style cho các ô
applyCellStyle($sheet, "A$row");
applyCellStyle($sheet, "B$row");
applyCellStyle($sheet, "C$row");
applyCellStyle($sheet, "D$row");
applyCellStyle($sheet, "E$row");
applyCellStyle($sheet, "F$row");

// Áp dụng định dạng Accounting cho cột doanh thu
$sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('#,##0 "đ"');

// // Áp dụng định dạng Accounting cho cột doanh thu
// $sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('_ * #,##0_ đ');


    //////////////////////////////////////////////////////////

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
} elseif ($reportType == 'daily') {
    // Lấy dữ liệu báo cáo từ cơ sở dữ liệu
    $sql = "SELECT DATE(NgayThanhToan) AS day, SUM(SoTien) AS totalRevenue, COUNT(*) AS totalTickets 
            FROM thanhtoan 
            WHERE TrangThaiThanhToan = 'Đã thanh toán' AND DATE(NgayThanhToan) BETWEEN ? AND ?
            GROUP BY DATE(NgayThanhToan)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ghi dữ liệu vào file Excel
    $row = 12; // Bắt đầu từ hàng 12
    $days = [];

    while ($data = $result->fetch_assoc()) {
        $days[$data['day']] = [
            'totalTickets' => $data['totalTickets'],
            'totalRevenue' => $data['totalRevenue']
        ];
    }

    $period = new DatePeriod(
        new DateTime($startDate),
        new DateInterval('P1D'),
        new DateTime($endDate . ' +1 day')
    );

    foreach ($period as $dt) {
        $day = $dt->format('Y-m-d');
        $formattedDay = strftime('Ngày %d tháng %m năm %Y', strtotime($day));
        $totalTickets = $days[$day]['totalTickets'] ?? 0;
        $totalRevenue = $days[$day]['totalRevenue'] ?? 0;

        // Merge & Center
        $sheet->mergeCells("A$row:B$row");
        $sheet->mergeCells("D$row:F$row");

        // Ghi dữ liệu
        $sheet->setCellValue("A$row", $formattedDay);
        $sheet->setCellValue("C$row", $totalTickets);
        $sheet->setCellValue("D$row", $totalRevenue);

        // Áp dụng style cho các ô
        applyCellStyle($sheet, "A$row");
        applyCellStyle($sheet, "B$row");
        applyCellStyle($sheet, "C$row");
        applyCellStyle($sheet, "D$row");
        applyCellStyle($sheet, "E$row");
        applyCellStyle($sheet, "F$row");

// Áp dụng định dạng Accounting cho cột doanh thu
$sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('#,##0 "đ"');


        // Tăng hàng để ghi dữ liệu cho ngày tiếp theo
        $row++;
        addEmptyCells($sheet, $row);
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tính tổng doanh thu
$totalRevenueSum = array_sum(array_column($days, 'totalRevenue'));

// Merge & Center
$sheet->mergeCells("A$row:B$row");
$sheet->mergeCells("D$row:F$row");

// Ghi dữ liệu dòng tổng
$sheet->setCellValue("A$row", "Tổng:");
$sheet->setCellValue("D$row", $totalRevenueSum);

// Áp dụng style cho các ô
applyCellStyle($sheet, "A$row");
applyCellStyle($sheet, "B$row");
applyCellStyle($sheet, "C$row");
applyCellStyle($sheet, "D$row");
applyCellStyle($sheet, "E$row");
applyCellStyle($sheet, "F$row");

// Áp dụng định dạng Accounting cho cột doanh thu
$sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('#,##0 "đ"');

// // Áp dụng định dạng Accounting cho cột doanh thu
// $sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('_ * #,##0_ đ');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

// elseif ($reportType == 'yearly') {
//     // Lấy dữ liệu báo cáo từ cơ sở dữ liệu
//     $startYear = date('Y', strtotime($startDate));
//     $endYear = date('Y', strtotime($endDate));

//     $sql = "SELECT DATE_FORMAT(NgayThanhToan, '%Y') AS year, SUM(SoTien) AS totalRevenue, COUNT(*) AS totalTickets 
//             FROM thanhtoan 
//             WHERE TrangThaiThanhToan = 'Đã thanh toán' AND DATE_FORMAT(NgayThanhToan, '%Y') BETWEEN ? AND ?
//             GROUP BY DATE_FORMAT(NgayThanhToan, '%Y')";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('ss', $startYear, $endYear);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     // Ghi dữ liệu vào file Excel
//     $row = 12; // Bắt đầu từ hàng 12
//     $years = [];

//     while ($data = $result->fetch_assoc()) {
//         $years[$data['year']] = [
//             'totalTickets' => $data['totalTickets'],
//             'totalRevenue' => $data['totalRevenue']
//         ];
//     }

//     for ($year = $startYear; $year <= $endYear; $year++) {
//         $totalTickets = $years[$year]['totalTickets'] ?? 0;
//         $totalRevenue = $years[$year]['totalRevenue'] ?? 0;

//         // Merge & Center
//         $sheet->mergeCells("A$row:B$row");
//         $sheet->mergeCells("D$row:F$row");

//         // Ghi dữ liệu
//         $sheet->setCellValue("A$row", "Năm " . $year);
//         $sheet->setCellValue("C$row", $totalTickets);
//         $sheet->setCellValue("D$row", $totalRevenue);

//         // Áp dụng style cho các ô
//         applyCellStyle($sheet, "A$row");
//         applyCellStyle($sheet, "B$row");
//         applyCellStyle($sheet, "C$row");
//         applyCellStyle($sheet, "D$row");
//         applyCellStyle($sheet, "E$row");
//         applyCellStyle($sheet, "F$row");

//         // Áp dụng định dạng Accounting cho cột doanh thu
//         $sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('#,##0 "đ"');

//         // Tăng hàng để ghi dữ liệu cho năm tiếp theo
//         $row++;
//         addEmptyCells($sheet, $row);
//     }

//     // Tính tổng doanh thu
//     $totalRevenueSum = array_sum(array_column($years, 'totalRevenue'));

//     // Merge & Center
//     $sheet->mergeCells("A$row:B$row");
//     $sheet->mergeCells("D$row:F$row");

//     // Ghi dữ liệu dòng tổng
//     $sheet->setCellValue("A$row", "Tổng:");
//     $sheet->setCellValue("D$row", $totalRevenueSum);

//     // Áp dụng style cho các ô
//     applyCellStyle($sheet, "A$row");
//     applyCellStyle($sheet, "B$row");
//     applyCellStyle($sheet, "C$row");
//     applyCellStyle($sheet, "D$row");
//     applyCellStyle($sheet, "E$row");
//     applyCellStyle($sheet, "F$row");

//     // Áp dụng định dạng Accounting cho cột doanh thu
//     $sheet->getStyle("D$row:F$row")->getNumberFormat()->setFormatCode('#,##0 "đ"');

//     // Lưu file Excel và gửi cho người dùng tải về
//     $writer = new Xlsx($spreadsheet);
//     $filename = 'BaoCao_DoanhThu_VeBan_' . date('Ymd_His') . '.xlsx';
//     $writer->save($filename);

//     // Gửi file cho người dùng tải về
//     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//     header('Content-Disposition: attachment; filename="' . $filename . '"');
//     header('Cache-Control: max-age=0');
//     readfile($filename);

//     // Xóa file tạm sau khi gửi
//     unlink($filename);
//     exit();
// }

?>
