-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 04:09 AM
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
  `admission_type` enum('Student','Professor','Other') NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `year_level_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `cbc` varchar(50) DEFAULT NULL,
  `urine` varchar(50) DEFAULT NULL,
  `xray` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Ongoing','Completed') NOT NULL DEFAULT 'Pending',
  `date_completed` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `applicable` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`id`, `name`, `description`, `brand`, `dosage`, `applicable`, `quantity`, `status`, `date`) VALUES
(24, 'Mefenamic', 'teeth pain', 'Ponstan', '250mg', 'Adult', 2, 'Have Stock', '2025-06-19');

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
(1, 'loy', 'abargos', 'bacuetes', '2122323', 0, NULL, '001', '', 23, 'Male', 'bulacan', 'abargosangelo5@gmail.com', '092333456766', '09444467432', 'PWD', 'none', ''),
(19, 'gelo', 'cruz', 'dela', '343434', 0, NULL, '003', '', 23, 'Male', 'bulacan', 'geloabargos@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(22, 'ramon', 'cruz', 'bacuetes', '21086656', 0, NULL, '001', '', 23, 'Male', 'bulacan', 'ramonx@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(23, 'james', 'lebron', 'cruz', '21012923', 0, NULL, '003', '', 24, 'Male', 'bulacan', 'jame.lebren@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(24, 'angelo', 'abargos', 'bacuetes', '21034556', 0, NULL, '003', '', 25, 'Male', 'bulacan', 'geloabargos@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(26, 'john', 'abargos', 'bacuetes', '21034555', 0, NULL, '003', '', 24, 'Male', 'bulacan', 'john@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(27, 'ariel', 'abargos', 'cruz', '21023344', 0, NULL, '003', '', 25, 'Male', 'bulacan', 'ariel24@gmail.com', '09343434343', '09444467432', 'Normal', 'none', ''),
(28, 'gelo', 'stuart', 'campo', '21012823', 0, NULL, '003', '', 25, 'Male', 'bulacan', 'campo12@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(29, 'angelo', 'simon', 'cruz', '21097676', 0, NULL, '001', '', 23, 'Male', 'bulacan', 'abargosangelo5@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(30, 'angelo', 'simon', 'cruz', '21074534', 0, NULL, '001', '', 34, 'Male', 'bulacan', 'abargosangelo5@gmail.com', '092333456766', '09444467432', 'Normal', 'none', ''),
(31, 'CHARMAINE LOUISE', 'CATOR', 'DE JESUS', '0092138', 1, 1, NULL, '1999-06-22', 25, NULL, 'CALOOCAN CITY', '', '09123456789', '0912345678', NULL, NULL, 'Active');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admissions_student` (`student_id`),
  ADD KEY `fk_admissions_year_level` (`year_level_id`),
  ADD KEY `fk_admissions_course` (`course_id`);

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
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `year_levels`
--
ALTER TABLE `year_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admissions`
--
ALTER TABLE `admissions`
  ADD CONSTRAINT `fk_admissions_course` FOREIGN KEY (`course_id`) REFERENCES `courses_strands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_admissions_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_admissions_year_level` FOREIGN KEY (`year_level_id`) REFERENCES `year_levels` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses_strands`
--
ALTER TABLE `courses_strands`
  ADD CONSTRAINT `courses_strands_ibfk_1` FOREIGN KEY (`year_level_id`) REFERENCES `year_levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_year_level` FOREIGN KEY (`year_level`) REFERENCES `year_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
