<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newHoTen = $_POST['newHoTen'];
    $newSoDienThoai = $_POST['newSoDienThoai'];
    $newEmail = $_POST['newEmail'];
    $userid = $_SESSION['userid'];

    $sql = "UPDATE KhachHang SET HoTen = ?, SoDienThoai = ?, Email = ? WHERE NguoiDungID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $newHoTen, $newSoDienThoai, $newEmail, $userid);
    $stmt->execute();

    // Cập nhật email trong bảng NguoiDung nếu có
    if ($newEmail) {
        $sql = "UPDATE NguoiDung SET Email = ? WHERE NguoiDungID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newEmail, $userid);
        $stmt->execute();
    }

    header("Location: account.php");
}
?>
