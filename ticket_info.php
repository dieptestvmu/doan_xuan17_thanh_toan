<?php
include 'includes/db.php';

if (isset($_GET['veXeID'])) {
    $veXeID = $_GET['veXeID'];

    $sql = "SELECT vx.*, kh.HoTen, kh.SoDienThoai, kh.Email, lt.NgayKhoiHanh, lt.GioKhoiHanh, tx.DiemDi, tx.DiemDen, tt.SoTien
            FROM vexe vx
            JOIN khachhang kh ON vx.KhachHangID = kh.KhachHangID
            JOIN lichtrinhxe lt ON vx.LichTrinhXeID = lt.LichTrinhXeID
            JOIN tuyenxe tx ON lt.TuyenXeID = tx.TuyenXeID
            JOIN thanhtoan tt ON vx.VeXeID = tt.VeXeID
            WHERE vx.VeXeID = '$veXeID'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hoTen = $row['HoTen'];
        $soDienThoai = $row['SoDienThoai'];
        $email = $row['Email'];
        $ngayKhoiHanh = $row['NgayKhoiHanh'];
        $gioKhoiHanh = $row['GioKhoiHanh'];
        $diemDi = $row['DiemDi'];
        $diemDen = $row['DiemDen'];
        $soTien = $row['SoTien'];
    } else {
        echo "<p>Không tìm thấy thông tin vé.</p>";
    }
} else {
    echo "<p>Không tìm thấy thông tin vé.</p>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin vé</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap
