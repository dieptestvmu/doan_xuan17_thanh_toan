<link rel="stylesheet" href="css_admin/admin_styles.css">
<script src="js_admin/admin_scripts.js"></script>
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

// Xử lý thêm sao lưu
if (isset($_POST['add_backup'])) {
    $thoiGianSaoLuu = $_POST['thoiGianSaoLuu'];
    $duLieu = $_POST['duLieu'];
    $ghiChu = $_POST['ghiChu'];
    $nhanVienID = $_POST['nhanVienID'];
    $sql = "INSERT INTO SaoLuuDuLieu (ThoiGianSaoLuu, DuLieu, GhiChu, NhanVienID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $thoiGianSaoLuu, $duLieu, $ghiChu, $nhanVienID);
    $stmt->execute();
}

// Xử lý cập nhật sao lưu
if (isset($_POST['update_backup'])) {
    $saoLuuID = $_POST['saoLuuID'];
    $thoiGianSaoLuu = $_POST['thoiGianSaoLuu'];
    $duLieu = $_POST['duLieu'];
    $ghiChu = $_POST['ghiChu'];
    $nhanVienID = $_POST['nhanVienID'];
    $sql = "UPDATE SaoLuuDuLieu SET ThoiGianSaoLuu = ?, DuLieu = ?, GhiChu = ?, NhanVienID = ? WHERE SaoLuuID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $thoiGianSaoLuu, $duLieu, $ghiChu, $nhanVienID, $saoLuuID);
    $stmt->execute();
}

// Xử lý xóa sao lưu
if (isset($_GET['delete'])) {
    $saoLuuID = $_GET['delete'];
    $sql = "DELETE FROM SaoLuuDuLieu WHERE SaoLuuID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $saoLuuID);
    $stmt->execute();
}

// Xử lý phục hồi sao lưu
if (isset($_GET['restore'])) {
    $saoLuuID = $_GET['restore'];
    // Tìm kiếm dữ liệu sao lưu tương ứng
    $sql = "SELECT DuLieu FROM SaoLuuDuLieu WHERE SaoLuuID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $saoLuuID);
    $stmt->execute();
    $result = $stmt->get_result();
    $backup = $result->fetch_assoc();

    if ($backup) {
        // Khôi phục dữ liệu từ bản sao lưu
        $data = $backup['DuLieu'];
        $queries = explode(";", $data);
        foreach ($queries as $query) {
            if (trim($query) != "") {
                $conn->query($query);
            }
        }
        echo "Dữ liệu đã được khôi phục thành công.";
    } else {
        echo "Không tìm thấy bản sao lưu.";
    }
}

// Xử lý tìm kiếm sao lưu
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $sql = "SELECT * FROM SaoLuuDuLieu WHERE GhiChu LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param('s', $searchTerm);
} else {
    $sql = "SELECT * FROM SaoLuuDuLieu";
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

<div class="main-content container">
    <h1>Quản Lý Sao Lưu Dữ Liệu</h1>

    <div class="custom-card">
        <form method="POST" action="">
            <div class="form-group">
                <label for="thoiGianSaoLuu">Thời gian sao lưu:</label>
                <input type="datetime-local" id="thoiGianSaoLuu" name="thoiGianSaoLuu" required>
            </div>
            <div class="form-group">
                <label for="duLieu">Dữ liệu sao lưu:</label>
                <textarea id="duLieu" name="duLieu" required></textarea>
            </div>
            <div class="form-group">
                <label for="ghiChu">Ghi chú:</label>
                <input type="text" id="ghiChu" name="ghiChu">
            </div>
            <div class="form-group">
                <label for="nhanVienID">Nhân viên thực hiện:</label>
                <select id="nhanVienID" name="nhanVienID" required>
                    <?php foreach ($nhanVienOptions as $nhanVien): ?>
                        <option value="<?= $nhanVien['NhanVienID'] ?>"><?= $nhanVien['HoTen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="custom-button" type="submit" name="add_backup">Thêm sao lưu</button>
        </form>
    </div>

    <div class="custom-card">
        <form method="POST" action="" class="form-inline">
            <input type="text" name="searchQuery" placeholder="Tìm kiếm sao lưu" value="<?= $searchQuery ?>">
            <button class="custom-button" type="submit" name="search">Tìm kiếm</button>
        </form>
    </div>

    <div class="custom-card">
        <table class="custom-table">
            <tr>
                <th>ID</th>
                <th>Thời gian sao lưu</th>
                <th>Ghi chú</th>
                <th>Nhân viên thực hiện</th>
                <th>Hành động</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['SaoLuuID'] ?></td>
                <td><?= $row['ThoiGianSaoLuu'] ?></td>
                <td><?= $row['GhiChu'] ?></td>
                <td><?= $row['NhanVienID'] ?></td>
                <td>
                    <form method="POST" action="" style="display:inline-block;">
                        <input type="hidden" name="saoLuuID" value="<?= $row['SaoLuuID'] ?>">
                        <input type="datetime-local" name="thoiGianSaoLuu" value="<?= date('Y-m-d\TH:i', strtotime($row['ThoiGianSaoLuu'])) ?>" required>
                        <textarea name="duLieu" required><?= $row['DuLieu'] ?></textarea>
                        <input type="text" name="ghiChu" value="<?= $row['GhiChu'] ?>">
                        <select name="nhanVienID" required>
                            <?php foreach ($nhanVienOptions as $nhanVien): ?>
                                <option value="<?= $nhanVien['NhanVienID'] ?>" <?= $nhanVien['NhanVienID'] == $row['NhanVienID'] ? 'selected' : '' ?>><?= $nhanVien['HoTen'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="small-button custom-button" type="submit" name="update_backup">Sửa</button>
                    </form>
                    <a href="?delete=<?= $row['SaoLuuID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="small-button custom-button">Xóa</a>
                    <a href="?restore=<?= $row['SaoLuuID'] ?>" onclick="return confirm('Bạn có chắc chắn muốn phục hồi?')" class="small-button custom-button">Phục hồi</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php
require 'includes_admin/admin_footer.php';
?>
