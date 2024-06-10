<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $veXeIDs = $_POST['veXeIDs'];

    foreach ($veXeIDs as $veXeID) {
        $sqlUpdateThanhToan = "UPDATE thanhtoan SET TrangThaiThanhToan = 'Đã thanh toán' WHERE VeXeID = '$veXeID'";
        if ($conn->query($sqlUpdateThanhToan) !== TRUE) {
            echo json_encode(['success' => false]);
            exit();
        }
    }

    echo json_encode(['success' => true]);
}
?>
