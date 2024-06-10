<?php include 'includes/header.php'; ?>

<?php include 'includes/db.php'; ?>

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
    border: 1px solid #ccc;
    cursor: pointer;
}

.seat-trong {
    background-color: #fff;
}

.seat-dangchon {
    background-color: #6c757d;
    color: #fff;
}

.seat-dadat {
    background-color: #dc3545;
    color: #fff;
    cursor: not-allowed;
}

.seat-daban {
    background-color: #28a745;
    color: #fff;
    cursor: not-allowed;
}

.seat-khongban {
    background-color: #6c757d;
    color: #fff;
    cursor: not-allowed;
}

</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="container mt-5">
    <?php
    if (isset($_GET['lichTrinhXeID'])) {
        $lichTrinhXeID = $_GET['lichTrinhXeID'];

        // Truy vấn thông tin lịch trình xe và tuyến xe
        $sqlLichTrinhXe = "SELECT lt.NgayKhoiHanh, lt.GioKhoiHanh, tx.DiemDi, tx.DiemDen, tx.ThoiGianDi, x.BienSo, x.XeID, lt.GiaVe 
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
        } else {
            echo "<p>Không tìm thấy thông tin lịch trình xe.</p>";
        }
    } else {
        echo "<p>Không tìm thấy thông tin lịch trình xe.</p>";
        $xeID = 0; // Gán giá trị mặc định nếu không có thông tin lịch trình xe
    }
    ?>

    <script>
        var giaVe = <?php echo json_encode($giaVe); ?>;
        var lichTrinhXeID = <?php echo json_encode($lichTrinhXeID); ?>;
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
                    $sql = "SELECT * FROM ChoNgoi WHERE XeID = '$xeID' ORDER BY SoGhe ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
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
                            echo "<div class='seat $status_class' data-seat-id='{$row['ChoNgoiID']}'>{$row['SoGhe']}</div>";
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
                <div class="col-md-2 seat-status">
                    <img src="images/logo_ghe_trong.png" alt="Ghế trống">
                    Ghế trống
                </div>
                <div class="col-md-2 seat-status">
                    <img src="images/logo_ghe_da_dat.png" alt="Ghế đã đặt">
                    Ghế đã đặt
                </div>
                <div class="col-md-2 seat-status">
                    <img src="images/logo_ghe_da_ban.png" alt="Ghế đã bán">
                    Ghế đã bán
                </div>
                <div class="col-md-2 seat-status">
                    <img src="images/logo_ghe_khong_ban.png" alt="Ghế không bán">
                    Ghế không bán
                </div>
                <div class="col-md-2 seat-status">
                    <img src="images/logo_ghe_dang_chon.png" alt="Ghế đang chọn">
                    Ghế đang chọn
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
                    <input type="text" class="form-control" id="full-name" required>
                </div>
                <div class="form-group">
                    <label for="phone-number">Số điện thoại</label>
                    <span class="required">*</span>
                    <input type="text" class="form-control" id="phone-number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email">
                </div>
                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea class="form-control" id="notes" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="pickup-point">Điểm đi</label>
                    <span class="required">*</span>
                    <select class="form-control" id="pickup-point" required>
                        <option value="">Chọn điểm đi</option>
                        <option value="Tại bến">Tại bến</option>
                        <option value="Đón tận nơi">Đón tận nơi</option>
                        <option value="<?php echo isset($noiDi) ? $noiDi : ''; ?>"><?php echo isset($noiDi) ? $noiDi : ''; ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dropoff-point">Điểm đến</label>
                    <span class="required">*</span>
                    <select class="form-control" id="dropoff-point" required>
                        <option value="">Chọn điểm đến</option>
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
                <button type="submit" class="btn btn-success" id="confirmBtn">Tiếp tục</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/scripts.js"></script>
