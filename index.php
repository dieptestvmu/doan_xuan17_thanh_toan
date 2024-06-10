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

<!-- nút gọi điện rung lắc -->
<style>
    .hotline-phone-ring-wrap {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 999999;
}
.hotline-phone-ring {
  position: relative;
  visibility: visible;
  background-color: transparent;
  width: 110px;
  height: 110px;
  cursor: pointer;
  z-index: 11;
  -webkit-backface-visibility: hidden;
  -webkit-transform: translateZ(0);
  transition: visibility .5s;
  left: 0;
  bottom: 0;
  display: block;
}
.hotline-phone-ring-circle {
	width: 85px;
  height: 85px;
  top: 10px;
  left: 10px;
  position: absolute;
  background-color: transparent;
  border-radius: 100%;
  border: 2px solid #e60808;
  -webkit-animation: phonering-alo-circle-anim 1.2s infinite ease-in-out;
  animation: phonering-alo-circle-anim 1.2s infinite ease-in-out;
  transition: all .5s;
  -webkit-transform-origin: 50% 50%;
  -ms-transform-origin: 50% 50%;
  transform-origin: 50% 50%;
  opacity: 0.5;
}
.hotline-phone-ring-circle-fill {
	width: 55px;
  height: 55px;
  top: 25px;
  left: 25px;
  position: absolute;
  background-color: rgba(230, 8, 8, 0.7);
  border-radius: 100%;
  border: 2px solid transparent;
  -webkit-animation: phonering-alo-circle-fill-anim 2.3s infinite ease-in-out;
  animation: phonering-alo-circle-fill-anim 2.3s infinite ease-in-out;
  transition: all .5s;
  -webkit-transform-origin: 50% 50%;
  -ms-transform-origin: 50% 50%;
  transform-origin: 50% 50%;
}
.hotline-phone-ring-img-circle {
	background-color: #e60808;
	width: 33px;
  height: 33px;
  top: 37px;
  left: 37px;
  position: absolute;
  background-size: 20px;
  border-radius: 100%;
  border: 2px solid transparent;
  -webkit-animation: phonering-alo-circle-img-anim 1s infinite ease-in-out;
  animation: phonering-alo-circle-img-anim 1s infinite ease-in-out;
  -webkit-transform-origin: 50% 50%;
  -ms-transform-origin: 50% 50%;
  transform-origin: 50% 50%;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  align-items: center;
  justify-content: center;
}
.hotline-phone-ring-img-circle .pps-btn-img {
	display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
}
.hotline-phone-ring-img-circle .pps-btn-img img {
	width: 20px;
	height: 20px;
}
.hotline-bar {
  position: absolute;
  background: rgba(230, 8, 8, 0.75);
  height: 40px;
  width: 180px;
  line-height: 40px;
  border-radius: 3px;
  padding: 0 10px;
  background-size: 100%;
  cursor: pointer;
  transition: all 0.8s;
  -webkit-transition: all 0.8s;
  z-index: 9;
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.1);
  border-radius: 50px !important;
  /* width: 175px !important; */
  left: 33px;
  bottom: 37px;
}
.hotline-bar > a {
  color: #fff;
  text-decoration: none;
  font-size: 15px;
  font-weight: bold;
  text-indent: 50px;
  display: block;
  letter-spacing: 1px;
  line-height: 40px;
  font-family: Arial;
}
.hotline-bar > a:hover,
.hotline-bar > a:active {
  color: #fff;
}
@-webkit-keyframes phonering-alo-circle-anim {
  0% {
    -webkit-transform: rotate(0) scale(0.5) skew(1deg);
    -webkit-opacity: 0.1;
  }
  30% {
    -webkit-transform: rotate(0) scale(0.7) skew(1deg);
    -webkit-opacity: 0.5;
  }
  100% {
    -webkit-transform: rotate(0) scale(1) skew(1deg);
    -webkit-opacity: 0.1;
  }
}
@-webkit-keyframes phonering-alo-circle-fill-anim {
  0% {
    -webkit-transform: rotate(0) scale(0.7) skew(1deg);
    opacity: 0.6;
  }
  50% {
    -webkit-transform: rotate(0) scale(1) skew(1deg);
    opacity: 0.6;
  }
  100% {
    -webkit-transform: rotate(0) scale(0.7) skew(1deg);
    opacity: 0.6;
  }
}
@-webkit-keyframes phonering-alo-circle-img-anim {
  0% {
    -webkit-transform: rotate(0) scale(1) skew(1deg);
  }
  10% {
    -webkit-transform: rotate(-25deg) scale(1) skew(1deg);
  }
  20% {
    -webkit-transform: rotate(25deg) scale(1) skew(1deg);
  }
  30% {
    -webkit-transform: rotate(-25deg) scale(1) skew(1deg);
  }
  40% {
    -webkit-transform: rotate(25deg) scale(1) skew(1deg);
  }
  50% {
    -webkit-transform: rotate(0) scale(1) skew(1deg);
  }
  100% {
    -webkit-transform: rotate(0) scale(1) skew(1deg);
  }
}
@media (max-width: 768px) {
  .hotline-bar {
    display: none;
  }
}
</style>

<div class="hotline-phone-ring-wrap">
	<div class="hotline-phone-ring">
		<div class="hotline-phone-ring-circle"></div>
		<div class="hotline-phone-ring-circle-fill"></div>
		<div class="hotline-phone-ring-img-circle">
		<a href="tel:0316278888" class="pps-btn-img">
			<img src="https://wiki.minhduy.vn/wp-content/uploads/2022/07/icon-call-nh.png" alt="Gọi điện thoại" width="50">
		</a>
		</div>
	</div>
	<div class="hotline-bar">
		<a href="tel:0316278888">
			<span class="text-hotline">0316.278.888</span>
		</a>
	</div>
</div>



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
        <button id="register-btn" class="btn btn-link"  style="margin-left: 20px;">Đăng ký</button>
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
                $submitBtn.prop('disabled', false);
            },
            error: function() {
                alert('Đăng ký không thành công!');
                $submitBtn.prop('disabled', false);
            }
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