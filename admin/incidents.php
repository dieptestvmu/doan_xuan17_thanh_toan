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

// Xử lý thêm sự cố
if (isset($_POST['add_incident'])) {
    $moTaSuCo = $_POST['moTaSuCo'];
    $ngayXayRaSuCo = $_POST['ngayXayRaSuCo'];
    $trangThai = $_POST['trangThai'];
    $nhanVienID = $_POST['nhanVienID'];
    $sql = "INSERT INTO SuCo (MoTaSuCo, NgayXayRaSuCo, TrangThai, NhanVienID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $moTaSuCo, $ngayXayRaSuCo, $trangThai, $nhanVienID);
    $stmt->execute();
}

// Xử lý cập nhật sự cố
if (isset($_POST['update_incident'])) {
    $suCoID = $_POST['suCoID'];
    $moTaSuCo = $_POST['moTaSuCo'];
    $ngayXayRaSuCo = $_POST['ngayXayRaSuCo'];
    $trangThai = $_POST['trangThai'];
    $nhanVienID = $_POST['nhanVienID'];
    $sql = "UPDATE SuCo SET MoTaSuCo = ?, NgayXayRaSuCo = ?, TrangThai = ?, NhanVienID = ? WHERE SuCoID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $moTaSuCo, $ngayXayRaSuCo, $trangThai, $nhanVienID, $suCoID);
    $stmt->execute();
}

// Xử lý xóa sự cố
if (isset($_GET['delete'])) {
    $suCoID = $_GET['delete'];
    $sql = "DELETE FROM SuCo WHERE SuCoID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $suCoID);
    $stmt->execute();
}

// Xử lý tìm kiếm sự cố
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM SuCo WHERE MoTaSuCo LIKE ? OR TrangThai LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
} else {
    $sql = "SELECT * FROM SuCo";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách nhân viên
$sqlNhanVien = "SELECT * FROM NhanVien";
$resultNhanVien = $conn->query($sqlNhanVien);
$nhanVienOptions = [];
while ($row = $resultNhanVien->fetch_assoc()) {
    $nhanVienOptions[] = $row;
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
    <h1>Quản Lý Sự Cố</h1>

    <button id="showAddIncidentForm" class="custom-button">Thêm sự cố</button>

    <div id="addIncidentForm" class="custom-card" style="display:none;">
        <form method="POST" action="incidents.php">
            <div class="form-group">
                <label for="moTaSuCo">Mô tả sự cố:</label>
                <input type="text" id="moTaSuCo" name="moTaSuCo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ngayXayRaSuCo">Ngày xảy ra sự cố:</label>
                <input type="datetime-local" id="ngayXayRaSuCo" name="ngayXayRaSuCo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="trangThai">Trạng thái:</label>
                <input type="text" id="trangThai" name="trangThai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nhanVienID">Nhân viên phụ trách:</label>
                <select id="nhanVienID" name="nhanVienID" class="form-control" required>
                    <?php foreach ($nhanVienOptions as $nhanVien): ?>
                        <option value="<?= $nhanVien['NhanVienID'] ?>"><?= $nhanVien['HoTen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_incident" class="custom-button">Thêm sự cố</button>
        </form>
    </div>

    <form method="POST" action="incidents.php" class="form-inline">
        <input type="text" name="searchQuery" placeholder="Tìm kiếm sự cố" class="form-control" value="<?= $searchQuery ?>">
        <button type="submit" name="search" class="custom-button">Tìm kiếm</button>
    </form>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mô tả sự cố</th>
                <th>Ngày xảy ra sự cố</th>
                <th>Trạng thái</th>
                <th>Nhân viên phụ trách</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['SuCoID'] ?>">
                <td><?= $row['SuCoID'] ?></td>
                <td class="editable" data-field="MoTaSuCo"><?= $row['MoTaSuCo'] ?></td>
                <td class="editable" data-field="NgayXayRaSuCo"><?= $row['NgayXayRaSuCo'] ?></td>
                <td class="editable" data-field="TrangThai"><?= $row['TrangThai'] ?></td>
                <td class="editable" data-field="NhanVienID"><?= $nhanVienOptions[array_search($row['NhanVienID'], array_column($nhanVienOptions, 'NhanVienID'))]['HoTen'] ?></td>
                <td>
                    <button class="edit-button small-button custom-button">Sửa</button>
                    <button class="save-button small-button custom-button" style="display:none;">Lưu</button>
                    <a href="incidents.php?delete=<?= $row['SuCoID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showAddIncidentForm').addEventListener('click', function() {
        var form = document.getElementById('addIncidentForm');
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
                if (field === 'NhanVienID') {
                    var select = $('<select class="form-control"></select>');
                    <?php foreach ($nhanVienOptions as $nhanVien): ?>
                        select.append('<option value="<?= $nhanVien['NhanVienID'] ?>"><?= $nhanVien['HoTen'] ?></option>');
                    <?php endforeach; ?>
                    select.val(value);
                    $(this).html(select);
                } else {
                    input = $('<input type="text" class="form-control">');
                    if (field === 'NgayXayRaSuCo') {
                        input.attr('type', 'datetime-local');
                    } else {
                        input.attr('type', 'text');
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
            var suCoID = row.data('id');
            var data = {
                suCoID: suCoID
            };
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).find('input, select').val();
                data[field] = value;
                $(this).text(value);
            });

            $.post('incidents.php', {
                update_incident: true,
                suCoID: data.suCoID,
                moTaSuCo: data.MoTaSuCo,
                ngayXayRaSuCo: data.NgayXayRaSuCo,
                trangThai: data.TrangThai,
                nhanVienID: data.NhanVienID
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
