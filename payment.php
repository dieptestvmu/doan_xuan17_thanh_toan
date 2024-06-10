<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$veXeIDs = isset($_GET['veXeIDs']) ? explode(',', $_GET['veXeIDs']) : [];
if (empty($veXeIDs)) {
    $veDetails = [];
} else {
    $veXeIDList = implode(',', array_map('intval', $veXeIDs));
    $sql = "SELECT vx.*, kh.HoTen, kh.SoDienThoai, kh.Email, lt.NgayKhoiHanh, lt.GioKhoiHanh, tx.DiemDi, tx.DiemDen, tt.*, lt.GiaVe, cg.SoGhe, xe.LoaiXe
            FROM vexe vx
            JOIN khachhang kh ON vx.KhachHangID = kh.KhachHangID
            JOIN lichtrinhxe lt ON vx.LichTrinhXeID = lt.LichTrinhXeID
            JOIN tuyenxe tx ON lt.TuyenXeID = tx.TuyenXeID
            LEFT JOIN thanhtoan tt ON vx.VeXeID = tt.VeXeID
            JOIN chongoi cg ON vx.ChoNgoiID = cg.ChoNgoiID
            JOIN xe ON lt.XeID = xe.XeID
            WHERE vx.VeXeID IN ($veXeIDList)";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $veDetails = [];
        while ($row = $result->fetch_assoc()) {
            $veDetails[] = $row;
        }
    } else {
        $veDetails = [];
    }
}

$hoTen = !empty($veDetails) ? $veDetails[0]['HoTen'] : '';
$soDienThoai = !empty($veDetails) ? $veDetails[0]['SoDienThoai'] : '';
$email = !empty($veDetails) ? $veDetails[0]['Email'] : '';
$ngayKhoiHanh = !empty($veDetails) ? $veDetails[0]['NgayKhoiHanh'] : '';
$gioKhoiHanh = !empty($veDetails) ? $veDetails[0]['GioKhoiHanh'] : '';
$diemDi = !empty($veDetails) ? $veDetails[0]['DiemDi'] : '';
$diemDen = !empty($veDetails) ? $veDetails[0]['DiemDen'] : '';
$codeVeXe = !empty($veDetails) ? $veDetails[0]['CodeVeXe'] : '';
$loaiXe = !empty($veDetails) ? $veDetails[0]['LoaiXe'] : '';
$phuongThucThanhToan = !empty($veDetails) ? $veDetails[0]['PhuongThucThanhToan'] : '';
$soTien = !empty($veDetails) ? array_sum(array_column($veDetails, 'SoTien')) : 0;
$trangThaiThanhToan = !empty($veDetails) ? $veDetails[0]['TrangThaiThanhToan'] : '';

$gheDetails = [];
if (!empty($veDetails)) {
    foreach ($veDetails as $ve) {
        $gheDetails[] = $ve['SoGhe'];
    }
}
$gheList = implode(', ', $gheDetails);

$noiDungThanhToan = "tt " . $codeVeXe;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    .countdown {
        font-size: 24px;
        font-weight: bold;
        color: #dc3545;
    }

    .status {
        font-size: 18px;
        font-weight: bold;
        color: red;
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
        <h1>Thông tin vé</h1>
    </div>
    <div class="content">
        <?php if (empty($veDetails)): ?>
            <div class="alert alert-danger" role="alert">
                Không tìm thấy thông tin vé.
            </div>
        <?php else: ?>
            <div class="left">
                <div class="section">
                    <h2>Thông tin khách hàng</h2>
                    <p><i class="fas fa-user"></i> <strong>Họ tên: </strong> <?= htmlspecialchars($hoTen) ?></p>
                    <p><i class="fas fa-phone"></i> <strong>Số điện thoại: </strong> <?= htmlspecialchars($soDienThoai) ?></p>
                    <p><i class="fas fa-envelope"></i> <strong>Email: </strong> <?= htmlspecialchars($email) ?></p>
                </div>

                <div class="section">
                    <h2>Chi tiết lịch trình</h2>
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Điểm đi: </strong> <?= htmlspecialchars($diemDi) ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Điểm đến: </strong> <?= htmlspecialchars($diemDen) ?></p>
                    <p><i class="fas fa-calendar-day"></i> <strong>Ngày khởi hành: </strong> <?= htmlspecialchars($ngayKhoiHanh) ?></p>
                    <p><i class="fas fa-clock"></i> <strong>Giờ khởi hành: </strong> <?= htmlspecialchars($gioKhoiHanh) ?></p>
                    <p><i class="fas fa-chair"></i> <strong>Số ghế: </strong> <?= htmlspecialchars($gheList) ?></p>
                    <p><i class="fas fa-bus"></i> <strong>Loại xe: </strong> <?= htmlspecialchars($loaiXe) ?></p>
                    <p><i class="fas fa-money-bill-wave"></i> <strong>Tổng tiền: </strong> <?= number_format($soTien) ?> VND</p>
                </div>
            </div>

            <div class="right">
                <div class="section">
                    <h2>Thông tin thanh toán</h2>
                    <p><i class="fas fa-credit-card"></i> <strong>Phương thức thanh toán: </strong> <?= htmlspecialchars($phuongThucThanhToan) ?></p>
                    <p><i class="fas fa-hourglass-half"></i> <strong>Thời hạn thanh toán: </strong> <span class="countdown" id="countdown">15:00</span></p>
                    <p><i class="fas fa-check-circle"></i> <strong>Trạng thái thanh toán: </strong><span class="status"><?= htmlspecialchars($trangThaiThanhToan) ?></span></p>
                    <form id="paymentForm" method="post">
                        <?php foreach ($veXeIDs as $veXeID) { ?>
                            <input type="hidden" name="veXeIDs[]" value="<?= htmlspecialchars($veXeID) ?>">
                        <?php } ?>
                    </form>
                </div>

                <form action="online_checkout_controller.php" id="create_form" method="post"> 
                    <input type="hidden" name="codeVeXe" value="<?= htmlspecialchars($codeVeXe) ?>">
                    <input type="hidden" name="noiDungThanhToan" value="<?= htmlspecialchars($noiDungThanhToan) ?>">
                    <input type="hidden" name="soTien" value="<?= htmlspecialchars($soTien) ?>">
                    <button type="submit" name="redirect" id="redirect" class="button">Tiến hành thanh toán</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
let time = 15 * 60; // 15 phút
const countdownElement = document.getElementById('countdown');

function updateCountdown() {
    const minutes = Math.floor(time / 60);
    const seconds = time % 60;
    countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    time--;

    if (time < 0) {
        clearInterval(countdownInterval);
        alert('Thời gian thanh toán đã hết. Vui lòng thử lại.');
    }
}

const countdownInterval = setInterval(updateCountdown, 1000);
</script>
</body>
</html>
