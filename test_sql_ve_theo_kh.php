<?php
include 'includes/db.php';

$KhachHangID = 4;
$query = $conn->prepare("
    SELECT vx.VeXeID, vx.NhanVienID, vx.KhachHangID, vx.ChoNgoiID, vx.TrangThai, vx.KhuyenMaiID, vx.GhiChu, vx.LichTrinhXeID, vx.ThoiGianDatVe, vx.CodeVeXe
    FROM vexe vx
    INNER JOIN khachhang kh ON vx.KhachHangID = kh.KhachHangID
    WHERE kh.KhachHangID = ?
");
$query->bind_param('i', $KhachHangID);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thống Kê Vé Xe</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Thống Kê Vé Xe của Người Dùng ID = 4</h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>VeXeID</th>
                    <th>NhanVienID</th>
                    <th>KhachHangID</th>
                    <th>ChoNgoiID</th>
                    <th>TrangThai</th>
                    <th>KhuyenMaiID</th>
                    <th>GhiChu</th>
                    <th>LichTrinhXeID</th>
                    <th>ThoiGianDatVe</th>
                    <th>CodeVeXe</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['VeXeID']); ?></td>
                        <td><?php echo htmlspecialchars($row['NhanVienID']); ?></td>
                        <td><?php echo htmlspecialchars($row['KhachHangID']); ?></td>
                        <td><?php echo htmlspecialchars($row['ChoNgoiID']); ?></td>
                        <td><?php echo htmlspecialchars($row['TrangThai']); ?></td>
                        <td><?php echo htmlspecialchars($row['KhuyenMaiID']); ?></td>
                        <td><?php echo htmlspecialchars($row['GhiChu']); ?></td>
                        <td><?php echo htmlspecialchars($row['LichTrinhXeID']); ?></td>
                        <td><?php echo htmlspecialchars($row['ThoiGianDatVe']); ?></td>
                        <td><?php echo htmlspecialchars($row['CodeVeXe']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
