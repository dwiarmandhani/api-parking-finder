-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2023 at 07:54 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parking-finder`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_image_place`
--

INSERT INTO `tbl_image_place` (`image_id`, `image_place_id`, `image_name`, `image_type`, `image_path`, `image_size`) VALUES
(17, 31, '1690902011.png', 'image/png', './uploads/image_place/1690902011.png', 20.19),
(18, 31, '16909020111.png', 'image/png', './uploads/image_place/16909020111.png', 16.58),
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
(32, 38, '16909040121.png', 'image/png', './uploads/image_place/16909040121.png', 16.58);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_place`
--

INSERT INTO `tbl_place` (`place_id`, `place_user_id`, `place_name`, `place_address`, `place_car`, `place_motor`, `place_description`, `place_image`, `place_longitude`, `place_latitude`, `place_pic`, `place_pic_contact`, `place_active`, `place_rating`) VALUES
(31, 27, 'Parkiran 1', 'jalan maleber utara', '10', '50', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690902011.png\",\".\\/uploads\\/image_place\\/16909020111.png\"]', '11111', '2222', 'Dwi', '085721813979', 1, '49'),
(32, 27, 'Superindo', 'Jalan rajawali', '9', '20', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903526.png\",\".\\/uploads\\/image_place\\/16909035261.png\"]', '-6.912447812693243', '107.57540765115746', 'Dwi', '085721813979', 1, '52'),
(33, 27, 'Rumah Sakit Rajawali', 'Jl. Rajawali Barat No.38, Maleber, Kec. Andir, Kota Bandung, Jawa Barat 40184', '40', '60', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903586.png\",\".\\/uploads\\/image_place\\/16909035861.png\"]', '-6.9118087612516055', '107.57347646092317', 'Dwi', '085721813979', 1, '69'),
(34, 27, 'Sate AMBAL, Sate TAICHAN, Sate MARANGGI Mas Ikhsan', 'Jl. Maleber Utara', '5', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903668.png\",\".\\/uploads\\/image_place\\/16909036681.png\"]', '-6.907580348942091', '107.57848585864954', 'Dwi', '085721813979', 1, '4'),
(35, 27, 'Alun-Alun Cicendo', 'Jl. Jalan jatayu', '38', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903795.png\",\".\\/uploads\\/image_place\\/16909037951.png\"]', '-6.9103176378437885', '107.5884539148363', 'Dwi', '085721813979', 1, '98'),
(36, 27, 'Bandara husein', 'Jalan husein', '22', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903858.png\",\".\\/uploads\\/image_place\\/16909038581.png\"]', '-6.90316179339655', '107.57999013903179', 'Dwi', '085721813979', 1, '76'),
(37, 27, 'Hotel Endah', 'Jalan cibabat', '29', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690903925.png\",\".\\/uploads\\/image_place\\/16909039251.png\"]', '-6.903028034092383', '107.56502908216913', 'Dwi', '085721813979', 1, '39'),
(38, 27, 'Taman Hutan Raya Ir. H. Djuanda', 'Kompleks Tahura, Jl. Ir. H. Juanda No.99, Ciburial, Kec. Cimenyan, Kabupaten Bandung, Jawa Barat 40198', '29', '100', 'mantaappppp', '[\".\\/uploads\\/image_place\\/1690904012.png\",\".\\/uploads\\/image_place\\/16909040121.png\"]', '-6.856702481282392', '107.62900828179558', 'Dwi', '085721813979', 1, '5');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ratings`
--

CREATE TABLE `tbl_ratings` (
  `rating_id` int(11) NOT NULL,
  `rating_user_id` int(11) NOT NULL,
  `rating_place_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_phone`, `user_email`, `user_password`, `user_is_admin`) VALUES
(27, 'admindwi', '085721813979', 'admindwi@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 1),
(28, 'tes1', '085721813979', 'test1@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(29, 'tes2', '085721813979', 'test2@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0),
(30, 'tes3', '085721813979', 'test3@gmail.com', 'ecf8e015a19665e5fd1825212b3321d142cad4253e638a487151887adf6b27033e0e63e6b004867857a0e95a590044eba99d3bb6ab6dad51a007233a312674ac', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_lastlocation`
--

CREATE TABLE `tbl_user_lastlocation` (
  `lastlocation_id` int(11) NOT NULL,
  `lastlocation_user_id` int(11) NOT NULL,
  `lastlocation_longitude` varchar(256) NOT NULL,
  `lastlocation_latitude` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user_lastlocation`
--

INSERT INTO `tbl_user_lastlocation` (`lastlocation_id`, `lastlocation_user_id`, `lastlocation_longitude`, `lastlocation_latitude`) VALUES
(1, 27, '-6.905889', '107.574528');

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
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_place`
--
ALTER TABLE `tbl_place`
  MODIFY `place_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_user_lastlocation`
--
ALTER TABLE `tbl_user_lastlocation`
  MODIFY `lastlocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
