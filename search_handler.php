<?php include 'includes/db.php'; ?>

<?php
if (isset($_GET['diemDi']) && isset($_GET['diemDen']) && isset($_GET['ngayDi'])) {
    $diemDi = $_GET['diemDi'];
    $diemDen = $_GET['diemDen'];
    $ngayDi = $_GET['ngayDi'];

    // Chuyển đổi ngày đi sang định dạng Y-m-d
    $ngayDi = date('Y-m-d', strtotime($ngayDi));

    // Hiển thị các giá trị để kiểm tra
    // echo "Điểm đi: $diemDi<br>";
    // echo "Điểm đến: $diemDen<br>";
    // echo "Ngày đi: $ngayDi<br>";

    // Truy vấn cơ sở dữ liệu với điều kiện ngày đi từ bảng LichTrinhXe
    $sql = "
    SELECT 
        LichTrinhXe.LichTrinhXeID, LichTrinhXe.NgayKhoiHanh, LichTrinhXe.GioKhoiHanh, LichTrinhXe.TrangThai,
        TuyenXe.DiemDi, TuyenXe.DiemDen, TuyenXe.KhoangCach,
        Xe.LoaiXe, Xe.BienSo, Xe.SucChua, Xe.TrangThai AS TrangThaiXe,
        LichTrinhXe.GiaVe
    FROM LichTrinhXe
    JOIN TuyenXe ON LichTrinhXe.TuyenXeID = TuyenXe.TuyenXeID
    JOIN Xe ON LichTrinhXe.XeID = Xe.XeID
    WHERE TuyenXe.DiemDi='$diemDi' AND TuyenXe.DiemDen='$diemDen' AND LichTrinhXe.NgayKhoiHanh = '$ngayDi'
";


    // echo "SQL Query: $sql<br>"; // Hiển thị câu lệnh SQL để kiểm tra

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="list-group mt-4">';
        while($row = $result->fetch_assoc()) {
            $loaiXe = $row["LoaiXe"];
            $giaVe = $row["GiaVe"] ? $row["GiaVe"] : "Chưa có giá vé";

            echo '<a href="seat_selection.php?lichTrinhXeID=' . $row["LichTrinhXeID"] . '" class="list-group-item list-group-item-action">';
            // echo '<a href="details.php?lichTrinhXeID=' . $row["LichTrinhXeID"] . '" class="list-group-item list-group-item-action">';
            echo '<h5 class="mb-1">' . $row["DiemDi"] . ' - ' . $row["DiemDen"] . '</h5>';
            echo '<p class="mb-1">Thời gian khởi hành: ' . $row["NgayKhoiHanh"] . ' ' . $row["GioKhoiHanh"] . '</p>';
            echo '<p class="mb-1">Khoảng cách: ' . $row["KhoangCach"] . ' km</p>';
            echo '<small>' . $loaiXe . ' - ' . $giaVe . ' VND</small>'; // Hiển thị loại xe và giá vé
            echo '</a>';
        }
        echo '</div>';
    } else {
        echo '<p class="mt-4">Không tìm thấy chuyến đi nào.</p>';
    }
} else {
    echo '<p class="mt-4 text-danger">Vui lòng nhập đầy đủ thông tin.</p>';
}
?>
