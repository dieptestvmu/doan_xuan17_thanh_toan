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

// Xử lý thêm quyền hạn
if (isset($_POST['add_permission'])) {
    $tenQuyenHan = $_POST['tenQuyenHan'];
    $sql = "INSERT INTO QuyenHan (TenQuyenHan) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $tenQuyenHan);
    $stmt->execute();
}

// Xử lý cập nhật quyền hạn
if (isset($_POST['update_permission'])) {
    $quyenHanID = $_POST['quyenHanID'];
    $tenQuyenHan = $_POST['tenQuyenHan'];
    $sql = "UPDATE QuyenHan SET TenQuyenHan = ? WHERE QuyenHanID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $tenQuyenHan, $quyenHanID);
    $stmt->execute();
}

// Xử lý xóa quyền hạn
if (isset($_GET['delete'])) {
    $quyenHanID = $_GET['delete'];
    $sql = "DELETE FROM QuyenHan WHERE QuyenHanID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $quyenHanID);
    $stmt->execute();
}

// Xử lý tìm kiếm quyền hạn
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM QuyenHan WHERE TenQuyenHan LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('s', $searchTerm);
} else {
    $sql = "SELECT * FROM QuyenHan";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
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
    <h1>Quản Lý Quyền Hạn</h1>

    <button id="showAddPermissionForm" class="custom-button">Thêm quyền hạn</button>

    <div id="addPermissionForm" class="custom-card" style="display:none;">
        <form method="POST" action="permissions.php">
            <div class="form-group">
                <label for="tenQuyenHan">Tên quyền hạn:</label>
                <input type="text" id="tenQuyenHan" name="tenQuyenHan" class="form-control" required>
            </div>
            <button type="submit" name="add_permission" class="custom-button">Thêm quyền hạn</button>
        </form>
    </div>

    <form method="POST" action="permissions.php" class="form-inline">
        <input type="text" name="searchQuery" placeholder="Tìm kiếm quyền hạn" class="form-control" value="<?= $searchQuery ?>">
        <button type="submit" name="search" class="custom-button">Tìm kiếm</button>
    </form>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên quyền hạn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['QuyenHanID'] ?>">
                <td><?= $row['QuyenHanID'] ?></td>
                <td class="editable" data-field="TenQuyenHan"><?= $row['TenQuyenHan'] ?></td>
                <td>
                    <button class="edit-button small-button custom-button">Sửa</button>
                    <button class="save-button small-button custom-button" style="display:none;">Lưu</button>
                    <a href="permissions.php?delete=<?= $row['QuyenHanID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showAddPermissionForm').addEventListener('click', function() {
        var form = document.getElementById('addPermissionForm');
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
                var input = $('<input type="text" class="form-control">');
                input.val(value);
                $(this).html(input);
            });
            row.find('.edit-button').hide();
            row.find('.save-button').show();
        });

        $('.save-button').on('click', function() {
            var row = $(this).closest('tr');
            var quyenHanID = row.data('id');
            var data = {
                quyenHanID: quyenHanID
            };
            row.find('.editable').each(function() {
                var field = $(this).data('field');
                var value = $(this).find('input').val();
                data[field] = value;
                $(this).text(value);
            });

            $.post('permissions.php', {
                update_permission: true,
                quyenHanID: data.quyenHanID,
                tenQuyenHan: data.TenQuyenHan
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
