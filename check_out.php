<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    // Lấy thời gian hiện tại
    $timestamp = time();

    // Cộng thêm 15 phút (900 giây)
    $timestamp += 900;

    // Chuyển đổi timestamp thành chuỗi thời gian
    $new_time_string = date("YmdHis", $timestamp);
    echo $new_time_string
    ?>
    <div class="form-group">
        <label >Thời hạn thanh toán</label>
        <input class="form-control" id="txtexpire"
                name="txtexpire" type="text" value="<?php echo $new_time_string; ?>"/>
    </div>
    <form action="online_checkout_controller.php" method="POST">
        <button type="submit" name="redirect" class="btn btn-success">Thanh toán VNpay</button>
    </form>
</body>
</html>
