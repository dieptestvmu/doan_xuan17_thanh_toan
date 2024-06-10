<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Liên Hệ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="contact-container">
        <div class="contact-header">
            <h1>THÔNG TIN LIÊN HỆ</h1>
            <p>Công Ty TNHH Thương Mại Đoàn Xuân</p>
            <p><a href="tel:0316278888">0316 278 888</a> | <a href="mailto:contact@doanxuanbus.vn">contact@doanxuanbus.vn</a></p>
        </div>
        <div class="contact-content">
            <div class="contact-info">
                <h2>THÔNG TIN TRỤ SỞ</h2>
                <div class="location">
                    <h3>Hải Phòng</h3>
                    <p>726 Võ Nguyên Giáp, Vĩnh Niệm, Lê Chân, Hải Phòng</p>
                </div>
            </div>
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3729.125699634465!2d106.68461407476386!3d20.82662999469126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314a7156326ceb2f%3A0xeb91e1ee8a684f4e!2zxJBvw6BuIFh1w6JuIEJ1cw!5e0!3m2!1svi!2s!4v1717385961303!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="contact-form">
            <h2>Liên Hệ Với Chúng Tôi</h2>
            <form action="submit_contact.php" method="post">
                <label for="name">Họ và tên *</label>
                <input type="text" id="name" name="name" required>
                
                <label for="phone">Số điện thoại *</label>
                <input type="text" id="phone" name="phone" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
                
                <label for="subject">Tiêu đề</label>
                <input type="text" id="subject" name="subject">
                
                <label for="message">Nội dung liên hệ</label>
                <textarea id="message" name="message"></textarea>
                
                <button type="submit">GỬI THƯ</button>
            </form>
        </div>
        <div class="contact-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-facebook-messenger"></i></a>
            <a href="#"><i class="fas fa-phone"></i></a>
            <a href="#"><i class="fas fa-link"></i></a>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
