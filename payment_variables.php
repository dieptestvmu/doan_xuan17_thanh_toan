<!-- payment_variables.php -->
<?php
session_start();
include 'includes/db.php';

$codeVeXe = isset($_GET['codeVeXe']) ? $_GET['codeVeXe'] : '';
$soTien = isset($_GET['soTien']) ? $_GET['soTien'] : '';
$noiDungThanhToan = isset($_GET['noiDungThanhToan']) ? $_GET['noiDungThanhToan'] : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Các biến thanh toán</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h2>Thông tin các biến thanh toán</h2>
        </div>
        <div class="card-body">
            <p><strong>Mã vé xe:</strong> <?php echo htmlspecialchars($codeVeXe); ?> (Kiểu dữ liệu: <?php echo gettype($codeVeXe); ?>)</p>
            <p><strong>Tổng tiền:</strong> <?php echo htmlspecialchars($soTien); ?> VND (Kiểu dữ liệu: <?php echo gettype($soTien); ?>)</p>
            <p><strong>Nội dung thanh toán:</strong> <?php echo htmlspecialchars($noiDungThanhToan); ?> (Kiểu dữ liệu: <?php echo gettype($noiDungThanhToan); ?>)</p>
        </div>
    </div>
</div>
</body>
</html>
