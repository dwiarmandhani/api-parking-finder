-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2023 at 06:03 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id21096865_apiparkingapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(1000) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(4, 0, 'generateNewKey', 0, 0, 0, NULL, 0),
(6, 0, '7fcf4ba391c48784edde599889d6e3f1e47a27db36ecc050cc92f259bfac38afad2c68a1ae804d77075e8fb722503f3eca2b2c1006ee6f6c7b7628cb45fffd1d', 1, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `limits`
--

CREATE TABLE `limits` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image_place`
--

CREATE TABLE `tbl_image_place` (
  `image_id` int(11) NOT NULL,
  `image_place_id` int(11) NOT NULL,
  `image_name` varchar(50) NOT NULL,
  `image_type` varchar(50) NOT NULL,
  `image_path` varchar(150) NOT NULL,
  `image_size` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_image_place`
--

INSERT INTO `tbl_image_place` (`image_id`, `image_place_id`, `image_name`, `image_type`, `image_path`, `image_size`) VALUES
(19, 32, '1690903526.png', 'image/png', './uploads/image_place/1690903526.png', 20.19),
(20, 32, '16909035261.png', 'image/png', './uploads/image_place/16909035261.png', 16.58),
(21, 33, '1690903586.png', 'image/png', './uploads/image_place/1690903586.png', 20.19),
(22, 33, '16909035861.png', 'image/png', './uploads/image_place/16909035861.png', 16.58),
(23, 34, '1690903668.png', 'image/png', './uploads/image_place/1690903668.png', 20.19),
(24, 34, '16909036681.png', 'image/png', './uploads/image_place/16909036681.png', 16.58),
(25, 35, '1690903795.png', 'image/png', './uploads/image_place/1690903795.png', 20.19),
(26, 35, '16909037951.png', 'image/png', './uploads/image_place/16909037951.png', 16.58),
(27, 36, '1690903858.png', 'image/png', './uploads/image_place/1690903858.png', 20.19),
(28, 36, '16909038581.png', 'image/png', './uploads/image_place/16909038581.png', 16.58),
(29, 37, '1690903925.png', 'image/png', './uploads/image_place/1690903925.png', 20.19),
(30, 37, '16909039251.png', 'image/png', './uploads/image_place/16909039251.png', 16.58),
(31, 38, '1690904012.png', 'image/png', './uploads/image_place/1690904012.png', 20.19),
(32, 38, '16909040121.png', 'image/png', './uploads/image_place/16909040121.png', 16.58),
(33, 39, '1691240596.jpg', 'image/jpeg', './uploads/image_place/1691240596.jpg', 118.59),
(34, 39, '1691240596.jpeg', 'image/jpeg', './uploads/image_place/1691240596.jpeg', 71.18),
(35, 40, '1691240932.jpg', 'image/jpeg', './uploads/image_place/1691240932.jpg', 88.31),
(36, 40, '1691240932.jpeg', 'image/jpeg', './uploads/image_place/1691240932.jpeg', 71.18),
(37, 41, '1691241330.jpg', 'image/jpeg', './uploads/image_place/1691241330.jpg', 88.31),
(38, 41, '1691241330.jpeg', 'image/jpeg', './uploads/image_place/1691241330.jpeg', 71.18),
(39, 42, '1691256227.jpg', 'image/jpeg', './uploads/image_place/1691256227.jpg', 88.31),
(40, 42, '1691256227.jpeg', 'image/jpeg', './uploads/image_place/1691256227.jpeg', 71.18),
(41, 43, '1691294888.jpg', 'image/jpeg', './uploads/image_place/1691294888.jpg', 88.31),
(42, 43, '1691294888.jpeg', 'image/jpeg', './uploads/image_place/1691294888.jpeg', 71.18),
(43, 44, '1691295039.jpg', 'image/jpeg', './uploads/image_place/1691295039.jpg', 88.31),
(44, 44, '1691295039.jpeg', 'image/jpeg', './uploads/image_place/1691295039.jpeg', 71.18),
(45, 45, '1691296159.jpg', 'image/jpeg', './uploads/image_place/1691296159.jpg', 88.31),
(46, 45, '1691296159.jpeg', 'image/jpeg', './uploads/image_place/1691296159.jpeg', 71.18),
(47, 46, '1691331994.jpg', 'image/jpeg', './uploads/image_place/1691331994.jpg', 88.31),
(48, 46, '1691331994.jpeg', 'image/jpeg', './uploads/image_place/1691331994.jpeg', 71.18);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_place`
--

CREATE TABLE `tbl_place` (
  `place_id` int(11) NOT NULL,
  `place_user_id` int(11) NOT NULL,
  `place_name` varchar(50) NOT NULL,
  `place_address` varchar(128) NOT NULL,
  `place_car` varchar(11) NOT NULL,
  `place_motor` varchar(11) NOT NULL,
  `place_description` text NOT NULL,
  `place_image` varchar(1000) NOT NULL,
  `place_longitude` varchar(256) NOT NULL,
  `place_latitude` varchar(256) NOT NULL,
  `place_pic` varchar(256) NOT NULL,
  `place_pic_contact` varchar(20) NOT NULL,
  `place_active` int(11) NOT NULL,
  `place_rating` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_place`
--

INSERT INTO `tbl_place` (`place_id`, `place_user_id`, `place_name`, `place_address`, `place_car`, `place_motor`, `place_description`, `place_image`, `place_longitude`, `place_latitude`, `place_pic`, `place_pic_contact`, `place_active`, `place_rating`) VALUES
(32, 27, 'Superindo', 'Jalan rajawali', '9', '20', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903526.png\",\".\\/uploads\\/image_place\\/16909035261.png\"]', '-6.912447812693243', '107.57540765115746', 'Dwi', '085721813979', 1, '52'),
(33, 27, 'Rumah Sakit Rajawali', 'Jl. Rajawali Barat No.38, Maleber, Kec. Andir, Kota Bandung, Jawa Barat 40184', '40', '60', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903586.png\",\".\\/uploads\\/image_place\\/16909035861.png\"]', '-6.9118087612516055', '107.57347646092317', 'Dwi', '085721813979', 1, '69'),
(34, 27, 'Sate AMBAL, Sate TAICHAN, Sate MARANGGI Mas Ikhsan', 'Jl. Maleber Utara', '5', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903668.png\",\".\\/uploads\\/image_place\\/16909036681.png\"]', '-6.907580348942091', '107.57848585864954', 'Dwi', '085721813979', 1, '4'),
(35, 27, 'Alun-Alun Cicendo', 'Jl. Jalan jatayu', '38', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903795.png\",\".\\/uploads\\/image_place\\/16909037951.png\"]', '-6.9103176378437885', '107.5884539148363', 'Dwi', '085721813979', 1, '98'),
(36, 27, 'Bandara husein', 'Jalan husein', '22', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903858.png\",\".\\/uploads\\/image_place\\/16909038581.png\"]', '-6.90316179339655', '107.57999013903179', 'Dwi', '085721813979', 1, '76'),
(37, 27, 'Hotel Endah', 'Jalan cibabat', '29', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903925.png\",\".\\/uploads\\/image_place\\/16909039251.png\"]', '-6.903028034092383', '107.56502908216913', 'Dwi', '085721813979', 1, '39'),
(38, 27, 'Taman Hutan Raya Ir. H. Djuanda', 'Kompleks Tahura, Jl. Ir. H. Juanda No.99, Ciburial, Kec. Cimenyan, Kabupaten Bandung, Jawa Barat 40198', '29', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690904012.png\",\".\\/uploads\\/image_place\\/16909040121.png\"]', '-6.856702481282392', '107.62900828179558', 'Dwi', '085721813979', 1, '5'),
(39, 34, 'Stasiun Semarang Tawang', 'Jl. Taman Tawang No.1, Tj. Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50211', '70', '150', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1691240596.jpg\",\".\\/uploads\\/image_place\\/1691240596.jpeg\"]', '-6.964518', '110.428101', 'Fajar', '087732743408', 1, ''),
(40, 34, 'Kota Lama Semarang', 'Jln Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Area Luas Doank', '[\".\\/uploads\\/image_place\\/1691240932.jpg\",\".\\/uploads\\/image_place\\/1691240932.jpeg\"]', '-6.964518', '110.428101', 'Firmansyah', '087732743401', 1, '1'),
(41, 27, 'Kota Lama Semarang', 'Jln Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Area Luas Doank', '[\".\\/uploads\\/image_place\\/1691241330.jpg\",\".\\/uploads\\/image_place\\/1691241330.jpeg\"]', '-6.964518', '110.428101', 'Firmansyah', '087732743401', 1, ''),
(42, 27, 'Kota Lama Semarang', 'Jln Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Area Luas Doank', '[\".\\/uploads\\/image_place\\/1691256227.jpg\",\".\\/uploads\\/image_place\\/1691256227.jpeg\"]', '-6.964518', '110.428101', 'Firmansyah', '087732743401', 1, ''),
(43, 34, 'Kota Lama Semarang', 'Jln Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Gasss Keun Bro', '[\".\\/uploads\\/image_place\\/1691294888.jpg\",\".\\/uploads\\/image_place\\/1691294888.jpeg\"]', '110.428101 ', '-6.964518', 'Firmansyah', '087732743401', 1, ''),
(44, 27, 'Semarang Tawang Bank Jateng', 'Jln Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Gasss Keun Bro', '[\".\\/uploads\\/image_place\\/1691295039.jpg\",\".\\/uploads\\/image_place\\/1691295039.jpeg\"]', '110.428101 ', '-6.964518', 'Firmansyah', '087732743401', 1, ''),
(45, 27, 'Hotel Pelangi Indah', 'Jl. Merak No.28, Tj. Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Gasss Keun Bro', '[\".\\/uploads\\/image_place\\/1691296159.jpg\",\".\\/uploads\\/image_place\\/1691296159.jpeg\"]', '110.428546', '-6.965804', 'Firmansyah', '087732743401', 1, ''),
(46, 27, 'Hotel Pelangi Indah', 'Jl. Merak No.28, Tj. Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174', '30', '120', 'Gasss Keun Bro', '[\".\\/uploads\\/image_place\\/1691331994.jpg\",\".\\/uploads\\/image_place\\/1691331994.jpeg\"]', '110.428546', '-6.965804', 'Firmansyah', '087732743401', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ratings`
--

CREATE TABLE `tbl_ratings` (
  `rating_id` int(11) NOT NULL,
  `rating_user_id` int(11) NOT NULL,
  `rating_place_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_ratings`
--

INSERT INTO `tbl_ratings` (`rating_id`, `rating_user_id`, `rating_place_id`) VALUES
(4, 28, 40);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(128) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `user_is_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_phone`, `user_email`, `user_password`, `user_is_admin`) VALUES
(27, 'admindwi', '085721813979', 'admindwi@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 1),
(28, 'tes1', '085721813979', 'test1@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(29, 'tes2', '085721813979', 'test2@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(30, 'tes3', '085721813979', 'test3@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(31, 'tes4', '085721813979', 'test4@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(32, 'dwicontoh', '085721813979', 'tesdwi@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(33, '', '085721813979', 'tedwi@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(34, 'Fajar Ega Firmansyah', '082118219405', 'fajaregafirmansyah@yopmail.com', '40a9696886e623f23f179afc544b095035a19d0e69243dc25dff4cb27163dd0c5131b10637344d91cbdcf52e17193404b336814d01a586d417e854b22e08ab83', 0),
(35, 'sdfssdf', '', 'dsfsfds', '20e3515cf71293985ac058076b85186fc566710f75c1baf9ba1c62a468beadb27e41c843d58012fe9679afa903b953308ba525ed9f1065d3c46cc5e135e76562', 0),
(36, 'hqahwhw', '08976484500', 'testing@gmail.com', '6de0ac5bb661aa50819bb0df09d3fcaabedb1f547c7caea21953183b9894d3f57ba9305ebd819e6778709640b7158047f4ad2aff0d844854ef38ed5f67bd4497', 0),
(37, 'Tester Mobile', '0878239203', 'testermobile@gmail.com', '40a9696886e623f23f179afc544b095035a19d0e69243dc25dff4cb27163dd0c5131b10637344d91cbdcf52e17193404b336814d01a586d417e854b22e08ab83', 0),
(38, 'adnan', '085721813979', 'adnan@parking.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_lastlocation`
--

CREATE TABLE `tbl_user_lastlocation` (
  `lastlocation_id` int(11) NOT NULL,
  `lastlocation_user_id` int(11) NOT NULL,
  `lastlocation_longitude` varchar(256) NOT NULL,
  `lastlocation_latitude` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_lastlocation`
--

INSERT INTO `tbl_user_lastlocation` (`lastlocation_id`, `lastlocation_user_id`, `lastlocation_longitude`, `lastlocation_latitude`) VALUES
(1, 27, '110.4648515', '-7.0137814'),
(2, 34, '110.4648549', '-7.0137752'),
(3, 36, '110.4648541', '-7.0137749'),
(4, 37, '110.4648549', '-7.0137752');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `limits`
--
ALTER TABLE `limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_image_place`
--
ALTER TABLE `tbl_image_place`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `tbl_place`
--
ALTER TABLE `tbl_place`
  ADD PRIMARY KEY (`place_id`);

--
-- Indexes for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_user_lastlocation`
--
ALTER TABLE `tbl_user_lastlocation`
  ADD PRIMARY KEY (`lastlocation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `limits`
--
ALTER TABLE `limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_image_place`
--
ALTER TABLE `tbl_image_place`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tbl_place`
--
ALTER TABLE `tbl_place`
  MODIFY `place_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_user_lastlocation`
--
ALTER TABLE `tbl_user_lastlocation`
  MODIFY `lastlocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
