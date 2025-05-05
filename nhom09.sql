-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2025 at 09:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nhom09_kiemtra2`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `image_url`, `description`, `price`) VALUES
(1, 'iPhone 15 Pro Max 256GB', 20, 'image/iphone15.jpg', 'iPhone 15 Pro Max - chip A17 Pro, khung titan siêu nhẹ.', 33990000.00),
(2, 'Samsung Galaxy S23 Ultra 256GB', 15, 'image/galaxy-s23.jpg', 'Samsung S23 Ultra - camera 200MP, Snapdragon 8 Gen 2.', 29990000.00),
(3, 'Xiaomi 13 Pro 12GB/256GB', 25, 'image/xiaomi-13.jpg', 'Xiaomi 13 Pro - camera Leica, hiệu năng mạnh mẽ.', 18990000.00),
(4, 'OPPO Find N3 Flip 256GB', 12, 'image/oppo-findn.jpg', 'Điện thoại gập OPPO Find N3 Flip - thời trang, cấu hình cao.', 22990000.00),
(5, 'vivo V27e 8GB/256GB', 30, 'image/vivo-v27e.jpg', 'vivo V27e - thiết kế đẹp, chụp ảnh chân dung chuyên nghiệp.', 7490000.00),
(6, 'realme C55 6GB/128GB', 35, 'image/realme-c55.jpg', 'realme C55 - pin lớn, màn hình lớn, giá mềm.', 4590000.00),
(7, 'iPhone 13 128GB', 20, 'image/iphone13.jpg', 'iPhone 13 - chip A15, Face ID, pin bền.', 17490000.00),
(8, 'Samsung Galaxy A54 5G 128GB', 25, 'image/galaxy-a54.jpg', 'Galaxy A54 5G - hiệu năng ổn định, màn Super AMOLED.', 9490000.00),
(9, 'Xiaomi Redmi Note 12 6GB/128GB', 40, 'image/redmi-note12.jpg', 'Redmi Note 12 - pin 5000mAh, màn hình 120Hz.', 4790000.00),
(10, 'vivo Y36 8GB/128GB', 30, 'image/vivo-y36.jpg', 'vivo Y36 - sạc nhanh 44W, thiết kế trẻ trung.', 5390000.00),
(11, 'OPPO Reno10 5G 256GB', 18, 'image/oppo-reno10.jpg', 'OPPO Reno10 - zoom quang học 2x, sạc nhanh 67W.', 11990000.00),
(12, 'realme 11 Pro+ 5G', 22, 'image/realme-11pro.jpg', 'realme 11 Pro+ - camera 200MP, thiết kế da lưng.', 9990000.00),
(13, 'iPhone SE 2022 64GB', 15, 'image/iphone-se.jpg', 'iPhone SE 3 - nhỏ gọn, hiệu năng A15 mạnh mẽ.', 9990000.00),
(14, 'Samsung Galaxy Z Flip5 256GB', 10, 'image/galaxy-zflip5.jpg', 'Galaxy Z Flip5 - điện thoại gập dọc thời trang.', 24990000.00),
(15, 'Xiaomi Poco F5 8GB/256GB', 20, 'image/poco-f5.jpg', 'Poco F5 - chip Snapdragon 7+ Gen 2, hiệu năng khủng.', 8490000.00),
(16, 'vivo X90 12GB/256GB', 12, 'image/vivo-x90.jpg', 'vivo X90 - camera ZEISS, thiết kế cao cấp.', 19990000.00),
(17, 'realme Narzo 60x 5G', 30, 'image/narzo60x.jpg', 'realme Narzo 60x - pin 5000mAh, giá rẻ cho sinh viên.', 3990000.00),
(18, 'Samsung Galaxy M14 5G', 35, 'image/galaxy-m14.jpg', 'Galaxy M14 5G - pin khủng, giá hợp lý.', 4290000.00),
(19, 'Xiaomi 12T Pro 5G 12GB/256GB', 10, 'image/xiaomi-12tpro.jpg', 'Xiaomi 12T Pro - Snapdragon 8+ Gen 1, camera 200MP.', 14990000.00),
(20, 'OPPO A78 8GB/256GB', 28, 'image/oppo-a78.jpg', 'OPPO A78 - sạc nhanh, hiệu năng ổn định.', 6390000.00),
(21, 'Test Product', 10, NULL, 'Test description', 99.99);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
