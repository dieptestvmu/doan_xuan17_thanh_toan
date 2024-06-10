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

// Xử lý thêm thanh toán
if (isset($_POST['add_payment'])) {
    $veXeID = $_POST['veXeID'];
    $soTien = $_POST['soTien'];
    $phuongThucThanhToan = $_POST['phuongThucThanhToan'];
    $trangThaiThanhToan = $_POST['trangThaiThanhToan'];
    $ngayThanhToan = $_POST['ngayThanhToan'];
    $ghiChu = $_POST['ghiChu'];
    $sql = "INSERT INTO ThanhToan (VeXeID, SoTien, PhuongThucThanhToan, TrangThaiThanhToan, NgayThanhToan, GhiChu) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissss', $veXeID, $soTien, $phuongThucThanhToan, $trangThaiThanhToan, $ngayThanhToan, $ghiChu);
    $stmt->execute();
}

// Xử lý cập nhật thanh toán
if (isset($_POST['update_payment'])) {
    $thanhToanID = $_POST['thanhToanID'];
    $veXeID = $_POST['veXeID'];
    $soTien = $_POST['soTien'];
    $phuongThucThanhToan = $_POST['phuongThucThanhToan'];
    $trangThaiThanhToan = $_POST['trangThaiThanhToan'];
    $ngayThanhToan = $_POST['ngayThanhToan'];
    $ghiChu = $_POST['ghiChu'];
    $sql = "UPDATE ThanhToan SET VeXeID = ?, SoTien = ?, PhuongThucThanhToan = ?, TrangThaiThanhToan = ?, NgayThanhToan = ?, GhiChu = ? WHERE ThanhToanID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissssi', $veXeID, $soTien, $phuongThucThanhToan, $trangThaiThanhToan, $ngayThanhToan, $ghiChu, $thanhToanID);
    $stmt->execute();
}

// Xử lý xóa thanh toán
if (isset($_GET['delete'])) {
    $thanhToanID = $_GET['delete'];
    $sql = "DELETE FROM ThanhToan WHERE ThanhToanID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $thanhToanID);
    $stmt->execute();
}

// Xử lý tìm kiếm thanh toán
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM ThanhToan WHERE TrangThaiThanhToan LIKE ? OR PhuongThucThanhToan LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
} else {
    $sql = "SELECT * FROM ThanhToan";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách vé xe
$sqlVeXe = "SELECT * FROM VeXe";
$resultVeXe = $conn->query($sqlVeXe);
$veXeOptions = [];
while ($row = $resultVeXe->fetch_assoc()) {
    $veXeOptions[] = $row;
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

.form-group input, .form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-group input:focus, .form-group select:focus {
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
    <h1>Quản Lý Thanh Toán</h1>

    <button id="showAddPaymentForm" class="custom-button">Thêm thanh toán</button>

    <div id="addPaymentForm" class="custom-card" style="display:none;">
        <form method="POST" action="payments.php">
            <div class="form-group">
                <label for="veXeID">Vé xe:</label>
                <select id="veXeID" name="veXeID" class="form-control" required>
                    <?php foreach ($veXeOptions as $veXe): ?>
                        <option value="<?= $veXe['VeXeID'] ?>"><?= $veXe['VeXeID'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="soTien">Số tiền:</label>
                <input type="number" id="soTien" name="soTien" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phuongThucThanhToan">Phương thức thanh toán:</label>
                <input type="text" id="phuongThucThanhToan" name="phuongThucThanhToan" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="trangThaiThanhToan">Trạng thái thanh toán:</label>
                <input type="text" id="trangThaiThanhToan" name="trangThaiThanhToan" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ngayThanhToan">Ngày thanh toán:</label>
                <input type="datetime-local" id="ngayThanhToan" name="ngayThanhToan" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ghiChu">Ghi chú:</label>
                <input type="text" id="ghiChu" name="ghiChu" class="form-control">
            </div>
            <button type="submit" name="add_payment" class="custom-button">Thêm thanh toán</button>
        </form>
    </div>

    <form method="POST" action="payments.php" class="form-inline">
        <input type="text" name="searchQuery" placeholder="Tìm kiếm thanh toán" class="form-control" value="<?= $searchQuery ?>">
        <button type="submit" name="search" class="custom-button">Tìm kiếm</button>
    </form>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vé xe</th>
                <th>Số tiền</th>
                <th>Phương thức thanh toán</th>
                <th>Trạng thái thanh toán</th>
                <th>Ngày thanh toán</th>
                <th>Ghi chú</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['ThanhToanID'] ?>">
                <td><?= $row['ThanhToanID'] ?></td>
                <td class="editable" data-field="VeXeID"><?= $veXeOptions[array_search($row['VeXeID'], array_column($veXeOptions, 'VeXeID'))]['VeXeID'] ?></td>
                <td class="editable" data-field="SoTien"><?= $row['SoTien'] ?></td>
                <td class="editable" data-field="PhuongThucThanhToan"><?= $row['PhuongThucThanhToan'] ?></td>
                <td class="editable" data-field="TrangThaiThanhToan"><?= $row['TrangThaiThanhToan'] ?></td>
                <td class="editable" data-field="NgayThanhToan"><?= date('Y-m-d\TH:i', strtotime($row['NgayThanhToan'])) ?></td>
                <td class="editable" data-field="GhiChu"><?= $row['GhiChu'] ?></td>
                <td>
                    <button class="edit-button small-button custom-button">Sửa</button>
                    <button class="save-button small-button custom-button" style="display:none;">Lưu</button>
                    <a href="payments.php?delete=<?= $row['ThanhToanID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showAddPaymentForm').addEventListener('click', function() {
        var form = document.getElementById('addPaymentForm');
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
                if (field === 'VeXeID') {
                    var select = $('<select class="form-control"></select>');
                    <?php foreach ($veXeOptions as $veXe): ?>
                        select.append('<option value="<?= $veXe['VeXeID'] ?>"><?= $veXe['VeXeID'] ?></option>');
                    <?php endforeach; ?>
                    select.val(value);
                    $(this).html(select);
                } else if (field === 'NgayThanhToan') {
                    var input = $('<input type="datetime-local" class="form-control">');
                    input.val(value);
                    $(this).html(input);
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
            var thanhToanID = row.data('id');
            var data = {
                thanhToanID: thanhToanID
            };
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).find('input, select').val();
                data[field] = value;
                $(this).text(value);
            });

            $.post('payments.php', {
                update_payment: true,
                thanhToanID: data.thanhToanID,
                veXeID: data.VeXeID,
                soTien: data.SoTien,
                phuongThucThanhToan: data.PhuongThucThanhToan,
                trangThaiThanhToan: data.TrangThaiThanhToan,
                ngayThanhToan: data.NgayThanhToan,
                ghiChu: data.GhiChu
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
