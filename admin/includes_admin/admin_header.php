<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css_admin/admin_styles.css">
</head>
<body>
    <style>
        .logout-button {
            background-color: #4CAF50; /* Màu nền nút */
            border: none;
            color: white;
            padding: 0px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px; /* Bo góc cho nút */
            margin-left: 20px; /* Dịch nút sang trái */
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu khi hover */
            margin-right: 50px;
        }

        .logout-button:hover {
            background-color: #3e8e41; /* Màu nền khi hover */
        }

        .stile {
            /* margin-left: 30px; */
            margin-left: 580px;
            display: flex;
            justify-content: space-between;
        }

        .stile h1 {
            color:white;
            margin: 20px 0;;
        }
    </style>
    <!-- <header class="bg-primary text-white p-3"> -->
    <header>
        <!-- <div class="container d-flex justify-content-between align-items-center"> -->
        <div class="stile">
        
            <h1>Quản Trị Hệ Thống Đoàn Xuân</h1>
            <!-- <nav style="text-align: right;"> -->
                <!-- <a href="../logout.php" class="text-white">Đăng Xuất</a> -->
                <button onclick="window.location.href='../logout.php'" class="logout-button">Đăng Xuất</button>
      
            <!-- </nav> -->
        </div>
    </header>
    <div class="container mt-4">
