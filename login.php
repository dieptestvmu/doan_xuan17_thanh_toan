<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM NguoiDung WHERE TenDangNhap = ? AND MatKhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['userid'] = $user['NguoiDungID'];
        $_SESSION['username'] = $user['TenDangNhap'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['avatar'] = 'path_to_avatar_image'; // Đặt đường dẫn đến avatar người dùng nếu có
        $_SESSION['quyenhanid'] = $user['QuyenHanID'];

        if ($user['QuyenHanID'] == 1) {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
    } else {
        echo "Tên đăng nhập hoặc mật khẩu không đúng.";
    }

    // if ($result->num_rows > 0) {
    //     $user = $result->fetch_assoc();
    //     $_SESSION['userid'] = $user['NguoiDungID'];
    //     $_SESSION['username'] = $user['TenDangNhap'];
    //     $_SESSION['email'] = $user['Email'];
    //     $_SESSION['avatar'] = 'path_to_avatar_image'; // Đặt đường dẫn đến avatar người dùng nếu có
    //     header("Location: index.php");
    // } else {
    //     echo "Tên đăng nhập hoặc mật khẩu sai";
    // }
}
?>
