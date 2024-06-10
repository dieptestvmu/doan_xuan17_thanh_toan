<?php
session_start();
include 'includes/db.php';

function generateRandomCode($length = 5) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];
$fullName = $_POST['fullName'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'];
$pickupPoint = $_POST['pickupPoint'];
$dropoffPoint = $_POST['dropoffPoint'];
$selectedSeats = $_POST['selectedSeats'];
$lichTrinhXeID = $_POST['lichTrinhXeID'];
$promoCode = $_POST['promoCode'];

// Lấy thông tin NguoiDungID từ tên đăng nhập
$sqlUser = "SELECT NguoiDungID FROM nguoidung WHERE TenDangNhap = '$username'";
$resultUser = $conn->query($sqlUser);
$rowUser = $resultUser->fetch_assoc();
$nguoiDungID = $rowUser['NguoiDungID'];

// Lấy thông tin KhuyenMaiID nếu có
$khuyenMaiID = null;
if ($promoCode) {
    $sqlPromo = "SELECT KhuyenMaiID FROM khuyenmai WHERE MaCode = '$promoCode'";
    $resultPromo = $conn->query($sqlPromo);
    if ($resultPromo->num_rows > 0) {
        $rowPromo = $resultPromo->fetch_assoc();
        $khuyenMaiID = $rowPromo['KhuyenMaiID'];
    }
}

// Lấy giá vé từ bảng LichTrinhXe
$sqlGiaVe = "SELECT GiaVe FROM lichtrinhxe WHERE LichTrinhXeID = '$lichTrinhXeID'";
$resultGiaVe = $conn->query($sqlGiaVe);
$rowGiaVe = $resultGiaVe->fetch_assoc();
$giaVe = $rowGiaVe['GiaVe'];

$codeVeXe = generateRandomCode();
$veXeIDArray = [];
$totalPrice = 0; // Tổng tiền khởi đầu bằng 0

// Lưu thông tin vào bảng VeXe
foreach ($selectedSeats as $seat) {
    $seatID = $seat['seatId']; // Lấy seatId từ object
    $soGhe = $seat['soGhe']; // Lấy soGhe từ object
    $totalPrice += $giaVe; // Tính tổng tiền

    $sqlInsertVeXe = "INSERT INTO vexe (NhanVienID, KhachHangID, ChoNgoiID, TrangThai, KhuyenMaiID, GhiChu, LichTrinhXeID, ThoiGianDatVe, CodeVeXe) 
                      VALUES (NULL, '$nguoiDungID', '$seatID', 'Đã đặt', '$khuyenMaiID', '', '$lichTrinhXeID', NOW(), '$codeVeXe')";
    $conn->query($sqlInsertVeXe);
    $veXeID = $conn->insert_id;
    array_push($veXeIDArray, $veXeID);

    // Cập nhật trạng thái chỗ ngồi
    $sqlUpdateChoNgoi = "UPDATE chongoi SET TrangThai = 'Đã đặt' WHERE ChoNgoiID = '$seatID'";
    $conn->query($sqlUpdateChoNgoi);
}

// Lưu thông tin vào bảng ThanhToan
$sqlInsertThanhToan = "INSERT INTO thanhtoan (VeXeID, SoTien, PhuongThucThanhToan, TrangThaiThanhToan, NgayThanhToan) 
                       VALUES ('$veXeID', '$totalPrice', 'Online', 'Đang chờ', NOW())";
$conn->query($sqlInsertThanhToan);

echo json_encode(['success' => true, 'veXeIDs' => $veXeIDArray]);
?>
