<?php
session_start();
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
        .option-group-label {
            font-weight: bold;
        }
        .option-sub-item {
            padding-left: 15px;
        }
        .date-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .date-container input[type="date"] {
            width: 100%;
            box-sizing: border-box;
        }
        .modal-form {
            max-width: 500px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transform: translateY(100%);
            transition: transform 0.5s ease-in-out;
            position: relative;
        }
        .form-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .form-container.active .modal-form {
            transform: translateY(0);
        }
        .btn-link {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
            color: #007bff;
            cursor: pointer;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
    <script src="js/scripts.js" defer></script>
</head>
<body>
<?php include 'includes/header.php'; ?>

<!-- ảnh trang chủ - hãng xe Đoàn Xuân -->
<img width="1516" height="707" src="images/header2.jpg" alt="header">
<div class="welcome-message-container">
    <h1>Chào mừng đến với Đoàn Xuân</h1>
    <p>Đặt vé xe khách một cách dễ dàng và nhanh chóng.</p>
</div>

<!-- Phần tìm kiếm -->
<div id="search-section" class="container mt-5">
    <h2>Tìm chuyến đi</h2>
    <form id="search-form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="diemDi">Điểm đi</label>
                <select class="form-control" id="diemDi" name="diemDi" required>
                    <option value="">Chọn điểm đi</option>
                    <optgroup label="Khu vực Hà Nội" class="option-group-label">
                        <option class="option-sub-item" value="Bến xe Gia Lâm">Bến xe Gia Lâm</option>
                        <option class="option-sub-item" value="Bến xe Yên Nghĩa">Bến xe Yên Nghĩa</option>
                    </optgroup>
                    <optgroup label="Khu vực Hải Phòng" class="option-group-label">
                        <option class="option-sub-item" value="Bến xe Niệm Nghĩa">Bến xe Niệm Nghĩa</option>
                        <option class="option-sub-item" value="Kiến An">Kiến An</option>
                        <option class="option-sub-item" value="Quý Cao">Quý Cao</option>
                        <option class="option-sub-item" value="Vĩnh Bảo">Vĩnh Bảo</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="diemDen">Điểm đến</label>
                <select class="form-control" id="diemDen" name="diemDen" required>
                    <option value="">Chọn điểm đến</option>
                    <optgroup label="Khu vực Hà Nội" class="option-group-label">
                        <option class="option-sub-item" value="Bến xe Gia Lâm">Bến xe Gia Lâm</option>
                        <option class="option-sub-item" value="Bến xe Yên Nghĩa">Bến xe Yên Nghĩa</option>
                    </optgroup>
                    <optgroup label="Khu vực Hải Phòng" class="option-group-label">
                        <option class="option-sub-item" value="Bến xe Niệm Nghĩa">Bến xe Niệm Nghĩa</option>
                        <option class="option-sub-item" value="Kiến An">Kiến An</option>
                        <option class="option-sub-item" value="Quý Cao">Quý Cao</option>
                        <option class="option-sub-item" value="Vĩnh Bảo">Vĩnh Bảo</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="ngayDi">Ngày đi</label>
                <div class="date-container">
                    <input type="date" class="form-control" id="ngayDi" name="ngayDi" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>
    <div id="search-results" class="mt-5"></div> <!-- Kết quả tìm kiếm sẽ hiển thị ở đây -->
</div>

<?php include 'content_in_index.php'; ?>
<?php include 'includes/footer.php'; ?>

<!-- Cuộn xuống phần tìm kiếm -->
<script src="js/js_for_index.js"></script>
<script src="js/show_date.js"></script>
<div class="form-container" id="login-container">
    <div class="modal-form">
        <button class="close-btn" id="close-login">&times;</button>
        <h2>Đăng nhập</h2>
        <form id="login" action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
        <button id="forgot-password-btn" class="btn btn-link">Quên mật khẩu</button>
        <button id="register-btn" class="btn btn-link">Đăng ký</button>
    </div>
</div>


<div class="form-container" id="forgot-password-container">
    <div class="modal-form">
        <button class="close-btn" id="close-forgot-password">&times;</button>
        <h2>Quên mật khẩu</h2>
        <form id="forgot-password-form">
            <div class="form-group">
                <label for="forgot-email">Email:</label>
                <input type="email" id="forgot-email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Xác nhận</button>
        </form>
        <button id="back-to-login-from-forgot" class="btn btn-link">Quay lại Đăng nhập</button>
    </div>
</div>

<div class="form-container" id="register-container">
    <div class="modal-form">
        <button class="close-btn" id="close-register">&times;</button>
        <h2>Đăng ký</h2>
        <form id="register-form">
            <div class="form-group">
                <label for="register-username">Tên đăng nhập:</label>
                <input type="text" id="register-username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="register-password">Mật khẩu:</label>
                <input type="password" id="register-password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="register-fullname">Họ tên:</label>
                <input type="text" id="register-fullname" name="fullname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="register-phone">Số điện thoại:</label>
                <input type="text" id="register-phone" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="register-email">Email:</label>
                <input type="email" id="register-email" name="email" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </form>
        <button id="back-to-login-from-register" class="btn btn-link">Quay lại Đăng nhập</button>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#loginBtn').click(function() {
        $('#login-container').addClass('active').show();
    });

    $('#forgot-password-btn').click(function() {
        $('#login-container').removeClass('active').hide();
        $('#forgot-password-container').addClass('active').show();
    });

    $('#register-btn').click(function() {
        $('#login-container').removeClass('active').hide();
        $('#register-container').addClass('active').show();
    });

    $('#back-to-login-from-forgot').click(function() {
        $('#forgot-password-container').removeClass('active').hide();
        $('#login-container').addClass('active').show();
    });

    $('#back-to-login-from-register').click(function() {
        $('#register-container').removeClass('active').hide();
        $('#login-container').addClass('active').show();
    });

    $('.close-btn').click(function() {
        $(this).closest('.form-container').removeClass('active').hide();
    });


    $('#register-form').submit(function(e) {
    e.preventDefault();

    // Disable the submit button to prevent multiple submissions
    var $submitBtn = $(this).find('button[type="submit"]');
    $submitBtn.prop('disabled', true);

    $.ajax({
        url: 'register.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                $('#register-container').removeClass('active').hide();
                $('#login-container').addClass('active').show();
            } else {
                alert(data.message || 'Đăng ký không thành công!');
            }
            $submitBtn.prop('disabled', false); // Re-enable the submit button
        },
        error: function() {
            alert('Đăng ký không thành công!');
            $submitBtn.prop('disabled', false); // Re-enable the submit button
        }
    });
});

});



    $('#forgot-password-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'forgot_password.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#forgot-password-container').removeClass('active').hide();
                    $('#login-container').addClass('active').show();
                } else {
                    alert('Có lỗi xảy ra!');
                }
            }
        });
    });
});
</script>
</body>
</html>
