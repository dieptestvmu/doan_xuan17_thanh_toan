<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];
    $userid = $_SESSION['userid'];

    if ($newPassword !== $confirmNewPassword) {
        echo "Mật khẩu mới và xác nhận mật khẩu không khớp.";
        exit();
    }

    $sql = "SELECT MatKhau FROM NguoiDung WHERE NguoiDungID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['MatKhau'] !== $currentPassword) {
        echo "Mật khẩu hiện tại không đúng.";
        exit();
    }

    $sql = "UPDATE NguoiDung SET MatKhau = ? WHERE NguoiDungID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $newPassword, $userid);
    $stmt->execute();

    echo "Thay đổi mật khẩu thành công.";
    header("Location: account.php");
}
?>
