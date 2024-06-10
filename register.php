<?php
include 'includes/db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$email = isset($_POST['email']) ? $_POST['email'] : NULL;

$response = array('success' => false);

// Kiểm tra tên đăng nhập đã tồn tại
$sql_check = "SELECT * FROM nguoidung WHERE TenDangNhap = ?";
$stmt_check = $conn->prepare($sql_check);
if ($stmt_check === false) {
    $response['message'] = 'Lỗi chuẩn bị câu lệnh SQL kiểm tra người dùng.';
    echo json_encode($response);
    exit;
}

$stmt_check->bind_param('s', $username);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $response['message'] = 'Tên đăng nhập đã được sử dụng';
} else {
    // Bắt đầu giao dịch
    $conn->begin_transaction();

    try {
        // Thêm dữ liệu vào bảng NguoiDung
        $sql_insert_nguoidung = "INSERT INTO nguoidung (TenDangNhap, MatKhau, Email, QuyenHanID) VALUES (?, ?, ?, 2)";
        $stmt_insert_nguoidung = $conn->prepare($sql_insert_nguoidung);
        if ($stmt_insert_nguoidung === false) {
            throw new Exception('Lỗi chuẩn bị câu lệnh SQL chèn người dùng.');
        }

        $stmt_insert_nguoidung->bind_param('sss', $username, $password, $email);

        if (!$stmt_insert_nguoidung->execute()) {
            throw new Exception('Lỗi thực thi câu lệnh SQL chèn người dùng.');
        }

        $nguoidung_id = $stmt_insert_nguoidung->insert_id;

        // Thêm dữ liệu vào bảng KhachHang
        $sql_insert_khachhang = "INSERT INTO khachhang (HoTen, SoDienThoai, Email, NguoiDungID) VALUES (?, ?, ?, ?)";
        $stmt_insert_khachhang = $conn->prepare($sql_insert_khachhang);
        if ($stmt_insert_khachhang === false) {
            throw new Exception('Lỗi chuẩn bị câu lệnh SQL chèn khách hàng.');
        }

        $stmt_insert_khachhang->bind_param('sssi', $fullname, $phone, $email, $nguoidung_id);

        if (!$stmt_insert_khachhang->execute()) {
            throw new Exception('Lỗi thực thi câu lệnh SQL chèn khách hàng.');
        }

        // Hoàn tất giao dịch
        $conn->commit();
        $response['success'] = true;
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
