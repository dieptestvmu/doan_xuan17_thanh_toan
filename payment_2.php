<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form seat_selection.php
    $selectedSeats = $_POST['selectedSeats'];
    $fullName = $_POST['fullName'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $pickupPoint = $_POST['pickupPoint'];
    $dropoffPoint = $_POST['dropoffPoint'];
    $lichTrinhXeID = $_POST['lichTrinhXeID'];
    $giaVe = $_POST['giaVe'];
    $totalPrice = count($selectedSeats) * $giaVe;

    // Tạo mã giao dịch duy nhất cho VNPay
    $transactionID = uniqid();

    // Lưu thông tin vào session
    $_SESSION['transactionID'] = $transactionID;
    $_SESSION['selectedSeats'] = $selectedSeats;
    $_SESSION['fullName'] = $fullName;
    $_SESSION['phoneNumber'] = $phoneNumber;
    $_SESSION['email'] = $email;
    $_SESSION['pickupPoint'] = $pickupPoint;
    $_SESSION['dropoffPoint'] = $dropoffPoint;
    $_SESSION['lichTrinhXeID'] = $lichTrinhXeID;
    $_SESSION['giaVe'] = $giaVe;
    $_SESSION['totalPrice'] = $totalPrice;
    $_SESSION['startTime'] = time();
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2>Thanh Toán</h2>
            <p>Thời gian thanh toán còn lại: <span id="countdown" style="color: red;"></span></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="vnpay_payment.php" method="POST">
                <input type="hidden" name="transactionID" value="<?php echo $_SESSION['transactionID']; ?>">
                <input type="hidden" name="totalPrice" value="<?php echo $_SESSION['totalPrice']; ?>">
                <button type="submit" class="btn btn-primary btn-block">Thanh toán qua VNPay</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Đếm ngược 15 phút
    var countDownDate = new Date().getTime() + 15 * 60 * 1000;

    var countdownfunction = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = minutes + "m " + seconds + "s ";

        if (distance < 0) {
            clearInterval(countdownfunction);
            document.getElementById("countdown").innerHTML = "Hết giờ";
            window.location.href = 'seat_selection.php';
        }
    }, 1000);
</script>

<?php include 'includes/footer.php'; ?>
