-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2026 at 11:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_thoi_trang`
--

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` int(11) NOT NULL,
  `ma_dh` int(11) DEFAULT NULL,
  `ma_sp` int(11) DEFAULT NULL,
  `so_luong` int(11) DEFAULT NULL,
  `gia_ban` decimal(15,2) DEFAULT NULL,
  `thanh_tien` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_ctdh`, `ma_dh`, `ma_sp`, `so_luong`, `gia_ban`, `thanh_tien`) VALUES
(12, 7, 1, 1, 150000.00, 150000.00),
(13, 7, 2, 1, 300000.00, 300000.00),
(14, 7, 3, 1, 250000.00, 250000.00);

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_dm` int(11) NOT NULL,
  `ten_dm` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`ma_dm`, `ten_dm`) VALUES
(1, 'Thời trang Nam'),
(2, 'Thời trang Nữ');

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_dh` int(11) NOT NULL,
  `id_tai_khoan` int(11) DEFAULT NULL,
  `ten_kh` varchar(100) DEFAULT NULL,
  `dia_chi_kh` varchar(255) DEFAULT NULL,
  `dien_thoai` varchar(15) DEFAULT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `phuong_thuc_ttoan` varchar(100) DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `trang_thai` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`ma_dh`, `id_tai_khoan`, `ten_kh`, `dia_chi_kh`, `dien_thoai`, `ngay_dat`, `phuong_thuc_ttoan`, `tong_tien`, `trang_thai`) VALUES
(7, 6, 'huong@123', 'Lê Văn Lương', '123456789', '2026-04-08 15:09:55', 'COD', 700000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `ma_sp` int(11) NOT NULL,
  `ten_sp` varchar(255) DEFAULT NULL,
  `gia_ban` decimal(15,2) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `so_luong` int(11) DEFAULT NULL,
  `ma_dm` int(11) DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`ma_sp`, `ten_sp`, `gia_ban`, `size`, `so_luong`, `ma_dm`, `hinh_anh`) VALUES
(1, 'Áo Baby Tee Y2K', 150000.00, 'M', 47, 2, 'ao-baby-tee.jpg'),
(2, 'Quần Túi Hộp Nam', 300000.00, 'S', 23, 1, 'quan-tui-hop.jpg'),
(3, 'Chân Váy Xếp Ly', 250000.00, 'S', 37, 2, 'chan-vay.jpg'),
(4, 'Áo Thun Nam', 50000.00, 'M', 10, 1, 'ao-thun-nam.jpg'),
(6, 'Áo Hoodie Nam Tay Dài', 600000.00, 'M', 2, 1, 'ao-hoodie-nam-tay-dai.jpg'),
(7, 'Áo tanktop Pickleball Driveshot Essentials', 279000.00, 'S', 10, 2, 'ao-tank-top-nu-pkb-exdry-classics.jpg'),
(8, 'Short nam 6inch Pickleball Smash Shot', 379000.00, 'L', 25, 1, 'short-nam-6inch-pickleball-smash.jpg'),
(9, 'Váy thun knit Aline Pickleball Essentials', 399000.00, 'XL', 12, 2, 'vay-thun-knit-aline-pickleball-essentials.jpg'),
(10, 'Áo polo nữ Pickleball Exdry Essentials', 499000.00, 'L', 50, 2, 'ao-polo-nu-pkb-exdry-essentials.jpg'),
(11, ' Tshirt thể thao nữ Quick Dry Tight', 239000.00, 'S', 10, 2, 't-shirt-the-thao-nu-quick-dry-tight-5-xanh-blue.jpg'),
(12, 'Tshirt thể thao nữ Quick Dry Slim', 239000.00, 'XL', 20, 2, 't-shirt-the-thao-nu-quick-dry-slim-16-cam.jpg'),
(13, '\"NTOXCOOLMATE\" Quần short chạy bộ cạp cao 3IN', 400000.00, 'L', 40, 2, 'ntoxcm-quan-short-chay-bo-cap-cao-3in.jpg'),
(14, '\"NTOXCOOLMATE\" Áo T-shirt chạy bộ Light Weight', 239000.00, 'L', 10, 2, 'ntoxcm-t-shirt-chay-bo-nu-light-weight.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `dien_thoai` varchar(15) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `vai_tro` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tai_khoan`
--

INSERT INTO `tai_khoan` (`id`, `ho_ten`, `email`, `mat_khau`, `ngay_tao`, `dien_thoai`, `dia_chi`, `vai_tro`) VALUES
(5, 'admin', 'admin@123', '$2y$10$j7xXE.sqntQvDGBNTrpS8eEEi1IFSnCC0gRJv3PqagrLNg8En7hrG', '2026-04-08 14:59:09', '0849900778', 'Đường 970', 1),
(6, 'huong@123', 'huong@123', '$2y$10$/SJWnGf.Ml2VbAuwe7ZsquO.pQGQRP9P/VJlgfV7si3i9wwIlrUmi', '2026-04-08 15:09:25', '123456789', 'Lê Văn Lương', 0);

-- --------------------------------------------------------

--
-- Table structure for table `thong_ke_truy_cap`
--

CREATE TABLE `thong_ke_truy_cap` (
  `ngay` date NOT NULL,
  `so_luong` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thong_ke_truy_cap`
--

INSERT INTO `thong_ke_truy_cap` (`ngay`, `so_luong`) VALUES
('2026-04-08', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_ctdh`),
  ADD KEY `fk_ctdh_dh` (`ma_dh`),
  ADD KEY `fk_ctdh_sp` (`ma_sp`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_dm`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_dh`),
  ADD KEY `fk_donhang_taikhoan` (`id_tai_khoan`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ma_sp`),
  ADD KEY `ma_dm` (`ma_dm`);

--
-- Indexes for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `thong_ke_truy_cap`
--
ALTER TABLE `thong_ke_truy_cap`
  ADD PRIMARY KEY (`ngay`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `ma_ctdh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_dm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ma_dh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `ma_sp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_dh` FOREIGN KEY (`ma_dh`) REFERENCES `don_hang` (`ma_dh`),
  ADD CONSTRAINT `fk_ctdh_sp` FOREIGN KEY (`ma_sp`) REFERENCES `san_pham` (`ma_sp`);

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_donhang_taikhoan` FOREIGN KEY (`id_tai_khoan`) REFERENCES `tai_khoan` (`id`);

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`ma_dm`) REFERENCES `danh_muc` (`ma_dm`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
