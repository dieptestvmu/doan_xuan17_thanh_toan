<link rel="stylesheet" href="css_admin/admin_styles.css">
<script src="js_admin/admin_scripts.js"></script>
<?php
session_start();
require '../config.php';
require 'includes_admin/admin_header.php';
require 'includes_admin/admin_sidebar.php';

// Kiểm tra xem người dùng có phải là admin không
if ($_SESSION['quyenhanid'] != 1) {
    header("Location: ../index.php");
    exit();
}

// Lấy ID khách hàng
$khachHangID = $_GET['id'];

// Lấy thông tin khách hàng
$sql = "SELECT * FROM KhachHang WHERE KhachHangID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $khachHangID);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Lấy lịch sử đặt vé của khách hàng
$sql = "SELECT VeXe.*, ChoNgoi.SoGhe, TuyenXe.DiemDi, TuyenXe.DiemDen, LichTrinhXe.NgayKhoiHanh, LichTrinhXe.GioKhoiHanh, LichTrinhXe.GiaVe
        FROM VeXe
        JOIN ChoNgoi ON VeXe.ChoNgoiID = ChoNgoi.ChoNgoiID
        JOIN LichTrinhXe ON VeXe.LichTrinhXeID = LichTrinhXe.LichTrinhXeID
        JOIN TuyenXe ON LichTrinhXe.TuyenXeID = TuyenXe.TuyenXeID
        WHERE VeXe.KhachHangID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $khachHangID);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="main-content">
    <h1>Lịch Sử Đặt Vé của Khách Hàng</h1>
    <p><strong>Họ tên:</strong> <?= $customer['HoTen'] ?></p>
    <p><strong>Số điện thoại:</strong> <?= $customer['SoDienThoai'] ?></p>
    <p><strong>Email:</strong> <?= $customer['Email'] ?></p>
    <p><strong>Ghi chú:</strong> <?= $customer['GhiChu'] ?></p>

    <table>
        <tr>
            <th>ID Vé</th>
            <th>Điểm đi</th>
            <th>Điểm đến</th>
            <th>Ngày khởi hành</th>
            <th>Giờ khởi hành</th>
            <th>Chỗ ngồi</th>
            <th>Giá vé</th>
            <th>Trạng thái</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['VeXeID'] ?></td>
            <td><?= $row['DiemDi'] ?></td>
            <td><?= $row['DiemDen'] ?></td>
            <td><?= $row['NgayKhoiHanh'] ?></td>
            <td><?= $row['GioKhoiHanh'] ?></td>
            <td><?= $row['SoGhe'] ?></td>
            <td><?= $row['GiaVe'] ?> VND</td>
            <td><?= $row['TrangThai'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <button onclick="window.location.href='customers.php'">Quay lại</button>
</div>

<?php
require 'includes_admin/admin_footer.php';
?>
