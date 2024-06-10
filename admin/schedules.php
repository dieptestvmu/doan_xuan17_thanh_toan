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

// Xử lý thêm lịch trình xe
if (isset($_POST['add_schedule'])) {
    $xeID = $_POST['xeID'];
    $tuyenXeID = $_POST['tuyenXeID'];
    $ngayKhoiHanh = $_POST['ngayKhoiHanh'];
    $gioKhoiHanh = $_POST['gioKhoiHanh'];
    $trangThai = $_POST['trangThai'];
    $giaVe = $_POST['giaVe'];
    $sql = "INSERT INTO LichTrinhXe (XeID, TuyenXeID, NgayKhoiHanh, GioKhoiHanh, TrangThai, GiaVe) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iisssi', $xeID, $tuyenXeID, $ngayKhoiHanh, $gioKhoiHanh, $trangThai, $giaVe);
    $stmt->execute();
}

// Xử lý cập nhật lịch trình xe
if (isset($_POST['update_schedule'])) {
    $lichTrinhXeID = $_POST['lichTrinhXeID'];
    $xeID = $_POST['xeID'];
    $tuyenXeID = $_POST['tuyenXeID'];
    $ngayKhoiHanh = $_POST['ngayKhoiHanh'];
    $gioKhoiHanh = $_POST['gioKhoiHanh'];
    $trangThai = $_POST['trangThai'];
    $giaVe = $_POST['giaVe'];
    $sql = "UPDATE LichTrinhXe SET XeID = ?, TuyenXeID = ?, NgayKhoiHanh = ?, GioKhoiHanh = ?, TrangThai = ?, GiaVe = ? WHERE LichTrinhXeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iisssii', $xeID, $tuyenXeID, $ngayKhoiHanh, $gioKhoiHanh, $trangThai, $giaVe, $lichTrinhXeID);
    $stmt->execute();
}

// Xử lý xóa lịch trình xe
if (isset($_GET['delete'])) {
    $lichTrinhXeID = $_GET['delete'];
    $sql = "DELETE FROM LichTrinhXe WHERE LichTrinhXeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $lichTrinhXeID);
    $stmt->execute();
}

// Xử lý tìm kiếm lịch trình xe
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM LichTrinhXe WHERE NgayKhoiHanh LIKE ? OR GioKhoiHanh LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
} else {
    $sql = "SELECT * FROM LichTrinhXe";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách xe
$sqlXe = "SELECT * FROM Xe";
$resultXe = $conn->query($sqlXe);
$xeOptions = [];
while ($row = $resultXe->fetch_assoc()) {
    $xeOptions[] = $row;
}

// Lấy danh sách tuyến xe
$sqlTuyenXe = "SELECT * FROM TuyenXe";
$resultTuyenXe = $conn->query($sqlTuyenXe);
$tuyenXeOptions = [];
while ($row = $resultTuyenXe->fetch_assoc()) {
    $tuyenXeOptions[] = $row;
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
    <h1>Quản Lý Lịch Trình Xe</h1>

    <button id="showAddScheduleForm" class="custom-button">Thêm lịch trình</button>

    <div id="addScheduleForm" class="custom-card" style="display:none;">
        <form method="POST" action="schedules.php">
            <div class="form-group">
                <label for="xeID">Xe:</label>
                <select id="xeID" name="xeID" class="form-control" required>
                    <?php foreach ($xeOptions as $xe): ?>
                        <option value="<?= $xe['XeID'] ?>"><?= $xe['BienSo'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tuyenXeID">Tuyến xe:</label>
                <select id="tuyenXeID" name="tuyenXeID" class="form-control" required>
                    <?php foreach ($tuyenXeOptions as $tuyenXe): ?>
                        <option value="<?= $tuyenXe['TuyenXeID'] ?>"><?= $tuyenXe['DiemDi'] ?> - <?= $tuyenXe['DiemDen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ngayKhoiHanh">Ngày khởi hành:</label>
                <input type="date" id="ngayKhoiHanh" name="ngayKhoiHanh" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gioKhoiHanh">Giờ khởi hành:</label>
                <input type="time" id="gioKhoiHanh" name="gioKhoiHanh" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="trangThai">Trạng thái:</label>
                <input type="text" id="trangThai" name="trangThai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="giaVe">Giá vé:</label>
                <input type="number" id="giaVe" name="giaVe" class="form-control" required>
            </div>
            <button type="submit" name="add_schedule" class="custom-button">Thêm lịch trình</button>
        </form>
    </div>

    <form method="POST" action="schedules.php" class="form-inline">
        <input type="text" name="searchQuery" placeholder="Tìm kiếm lịch trình" class="form-control" value="<?= $searchQuery ?>">
        <button type="submit" name="search" class="custom-button">Tìm kiếm</button>
    </form>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Xe</th>
                <th>Tuyến xe</th>
                <th>Ngày khởi hành</th>
                <th>Giờ khởi hành</th>
                <th>Trạng thái</th>
                <th>Giá vé</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['LichTrinhXeID'] ?>">
                <td><?= $row['LichTrinhXeID'] ?></td>
                <td class="editable" data-field="XeID"><?= $xeOptions[array_search($row['XeID'], array_column($xeOptions, 'XeID'))]['BienSo'] ?></td>
                <td class="editable" data-field="TuyenXeID"><?= $tuyenXeOptions[array_search($row['TuyenXeID'], array_column($tuyenXeOptions, 'TuyenXeID'))]['DiemDi'] ?> - <?= $tuyenXeOptions[array_search($row['TuyenXeID'], array_column($tuyenXeOptions, 'TuyenXeID'))]['DiemDen'] ?></td>
                <td class="editable" data-field="NgayKhoiHanh"><?= $row['NgayKhoiHanh'] ?></td>
                <td class="editable" data-field="GioKhoiHanh"><?= $row['GioKhoiHanh'] ?></td>
                <td class="editable" data-field="TrangThai"><?= $row['TrangThai'] ?></td>
                <td class="editable" data-field="GiaVe"><?= $row['GiaVe'] ?></td>
                <td>
                    <button class="edit-button small-button custom-button">Sửa</button>
                    <button class="save-button small-button custom-button" style="display:none;">Lưu</button>
                    <a href="schedules.php?delete=<?= $row['LichTrinhXeID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showAddScheduleForm').addEventListener('click', function() {
        var form = document.getElementById('addScheduleForm');
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
                var input;
                if (field === 'XeID' || field === 'TuyenXeID') {
                    var select = $('<select class="form-control"></select>');
                    if (field === 'XeID') {
                        <?php foreach ($xeOptions as $xe): ?>
                            select.append('<option value="<?= $xe['XeID'] ?>"><?= $xe['BienSo'] ?></option>');
                        <?php endforeach; ?>
                    } else {
                        <?php foreach ($tuyenXeOptions as $tuyenXe): ?>
                            select.append('<option value="<?= $tuyenXe['TuyenXeID'] ?>"><?= $tuyenXe['DiemDi'] ?> - <?= $tuyenXe['DiemDen'] ?></option>');
                        <?php endforeach; ?>
                    }
                    select.val(value);
                    $(this).html(select);
                } else {
                    input = $('<input type="text" class="form-control">');
                    if (field === 'NgayKhoiHanh') {
                        input.attr('type', 'date');
                    }
                    if (field === 'GioKhoiHanh') {
                        input.attr('type', 'time');
                    }
                    if (field === 'GiaVe') {
                        input.attr('type', 'number');
                    }
                    input.val(value);
                    $(this).html(input);
                }
            });
            row.find('.edit-button').hide();
            row.find('.save-button').show();
        });

        $('.save-button').on('click', function() {
            var row = $(this).closest('tr');
            var lichTrinhXeID = row.data('id');
            var data = {
                lichTrinhXeID: lichTrinhXeID
            };
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).find('input, select').val();
                data[field] = value;
                $(this).text(value);
            });

            $.post('schedules.php', {
                update_schedule: true,
                lichTrinhXeID: data.lichTrinhXeID,
                xeID: data.XeID,
                tuyenXeID: data.TuyenXeID,
                ngayKhoiHanh: data.NgayKhoiHanh,
                gioKhoiHanh: data.GioKhoiHanh,
                trangThai: data.TrangThai,
                giaVe: data.GiaVe
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
