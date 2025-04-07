-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 06:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resident_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `nic` varchar(12) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other','') NOT NULL,
  `registered_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `full_name`, `dob`, `nic`, `address`, `phone`, `email`, `occupation`, `gender`, `registered_date`) VALUES
(32, 'Nimal Perera', '1985-04-10', '852345678V', 'No. 123, Galle Road, Colombo 03', '0771234567', 'nimal.perera@gmail.com', 'Teacher', 'Male', '2025-04-07 16:19:42'),
(33, 'Ishara Gunasekara', '1992-07-23', '923456789V', '45/1, Temple Road, Kandy', '0712345678', 'ishara.g@gmail.com', 'Female', 'Female', '2025-04-07 16:21:27'),
(34, 'Chamara Jayasuriya', '1990-02-15', '903456789V', '88, Lake View Lane, Kurunegala', '0756789123', 'chamara.j@gmail.com', 'Accountant', 'Male', '2025-04-07 16:22:18'),
(35, 'Nilusha Fernando', '1998-11-30', '883456789V', '21A, Beach Road, Negombo', '0761234987', 'nilu.fernando@yahoo.com', 'Nurse', 'Female', '2025-04-07 16:23:11'),
(36, 'Dinesh Abeywickrama', '1983-06-25', '832345678V', '7, Hill Street, Nuwara Eliya', '0777894561', 'dinesh.abey@gmail.com', 'Lawyer', 'Male', '2025-04-07 16:24:06'),
(37, 'Anushka Madushani', '1995-12-08', '953456789V', '10, Main Street, Matara', '0723456789', 'anushka.madu@gmail.com', 'Pharmacist', 'Female', '2025-04-07 16:24:57'),
(38, 'Tharindu Rathnayake', '1987-03-19', '873456789V', '56, Peradeniya Road, Gampola', '0701122334', 'tharindu.r@gmail.com', 'Civil Engineer', 'Male', '2025-04-07 16:25:41'),
(39, 'Sajini Wickramasinghe', '1993-09-05', '933456789V', '14, Railway Avenue, Batticaloa', '0783456721', 'sajini.w@gmail.com', 'Architect', 'Female', '2025-04-07 16:26:26'),
(40, 'Roshan De Silva', '1991-01-12', '913456789V', '90, Kaduwela Road, Malabe', '0776655443', 'roshan.desilva@outlook.com', 'Lecturer', 'Male', '2025-04-07 16:27:12'),
(41, 'Kaushalya Weerasinghe', '1996-05-28', '962345678V', '22, Palm Garden, Polonnaruwa', '0794567812', 'kaushalya.w@gmail.com', 'Graphic Designer', 'Female', '2025-04-07 16:27:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
