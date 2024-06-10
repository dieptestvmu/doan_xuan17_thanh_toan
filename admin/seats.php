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

// Xử lý thêm chỗ ngồi
if (isset($_POST['add_seat'])) {
    $xeID = $_POST['xeID'];
    $soGhe = $_POST['soGhe'];
    $trangThai = $_POST['trangThai'];
    $sql = "INSERT INTO ChoNgoi (XeID, SoGhe, TrangThai) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $xeID, $soGhe, $trangThai);
    $stmt->execute();
}

// Xử lý cập nhật chỗ ngồi
if (isset($_POST['update_seat'])) {
    $choNgoiID = $_POST['choNgoiID'];
    $xeID = $_POST['xeID'];
    $soGhe = $_POST['soGhe'];
    $trangThai = $_POST['trangThai'];
    $sql = "UPDATE ChoNgoi SET XeID = ?, SoGhe = ?, TrangThai = ? WHERE ChoNgoiID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issi', $xeID, $soGhe, $trangThai, $choNgoiID);
    $stmt->execute();
}

// Xử lý xóa chỗ ngồi
if (isset($_GET['delete'])) {
    $choNgoiID = $_GET['delete'];
    $sql = "DELETE FROM ChoNgoi WHERE ChoNgoiID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $choNgoiID);
    $stmt->execute();
}

// Xử lý tìm kiếm chỗ ngồi
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM ChoNgoi WHERE SoGhe LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('s', $searchTerm);
} else {
    $sql = "SELECT * FROM ChoNgoi";
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
    <h1>Quản Lý Chỗ Ngồi</h1>

    <button id="showAddSeatForm" class="custom-button">Thêm chỗ ngồi</button>

    <div id="addSeatForm" class="custom-card" style="display:none;">
        <form method="POST" action="seats.php">
            <div class="form-group">
                <label for="xeID">Xe:</label>
                <select id="xeID" name="xeID" class="form-control" required>
                    <?php foreach ($xeOptions as $xe): ?>
                        <option value="<?= $xe['XeID'] ?>"><?= $xe['BienSo'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="soGhe">Số ghế:</label>
                <input type="text" id="soGhe" name="soGhe" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="trangThai">Trạng thái:</label>
                <select id="trangThai" name="trangThai" class="form-control" required>
                    <option value="Trống">Trống</option>
                    <option value="Đã đặt">Đã đặt</option>
                    <option value="Không bán">Không bán</option>
                </select>
            </div>
            <button type="submit" name="add_seat" class="custom-button">Thêm chỗ ngồi</button>
        </form>
    </div>

    <form method="POST" action="seats.php" class="form-inline">
        <input type="text" name="searchQuery" placeholder="Tìm kiếm chỗ ngồi" class="form-control" value="<?= $searchQuery ?>">
        <button type="submit" name="search" class="custom-button">Tìm kiếm</button>
    </form>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Xe</th>
                <th>Số ghế</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['ChoNgoiID'] ?>">
                <td><?= $row['ChoNgoiID'] ?></td>
                <td class="editable" data-field="XeID"><?= $xeOptions[array_search($row['XeID'], array_column($xeOptions, 'XeID'))]['BienSo'] ?></td>
                <td class="editable" data-field="SoGhe"><?= $row['SoGhe'] ?></td>
                <td class="editable" data-field="TrangThai"><?= $row['TrangThai'] ?></td>
                <td>
                    <button class="edit-button small-button custom-button">Sửa</button>
                    <button class="save-button small-button custom-button" style="display:none;">Lưu</button>
                    <a href="seats.php?delete=<?= $row['ChoNgoiID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showAddSeatForm').addEventListener('click', function() {
        var form = document.getElementById('addSeatForm');
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
                if (field === 'XeID') {
                    var select = $('<select class="form-control"></select>');
                    <?php foreach ($xeOptions as $xe): ?>
                        select.append('<option value="<?= $xe['XeID'] ?>"><?= $xe['BienSo'] ?></option>');
                    <?php endforeach; ?>
                    select.val(value);
                    $(this).html(select);
                } else {
                    input = $('<input type="text" class="form-control">');
                    if (field === 'SoGhe') {
                        input.attr('type', 'text');
                    }
                    if (field === 'TrangThai') {
                        var selectStatus = $('<select class="form-control"></select>');
                        selectStatus.append('<option value="Trống">Trống</option>');
                        selectStatus.append('<option value="Đã đặt">Đã đặt</option>');
                        selectStatus.append('<option value="Không bán">Không bán</option>');
                        selectStatus.val(value);
                        $(this).html(selectStatus);
                        return;
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
            var choNgoiID = row.data('id');
            var data = {
                choNgoiID: choNgoiID
            };
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).find('input, select').val();
                data[field] = value;
                $(this).text(value);
            });

            $.post('seats.php', {
                update_seat: true,
                choNgoiID: data.choNgoiID,
                xeID: data.XeID,
                soGhe: data.SoGhe,
                trangThai: data.TrangThai
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
