DROP TABLE IF EXISTS baocaothongke;

CREATE TABLE `baocaothongke` (
  `BaoCaoID` int(11) NOT NULL,
  `LoaiBaoCao` varchar(50) DEFAULT NULL,
  `NoiDungBaoCao` text DEFAULT NULL,
  `NgayTaoBaoCao` datetime DEFAULT NULL,
  `NhanVienID` int(11) DEFAULT NULL,
  `VeXeID` int(11) DEFAULT NULL,
  PRIMARY KEY (`BaoCaoID`),
  KEY `NhanVienID` (`NhanVienID`),
  KEY `baocaothongke_ibfk_2` (`VeXeID`),
  CONSTRAINT `baocaothongke_ibfk_1` FOREIGN KEY (`NhanVienID`) REFERENCES `nhanvien` (`NhanVienID`),
  CONSTRAINT `baocaothongke_ibfk_2` FOREIGN KEY (`VeXeID`) REFERENCES `vexe` (`VeXeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO baocaothongke VALUES("0","bc1","nd báo cáo","2024-06-07 23:17:00","3","");



DROP TABLE IF EXISTS chongoi;

CREATE TABLE `chongoi` (
  `ChoNgoiID` int(11) NOT NULL,
  `MaChoNgoi` varchar(20) NOT NULL,
  `XeID` int(11) DEFAULT NULL,
  `SoGhe` varchar(10) DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ChoNgoiID`),
  KEY `XeID` (`XeID`),
  CONSTRAINT `chongoi_ibfk_1` FOREIGN KEY (`XeID`) REFERENCES `xe` (`XeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO chongoi VALUES("1","","1","1","Đã đặt");
INSERT INTO chongoi VALUES("2","","1","2","Đã đặt");
INSERT INTO chongoi VALUES("3","","1","3","Đã đặt");
INSERT INTO chongoi VALUES("4","","1","4","Đã đặt");
INSERT INTO chongoi VALUES("5","","1","5","Đã đặt");
INSERT INTO chongoi VALUES("6","","1","6","Trống");
INSERT INTO chongoi VALUES("7","","1","7","Trống");
INSERT INTO chongoi VALUES("8","","1","8","Trống");
INSERT INTO chongoi VALUES("9","","1","9","Trống");
INSERT INTO chongoi VALUES("10","","1","10","Trống");
INSERT INTO chongoi VALUES("11","","1","11","Trống");
INSERT INTO chongoi VALUES("12","","1","12","Trống");
INSERT INTO chongoi VALUES("13","","1","13","Trống");
INSERT INTO chongoi VALUES("14","","1","14","Trống");
INSERT INTO chongoi VALUES("15","","1","15","Trống");
INSERT INTO chongoi VALUES("16","","1","16","Trống");
INSERT INTO chongoi VALUES("17","","1","17","Trống");
INSERT INTO chongoi VALUES("18","","1","18","Trống");
INSERT INTO chongoi VALUES("19","","1","19","Trống");
INSERT INTO chongoi VALUES("20","","1","20","Trống");
INSERT INTO chongoi VALUES("21","","1","21","Trống");
INSERT INTO chongoi VALUES("22","","1","22","Trống");
INSERT INTO chongoi VALUES("23","","1","23","Trống");
INSERT INTO chongoi VALUES("24","","1","24","Trống");
INSERT INTO chongoi VALUES("25","","1","25","Trống");
INSERT INTO chongoi VALUES("26","","1","26","Trống");
INSERT INTO chongoi VALUES("27","","1","27","Trống");
INSERT INTO chongoi VALUES("28","","1","28","Trống");
INSERT INTO chongoi VALUES("29","","2","1","Đã đặt");
INSERT INTO chongoi VALUES("30","","2","2","Đã đặt");
INSERT INTO chongoi VALUES("31","","2","3","Đã đặt");
INSERT INTO chongoi VALUES("32","","2","4","Đã đặt");
INSERT INTO chongoi VALUES("33","","2","5","Đã đặt");
INSERT INTO chongoi VALUES("34","","2","6","Đã đặt");
INSERT INTO chongoi VALUES("35","","2","7","Đã đặt");
INSERT INTO chongoi VALUES("36","","2","8","Đã đặt");
INSERT INTO chongoi VALUES("37","","2","9","Đã đặt");
INSERT INTO chongoi VALUES("38","","2","10","Trống");
INSERT INTO chongoi VALUES("39","","2","11","Trống");
INSERT INTO chongoi VALUES("40","","2","12","Đã đặt");
INSERT INTO chongoi VALUES("41","","2","13","Đã đặt");
INSERT INTO chongoi VALUES("42","","2","14","Đã đặt");
INSERT INTO chongoi VALUES("43","","2","15","Trống");
INSERT INTO chongoi VALUES("44","","2","16","Trống");
INSERT INTO chongoi VALUES("45","","2","17","Đã đặt");
INSERT INTO chongoi VALUES("46","","2","18","Trống");
INSERT INTO chongoi VALUES("47","","2","19","Trống");
INSERT INTO chongoi VALUES("48","","2","20","Trống");
INSERT INTO chongoi VALUES("49","","2","21","Trống");
INSERT INTO chongoi VALUES("50","","2","22","Trống");
INSERT INTO chongoi VALUES("51","","2","23","Đã đặt");
INSERT INTO chongoi VALUES("52","","2","24","Trống");
INSERT INTO chongoi VALUES("53","","2","25","Trống");
INSERT INTO chongoi VALUES("54","","2","26","Trống");
INSERT INTO chongoi VALUES("55","","2","27","Trống");
INSERT INTO chongoi VALUES("56","","2","28","Trống");
INSERT INTO chongoi VALUES("57","","2","29","Trống");
INSERT INTO chongoi VALUES("58","","2","30","Trống");
INSERT INTO chongoi VALUES("59","","2","31","Trống");
INSERT INTO chongoi VALUES("60","","2","32","Trống");
INSERT INTO chongoi VALUES("61","","2","33","Trống");
INSERT INTO chongoi VALUES("62","","2","34","Trống");
INSERT INTO chongoi VALUES("63","","2","35","Trống");
INSERT INTO chongoi VALUES("64","","2","36","Trống");
INSERT INTO chongoi VALUES("65","","2","37","Trống");
INSERT INTO chongoi VALUES("66","","2","38","Trống");
INSERT INTO chongoi VALUES("67","","2","39","Trống");
INSERT INTO chongoi VALUES("68","","2","40","Trống");
INSERT INTO chongoi VALUES("69","","2","41","Đã đặt");
INSERT INTO chongoi VALUES("70","","2","42","Đã đặt");
INSERT INTO chongoi VALUES("71","","2","43","Đã đặt");
INSERT INTO chongoi VALUES("72","","2","44","Đã đặt");
INSERT INTO chongoi VALUES("73","","2","45","Trống");



DROP TABLE IF EXISTS khachhang;

CREATE TABLE `khachhang` (
  `KhachHangID` int(11) NOT NULL AUTO_INCREMENT,
  `MaKhachHang` varchar(20) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `SoDienThoai` varchar(20) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `NguoiDungID` int(11) DEFAULT NULL,
  PRIMARY KEY (`KhachHangID`),
  KEY `NguoiDungID` (`NguoiDungID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO khachhang VALUES("1","","điệp đẹp trai","02454543","","","0");
INSERT INTO khachhang VALUES("2","","fedfwfewfew","23423525","","","");
INSERT INTO khachhang VALUES("3","","ừdvvw","43566546546","","","");
INSERT INTO khachhang VALUES("4","","ừdvvw","43566546546","","","");
INSERT INTO khachhang VALUES("5","","chon1","1234","","","");
INSERT INTO khachhang VALUES("6","","kh chọn ghế 3 và 4","52","","","");
INSERT INTO khachhang VALUES("7","","","","","","0");
INSERT INTO khachhang VALUES("8","","dky10","101010","dky10@gmail.com","","4");
INSERT INTO khachhang VALUES("9","","kh9","","","","5");
INSERT INTO khachhang VALUES("26","","dky10","101010","","","4");
INSERT INTO khachhang VALUES("29","","diep","43250","","","15");
INSERT INTO khachhang VALUES("30","","diep","43250","","","16");
INSERT INTO khachhang VALUES("31","","ab","45","","","17");
INSERT INTO khachhang VALUES("32","","tên của dky11 1","123","","","19");
INSERT INTO khachhang VALUES("33","","tên của dky11 1","123","","","18");
INSERT INTO khachhang VALUES("34","","họ tên dky12 1","12","","","20");
INSERT INTO khachhang VALUES("35","","Phạm Văn Điệp","0123","","","21");
INSERT INTO khachhang VALUES("36","","dp2 họ tên mk là 1 sửa thông tin","123","","","22");
INSERT INTO khachhang VALUES("37","","oke2 mk 1","123","","","23");
INSERT INTO khachhang VALUES("38","","abc1 1","12345","mial@dc","","24");
INSERT INTO khachhang VALUES("39","","abc1 1","123","","","25");
INSERT INTO khachhang VALUES("40","","abcd 1","123","","","27");
INSERT INTO khachhang VALUES("41","","abcd 1","123","","","26");



DROP TABLE IF EXISTS khuyenmai;

CREATE TABLE `khuyenmai` (
  `KhuyenMaiID` int(11) NOT NULL,
  `MaKhuyenMai` varchar(20) DEFAULT NULL,
  `TyLeGiam` int(11) DEFAULT NULL,
  `NgayBatDau` datetime DEFAULT NULL,
  `NgayKetThuc` datetime DEFAULT NULL,
  `NhanVienID` int(11) DEFAULT NULL,
  PRIMARY KEY (`KhuyenMaiID`),
  KEY `NhanVienID` (`NhanVienID`),
  CONSTRAINT `khuyenmai_ibfk_1` FOREIGN KEY (`NhanVienID`) REFERENCES `nhanvien` (`NhanVienID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO khuyenmai VALUES("0","PROMO30","3","2024-06-07 00:00:00","2024-06-14 00:00:00","4");
INSERT INTO khuyenmai VALUES("1","PROMO10","10","2024-06-01 00:00:00","2024-06-30 23:59:59","1");
INSERT INTO khuyenmai VALUES("2","PROMO20","20","2024-06-01 00:00:00","2024-06-30 23:59:59","2");



DROP TABLE IF EXISTS lichtrinhxe;

CREATE TABLE `lichtrinhxe` (
  `LichTrinhXeID` int(11) NOT NULL,
  `MaLichTrinhXe` int(11) NOT NULL,
  `XeID` int(11) DEFAULT NULL,
  `TuyenXeID` int(11) DEFAULT NULL,
  `NgayKhoiHanh` date DEFAULT NULL,
  `GioKhoiHanh` time NOT NULL,
  `TrangThai` varchar(50) NOT NULL,
  `GiaVe` int(11) DEFAULT NULL,
  PRIMARY KEY (`LichTrinhXeID`),
  KEY `FK_XeID` (`XeID`),
  KEY `FK_TuyenXeID` (`TuyenXeID`),
  CONSTRAINT `FK_TuyenXeID` FOREIGN KEY (`TuyenXeID`) REFERENCES `tuyenxe` (`TuyenXeID`),
  CONSTRAINT `FK_XeID` FOREIGN KEY (`XeID`) REFERENCES `xe` (`XeID`),
  CONSTRAINT `lichtrinhxe_ibfk_1` FOREIGN KEY (`XeID`) REFERENCES `xe` (`XeID`),
  CONSTRAINT `lichtrinhxe_ibfk_2` FOREIGN KEY (`TuyenXeID`) REFERENCES `tuyenxe` (`TuyenXeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO lichtrinhxe VALUES("1","0","1","1","2024-06-10","08:00:00","Đang chạy","150000");
INSERT INTO lichtrinhxe VALUES("2","0","2","2","2024-06-11","09:00:00","Đang chạy","130000");
INSERT INTO lichtrinhxe VALUES("3","0","3","3","2024-06-12","10:00:00","Đang chạy","100000");
INSERT INTO lichtrinhxe VALUES("4","0","1","4","2024-06-13","11:00:00","Đang chạy","150000");
INSERT INTO lichtrinhxe VALUES("5","0","2","5","2024-06-14","12:00:00","Đang chạy","130000");
INSERT INTO lichtrinhxe VALUES("6","0","3","6","2024-06-15","13:00:00","Đang chạy","100000");
INSERT INTO lichtrinhxe VALUES("7","0","1","7","2024-06-16","14:00:00","Đang chạy","150000");
INSERT INTO lichtrinhxe VALUES("8","0","2","8","2024-06-17","15:00:00","Đang chạy","130000");
INSERT INTO lichtrinhxe VALUES("9","0","3","9","2024-06-18","16:00:00","Đang chạy","100000");
INSERT INTO lichtrinhxe VALUES("10","0","1","10","2024-06-19","17:00:00","Đang chạy","150000");
INSERT INTO lichtrinhxe VALUES("11","0","2","11","2024-06-20","18:00:00","Đang chạy","130000");
INSERT INTO lichtrinhxe VALUES("12","0","3","12","2024-06-21","19:00:00","Đang chạy","100000");
INSERT INTO lichtrinhxe VALUES("13","0","1","13","2024-06-22","20:00:00","Đang chạy","150000");
INSERT INTO lichtrinhxe VALUES("14","0","2","14","2024-06-23","21:00:00","Đang chạy","130000");
INSERT INTO lichtrinhxe VALUES("15","0","3","15","2024-06-24","22:00:00","Đang chạy","100000");
INSERT INTO lichtrinhxe VALUES("16","0","1","16","2024-06-25","23:00:00","Đang chạy","150000");



DROP TABLE IF EXISTS loghuyve;

CREATE TABLE `loghuyve` (
  `LogHuyVeID` int(11) NOT NULL AUTO_INCREMENT,
  `NguoiDungID` int(11) DEFAULT NULL,
  `NgayHuy` datetime DEFAULT NULL,
  `LyDoHuy` text DEFAULT NULL,
  `CodeVeXe` varchar(5) DEFAULT NULL,
  `ChoNgoiID` int(11) DEFAULT NULL,
  `LichTrinhXeID` int(11) DEFAULT NULL,
  `ThoiGianDatVe` datetime DEFAULT NULL,
  PRIMARY KEY (`LogHuyVeID`),
  KEY `NguoiDungID` (`NguoiDungID`),
  KEY `ChoNgoiID` (`ChoNgoiID`),
  KEY `LichTrinhXeID` (`LichTrinhXeID`),
  CONSTRAINT `loghuyve_ibfk_1` FOREIGN KEY (`ChoNgoiID`) REFERENCES `chongoi` (`ChoNgoiID`),
  CONSTRAINT `loghuyve_ibfk_2` FOREIGN KEY (`LichTrinhXeID`) REFERENCES `lichtrinhxe` (`LichTrinhXeID`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO loghuyve VALUES("1","15","2024-06-06 15:18:24","Tự động xóa","","27","1","2024-06-06 08:44:14");
INSERT INTO loghuyve VALUES("2","11","2024-06-06 15:21:11","Tự động xóa","","2","1","2024-06-06 07:11:55");
INSERT INTO loghuyve VALUES("3","12","2024-06-06 15:31:58","Tự động xóa","","3","1","2024-06-06 07:22:10");
INSERT INTO loghuyve VALUES("4","16","2024-06-06 15:36:56","Tự động xóa","RVQ12","2","1","2024-06-06 10:33:02");
INSERT INTO loghuyve VALUES("5","10","2024-06-07 10:37:04","Tự động xóa","","28","1","2024-06-06 05:51:54");
INSERT INTO loghuyve VALUES("6","22","2024-06-08 16:10:33","Tự động xóa","AFJ4O","26","1","2024-06-07 10:36:32");
INSERT INTO loghuyve VALUES("7","4","2024-06-08 17:58:33","Tự động xóa","","","2","2024-06-08 17:03:42");
INSERT INTO loghuyve VALUES("8","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:14:10");
INSERT INTO loghuyve VALUES("9","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:15:01");
INSERT INTO loghuyve VALUES("10","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:15:14");
INSERT INTO loghuyve VALUES("11","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:20:44");
INSERT INTO loghuyve VALUES("12","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:24:22");
INSERT INTO loghuyve VALUES("13","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:28:33");
INSERT INTO loghuyve VALUES("14","4","2024-06-08 17:58:34","Tự động xóa","","","2","2024-06-08 17:30:14");
INSERT INTO loghuyve VALUES("15","8","2024-06-08 20:41:17","Tự động xóa","eef48","50","2","2024-06-08 20:39:55");
INSERT INTO loghuyve VALUES("16","3","2024-06-08 20:42:27","Tự động xóa","","5","1","2024-06-05 20:55:10");
INSERT INTO loghuyve VALUES("17","4","2024-06-08 20:42:27","Tự động xóa","","3","1","2024-06-06 02:01:44");
INSERT INTO loghuyve VALUES("18","4","2024-06-08 20:42:27","Tự động xóa","","4","1","2024-06-06 02:01:44");
INSERT INTO loghuyve VALUES("19","7","2024-06-08 20:42:27","Tự động xóa","","10","1","2024-06-06 10:38:02");
INSERT INTO loghuyve VALUES("20","13","2024-06-08 20:42:27","Tự động xóa","","6","1","2024-06-06 07:25:10");
INSERT INTO loghuyve VALUES("21","17","2024-06-08 20:42:27","Tự động xóa","BUSHE","2","1","2024-06-06 10:37:47");
INSERT INTO loghuyve VALUES("22","19","2024-06-08 20:42:27","Tự động xóa","XHBWD","7","1","2024-06-06 10:40:58");
INSERT INTO loghuyve VALUES("24","2","2024-06-08 20:43:07","Tự động xóa","","1","1","");
INSERT INTO loghuyve VALUES("25","5","2024-06-08 20:43:07","Tự động xóa","","1","1","2024-06-06 08:53:51");
INSERT INTO loghuyve VALUES("26","6","2024-06-08 20:43:07","Tự động xóa","","4","1","2024-06-06 08:56:08");
INSERT INTO loghuyve VALUES("27","6","2024-06-08 20:43:07","Tự động xóa","","5","1","2024-06-06 08:56:08");
INSERT INTO loghuyve VALUES("28","18","2024-06-08 20:43:07","Tự động xóa","EKGSI","3","1","2024-06-06 10:38:29");
INSERT INTO loghuyve VALUES("29","21","2024-06-08 20:43:07","Tự động xóa","96BED","29","2","2024-06-06 19:10:58");
INSERT INTO loghuyve VALUES("30","4","2024-06-08 20:43:07","Tự động xóa","","","2","2024-06-08 17:57:20");
INSERT INTO loghuyve VALUES("31","24","2024-06-08 20:43:07","Tự động xóa","G7VR9","32","2","2024-06-08 20:23:14");
INSERT INTO loghuyve VALUES("32","8","2024-06-08 20:43:07","Tự động xóa","c9f7c","48","2","2024-06-08 20:30:49");
INSERT INTO loghuyve VALUES("33","8","2024-06-08 20:43:07","Tự động xóa","8c25c","48","2","2024-06-08 20:30:51");
INSERT INTO loghuyve VALUES("34","8","2024-06-08 20:43:07","Tự động xóa","27584","48","2","2024-06-08 20:30:53");
INSERT INTO loghuyve VALUES("35","8","2024-06-08 20:43:07","Tự động xóa","33e59","49","2","2024-06-08 20:37:33");
INSERT INTO loghuyve VALUES("36","4","2024-06-08 23:27:10","Tự động xóa","L82TC","40","2","2024-06-08 23:15:41");



DROP TABLE IF EXISTS nguoidung;

CREATE TABLE `nguoidung` (
  `NguoiDungID` int(11) NOT NULL AUTO_INCREMENT,
  `TenDangNhap` varchar(50) DEFAULT NULL,
  `MatKhau` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `QuyenHanID` int(11) DEFAULT NULL,
  PRIMARY KEY (`NguoiDungID`),
  KEY `QuyenHanID` (`QuyenHanID`),
  CONSTRAINT `nguoidung_ibfk_1` FOREIGN KEY (`QuyenHanID`) REFERENCES `quyenhan` (`QuyenHanID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO nguoidung VALUES("1","admin","admin123","admin@doanxuan.com","1");
INSERT INTO nguoidung VALUES("2","user1","user123","user1@doanxuan.com","2");
INSERT INTO nguoidung VALUES("3","emp1","emp123","emp1@doanxuan.com","3");
INSERT INTO nguoidung VALUES("4","dky10","1","dky10@gmail.com","2");
INSERT INTO nguoidung VALUES("5","kh9","1","","2");
INSERT INTO nguoidung VALUES("6","dk12","1","","2");
INSERT INTO nguoidung VALUES("7","a","a","a@gmail.com","2");
INSERT INTO nguoidung VALUES("8","a","a","a@gmail.com","2");
INSERT INTO nguoidung VALUES("9","d","d","d@gm","2");
INSERT INTO nguoidung VALUES("10","d","d","d@gm","2");
INSERT INTO nguoidung VALUES("11","1","1","1@g","1");
INSERT INTO nguoidung VALUES("13","tk2","2","tk2@2","2");
INSERT INTO nguoidung VALUES("14","tk2","2","tk2@2","2");
INSERT INTO nguoidung VALUES("15","abc","abc","","2");
INSERT INTO nguoidung VALUES("16","abc","abc","","2");
INSERT INTO nguoidung VALUES("17","45","45","","3");
INSERT INTO nguoidung VALUES("18","dky11","1","","2");
INSERT INTO nguoidung VALUES("19","dky11","1","","2");
INSERT INTO nguoidung VALUES("20","dky12","1","","2");
INSERT INTO nguoidung VALUES("21","diep12h41p11062024","1","","2");
INSERT INTO nguoidung VALUES("22","dp2","2","","2");
INSERT INTO nguoidung VALUES("23","oke2","1","","2");
INSERT INTO nguoidung VALUES("24","abc1","1","mial@dc","2");
INSERT INTO nguoidung VALUES("25","abc1","1","","2");
INSERT INTO nguoidung VALUES("26","abcd","1","","2");
INSERT INTO nguoidung VALUES("27","abcd","1","","2");



DROP TABLE IF EXISTS nhanvien;

CREATE TABLE `nhanvien` (
  `NhanVienID` int(11) NOT NULL,
  `MaNhanVien` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `SoDienThoai` varchar(20) DEFAULT NULL,
  `VaiTro` varchar(50) DEFAULT NULL,
  `NguoiDungID` int(11) DEFAULT NULL,
  PRIMARY KEY (`NhanVienID`),
  KEY `NguoiDungID` (`NguoiDungID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO nhanvien VALUES("1","0","Nguyen Van A","0909123456","Nhân viên bán vé","3");
INSERT INTO nhanvien VALUES("2","0","Tran Van B","0919123456","Nhân viên lái xe","3");
INSERT INTO nhanvien VALUES("3","0","Le Thi C","0929123456","Nhân viên kỹ thuật","3");
INSERT INTO nhanvien VALUES("4","0","Pham Van D","0939123456","Nhân viên chăm sóc khách hàng","3");
INSERT INTO nhanvien VALUES("5","0","Hoang Van E","0949123456","Quản lý","3");



DROP TABLE IF EXISTS quyenhan;

CREATE TABLE `quyenhan` (
  `QuyenHanID` int(11) NOT NULL,
  `TenQuyenHan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`QuyenHanID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO quyenhan VALUES("1","Admin");
INSERT INTO quyenhan VALUES("2","User");
INSERT INTO quyenhan VALUES("3","Employee");



DROP TABLE IF EXISTS saoluudulieu;

CREATE TABLE `saoluudulieu` (
  `SaoLuuID` int(11) NOT NULL,
  `ThoiGianSaoLuu` datetime DEFAULT NULL,
  `DuLieu` longtext DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `NhanVienID` int(11) DEFAULT NULL,
  PRIMARY KEY (`SaoLuuID`),
  KEY `NhanVienID` (`NhanVienID`),
  CONSTRAINT `saoluudulieu_ibfk_1` FOREIGN KEY (`NhanVienID`) REFERENCES `nhanvien` (`NhanVienID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO saoluudulieu VALUES("1","2024-06-01 00:00:00","Dữ liệu sao lưu","Sao lưu định kỳ hàng ngày","1");
INSERT INTO saoluudulieu VALUES("2","2024-06-02 00:00:00","Dữ liệu sao lưu","Sao lưu trước bảo trì hệ thống","1");



DROP TABLE IF EXISTS suco;

CREATE TABLE `suco` (
  `SuCoID` int(11) NOT NULL,
  `MoTaSuCo` text DEFAULT NULL,
  `NgayXayRaSuCo` datetime DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  `NhanVienID` int(11) DEFAULT NULL,
  PRIMARY KEY (`SuCoID`),
  KEY `NhanVienID` (`NhanVienID`),
  CONSTRAINT `suco_ibfk_1` FOREIGN KEY (`NhanVienID`) REFERENCES `nhanvien` (`NhanVienID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO suco VALUES("1","Xe bị hỏng động cơ","2024-06-04 09:00:00","Đang xử lý","2");
INSERT INTO suco VALUES("2","Khách hàng phàn nàn về dịch vụ","2024-06-03 10:00:00","Đã xử lý","4");



DROP TABLE IF EXISTS thanhtoan;

CREATE TABLE `thanhtoan` (
  `ThanhToanID` int(11) NOT NULL AUTO_INCREMENT,
  `MaThanhToan` int(11) NOT NULL,
  `VeXeID` int(11) DEFAULT NULL,
  `SoTien` int(11) DEFAULT NULL,
  `PhuongThucThanhToan` varchar(50) DEFAULT NULL,
  `TrangThaiThanhToan` varchar(50) DEFAULT NULL,
  `NgayThanhToan` datetime DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ThanhToanID`),
  KEY `thanhtoan_ibfk_1` (`VeXeID`),
  CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`VeXeID`) REFERENCES `vexe` (`VeXeID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO thanhtoan VALUES("73","0","216","130000","Online","Đã thanh toán","2024-06-09 22:17:23","");
INSERT INTO thanhtoan VALUES("74","0","217","100000","Chuyển khoản","Đã thanh toán","2024-05-21 00:00:00","");
INSERT INTO thanhtoan VALUES("75","0","218","100000","Chuyển khoản","Đã thanh toán","2024-06-22 00:00:00","");
INSERT INTO thanhtoan VALUES("76","0","219","100000","Chuyển khoản","Đã thanh toán","2024-07-23 00:00:00","");
INSERT INTO thanhtoan VALUES("77","0","220","100000","Chuyển khoản","Đã thanh toán","2024-08-24 00:00:00","");
INSERT INTO thanhtoan VALUES("78","0","221","100000","Chuyển khoản","Đã thanh toán","2024-09-25 00:00:00","");
INSERT INTO thanhtoan VALUES("79","0","223","260000","Online","Đã thanh toán","2024-06-10 14:27:53","");
INSERT INTO thanhtoan VALUES("80","0","229","130000","Online","Đang chờ","2024-06-10 15:37:23","");
INSERT INTO thanhtoan VALUES("81","0","231","390000","thẻ tín dụng","thành công","2024-06-10 15:58:33","");
INSERT INTO thanhtoan VALUES("82","0","232","390000","thẻ tín dụng","thành công","2024-06-10 15:58:33","");
INSERT INTO thanhtoan VALUES("83","0","233","390000","thẻ tín dụng","thành công","2024-06-10 15:58:33","");
INSERT INTO thanhtoan VALUES("84","0","234","130000","Online","Đang chờ","2024-06-10 16:08:29","");
INSERT INTO thanhtoan VALUES("85","0","235","130000","Online","Đã thanh toán","2024-06-10 16:09:18","");
INSERT INTO thanhtoan VALUES("86","0","242","130000","Online","Đã thanh toán","2024-06-11 00:23:10","");
INSERT INTO thanhtoan VALUES("87","0","245","130000","Online","Đang chờ","2024-06-11 00:46:51","");
INSERT INTO thanhtoan VALUES("88","0","246","130000","Online","Đang chờ","2024-06-11 00:47:54","");
INSERT INTO thanhtoan VALUES("95","0","286","130000","Online","Đang chờ","2024-06-11 02:18:46","");
INSERT INTO thanhtoan VALUES("96","0","287","130000","Online","Đã thanh toán","2024-06-11 02:28:10","");
INSERT INTO thanhtoan VALUES("97","0","288","130000","Online","Đang chờ","2024-06-11 02:40:02","");
INSERT INTO thanhtoan VALUES("98","0","290","260000","Online","Đang chờ","2024-06-11 02:46:39","");
INSERT INTO thanhtoan VALUES("99","0","291","130000","Online","Đã thanh toán","2024-06-11 03:27:28","");



DROP TABLE IF EXISTS tuyenxe;

CREATE TABLE `tuyenxe` (
  `TuyenXeID` int(11) NOT NULL,
  `MaTuyenXe` int(11) NOT NULL,
  `DiemDi` varchar(100) DEFAULT NULL,
  `DiemDen` varchar(100) DEFAULT NULL,
  `KhoangCach` int(11) DEFAULT NULL,
  `ThoiGianDi` time DEFAULT NULL,
  PRIMARY KEY (`TuyenXeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tuyenxe VALUES("1","0","Hanoi","Haiphong","100","02:00:00");
INSERT INTO tuyenxe VALUES("2","0","Bến xe Gia Lâm","Kiến An","100","02:00:00");
INSERT INTO tuyenxe VALUES("3","0","Bến xe Gia Lâm","Quý Cao","100","02:00:00");
INSERT INTO tuyenxe VALUES("4","0","Bến xe Gia Lâm","Vĩnh Bảo","100","02:00:00");
INSERT INTO tuyenxe VALUES("5","0","Bến xe Yên Nghĩa","Bến xe Niệm Nghĩa","100","02:00:00");
INSERT INTO tuyenxe VALUES("6","0","Bến xe Yên Nghĩa","Kiến An","100","02:00:00");
INSERT INTO tuyenxe VALUES("7","0","Bến xe Yên Nghĩa","Quý Cao","100","02:00:00");
INSERT INTO tuyenxe VALUES("8","0","Bến xe Yên Nghĩa","Vĩnh Bảo","100","02:00:00");
INSERT INTO tuyenxe VALUES("9","0","Bến xe Niệm Nghĩa","Bến xe Gia Lâm","100","02:00:00");
INSERT INTO tuyenxe VALUES("10","0","Bến xe Niệm Nghĩa","Bến xe Yên Nghĩa","100","02:00:00");
INSERT INTO tuyenxe VALUES("11","0","Bến xe Kiến An","Bến xe Gia Lâm","100","02:00:00");
INSERT INTO tuyenxe VALUES("12","0","Bến xe Kiến An","Bến xe Yên Nghĩa","100","02:00:00");
INSERT INTO tuyenxe VALUES("13","0","Bến xe Quý Cao","Bến xe Gia Lâm","100","02:00:00");
INSERT INTO tuyenxe VALUES("14","0","Bến xe Quý Cao","Bến xe Yên Nghĩa","100","02:00:00");
INSERT INTO tuyenxe VALUES("15","0","Bến xe Vĩnh Bảo","Bến xe Gia Lâm","100","02:00:00");
INSERT INTO tuyenxe VALUES("16","0","Bến xe Vĩnh Bảo","Bến xe Yên Nghĩa","100","02:00:00");



DROP TABLE IF EXISTS vexe;

CREATE TABLE `vexe` (
  `VeXeID` int(11) NOT NULL AUTO_INCREMENT,
  `NhanVienID` int(11) DEFAULT NULL,
  `KhachHangID` int(11) DEFAULT NULL,
  `ChoNgoiID` int(11) DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  `KhuyenMaiID` int(11) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `LichTrinhXeID` int(11) DEFAULT NULL,
  `ThoiGianDatVe` datetime DEFAULT NULL,
  `CodeVeXe` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`VeXeID`),
  KEY `NhanVienID` (`NhanVienID`),
  KEY `ChoNgoiID` (`ChoNgoiID`),
  KEY `KhuyenMaiID` (`KhuyenMaiID`),
  KEY `LichTrinhXeID` (`LichTrinhXeID`),
  KEY `vexe_ibfk_2` (`KhachHangID`),
  CONSTRAINT `vexe_ibfk_1` FOREIGN KEY (`NhanVienID`) REFERENCES `nhanvien` (`NhanVienID`),
  CONSTRAINT `vexe_ibfk_2` FOREIGN KEY (`KhachHangID`) REFERENCES `khachhang` (`KhachHangID`),
  CONSTRAINT `vexe_ibfk_5` FOREIGN KEY (`ChoNgoiID`) REFERENCES `chongoi` (`ChoNgoiID`),
  CONSTRAINT `vexe_ibfk_6` FOREIGN KEY (`KhuyenMaiID`) REFERENCES `khuyenmai` (`KhuyenMaiID`),
  CONSTRAINT `vexe_ibfk_7` FOREIGN KEY (`LichTrinhXeID`) REFERENCES `lichtrinhxe` (`LichTrinhXeID`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO vexe VALUES("216","","4","30","Đã đặt","0","","2","2024-06-09 22:17:23","RS0Z3");
INSERT INTO vexe VALUES("217","1","1","1","Đã đặt","","","2","2024-05-21 00:00:00","A");
INSERT INTO vexe VALUES("218","1","1","2","Đã đặt","","","2","2024-06-22 00:00:00","B");
INSERT INTO vexe VALUES("219","1","1","3","Đã đặt","","","2","2024-07-23 00:00:00","C");
INSERT INTO vexe VALUES("220","1","1","4","Đã đặt","","","2","2024-08-24 00:00:00","D");
INSERT INTO vexe VALUES("221","1","1","5","Đã đặt","","","2","2024-09-25 00:00:00","E");
INSERT INTO vexe VALUES("222","","4","31","Đã đặt","0","","2","2024-06-10 14:27:53","DY8MR");
INSERT INTO vexe VALUES("223","","4","32","Đã đặt","0","","2","2024-06-10 14:27:53","DY8MR");
INSERT INTO vexe VALUES("229","","4","29","Đã đặt","0","","2","2024-06-10 15:37:23","WR1C2");
INSERT INTO vexe VALUES("231","","31","33","đã thanh toán","","","2","2024-06-10 15:56:03","");
INSERT INTO vexe VALUES("232","","31","34","đã thanh toán","","","2","2024-06-10 15:56:11","");
INSERT INTO vexe VALUES("233","","31","35","đã thanh toán","","","2","2024-06-10 15:58:29","");
INSERT INTO vexe VALUES("234","","4","36","Đã đặt","0","","2","2024-06-10 16:08:29","I4VH0");
INSERT INTO vexe VALUES("235","","4","40","Đã đặt","0","","2","2024-06-10 16:09:18","QT30Y");
INSERT INTO vexe VALUES("242","","4","37","Đã đặt","0","","2","2024-06-11 00:23:10","P0AUV");
INSERT INTO vexe VALUES("245","","4","72","Đã đặt","0","","2","2024-06-11 00:46:51","U8PL7");
INSERT INTO vexe VALUES("246","","4","71","Đã đặt","0","","2","2024-06-11 00:47:54","NG78B");
INSERT INTO vexe VALUES("286","","35","70","Đã đặt","0","","2","2024-06-11 02:18:46","5WZA3");
INSERT INTO vexe VALUES("287","","36","69","Đã đặt","0","","2","2024-06-11 02:28:09","H2UTC");
INSERT INTO vexe VALUES("288","","37","45","Đã đặt","0","","2","2024-06-11 02:40:02","A7M3T");
INSERT INTO vexe VALUES("289","","37","41","Đã đặt","0","","2","2024-06-11 02:46:39","BVR06");
INSERT INTO vexe VALUES("290","","37","42","Đã đặt","0","","2","2024-06-11 02:46:39","BVR06");
INSERT INTO vexe VALUES("291","","38","51","Đã đặt","0","","2","2024-06-11 03:27:28","YBA1P");



DROP TABLE IF EXISTS xe;

CREATE TABLE `xe` (
  `XeID` int(11) NOT NULL,
  `MaXe` varchar(20) NOT NULL,
  `LoaiXe` varchar(100) DEFAULT NULL,
  `BienSo` varchar(50) DEFAULT NULL,
  `SucChua` varchar(50) DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `NhanVienID` int(11) DEFAULT NULL,
  PRIMARY KEY (`XeID`),
  KEY `NhanVienID` (`NhanVienID`),
  CONSTRAINT `xe_ibfk_1` FOREIGN KEY (`NhanVienID`) REFERENCES `nhanvien` (`NhanVienID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO xe VALUES("0","","Xe thêm lúc 21h 06062024","A23543","28","0","","3");
INSERT INTO xe VALUES("1","","Xe khách 28 chỗ","29A-12345","28","Đang hoạt động","Không có","1");
INSERT INTO xe VALUES("2","","Xe khách 45 chỗ","29B-67890","45","Đang bảo dưỡng","Không có","2");
INSERT INTO xe VALUES("3","","Xe trung chuyển 28 chỗ","30A-11223","28","Đang hoạt động","Không có","1");



