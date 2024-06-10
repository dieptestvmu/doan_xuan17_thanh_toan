<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Quả Tìm Kiếm</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <button onclick="window.location.href='index.php'">Trang chủ</button>
    <h1>Kết Quả Tìm Kiếm</h1>
    <?php
require 'config.php';

$diemdi = $_GET['diemdi'];
$diemden = $_GET['diemden'];
$ngaykhoihanh = $_GET['ngaykhoihanh'];

$sql = "SELECT LichTrinhXe.*, Xe.BienSo, TuyenXe.DiemDi, TuyenXe.DiemDen
        FROM LichTrinhXe
        JOIN Xe ON LichTrinhXe.XeID = Xe.XeID
        JOIN TuyenXe ON LichTrinhXe.TuyenXeID = TuyenXe.TuyenXeID
        WHERE TuyenXe.DiemDi = ? AND TuyenXe.DiemDen = ? AND LichTrinhXe.NgayKhoiHanh = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $diemdi, $diemden, $ngaykhoihanh);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Biển số xe</th><th>Điểm đi</th><th>Điểm đến</th><th>Ngày khởi hành</th><th>Giờ khởi hành</th><th>Chọn</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['BienSo'] . "</td>";
        echo "<td>" . $row['DiemDi'] . "</td>";
        echo "<td>" . $row['DiemDen'] . "</td>";
        echo "<td>" . $row['NgayKhoiHanh'] . "</td>";
        echo "<td>" . $row['GioKhoiHanh'] . "</td>";
        echo "<td><a href='details.php?id=" . $row['LichTrinhXeID'] . "'>Chọn</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Không tìm thấy chuyến đi nào.";
}
?>

</body>
</html>