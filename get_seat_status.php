
<?php
include 'includes/db.php';

if (isset($_GET['xeID'])) {
    $xeID = $_GET['xeID'];

    $sql = "SELECT ChoNgoiID, TrangThai FROM ChoNgoi WHERE XeID = '$xeID'";
    $result = $conn->query($sql);

    $seatStatus = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $seatStatus[] = $row;
        }
    }
    echo json_encode($seatStatus);
}
?>
