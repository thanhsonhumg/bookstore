-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.26 - MySQL Community Server (GPL)
-- Server OS:                    Linux
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for bookstore
CREATE DATABASE IF NOT EXISTS `bookstore` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `bookstore`;

-- Dumping structure for table bookstore.d_orders
CREATE TABLE IF NOT EXISTS `d_orders` (
  `detail_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`detail_order_id`),
  KEY `order_id` (`order_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `d_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `m_orders` (`order_id`),
  CONSTRAINT `d_orders_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `m_books` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Dumping data for table bookstore.d_orders: ~11 rows (approximately)
INSERT INTO `d_orders` (`detail_order_id`, `order_id`, `book_id`, `price`, `count`) VALUES
	(5, 3, 2, 100, 2),
	(6, 3, 3, 200, 1),
	(7, 4, 11, 200, 1),
	(9, 6, 2, 100, 1),
	(10, 6, 3, 200, 1),
	(11, 6, 7, 300, 1),
	(12, 6, 8, 280, 1),
	(13, 7, 33, 87, 1),
	(14, 8, 33, 87, 1),
	(15, 10, 3, 200, 1),
	(16, 10, 2, 100, 1),
	(17, 11, 2, 100, 1);

-- Dumping structure for table bookstore.d_users
CREATE TABLE IF NOT EXISTS `d_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `user_avt` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`),
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `m_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table bookstore.d_users: ~1 rows (approximately)
INSERT INTO `d_users` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `user_avt`, `created_at`, `updated_at`) VALUES
	(1, 20, 'sơn nguyễn', 'son29102k2@gmail.com', '09659705555', 'Hiệp hòa, bắc giang', './user-avatar/20_20250104_201726.jpg', '2024-12-07 03:00:18', '2025-01-04 13:17:31'),
	(2, 21, 'Nguyễn Thanh Sơn', 'son29102k2@gmail.com', '0965970545', 'Dịch vọng hậu, cầu giấy', './user-avatar/21_20250104_202859.jpg', '2025-01-04 13:23:00', '2025-01-04 13:28:59');

-- Dumping structure for table bookstore.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `m_users` (`user_id`),
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `m_books` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table bookstore.favorites: ~2 rows (approximately)
INSERT INTO `favorites` (`id`, `user_id`, `book_id`, `created_at`) VALUES
	(3, 20, 1, '2025-01-04 02:08:16'),
	(4, 20, 2, '2025-01-04 02:08:18');

-- Dumping structure for table bookstore.m_books
CREATE TABLE IF NOT EXISTS `m_books` (
  `detail_id` varchar(100) NOT NULL,
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(255) NOT NULL,
  `book_type` varchar(50) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `published_year` int(11) DEFAULT NULL,
  `book_price` int(11) NOT NULL DEFAULT '0',
  `book_img` varchar(255) DEFAULT NULL,
  `book_desc` text,
  `stop_flg` int(11) NOT NULL DEFAULT '0' COMMENT '0: Normal, 1: Disabled',
  `insert_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date of insert',
  `update_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date of update',
  `insert_id` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_id` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_id`) USING BTREE,
  UNIQUE KEY `detail_id` (`detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

-- Dumping data for table bookstore.m_books: ~54 rows (approximately)
INSERT INTO `m_books` (`detail_id`, `book_id`, `book_name`, `book_type`, `author`, `published_year`, `book_price`, `book_img`, `book_desc`, `stop_flg`, `insert_dt`, `update_dt`, `insert_id`, `update_id`) VALUES
	('CTVHB', 1, 'Chiến tranh và hòa bình', 'Văn học', 'Lev Tolstoy', 18690126, 300, 'insert-img/CTVHB_20241216_205348.jpg', '\n"Chiến tranh và Hòa bình" của Lev Tolstoy là một kiệt tác văn học Nga, kể về cuộc sống, tình yêu và chiến tranh của bốn gia đình quý tộc trong bối cảnh nước Nga đầu thế kỷ 19, khi Napoleon xâm lược. Tác phẩm nổi bật với các nhân vật phức tạp như Pierre Bezukhov, Andrei Bolkonsky, Natasha Rostova và sự đối lập giữa Napoleon và tướng Kutuzov, đồng thời khám phá sâu sắc ý nghĩa của chiến tranh, hòa bình, tình yêu và ý chí con người. Với sự kết hợp giữa lịch sử và hư cấu, cùng những suy tư triết học về cuộc sống, tác phẩm trở thành biểu tượng văn học thế giới, tôn vinh sức mạnh của hy vọng và giá trị của nhân sinh.', 0, '2024-11-26 14:27:20', '2024-12-16 13:53:48', '2024-11-26 14:27:20', '2024-12-16 13:53:48'),
	('DMPLK', 2, 'Dế Mèn phiêu lưu ký', 'Thiếu nhi', 'Tô Hoài', 19410101, 100, 'insert-img/DMPLK_20241216_092842.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 02:28:42', '2024-11-26 14:27:20', '2024-12-16 02:28:42'),
	('TK', 3, 'Truyện Kiều', 'Văn học', 'Nguyễn Du', 18200210, 200, 'insert-img/TK_20241216_092951.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 13:54:42', '2024-11-26 14:27:20', '2024-12-16 13:54:42'),
	('LSTGH', 6, 'Lịch sử thế giới hiện đại', 'Lịch sử', 'Various', 20210329, 220, 'insert-img/LSTGH_20241216_093258.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 02:32:58', '2024-11-26 14:27:20', '2024-12-16 02:32:58'),
	('LSVNT', 7, 'Lịch sử Việt Nam từ nguồn gốc đến cuối thế kỉ XIX', 'Lịch sử', 'Đào Duy Anh', 20230912, 189, 'insert-img/LSVNT_20241216_093424.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 02:34:53', '2024-11-26 14:27:20', '2024-12-16 02:34:53'),
	('TNCCD', 8, 'Tín ngưỡng của các dân tộc Việt Nam', 'Phong tục - tập quán Việt Nam', 'GT.TS. Ngô Đức Thịnh', 20231019, 209, 'insert-img/TNCCD_20241216_093648.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 02:36:48', '2024-11-26 14:27:20', '2024-12-16 02:36:48'),
	('CNT', 11, 'Đắc nhân tâm', 'Kỹ năng sống', 'Dale Carnegie', 20220629, 200, 'insert-img/CNT_20241216_100214.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 03:02:14', '2024-11-26 14:27:20', '2024-12-16 03:02:14'),
	('QGLIV', 12, 'Quẳng gánh lo đi và vui sống', 'Tâm lý học', 'Dale Carnegie', 20210101, 150, 'insert-img/QGLIV_20241216_100254.webp', NULL, 0, '2024-11-26 14:27:20', '2024-12-16 03:03:18', '2024-11-26 14:27:20', '2024-12-16 03:03:18'),
	('TDNVC', 14, 'Tư duy nhanh và chậm', 'Tâm lý học', 'Daniel Kahneman', 2011, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:15', '2024-11-26 14:27:20', '2024-11-26 14:35:15'),
	('DML', 15, 'Dám nghĩ lớn', 'Kỹ năng sống', 'David J. Schwartz', 1959, 180, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:15', '2024-11-26 14:27:20', '2024-11-26 14:35:15'),
	('TCBBCS', 16, 'Totto-chan: Cô bé bên cửa sổ', 'Thiếu nhi', 'Tetsuko Kuroyanagi', 1981, 120, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:16', '2024-11-26 14:27:20', '2024-11-26 14:35:16'),
	('CVCNT', 17, 'Charlotte và chú nhện tài ba', 'Thiếu nhi', 'E. B. White', 0, 110, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 1, '2024-11-26 14:27:20', '2024-11-27 13:08:26', '2024-11-26 14:27:20', '2024-11-27 13:08:26'),
	('NMDCT', 18, 'Những mảnh đời cổ tích', 'Thiếu nhi', 'Grimm Brothers', 1812, 90, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:17', '2024-11-26 14:27:20', '2024-11-26 14:35:17'),
	('HTB', 19, 'Hoàng tử bé', 'Thiếu nhi', 'Antoine de Saint-Exupéry', 1943, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:18', '2024-11-26 14:27:20', '2024-11-26 14:35:18'),
	('VTTVHD', 22, 'Vũ trụ trong vỏ hạt dẻ', 'Khoa học', 'Stephen Hawking', 2001, 250, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:21', '2024-11-26 14:27:20', '2024-11-26 14:35:21'),
	('GRCLS', 23, 'Gốc rễ của lịch sử', 'Khoa học', 'Yuval Noah Harari', 2014, 350, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:21', '2024-11-26 14:27:20', '2024-11-26 14:35:21'),
	('TGVC', 24, 'Thế giới vật chất', 'Khoa học', 'Richard Feynman', 1998, 400, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:22', '2024-11-26 14:27:20', '2024-11-26 14:35:22'),
	('THVCT', 25, 'Thiên hà và chúng ta', 'Khoa học', 'Neil deGrasse Tyson', 2017, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:23', '2024-11-26 14:27:20', '2024-11-26 14:35:23'),
	('NKTT', 26, 'Nhật ký trong tù', 'Văn học', 'Hồ Chí Minh', 1942, 100, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:22', '2024-11-26 14:27:20', '2024-11-26 14:35:22'),
	('TPKL', 27, 'Thành phố khói lửa', 'Lịch sử', 'Nguyễn Tuân', 1948, 180, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:24', '2024-11-26 14:27:20', '2024-11-26 14:35:24'),
	('GT', 29, 'Giông tố', 'Văn học', 'Vũ Trọng Phụng', 1936, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:26', '2024-11-26 14:27:20', '2024-11-26 14:35:26'),
	('TD', 30, 'Tắt đèn', 'Văn học', 'Ngô Tất Tố', 1939, 150, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:26', '2024-11-26 14:27:20', '2024-11-26 14:35:26'),
	('HPVHDPT', 31, 'Harry Potter và Hòn đá Phù thủy', 'Thiếu nhi', 'J. K. Rowling', 1997, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:27', '2024-11-26 14:27:20', '2024-11-26 14:35:27'),
	('HPVPCBM', 32, 'Harry Potter và Phòng chứa Bí mật', 'Thiếu nhi', 'J. K. Rowling', 1998, 350, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:27', '2024-11-26 14:27:20', '2024-11-26 14:35:27'),
	('TDTTD', 33, 'Thép đã tôi thế đấy', 'Tiểu thuyết', 'Nikolai Alekseyevich Ostrovsky', 1934, 87, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:28', '2024-11-26 14:27:20', '2024-11-26 14:35:28'),
	('NCCVH', 34, 'Những câu chuyện văn học', 'Thiếu nhi', 'Andersen', 1835, 100, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:28', '2024-11-26 14:27:20', '2024-11-26 14:35:28'),
	('NMHUD', 35, 'Những mảnh hồi ức đẹp', 'Thiếu nhi', 'Various', 1920, 90, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:29', '2024-11-26 14:27:20', '2024-11-26 14:35:29'),
	('LSTG', 37, 'Lịch sử thế giới', 'Lịch sử', 'Howard Zinn', 1999, 400, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:30', '2024-11-26 14:27:20', '2024-11-26 14:35:30'),
	('DLVLSVN', 38, 'Địa lý và lịch sử Việt Nam', 'Lịch sử', 'Trần Văn Giàu', 1978, 220, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:30', '2024-11-26 14:27:20', '2024-11-26 14:35:30'),
	('CNQSCD', 39, 'Cẩm nang quân sự cổ đại', 'Lịch sử', 'Tôn Tử', 500, 150, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:31', '2024-11-26 14:27:20', '2024-11-26 14:35:31'),
	('DCTTDS', 40, 'Dân chủ trong thời đại số', 'Lịch sử', 'Nguyễn Phú Trọng', 2016, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:32', '2024-11-26 14:27:20', '2024-11-26 14:35:32'),
	('TLHTP', 41, 'Tâm lý học tội phạm', 'Tâm lý học', 'Jordan B. Peterson', 2019, 280, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:33', '2024-11-26 14:27:20', '2024-11-26 14:35:33'),
	('PLTL', 42, 'Phân tích tâm lý', 'Tâm lý học', 'Sigmund Freud', 1900, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:33', '2024-11-26 14:27:20', '2024-11-26 14:35:33'),
	('KNDP', 43, 'Kỹ năng đàm phán', 'Kỹ năng sống', 'Chris Voss', 2016, 350, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:34', '2024-11-26 14:27:20', '2024-11-26 14:35:34'),
	('XLHVNT', 44, 'Xử lý hành vi nội tâm', 'Tâm lý học', 'Tony Robbins', 1995, 400, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:35', '2024-11-26 14:27:20', '2024-11-26 14:35:35'),
	('TDDMT', 45, 'Tư duy đạt mục tiêu', 'Kỹ năng sống', 'Brian Tracy', 2001, 180, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:36', '2024-11-26 14:27:20', '2024-11-26 14:35:36'),
	('PHCSTTL', 46, 'Phác họa cuộc sống trong tương lai', 'Khoa học', 'Michio Kaku', 2018, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:36', '2024-11-26 14:27:20', '2024-11-26 14:35:36'),
	('VTHD', 47, 'Vũ trụ hấp dẫn', 'Khoa học', 'Stephen Hawking', 1988, 350, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:37', '2024-11-26 14:27:20', '2024-11-26 14:35:37'),
	('CTNHH', 49, 'Các thí nghiệm hóa học', 'Khoa học', 'Marie Curie', 1903, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:38', '2024-11-26 14:27:20', '2024-11-26 14:35:38'),
	('THBT', 50, 'Thiên hà bóng tối', 'Khoa học', 'Neil Tyson', 2019, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:38', '2024-11-26 14:27:20', '2024-11-26 14:35:38'),
	('DCVNDT', 51, 'Dân chủ và nền dân trị', 'Chính trị', 'John Stuart Mill', 1859, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:39', '2024-11-26 14:27:20', '2024-11-26 14:35:39'),
	('KTVQP', 52, 'Kinh tế và quốc phòng', 'Chính trị', 'Adam Smith', 1776, 250, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:39', '2024-11-26 14:27:20', '2024-11-26 14:35:39'),
	('TDQLQG', 53, 'Tư duy quản lý quốc gia', 'Chính trị', 'Nguyễn Minh Triết', 2007, 280, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:40', '2024-11-26 14:27:20', '2024-11-26 14:35:40'),
	('CTKT', 54, 'Chiến tranh kinh tế', 'Chính trị', 'Various', 2022, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:40', '2024-11-26 14:27:20', '2024-11-26 14:35:40'),
	('PNVCT', 55, 'Phát triển và cách tân', 'Chính trị', 'Thomas Friedman', 2005, 350, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:40', '2024-11-26 14:27:20', '2024-11-26 14:35:40'),
	('CDBT', 56, 'Cánh đồng bất tận', 'Văn học', 'Nguyễn Ngọc Tư', 2005, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:41', '2024-11-26 14:27:20', '2024-11-26 14:35:41'),
	('TTHVTCX', 57, 'Tôi thấy hoa vàng trên cỏ xanh', 'Văn học', 'Nguyễn Nhật Ánh', 2010, 180, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:43', '2024-11-26 14:27:20', '2024-11-26 14:35:43'),
	('TNMH', 58, 'Thương nhớ mười hai', 'Văn học', 'Vũ Bằng', 1971, 150, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:43', '2024-11-26 14:27:20', '2024-11-26 14:35:43'),
	('LCTP', 59, 'Lá cờ trên phố', 'Văn học', 'Various', 1990, 180, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:44', '2024-11-26 14:27:20', '2024-11-26 14:35:44'),
	('BCTMN', 60, 'Bước chân bên miền nhớ', 'Văn học', 'Various', 2023, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:44', '2024-11-26 14:27:20', '2024-11-26 14:35:44'),
	('BDKC', 61, 'Biệt đội kiếm chuyện', 'Thiếu nhi', 'Tô Hoài', 2005, 100, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:45', '2024-11-26 14:27:20', '2024-11-26 14:35:45'),
	('PTVTG', 63, 'Phép thuật và thầy giáo', 'Thiếu nhi', 'Lewis Caroll', 1870, 100, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:46', '2024-11-26 14:27:20', '2024-11-26 14:35:46'),
	('HHCMQ', 64, 'Hiểu hết các món quà', 'Thiếu nhi', 'Various', 2020, 300, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:47', '2024-11-26 14:27:20', '2024-11-26 14:35:47'),
	('HTVNG', 65, 'Hoàng tử và người gác', 'Thiếu nhi', 'Grimm Brothers', 1809, 200, 'insert-img/DMPLK_20241126_213342.jpg', NULL, 0, '2024-11-26 14:27:20', '2024-11-26 14:35:49', '2024-11-26 14:27:20', '2024-11-26 14:35:49');

-- Dumping structure for table bookstore.m_orders
CREATE TABLE IF NOT EXISTS `m_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'pending-complete-cancelled',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipping_address` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table bookstore.m_orders: ~8 rows (approximately)
INSERT INTO `m_orders` (`order_id`, `user_id`, `total_amount`, `status`, `created_at`, `updated_at`, `shipping_address`, `phone`) VALUES
	(3, '0', 400, 'completed', '2024-11-28 14:17:31', '2025-01-04 12:45:38', 'hưng thịnh, Thị trấn thắng, huyện hiệp hòa, tỉnh Bắc giang', '0965970545'),
	(4, '20', 200, 'cancelled', '2024-12-04 17:15:53', '2025-01-04 03:10:02', 'hưng thịnh, Thị trấn thắng, huyện hiệp hòa, tỉnh Bắc giang', '0965970545'),
	(5, '20', 450, 'completed', '2024-12-06 17:02:03', '2025-01-04 12:45:22', 'hưng thịnh, Thị trấn thắng, huyện hiệp hòa, tỉnh Bắc giang', '0965970545'),
	(6, '20', 880, 'pending', '2024-12-07 03:46:36', '2024-12-07 03:46:36', 'Hiệp hòa, bắc giang', '0965970545'),
	(7, '20', 87, 'completed', '2024-12-07 03:48:00', '2024-12-07 04:02:06', 'Hiệp hòa, bắc giang', '0965970545'),
	(8, '20', 87, 'pending', '2024-12-07 03:49:20', '2024-12-07 03:49:20', 'Hiệp hòa, bắc giang', '0965970545'),
	(9, '20', 900, 'pending', '2024-12-07 03:59:34', '2024-12-07 03:59:34', 'Hiệp hòa, bắc giang', '0965970545'),
	(10, '20', 300, 'pending', '2024-12-07 04:04:31', '2024-12-07 04:04:31', 'Hiệp hòa, bắc giang', '0965970545'),
	(11, '21', 100, 'completed', '2025-01-04 13:23:30', '2025-01-04 13:24:12', 'Dịch vọng hậu, cầu giấy', '0965970545');

-- Dumping structure for table bookstore.m_users
CREATE TABLE IF NOT EXISTS `m_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) DEFAULT '0' COMMENT '''0'': user - ''1'': admin',
  `stop_flg` int(11) DEFAULT '0' COMMENT '0: Normal, 1: Disabled',
  `insert_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Dumping data for table bookstore.m_users: ~3 rows (approximately)
INSERT INTO `m_users` (`user_id`, `username`, `password`, `role`, `stop_flg`, `insert_dt`, `update_dt`) VALUES
	(18, 'sondenhat29', '@Sondenhat29', 1, 0, '2024-11-15 13:00:04', '2024-11-16 13:58:19'),
	(20, 'sonnt123', '@Sondenhat29', 0, 0, '2024-11-15 13:00:04', '2024-12-04 17:07:38'),
	(21, 'sondenhat2910', '@Sondenhat29', 0, 0, '2024-11-15 13:00:04', '2024-11-16 13:58:10');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
