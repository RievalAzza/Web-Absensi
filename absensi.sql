-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 14, 2025 at 08:32 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id` int NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alfa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absen`
--

INSERT INTO `absen` (`id`, `nama_siswa`, `kelas`, `tanggal`, `status`) VALUES
(1, 'Mochamad Rivaldi', 'X', '2025-08-11', 'Hadir'),
(2, 'Wahyu Ali Marzuki', 'X', '2025-08-11', 'Sakit'),
(3, 'Romi Dwi Firmansyah', 'X', '2025-08-11', 'Izin'),
(4, 'Bima Sakti', 'XI', '2025-08-12', 'Hadir'),
(5, 'Mochamad Rivaldi', 'X', '2025-08-12', 'Hadir'),
(6, 'Wahyu Ali Marzuki', 'X', '2025-08-12', 'Hadir'),
(7, 'Wahyu Ali Marzuki', 'X', '2025-08-14', 'Hadir'),
(8, 'Fajar', 'X', '2025-08-14', 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kelas` enum('X','XI','XII','-') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('admin','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `kelas`, `role`) VALUES
(1, 'AdminSekolah', '59b92d6531403428275a0b841c2a78f8', '-', 'admin'),
(2, 'Admin', '1bbd886460827015e5d605ed44252251', '-', 'admin'),
(3, 'Fajar', 'a44b8b519b214aaa677b91f9891ef4da', 'X', 'siswa'),
(4, 'Rizky Adityo', '93078d2dda31e22c769e211604c14579', 'X', 'siswa'),
(5, 'Bagus Satria', '2328634b35144a010ee15d9edf600c8b', 'X', 'siswa'),
(6, 'Yoga Pratama', '7137705a49d4dc91fe7930f57ce112ce', 'X', 'siswa'),
(7, 'Dimas Wicaksono', '2349a38c633f602ac03c7a6a762faf3b', 'X', 'siswa'),
(8, 'Irfan Hakim', '6e28a93dfdb9e19694d8c709ea0ad424', 'X', 'siswa'),
(9, 'Galih Permana', 'f8a7fdceaf3e97dcf9b5d29655fa73d2', 'X', 'siswa'),
(10, 'Wahyu Budianto', 'f5d90c07aa7369b7cc4206673ce65f3c', 'X', 'siswa'),
(11, 'Putri Handayani', 'ef761ea01d9ceb14a53339d804e24331', 'X', 'siswa'),
(12, 'Siti Nurhaliza', 'bb6a696a6e67cae0d1599c76af42910d', 'X', 'siswa'),
(23, 'Aisyah Rahmawati', '2b8410cf319b44547c3d948708b8c347', 'XI', 'siswa'),
(24, 'Dewi Anggraini', '7383ce6edb1ca503309f0e75aaf53512', 'XI', 'siswa'),
(25, 'Dian Kartika', 'd46f61d1ef5b55661822bdf7c00eece9', 'XI', 'siswa'),
(26, 'Lina Marlina', '47569ffc617871ed34a93796f92647e6', 'XI', 'siswa'),
(27, 'Mila Sari', '62707d3c17c8399cb74556a413a724fe', 'XI', 'siswa'),
(28, 'Ratna Wijayanti', 'e07385e98c199c25b0b92a40fe61cae9', 'XI', 'siswa'),
(29, 'Putri Wulandari', 'ff6ec64bcdded0cd730c3d8c7b848555', 'XI', 'siswa'),
(30, 'Salma Aprilia', '42d7e8b28706ba2eca335daab9676ba3', 'XI', 'siswa'),
(31, 'Andi Permana', 'db4a737e5ff7180eabd75d0b594f0fc3', 'XI', 'siswa'),
(32, 'Bima Sakti', '4bf2ee63831350c6d5e887ddb85cb3e8', 'XI', 'siswa'),
(33, 'Aditya Putra', 'a8b582450000fba80302ba598ef6e043', 'XII', 'siswa'),
(34, 'Eka Saputra', '4537f362fcf68fd8468392d601030b8e', 'XII', 'siswa'),
(35, 'Fahmi Hidayat', 'a95df6f3ede6ade115fa8babf9401dd2', 'XII', 'siswa'),
(36, 'Hari Santoso', '89c6d4f299bdd1dc8d7cde884a27618e', 'XII', 'siswa'),
(37, 'Joko Susilo', '04e7b107fd4665f1b5bdb9f2289edd76', 'XII', 'siswa'),
(38, 'Kurniawan Hadi', '411ae084ad68db508965428d098a83f3', 'XII', 'siswa'),
(39, 'Maulana Yusuf', 'a5b263f66870543b76974abf774d2a50', 'XII', 'siswa'),
(40, 'Naufal Akbar', '794288badd23e0341ad5191430622ecd', 'XII', 'siswa'),
(41, 'Dinda Lestari', '58dfa8ec125daab2cff219dcc11627fd', 'XII', 'siswa'),
(42, 'Rina Fitriani', '3a4dfd9ce9650ab7c8f3d3dc22c0f12f', 'XII', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
