<?php
session_start();
require 'config.php';

if (!isset($_SESSION['userid'])) {
    echo "Bạn cần đăng nhập để thực hiện chức năng này";
    exit();
}

$userid = $_SESSION['userid'];
$sql = "SELECT NguoiDung.TenDangNhap, NguoiDung.Email AS NguoiDungEmail, KhachHang.HoTen, KhachHang.SoDienThoai, KhachHang.Email AS KhachHangEmail
        FROM nguoidung NguoiDung
        LEFT JOIN khachhang KhachHang ON NguoiDung.NguoiDungID = KhachHang.NguoiDungID
        WHERE NguoiDung.NguoiDungID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/account.js" defer></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin: 10px 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            transition: background-color 0.3s;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .small-button {
            padding: 5px 10px;
            font-size: 12px;
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Quản Lý Tài Khoản</h1>
        <p>Tên đăng nhập: <?= $user['TenDangNhap'] ?></p>
        <p>Họ tên: <?= $user['HoTen'] ? $user['HoTen'] : '' ?></p>
        <p>Số điện thoại: <?= $user['SoDienThoai'] ? $user['SoDienThoai'] : '' ?></p>
        <p>Email: <?= $user['NguoiDungEmail'] ? $user['NguoiDungEmail'] : ($user['KhachHangEmail'] ? $user['KhachHangEmail'] : '') ?></p>
        <button id="editInfoBtn">Sửa thông tin</button>
        <button id="changePasswordBtn">Đổi mật khẩu</button>
        <button onclick="window.location.href='index.php'">Trang chủ</button>
    </div>

    <div id="editInfoForm" class="form-container" style="display: none;">
        <h2>Sửa Thông Tin</h2>
        <form id="editInfo" action="update_info.php" method="POST">
            <div class="form-group">
                <label for="newHoTen">Họ tên:</label>
                <input type="text" id="newHoTen" name="newHoTen" value="<?= $user['HoTen'] ?>">
            </div>
            <div class="form-group">
                <label for="newSoDienThoai">Số điện thoại:</label>
                <input type="text" id="newSoDienThoai" name="newSoDienThoai" value="<?= $user['SoDienThoai'] ?>">
            </div>
            <div class="form-group">
                <label for="newEmail">Email:</label>
                <input type="email" id="newEmail" name="newEmail" value="<?= $user['NguoiDungEmail'] ? $user['NguoiDungEmail'] : ($user['KhachHangEmail'] ? $user['KhachHangEmail'] : '') ?>">
            </div>
            <button type="submit">Cập nhật</button>
        </form>
    </div>

    <div id="changePasswordForm" class="form-container" style="display: none;">
        <h2>Đổi Mật Khẩu</h2>
        <form id="changePassword" action="change_password.php" method="POST">
            <div class="form-group">
                <label for="currentPassword">Mật khẩu hiện tại:</label>
                <input type="password" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newPassword">Mật khẩu mới:</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <div class="form-group">
                <label for="confirmNewPassword">Nhập lại mật khẩu mới:</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
            </div>
            <button type="submit">Xác nhận</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Thông tin vé của tôi</h2>
        <table>
        <?php
        // Lấy KhachHangID dựa trên NguoiDungID
        $sqlKhachHang = "SELECT KhachHangID FROM khachhang WHERE NguoiDungID = ?";
        $stmtKhachHang = $conn->prepare($sqlKhachHang);
        $stmtKhachHang->bind_param('i', $userid);
        $stmtKhachHang->execute();
        $resultKhachHang = $stmtKhachHang->get_result();
        $khachhangIDs = [];
        while ($row = $resultKhachHang->fetch_assoc()) {
            $khachhangIDs[] = $row['KhachHangID'];
        }

        if (count($khachhangIDs) > 0) {
            // Chuẩn bị một chuỗi các dấu chấm hỏi (placeholders) cho mỗi KhachHangID
            $placeholders = implode(',', array_fill(0, count($khachhangIDs), '?'));

            // Lấy thông tin vé dựa trên KhachHangID
            $sqlTickets = "SELECT VeXe.*, ChoNgoi.SoGhe, TuyenXe.DiemDi, TuyenXe.DiemDen, LichTrinhXe.NgayKhoiHanh, LichTrinhXe.GioKhoiHanh, LichTrinhXe.GiaVe, ThanhToan.TrangThaiThanhToan
               FROM vexe VeXe
               JOIN chongoi ChoNgoi ON VeXe.ChoNgoiID = ChoNgoi.ChoNgoiID
               JOIN lichtrinhxe LichTrinhXe ON VeXe.LichTrinhXeID = LichTrinhXe.LichTrinhXeID
               JOIN tuyenxe TuyenXe ON LichTrinhXe.TuyenXeID = TuyenXe.TuyenXeID
               LEFT JOIN thanhtoan ThanhToan ON VeXe.VeXeID = ThanhToan.VeXeID
               WHERE VeXe.KhachHangID IN ($placeholders)";

            $stmtTickets = $conn->prepare($sqlTickets);

            // Liên kết các giá trị KhachHangID vào các placeholders
            $stmtTickets->bind_param(str_repeat('i', count($khachhangIDs)), ...$khachhangIDs);
            $stmtTickets->execute();
            $resultTickets = $stmtTickets->get_result();

            if ($resultTickets->num_rows > 0) {
                echo "<tr>
                        <th>Điểm đi</th>
                        <th>Điểm đến</th>
                        <th>Ngày khởi hành</th>
                        <th>Giờ khởi hành</th>
                        <th>Chỗ ngồi</th>
                        <th>Giá vé</th>
                        <th>Mã vé</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Thời gian đặt vé</th>
                        <th>Hành động</th>
                      </tr>";
                while ($ticket = $resultTickets->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $ticket['DiemDi'] . "</td>";
                    echo "<td>" . $ticket['DiemDen'] . "</td>";
                    echo "<td>" . $ticket['NgayKhoiHanh'] . "</td>";
                    echo "<td>" . $ticket['GioKhoiHanh'] . "</td>";
                    echo "<td>" . $ticket['SoGhe'] . "</td>";
                    echo "<td>" . $ticket['GiaVe'] . " VND</td>";
                    echo "<td>" . $ticket['CodeVeXe'] . "</td>";
                    echo "<td>" . $ticket['TrangThaiThanhToan'] . "</td>";
                    echo "<td>" . $ticket['ThoiGianDatVe'] . "</td>";
                    echo "<td><button class='small-button cancel-ticket-btn' data-ticket-id='" . $ticket['VeXeID'] . "'>Hủy vé</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "Bạn chưa đặt vé nào.";
            }
            
        } else {
            echo "Bạn chưa đặt vé nào.";
        }
        ?>
        </table>
    </div>

    <div id="cancelTicketForm" class="form-container" style="display: none;">
        <h2>Xác nhận hủy vé</h2>
        <p>Chọn lý do hủy:</p>
        <form id="cancelTicket" method="POST" action="cancel_ticket.php">
            <input type="hidden" id="cancelTicketID" name="cancelTicketID">
            <label><input type="radio" name="reason" value="Tôi bận nên không đi nữa"> Tôi bận nên không đi nữa</label><br>
            <label><input type="radio" name="reason" value="Tôi đặt nhầm giờ/ngày"> Tôi đặt nhầm giờ/ngày</label><br>
            <label><input type="radio" name="reason" value="Tôi đặt nhầm tuyến đường"> Tôi đặt nhầm tuyến đường</label><br>
            <label><input type="radio" name="reason" value="Nhà xe không có đủ tiện ích cần thiết"> Nhà xe không có đủ tiện ích cần thiết</label><br>
            <label><input type="radio" name="reason" value="Tôi muốn đón dọc đường/trung chuyển nhưng nhà xe không đón"> Tôi muốn đón dọc đường/trung chuyển nhưng nhà xe không đón</label><br>
            <button type="button" id="confirmCancelTicket" class="small-button" style="color: red; border-color: red;">Hủy vé</button>
            <button type="button" id="closeCancelTicket" class="small-button">Đóng lại</button>
        </form>
    </div>

    <div id="warningForm" class="form-container" style="display: none;">
        <h2>Cảnh báo</h2>
        <p>Vui lòng chọn lí do hủy vé</p>
    </div>

    <div id="successForm" class="form-container" style="display: none;">
        <h2>Đã hủy vé thành công</h2>
    </div>

    <script src="js/account.js"></script>
</body>
</html>
