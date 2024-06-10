<?php
session_start();
require 'config.php';

$lichtrinhxeid = $_POST['lichtrinhxeid'];
$seats = json_decode($_POST['seats']);
$hoten = $_POST['hoten'];
$sodienthoai = $_POST['sodienthoai'];
$thoigian_datve = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại

function generateCodeVeXe() {
    return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
}

if (count($seats) > 0) {
    $conn->begin_transaction();
    try {
        // Thêm khách hàng vào bảng KhachHang
        $sql = "INSERT INTO KhachHang (NguoiDungID, HoTen, SoDienThoai, Email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $_SESSION['userid'], $hoten, $sodienthoai, $_SESSION['email']);
        $stmt->execute();
        $khachhangid = $stmt->insert_id;

        // Thêm vé vào bảng VeXe cho mỗi ghế ngồi
        foreach ($seats as $seat) {
            $codeVeXe = generateCodeVeXe();
            $sqlTicket = "INSERT INTO VeXe (KhachHangID, ChoNgoiID, LichTrinhXeID, TrangThai, ThoiGianDatVe, CodeVeXe) VALUES (?, ?, ?, 'Đã đặt', ?, ?)";
            $stmtTicket = $conn->prepare($sqlTicket);
            $stmtTicket->bind_param('iiiss', $khachhangid, $seat, $lichtrinhxeid, $thoigian_datve, $codeVeXe);
            $stmtTicket->execute();

            // Cập nhật trạng thái ghế ngồi thành 'Đã đặt'
            $sqlUpdateSeat = "UPDATE ChoNgoi SET TrangThai = 'Đã đặt' WHERE ChoNgoiID = ?";
            $stmtUpdateSeat = $conn->prepare($sqlUpdateSeat);
            $stmtUpdateSeat->bind_param('i', $seat);
            $stmtUpdateSeat->execute();
        }
        $conn->commit();
        header("Location: payment.php?khachhangid=$khachhangid");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Đặt vé thất bại: " . $e->getMessage();
    }
} else {
    echo "Vui lòng chọn chỗ ngồi.";
}
?>
