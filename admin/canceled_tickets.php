<link rel="stylesheet" href="css_admin/admin_styles.css">
<script src="js_admin/admin_scripts.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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

// Xử lý thêm thông tin hủy vé
if (isset($_POST['add_canceled_ticket'])) {
    $codeVeXe = $_POST['codeVeXe'];
    $nguoiDungID = $_POST['nguoiDungID'];
    $choNgoiID = $_POST['choNgoiID'];
    $lichTrinhXeID = $_POST['lichTrinhXeID'];
    $thoiGianDatVe = $_POST['thoiGianDatVe'];
    $ngayHuy = $_POST['ngayHuy'];
    $lyDoHuy = $_POST['lyDoHuy'];
    $sql = "INSERT INTO LogHuyVe (CodeVeXe, NguoiDungID, ChoNgoiID, LichTrinhXeID, ThoiGianDatVe, NgayHuy, LyDoHuy) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiisss', $codeVeXe, $nguoiDungID, $choNgoiID, $lichTrinhXeID, $thoiGianDatVe, $ngayHuy, $lyDoHuy);
    $stmt->execute();
}

// Xử lý cập nhật thông tin hủy vé
if (isset($_POST['update_canceled_ticket'])) {
    $logHuyVeID = $_POST['logHuyVeID'];
    $codeVeXe = $_POST['codeVeXe'];
    $nguoiDungID = $_POST['nguoiDungID'];
    $choNgoiID = $_POST['choNgoiID'];
    $lichTrinhXeID = $_POST['lichTrinhXeID'];
    $thoiGianDatVe = $_POST['thoiGianDatVe'];
    $ngayHuy = $_POST['ngayHuy'];
    $lyDoHuy = $_POST['lyDoHuy'];
    $sql = "UPDATE LogHuyVe SET CodeVeXe = ?, NguoiDungID = ?, ChoNgoiID = ?, LichTrinhXeID = ?, ThoiGianDatVe = ?, NgayHuy = ?, LyDoHuy = ? WHERE LogHuyVeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiisssi', $codeVeXe, $nguoiDungID, $choNgoiID, $lichTrinhXeID, $thoiGianDatVe, $ngayHuy, $lyDoHuy, $logHuyVeID);
    $stmt->execute();
}

// Xử lý xóa thông tin hủy vé
if (isset($_GET['delete'])) {
    $logHuyVeID = $_GET['delete'];
    $sql = "DELETE FROM LogHuyVe WHERE LogHuyVeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $logHuyVeID);
    $stmt->execute();
}

// Xử lý tìm kiếm thông tin hủy vé
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM LogHuyVe WHERE LyDoHuy LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('s', $searchTerm);
} else {
    $sql = "SELECT * FROM LogHuyVe";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách người dùng
$sqlNguoiDung = "SELECT * FROM NguoiDung";
$resultNguoiDung = $conn->query($sqlNguoiDung);
$nguoiDungOptions = [];
while ($row = $resultNguoiDung->fetch_assoc()) {
    $nguoiDungOptions[] = $row;
}

// Lấy danh sách chỗ ngồi
$sqlChoNgoi = "SELECT * FROM ChoNgoi";
$resultChoNgoi = $conn->query($sqlChoNgoi);
$choNgoiOptions = [];
while ($row = $resultChoNgoi->fetch_assoc()) {
    $choNgoiOptions[] = $row;
}

// Lấy danh sách lịch trình xe
$sqlLichTrinhXe = "SELECT * FROM LichTrinhXe";
$resultLichTrinhXe = $conn->query($sqlLichTrinhXe);
$lichTrinhXeOptions = [];
while ($row = $resultLichTrinhXe->fetch_assoc()) {
    $lichTrinhXeOptions[] = $row;
}
?>
<style>
    /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 1400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.custom-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
    display: inline-block;
    margin: 5px 0;
    transition: background-color 0.3s, transform 0.3s;
    font-size: 14px;
}

.custom-button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.custom-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: box-shadow 0.3s;
}

.custom-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

.form-inline {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.form-inline input[type="text"] {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-inline input[type="text"]:focus {
    border-color: #007bff;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.custom-table th, .custom-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
    transition: background-color 0.3s;
}

.custom-table th {
    background-color: #f4f4f4;
    color: #333;
}

.custom-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.custom-table tr:hover {
    background-color: #f1f1f1;
}

.custom-table th {
    padding-top: 12px;
    padding-bottom: 12px;
    background-color: #007bff;
    color: white;
}

.small-button {
    padding: 5px 10px;
    font-size: 12px;
    margin: 2px;
}

</style>
<div class="main-content container mt-4">
    <h1>Quản Lý Thông Tin Hủy Vé</h1>

    <button id="showAddCanceledTicketForm" class="custom-button">Thêm thông tin hủy vé</button>

    <div id="addCanceledTicketForm" class="custom-card" style="display:none;">
        <form method="POST" action="canceled_tickets.php">
            <div class="form-group">
                <label for="codeVeXe">Code vé xe:</label>
                <input type="text" id="codeVeXe" name="codeVeXe" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nguoiDungID">Người dùng:</label>
                <select id="nguoiDungID" name="nguoiDungID" class="form-control" required>
                    <?php foreach ($nguoiDungOptions as $nguoiDung): ?>
                        <option value="<?= $nguoiDung['NguoiDungID'] ?>"><?= $nguoiDung['TenDangNhap'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="choNgoiID">Chỗ ngồi:</label>
                <select id="choNgoiID" name="choNgoiID" class="form-control" required>
                    <?php foreach ($choNgoiOptions as $choNgoi): ?>
                        <option value="<?= $choNgoi['ChoNgoiID'] ?>"><?= $choNgoi['SoGhe'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="lichTrinhXeID">Lịch trình xe:</label>
                <select id="lichTrinhXeID" name="lichTrinhXeID" class="form-control" required>
                    <?php foreach ($lichTrinhXeOptions as $lichTrinhXe): ?>
                        <option value="<?= $lichTrinhXe['LichTrinhXeID'] ?>"><?= $lichTrinhXe['NgayKhoiHanh'] ?> - <?= $lichTrinhXe['GioKhoiHanh'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="thoiGianDatVe">Thời gian đặt vé:</label>
                <input type="datetime-local" id="thoiGianDatVe" name="thoiGianDatVe" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ngayHuy">Ngày hủy:</label>
                <input type="datetime-local" id="ngayHuy" name="ngayHuy" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lyDoHuy">Lý do hủy:</label>
                <textarea id="lyDoHuy" name="lyDoHuy" class="form-control" required></textarea>
            </div>
            <button type="submit" name="add_canceled_ticket" class="custom-button">Thêm thông tin hủy vé</button>
        </form>
    </div>

    <form method="POST" action="canceled_tickets.php" class="form-inline">
        <input type="text" name="searchQuery" placeholder="Tìm kiếm lý do hủy vé" class="form-control" value="<?= $searchQuery ?>">
        <button type="submit" name="search" class="custom-button">Tìm kiếm</button>
    </form>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code vé xe</th>
                <th>Người dùng</th>
                <th>Chỗ ngồi</th>
                <th>Lịch trình xe</th>
                <th>Thời gian đặt vé</th>
                <th>Ngày hủy</th>
                <th>Lý do hủy</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['LogHuyVeID'] ?>">
                <td><?= $row['LogHuyVeID'] ?></td>
                <td class="editable" data-field="CodeVeXe"><?= $row['CodeVeXe'] ?></td>
                <td class="editable" data-field="NguoiDungID"><?= $nguoiDungOptions[array_search($row['NguoiDungID'], array_column($nguoiDungOptions, 'NguoiDungID'))]['TenDangNhap'] ?></td>
                <td class="editable" data-field="ChoNgoiID"><?= $choNgoiOptions[array_search($row['ChoNgoiID'], array_column($choNgoiOptions, 'ChoNgoiID'))]['SoGhe'] ?></td>
                <td class="editable" data-field="LichTrinhXeID"><?= $lichTrinhXeOptions[array_search($row['LichTrinhXeID'], array_column($lichTrinhXeOptions, 'LichTrinhXeID'))]['NgayKhoiHanh'] ?> - <?= $lichTrinhXeOptions[array_search($row['LichTrinhXeID'], array_column($lichTrinhXeOptions, 'LichTrinhXeID'))]['GioKhoiHanh'] ?></td>
                <td class="editable" data-field="ThoiGianDatVe"><?= date('Y-m-d\TH:i', strtotime($row['ThoiGianDatVe'])) ?></td>
                <td class="editable" data-field="NgayHuy"><?= date('Y-m-d\TH:i', strtotime($row['NgayHuy'])) ?></td>
                <td class="editable" data-field="LyDoHuy"><?= $row['LyDoHuy'] ?></td>
                <td>
                    <button class="edit-button small-button custom-button">Sửa</button>
                    <button class="save-button small-button custom-button" style="display:none;">Lưu</button>
                    <a href="canceled_tickets.php?delete=<?= $row['LogHuyVeID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showAddCanceledTicketForm').addEventListener('click', function() {
        var form = document.getElementById('addCanceledTicketForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    });

    $(document).ready(function() {
        $('.edit-button').on('click', function() {
            var row = $(this).closest('tr');
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).text();
                if (field === 'NguoiDungID') {
                    var select = $('<select class="form-control"></select>');
                    <?php foreach ($nguoiDungOptions as $nguoiDung): ?>
                        select.append('<option value="<?= $nguoiDung['NguoiDungID'] ?>"><?= $nguoiDung['TenDangNhap'] ?></option>');
                    <?php endforeach; ?>
                    select.val(value);
                    $(this).html(select);
                } else if (field === 'ChoNgoiID') {
                    var select = $('<select class="form-control"></select>');
                    <?php foreach ($choNgoiOptions as $choNgoi): ?>
                        select.append('<option value="<?= $choNgoi['ChoNgoiID'] ?>"><?= $choNgoi['SoGhe'] ?></option>');
                    <?php endforeach; ?>
                    select.val(value);
                    $(this).html(select);
                } else if (field === 'LichTrinhXeID') {
                    var select = $('<select class="form-control"></select>');
                    <?php foreach ($lichTrinhXeOptions as $lichTrinhXe): ?>
                        select.append('<option value="<?= $lichTrinhXe['LichTrinhXeID'] ?>"><?= $lichTrinhXe['NgayKhoiHanh'] ?> - <?= $lichTrinhXe['GioKhoiHanh'] ?></option>');
                    <?php endforeach; ?>
                    select.val(value);
                    $(this).html(select);
                } else {
                    var input = $('<input type="text" class="form-control">');
                    input.val(value);
                    $(this).html(input);
                }
            });
            row.find('.edit-button').hide();
            row.find('.save-button').show();
        });

        $('.save-button').on('click', function() {
            var row = $(this).closest('tr');
            var logHuyVeID = row.data('id');
            var data = {
                logHuyVeID: logHuyVeID
            };
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).find('input, select').val();
                data[field] = value;
                $(this).text(value);
            });

            $.post('canceled_tickets.php', {
                update_canceled_ticket: true,
                logHuyVeID: data.logHuyVeID,
                codeVeXe: data.CodeVeXe,
                nguoiDungID: data.NguoiDungID,
                choNgoiID: data.ChoNgoiID,
                lichTrinhXeID: data.LichTrinhXeID,
                thoiGianDatVe: data.ThoiGianDatVe,
                ngayHuy: data.NgayHuy,
                lyDoHuy: data.LyDoHuy
            }, function(response) {
                // Handle response if needed
            });

            row.find('.edit-button').show();
            row.find('.save-button').hide();
        });
    });
</script>

<?php
require 'includes_admin/admin_footer.php';
?>
