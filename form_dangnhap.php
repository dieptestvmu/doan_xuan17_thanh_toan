<?php
session_start();
include 'includes/db.php'; // Kết nối tới database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra đăng nhập
    $query = "SELECT * FROM nguoidung WHERE TenDangNhap = ? AND MatKhau = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['userid'] = $user['NguoiDungID'];
        $_SESSION['username'] = $user['TenDangNhap'];
        $_SESSION['avatar'] = 'images/login.png'; // Đường dẫn tới avatar

        if ($user['QuyenHanID'] == 1) {
            header('Location: admin/index.php');
            exit();
        } else {
            $loggedIn = true;
        }
    } else {
        $loginError = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Vé Xe Đoàn Xuân</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #0071c1;
            color: white;
        }
        .header .brand-text {
            font-weight: bold;
            font-size: 24px;
        }
        .header .nav-menu a {
            color: white;
            margin-left: 20px;
        }
        .header .nav-menu a.glow-effect {
            text-shadow: 0 0 5px #fff;
            transition: text-shadow 0.3s;
        }
        .header .nav-menu a.glow-effect:hover {
            text-shadow: none;
        }
        .form-container {
            display: none;
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
        }
        .form-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .form-box h2 {
            margin-bottom: 20px;
        }
        .form-box form {
            display: flex;
            flex-direction: column;
        }
        .form-box form input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a class="navbar-brand" href="/">
            <img src="images/logo.jpg" alt="Đoàn Xuân Bus Logo" height="40" class="d-inline-block align-top rounded-logo">
            <span class="brand-text">Đoàn Xuân Bus</span>
        </a>
        <div class="nav-menu">
            <a href="/">Trang Chủ</a>
            <a href="#search-section" class="glow-effect">Tìm Chuyến đi</a>
            <a href="#">Khuyến Mãi</a>
            <a href="contact.php">Liên Hệ</a>
        </div>
        <?php if (isset($_SESSION['userid'])): ?>
            <div>
                <img src="<?= $_SESSION['avatar'] ?>" alt="Avatar" height="30" class="d-inline-block align-top">
                <span><?= $_SESSION['username'] ?></span>
            </div>
        <?php else: ?>
            <button id="loginRegisterBtn" class="btn btn-primary">Đăng nhập/Đăng ký</button>
        <?php endif; ?>
    </div>

    <div id="loginRegisterForm" class="form-container">
        <div class="form-box">
            <h2>Đăng nhập</h2>
            <?php if (isset($loginError)): ?>
                <div class="alert alert-danger"><?= $loginError ?></div>
            <?php endif; ?>
            <form id="loginForm" method="POST">
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
                <button type="button" id="forgotPasswordBtn" class="btn btn-link">Quên mật khẩu</button>
                <button type="button" id="registerBtn" class="btn btn-link">Đăng ký</button>
            </form>
        </div>
    </div>

    <div id="forgotPasswordForm" class="form-container">
        <div class="form-box">
            <h2>Quên mật khẩu</h2>
            <form id="forgotPassword" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" class="btn btn-primary">Xác nhận</button>
                <button type="button" id="backToLoginFromForgot" class="btn btn-link">Đăng nhập</button>
            </form>
        </div>
    </div>

    <div id="registerForm" class="form-container">
        <div class="form-box">
            <h2>Đăng ký</h2>
            <form id="register" method="POST">
                <input type="text" name="regUsername" placeholder="Tên đăng nhập" required>
                <input type="password" name="regPassword" placeholder="Mật khẩu" required>
                <input type="email" name="regEmail" placeholder="Email">
                <button type="submit" class="btn btn-primary">Đăng ký</button>
                <button type="button" id="backToLoginFromRegister" class="btn btn-link">Đăng nhập</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginRegisterBtn').click(function() {
                $('#loginRegisterForm').show();
            });

            $('#forgotPasswordBtn').click(function() {
                $('#loginRegisterForm').hide();
                $('#forgotPasswordForm').show();
            });

            $('#registerBtn').click(function() {
                $('#loginRegisterForm').hide();
                $('#registerForm').show();
            });

            $('#backToLoginFromForgot').click(function() {
                $('#forgotPasswordForm').hide();
                $('#loginRegisterForm').show();
            });

            $('#backToLoginFromRegister').click(function() {
                $('#registerForm').hide();
                $('#loginRegisterForm').show();
            });
        });
    </script>
</body>
</html>
