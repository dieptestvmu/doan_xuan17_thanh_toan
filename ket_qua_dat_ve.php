<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Lấy các tham số từ URL
$vnp_TxnRef = isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '';
$vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
$vnp_Amount = isset($_GET['vnp_Amount']) ? $_GET['vnp_Amount'] : '';
$vnp_BankCode = isset($_GET['vnp_BankCode']) ? $_GET['vnp_BankCode'] : '';
$vnp_TransactionStatus = isset($_GET['vnp_TransactionStatus']) ? $_GET['vnp_TransactionStatus'] : '';
$vnp_PayDate = isset($_GET['vnp_PayDate']) ? $_GET['vnp_PayDate'] : '';

// Chuyển đổi định dạng thời gian
if (!empty($vnp_PayDate)) {
    $dateTime = DateTime::createFromFormat('YmdHis', $vnp_PayDate);
    $formattedDate = $dateTime->format('d/m/Y - H:i:s');
}

if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
    // Cập nhật trạng thái thanh toán trong bảng thanhtoan
    $sqlUpdateThanhToan = "UPDATE thanhtoan tt
                           JOIN vexe vx ON tt.VeXeID = vx.VeXeID
                           SET tt.TrangThaiThanhToan = 'Đã thanh toán'
                           WHERE vx.CodeVeXe = '$vnp_TxnRef'";
    $conn->query($sqlUpdateThanhToan);
}

// Lấy thông tin vé từ cơ sở dữ liệu dựa trên mã vé
$sql = "SELECT vx.*, kh.HoTen, kh.SoDienThoai, kh.Email, lt.NgayKhoiHanh, lt.GioKhoiHanh, tx.DiemDi, tx.DiemDen, tt.*, lt.GiaVe, cg.SoGhe, xe.LoaiXe
        FROM vexe vx
        JOIN khachhang kh ON vx.KhachHangID = kh.KhachHangID
        JOIN lichtrinhxe lt ON vx.LichTrinhXeID = lt.LichTrinhXeID
        JOIN tuyenxe tx ON lt.TuyenXeID = tx.TuyenXeID
        LEFT JOIN thanhtoan tt ON vx.VeXeID = tt.VeXeID
        JOIN chongoi cg ON vx.ChoNgoiID = cg.ChoNgoiID
        JOIN xe ON lt.XeID = xe.XeID
        WHERE vx.CodeVeXe = '$vnp_TxnRef'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $veDetails = [];
    while ($row = $result->fetch_assoc()) {
        $veDetails[] = $row;
    }
} else {
    echo "<p>Không tìm thấy thông tin vé.</p>";
    exit();
}

$hoTen = $veDetails[0]['HoTen'];
$soDienThoai = $veDetails[0]['SoDienThoai'];
$email = $veDetails[0]['Email'];
$ngayKhoiHanh = $veDetails[0]['NgayKhoiHanh'];
$gioKhoiHanh = $veDetails[0]['GioKhoiHanh'];
$diemDi = $veDetails[0]['DiemDi'];
$diemDen = $veDetails[0]['DiemDen'];
$codeVeXe = $veDetails[0]['CodeVeXe'];
$loaiXe = $veDetails[0]['LoaiXe'];
$phuongThucThanhToan = $vnp_BankCode;
$soTien = $vnp_Amount / 100; // Chuyển đổi từ đơn vị VNĐ
$trangThaiThanhToan = $vnp_ResponseCode == '00' ? 'Đã thanh toán' : '';

$gheDetails = [];
foreach ($veDetails as $ve) {
    $gheDetails[] = $ve['SoGhe'];
}
$gheList = implode(', ', $gheDetails);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin vé đã đặt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        max-width: 1000px;
        width: 100%;
        background-color: #fff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .header {
        background-color: #0071C1;
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    .header h1 {
        margin: 0;
    }

    .content {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
    }

    .left, .right {
        flex: 1;
        min-width: 300px;
        padding: 10px;
    }

    .section {
        margin-bottom: 20px;
    }

    .section h2 {
        color: #0071C1;
        border-bottom: 2px solid #0071C1;
        padding-bottom: 5px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .section p {
        margin: 5px 0;
        display: flex;
        align-items: center;
    }

    .section p i {
        margin-right: 10px;
        color: #0071C1;
    }

    .button {
        display: block;
        width: 200px;
        margin: 20px auto;
        padding: 10px;
        text-align: center;
        background-color: #0071C1;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #005a9c;
    }

    @media (max-width: 600px) {
        .container {
            margin: 20px;
        }
    }

</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Thông tin vé đã đặt</h1>
    </div>
    
    <div class="content">
        <div class="left">
            <div class="section">
                <h2>Thông tin khách hàng</h2>
                <p><i class="fas fa-user"></i> <strong>Họ và tên: </strong> <?= htmlspecialchars($hoTen) ?></p>
                <p><i class="fas fa-phone"></i> <strong>Số điện thoại: </strong> <?= htmlspecialchars($soDienThoai) ?></p>
                <p><i class="fas fa-envelope"></i> <strong>Email: </strong> <?= htmlspecialchars($email) ?></p>
            </div>
            
            <div class="section">
                <h2>Thông tin thanh toán</h2>
                <p><i class="fas fa-dollar-sign"></i> <strong>Số tiền: </strong> <?= number_format($soTien) ?> VND</p>
                <p><i class="fas fa-credit-card"></i> <strong>Phương thức thanh toán: </strong> <?= htmlspecialchars($phuongThucThanhToan) ?></p>
                <p><i class="fas fa-check-circle"></i> <strong>Trạng thái thanh toán: </strong> <?= htmlspecialchars($trangThaiThanhToan) ?></p>
                <p><i class="fas fa-calendar-check"></i> <strong>Thời gian thanh toán: </strong> <?= htmlspecialchars($formattedDate) ?></p>
            </div>
        </div>

        <div class="right">
            <div class="section">
                <h2>Chi tiết lịch trình</h2>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Điểm đi: </strong> <?= htmlspecialchars($diemDi) ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Điểm đến: </strong> <?= htmlspecialchars($diemDen) ?></p>
                <p><i class="fas fa-calendar-day"></i> <strong>Ngày khởi hành: </strong> <?= htmlspecialchars($ngayKhoiHanh) ?></p>
                <p><i class="fas fa-clock"></i> <strong>Giờ khởi hành: </strong> <?= htmlspecialchars($gioKhoiHanh) ?></p>
                <p><i class="fas fa-money-bill-wave"></i> <strong>Giá vé: </strong> <?= number_format($soTien) ?> VND</p>
                <p><i class="fas fa-chair"></i> <strong>Số ghế: </strong> <?= htmlspecialchars($gheList) ?></p>
            </div>
            
            <div class="section">
                <h2>Thông tin vé</h2>
                <p><i class="fas fa-ticket-alt"></i> <strong>Mã vé: </strong> <?= htmlspecialchars($codeVeXe) ?></p>
            </div>
        </div>
    </div>

    <a href="index.php" class="button">Quay lại trang chủ</a>
</div>
</body>
</html>
