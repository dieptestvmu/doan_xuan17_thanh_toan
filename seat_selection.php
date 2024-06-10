
<?php include 'includes/db.php'; ?>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];

$sqlUser = "SELECT nd.TenDangNhap, nd.Email, kh.HoTen, kh.SoDienThoai 
            FROM nguoidung nd 
            JOIN khachhang kh ON nd.NguoiDungID = kh.NguoiDungID 
            WHERE nd.TenDangNhap = '$username'";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $hoTen = $rowUser['HoTen'];
    $soDienThoai = $rowUser['SoDienThoai'];
    $email = $rowUser['Email'];
} else {
    $hoTen = '';
    $soDienThoai = '';
    $email = '';
}
?>
<?php include 'includes/header.php'; ?>


<!-- Thêm thẻ link dưới đây -->
<link rel="stylesheet" type="text/css" href="css/styles.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
.seat {
    width: 50px;
    height: 50px;
    margin: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: none; /* Loại bỏ viền */
    background: transparent; /* Loại bỏ màu nền */
}

.seat img {
    width: 100%;
    height: 100%;
}

.seat-trong img {
    content: url('images/logo_ghe_trong.png');
}

.seat-dangchon img {
    content: url('images/logo_ghe_dang_chon.png');
}

.seat-dadat img {
    content: url('images/logo_ghe_da_dat.png');
    cursor: not-allowed;
}

.seat-daban img {
    content: url('images/logo_ghe_da_ban.png');
    cursor: not-allowed;
}

.seat-khongban img {
    content: url('images/logo_ghe_khong_ban.png');
    cursor: not-allowed;
}
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="container mt-5">
    <?php
    if (isset($_GET['lichTrinhXeID'])) {
        $lichTrinhXeID = $_GET['lichTrinhXeID'];

        // Truy vấn thông tin lịch trình xe và tuyến xe
        $sqlLichTrinhXe = "SELECT lt.NgayKhoiHanh, lt.GioKhoiHanh, tx.DiemDi, tx.DiemDen, x.BienSo, lt.GiaVe, lt.XeID, x.SucChua
                           FROM LichTrinhXe lt
                           JOIN TuyenXe tx ON lt.TuyenXeID = tx.TuyenXeID
                           JOIN Xe x ON lt.XeID = x.XeID
                           WHERE lt.LichTrinhXeID = '$lichTrinhXeID' LIMIT 1";
        $resultLichTrinhXe = $conn->query($sqlLichTrinhXe);

        if ($resultLichTrinhXe->num_rows > 0) {
            $rowLichTrinhXe = $resultLichTrinhXe->fetch_assoc();
            $noiDi = $rowLichTrinhXe['DiemDi'];
            $noiDen = $rowLichTrinhXe['DiemDen'];
            $ngayDi = $rowLichTrinhXe['NgayKhoiHanh'];
            $gioDi = $rowLichTrinhXe['GioKhoiHanh'];
            $giaVe = $rowLichTrinhXe['GiaVe'];
            $bienSo = $rowLichTrinhXe['BienSo'];
            $xeID = $rowLichTrinhXe['XeID'];
            $sucChua = $rowLichTrinhXe['SucChua'];
        } else {
            echo "<p>Không tìm thấy thông tin lịch trình xe.</p>";
            $xeID = 0; // Gán giá trị mặc định nếu không có thông tin lịch trình xe
        }
    } else {
        echo "<p>Không tìm thấy thông tin lịch trình xe.</p>";
        $xeID = 0; // Gán giá trị mặc định nếu không có thông tin lịch trình xe
    }
    ?>

    <script>
        var giaVe = <?php echo json_encode($giaVe); ?>;
        var lichTrinhXeID = <?php echo json_encode($lichTrinhXeID); ?>;
        var selectedSeats = [];
        
        function updateSelectedSeats() {
            var selectedSeatsDisplay = selectedSeats.map(seat => seat.soGhe).join(", ");
            var totalPrice = selectedSeats.length * giaVe;
            $('#seat-number').val(selectedSeatsDisplay);
            $('#total-price').val(totalPrice + " VND");
        }

        $(document).on('click', '.seat-trong, .seat-dangchon', function() {
            var seatId = $(this).data('seat-id');
            var soGhe = $(this).data('so-ghe');
            if ($(this).hasClass('seat-trong')) {
                $(this).removeClass('seat-trong').addClass('seat-dangchon');
                selectedSeats.push({ seatId: seatId, soGhe: soGhe });
            } else if ($(this).hasClass('seat-dangchon')) {
                $(this).removeClass('seat-dangchon').addClass('seat-trong');
                selectedSeats = selectedSeats.filter(seat => seat.seatId !== seatId);
            }
            updateSelectedSeats();
        });

        $(document).ready(function() {
    $('#confirmBtn').click(function() {
        if (selectedSeats.length > 0 && $('#full-name').val() && $('#phone-number').val() && $('#pickup-point').val() && $('#dropoff-point').val()) {
            // Collect form data
            var formData = {
                fullName: $('#full-name').val(),
                phoneNumber: $('#phone-number').val(),
                email: $('#email').val(),
                pickupPoint: $('#pickup-point').val(),
                dropoffPoint: $('#dropoff-point').val(),
                selectedSeats: selectedSeats,
                lichTrinhXeID: lichTrinhXeID,
                promoCode: $('#promo-code').val()
            };

            // Send the booking request via AJAX
            $.ajax({
                url: 'process_booking.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        var veXeIDs = data.veXeIDs.join(',');
                        window.location.href = 'payment.php?veXeIDs=' + veXeIDs;
                    } else {
                        alert('Đã xảy ra lỗi: ' + data.message);
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi kết nối với máy chủ. Vui lòng thử lại.');
                }
            });
        } else {
            alert('Vui lòng chọn ít nhất 1 chỗ ngồi và điền đầy đủ thông tin trên.');
        }
    });
});



    </script>

    <div class="row" style="padding-top: 60px;">
        <div class="col-md-12 text-center">
            <h2>Điểm đi: <?php echo isset($noiDi) ? $noiDi : ''; ?></h2>
            <h2>Điểm đến: <?php echo isset($noiDen) ? $noiDen : ''; ?></h2>
            <h2>Ngày khởi hành: <?php echo isset($ngayDi) ? $ngayDi : ''; ?></h2>
            <h2>Giờ khởi hành: <?php echo isset($gioDi) ? $gioDi : ''; ?></h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Chọn chỗ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Thanh toán</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Hoàn thành</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <h4>Biển số: <?php echo isset($bienSo) ? $bienSo : ''; ?></h4>
            <h4>Giá vé: <?php echo isset($giaVe) ? $giaVe : 0; ?> VND</h4>
        </div>
    </div>
    <div class="row mt-3">
        
        <div class="col-md-6">
            <div class="seat-map d-flex flex-wrap justify-content-center">
                
            <?php
                if (isset($xeID) && $xeID > 0) {
                    $sql = "SELECT * FROM ChoNgoi WHERE XeID = '$xeID'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $seatCount = 0;
                        while ($row = $result->fetch_assoc()) {
                            $seatCount++;
                            $status_class = "";
                            switch ($row['TrangThai']) {
                                case 'Trống':
                                    $status_class = "seat-trong";
                                    break;
                                case 'Không bán':
                                    $status_class = "seat-khongban";
                                    break;
                                case 'Đã đặt':
                                    $status_class = "seat-dadat";
                                    break;
                                case 'Đã bán':
                                    $status_class = "seat-daban";
                                    break;
                            }
                            echo "<div class='seat $status_class' data-seat-id='{$row['ChoNgoiID']}' data-so-ghe='{$row['SoGhe']}'><img></div>";
                            
                            if ($sucChua <= 30 && $seatCount % 4 == 0) {
                                echo "<div style='flex-basis: 100%; height: 0;'></div>"; // Row break
                            } elseif ($sucChua > 30 && $seatCount % 4 == 0 && $seatCount < 45) {
                                echo "<div style='flex-basis: 100%; height: 0;'></div>"; // Row break
                            } elseif ($seatCount % 2 == 0) {
                                echo "<div style='width: 50px; height: 50px;'></div>"; // Space between seat pairs
                            }
                        }
                    } else {
                        echo "Không có chỗ ngồi nào.";
                    }
                } else {
                    echo "Không tìm thấy thông tin xe.";
                }
            ?>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 seat-status">
                    <div class="seat seat-trong"><img></div> Ghế trống
                </div>
                <div class="col-md-4 seat-status">
                    <div class="seat seat-dadat"><img></div> Ghế đã đặt
                </div>
                <div class="col-md-4 seat-status">
                    <div class="seat seat-dangchon"><img></div> Ghế đang chọn
                </div>
            </div>
        </div>
        <div class="col-md-6" style="left: 100px;">
            <form id="seatForm">
                <div class="form-group">
                    <label for="seat-number">Ghế đã chọn</label>
                    <input type="text" class="form-control" id="seat-number" disabled>
                </div>
                <div class="form-group">
                    <label for="total-price">Tổng tiền</label>
                    <input type="text" class="form-control" id="total-price" disabled>
                </div>
                <div class="form-group">
                    <label for="full-name">Họ tên</label>
                    <span class="required">*</span>
                    <input type="text" class="form-control" id="full-name" name="full-name" value="<?php echo htmlspecialchars($hoTen); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone-number">Số điện thoại</label>
                    <span class="required">*</span>
                    <input type="text" class="form-control" id="phone-number" name="phone-number" value="<?php echo htmlspecialchars($soDienThoai); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea class="form-control" id="notes" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="pickup-point">Điểm đón</label>
                    <!-- <span class="required">*</span> -->
                    <select class="form-control" id="pickup-point" required>
                        <!-- <option value="">Chọn điểm đi</option> -->
                        <option value="Tại bến">Tại bến</option>
                        <option value="Đón tận nơi">Đón tận nơi</option>
                        <option value="<?php echo isset($noiDi) ? $noiDi : ''; ?>"><?php echo isset($noiDi) ? $noiDi : ''; ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dropoff-point">Điểm trả</label>
                    <!-- <span class="required">*</span> -->
                    <select class="form-control" id="dropoff-point" required>
                        <!-- <option value="">Chọn điểm đến</option> -->
                        <option value="Tại bến">Tại bến</option>
                        <option value="Trả tận nơi">Trả tận nơi</option>
                        <option value="<?php echo isset($noiDen) ? $noiDen : ''; ?>"><?php echo isset($noiDen) ? $noiDen : ''; ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="promo-code">Mã khuyến mãi</label>
                    <input type="text" class="form-control" id="promo-code">
                </div>
                <button type="button" class="btn btn-primary" id="check-promo">Kiểm tra mã</button>
                <button type="button" class="btn btn-success" id="confirmBtn">Tiếp tục</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/scripts.js"></script>
