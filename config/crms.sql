-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 11:26 PM
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
-- Database: `crms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin@gmail.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `admission_type` varchar(50) DEFAULT NULL,
  `student_id` varchar(11) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `symptoms` text NOT NULL,
  `diagnosis` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `admission_type`, `student_id`, `firstname`, `lastname`, `email`, `symptoms`, `diagnosis`, `status`, `created_at`) VALUES
(1, 'Student', '002299', '', '', '', '[{\"value\":\"headache\"}]', 'Flu', 'Pending', '2025-03-01 11:56:28'),
(2, 'Professor', NULL, 'JOHN', 'DOE', 'joh@doe.com', '[{\"value\":\"headache\"}]', 'Flu', 'Pending', '2025-03-01 11:59:11'),
(3, 'Student', '87654321', '', '', '', '[{\"value\":\"headache\"}]', 'Flu', 'Pending', '2025-03-01 16:05:54'),
(4, 'Student', '87654321', '', '', '', '[{\"value\":\"head\"}]', 'Flu', 'Pending', '2025-03-01 16:07:56'),
(5, 'Professor', '', 'JOHN', 'DOE', 'jon@fmail.com', '[{\"value\":\"head\"}]', 'Flu', 'Pending', '2025-03-01 16:15:18'),
(6, 'Student', '2', '', '', '', '[{\"value\":\"headache\"}]', 'Flu', 'Pending', '2025-03-01 21:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `admit`
--

CREATE TABLE `admit` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `year_level` varchar(100) NOT NULL,
  `section` int(255) NOT NULL,
  `disease` varchar(255) NOT NULL,
  `medicine` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admit`
--

INSERT INTO `admit` (`id`, `type`, `firstname`, `lastname`, `middlename`, `course`, `year_level`, `section`, `disease`, `medicine`, `brand`, `dosage`, `date`) VALUES
(6, 'Professor', 'john', 'cruz', 'dela', 'BSCRIM', '3rd Year', 5, 'cough', 'Cough', 'Solmux', '500mg', '2025-02-27'),
(7, 'Other', 'jay', 'simon', 'cruz', 'ABM', 'Grade 11', 3, 'cough', 'Paracetamol', 'Biogesic', '350mg', '2025-02-03'),
(9, 'Student', 'ariel', 'forcado', 'cruz', 'P.A', 'Grade 12', 2, 'sick', 'Paracetamol', 'Biogesic', '500mg', '2025-02-19'),
(10, 'Student', 'francis', 'cruz', 'javar', 'BSIT', '1st Year', 2, 'sick', 'Paracetamol', 'Biogesic', '250mg', '2025-02-20');

-- --------------------------------------------------------

--
-- Table structure for table `courses_strands`
--

CREATE TABLE `courses_strands` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `year_level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses_strands`
--

INSERT INTO `courses_strands` (`id`, `code`, `name`, `description`, `year_level_id`) VALUES
(1, 'STEM', 'Science, Technology, Engineering, and Mathematics', 'Science, Technology, Engineering, and Mathematics', 1),
(2, 'ABM', 'Accountancy, Business and Management', 'Accountancy, Business and Management', 1),
(3, 'HUMSS', 'Humanities and Social Sciences', 'Humanities and Social Sciences', 1),
(4, 'HE', 'Home Economics', 'Home Economics', 1),
(5, 'GAS', 'General Academic Strand', 'General Academic Strand', 1),
(6, 'ICT', 'Information and Communication Technology', 'Information and Communication Technology', 1),
(7, 'PA', 'Performing Arts', 'Performing Arts', 1),
(8, 'BSIT', 'Bachelor of Science in Information Technology', 'Bachelor of Science in Information Technology', 2),
(9, 'BSCRIM', 'Bachelor of Science in Criminology', 'Bachelor of Science in Criminology', 2),
(10, 'BLIS', 'Bachelor of Library Information Science', 'Bachelor of Library Information Science', 2),
(11, 'BSHM', 'Bachelor of Science in Hospitality Management', 'Bachelor of Science in Hospitality Management', 2),
(12, 'BSENTREP', 'Bachelor of Science in Entrepreneurship', 'Bachelor of Science in Entrepreneurship', 2),
(13, 'BSBA', 'Bachelor of Science in Business Administration', 'Bachelor of Science in Business Administration', 2),
(14, 'BSAIS', 'Bachelor of Science in Accounting Information System', 'Bachelor of Science in Accounting Information System', 2),
(15, 'BSOA', 'Bachelor of Science in Office Administration', 'Bachelor of Science in Office Administration', 2),
(16, 'BPED', 'Bachelor of Science in Physical Education', 'Bachelor of Science in Physical Education', 2),
(17, 'BTLED', 'Bachelor of Science in Technological & Livelihood Education', 'Bachelor of Science in Technological & Livelihood Education', 2),
(18, 'BEED', 'Bachelor of Science in Elementary Education', 'Bachelor of Science in Elementary Education', 2),
(19, 'BSED', 'Bachelor of Science in Secondary Education', 'Bachelor of Science in Secondary Education', 2),
(20, 'BSTM', 'Bachelor of Science in Tourism Management', 'Bachelor of Science in Tourism Management', 2),
(21, 'BSCpE', 'Bachelor of Science in Computer Engineering', 'Bachelor of Science in Computer Engineering', 2),
(22, 'BSP', 'Bachelor of Science in Psychology', 'Bachelor of Science in Psychology', 2);

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `id` int(11) NOT NULL,
  `admission_id` int(11) NOT NULL,
  `cbc` tinyint(1) DEFAULT 0,
  `cbc_result` text DEFAULT NULL,
  `xray` tinyint(1) DEFAULT 0,
  `xray_result` text DEFAULT NULL,
  `urine` tinyint(1) DEFAULT 0,
  `urine_result` text DEFAULT NULL,
  `schedule_time` datetime NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`id`, `admission_id`, `cbc`, `cbc_result`, `xray`, `xray_result`, `urine`, `urine_result`, `schedule_time`, `status`) VALUES
(1, 6, 1, '1_cbc_result.jpg', 1, '1_xray_result.jpg', 0, NULL, '2025-03-02 08:30:00', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `hospitalized` enum('Yes','No') DEFAULT 'No',
  `surgeries` enum('Yes','No') DEFAULT 'No',
  `medications` enum('Yes','No') DEFAULT 'No',
  `allergies` text DEFAULT NULL,
  `existing_conditions` text DEFAULT NULL,
  `doctors_notes` text DEFAULT NULL,
  `medical_report` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `student_id`, `hospitalized`, `surgeries`, `medications`, `allergies`, `existing_conditions`, `doctors_notes`, `medical_report`, `created_at`, `updated_at`) VALUES
(4, 2, 'No', 'No', 'No', 'NONE', 'NONE', 'NONE', '', '2025-03-01 15:08:31', '2025-03-01 15:08:31'),
(5, 3, 'No', 'No', 'No', 'NONE', 'NONE', 'NONE', '', '2025-03-01 15:13:28', '2025-03-01 15:13:28'),
(6, 2, 'No', 'No', 'No', 'NONE', 'NONE', 'NONE', '', '2025-03-01 15:39:52', '2025-03-01 15:39:52'),
(7, 2, 'No', 'No', 'No', 'NONE', 'NONE', 'NONE', '', '2025-03-01 15:44:30', '2025-03-01 15:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `medical_supplies`
--

CREATE TABLE `medical_supplies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_supplies`
--

INSERT INTO `medical_supplies` (`id`, `name`, `stock`, `supplier`, `created_at`) VALUES
(1, 'Hose', 10, 'HOSE', '2025-03-01 22:02:42'),
(2, 'Hose', 10, 'HOSE', '2025-03-01 22:02:45'),
(3, 'test', 10, 'test', '2025-03-01 22:07:49'),
(4, 'a', 10, '1a', '2025-03-01 22:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `category`, `expiry_date`) VALUES
(1, 'Mefenamic', 'Pain Reliever', '2030-02-28'),
(2, 'Diatabs', 'LBM', '2030-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_stocks`
--

CREATE TABLE `medicine_stocks` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction_type` enum('IN','OUT') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_stocks`
--

INSERT INTO `medicine_stocks` (`id`, `medicine_id`, `quantity`, `transaction_type`, `created_at`) VALUES
(1, 1, 12, 'IN', '2025-02-26 14:24:47');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `student_number` varchar(20) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `year_level` varchar(255) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `parent_contact` varchar(20) DEFAULT NULL,
  `disability_status` varchar(50) DEFAULT NULL,
  `illness` varchar(50) DEFAULT NULL,
  `allergies` varchar(255) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `cbc` varchar(255) DEFAULT NULL,
  `urine` varchar(255) DEFAULT NULL,
  `xray` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `student_number` varchar(20) DEFAULT NULL,
  `course` int(11) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `birthdate` varchar(20) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `parent_contact` varchar(20) DEFAULT NULL,
  `disability_status` varchar(50) DEFAULT NULL,
  `illness` varchar(50) DEFAULT NULL,
  `status` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `firstname`, `lastname`, `middlename`, `student_number`, `course`, `year_level`, `section`, `birthdate`, `age`, `gender`, `address`, `email`, `contact_number`, `parent_contact`, `disability_status`, `illness`, `status`) VALUES
(32, 'CHARMAINE LOUISE', 'CATOR', 'DE JESUS', '002299', 1, 1, NULL, '1999-06-22', 25, NULL, 'CALOOCAN CITY', '', '09392878727', '09123456789', NULL, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','nurse','staff','patient') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'John Doe', 'johndoe@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'admin', 'active', '2025-02-27 15:14:12'),
(2, 'Jane Smith', 'janesmith@example.com', '482c811da5d5b4bc6d497ffa98491e38', '', 'active', '2025-02-28 04:00:00'),
(3, 'Robert Brown', 'robertbrown@example.com', 'bb77d0d3b3f239fa5db73bdf27b8d29a', '', 'inactive', '2025-02-28 06:30:00'),
(4, 'Alice Johnson', 'alicejohnson@example.com', '78d03b2810a74e5751c02db550798676', 'admin', 'active', '2025-02-28 08:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `year_levels`
--

CREATE TABLE `year_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_college` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_levels`
--

INSERT INTO `year_levels` (`id`, `name`, `is_college`) VALUES
(1, 'Grade 11', 0),
(2, 'Grade 12', 0),
(3, 'First Year', 1),
(4, 'Second Year', 1),
(5, 'Third Year', 1),
(6, 'Fourth Year', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admit`
--
ALTER TABLE `admit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses_strands`
--
ALTER TABLE `courses_strands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `year_level_id` (`year_level_id`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admission_id` (`admission_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `medical_supplies`
--
ALTER TABLE `medical_supplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_stocks`
--
ALTER TABLE `medicine_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_students_year_level` (`year_level`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admit`
--
ALTER TABLE `admit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `courses_strands`
--
ALTER TABLE `courses_strands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `lab_tests`
--
ALTER TABLE `lab_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `medical_supplies`
--
ALTER TABLE `medical_supplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medicine_stocks`
--
ALTER TABLE `medicine_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `year_levels`
--
ALTER TABLE `year_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses_strands`
--
ALTER TABLE `courses_strands`
  ADD CONSTRAINT `courses_strands_ibfk_1` FOREIGN KEY (`year_level_id`) REFERENCES `year_levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD CONSTRAINT `lab_tests_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine_stocks`
--
ALTER TABLE `medicine_stocks`
  ADD CONSTRAINT `medicine_stocks_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_year_level` FOREIGN KEY (`year_level`) REFERENCES `year_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
