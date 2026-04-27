-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2026 at 03:06 AM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wmsu_union_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_content`
--

CREATE TABLE `about_content` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `p1` text NOT NULL,
  `p2` text NOT NULL,
  `p3` text NOT NULL,
  `image_path` varchar(255) DEFAULT 'img/about.jpg',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_content`
--

INSERT INTO `about_content` (`id`, `section_name`, `heading`, `p1`, `p2`, `p3`, `image_path`, `updated_at`) VALUES
(1, 'about_union', 'Upholding Faculty Rights and Academic Freedom', 'The WMSU Faculty Union is a united and independent organization dedicated to protecting the rights and welfare of the academic personnel.\nOur union serves as a strong collective voice, striving to ensure equitable access to professional development.\nWe are committed to defending academic freedom and fostering solidarity.', '', '', 'img/about.jpg', '2026-04-20 11:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `admin_videos`
--

CREATE TABLE `admin_videos` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_type` enum('youtube','raw') NOT NULL,
  `video_source` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_videos`
--

INSERT INTO `admin_videos` (`id`, `video_title`, `video_type`, `video_source`, `thumbnail`, `created_at`) VALUES
(1, 'TEAM UNITY WITH CARE 2 0 ', 'youtube', 'https://www.youtube.com/embed/_f48t-J88yU', NULL, '2026-04-22 09:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` int(11) NOT NULL,
  `award_title` varchar(255) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `award_image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `award_year` year(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`id`, `award_title`, `recipient_name`, `award_image`, `description`, `award_year`, `created_at`) VALUES
(2, 'kupal award', 'danch', 'uploads/awards/1776823752_327744610_733922131720223_8935806063694028935_n.jpg', 'rdtfhjkljghfcjkhlityfhbyukgv', '2026', '2026-04-22 02:09:12'),
(3, 'kupals award', 'danc', 'uploads/awards/1776824217_328258926_849097926181436_2427018608687281411_n.jpg', 'lljhtgghfghjkputaehxfhvjbkllfgfdv', '2026', '2026-04-22 02:16:57'),
(4, 'pabida award', 'danchio', 'uploads/awards/1776824266_476080601_1180706744062389_5378603482475314303_n.jpg', 'srdytgihopioiuytjfjxhcvjkjopouyudtkghvjkbljoytyutddhjgi;itluketydhgjgiyety', '2026', '2026-04-22 02:17:46'),
(5, 'pabida awards', 'danchion', 'uploads/awards/1776824333_475872352_1056083876564843_4556632453379750438_n.jpg', 'hkkjggggggggggggggggggggggggggggggggggg', '2026', '2026-04-22 02:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(100) NOT NULL,
  `hours` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `facebook_url` varchar(255) NOT NULL,
  `facebook_name` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `address`, `phone`, `hours`, `email`, `facebook_url`, `facebook_name`, `updated_at`) VALUES
(1, 'Faculty Union Office, Western Mindanao State University, Normal Rd, Zamboanga City, 7000', '+63 62 991 1040', 'Mon - Fri: 8:00 AM - 5:00 PM', 'facultyunion@wmsu.edu.ph', 'https://www.facebook.com/WMSUFacultyUnion', 'WMSU Faculty Union', '2026-04-21 22:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT 'img/event-default.jpg',
  `description` text NOT NULL,
  `event_dates` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `event_time` varchar(100) NOT NULL,
  `admission` varchar(100) DEFAULT 'Free Entry',
  `features` varchar(255) DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `subtitle`, `banner_path`, `description`, `event_dates`, `location`, `event_time`, `admission`, `features`, `highlights`, `created_at`) VALUES
(1, 'Dan Chion', 'danley kwero', 'img/1776810325_Screenshot 2024-11-29 045023.png', 'trtikdcvhbjkfcghjnkmfghjk rdftvbjnmk,trtfygjhdxcvgbntrtghbn xcvbnmsdfghjxcvbnmdsf ghjkm  xcvybinoertvbunxxcvbhmktfghjk', 'Dec 09-2028', 'Faculty Union Hall', '9:00am - 8:00pm', 'Free Entry', 'dfghjklxcvbnm', 'fcgbhjklfgvhbjml,fcgvbjnkxgvbm', '2026-04-21 22:25:25'),
(2, 'Chiong', 'Danch', 'img/1776819302_Screenshot 2025-12-17 222615.png', 'waertfyuiopyedrutghujioprtvubituxctvbiu', 'Dec 09-2028', 'Faculty Union Hall', '9:00am - 8:00pm', 'Free Entry', 'dfghjklxcvbnm', 'rwetrbumobueyervnml,oiuytuyatwunmoihyfsyatryuiompjhtgehyiojhudraewtrybuyurttwety', '2026-04-22 00:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE `objectives` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objectives`
--

INSERT INTO `objectives` (`id`, `content`, `sort_order`) VALUES
(1, 'To defend and advance academic freedom and shared governance in WMSU;', 1),
(2, 'To foster solidarity, collaboration, linkages, partnership, and sense of community;', 2),
(3, 'To promote faculty participation in WMSU’s institutional governance;', 3),
(4, 'To advance the rights and welfare of the academic personnel;', 4),
(5, 'To promote fair environment and protect faculty from arbitrary decisions;', 5),
(6, 'To improve the status and conditions of faculty members.', 6),
(7, 'To defend and advance academic freedom and shared governance in WMSU;', 1),
(8, 'To foster solidarity, collaboration, linkages, partnership, and sense of community;', 2),
(9, 'To promote faculty participation in WMSU’s institutional governance;', 3),
(10, 'To advance the rights and welfare of the academic personnel;', 4),
(11, 'To promote fair environment and protect faculty from arbitrary decisions;', 5),
(12, 'To improve the status and conditions of faculty members.', 6);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `department_acronym` varchar(20) DEFAULT NULL,
  `category` enum('Executive','Finance') DEFAULT 'Executive',
  `rank` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `full_name`, `position`, `department_acronym`, `category`, `rank`) VALUES
(1, 'Dr. Adrian P. Semorlan', 'President', 'CLA', 'Executive', 1),
(2, 'Prof. Harry Subibi', 'Vice President', 'CTE', 'Executive', 2),
(3, 'Prof. Evelyn Angeles', 'Secretary', 'COE', 'Executive', 3),
(4, 'Dr. Cheryl Barredo', 'Treasurer', 'CLA', 'Executive', 4),
(5, 'Prof. Erwin Alonzo', 'Auditor', 'CSM', 'Executive', 5),
(6, 'Prof. Victor Pagal', 'PIO', 'ESU', 'Executive', 6),
(7, 'Dr. Mervyn Garingo', 'Project Manager', 'CTE', 'Executive', 7),
(8, 'Prof. Patrick Brown', 'Finance Officer I', 'CHE', 'Finance', 8),
(9, 'Prof. Mai Gonzales', 'Finance Officer II', 'CN', 'Finance', 9);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `logo_path` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `logo_path`, `updated_at`) VALUES
(1, 'Faculty Union', 'img/1776808510_Screenshot 2025-04-27 175224.png', '2026-04-22 08:14:58');

-- --------------------------------------------------------

--
-- Table structure for table `union_info`
--

CREATE TABLE `union_info` (
  `id` int(11) NOT NULL,
  `vision` text NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `union_info`
--

INSERT INTO `union_info` (`id`, `vision`, `updated_at`) VALUES
(1, 'A united and independent faculty union that cares for the rights and welfare of the WMSU FACULTY, with strong collective voice gearing towards equitable access to professional development, opportunities, and healthy working environment.', '2026-04-20 09:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `union_objectives`
--

CREATE TABLE `union_objectives` (
  `id` int(11) NOT NULL,
  `objective_text` varchar(500) NOT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `union_officers`
--

CREATE TABLE `union_officers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `department_code` varchar(50) DEFAULT NULL,
  `category` enum('executive','finance') DEFAULT 'executive',
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `union_vision_mission`
--

CREATE TABLE `union_vision_mission` (
  `id` int(11) NOT NULL,
  `type` enum('vision','mission') NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(2, 'admin', '$2y$10$s9VysicSbjyyhrYsvNt0NelAZHsU/bTg5K2kn7vfV6wKETzTv.WgG', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_name` (`section_name`);

--
-- Indexes for table `admin_videos`
--
ALTER TABLE `admin_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `union_info`
--
ALTER TABLE `union_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `union_objectives`
--
ALTER TABLE `union_objectives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `union_officers`
--
ALTER TABLE `union_officers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `union_vision_mission`
--
ALTER TABLE `union_vision_mission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_videos`
--
ALTER TABLE `admin_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `objectives`
--
ALTER TABLE `objectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `union_info`
--
ALTER TABLE `union_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `union_objectives`
--
ALTER TABLE `union_objectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `union_officers`
--
ALTER TABLE `union_officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `union_vision_mission`
--
ALTER TABLE `union_vision_mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
