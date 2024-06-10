<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles_hop_nhat.css">
    <title>Đoàn Xuân Bus</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.jpg"/>
</head>
<body>
    <style>
        #loginBtn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        #loginBtn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        #dangXuat  {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        #dangXuat :hover {
            background-color: #218838;
            transform: scale(1.05);
        }

    </style>
    <div class="header">
        <a class="navbar-brand" href="/doan_xuan17_thanh_toan">
            <img src="images/logo.jpg" alt="Đoàn Xuân Bus Logo" height="40" class="d-inline-block align-top rounded-logo"> <!-- Add class "rounded-logo" -->
            <span class="brand-text">Đoàn Xuân Bus</span> <!-- Add class "brand-text" -->
        </a>
        <div class="nav-menu">
            <a href="/doan_xuan17_thanh_toan">Trang Chủ</a>
            <a href="#search-section" class="glow-effect">Tìm Chuyến đi</a> <!-- Thêm class "glow-effect" -->
            <a href="#">Khuyến Mãi</a>
            <a href="contact.php">Liên Hệ</a>
        </div>
        <div id="authSection">
            <?php if (isset($_SESSION['userid'])): ?>
                
                <!-- $_SESSION['avatar'] = 'img/av.png'; -->

                <a href="account.php">
                    <img src="<?= 'images/login.png' ?>" alt="Avatar" style="width: 50px; height: 50px;">
                </a>
                <span>Xin chào, <?= $_SESSION['username'] ?></span>
                <form id="logoutForm" action="logout.php" method="POST" style="display:inline;">
                    <button id = dangXuat type="submit">Đăng xuất</button>
                </form>
            <?php else: ?>
                <button id="loginBtn">Đăng nhập/Đăng ký</button>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
