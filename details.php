<?php
session_start();
require 'config.php';

$lichtrinhxeid = $_GET['id'];

if (!isset($_SESSION['userid'])) {
    echo "Bạn cần đăng nhập để thực hiện chức năng này";
    exit();
}

$sql = "SELECT LichTrinhXe.*, Xe.BienSo, TuyenXe.DiemDi, TuyenXe.DiemDen, LichTrinhXe.GiaVe
        FROM LichTrinhXe
        JOIN Xe ON LichTrinhXe.XeID = Xe.XeID
        JOIN TuyenXe ON LichTrinhXe.TuyenXeID = TuyenXe.TuyenXeID
        WHERE LichTrinhXe.LichTrinhXeID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $lichtrinhxeid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    echo "<button onclick=\"window.location.href='index.php'\">Trang chủ</button>";
    echo "<h1>Thông tin chi tiết xe</h1>";
    echo "<p>Biển số xe: " . $row['BienSo'] . "</p>";
    echo "<p>Điểm đi: " . $row['DiemDi'] . "</p>";
    echo "<p>Điểm đến: " . $row['DiemDen'] . "</p>";
    echo "<p>Ngày khởi hành: " . $row['NgayKhoiHanh'] . "</p>";
    echo "<p>Giờ khởi hành: " . $row['GioKhoiHanh'] . "</p>";
    echo "<p>Giá vé: " . $row['GiaVe'] . " VND</p>";

    $userid = $_SESSION['userid'];
    $sqlCustomer = "SELECT * FROM KhachHang WHERE NguoiDungID = ?";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    $stmtCustomer->bind_param('i', $userid);
    $stmtCustomer->execute();
    $resultCustomer = $stmtCustomer->get_result();
    $customer = $resultCustomer->fetch_assoc();

    echo "<h2>Chọn chỗ ngồi</h2>";
    echo "<form id='seatForm' action='book_ticket.php' method='POST'>";
    echo "<input type='hidden' name='lichtrinhxeid' value='$lichtrinhxeid'>";
    echo "<input type='hidden' id='giaVe' value='" . $row['GiaVe'] . "'>";

    $sqlSeats = "SELECT * FROM ChoNgoi WHERE XeID = ?";
    $stmtSeats = $conn->prepare($sqlSeats);
    $stmtSeats->bind_param('i', $row['XeID']);
    $stmtSeats->execute();
    $resultSeats = $stmtSeats->get_result();

    if ($resultSeats->num_rows > 0) {
        echo "<div class='seats'>";
        while($seat = $resultSeats->fetch_assoc()) {
            $seatStatus = $seat['TrangThai'];
            $seatImage = '';
            switch ($seatStatus) {
                case 'Trống':
                    $seatImage = 'images/logo_ghe_trong.png';
                    break;
                case 'Đã đặt':
                    $seatImage = 'images/logo_ghe_da_dat.png';
                    break;
                case 'Đã bán':
                    $seatImage = 'images/logo_ghe_da_ban.png';
                    break;
                case 'Không bán':
                    $seatImage = 'images/logo_ghe_khong_ban.png';
                    break;
                case 'Đang chọn':
                    $seatImage = 'images/logo_ghe_dang_chon.png';
                    break;
            }
            echo "<div class='seat' data-seat-id='" . $seat['ChoNgoiID'] . "' data-seat-status='" . $seatStatus . "'>";
            echo "<img src='$seatImage' alt='Ghế'>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "Không có chỗ ngồi nào.";
    }

    echo "<div id='total'></div>";
    
    if ($customer) {
        echo "<h2>Thông tin khách hàng</h2>";
        echo "<p>Họ tên: " . $customer['HoTen'] . "</p>";
        echo "<p>Số điện thoại: " . $customer['SoDienThoai'] . "</p>";
        echo "<input type='hidden' name='hoten' value='" . $customer['HoTen'] . "'>";
        echo "<input type='hidden' name='sodienthoai' value='" . $customer['SoDienThoai'] . "'>";
    } else {
        echo "<h2>Thông tin khách hàng</h2>";
        echo "<label for='hoten'>Họ tên:</label>";
        echo "<input type='text' id='hoten' name='hoten' required>";
        echo "<label for='sodienthoai'>Số điện thoại:</label>";
        echo "<input type='text' id='sodienthoai' name='sodienthoai' required>";
    }
    
    echo "<button type='submit'>Đặt vé</button>";
    echo "</form>";
} else {
    echo "Không tìm thấy thông tin xe.";
}
?>
<link rel="stylesheet" href="css/styles.css">
<script src="js/scripts.js"></script>
