<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $veXeID = $_POST['cancelTicketID'];
    $lyDoHuy = $_POST['reason'];
    $nguoiDungID = $_SESSION['userid'];
    $ngayHuy = date('Y-m-d H:i:s');

    $conn->begin_transaction();
    try {
        // Cập nhật trạng thái ghế ngồi
        $sql = "UPDATE ChoNgoi SET TrangThai = 'Trống' WHERE ChoNgoiID = (SELECT ChoNgoiID FROM VeXe WHERE VeXeID = ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement for ChoNgoi: " . $conn->error);
        }
        $stmt->bind_param('i', $veXeID);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement for ChoNgoi: " . $stmt->error);
        }

        // Xóa vé trong bảng VeXe
        $sql = "DELETE FROM VeXe WHERE VeXeID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement for VeXe: " . $conn->error);
        }
        $stmt->bind_param('i', $veXeID);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement for VeXe: " . $stmt->error);
        }

        $conn->commit();

        // Lấy danh sách vé mới sau khi hủy
        $sqlTickets = "SELECT VeXe.*, ChoNgoi.SoGhe, TuyenXe.DiemDi, TuyenXe.DiemDen, LichTrinhXe.NgayKhoiHanh, LichTrinhXe.GioKhoiHanh, LichTrinhXe.GiaVe
                       FROM VeXe
                       JOIN ChoNgoi ON VeXe.ChoNgoiID = ChoNgoi.ChoNgoiID
                       JOIN LichTrinhXe ON VeXe.LichTrinhXeID = LichTrinhXe.LichTrinhXeID
                       JOIN TuyenXe ON LichTrinhXe.TuyenXeID = TuyenXe.TuyenXeID
                       WHERE VeXe.KhachHangID IN (SELECT KhachHangID FROM KhachHang WHERE NguoiDungID = ?)";
        $stmtTickets = $conn->prepare($sqlTickets);
        $stmtTickets->bind_param('i', $nguoiDungID);
        $stmtTickets->execute();
        $resultTickets = $stmtTickets->get_result();

        $tickets = [];
        while ($ticket = $resultTickets->fetch_assoc()) {
            $tickets[] = $ticket;
        }

        echo json_encode(['status' => 'success', 'tickets' => $tickets]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
