<?php
session_start();
include 'includes/db.php';

function generateRandomCode($length = 5) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
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

try {
    // Lấy thông tin NguoiDungID từ tên đăng nhập
    $sqlUser = "SELECT NguoiDungID FROM nguoidung WHERE TenDangNhap = '$username'";
    $resultUser = $conn->query($sqlUser);
    if ($resultUser->num_rows == 0) {
        throw new Exception('User not found');
    }
    $rowUser = $resultUser->fetch_assoc();
    $nguoiDungID = $rowUser['NguoiDungID'];

    // Lấy thông tin KhachHangID từ NguoiDungID
    $sqlCustomer = "SELECT KhachHangID FROM khachhang WHERE NguoiDungID = '$nguoiDungID'";
    $resultCustomer = $conn->query($sqlCustomer);
    if ($resultCustomer->num_rows == 0) {
        throw new Exception('Customer not found');
    }
    $rowCustomer = $resultCustomer->fetch_assoc();
    $khachHangID = $rowCustomer['KhachHangID'];

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
    if ($resultGiaVe->num_rows == 0) {
        throw new Exception('Ticket price not found');
    }
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
                          VALUES (NULL, '$khachHangID', '$seatID', 'Đã đặt', '$khuyenMaiID', '', '$lichTrinhXeID', NOW(), '$codeVeXe')";
        if (!$conn->query($sqlInsertVeXe)) {
            throw new Exception('Failed to insert ticket: ' . $conn->error);
        }
        $veXeID = $conn->insert_id;
        array_push($veXeIDArray, $veXeID);

        // Cập nhật trạng thái chỗ ngồi
        $sqlUpdateChoNgoi = "UPDATE chongoi SET TrangThai = 'Đã đặt' WHERE ChoNgoiID = '$seatID'";
        if (!$conn->query($sqlUpdateChoNgoi)) {
            throw new Exception('Failed to update seat status: ' . $conn->error);
        }
    }

    // Lưu thông tin vào bảng ThanhToan
    $sqlInsertThanhToan = "INSERT INTO thanhtoan (VeXeID, SoTien, PhuongThucThanhToan, TrangThaiThanhToan, NgayThanhToan) 
                           VALUES ('$veXeID', '$totalPrice', 'Online', 'Đang chờ', NOW())";
    if (!$conn->query($sqlInsertThanhToan)) {
        throw new Exception('Failed to insert payment: ' . $conn->error);
    }

    echo json_encode(['success' => true, 'veXeIDs' => $veXeIDArray]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
