-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 29, 2026 at 08:29 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u965090405_zankolportal_d`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_answers`
--

CREATE TABLE `ai_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` text NOT NULL,
  `score` double(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_questions`
--

CREATE TABLE `ai_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_ku` varchar(255) NOT NULL,
  `question_en` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `weight` int(11) NOT NULL DEFAULT 1,
  `department_weights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`department_weights`)),
  `order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_rankings`
--

CREATE TABLE `ai_rankings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `score` double(5,2) NOT NULL DEFAULT 0.00,
  `rank` int(11) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL,
  `match_factors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`match_factors`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE `backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `database_type` varchar(255) NOT NULL DEFAULT 'mysql',
  `source_db` varchar(255) DEFAULT NULL,
  `target_db` varchar(255) DEFAULT NULL,
  `tables_count` int(11) NOT NULL DEFAULT 0,
  `records_count` int(11) NOT NULL DEFAULT 0,
  `file_size` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `university_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `geojson` longtext DEFAULT NULL,
  `lat` double(10,6) DEFAULT NULL,
  `lng` double(10,6) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`id`, `university_id`, `name`, `name_en`, `geojson`, `lat`, `lng`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'کۆلێژی ئەندازیاری', 'College of Engineering', '\"{\\\"type\\\":\\\"FeatureCollection\\\",\\\"features\\\":[{\\\"type\\\":\\\"Feature\\\",\\\"properties\\\":[],\\\"geometry\\\":{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.021434,36.139401],[44.026691,36.139748],[44.027098,36.140839],[44.027871,36.141896],[44.029588,36.142849],[44.028922,36.14758],[44.020296,36.14453]]]}}]}\"', 36.143987, 44.024888, '/uploads/media_6917a0230a4de.jpeg', 1, '2025-11-01 10:27:34', '2025-11-14 21:33:23'),
(2, 1, 'کۆلێژی زانست', 'College of Science', '\"{\\\"type\\\":\\\"FeatureCollection\\\",\\\"features\\\":[{\\\"type\\\":\\\"Feature\\\",\\\"properties\\\":[],\\\"geometry\\\":{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.022925,36.154656],[44.018204,36.153928],[44.018365,36.152529],[44.018912,36.152651],[44.019234,36.151447],[44.023569,36.152218]]]}}]}\"', 36.153134, 44.020860, '/uploads/media_6917a05ab3e42.jpeg', 1, '2025-11-01 10:27:34', '2025-11-14 21:34:18'),
(3, 5, 'کۆلێژی تەکنیکی ئەندازیاری هەولێر', 'Erbil Technical Engineering College', NULL, 36.142372, 44.035263, '/uploads/media_6905e444732fe.webp', 1, '2025-11-01 10:43:16', '2025-11-01 10:43:16'),
(4, 2, 'کۆلێژی پزیشکی', 'College of Medicine', NULL, 36.198234, 44.019362, '/uploads/media_6906d25196848.jpg', 1, '2025-11-02 03:38:57', '2025-11-02 03:38:57'),
(5, 2, 'کۆلێژی پزیشکی ددان', 'College of Dentistry', NULL, 36.200114, 44.020873, '/uploads/media_6906d3cb4c0f3.jpg', 1, '2025-11-02 03:45:15', '2025-11-02 03:45:15'),
(6, 2, 'دەرمانسازی', 'Pharmaceuticals', NULL, 36.190932, 44.040003, '/uploads/media_6906d450980be.jpg', 1, '2025-11-02 03:47:28', '2025-11-02 03:47:28'),
(7, 2, 'کۆلێژی پەرستارى', 'College of Nursing', NULL, 36.198992, 44.020839, '/uploads/media_6906d55e1f177.png', 1, '2025-11-02 03:51:58', '2025-11-02 03:51:58'),
(8, 2, 'زانسته‌ ته‌ندروستیه‌كان', 'Health Sciences', NULL, 36.190140, 44.039850, '/uploads/media_6906d644e4b64.png', 1, '2025-11-02 03:55:48', '2025-11-02 03:55:48'),
(9, 1, 'کۆلێژی زانستە ئیسلامییەکان', 'College of Islamic Sciences', NULL, 36.144616, 44.026075, '/uploads/media_6917a0bdd034f.jpeg', 1, '2025-11-02 03:58:33', '2025-11-14 21:35:57'),
(10, 1, 'کۆلێژی پەروەردەی / شەقلاوە', 'College of Education / Shaqlawa', NULL, 36.414567, 44.308922, '/uploads/media_6917a11a74722.jpeg', 1, '2025-11-02 04:00:32', '2025-11-14 21:37:30'),
(11, 1, 'کۆلێژی پزیشکی ڤێتێنەری', 'College of Veterinary Medicine', NULL, 36.187264, 44.018512, '/uploads/media_6917a2adeee21.jpeg', 1, '2025-11-02 04:01:49', '2025-11-14 21:44:13'),
(12, 1, 'کۆلێژی پەروەردەی / مەخمور', 'College of Education / Makhmur', NULL, 36.185073, 44.003991, '/uploads/media_6906d804d3555.jpg', 1, '2025-11-02 04:03:16', '2025-11-02 04:03:16'),
(13, 1, 'کۆلێژی زمان', 'College of Language', NULL, 36.158650, 44.014189, '/uploads/media_6906d864a0f02.png', 1, '2025-11-02 04:04:52', '2025-11-02 04:04:52'),
(14, 1, 'کۆلێژی یاسا', 'College of Law', NULL, 36.143392, 44.027758, '/uploads/media_690761eab826c.png', 1, '2025-11-02 13:51:38', '2025-11-02 13:51:38'),
(15, 1, 'کۆلێژی زانستە سیاسیەکان', 'College of Political Science', NULL, 36.146471, 44.026093, '/uploads/media_690762684428a.png', 1, '2025-11-02 13:53:44', '2025-11-02 13:53:44'),
(16, 1, 'کۆلێژی زانستی کۆمپیوتەر و ئینفۆرماتیکس', 'College of Computer Science and Informatics', NULL, 36.152475, 44.020173, NULL, 1, '2025-11-02 13:55:55', '2025-11-02 13:55:55'),
(17, 1, 'کۆلێژی کارگێری و ئابووری', 'College of Management and Economics', NULL, 36.179556, 44.018439, '/uploads/media_690763d7519c0.jpg', 1, '2025-11-02 13:59:51', '2025-11-02 13:59:51'),
(18, 1, 'کۆلێژی ئاداب', 'College of Arts', NULL, 36.158636, 44.012467, '/uploads/media_690765183ecf3.png', 1, '2025-11-02 14:05:12', '2025-11-02 14:05:12'),
(19, 1, 'کۆلێژی هونەرە جوانەکان', 'College of Fine Arts', NULL, 36.152991, 44.016181, '/uploads/media_690765ad2a682.png', 1, '2025-11-02 14:07:41', '2025-11-02 14:07:41'),
(20, 1, 'کۆلێژی پەروەردە', 'College of Education', NULL, 36.162307, 44.016384, '/uploads/media_6907661346587.jpg', 1, '2025-11-02 14:09:23', '2025-11-02 14:09:23'),
(21, 1, 'کۆلێژی پەروەردەی جەستەیی و زانستە وەرزشیەکان', 'College of Physical Education and Sport Sciences', NULL, 36.191028, 44.009900, '/uploads/media_6907679c3e486.jpg', 1, '2025-11-02 14:15:56', '2025-11-02 14:15:56'),
(22, 1, 'کۆلێژی پەروەردەی بنەڕەتی', 'College of Elementary Education', NULL, 36.147073, 44.027946, '/uploads/media_6907687f11d01.png', 1, '2025-11-02 14:19:43', '2025-11-02 14:19:43'),
(23, 1, 'کۆلێژی زانستە ئەندازیارییە کشتوڵییەکان', 'College of Agricultural Engineering Sciences', NULL, 36.162951, 44.012628, '/uploads/media_690769092210f.jpg', 1, '2025-11-02 14:22:01', '2025-11-02 14:22:01'),
(24, 3, 'فاکەڵتی پزیشکی', 'Faculty of Medicine', NULL, 36.088844, 44.649400, '/uploads/media_690769ee62182.png', 1, '2025-11-02 14:25:50', '2025-11-02 14:25:50'),
(25, 3, 'فاکەڵتی ئەندازیاری', 'Faculty of Engineering', NULL, 36.098840, 44.658262, '/uploads/media_69076be7b3ef6.png', 1, '2025-11-02 14:32:30', '2025-11-02 14:34:15'),
(26, 3, 'فاکەڵتی زانستە مرۆڤایەتی و کۆمەلایەتیەکان', 'Faculty of Humanities and Social Sciences', '\"{\\\"type\\\":\\\"FeatureCollection\\\",\\\"features\\\":[{\\\"type\\\":\\\"Feature\\\",\\\"properties\\\":[],\\\"geometry\\\":{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.65547,36.096668],[44.655095,36.096425],[44.655975,36.095662],[44.656296,36.095922]]]}}]}\"', 36.096272, 44.655509, '/uploads/media_69076d8eb88ca.jpg', 1, '2025-11-02 14:41:18', '2025-11-02 14:43:24'),
(27, 3, 'فاکەڵتی پەروەردە', 'Faculty of Education', NULL, 36.096067, 44.655637, '/uploads/media_69076e67a754c.jpg', 1, '2025-11-02 14:44:55', '2025-11-02 14:44:55'),
(28, 3, 'فاکەڵتی پەروەردەی جەستەیی و زانستە وەرزشیەکان', 'Faculty of Physical Education and Sport Sciences', NULL, 36.102034, 44.647796, NULL, 1, '2025-11-02 14:47:33', '2025-11-02 14:47:33'),
(29, 4, 'فاکەڵتی ئەندازیاریی', 'Faculty of Enginering', NULL, 36.696780, 44.526736, NULL, 1, '2025-11-02 14:53:44', '2025-11-02 14:53:44'),
(30, 4, 'فاکەڵتی زانست', 'Faculty of Science', NULL, 36.696957, 44.527140, NULL, 1, '2025-11-02 14:56:30', '2025-11-02 14:56:30'),
(31, 4, 'فاکەڵتی پەروەردەی', 'Faculty of Educationi', '\"{\\\"type\\\":\\\"FeatureCollection\\\",\\\"features\\\":[{\\\"type\\\":\\\"Feature\\\",\\\"properties\\\":{},\\\"geometry\\\":{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[36.684882452915716,44.537161028079],[36.683738140855795,44.537042989822325],[36.6837467273527,44.53810513675943],[36.68492545589479,44.53815881853715]]]}}]}\"', 36.684347, 44.537447, '/uploads/media_690772816f8d2.png', 1, '2025-11-02 15:00:51', '2025-11-02 15:02:25'),
(32, 4, 'فاکەڵتی ئاداب', 'Faculty of Arts', NULL, 36.698879, 44.529222, '/uploads/media_690772e940311.jpg', 1, '2025-11-02 15:04:09', '2025-11-02 15:04:09'),
(33, 4, 'فاکەڵتی یاسا و زانستە سیاسیەکان و بەێوەبردن', 'Faculty of Law, Political Sciences and Management', NULL, 36.701129, 44.532083, NULL, 1, '2025-11-02 15:06:14', '2025-11-02 15:06:14'),
(34, 5, 'پەیمانگەی تەکنیکی چۆمان', 'Choman Technical Institute', NULL, 36.636779, 44.874799, '/uploads/media_69077a8db815a.png', 1, '2025-11-02 15:36:45', '2025-11-02 15:36:45'),
(35, 5, 'کۆلێژی تەکنەلۆجی هەولێر', 'Erbil Technology College', NULL, 36.143637, 44.018690, NULL, 1, '2025-11-02 15:41:20', '2025-11-02 15:41:20'),
(36, 5, 'پەیمانگەی تەکنیکی کارگێری هەولێر', 'Erbil Administrative Technical Institute', NULL, 36.143615, 44.016425, NULL, 1, '2025-11-02 15:44:26', '2025-11-02 15:44:26'),
(37, 5, 'کۆلێژی تەکنیکی کارگێری هەولێر', 'Erbil Technical College of Management', NULL, 36.142391, 44.036203, NULL, 1, '2025-11-02 15:46:35', '2025-11-02 15:46:35'),
(38, 5, 'کۆلێژی تەکنیکی تەندروستی و پزیشکی هەولێر', 'Erbil Technical College of Health and Medicine', NULL, 36.145045, 44.015547, '/uploads/media_69077de950967.jpg', 1, '2025-11-02 15:51:05', '2025-11-02 15:51:05'),
(39, 5, 'کۆلێژی تەکنیکی ئەندازیاری ئینفۆرماتیک و کۆمپیوتەر', 'Technical College of Engineering Engineering and Computer Engineering', NULL, 36.143603, 44.019198, NULL, 1, '2025-11-02 15:53:32', '2025-11-02 15:53:32'),
(40, 5, 'پەیمانگەی تەکنیکی خەبات', 'Khabat Technical Institute', NULL, 36.265312, 43.654317, '/uploads/media_69077ee51f2da.jpg', 1, '2025-11-02 15:55:17', '2025-11-02 15:55:17'),
(41, 5, 'پەیمانگەی تەکنیکی کۆیە', 'Koya Technical Institute', NULL, 36.074919, 44.636877, '/uploads/media_69077fdc1f718.jpg', 1, '2025-11-02 15:59:24', '2025-11-02 15:59:24'),
(42, 5, 'پەیمانگەی تەکنیکی پزیشکی هەولێر', 'Erbil Medical Technical Institute', NULL, 36.150927, 44.036121, NULL, 1, '2025-11-02 16:03:11', '2025-11-02 16:03:11'),
(43, 5, 'پەیمانگەی تەکنیکی مێرگەسۆر', 'Mergasur Technical Institute', NULL, 36.825844, 44.315526, NULL, 1, '2025-11-02 16:04:25', '2025-11-02 16:04:25'),
(44, 5, 'کۆلێژی تەکنیکی شەقڵاوە', 'Shaqlawa Technical College', NULL, 36.407645, 44.309886, '/uploads/media_690781c8232af.png', 1, '2025-11-02 16:07:36', '2025-11-02 16:07:36'),
(45, 5, 'کۆلێژی تەکنیکی سۆران', 'Soran Technical College', NULL, 36.657689, 44.541407, '/uploads/media_690783114b9aa.png', 1, '2025-11-02 16:13:05', '2025-11-02 16:13:05'),
(46, 3, 'فاکەڵتی زانست و تەندروستی', 'Faculty of Science and Health', NULL, 36.097106, 44.657034, NULL, 1, '2025-11-15 19:08:20', '2025-11-15 19:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_id` bigint(20) UNSIGNED NOT NULL,
  `province_id` bigint(20) UNSIGNED NOT NULL,
  `university_id` bigint(20) UNSIGNED NOT NULL,
  `college_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `local_score` double(8,2) NOT NULL DEFAULT 50.00,
  `external_score` double(8,2) NOT NULL DEFAULT 50.00,
  `type` enum('زانستی','وێژەیی','زانستی و وێژەیی') NOT NULL DEFAULT 'زانستی',
  `sex` varchar(255) NOT NULL,
  `lat` double(10,6) DEFAULT NULL,
  `lng` double(10,6) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `system_id`, `province_id`, `university_id`, `college_id`, `name`, `name_en`, `local_score`, `external_score`, `type`, `sex`, `lat`, `lng`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 'پرۆگرام سازى/ ئینفۆرماتكس', 'Software', 95.63, 96.63, 'زانستی', 'نێر', 36.144258, 44.023660, '<p><br></p>', '/uploads/media_696696d43833b.jpeg', 1, '2025-11-01 10:31:32', '2026-01-13 19:02:44'),
(3, 1, 1, 5, 3, 'ئەندازیاری سیستەمی زانیاری IS', 'Information System Engineering', 92.20, 92.60, 'زانستی', 'نێر', 36.142543, 44.036577, '<p><a href=\"https://road-map-uni.netlify.app/\">University Campus GIS - Interactive Map</a><br><br>خوێند لەم بەشە 4 ساڵە ، 8 وەرزە</p><p><br></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>یەکەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>یەکەم</u></span>&nbsp;: Stage 1 Semester 1</p><ol><li>Drawing Engineering</li><li>Computer</li><li>English 1</li><li>Infomation&nbsp;System</li><li>Kurdology</li></ol><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>یەکەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>دووەم</u>&nbsp;</span>: Stage 1 Semester 2</p><ol><li>AutoCad</li><li>Calculas 1</li><li>Fundamental of Programming with CPP</li><li>Computer Organizations and Digital Design (Logic)</li><li>English 2</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>دووەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>سێ</u>&nbsp;</span>: Stage 2 Semester 3</p><ol><li>Calcuals 2</li><li>Introduction to Problem solving and Object Oriented Programming OOP</li><li>Design New Media</li><li>Principles of Electronic Circuit</li><li>Web Design</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>دووەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>چوار</u>&nbsp;</span>: Stage 2 Semester 4</p><ol><li>Adv. OOP</li><li>Computer Architecture</li><li>Engineering Statistics</li><li>Multimedia System</li><li>Web Development</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>سێ</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>پێنج</u>&nbsp;</span>: 5 Stage 3 Semester</p><ol><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Computational Theory</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Data Communication</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Data Structure</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Digital image and video processing</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Internet TechnologyInternet Technology</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>سێ</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>شەش</u>&nbsp;</span>: 6 Stage 3 Semester</p><ol><li>Applications of Data Communication</li><li>Engineering analysis NOT Practical</li><li>Mobile Application</li><li>Management and Organization NOT Practical</li><li>Database</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>چوار</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>حەوت</u>&nbsp;</span>: 7 Stage 4 Semester</p><ol><li>Artificial Intelligent (AI)</li><li>Database management system (DBMS)</li><li>Geographic Information System (GIS)</li><li>Information System Architecture (ISA)</li><li>Network Design &amp; Implementing (CCNA)</li></ol><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>چوار</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>هەشت</u>&nbsp;</span>: 8 Stage 4 Semester</p><ol><li>IR</li><li>IoT</li><li>Network<br>4. Security<br>5. Project</li></ol>', '/uploads/media_6905ea5f01f19.webp', 1, '2025-11-01 11:09:19', '2026-01-22 07:41:37'),
(4, 1, 1, 2, 4, 'پزیشکى', 'Medical', 97.79, 98.33, 'زانستی', 'نێر', 36.197800, 44.018008, '<p><br></p>', NULL, 1, '2025-11-02 03:42:10', '2025-11-02 03:42:10'),
(5, 2, 1, 2, 4, 'پزیشکى', 'Medical', 97.20, 97.67, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 03:42:56', '2025-11-02 16:19:27'),
(6, 1, 1, 2, 5, 'پزیشکی ددان', 'Dental Medicine', 96.67, 97.67, 'زانستی', 'نێر', 36.200392, 44.020988, '<p><br></p>', '/uploads/media_6907842f9dd61.png', 1, '2025-11-02 16:16:36', '2025-11-02 16:17:51'),
(7, 2, 1, 2, 5, 'پزیشکی ددان', 'Dental Medicine', 96.40, 96.80, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 16:19:08', '2025-11-02 16:19:08'),
(8, 1, 1, 2, 6, 'دەرمانسازى', 'Pharmaceuticals', 96.00, 96.67, 'زانستی', 'نێر', 36.190857, 44.039616, '<p><br></p>', NULL, 1, '2025-11-02 16:22:03', '2025-11-02 16:22:03'),
(9, 2, 1, 2, 6, 'دەرمانسازى', 'Pharmaceuticals', 95.78, 96.33, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 16:23:02', '2025-11-02 16:23:02'),
(10, 1, 1, 2, 7, 'پەرستاری', 'Nursing', 94.80, 95.80, 'زانستی', 'نێر', 36.189912, 44.039750, '<p><br></p>', NULL, 1, '2025-11-02 17:35:46', '2025-11-02 17:36:27'),
(11, 2, 1, 2, 7, 'پەرستاری', 'Nursing', 93.61, 94.61, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 17:37:26', '2025-11-02 17:37:26'),
(12, 1, 1, 2, 7, 'مامانی', 'Mamani', 89.42, 90.00, 'زانستی', 'مێ', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 17:47:11', '2025-11-02 17:47:11'),
(13, 2, 1, 2, 7, 'مامانی', 'Mamani', 87.10, 88.10, 'زانستی', 'مێ', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 17:54:40', '2025-11-02 17:54:40'),
(14, 1, 1, 2, 8, 'كیمیایی ژیانی', 'chemistry of life', 91.60, 92.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 17:57:29', '2025-11-02 17:57:29'),
(15, 2, 1, 2, 8, 'كیمیایی ژیانی', 'chemistry of life', 87.98, 88.33, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 17:58:13', '2025-11-02 17:58:13'),
(16, 1, 1, 2, 8, 'مایكرۆبایۆلۆجی', 'Microbiology', 94.40, 95.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 17:59:28', '2025-11-02 17:59:28'),
(17, 2, 1, 2, 8, 'مایكرۆبایۆلۆجی', 'Microbiology', 92.84, 93.23, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:00:08', '2025-11-02 18:00:08'),
(18, 1, 1, 2, 8, 'چارەسەرى سروشتى', 'Natural Treatment', 0.00, 0.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:02:04', '2025-11-02 18:02:04'),
(19, 2, 1, 2, 8, 'چارەسەرى سروشتى', 'Natural Treatment', 0.00, 0.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:02:20', '2025-11-02 18:02:20'),
(20, 1, 1, 2, 8, 'خۆراكزانی و پارێزی', 'Nutrition and Protection', 84.37, 85.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:03:27', '2025-11-02 18:03:27'),
(21, 2, 1, 2, 8, 'خۆراكزانی و پارێزی', 'Nutrition and Protection', 81.40, 82.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:03:50', '2025-11-02 18:04:24'),
(22, 1, 1, 2, 8, 'تەندروستى گشتى', 'General Health', 0.00, 0.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:05:30', '2025-11-02 18:05:30'),
(23, 2, 1, 2, 8, 'تەندروستى گشتى', 'General Health', 0.00, 0.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:06:05', '2025-11-02 18:06:05'),
(24, 2, 1, 1, 1, 'پرۆگرام سازى و ئینفۆرماتكس', 'Programming', 95.07, 96.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_6966972761b9d.jpeg', 1, '2025-11-02 18:12:35', '2026-01-13 19:04:07'),
(25, 1, 1, 1, 1, 'تەلارسازى', 'Architecture', 95.65, 96.10, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_696697c9e1fdb.jpeg', 1, '2025-11-02 18:13:30', '2026-01-13 19:06:49'),
(26, 2, 1, 1, 1, 'تەلارسازى', 'Architecture', 95.08, 95.68, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_696697e845b56.jpeg', 1, '2025-11-02 18:14:07', '2026-01-13 19:07:20'),
(27, 1, 1, 1, 1, 'شارستانى', 'Civil', 93.80, 94.80, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_6966981df3f35.jpeg', 1, '2025-11-02 18:16:18', '2026-01-13 19:08:14'),
(28, 2, 1, 1, 1, 'شارستانى', 'Civil', 92.70, 93.70, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_6966983d67fc9.jpeg', 1, '2025-11-02 18:16:51', '2026-01-13 19:08:45'),
(29, 1, 1, 1, 1, 'میکانیک و میكاترۆنیك', 'Mechanics and Mechatronics', 88.40, 89.00, 'زانستی', 'نێر', 36.144559, 44.021750, '<p><br></p>', '/uploads/media_6966986e9ae44.jpeg', 1, '2025-11-02 18:21:44', '2026-01-13 19:09:34'),
(30, 2, 1, 1, 1, 'میکانیک و میكاترۆنیك', 'Mechanics and Mechatronics', 86.23, 87.33, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_6966988abb988.jpeg', 1, '2025-11-02 18:22:27', '2026-01-13 19:10:02'),
(31, 1, 1, 1, 1, 'سەرچاوەکانى ئاو', 'Water sources', 75.16, 76.00, 'زانستی', 'نێر', 36.145521, 44.025205, '<p><br></p>', '/uploads/media_69669919dca73.jpeg', 1, '2025-11-02 18:23:47', '2026-01-13 19:12:25'),
(32, 2, 1, 1, 1, 'سەرچاوەکانى ئاو', 'Water sources', 72.07, 73.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_6966993655639.jpeg', 1, '2025-11-02 18:24:23', '2026-01-13 19:12:54'),
(33, 1, 1, 1, 1, 'كیمیا و پترۆ كیمیا', 'Chemistry and petrochemical chemistry', 86.12, 87.00, 'زانستی', 'نێر', 36.142222, 44.022616, '<p><br></p>', '/uploads/media_69669955b3831.jpeg', 1, '2025-11-02 18:26:04', '2026-01-13 19:13:25'),
(34, 2, 1, 1, 1, 'كیمیا و پترۆ كیمیا', 'Chemistry and petrochemical chemistry', 83.36, 84.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_6966997aa3ed7.jpeg', 1, '2025-11-02 18:27:19', '2026-01-13 19:14:02'),
(35, 1, 1, 1, 1, 'ڕووپێوان(جیۆماتیك)', 'Surveying (Gyomatic)', 81.92, 82.70, 'زانستی', 'نێر', 36.142584, 44.023467, '<p><br></p>', '/uploads/media_6966999f5deff.jpeg', 1, '2025-11-02 18:29:29', '2026-01-13 19:14:39'),
(36, 2, 1, 1, 1, 'ڕووپێوان(جیۆماتیك)', 'Surveying (Gyomatic)', 78.76, 79.50, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_696699bba4ef7.jpeg', 1, '2025-11-02 18:30:01', '2026-01-13 19:15:07'),
(37, 1, 1, 1, 1, 'ئه‌ندازيارى كاره‌با - تواناو وزه‌ نوێبوه‌كان', 'Electrical Engineer - Renewed Energy', 87.00, 88.00, 'زانستی', 'نێر', 36.143727, 44.022759, '<p><br></p>', '/uploads/media_696699e94e4b5.jpeg', 1, '2025-11-02 18:33:13', '2026-01-13 19:15:53'),
(38, 2, 1, 1, 1, 'ئه‌ندازيارى كاره‌با - تواناو وزه‌ نوێبوه‌كان', 'Electrical Engineer - Renewed Energy', 84.42, 85.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_69669a01bcb24.jpeg', 1, '2025-11-02 18:34:14', '2026-01-13 19:16:17'),
(39, 1, 1, 1, 1, 'ئه‌ندازيارى كاره‌با -كۆمپيوته‌ر و كۆنترۆل', 'Electrical Engineer - Computer and Control', 89.95, 90.30, 'زانستی', 'نێر', 36.143808, 44.022788, '<p><br></p>', '/uploads/media_69669a1dcdbd3.jpeg', 1, '2025-11-02 18:36:03', '2026-01-13 19:16:45'),
(40, 2, 1, 1, 1, 'ئه‌ندازيارى كاره‌با -كۆمپيوته‌ر و كۆنترۆل', 'Electrical Engineer - Computer and Control', 87.01, 88.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_69669a346a49d.jpeg', 1, '2025-11-02 18:36:34', '2026-01-13 19:17:08'),
(41, 1, 1, 1, 1, 'ئه‌ندازيارى كاره‌با - ئه‌لكترۆنيك و گه‌ياندن', 'Electrical Engineer - Electronics and Communications', 87.52, 88.90, 'زانستی', 'نێر', 36.143773, 44.022767, '<p><br></p>', '/uploads/media_69669a5a05e31.jpeg', 1, '2025-11-02 18:37:21', '2026-01-13 19:17:46'),
(42, 2, 1, 1, 1, 'ئه‌ندازيارى كاره‌با - ئه‌لكترۆنيك و گه‌ياندن', 'Electrical Engineer - Electronics and Communications', 85.39, 86.70, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', '/uploads/media_69669a776661e.jpeg', 1, '2025-11-02 18:37:49', '2026-01-13 19:18:15'),
(43, 1, 1, 1, 2, 'بایۆلۆجی /Molecular Genetics', 'Biology /Molecular Genetics', 89.08, 90.00, 'زانستی', 'نێر', 36.153233, 44.020095, '<p><br></p>', NULL, 1, '2025-11-02 18:39:54', '2025-11-02 18:39:54'),
(44, 2, 1, 1, 2, 'بایۆلۆجی /Molecular Genetics', 'Biology /Molecular Genetics', 85.83, 86.70, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:40:33', '2025-11-02 18:40:33'),
(45, 1, 1, 1, 2, 'بایۆلۆجی /بایۆلۆجی گشتی', 'Biology /General Biology', 88.00, 89.00, 'زانستی', 'نێر', 36.153844, 44.019687, '<p><br></p>', NULL, 1, '2025-11-02 18:41:21', '2025-11-02 18:43:06'),
(46, 2, 1, 1, 2, 'بایۆلۆجی /بایۆلۆجی گشتی', 'Biology /General Biology', 85.30, 86.00, 'زانستی', 'نێر', 36.153831, 44.019666, '<p><br></p>', NULL, 1, '2025-11-02 18:42:43', '2025-11-02 18:42:43'),
(47, 1, 1, 1, 2, 'بایۆلۆجی /بایۆلۆجی پزیشكی', 'Biology / Medical Biology', 90.98, 91.60, 'زانستی', 'نێر', 36.153827, 44.019655, '<p><br></p>', NULL, 1, '2025-11-02 18:48:43', '2025-11-02 18:48:43'),
(48, 2, 1, 1, 2, 'بایۆلۆجی /بایۆلۆجی پزیشكی', 'Biology / Medical Biology', 88.48, 89.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:49:19', '2025-11-02 18:49:19'),
(49, 1, 1, 1, 2, 'کیمیا', 'Chemistry', 82.48, 83.60, 'زانستی', 'نێر', 36.153268, 44.019462, '<p><br></p>', NULL, 1, '2025-11-02 18:50:21', '2025-11-02 18:50:21'),
(50, 2, 1, 1, 2, 'کیمیا', 'Chemistry', 78.68, 79.12, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:51:14', '2025-11-02 18:51:14'),
(51, 1, 1, 1, 2, 'فیزیك /فیزیای گشتی', 'Physics / General Physics', 74.43, 75.00, 'زانستی', 'نێر', 36.153701, 44.020755, '<p><br></p>', NULL, 1, '2025-11-02 18:52:09', '2025-11-02 18:52:09'),
(52, 2, 1, 1, 2, 'فیزیك /فیزیای گشتی', 'Physics / General Physics', 71.76, 72.65, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:52:37', '2025-11-02 18:52:37'),
(53, 1, 1, 1, 2, 'فیزیك /فیزیای پزیشكی', 'Physics / Medical Physics', 83.01, 85.00, 'زانستی', 'نێر', 36.153103, 44.019634, '<p><br></p>', NULL, 1, '2025-11-02 18:53:22', '2025-11-02 18:53:22'),
(54, 2, 1, 1, 2, 'فیزیك /فیزیای پزیشكی', 'Physics / Medical Physics', 78.82, 79.80, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:53:59', '2025-11-02 18:53:59'),
(55, 1, 1, 1, 2, 'ماتماتیك', 'Mathematics', 65.03, 66.00, 'زانستی', 'نێر', 36.152939, 44.020932, '<p><br></p>', NULL, 1, '2025-11-02 18:54:49', '2025-11-02 18:54:49'),
(56, 2, 1, 1, 2, 'ماتماتیك', 'Mathematics', 61.25, 62.50, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:55:16', '2025-11-02 18:55:16'),
(57, 1, 1, 1, 2, 'زانستەکانى زەوى و نەوت', 'ground and oil sciences', 65.78, 67.00, 'زانستی', 'نێر', 36.153138, 44.020111, '<p><br></p>', NULL, 1, '2025-11-02 18:56:16', '2025-11-02 18:56:16'),
(58, 2, 1, 1, 2, 'زانستەکانى زەوى و نەوت', 'ground and oil sciences', 63.23, 65.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:56:42', '2025-11-02 18:56:42'),
(59, 1, 1, 1, 2, 'زانستەکانى تەندروستى و ژینگە', 'Health and Environmental Sciences', 61.64, 63.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:58:00', '2025-11-02 18:58:00'),
(60, 2, 1, 1, 2, 'زانستەکانى تەندروستى و ژینگە', 'Health and Environmental Sciences', 58.37, 60.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 18:58:28', '2025-11-02 18:58:28'),
(61, 1, 1, 1, 9, 'شەریعە', 'Sharia', 58.16, 59.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:02:05', '2025-11-02 19:02:05'),
(62, 2, 1, 1, 9, 'شەریعە', 'Sharia', 56.46, 57.40, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:02:36', '2025-11-02 19:02:36'),
(63, 1, 1, 1, 9, 'خوێندنى ئیسلامى', 'Islamic Studies', 57.80, 58.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:04:00', '2025-11-02 19:04:00'),
(64, 2, 1, 1, 9, 'خوێندنى ئیسلامى', 'Islamic Studies', 53.69, 55.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:04:39', '2025-11-02 19:04:39'),
(65, 1, 1, 1, 9, 'په‌روه‌رده‌ی ئایینی', 'Religious Education', 59.23, 60.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:05:11', '2025-11-02 19:05:11'),
(66, 2, 1, 1, 9, 'په‌روه‌رده‌ی ئایینی', 'Religious Education', 54.29, 55.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:05:41', '2025-11-02 19:05:41'),
(67, 1, 1, 1, 9, 'ووتارخوێنى و ڕاگەیاندنى ئایینى', 'Religious Speaking and Media', 60.10, 62.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:06:14', '2025-11-02 19:06:14'),
(68, 2, 1, 1, 9, 'ووتارخوێنى و ڕاگەیاندنى ئایینى', 'Religious Speaking and Media', 53.67, 55.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:06:40', '2025-11-02 19:06:40'),
(69, 1, 1, 1, 10, 'زمانی کوردی', 'Kurdish Language', 62.50, 63.40, 'زانستی و وێژەیی', 'نێر', 36.413451, 44.317850, '<p><br></p>', NULL, 1, '2025-11-02 19:09:50', '2025-11-02 19:11:08'),
(70, 2, 1, 1, 10, 'زمانی کوردی', 'Kurdish Language', 59.15, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:10:36', '2025-11-02 19:10:36'),
(71, 1, 1, 1, 10, 'زمانی عه‌ره‌بی', 'Arabic Language', 65.00, 67.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:11:46', '2025-11-02 19:11:46'),
(72, 2, 1, 1, 10, 'زمانی عه‌ره‌بی', 'Arabic Language', 61.40, 63.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:12:12', '2025-11-02 19:20:11'),
(73, 1, 1, 1, 10, 'فیزیك', 'physics', 66.63, 67.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:13:15', '2025-11-02 19:13:15'),
(74, 2, 1, 1, 10, 'فیزیك', 'physics', 63.69, 65.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:13:45', '2025-11-02 19:13:45'),
(75, 1, 1, 1, 10, 'بایلۆجی', 'Biological', 72.39, 75.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:14:23', '2025-11-02 19:14:23'),
(76, 2, 1, 1, 10, 'بایلۆجی', 'Biological', 68.60, 70.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:14:51', '2025-11-02 19:14:51'),
(77, 1, 1, 1, 10, 'وه‌رزش', 'sports', 54.59, 55.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:15:27', '2025-11-02 19:16:04'),
(78, 2, 1, 1, 10, 'وه‌رزش', 'sports', 54.94, 55.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:15:55', '2025-11-02 19:15:55'),
(79, 1, 1, 1, 10, 'زمانی ئینگلیزى', 'English language', 75.20, 77.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:18:53', '2025-11-02 19:18:53'),
(80, 2, 1, 1, 10, 'زمانی ئینگلیزى', 'English language', 70.70, 72.10, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:19:31', '2025-11-02 19:19:31'),
(81, 1, 1, 1, 11, 'ڤێتێرنه‌ری', 'Veterinary', 90.74, 92.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:21:29', '2025-11-02 19:21:29'),
(82, 2, 1, 1, 11, 'ڤێتێرنه‌ری', 'Veterinary', 90.00, 91.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:21:55', '2025-11-02 19:21:55'),
(83, 1, 1, 1, 12, 'زمانی کوردی', 'Kurdish Language', 60.94, 62.20, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:24:28', '2025-11-02 19:24:28'),
(84, 2, 1, 1, 12, 'زمانی کوردی', 'Kurdish Language', 58.58, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:24:57', '2025-11-02 19:24:57'),
(85, 3, 1, 1, 12, 'زمانی کوردی', 'Kurdish Language', 56.60, 58.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:25:41', '2025-11-02 19:25:41'),
(86, 1, 1, 1, 12, 'زمانى عەرەبى', 'Arabic language', 62.40, 65.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:26:20', '2025-11-02 19:26:20'),
(87, 2, 1, 1, 12, 'زمانى عەرەبى', 'Arabic language', 59.40, 61.30, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:26:59', '2025-11-02 19:27:17'),
(88, 3, 1, 1, 12, 'زمانى عەرەبى', 'Arabic language', 57.00, 59.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:27:48', '2025-11-02 19:27:48'),
(89, 1, 1, 1, 13, 'زمانی ئینگلیزی', 'English language', 76.20, 77.80, 'زانستی و وێژەیی', 'نێر', 36.162173, 44.014967, '<p><br></p>', NULL, 1, '2025-11-02 19:31:35', '2025-11-02 19:31:35'),
(90, 2, 1, 1, 13, 'زمانی ئینگلیزی', 'English language', 72.95, 73.78, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:32:11', '2025-11-02 19:32:11'),
(91, 1, 1, 1, 13, 'زمانی کوردی', 'Kurdish Language', 60.40, 61.60, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:32:51', '2025-11-02 19:32:59'),
(92, 2, 1, 1, 13, 'زمانی کوردی', 'Kurdish Language', 58.23, 60.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:33:29', '2025-11-02 19:33:29'),
(93, 1, 1, 1, 13, 'زمانی فارسی', 'Persian language', 59.00, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:34:03', '2025-11-02 19:34:03'),
(94, 2, 1, 1, 13, 'زمانی فارسی', 'Persian language', 53.79, 55.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:34:34', '2025-11-02 19:34:34'),
(95, 1, 1, 1, 13, 'زمانی تورکی', 'Turkish language', 57.73, 59.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:35:05', '2025-11-02 19:35:05'),
(96, 2, 1, 1, 13, 'زمانی تورکی', 'Turkish language', 55.72, 57.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:35:34', '2025-11-02 19:35:34'),
(97, 1, 1, 1, 13, 'زمانی ئەلمانی', 'German Language', 58.10, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:36:08', '2025-11-02 19:36:08'),
(98, 2, 1, 1, 13, 'زمانی ئەلمانی', 'German Language', 58.01, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:36:47', '2025-11-02 19:36:47'),
(99, 1, 1, 1, 13, 'زمانی فەرەنسی', 'The French language', 59.10, 61.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:37:42', '2025-11-02 19:37:42'),
(100, 2, 1, 1, 13, 'زمانی فەرەنسی', 'The French language', 55.56, 57.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:38:34', '2025-11-02 19:38:34'),
(101, 1, 1, 1, 13, 'زمانی عه‌ره‌بی', 'Arabic Language', 62.40, 65.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:39:29', '2025-11-02 19:39:29'),
(102, 2, 1, 1, 13, 'زمانی عه‌ره‌بی', 'Arabic Language', 59.10, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:39:59', '2025-11-02 19:39:59'),
(103, 1, 1, 1, 13, 'زمانی چینی', 'Chinese Language', 58.70, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:40:28', '2025-11-02 19:40:28'),
(104, 2, 1, 1, 13, 'زمانی چینی', 'Chinese Language', 57.00, 58.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:40:57', '2025-11-02 19:40:57'),
(105, 1, 1, 1, 13, 'زمانی وەرگێران', 'Language of translation', 71.80, 72.60, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:41:31', '2025-11-02 19:41:31'),
(106, 2, 1, 1, 13, 'زمانی وەرگێران', 'Language of translation', 68.38, 69.80, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:42:01', '2025-11-02 19:42:01'),
(107, 1, 1, 1, 14, 'یاسا', 'law', 83.30, 85.00, 'زانستی', 'نێر', 36.143236, 44.027820, '<p><br></p>', NULL, 1, '2025-11-02 19:50:07', '2025-11-02 19:50:07'),
(108, 2, 1, 1, 14, 'یاسا', 'law', 80.10, 82.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-02 19:50:32', '2025-11-02 19:50:32'),
(109, 1, 1, 1, 15, 'پەیوەندییە نێودەوڵەتیەکان و دیبلۆماسى', 'International Relations and Diplomacy', 63.60, 65.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:14:52', '2025-11-03 03:16:56'),
(110, 2, 1, 1, 15, 'پەیوەندییە نێودەوڵەتیەکان و دیبلۆماسى', 'International Relations and Diplomacy', 61.10, 63.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:15:28', '2025-11-03 03:16:47'),
(111, 1, 1, 1, 15, 'سیستەمە سیاسیەکان و سیاسەتى گشتى', 'Political systems and public policy', 63.52, 64.52, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:16:12', '2025-11-03 03:16:12'),
(112, 2, 1, 1, 15, 'سیستەمە سیاسیەکان و سیاسەتى گشتى', 'Political systems and public policy', 58.20, 60.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:16:38', '2025-11-03 03:16:38'),
(113, 1, 1, 1, 16, 'زانستی کۆمپیوتەر CS', 'Computer Science CS', 86.76, 87.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:19:45', '2025-11-03 03:19:45'),
(114, 2, 1, 1, 16, 'زانستی کۆمپیوتەر CS', 'Computer Science CS', 83.94, 85.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:20:15', '2025-11-03 03:20:15'),
(115, 1, 1, 1, 16, 'تەکنەلۆجیای زانیاری IT', 'Information Technology IT', 87.80, 88.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:20:51', '2025-11-03 03:20:51'),
(116, 2, 1, 1, 16, 'تەکنەلۆجیای زانیاری IT', 'Information Technology IT', 85.80, 86.71, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:21:23', '2025-11-03 03:21:23'),
(117, 1, 1, 1, 16, 'ژیرى دەستکرد', 'Artificial intelligence', 87.80, 88.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:22:24', '2025-11-03 03:22:24'),
(118, 2, 1, 1, 16, 'ژیرى دەستکرد', 'Artificial intelligence', 85.29, 86.30, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:22:50', '2025-11-03 03:22:50'),
(119, 1, 1, 1, 17, 'کارگێرى کار', 'Labor Administration', 70.42, 71.60, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:26:43', '2025-11-03 03:26:43'),
(120, 2, 1, 1, 17, 'کارگێرى کار', 'Labor Administration', 66.00, 67.10, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:27:17', '2025-11-03 03:27:17'),
(121, 3, 1, 1, 17, 'کارگێرى کار', 'Labor Administration', 60.90, 62.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:27:47', '2025-11-03 03:27:47'),
(122, 1, 1, 1, 17, 'ژمێریارى', 'Accounting', 71.58, 72.84, 'زانستی', 'نێر', NULL, NULL, '<p><a href=\"https://road-map-uni.netlify.app/\">University Campus GIS - Interactive Map</a><br><br>خوێند لەم بەشە 4 ساڵە ، 8 وەرزە</p><p><br></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>یەکەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>یەکەم</u></span>&nbsp;: Stage 1 Semester 1</p><ol><li>Drawing Engineering</li><li>Computer</li><li>English 1</li><li>Infomation&nbsp;System</li><li>Kurdology</li></ol><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>یەکەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>دووەم</u>&nbsp;</span>: Stage 1 Semester 2</p><ol><li>AutoCad</li><li>Calculas 1</li><li>Fundamental of Programming with CPP</li><li>Computer Organizations and Digital Design (Logic)</li><li>English 2</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>دووەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>سێ</u>&nbsp;</span>: Stage 2 Semester 3</p><ol><li>Calcuals 2</li><li>Introduction to Problem solving and Object Oriented Programming OOP</li><li>Design New Media</li><li>Principles of Electronic Circuit</li><li>Web Design</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>دووەم</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>چوار</u>&nbsp;</span>: Stage 2 Semester 4</p><ol><li>Adv. OOP</li><li>Computer Architecture</li><li>Engineering Statistics</li><li>Multimedia System</li><li>Web Development</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>سێ</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>پێنج</u>&nbsp;</span>: 5 Stage 3 Semester</p><ol><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Computational Theory</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Data Communication</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Data Structure</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Digital image and video processing</li><li style=\"margin-top: 0px; margin-bottom: 1rem;\">Internet TechnologyInternet Technology</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>سێ</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>شەش</u>&nbsp;</span>: 6 Stage 3 Semester</p><ol><li>Applications of Data Communication</li><li>Engineering analysis NOT Practical</li><li>Mobile Application</li><li>Management and Organization NOT Practical</li><li>Database</li></ol><p></p><ol></ol><p></p><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>چوار</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>حەوت</u>&nbsp;</span>: 7 Stage 4 Semester</p><ol><li>Artificial Intelligent (AI)</li><li>Database management system (DBMS)</li><li>Geographic Information System (GIS)</li><li>Information System Architecture (ISA)</li><li>Network Design &amp; Implementing (CCNA)</li></ol><p>ساڵی&nbsp;<span style=\"font-weight: bolder;\"><u>چوار</u></span>&nbsp;وەرزی&nbsp;<span style=\"font-weight: bolder;\"><u>هەشت</u>&nbsp;</span>: 8 Stage 4 Semester</p><ol><li>IR</li><li>IoT</li><li>Network<br>4. Security<br>5. Project</li></ol>', NULL, 1, '2025-11-03 03:28:25', '2026-01-29 08:55:25'),
(123, 2, 1, 1, 17, 'ژمێریارى', 'Accounting', 67.89, 69.40, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:28:48', '2025-11-03 03:28:48'),
(124, 3, 1, 1, 17, 'ژمێریارى', 'Accounting', 61.83, 63.42, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:29:17', '2025-11-03 03:29:17'),
(125, 1, 1, 1, 17, 'ئابوورى', 'Economy', 63.95, 65.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:29:52', '2025-11-03 03:29:52'),
(126, 2, 1, 1, 17, 'ئابوورى', 'Economy', 60.90, 63.20, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:30:22', '2025-11-03 03:30:22'),
(127, 3, 1, 1, 17, 'ئابوورى', 'Economy', 57.21, 59.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:30:56', '2025-11-03 03:30:56'),
(128, 1, 1, 1, 17, 'ئامار و زانیارى', 'Statistics and Information', 58.04, 59.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:31:39', '2025-11-03 03:31:39'),
(129, 2, 1, 1, 17, 'ئامار و زانیارى', 'Statistics and Information', 57.59, 59.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:32:14', '2025-11-03 03:32:14'),
(130, 1, 1, 1, 17, 'زانستى دارایى و بانک', 'Finance and Banking Science', 62.20, 65.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:32:45', '2025-11-03 03:32:45'),
(131, 2, 1, 1, 17, 'زانستى دارایى و بانک', 'Finance and Banking Science', 60.61, 63.40, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:33:11', '2025-11-03 03:33:11'),
(132, 1, 1, 1, 17, 'كارگێرى بازارگەرى', 'Marketing Management', 63.00, 64.87, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:33:45', '2025-11-03 03:33:45'),
(133, 2, 1, 1, 17, 'كارگێرى بازارگەرى', 'Marketing Management', 60.55, 62.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:34:11', '2025-11-03 03:34:11'),
(134, 3, 1, 1, 17, 'كارگێرى بازارگەرى', 'Marketing Management', 56.40, 58.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 03:34:43', '2025-11-03 03:34:43'),
(135, 1, 1, 1, 18, 'مێژوو', 'History', 57.00, 58.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:02:55', '2025-11-03 06:02:55'),
(136, 2, 1, 1, 18, 'مێژوو', 'History', 58.20, 59.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:04:14', '2025-11-03 06:04:14'),
(137, 1, 1, 1, 18, 'جوگرافیا', 'Geography', 58.51, 59.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:05:50', '2025-11-03 06:05:50'),
(138, 2, 1, 1, 18, 'جوگرافیا', 'Geography', 57.89, 58.60, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:06:42', '2025-11-03 06:06:42'),
(139, 1, 1, 1, 18, 'کۆمەلناسی', 'Sociology', 57.82, 58.40, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:07:55', '2025-11-03 06:07:55'),
(140, 2, 1, 1, 18, 'کۆمەلناسی', 'Sociology', 56.87, 57.70, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:08:40', '2025-11-03 06:08:40'),
(141, 1, 1, 1, 18, 'شوێنەوار', 'Archeology', 60.03, 61.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:09:55', '2025-11-03 06:09:55'),
(142, 2, 1, 1, 18, 'شوێنەوار', 'Archeology', 53.60, 55.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:10:39', '2025-11-03 06:10:39'),
(143, 1, 1, 1, 18, 'دەرونناسی', 'Psychology', 60.68, 61.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:11:36', '2025-11-03 06:11:36'),
(144, 2, 1, 1, 18, 'دەرونناسی', 'Psychology', 58.40, 59.20, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:12:30', '2025-11-03 06:12:30'),
(145, 1, 1, 1, 18, 'کاری کۆمەلایەتی', 'Social work', 57.47, 58.77, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:13:31', '2025-11-03 06:13:31'),
(146, 2, 1, 1, 18, 'کاری کۆمەلایەتی', 'Social work', 55.80, 57.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:14:26', '2025-11-03 06:14:26'),
(147, 1, 1, 1, 18, 'راگەیاندن', 'Media', 62.67, 63.55, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p><p><br></p>', NULL, 1, '2025-11-03 06:15:10', '2025-11-03 06:15:10'),
(148, 2, 1, 1, 18, 'راگەیاندن', 'Media', 57.65, 58.80, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:15:53', '2025-11-03 06:16:06'),
(149, 1, 1, 1, 18, 'فەلسەفە', 'philosophy', 60.00, 61.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:16:57', '2025-11-03 06:16:57'),
(150, 2, 1, 1, 18, 'فەلسەفە', 'philosophy', 54.10, 55.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:17:31', '2025-11-03 06:17:31'),
(151, 1, 1, 1, 19, 'شێوه‌كاری', 'sculpture', 63.84, 65.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:18:27', '2025-11-03 06:18:27'),
(152, 2, 1, 1, 19, 'شێوه‌كاری', 'sculpture', 70.38, 71.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:19:15', '2025-11-03 06:19:15'),
(153, 1, 1, 1, 19, 'موزیک', 'music', 55.00, 56.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:20:14', '2025-11-03 06:20:14'),
(154, 2, 1, 1, 19, 'موزیک', 'music', 50.00, 52.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:20:56', '2025-11-03 06:20:56'),
(155, 1, 1, 1, 19, 'سینه‌ما و شانۆ', 'Cinema and theatre', 55.00, 56.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:21:44', '2025-11-03 06:21:44'),
(156, 2, 1, 1, 19, 'سینه‌ما و شانۆ', 'Cinema and theatre', 50.00, 51.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:25:00', '2025-11-03 06:25:17'),
(157, 1, 1, 1, 20, 'كیمیا', 'Chemistry', 72.76, 74.30, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:26:11', '2025-11-03 06:26:11'),
(158, 2, 1, 1, 20, 'كیمیا', 'Chemistry', 68.60, 70.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:27:09', '2025-11-03 06:27:09'),
(159, 1, 1, 1, 20, 'بایلۆجی', 'Biology', 74.90, 75.60, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:28:08', '2025-11-03 06:28:08'),
(160, 2, 1, 1, 20, 'بایلۆجی', 'Biology', 70.86, 72.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:28:51', '2025-11-03 06:28:51'),
(161, 1, 1, 1, 20, 'فیزیك', 'Physics', 70.20, 71.43, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:29:50', '2025-11-03 06:29:50'),
(162, 2, 1, 1, 20, 'فیزیك', 'Physics', 65.80, 68.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 06:31:36', '2025-11-03 06:31:36'),
(163, 1, 1, 1, 20, 'ماتماتیك', 'Mathematics', 68.54, 70.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:50:35', '2025-11-03 12:50:35'),
(164, 2, 1, 1, 20, 'ماتماتیك', 'Mathematics', 64.52, 66.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:51:06', '2025-11-03 12:51:06'),
(165, 1, 1, 1, 20, 'رێنمایی په‌روه‌رده‌یی و ده‌روونی', 'Educational and psychological guidance', 59.01, 60.20, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:51:50', '2025-11-03 12:51:50'),
(166, 2, 1, 1, 20, 'رێنمایی په‌روه‌رده‌یی و ده‌روونی', 'Educational and psychological guidance', 58.20, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:52:17', '2025-11-03 12:52:17'),
(167, 1, 1, 1, 20, 'په‌روه‌رده‌ی تایبه‌ت', 'Special education', 57.40, 59.30, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:52:55', '2025-11-03 12:52:55'),
(168, 2, 1, 1, 20, 'په‌روه‌رده‌ی تایبه‌ت', 'Special education', 57.66, 59.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:53:22', '2025-11-03 12:53:22'),
(169, 3, 1, 1, 20, 'په‌روه‌رده‌ی تایبه‌ت', 'Special education', 55.16, 66.50, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:54:18', '2025-11-03 12:54:18'),
(170, 1, 1, 1, 20, 'زمانی کوردی', 'Kurdish Language', 66.64, 67.40, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:55:00', '2025-11-03 12:55:00'),
(171, 2, 1, 1, 20, 'زمانی کوردی', 'Kurdish Language', 61.55, 62.50, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 12:55:29', '2025-11-03 12:55:29'),
(172, 3, 1, 1, 20, 'زمانی کوردی', 'Kurdish Language', 58.21, 59.60, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:02:17', '2025-11-03 13:02:17'),
(173, 1, 1, 1, 20, 'زمانی عه‌ره‌بی', 'Arabic Language', 69.34, 70.45, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:03:16', '2025-11-03 13:03:16'),
(174, 2, 1, 1, 20, 'زمانی عه‌ره‌بی', 'Arabic Language', 63.40, 65.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:03:42', '2025-11-03 13:03:42'),
(175, 3, 1, 1, 20, 'زمانی عه‌ره‌بی', 'Arabic Language', 59.06, 61.23, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:04:10', '2025-11-03 13:04:10'),
(176, 1, 1, 1, 20, 'زمانی ئینگلیزی', 'English language', 80.87, 81.78, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:04:41', '2025-11-03 13:04:41'),
(177, 2, 1, 1, 20, 'زمانی ئینگلیزی', 'English language', 75.90, 78.41, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:05:19', '2025-11-03 13:05:19'),
(178, 3, 1, 1, 20, 'زمانی ئینگلیزی', 'English language', 68.95, 70.80, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:05:51', '2025-11-03 13:05:51'),
(179, 1, 1, 1, 20, 'زمانی سریانی', 'Syriac language', 53.80, 55.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:06:31', '2025-11-03 13:06:31'),
(180, 2, 1, 1, 20, 'زمانی سریانی', 'Syriac language', 57.26, 59.60, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:07:09', '2025-11-03 13:07:09'),
(181, 1, 1, 1, 21, 'پەروه‌رده‌ی جه‌سته‌یی و زانسته‌ وه‌رزشیه‌كان', 'Physical Education and Sports Sciences', 53.09, 55.00, 'وێژەیی', 'نێر', 36.145891, 44.027466, '<p><br></p>', NULL, 1, '2025-11-03 13:19:07', '2025-11-03 13:19:07'),
(182, 2, 1, 1, 21, 'پەروه‌رده‌ی جه‌سته‌یی و زانسته‌ وه‌رزشیه‌كان', 'Physical Education and Sports Sciences', 52.00, 54.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:19:55', '2025-11-03 13:20:45'),
(183, 1, 1, 1, 22, 'زمانی ئینگلیزی', 'English language', 74.94, 76.40, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:22:28', '2025-11-03 13:22:28'),
(184, 2, 1, 1, 22, 'زمانی ئینگلیزی', 'Kurdish Language', 71.02, 73.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:23:01', '2025-11-03 13:23:01'),
(185, 3, 1, 1, 22, 'زمانی ئینگلیزی', 'English language', 66.47, 68.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:23:30', '2025-11-03 13:23:30'),
(186, 1, 1, 1, 22, 'زانستى گشتى', 'General Science', 68.36, 70.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:24:08', '2025-11-03 13:24:08'),
(187, 2, 1, 1, 22, 'زانستى گشتى', 'General Science', 65.07, 67.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:24:41', '2025-11-03 13:24:41'),
(188, 3, 1, 1, 22, 'زانستى گشتى', 'General Science', 59.90, 60.50, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:25:24', '2025-11-03 13:25:24'),
(189, 1, 1, 1, 22, 'زمانی کوردی', 'Kurdish Language', 64.70, 66.20, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:25:51', '2025-11-03 13:25:51'),
(190, 2, 1, 1, 22, 'زمانی کوردی', 'Kurdish Language', 60.90, 62.10, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:26:15', '2025-11-03 13:26:15'),
(191, 3, 1, 1, 22, 'زمانی کوردی', 'Kurdish Language', 57.70, 59.10, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:26:45', '2025-11-03 13:26:45'),
(192, 1, 1, 1, 22, 'زانستی كۆمه‌لاتییه‌كان', 'Social Sciences', 63.90, 65.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:27:31', '2025-11-03 13:27:31'),
(193, 2, 1, 1, 22, 'زانستی كۆمه‌لاتییه‌كان', 'Social Sciences', 60.20, 61.56, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:28:04', '2025-11-03 13:28:04'),
(194, 3, 1, 1, 22, 'زانستی كۆمه‌لاتییه‌كان', 'Social Sciences', 57.40, 58.00, 'وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:28:30', '2025-11-03 13:28:30'),
(195, 1, 1, 1, 22, 'باخچەى مندالان', 'Kindergarten', 61.80, 62.40, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:29:11', '2025-11-03 13:29:11'),
(196, 2, 1, 1, 22, 'باخچەى مندالان', 'Kindergarten', 59.42, 61.40, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:29:44', '2025-11-03 13:29:44'),
(197, 3, 1, 1, 22, 'باخچەی منداڵان', 'Kindergarten', 56.62, 57.90, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:30:17', '2025-11-03 13:30:17'),
(198, 1, 1, 1, 22, 'ماتماتیك', 'Mathematics', 63.97, 65.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:30:57', '2025-11-03 13:30:57'),
(199, 2, 1, 1, 22, 'ماتماتیك', 'Mathematics', 61.60, 63.40, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:31:20', '2025-11-03 13:31:20'),
(200, 3, 1, 1, 22, 'ماتماتیك', 'Mathematics', 59.00, 60.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:31:41', '2025-11-03 13:31:41'),
(201, 1, 1, 1, 22, 'زمانی عه‌ره‌بی', 'Arabic Language', 68.23, 70.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:32:16', '2025-11-03 13:32:16'),
(202, 2, 1, 1, 22, 'زمانی عه‌ره‌بی', 'Arabic Language', 62.40, 64.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:32:41', '2025-11-03 13:32:41'),
(203, 3, 1, 1, 22, 'زمانی عه‌ره‌بی', 'Arabic Language', 58.60, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:33:36', '2025-11-03 13:33:47'),
(204, 1, 1, 1, 22, 'مامۆستای پۆڵ', 'Paul\'s teacher', 62.70, 64.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:34:23', '2025-11-03 13:34:23'),
(205, 2, 1, 1, 22, 'مامۆستای پۆڵ', 'Paul\'s teacher', 59.78, 60.00, 'زانستی و وێژەیی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:34:53', '2025-11-03 13:34:53'),
(206, 1, 1, 1, 23, 'بەرهەمهێنان و دروستى ئاژەل', 'Animal production and correctness', 60.00, 61.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:39:30', '2025-11-03 13:39:30'),
(207, 2, 1, 1, 23, 'بەرهەمهێنان و دروستى ئاژەل', 'Animal production and correctness', 55.08, 56.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:40:11', '2025-11-03 13:40:11'),
(208, 1, 1, 1, 23, 'بەروبوومى کێلگەیى رووەکە پزیشکییەکان', 'Field crops of medicinal plants', 60.40, 61.54, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:40:41', '2025-11-03 13:40:41'),
(209, 2, 1, 1, 23, 'بەروبوومى کێلگەیى رووەکە پزیشکییەکان', 'Field crops of medicinal plants', 55.05, 56.30, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:41:20', '2025-11-03 13:41:20'),
(210, 1, 1, 1, 23, 'پاراستنى ڕووەک', 'Plant protection', 59.80, 61.20, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:41:57', '2025-11-03 13:41:57'),
(211, 2, 1, 1, 23, 'پاراستنى ڕووەک', 'Plant protection', 54.20, 56.47, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:42:22', '2025-11-03 13:42:44'),
(212, 1, 1, 1, 23, 'پیشەسازى خۆراک', 'The Food Industry', 59.54, 61.30, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:43:22', '2025-11-03 13:43:22'),
(213, 2, 1, 1, 23, 'پیشەسازى خۆراک', 'The Food Industry', 54.62, 55.90, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:43:51', '2025-11-03 13:43:51'),
(214, 1, 1, 1, 23, 'خاک و ئاو', 'Soil and water', 61.98, 63.45, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:44:25', '2025-11-03 13:44:25'),
(215, 2, 1, 1, 23, 'خاک و ئاو', 'Soil and water', 55.55, 57.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:44:58', '2025-11-03 13:44:58'),
(216, 1, 1, 1, 23, 'دارستان', 'forest', 60.00, 62.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:45:28', '2025-11-03 13:45:28'),
(217, 2, 1, 1, 23, 'دارستان', 'forest', 55.00, 56.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:45:56', '2025-11-03 13:45:56'),
(218, 1, 1, 1, 23, 'بەرهەمهێنان و تەندروستی ماسی', 'fish production and health', 60.00, 61.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:46:29', '2025-11-03 13:46:29'),
(219, 2, 1, 1, 23, 'بەرهەمهێنان و تەندروستی ماسی', 'fish production and health', 58.40, 59.60, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:46:56', '2025-11-03 13:46:56'),
(220, 1, 1, 1, 23, 'ڕەزگەری و ئەندازەی پاڕکەکان', 'Ranching and Parks Engineering', 60.00, 61.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:47:28', '2025-11-03 13:47:28'),
(221, 2, 1, 1, 23, 'ڕەزگەری و ئەندازەی پاڕکەکان', 'Ranching and Parks Engineering', 54.03, 55.00, 'زانستی', 'نێر', NULL, NULL, '<p><br></p>', NULL, 1, '2025-11-03 13:47:56', '2025-11-03 13:47:56'),
(222, 1, 1, 3, 24, 'پزیشکی گشتی', 'General Practitioner', 97.41, 98.67, 'زانستی', 'نێر', 36.095411, 44.655342, NULL, NULL, 1, '2025-11-15 18:12:51', '2025-11-15 18:12:51'),
(223, 2, 1, 3, 24, 'پزیشکی گشتی', 'General Practitioner', 96.90, 97.67, 'زانستی', 'نێر', 36.095411, 44.655342, NULL, NULL, 1, '2025-11-15 18:13:38', '2025-11-15 18:13:38'),
(224, 1, 1, 3, 25, 'تەلارسازى', 'Architecture', 94.84, 95.00, 'زانستی', 'نێر', 36.098726, 44.657756, '<p><br></p>', NULL, 1, '2025-11-15 18:24:03', '2025-11-15 18:24:03'),
(225, 2, 1, 3, 25, 'تەلارسازى', 'Architecture', 94.19, 95.00, 'زانستی', 'نێر', 36.098726, 44.657756, NULL, NULL, 1, '2025-11-15 18:24:39', '2025-11-15 18:24:39'),
(226, 1, 1, 3, 25, 'شارستانى', 'Civilization', 91.67, 92.00, 'زانستی', 'نێر', 36.098499, 44.657442, '<p><br></p>', NULL, 1, '2025-11-15 18:26:27', '2025-11-15 18:26:27'),
(227, 2, 1, 3, 25, 'شارستانى', 'Civilization', 89.90, 91.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:26:57', '2025-11-15 18:26:57'),
(228, 1, 1, 3, 25, 'جیۆتەکنیک', 'Geotechnics', 76.83, 78.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:27:40', '2025-11-15 18:27:40'),
(229, 2, 1, 3, 25, 'جیۆتەکنیک', 'Geotechnics', 75.00, 77.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:28:03', '2025-11-15 18:28:03'),
(230, 1, 1, 3, 25, 'میکانیک و دروست كردن', 'Mechanics and construction', 78.73, 79.20, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:28:38', '2025-11-15 18:28:38'),
(231, 2, 1, 3, 25, 'میکانیک و دروست كردن', 'Mechanics and construction', 76.47, 77.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:28:57', '2025-11-15 18:28:57'),
(232, 1, 1, 3, 25, 'پرۆگرام سازى', 'Programming', 94.76, 95.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:29:52', '2025-11-15 18:29:52'),
(233, 2, 1, 3, 25, 'پرۆگرام سازى', 'Programming', 94.16, 95.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:30:12', '2025-11-15 18:30:12'),
(234, 1, 1, 3, 25, 'نەوت', 'Oil', 82.86, 84.50, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:30:42', '2025-11-15 18:30:42'),
(235, 2, 1, 3, 25, 'نەوت', 'Oil', 78.65, 80.00, 'زانستی', 'نێر', 36.098499, 44.657442, NULL, NULL, 1, '2025-11-15 18:31:14', '2025-11-15 18:31:14'),
(236, 1, 1, 3, 25, 'كیمیاوی', 'Chemical', 79.00, 81.00, 'زانستی', 'نێر', NULL, NULL, NULL, NULL, 1, '2025-11-15 18:31:44', '2025-11-15 18:31:44'),
(237, 2, 1, 3, 25, 'كیمیاوی', 'Chemical', 75.83, 78.00, 'زانستی', 'نێر', NULL, NULL, NULL, NULL, 1, '2025-11-15 18:32:05', '2025-11-15 18:32:05'),
(238, 1, 1, 3, 26, 'یاسا', 'law', 77.59, 79.50, 'زانستی و وێژەیی', 'نێر', 36.095978, 44.658080, '<p><br></p>', NULL, 1, '2025-11-15 18:37:54', '2025-11-15 18:46:21'),
(239, 2, 1, 3, 26, 'یاسا', 'law', 75.05, 78.00, 'زانستی و وێژەیی', 'نێر', 36.095978, 44.658080, NULL, NULL, 1, '2025-11-15 18:38:43', '2025-11-15 18:46:40'),
(240, 1, 1, 3, 26, 'ئینگلیزی', 'English', 70.19, 72.00, 'زانستی و وێژەیی', 'نێر', 36.097477, 44.654558, '<p><br></p>', NULL, 1, '2025-11-15 18:40:31', '2025-11-15 18:46:44'),
(241, 2, 1, 3, 26, 'ئینگلیزی', 'English', 65.97, 67.40, 'زانستی و وێژەیی', 'نێر', 36.097477, 44.654558, NULL, NULL, 1, '2025-11-15 18:40:54', '2025-11-15 18:46:48'),
(242, 3, 1, 3, 26, 'ئینگلیزی', 'English', 57.70, 60.00, 'زانستی و وێژەیی', 'نێر', 36.097477, 44.654558, NULL, NULL, 1, '2025-11-15 18:41:28', '2025-11-15 18:46:51'),
(243, 1, 1, 3, 26, 'كارگیری', 'Administration', 61.00, 63.00, 'زانستی و وێژەیی', 'نێر', 36.096190, 44.655665, '<p><br></p>', NULL, 1, '2025-11-15 18:43:27', '2025-11-15 18:43:27'),
(244, 2, 1, 3, 26, 'كارگیری', 'Administration', 58.59, 60.00, 'زانستی و وێژەیی', 'نێر', 36.096190, 44.655665, NULL, NULL, 1, '2025-11-15 18:43:47', '2025-11-15 18:43:47'),
(245, 3, 1, 3, 26, 'كارگیری', 'Administration', 55.00, 57.30, 'زانستی و وێژەیی', 'نێر', 36.096190, 44.655665, NULL, NULL, 1, '2025-11-15 18:44:14', '2025-11-15 18:44:14'),
(246, 1, 1, 3, 26, 'ژمێریارى', 'Accounting', 63.41, 65.00, 'زانستی و وێژەیی', 'نێر', 36.096202, 44.655612, NULL, NULL, 1, '2025-11-15 18:45:07', '2025-11-15 18:45:07');
INSERT INTO `departments` (`id`, `system_id`, `province_id`, `university_id`, `college_id`, `name`, `name_en`, `local_score`, `external_score`, `type`, `sex`, `lat`, `lng`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(247, 2, 1, 3, 26, 'ژمێریارى', 'Accounting', 58.83, 60.00, 'زانستی و وێژەیی', 'نێر', 36.096202, 44.075775, NULL, NULL, 1, '2025-11-15 18:45:42', '2025-11-15 18:45:42'),
(248, 1, 1, 3, 27, 'زمانی عه‌ره‌بی', 'Arabic Language', 61.70, 63.20, 'زانستی و وێژەیی', 'نێر', 36.096098, 44.654818, NULL, NULL, 1, '2025-11-15 18:50:41', '2025-11-15 18:50:41'),
(249, 2, 1, 3, 27, 'زمانی عه‌ره‌بی', 'Arabic Language', 59.00, 60.30, 'زانستی و وێژەیی', 'نێر', 36.096098, 44.654818, NULL, NULL, 1, '2025-11-15 18:51:18', '2025-11-15 18:51:18'),
(250, 3, 1, 3, 27, 'زمانی عه‌ره‌بی', 'Arabic Language', 53.60, 55.00, 'زانستی و وێژەیی', 'نێر', 36.096098, 44.654818, NULL, NULL, 1, '2025-11-15 18:51:42', '2025-11-15 18:51:42'),
(251, 1, 1, 3, 27, 'زمانی کوردی', 'Kurdish Language', 58.23, 59.70, 'زانستی و وێژەیی', 'نێر', 36.096098, 44.654818, NULL, NULL, 1, '2025-11-15 18:52:27', '2025-11-15 18:52:27'),
(252, 2, 1, 3, 27, 'زمانی کوردی', 'Kurdish Language', 57.70, 58.40, 'زانستی و وێژەیی', 'نێر', 36.096100, 44.654820, NULL, NULL, 1, '2025-11-15 18:53:08', '2025-11-15 18:53:08'),
(253, 3, 1, 3, 27, 'زمانی کوردی', 'Kurdish Language', 53.77, 55.00, 'زانستی و وێژەیی', 'نێر', 36.096100, 44.654820, NULL, NULL, 1, '2025-11-15 18:53:39', '2025-11-15 18:53:39'),
(254, 1, 1, 3, 27, 'مێژوو', 'history', 59.24, 60.00, 'وێژەیی', 'نێر', 36.096102, 44.654822, NULL, NULL, 1, '2025-11-15 18:54:15', '2025-11-15 18:54:15'),
(255, 2, 1, 3, 27, 'مێژوو', 'history', 54.40, 56.00, 'وێژەیی', 'نێر', 36.096102, 44.654822, NULL, NULL, 1, '2025-11-15 18:54:36', '2025-11-15 18:54:36'),
(256, 3, 1, 3, 27, 'مێژوو', 'history', 51.20, 53.00, 'وێژەیی', 'نێر', 36.096102, 44.654822, NULL, NULL, 1, '2025-11-15 18:54:56', '2025-11-15 18:54:56'),
(257, 1, 1, 3, 27, 'جوگرافیا', 'Geography', 61.60, 63.00, 'زانستی و وێژەیی', 'نێر', 36.096108, 44.654828, NULL, NULL, 1, '2025-11-15 18:55:29', '2025-11-15 18:55:29'),
(258, 2, 1, 3, 27, 'جوگرافیا', 'Geography', 55.30, 56.00, 'زانستی و وێژەیی', 'نێر', 36.096108, 44.654828, NULL, NULL, 1, '2025-11-15 18:55:53', '2025-11-15 18:55:53'),
(259, 3, 1, 3, 27, 'جوگرافیا', 'Geography', 51.30, 52.00, 'زانستی و وێژەیی', 'نێر', 36.096108, 44.654828, NULL, NULL, 1, '2025-11-15 18:56:15', '2025-11-15 18:56:15'),
(260, 1, 1, 3, 27, 'پەروەردەی ئاینی', 'Religious Education', 58.50, 60.00, 'زانستی و وێژەیی', 'نێر', 36.096115, 44.654835, NULL, NULL, 1, '2025-11-15 19:00:12', '2025-11-15 19:00:12'),
(261, 2, 1, 3, 27, 'پەروەردەی ئاینی', 'Religious Education', 54.28, 56.00, 'زانستی و وێژەیی', 'نێر', 36.096115, 44.654835, NULL, NULL, 1, '2025-11-15 19:00:37', '2025-11-15 19:00:37'),
(262, 3, 1, 3, 27, 'پەروەردەی ئاینی', 'Religious Education', 55.00, 56.00, 'زانستی و وێژەیی', 'نێر', 36.096115, 44.654835, NULL, NULL, 1, '2025-11-15 19:00:57', '2025-11-15 19:00:57'),
(263, 1, 1, 3, 27, 'زمانی ئینگلیزی', 'English Language', 72.81, 75.00, 'زانستی و وێژەیی', 'نێر', 36.096125, 44.654845, NULL, NULL, 1, '2025-11-15 19:01:38', '2025-11-15 19:01:38'),
(264, 2, 1, 3, 27, 'زمانی ئینگلیزی', 'English Language', 69.45, 72.00, 'زانستی و وێژەیی', 'نێر', 36.096125, 44.654845, NULL, NULL, 1, '2025-11-15 19:02:01', '2025-11-15 19:02:01'),
(265, 3, 1, 3, 27, 'زمانی ئینگلیزی', 'English Language', 62.02, 65.00, 'زانستی و وێژەیی', 'نێر', 36.096125, 44.654845, NULL, NULL, 1, '2025-11-15 19:02:36', '2025-11-15 19:02:36'),
(266, 1, 1, 3, 27, 'په‌روه‌رده‌ و ده‌رونزانی', 'Education and Psychology', 59.33, 61.00, 'زانستی و وێژەیی', 'نێر', 36.096155, 44.654865, NULL, NULL, 1, '2025-11-15 19:03:10', '2025-11-15 19:03:10'),
(267, 2, 1, 3, 27, 'په‌روه‌رده‌ و ده‌رونزانی', 'Education and Psychology', 54.27, 66.00, 'زانستی و وێژەیی', 'نێر', 36.096155, 44.654865, NULL, NULL, 1, '2025-11-15 19:05:10', '2025-11-15 19:05:10'),
(268, 3, 1, 3, 27, 'په‌روه‌رده‌ و ده‌رونزانی', 'Education and Psychology', 50.80, 52.00, 'زانستی و وێژەیی', 'نێر', 36.096155, 44.654865, NULL, NULL, 1, '2025-11-15 19:05:29', '2025-11-15 19:05:29'),
(269, 1, 1, 3, 46, 'کلینیکەل سایکۆلۆجی', 'Clinical Psychology', 69.67, 71.40, 'زانستی', 'نێر', 36.097260, 44.656980, NULL, NULL, 1, '2025-11-15 19:09:21', '2025-11-15 19:09:21'),
(270, 2, 1, 3, 46, 'کلینیکەل سایکۆلۆجی', 'Clinical Psychology', 64.58, 66.00, 'زانستی', 'نێر', 36.096155, 44.654865, NULL, NULL, 1, '2025-11-15 19:09:59', '2025-11-15 19:09:59'),
(271, 1, 1, 3, 46, 'میدیكه‌ل مایکرۆبایۆلۆجی', 'Medical Microbiology', 92.00, 93.00, 'زانستی', 'نێر', 36.096155, 44.654865, NULL, NULL, 1, '2025-11-15 19:10:39', '2025-11-15 19:10:39'),
(272, 2, 1, 3, 46, 'میدیكه‌ل مایکرۆبایۆلۆجی', 'Medical Microbiology', 89.60, 90.50, 'زانستی', 'نێر', 36.096155, 44.654865, NULL, NULL, 1, '2025-11-15 19:11:04', '2025-11-15 19:11:04'),
(273, 1, 1, 3, 46, 'بایلۆجی', 'Biology', 83.80, 85.00, 'زانستی', 'نێر', 36.097821, 44.655331, NULL, NULL, 1, '2025-11-15 19:12:22', '2025-11-15 19:12:22'),
(274, 2, 1, 3, 46, 'بایلۆجی', 'Biology', 80.38, 82.00, 'زانستی', 'نێر', 36.097821, 44.655331, NULL, NULL, 1, '2025-11-15 19:12:45', '2025-11-15 19:12:45'),
(275, 1, 1, 3, 46, 'کیمیا', 'Chemistry', 77.06, 79.00, 'زانستی', 'نێر', 36.097851, 44.655351, NULL, NULL, 1, '2025-11-15 19:13:28', '2025-11-15 19:13:28'),
(276, 2, 1, 3, 46, 'کیمیا', 'Chemistry', 73.47, 75.00, 'زانستی', 'نێر', 36.097851, 44.655351, NULL, NULL, 1, '2025-11-15 19:13:50', '2025-11-15 19:41:30'),
(277, 1, 1, 3, 46, 'فیزیك', 'Physics', 62.10, 65.00, 'زانستی', 'نێر', 36.097897, 44.655253, NULL, NULL, 1, '2025-11-15 19:14:35', '2025-11-15 19:14:35'),
(278, 2, 1, 3, 46, 'فیزیك', 'Physics', 60.90, 62.60, 'زانستی', 'نێر', 36.097897, 44.655253, NULL, NULL, 1, '2025-11-15 19:15:07', '2025-11-15 19:15:07'),
(279, 1, 1, 3, 46, 'ماتماتیک', 'Mathematics', 62.83, 64.00, 'زانستی', 'نێر', 36.097320, 44.654931, NULL, NULL, 1, '2025-11-15 19:16:49', '2025-11-15 19:16:49'),
(280, 2, 1, 3, 46, 'ماتماتیک', 'Mathematics', 58.47, 60.00, 'زانستی', 'نێر', 36.097320, 44.654931, NULL, NULL, 1, '2025-11-15 19:17:14', '2025-11-15 19:17:14'),
(281, 1, 1, 3, 46, 'زانستى کۆمپیوتەر', 'Computer Science', 82.40, 84.20, 'زانستی', 'نێر', 36.094303, 44.656610, NULL, NULL, 1, '2025-11-15 19:18:12', '2025-11-15 19:18:12'),
(282, 2, 1, 3, 46, 'زانستى کۆمپیوتەر', 'Computer Science', 80.10, 82.00, 'زانستی', 'نێر', 36.094303, 44.656610, NULL, NULL, 1, '2025-11-15 19:18:40', '2025-11-15 19:18:40'),
(283, 1, 1, 3, 46, 'زانستى تاقیگەى پزیشکى', 'Medical Laboratory Science', 88.80, 90.00, 'زانستی', 'نێر', 36.095877, 44.658171, NULL, NULL, 1, '2025-11-15 19:19:35', '2025-11-15 19:19:35'),
(284, 2, 1, 3, 46, 'زانستى تاقیگەى پزیشکى', 'Medical Laboratory Science', 82.68, 85.00, 'زانستی', 'نێر', 36.095877, 44.658171, NULL, NULL, 1, '2025-11-15 19:20:01', '2025-11-15 19:20:01'),
(285, 1, 1, 3, 28, 'زانستى وەرزش بۆ تەندروستى و بەجێهێنان', 'Exercise Science for Health and Performance', 60.37, 62.00, 'زانستی و وێژەیی', 'نێر', 36.095821, 44.655226, NULL, NULL, 1, '2025-11-15 19:25:57', '2025-11-15 19:25:57'),
(286, 2, 1, 3, 26, 'زانستى وەرزش بۆ تەندروستى و بەجێهێنان', 'Exercise Science for Health and Performance', 55.91, 57.40, 'زانستی و وێژەیی', 'نێر', 36.095821, 44.655226, NULL, NULL, 1, '2025-11-15 19:27:03', '2025-11-15 19:32:07'),
(287, 1, 1, 3, 28, 'پەروەردەى وەرزش', 'Physical Education', 57.65, 59.00, 'زانستی و وێژەیی', 'نێر', 36.095461, 44.655446, NULL, NULL, 1, '2025-11-15 19:28:00', '2025-11-15 19:28:00'),
(288, 2, 1, 3, 26, 'پەروەردەى وەرزش', 'Physical Education', 54.72, 56.00, 'زانستی و وێژەیی', 'نێر', 36.095461, 44.655446, NULL, NULL, 1, '2025-11-15 19:28:27', '2025-11-15 19:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mbti_answers`
--

CREATE TABLE `mbti_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mbti_answers`
--

INSERT INTO `mbti_answers` (`id`, `user_id`, `student_id`, `question_id`, `score`, `created_at`, `updated_at`) VALUES
(1, 13, 4, 1, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(2, 13, 4, 2, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(3, 13, 4, 3, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(4, 13, 4, 4, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(5, 13, 4, 5, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(6, 13, 4, 6, 9, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(7, 13, 4, 7, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(8, 13, 4, 8, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(9, 13, 4, 9, 9, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(10, 13, 4, 10, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(11, 13, 4, 11, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(12, 13, 4, 12, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(13, 13, 4, 13, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(14, 13, 4, 14, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(15, 13, 4, 15, 8, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(16, 13, 4, 16, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(17, 13, 4, 17, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(18, 13, 4, 18, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(19, 13, 4, 19, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(20, 13, 4, 20, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(21, 13, 4, 21, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(22, 13, 4, 22, 9, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(23, 13, 4, 23, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(24, 13, 4, 24, 10, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(25, 13, 4, 25, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(26, 13, 4, 26, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(27, 13, 4, 27, 9, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(28, 13, 4, 28, 8, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(29, 13, 4, 29, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(30, 13, 4, 30, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(31, 13, 4, 31, 2, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(32, 13, 4, 32, 1, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(33, 13, 4, 33, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(34, 13, 4, 34, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(35, 13, 4, 35, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(36, 13, 4, 36, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(37, 13, 4, 37, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(38, 13, 4, 38, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(39, 13, 4, 39, 9, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(40, 13, 4, 40, 8, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(41, 13, 4, 41, 2, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(42, 13, 4, 42, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(43, 13, 4, 43, 8, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(44, 13, 4, 44, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(45, 13, 4, 45, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(46, 13, 4, 46, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(47, 13, 4, 47, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(48, 13, 4, 48, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(49, 13, 4, 49, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(50, 13, 4, 50, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(51, 13, 4, 51, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(52, 13, 4, 52, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(53, 13, 4, 53, 2, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(54, 13, 4, 54, 1, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(55, 13, 4, 55, 8, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(56, 13, 4, 56, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(57, 13, 4, 57, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(58, 13, 4, 58, 1, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(59, 13, 4, 59, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(60, 13, 4, 60, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(61, 13, 4, 61, 9, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(62, 13, 4, 62, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(63, 13, 4, 63, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(64, 13, 4, 64, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(65, 13, 4, 65, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(66, 13, 4, 66, 3, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(67, 13, 4, 67, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(68, 13, 4, 68, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(69, 13, 4, 69, 7, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(70, 13, 4, 70, 6, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(71, 13, 4, 71, 4, '2026-01-29 18:01:18', '2026-01-29 18:01:18'),
(72, 13, 4, 72, 5, '2026-01-29 18:01:18', '2026-01-29 18:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `mbti_questions`
--

CREATE TABLE `mbti_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dimension` enum('EI','SN','TF','JP') NOT NULL,
  `side` varchar(255) NOT NULL,
  `question_ku` text NOT NULL,
  `question_en` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mbti_questions`
--

INSERT INTO `mbti_questions` (`id`, `dimension`, `side`, `question_ku`, `question_en`, `order`, `created_at`, `updated_at`) VALUES
(1, 'EI', 'E', 'حەزم لە کاری جۆراوجۆر دەکەم', NULL, 1, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(2, 'EI', 'E', 'بە توانام لە پێشوازی کردنی خەڵکدا', NULL, 2, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(3, 'EI', 'E', 'حەزم لە کاری بە پێیه', NULL, 3, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(4, 'EI', 'E', 'گرنگی بە ڕای بەرامبەر دەدەم بۆ کارەکەم', NULL, 4, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(5, 'EI', 'E', 'حەزم لە قسەکردنی ناو تەلەفونە و لە ناردنی نامە', NULL, 5, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(6, 'EI', 'E', 'هەندێک جار بە یەک بیرکردنەوە کار دەکەم', NULL, 6, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(7, 'EI', 'E', 'حەز دەکەم لە کاتی شیکردنەوەدا خەڵک لەگەڵم بن', NULL, 7, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(8, 'EI', 'E', 'قسەکردنم پی باشترە و لە نوسین', NULL, 8, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(9, 'EI', 'E', 'حەز دەکەم کاری نوێ فیرع بێت لە نەجامی گفتوگۆ لەگەڵ خەڵکیدا', NULL, 9, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(10, 'EI', 'I', 'حەزم لە ئەمڕۆبوونەوەی تەمەرکیزم زیاترە', NULL, 10, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(11, 'EI', 'I', 'گرەفتەم هەمیشە لە ناسینەوەی دەنگ و دەودای ناو مکاندا', NULL, 11, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(12, 'EI', 'I', 'ناتوانم بۆ ماوەیەکی دور و درێژ لەم سەرۆژە کار بکەم بە یەک بچران', NULL, 12, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(13, 'EI', 'I', 'زۆر گرنگی دەدەم بە نەجام لە کارەکەمدا', NULL, 13, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(14, 'EI', 'I', 'ڕەقم لە بچراندنی بیرکردنەوەکەمە بەهۆی تەلەفونەوە', NULL, 14, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(15, 'EI', 'I', 'بیر دەکەمەوە پێش ئەوەی کار بکەم و هەندێک جار بیر دەکەمەوە کارەکەش دەکەم', NULL, 15, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(16, 'EI', 'I', 'بە تەنها دەتوانم کار بکەم نەک قەناعەتم یەتی هەبور', NULL, 16, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(17, 'EI', 'I', 'حەزم لە بەئەندامیبوونە لە ڕاپرسیەکی نوسینەوە', NULL, 17, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(18, 'EI', 'I', 'حەزم لە فیریوونە بە خوێندنەوە و لە قسەکردن', NULL, 18, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(19, 'SN', 'S', 'هەست بە شیکرە پێشهاتەکانی ناو ڕوداوەکان دەکەم', NULL, 1, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(20, 'SN', 'S', 'تەمەرکیزم لەسەر کاری ڕاستەقینەیە', NULL, 2, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(21, 'SN', 'S', 'حەزم لە ڕوونکردنەوەی ڕووسن و شیکرایە بۆ شیکردنەوەی کارەکانم', NULL, 3, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(22, 'SN', 'S', 'حەزم لە جنبەچیکردنی شوێنەهێنەیە کە فیزی بووم', NULL, 4, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(23, 'SN', 'S', 'بەردەوامیم هەیە لە کارکردن لەسەر ڕاستیەکان هەرچەندە کاتی زۆریش بگریت', NULL, 5, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(24, 'SN', 'S', 'هەنگاو بە هەنگاو دەڕۆم بە کوتایی', NULL, 6, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(25, 'SN', 'S', 'بڕوام بە نیگاهام نییە و کاریش بە نیگاهام ناکەم، بەڵکو بڕوام وایە کە دەبینم و دەیستم', NULL, 7, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(26, 'SN', 'S', 'زۆر مەیلەهە ڕاستیەکان بزادم', NULL, 8, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(27, 'SN', 'S', 'لە کارە وردەکاندا بەخوێنمام', NULL, 9, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(28, 'SN', 'N', 'هەست بە کۆسەکانی ڕوونگا و توانا نوێکان دەکەم', NULL, 10, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(29, 'SN', 'N', 'تەمەرکیزم لەسەر پێهەڵگەیاندنی شتەکان دەکەم', NULL, 11, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(30, 'SN', 'N', 'ڕەقم لە دووبارەکردنەوەی شتەکانە', NULL, 12, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(31, 'SN', 'N', 'حەزم لە فێربوونی مەهارەت و کاری نوێیە', NULL, 13, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(32, 'SN', 'N', 'بە توانا و خێرایی زۆرەوە کار دەکەم', NULL, 14, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(33, 'SN', 'N', 'زۆر بەخێرایی دەڕۆم بۆ کوتایی', NULL, 15, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(34, 'SN', 'N', 'کار بۆ هێنانەدی هەست و حەزەکان و بیرکردنەوەکانم دەکەم', NULL, 16, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(35, 'SN', 'N', 'هەندێک جار ڕاستیەکان بەهەڵمەگرم', NULL, 17, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(36, 'SN', 'N', 'ڕەقم لەوەیە کات بە شتە وردەوە بکوژم', NULL, 18, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(37, 'TF', 'F', 'حەزم لە گونجان و پێکه‌وه‌ژیانی ده‌م و هەولی بەدەستهێنانی دەم', NULL, 1, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(38, 'TF', 'F', 'بەم بیر و ڕا و هەڵکەوتەکانی خەڵکەوە دەچم', NULL, 2, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(39, 'TF', 'F', 'باش دەنوێنم کەم سی شیاو هەلیزترم', NULL, 3, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(40, 'TF', 'F', 'هەندێکجار پێویستم بە دەستخۆشی هەیە', NULL, 4, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(41, 'TF', 'F', 'لە کاتی بەرەنگاربووندا خۆم دەخەمە جێی کەسە بەرامبەرە باشەکان بەرەنگار دەکەم', NULL, 5, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(42, 'TF', 'F', 'حەزم لەوەیە لەگەڵ خەڵکی دڵخۆشدا بم', NULL, 6, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(43, 'TF', 'F', 'گرنگی بە خەڵکی خاوەنکار و فیکرد دەدەم', NULL, 7, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(44, 'TF', 'F', 'بەخشینی ماددی و سوزداریم هەیە', NULL, 8, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(45, 'TF', 'F', 'لە کاتی زۆرکردنی بەرامبەردا زۆر گوێبیار دەم و بیری لە دەمکەوە', NULL, 9, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(46, 'TF', 'T', 'بەتوانام لە دانانی شتەکان لە شوێنی گونجاو خۆیان', NULL, 10, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(47, 'TF', 'T', 'وەزع دانەوەم بۆ فیکری خەڵکی زیاتر وکەلە هەست و سوزیان', NULL, 11, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(48, 'TF', 'T', 'چاوەڕوانی نەجامی مەرجی دەکەم لە هەلسفتگاندنەکاندا', NULL, 12, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(49, 'TF', 'T', 'پێویستم بەوە هەیە کە بە دادپەروەرانە مامەڵە لەگەڵدا بکرێت', NULL, 13, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(50, 'TF', 'T', 'بەلا یە نەما دەچم کە خوگر و ڕاستەقینە بم', NULL, 14, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(51, 'TF', 'T', 'توانای دەمەقاڵی و سەرزنشتکردنم هەیە نەگەر پێویست بکات', NULL, 15, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(52, 'TF', 'T', 'لەوانەیە هەستی خەڵکی بەرەنگاریکەم بە یەک نموونە بزانم', NULL, 16, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(53, 'TF', 'T', 'توانای شیرکردنەوەی کێشەکەم هەیە', NULL, 17, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(54, 'TF', 'T', 'بە یەک مەرجی هەست و سوزم دەگونجێنم', NULL, 18, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(55, 'JP', 'P', 'ڕێز لەوە دەگرم کە کارەکان کراوە بن بۆ ڕۆژی گوڕانکاران پاشەڕۆدا بێت', NULL, 1, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(56, 'JP', 'P', 'توانای گونجام هەیە لەگەڵ گوڕانکاری نوێگان', NULL, 2, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(57, 'JP', 'P', 'کێشەی بەرەنگاربوونم هەیە و هەست دەکەم زانیاری تەواو نەبێت بۆ چارەسەر', NULL, 3, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(58, 'JP', 'P', 'پلانی جۆراوجۆر دادەمەزرێم و دەست بە جنبەچیکردنیان دەکەم، بەلام فورسە لەسەرم هەموویان تەواو بکەم', NULL, 4, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(59, 'JP', 'P', 'کارە فورسەکان دوادەخەم', NULL, 5, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(60, 'JP', 'P', 'درەنگ دەکەمە نەجام', NULL, 6, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(61, 'JP', 'P', 'لیستی نوسراو بەکارناھێنم بۆ کارەکان کە پێویستە نەجامی بدەم', NULL, 7, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(62, 'JP', 'P', 'نەفەریم هەیە لەگەڵ مامەڵەی کار و کاتدا', NULL, 8, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(63, 'JP', 'P', 'دەمەوێت هەموو شتێک دەربارەی کارە نوێکان بزانم', NULL, 9, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(64, 'JP', 'J', 'بە جددی کار دەکەم کاتێک پلان دادەنێم و بە دوای پلانەکەشمەدا دەمێرم', NULL, 10, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(65, 'JP', 'J', 'حەزم لە نەجامدان و تەواوکردنی کارەکانە', NULL, 11, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(66, 'JP', 'J', 'زوو بەرەنگار دەبم', NULL, 12, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(67, 'JP', 'J', 'ڕەق لەوەم پلان و پرۆژەکانم پێ ببردرێت', NULL, 13, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(68, 'JP', 'J', 'هەست بە دڵخۆشی و ڕێزمانداری دەکەم کاتێک حوکم لەسەر کەس و حالەتەکان دەدەم', NULL, 14, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(69, 'JP', 'J', 'بۆ دەستپێکردنی کارەکان پێویستم بە زانینی بنەمای بنەڕەتیەکان هەیە', NULL, 15, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(70, 'JP', 'J', 'بەرنامەکان بە ڕۆژ و سەعات دیاری دەکەم', NULL, 16, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(71, 'JP', 'J', 'لیست بەکار دەهێنم بۆ چارەسەرەکان کە دەمەوێت نەجامیان بدەم', NULL, 17, '2026-01-26 13:15:45', '2026-01-26 13:15:45'),
(72, 'JP', 'J', 'زۆر توندم لە مامەڵەکردن لەگەڵ کاتدا', NULL, 18, '2026-01-26 13:15:45', '2026-01-26 13:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_30_161246_create_systems_table', 1),
(6, '2025_09_30_163354_create_provinces_table', 1),
(7, '2025_09_30_164658_create_universities_table', 1),
(8, '2025_09_30_165714_create_colleges_table', 1),
(9, '2025_09_30_174818_create_departments_table', 1),
(10, '2025_10_02_213115_create_students_table', 1),
(11, '2025_10_04_093909_create_result_deps_table', 1),
(12, '2025_11_01_035416_create_teachers_table', 1),
(13, '2025_12_15_095234_create_mbti_questions_table', 2),
(14, '2025_12_15_095314_create_mbti_answers_table', 2),
(15, '2025_12_15_120000_add_mbti_type_to_students_table', 2),
(16, '2026_01_26_192721_create_request_more_departments_table', 3),
(17, '2026_01_28_212032_create_ai_questions_table', 3),
(18, '2026_01_28_212046_create_ai_answers_table', 3),
(19, '2026_1_20_122000_create_ai_rankings_table', 3),
(20, '2026_1_26_125000_add_ai_rank_to_students_table', 3),
(21, '2026_1_26_125001_add_rank_to_result_deps_table', 3),
(22, '2026_01_29_115309_create_backups_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `geojson` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `geojson_path` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `name_en`, `geojson`, `status`, `geojson_path`, `image`, `created_at`, `updated_at`) VALUES
(1, 'هەولێر', 'Erbil', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[43.82815718730448,37.01184971478694],[43.84800093720115,37.02960056935151],[43.86164349938767,37.03502651387388],[43.86980837595961,37.03668015971538],[43.87445927118346,37.040323431065495],[43.87838668652779,37.04528440402949],[43.88272750236917,37.05611059607205],[43.88613814730616,37.0620533820422],[43.89192589492543,37.066084082093276],[43.90939254379998,37.07414568317648],[43.9468062547476,37.08623783447729],[43.951353796008945,37.08866670443749],[43.9516638448776,37.090682032611625],[43.94949344259001,37.09285248342496],[43.947736439538225,37.0959530498785],[43.95207726735581,37.09835604093544],[43.96065556350716,37.10075903799938],[43.97863894862339,37.107580263158304],[43.98670047292775,37.11155934610898],[43.99248823839808,37.11590019640101],[43.99620894114402,37.12140373550011],[44.01119509912673,37.138353563865635],[44.01677616128128,37.142358528896864],[44.03755008479459,37.15124686434219],[44.05046918866319,37.15538095661454],[44.0557402007356,37.15855913981897],[44.05780725453817,37.16140131996139],[44.05325972194204,37.168300092136576],[44.053879832082636,37.17222746710684],[44.056980433194596,37.17780859678992],[44.06896936146654,37.19065016992309],[44.07672081077235,37.19687710276135],[44.08757287202938,37.20387930724203],[44.09852825965227,37.20767744885326],[44.09945845213696,37.21039054564155],[44.09935510020591,37.21426628268207],[44.09666791221276,37.22571254277728],[44.09511763558141,37.22715956460637],[44.09325728226022,37.22777967446834],[44.08705610180905,37.22793466434253],[44.08333540658418,37.22896823207001],[44.08075158763071,37.23072523957644],[44.07889122806502,37.23359324322791],[44.07796104597894,37.2395618399188],[44.076204045054105,37.24529791024845],[44.07589399346607,37.24901864779054],[44.07651412170935,37.25302363120493],[44.07930462683298,37.259586424728795],[44.088296325776064,37.27413333720917],[44.08829634189398,37.27744071104139],[44.086952741268995,37.280024468546195],[44.08395551777724,37.28131642531453],[44.08209517413311,37.28266005021399],[44.07982140680571,37.283538528892386],[44.077754342373616,37.28452034126821],[44.07620405896365,37.28581230430687],[44.07548058753146,37.287414268675626],[44.07331016672207,37.29170335928498],[44.070933065644354,37.293331242634046],[44.06772912814223,37.29454564216535],[44.064318489259534,37.29940325625107],[44.06902474790396,37.313726648433374],[44.08803715808389,37.311160421026855],[44.18446537038398,37.279172654733],[44.20689295412944,37.267519679298516],[44.22317103220258,37.25405797982493],[44.235004920253836,37.23677214206238],[44.24353153676415,37.213827828960326],[44.24854414562596,37.19191700093101],[44.24962936000695,37.17943718555261],[44.24833745380969,37.1696703640057],[44.240120887240934,37.15796558294992],[44.230199027824675,37.15432246823627],[44.218778529022586,37.152617111594125],[44.20596275684111,37.14662255800524],[44.18921960390873,37.129207661533776],[44.18064132339138,37.10892469466026],[44.18002120102952,37.08760813926564],[44.18746259630602,37.06693751137866],[44.22777023598402,36.994125534612905],[44.234281451482055,36.98366097807776],[44.24327315086723,36.97776986705372],[44.284821006348665,36.96916576404706],[44.29722335949379,36.96994095693715],[44.30662846739207,36.97722731800939],[44.315878538622044,36.99397043194418],[44.3159818916221,36.99397043194418],[44.31613691567917,36.99402208263601],[44.31613693500996,36.994125534612905],[44.33163986387856,37.01546789574175],[44.33515385323232,37.031151653625265],[44.34311200739264,37.042442868623475],[44.42806806768449,37.06476711884442],[44.454423041233674,37.07634260198559],[44.479227754186795,37.09200068782346],[44.50341231309542,37.11662445148326],[44.53937911823214,37.14367710466381],[44.5782397939289,37.166414729002234],[44.61027918741739,37.17835201165026],[44.62893436838083,37.17902374996345],[44.733785862372514,37.16724158934961],[44.75383631256952,37.159154203167624],[44.76613530363935,37.14192010850646],[44.752544397584245,37.11313631512197],[44.75269942072994,37.10331776359081],[44.76065760173028,37.08564447307879],[44.7665487027333,37.07895231550352],[44.77362836313478,37.07634260198559],[44.78112144982905,37.07448226864465],[44.788356173207475,37.07024491463908],[44.79223188444363,37.06373357878702],[44.797296173823156,37.048489028627976],[44.80189539626985,37.04352818990029],[44.81119714749182,37.04192618564398],[44.83093752687183,37.046835362706595],[44.84060104853139,37.04719723836152],[44.847577346598676,37.04404487373114],[44.85894615486847,37.034122965743336],[44.880753616110304,37.02492459226316],[44.887368202990736,37.01593291285214],[44.88571454758942,37.00508081434445],[44.87475914959948,36.99402208263601],[44.86917811237371,36.967357133584606],[44.874862497852625,36.949657846590505],[44.88406090827189,36.933353960033905],[44.88881514036557,36.91092638848787],[44.883440790934856,36.886845169272796],[44.86964318428458,36.868965125023536],[44.83414146665986,36.83403181301293],[44.823392767677916,36.809330473912915],[44.83124761278999,36.79191565430579],[44.85119468726234,36.78121849174775],[44.87723962349033,36.776464333398295],[44.908245493992716,36.77785965506617],[44.92271487463646,36.77589584725355],[44.93522057083272,36.76711084914994],[44.94441899703819,36.75827428730079],[44.95475428321767,36.75233149921773],[44.96643314886886,36.74933422363107],[44.97945559594762,36.7490240936476],[44.996198768650096,36.74168608401868],[45.010978226950606,36.725563029346624],[45.03526616615991,36.689596315080394],[45.045911502195715,36.66804719732632],[45.044309527757804,36.64934030053497],[45.02487918974229,36.61316680255571],[45.01418217038342,36.579008694099535],[45.01325198719014,36.55792466801938],[45.01025477503692,36.556012748114064],[44.99785240607056,36.552085209824895],[44.993408232312476,36.549656413916665],[44.991392852365934,36.53374007829291],[45.00591392922628,36.51870223555671],[45.00952465383703,36.51619584827642],[45.00948042551655,36.516091842058664],[45.0054496706405,36.506609256019445],[44.99997194485932,36.496764768783585],[44.98643272705615,36.480228348419566],[44.975270610210124,36.47376875449956],[44.96090457666813,36.46940217595903],[44.9263847278851,36.4713917574938],[44.883699980854544,36.47999580960507],[44.863236114493304,36.47960824502345],[44.84111859126931,36.47565494357784],[44.78923547212534,36.454622733187236],[44.745517193445046,36.44526920904545],[44.699835241505426,36.439404013072895],[44.671206493362774,36.446173598835664],[44.65467004541102,36.44811151493916],[44.633792764855066,36.44260796847846],[44.60320030897114,36.42382354195719],[44.58397667853309,36.40571096488381],[44.57281457845217,36.39253352238034],[44.53984499779929,36.36896898384339],[44.53953494220083,36.34778166627178],[44.545116006567184,36.336077003724895],[44.60268353538208,36.265125167752686],[44.61239871045262,36.2740910635832],[44.61890993367027,36.277966761032395],[44.62945195050105,36.27760514910692],[44.639373810034094,36.27088713341081],[44.68949995727888,36.221794566719765],[44.69518436188973,36.21424978543359],[44.697974883801365,36.205955690680796],[44.68867312921981,36.19742909817666],[44.665418733851226,36.1886699376097],[44.65766727506495,36.18388990192668],[44.65249962326814,36.17577666056843],[44.64691856941927,36.168981211051374],[44.63916710857168,36.16267670462791],[44.61766971918828,36.14862075045837],[44.62201053446446,36.13596000084342],[44.627384884451025,36.12678744157928],[44.72763715738877,36.068935641635264],[44.750478139476385,36.06092578838921],[44.762363722395534,36.05459544317738],[44.77197553317442,36.04821337445047],[44.84525272257515,35.97643485332002],[44.87677534298212,35.95240529507738],[44.901476671591475,35.93829759970084],[44.96927615479125,35.91090911272407],[44.975890738172474,35.904294527634406],[44.97733767797925,35.901452323107456],[44.977647736863894,35.89871347474827],[44.97713097177766,35.896439705552275],[44.97558068493227,35.89414014066388],[44.97341026518913,35.88822313527356],[44.97144656769489,35.88543264097773],[44.96503868704164,35.88122099864833],[44.957907335478446,35.879128082412294],[44.9472620007169,35.877887904229475],[44.924421013213575,35.87840464499291],[44.91491255323311,35.88060090839186],[44.9096415483933,35.883391392132154],[44.90778120097229,35.885587664099845],[44.90550744404334,35.886776250545545],[44.90333702355935,35.888171460790744],[44.89868614587843,35.89215055252872],[44.89527550468079,35.8938042173953],[44.89041792301746,35.89445018904366],[44.87966922568286,35.89323579571151],[44.87625857883649,35.89359751857183],[44.87005740749532,35.89581960789953],[44.86654340864621,35.89602630708357],[44.858585238484665,35.894760237498936],[44.855691359945965,35.89339081978193],[44.85507123944728,35.89072946302942],[44.86592329921182,35.87442559606243],[44.86695682184621,35.87194509957496],[44.861375766429624,35.86566640659841],[44.84607954620383,35.86132560813792],[44.83646772878349,35.85349662317647],[44.83347049728566,35.8527473260662],[44.83099002689754,35.854142574498326],[44.82892296954382,35.859542764299476],[44.82788944552724,35.87442559606243],[44.82602908913468,35.87719026692142],[44.82292850061503,35.87920563119719],[44.819207795738016,35.88026498868661],[44.816107210257016,35.880161640366914],[44.81186974231261,35.87695769616424],[44.809079214904024,35.873753759981845],[44.80742557088678,35.87060150558362],[44.80649539945818,35.863961104599554],[44.805668576820466,35.86067965321008],[44.80256798724977,35.858457549676224],[44.7979171035448,35.85887093600916],[44.789855578871034,35.86409025075938],[44.78613488552071,35.868637828536535],[44.78179405605074,35.8725651856383],[44.77734988316352,35.874012129503164],[44.77104536922645,35.87442559606243],[44.762570429525034,35.87189342574989],[44.75709272760525,35.868947870730985],[44.75140832413504,35.86719089073687],[44.746137324668,35.867552606229744],[44.74034955867405,35.87160918214096],[44.735285271595735,35.87830129682351],[44.73011763245041,35.879541550624175],[44.722366165447575,35.87985159545797],[44.69849164950767,35.873857107784126],[44.692703895499555,35.873133673343325],[44.68774295189789,35.87380543388195],[44.6840222540046,35.87799125236549],[44.68371219241979,35.88091095348251],[44.683918898586924,35.883365592596896],[44.683608837127544,35.88574268724077],[44.68185183638724,35.88791308840803],[44.67895795967441,35.88946339870674],[44.67461713740486,35.88956674777915],[44.66366172984433,35.88439907876052],[44.65994103086288,35.88388233557419],[44.655910266097095,35.88716377118526],[44.653119744541236,35.89041941555473],[44.6484688607942,35.892202227172746],[44.64557498453083,35.892589824678765],[44.62728152630577,35.88569101285838],[44.62139041113644,35.894165940417],[44.62139041469552,35.903777776378284],[44.618289826848525,35.907343439543524],[44.61498254067028,35.90783439221955],[44.60423384109422,35.90351940082901],[44.59958296081365,35.900935573006805],[44.59668908477442,35.897628302115784],[44.59296837169373,35.89003181870851],[44.58935102864823,35.88512259478996],[44.58097944180436,35.879515675949555],[44.575088338007376,35.87442559606243],[44.57105756971927,35.86985219850104],[44.566096634102706,35.86551138588821],[44.56206586572747,35.86613146884127],[44.55152387502491,35.87171252977024],[44.54759647332393,35.8709374227536],[44.5437724181613,35.86868950223041],[44.543152291478215,35.8654855114969],[44.544082474997445,35.86212658367333],[44.5453227023801,35.85892260931012],[44.54594283063584,35.85551202546711],[44.54408247994876,35.850861148148304],[44.54046511530605,35.85026679620969],[44.53664107039618,35.85220472150452],[44.530233187248896,35.85990447763874],[44.5260990756448,35.86347016879197],[44.518761027812786,35.86564060742021],[44.51276655512427,35.86427114566433],[44.50408491568455,35.86024039201001],[44.499537388614996,35.859697784289565],[44.49664350827726,35.860989693483106],[44.493956338065296,35.86380608409523],[44.471942170040265,35.86941293416313],[44.45054813585861,35.85923272437537],[44.441866495358994,35.859181051061945],[44.429360794440434,35.86088634671712],[44.421919382404184,35.86016284437196],[44.41664839131087,35.85716564302619],[44.41230757082788,35.853651642438315],[44.406726514632716,35.850886947030105],[44.38801965389546,35.84434991800261],[44.384608995603685,35.84223111266986],[44.37944136403714,35.83360121004757],[44.3764441240118,35.83313608432273],[44.373343535430855,35.83378202546176],[44.36889937262117,35.83546156418858],[44.35308638183189,35.83758028321268],[44.34719526188231,35.83897551525036],[44.33489627150556,35.84409147922495],[44.33107221753208,35.84393646111217],[44.32621463532294,35.837683628110895],[44.32042687394915,35.83347199172627],[44.30606083143486,35.827141663618924],[44.29624230065765,35.8250745583942],[44.288077432191066,35.8246095109417],[44.28228966944565,35.82564302516524],[44.26337609858933,35.83349786547526],[44.24146528358161,35.83690846636069],[44.18658491785989,35.845641811806395],[44.16984175504819,35.84292880622524],[44.14679405689862,35.83564237986253],[44.13222130186243,35.803835530224625],[44.1234363150336,35.78900442759211],[44.11413456189376,35.784250221901395],[44.092843859153824,35.781046213920035],[44.05543013480999,35.779857724495464],[43.94990685376145,35.84127513170665],[43.92727257654845,35.84812225755166],[43.911872995230425,35.84577095612883],[43.90184777813647,35.82367934132255],[43.829707478950056,35.760013975447094],[43.768522573056515,35.71789762942807],[43.762321402429215,35.709293510930095],[43.763044873513984,35.70288563886509],[43.76945275742962,35.698312312723566],[43.79146690803432,35.678055093807146],[43.7976680813447,35.669011731455846],[43.80169884712241,35.65986502314917],[43.80107872929176,35.64870290959913],[43.792603787443085,35.63958198203882],[43.77968468485749,35.62978932772942],[43.76449181237592,35.61454477158459],[43.75767052575689,35.60284007266326],[43.75208947187809,35.577957876832016],[43.741857535358534,35.548088879442936],[43.73193566284243,35.53736603313095],[43.721083612811626,35.53372285047707],[43.699792924721116,35.5321984060194],[43.66734012214868,35.516178697821616],[43.63395714907462,35.51387910747675],[43.61163293067011,35.51434419877821],[43.59809370414895,35.5092799003622],[43.588378535730286,35.49845369058757],[43.57669965991428,35.469489040523904],[43.568018017942485,35.45762929312746],[43.55220503416394,35.45031710157972],[43.53267133702333,35.447707407451105],[43.4898832557264,35.44607962660774],[43.47448367948663,35.442152221845554],[43.454639925052234,35.42763112014297],[43.44451134450453,35.42602915266156],[43.43386599868839,35.426339207108036],[43.41288537308026,35.43106763018282],[43.40327355195072,35.434762472145735],[43.35821170553267,35.45822358655148],[43.36627322583417,35.460083917918425],[43.36865034334365,35.46091074933891],[43.37123416247814,35.4640371629366],[43.37237104663324,35.46938568595498],[43.373404576389746,35.481633009744144],[43.37764204100358,35.494965510881954],[43.378882275010426,35.50139922692313],[43.37939904418075,35.51044262513633],[43.37505822182618,35.588241486583726],[43.37154422381743,35.603434351987424],[43.36379275848425,35.61526825314045],[43.35759158716661,35.620694282923374],[43.35221723994974,35.62317476080885],[43.34756635920554,35.62384654513934],[43.342915481776394,35.62539684684892],[43.340228303353996,35.62805815832949],[43.33919477780775,35.632011424477554],[43.341985307885295,35.63852266698931],[43.343949014561716,35.646351660809835],[43.33991825256778,35.65885735783085],[43.33495731484107,35.66844333179957],[43.33020307887119,35.674489452821994],[43.32555219722577,35.678726905179644],[43.32286502833823,35.68203422547492],[43.32162479157847,35.68578075673473],[43.325655553555436,35.691723546604365],[43.32782595959728,35.696090182710925],[43.334337192401,35.72345286369099],[43.317904083486724,35.75494963361067],[43.316663857238446,35.76063408338626],[43.315837034777495,35.76910902135208],[43.31976444313861,35.78086540581523],[43.34136519041449,35.82130221078291],[43.346636179034206,35.827813400192255],[43.352217232532276,35.83060391994071],[43.35934858141056,35.83055224777307],[43.36720339940226,35.83192167373338],[43.37454146334483,35.83781284685265],[43.38539350116048,35.85788906846954],[43.3943852058613,35.9272388717702],[43.394281847417155,35.94581651901486],[43.39087120244841,35.95294786719324],[43.37795210166417,35.95307709763059],[43.361622347204566,35.954808245181155],[43.350976997797346,35.96367072908401],[43.34477583611562,35.988837207483826],[43.352940702999625,35.99912076769908],[43.482545197393506,36.04330413083713],[43.49339724655779,36.05157234987711],[43.50755658731352,36.06872891372741],[43.5104504808009,36.07387079188262],[43.51169071190044,36.07870252214785],[43.51014041093548,36.098442875979096],[43.51045047638463,36.10513500891549],[43.51262088938157,36.113532445135846],[43.53401492666264,36.145778508176726],[43.53670210127679,36.15228974249502],[43.53897585873019,36.1594468975402],[43.54404015782478,36.168361126247525],[43.54755415329327,36.17146170397901],[43.555925728657606,36.17244352017491],[43.5753560771932,36.18407076860293],[43.586828246837186,36.20365614543092],[43.592202586677814,36.21530913212184],[43.59747358477357,36.22182037340256],[43.628996209346774,36.244997261862004],[43.637987901998486,36.25484158242361],[43.649666777732776,36.271145512120015],[43.657624944539734,36.27987880666298],[43.665479781541066,36.2857183454634],[43.688114043210575,36.29375394836707],[43.70227338014075,36.30742233728075],[43.71012821447231,36.310367981597686],[43.728628383156824,36.31997982793064],[43.744751406924294,36.33207198889479],[43.78671267309382,36.3727671969216],[43.803765910616185,36.39620253616406],[43.81100059864335,36.416149575558464],[43.809967084317734,36.430851598442686],[43.808416787969364,36.43997247066419],[43.80407595943333,36.44911915438875],[43.80469608938859,36.452891602080456],[43.80748661457751,36.45542373216918],[43.81100059032995,36.45568200253953],[43.82216272248908,36.454080155288544],[43.82939740491854,36.45402838087882],[43.83663210644315,36.45609544596768],[43.84459027739934,36.45966111974918],[43.853995393740085,36.46552642303741],[43.85740603613828,36.470332322564474],[43.858646261310724,36.47565494357784],[43.85864627000993,36.47999580960507],[43.85823285306162,36.48374232443923],[43.85678592048093,36.48728219967874],[43.854512167616456,36.490589545272115],[43.85244511150419,36.494956207959916],[43.85182499649421,36.50467138745875],[43.854202103712296,36.508908814330155],[43.858336211285064,36.51097583209302],[43.88355431738336,36.50844372458388],[43.88985885211661,36.50862464296651],[43.89885053457011,36.51128589262056],[43.9035014231512,36.51686699717619],[43.90556848753436,36.523119885107384],[43.90463829757784,36.53526377250282],[43.905878538176374,36.54252434100797],[43.908462349263075,36.54523730177314],[43.911459594837886,36.545650779024136],[43.914663537406575,36.543454549826286],[43.91776410559848,36.54074144300352],[43.92045127834438,36.53722743412278],[43.92551557155466,36.53609056904056],[43.93171674300672,36.53769253616116],[43.94577275038597,36.546270919936624],[43.95269737420796,36.553583040262524],[43.962619242913554,36.560145913178566],[43.96654667061476,36.56479689051905],[43.96933718542338,36.56882758317529],[43.973367964322684,36.57743180323312],[43.99868942021233,36.600763732551414],[44.0070609749368,36.60631880650543],[44.01243532619967,36.60854090438259],[44.01512250730439,36.60678393838417],[44.01822310531721,36.60585382535713],[44.022977326011556,36.60634469733606],[44.02411420291763,36.6086959486422],[44.02049687460966,36.61339861731604],[44.0192566147925,36.61701583727838],[44.019566691835685,36.62213189880686],[44.02194378779169,36.623914630819826],[44.02390751405552,36.623604688427356],[44.025767846670824,36.62083989789719],[44.02690474451875,36.617429342148064],[44.02824833399796,36.61463882713943],[44.03134889795271,36.61200321364304],[44.035379662208705,36.610091197463],[44.04023727014608,36.609264494629485],[44.044888139133604,36.609755368175755],[44.05036585040613,36.61133155397116],[44.05501672116957,36.6118999508332],[44.05946088264776,36.61179653750446],[44.06163130186654,36.61071137553691],[44.06225142156852,36.60828259768552],[44.06101118384183,36.60494945340859],[44.059047472411244,36.60122871148609],[44.058117307729916,36.59732719978089],[44.059047473833246,36.59373563592618],[44.06214807150276,36.590790141075594],[44.06876263872544,36.589110574402525],[44.0718632434653,36.59035090659036],[44.07341352298731,36.59381315698032],[44.073723587104766,36.597688915785575],[44.075273865836635,36.603063190077236],[44.0779610649383,36.605569628497214],[44.08137171062404,36.606680676079044],[44.08519575100731,36.60634469733606],[44.088813096624904,36.60662889440211],[44.09883832692597,36.610401286454916],[44.104212682167834,36.61174490610828],[44.10927695763622,36.6114864481072],[44.11485803681363,36.61022050155109],[44.118475362260114,36.6083858601455],[44.12012901175932,36.6064738504425],[44.11981895192618,36.60373499507814],[44.12105919354971,36.600789472720244],[44.12498660634672,36.59931671458262],[44.13532187972623,36.60084110358706],[44.14803429851931,36.609832890390884],[44.16529421193159,36.61841109698075],[44.19257937261149,36.638254871474864],[44.20022749299622,36.64256989719231],[44.21728070878284,36.64727241461294],[44.23309370225115,36.66721954610355],[44.261309046141086,36.68750260787869],[44.29706913426397,36.72584650582653],[44.30213341674088,36.73463145465761],[44.30265018008207,36.73943735924615],[44.301823350005236,36.743106349603515],[44.299963002747674,36.746103607160954],[44.29779258802013,36.747757228684954],[44.29551884617183,36.748015720197884],[44.29314171068574,36.74713712030399],[44.29066126319024,36.745173521487985],[44.28838748043744,36.74450162619031],[44.28570030960416,36.74548349977017],[44.279705835004144,36.75674891724619],[44.27660525390912,36.76413867264834],[44.26616662536061,36.77282036674363],[44.2652364311382,36.77736779944566],[44.26430627913766,36.785894534612304],[44.26244591106182,36.78987353128702],[44.25696822929951,36.79413694195364],[44.24859664050174,36.798193506797766],[44.23175010454272,36.80883875374906],[44.13749229961233,36.84209257946858],[44.130360953611,36.84614918463685],[44.125710077279024,36.850334989443496],[44.122299433760915,36.85550264011051],[44.11754520431867,36.858086475343576],[44.11413455395963,36.85932667644303],[44.08416223618104,36.86160050788292],[44.07496381056572,36.86320237115254],[44.070622991909964,36.86307318610812],[44.06617883965808,36.86206560355497],[44.05853072855619,36.86134213822042],[44.05481002544164,36.86325416562719],[44.0465417866907,36.87382195029087],[44.04561161016139,36.876638308369536],[44.04705855109465,36.879325487198926],[44.04798871570911,36.88348538126036],[44.047575311021404,36.88813629811052],[44.0411674286585,36.89606860853589],[44.03713666674671,36.89885913081885],[44.03279584354185,36.89989264840869],[44.029075142225366,36.89911751011636],[44.01274540894405,36.898575004010894],[44.01047161878737,36.90046105342338],[44.01057498603931,36.902373155203314],[44.01326214645002,36.90376834886827],[44.017292930889035,36.90446609700147],[44.019773379996224,36.906042130235434],[44.020393513938934,36.90885857872653],[44.01574261991169,36.91521470198706],[44.012952110870536,36.92014989905125],[44.011711877029555,36.925033322863996],[44.00654422050984,36.93115690995439],[44.00054977189012,36.93503273181467],[43.98773399771955,36.94035531086754],[43.973781375398175,36.94929540458953],[43.95352421801221,36.95932065644642],[43.93130332611887,36.97665796558655],[43.92603235092951,36.979965364155035],[43.92045127869259,36.98117966588784],[43.91466353770433,36.98009456434263],[43.891719177343205,36.98417687736795],[43.82815718730448,37.01184971478694]]]}\"', 1, NULL, '/uploads/media_69179b8cd1826.jpeg', '2025-11-01 10:27:34', '2025-11-14 21:13:48'),
(2, 'سلێمانی', 'Sulaymaniyah', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.62728152630577,35.88569101285838],[44.64557498453083,35.892589824678765],[44.6484688607942,35.892202227172746],[44.653119744541236,35.89041941555473],[44.655910266097095,35.88716377118526],[44.65994103086288,35.88388233557419],[44.66366172984433,35.88439907876052],[44.67461713740486,35.88956674777915],[44.67895795967441,35.88946339870674],[44.68185183638724,35.88791308840803],[44.683608837127544,35.88574268724077],[44.683918898586924,35.883365592596896],[44.68371219241979,35.88091095348251],[44.6840222540046,35.87799125236549],[44.68774295189789,35.87380543388195],[44.692703895499555,35.873133673343325],[44.69849164950767,35.873857107784126],[44.722366165447575,35.87985159545797],[44.73011763245041,35.879541550624175],[44.735285271595735,35.87830129682351],[44.74034955867405,35.87160918214096],[44.746137324668,35.867552606229744],[44.75140832413504,35.86719089073687],[44.75709272760525,35.868947870730985],[44.762570429525034,35.87189342574989],[44.77104536922645,35.87442559606243],[44.77734988316352,35.874012129503164],[44.78179405605074,35.8725651856383],[44.78613488552071,35.868637828536535],[44.789855578871034,35.86409025075938],[44.7979171035448,35.85887093600916],[44.80256798724977,35.858457549676224],[44.805668576820466,35.86067965321008],[44.80649539945818,35.863961104599554],[44.80742557088678,35.87060150558362],[44.809079214904024,35.873753759981845],[44.81186974231261,35.87695769616424],[44.816107210257016,35.880161640366914],[44.819207795738016,35.88026498868661],[44.82292850061503,35.87920563119719],[44.82602908913468,35.87719026692142],[44.82788944552724,35.87442559606243],[44.82892296954382,35.859542764299476],[44.83099002689754,35.854142574498326],[44.83347049728566,35.8527473260662],[44.83646772878349,35.85349662317647],[44.84607954620383,35.86132560813792],[44.861375766429624,35.86566640659841],[44.86695682184621,35.87194509957496],[44.86592329921182,35.87442559606243],[44.85507123944728,35.89072946302942],[44.855691359945965,35.89339081978193],[44.858585238484665,35.894760237498936],[44.86654340864621,35.89602630708357],[44.87005740749532,35.89581960789953],[44.87625857883649,35.89359751857183],[44.87966922568286,35.89323579571151],[44.89041792301746,35.89445018904366],[44.89527550468079,35.8938042173953],[44.89868614587843,35.89215055252872],[44.90333702355935,35.888171460790744],[44.90550744404334,35.886776250545545],[44.90778120097229,35.885587664099845],[44.9096415483933,35.883391392132154],[44.91491255323311,35.88060090839186],[44.924421013213575,35.87840464499291],[44.9472620007169,35.877887904229475],[44.957907335478446,35.879128082412294],[44.96503868704164,35.88122099864833],[44.97144656769489,35.88543264097773],[44.97341026518913,35.88822313527356],[44.97558068493227,35.89414014066388],[44.97713097177766,35.896439705552275],[44.977647736863894,35.89871347474827],[44.97733767797925,35.901452323107456],[44.975890738172474,35.904294527634406],[44.96927615479125,35.91090911272407],[44.901476671591475,35.93829759970084],[44.87677534298212,35.95240529507738],[44.84525272257515,35.97643485332002],[44.77197553317442,36.04821337445047],[44.762363722395534,36.05459544317738],[44.750478139476385,36.06092578838921],[44.72763715738877,36.068935641635264],[44.627384884451025,36.12678744157928],[44.62201053446446,36.13596000084342],[44.61766971918828,36.14862075045837],[44.63916710857168,36.16267670462791],[44.64691856941927,36.168981211051374],[44.65249962326814,36.17577666056843],[44.65766727506495,36.18388990192668],[44.665418733851226,36.1886699376097],[44.68867312921981,36.19742909817666],[44.697974883801365,36.205955690680796],[44.69518436188973,36.21424978543359],[44.68949995727888,36.221794566719765],[44.639373810034094,36.27088713341081],[44.62945195050105,36.27760514910692],[44.61890993367027,36.277966761032395],[44.61239871045262,36.2740910635832],[44.60268353538208,36.265125167752686],[44.545116006567184,36.336077003724895],[44.53953494220083,36.34778166627178],[44.53984499779929,36.36896898384339],[44.57281457845217,36.39253352238034],[44.58397667853309,36.40571096488381],[44.60320030897114,36.42382354195719],[44.633792764855066,36.44260796847846],[44.65467004541102,36.44811151493916],[44.671206493362774,36.446173598835664],[44.699835241505426,36.439404013072895],[44.745517193445046,36.44526920904545],[44.78923547212534,36.454622733187236],[44.84111859126931,36.47565494357784],[44.863236114493304,36.47960824502345],[44.883699980854544,36.47999580960507],[44.9263847278851,36.4713917574938],[44.96090457666813,36.46940217595903],[44.975270610210124,36.47376875449956],[44.98643272705615,36.480228348419566],[44.99997194485932,36.496764768783585],[45.0054496706405,36.506609256019445],[45.00948042551655,36.516091842058664],[45.00952465383703,36.51619584827642],[45.02534427917101,36.505214742879616],[45.03816002408151,36.49400090494097],[45.03909022413743,36.47984168681918],[45.045498084290074,36.47162504703824],[45.0540763855537,36.46495885537631],[45.06208622076941,36.45534697350433],[45.06689212888778,36.44237619010896],[45.06854576360757,36.43250593808203],[45.072266491319745,36.42341101356142],[45.08373865527752,36.412817318554005],[45.110817084919375,36.40253360351013],[45.13996261832855,36.40439408656273],[45.19597986507087,36.42242913338241],[45.22130129955884,36.420930428301645],[45.2388713058203,36.40299879923361],[45.24961999693762,36.37793569482741],[45.25447759334043,36.35478471674913],[45.25700973534744,36.311686556418636],[45.26393438113324,36.294271619005045],[45.28269290301145,36.2748928375507],[45.258508341837,36.26223215715636],[45.26419274282674,36.250191517052635],[45.282796260336674,36.23840929786452],[45.29736901334903,36.22673042492497],[45.30201989341617,36.20755846825547],[45.29953942431377,36.161411413677534],[45.30460371352836,36.140379101578986],[45.335609581768054,36.107719639387014],[45.34832197990594,36.0875141395104],[45.34418787303943,36.06746372164192],[45.33188888283592,36.05159905561556],[45.31958988931225,36.031031822492075],[45.31369876621644,36.010412878024376],[45.32021000609785,35.99428987740592],[45.320210004117165,35.9940831620593],[45.32031335576665,35.99403148322804],[45.320520061224975,35.99397980439891],[45.33809004981846,35.979303705726046],[45.35958744454667,35.97687490707787],[45.38180830826321,35.98297271415397],[45.40154870822719,35.99397980439891],[45.40160038376978,35.99403148322804],[45.40185876676975,35.99403148322804],[45.40196212012099,35.9940831620593],[45.402168826560676,35.994238198566016],[45.419945513480926,35.99826892887182],[45.45312178015069,36.01165311176013],[45.479270059380084,36.012014868774976],[45.50142368125312,36.005434804319094],[45.53998986923094,35.99397980439891],[45.57693851859105,35.9662295612733],[45.5898576290099,35.959614986651616],[45.61703943269911,35.9554291992281],[45.64685673466593,35.93320833725941],[45.693210487562375,35.87987822231037],[45.71873864680953,35.82830514979324],[45.73103763278237,35.81512763357557],[45.74902103485729,35.810993539828104],[45.78674483600933,35.820243653877846],[45.79790693534358,35.81838324634266],[45.81418501819255,35.80933990600201],[45.834752244612204,35.81052849740981],[45.85407921735821,35.81833157466134],[45.87831547583976,35.83522982833048],[45.888495724859965,35.83486804669407],[45.898779333035435,35.83218086222267],[45.90911461829232,35.831302359369644],[45.94125736740757,35.84034577748437],[46.00492272370103,35.83796861637746],[46.02497319010896,35.8432913422663],[46.04430017115823,35.85228302081793],[46.06042321502861,35.85724394264126],[46.076959676289256,35.85698557649433],[46.10775883537658,35.847425463772],[46.1150452110572,35.84644360495476],[46.11985111049192,35.84287788571476],[46.124812046723065,35.8224656917239],[46.129101196827804,35.81554108127885],[46.13519901685558,35.810011708593315],[46.14336389767134,35.80453403049408],[46.16351770409405,35.79750602316444],[46.18496342358039,35.79817781966754],[46.22744144454116,35.805722542310086],[46.246406702501986,35.81140698621901],[46.26351159934618,35.8211221492448],[46.28139164079495,35.82830514979324],[46.30299238962764,35.826548149934496],[46.319632207672804,35.81688469320486],[46.32707360582824,35.80350045781493],[46.32505822251111,35.787842480711404],[46.31337935398325,35.77151275691304],[46.297514696335114,35.75988559170517],[46.26712895111342,35.744124269263196],[46.25348636831094,35.727742815433935],[46.23788008247068,35.71554716272296],[46.217002811176755,35.71358349245904],[46.17772872069103,35.71539215976825],[46.127602587195284,35.69368808738229],[46.10703536247971,35.689812329890785],[46.037065467631386,35.691569298462305],[46.01649824395275,35.68572984228516],[46.003785854549044,35.67425774514318],[45.995879353884355,35.65839304539641],[45.99236535476374,35.640874729543995],[45.992313680950566,35.62438995896656],[46.00202884858516,35.58594268059702],[45.99923832067681,35.57214507407238],[45.96869754900633,35.579741510795515],[45.96389164100645,35.579741510795515],[45.95934411785476,35.578707997715824],[45.96802575909483,35.55850251398043],[45.97923954060599,35.53948556244395],[45.983115275120355,35.53529978463077],[45.9845622157475,35.53106232092069],[45.983218629949114,35.526979889226055],[45.97923954226924,35.52300079016442],[45.97133304809999,35.51803985652468],[45.9660620537253,35.51147695280854],[45.96358158297165,35.503415420596966],[45.96399499695587,35.49416535427932],[45.977017456783045,35.4653298991185],[46.04099288231048,35.38156241544701],[46.09628666283557,35.3411514334048],[46.12016117572901,35.31851716539367],[46.127395873354665,35.28906158059265],[46.12528543950972,35.28498933360041],[46.120057818814274,35.27490223673488],[46.107655478308715,35.26275828791939],[46.0987671313321,35.25004588075676],[46.101454305725944,35.23428456984289],[46.11540694201105,35.226326396058234],[46.15416426447519,35.226636446934414],[46.16925378443466,35.216249494299184],[46.16548140375248,35.18994618039355],[46.14382897789893,35.159612116159394],[46.13039310612177,35.13139678533469],[46.15106368029161,35.111604717548865],[46.14310551060812,35.09951243423301],[46.13235681115363,35.094758194497565],[46.11995446764269,35.09274281609861],[46.094529663158966,35.08581817684634],[46.07664961660094,35.08969390687403],[46.066572714826016,35.0888154144005],[46.039752645416954,35.07553456822345],[46.02507653817689,35.064269104943925],[46.009211873024064,35.06091013612816],[45.979239543192385,35.07155548302703],[45.96637211127176,35.07072865945178],[45.956036823683746,35.07424265430947],[45.934952840026035,35.08545644213859],[45.92017338100835,35.08964223304408],[45.9131970614829,35.08731679023515],[45.89939945434141,35.06860992682676],[45.898365927243965,35.06390737030558],[45.90115645416783,35.05160837889819],[45.8990893961879,35.04690582400572],[45.89309492981788,35.044373677023735],[45.879038940868746,35.04323679529653],[45.87247603428,35.04142812250059],[45.86188236507298,35.032798157391234],[45.857024780127496,35.02127431268354],[45.85681807396262,35.00804514579915],[45.85971192859,34.994402426145804],[45.85971196178636,34.99424757445046],[45.86896205978762,34.96835777386946],[45.86632654180506,34.94954744546963],[45.85785159383283,34.931305594639255],[45.85020347935689,34.90681094684832],[45.83501061742369,34.890171190098926],[45.808035510574264,34.89787092493479],[45.79620243938422,34.903063732843954],[45.789794539021635,34.914225722156644],[45.78028608595032,35.00597728073262],[45.781526320091636,35.012824409492396],[45.7839034360655,35.01447805435182],[45.78669396294236,35.01406464268983],[45.79434207504122,35.01468476022117],[45.79837283693918,35.01432302467294],[45.80281700991813,35.01292776055974],[45.806744418921525,35.01225596757808],[45.81170535695025,35.01233348173253],[45.81614952993915,35.01323781967287],[45.820076939033925,35.0144263781821],[45.82317752489827,35.01608002345343],[45.81242882719968,35.03042023507177],[45.74400923077604,35.09002900216311],[45.73842817513407,35.09726369435466],[45.73377729649463,35.10124278128895],[45.72612918560624,35.10664297425153],[45.71951460061618,35.11002777594093],[45.71300337137031,35.10992442698624],[45.70711225732377,35.10679799737127],[45.698223910192254,35.09891734066323],[45.69688032410141,35.09374970154425],[45.69605350093091,35.08698008862871],[45.692332798267415,35.08186412343646],[45.686958449161196,35.075611274866006],[45.676209750985905,35.06527598692403],[45.67104210810007,35.05876475753809],[45.66690799323169,35.05070323424679],[45.664220818652666,35.04693085213002],[45.65615929470406,35.043778589406045],[45.65336876791405,35.042331650536845],[45.65047488761771,35.040109562940444],[45.64799441893731,35.03907603566212],[45.64644412580545,35.03969615195165],[45.64623742029866,35.04171153560865],[45.64644412591244,35.044605413528146],[45.645617303054586,35.046775825293786],[45.643343540089084,35.04863617647955],[45.64003624895667,35.04752513276654],[45.636212193131364,35.04434703270742],[45.63011437395737,35.03525197976989],[45.62753055190719,35.029903468499455],[45.62815066879044,35.021971135869855],[45.630734491097925,35.01969737452792],[45.63352501802077,35.01918061011167],[45.6368323101166,35.02029165362855],[45.63900271997204,35.02075674084893],[45.64055301292988,35.019128932621754],[45.63941613091074,35.01589915551833],[45.63507531104008,35.008483588216066],[45.626600375999615,35.00411692999764],[45.62256960915782,34.999956950861815],[45.619158978983556,34.98564265352302],[45.609340420483626,34.97851110830185],[45.61151085978616,34.97256847765367],[45.61440475029777,34.97187090246603],[45.6239131803508,34.97125060201296],[45.627633905086284,34.97063060246611],[45.630631126225104,34.96939030337593],[45.63218144057585,34.96760758174324],[45.63249146450691,34.96543698764054],[45.63228479656122,34.96259498388763],[45.632284808391546,34.95995954983853],[45.629700968946814,34.95360325455917],[45.624429946975226,34.94440470909004],[45.601382301319944,34.9140708777725],[45.593217397288804,34.90497567388068],[45.56748252501144,34.885700314494464],[45.56448531964769,34.88549375851712],[45.56117799329312,34.88606201301209],[45.5580774210433,34.885752028658594],[45.55549360494861,34.88373668072081],[45.55466678876739,34.878414044755445],[45.55270308720956,34.87345312103174],[45.54836227407025,34.86725198633233],[45.53854375155078,34.85844115155627],[45.530998988131266,34.843170747012955],[45.52758832846301,34.83149178874283],[45.51921677685125,34.81309514432113],[45.51776981203278,34.80896089407914],[45.515599391767225,34.805808576120334],[45.50970826834042,34.80353476171095],[45.504850711750194,34.80022761917516],[45.500199804779605,34.795137337599805],[45.48717738785423,34.76679305524195],[45.47622196844966,34.75066992413625],[45.472604630062165,34.74338361167487],[45.47043417600155,34.7379056686415],[45.47033085116723,34.73488275074616],[45.46919398050498,34.73077453680249],[45.46909063811774,34.727105562433664],[45.46940068825204,34.723746551392644],[45.470330825762744,34.72105916667913],[45.470950960100446,34.71837208659892],[45.47043419598188,34.71552988294842],[45.46785038497536,34.70801102216611],[45.46536990767711,34.70315339170968],[45.453897758685194,34.686901257956535],[45.450590465292294,34.67759948687513],[45.450280409211835,34.67467978490695],[45.44935021094111,34.67201832670074],[45.444492610420234,34.66692810700383],[45.43736127118496,34.66204473210019],[45.41875779143779,34.65338912929276],[45.40852581796085,34.65000409981763],[45.39974085731535,34.64829896121608],[45.39353967511937,34.64819554665491],[45.38351446390963,34.64633528803908],[45.379793730240785,34.64605089831267],[45.37235232179191,34.64654181633512],[45.359019812426695,34.637007568362236],[45.250085886097345,34.52887459836555],[45.217323012689874,34.512803142807876],[45.214532500708785,34.52830615519217],[45.212672168894024,34.53300881761901],[45.210088316551676,34.53605755831107],[45.20605755790319,34.536651855897084],[45.20295697444564,34.53667770799658],[45.19933961021272,34.53709104103061],[45.191588188109854,34.53921001302818],[45.188694284849376,34.541199425970994],[45.18631716611767,34.545617745819236],[45.18621381310024,34.549493478728586],[45.193448510630226,34.56044886251549],[45.19623904498452,34.56706348728917],[45.19778935198234,34.57316138384294],[45.197996069779336,34.57866498917158],[45.19716921161966,34.583677408439144],[45.19117475277321,34.59861193606787],[45.18993454216946,34.60463237608668],[45.18962443899441,34.610471563113066],[45.19096802815517,34.61517413009688],[45.19375858662451,34.61961847716628],[45.20760785881744,34.63235665288724],[45.212052050379164,34.63762775143358],[45.21360232080007,34.64147752070976],[45.213602346973396,34.645766809253416],[45.202336891254575,34.67126916283858],[45.19675581556206,34.694032517640295],[45.195825654966434,34.70090556203878],[45.19561893260783,34.70555635086115],[45.196239059025544,34.71041398775421],[45.19613571480885,34.71666688412658],[45.191898248621676,34.73919781351483],[45.189107681477,34.77457011517159],[45.185697047827006,34.78363938608222],[45.177325466363804,34.794336411042124],[45.16957399838585,34.80226872668728],[45.156034809751496,34.81177739046428],[45.146112922270206,34.81604062690283],[45.13360720675658,34.8193736551894],[45.11231650692254,34.8214664971234],[45.100844327795336,34.82051042191854],[45.091955998234475,34.81846929329114],[45.06859824866502,34.807100469599526],[45.06281047270904,34.80565344073238],[45.05598923510681,34.8069456347482],[45.04958131333497,34.81097615571239],[45.03996951823027,34.82159577777278],[45.019195580460995,34.83872644998901],[44.96596885763363,34.86748439694479],[44.96348839563764,34.9386170445205],[44.966795697672126,34.9520529715817],[44.96741579545422,34.95660039214842],[44.966485609983486,34.96042439620837],[44.96400515145842,34.964765273669784],[44.95191286798444,34.97672837704752],[44.865923292903645,35.02626028047151],[44.828509556169124,35.031841336927585],[44.719058871999785,35.035665391998506],[44.70190229701111,35.0306011010609],[44.679371371999764,35.035665391998506],[44.662938267007995,35.04202159304368],[44.56206587046368,35.129070540050385],[44.60640425116063,35.159792188368115],[44.68433231148795,35.17989432214371],[44.69487430218775,35.18834341202667],[44.6976648290197,35.19668915510732],[44.69084353993163,35.210486761625994],[44.68929324601734,35.21790232462979],[44.68991336550825,35.22645478823883],[44.69384077348608,35.235549834653995],[44.70407270628164,35.248158877080044],[44.716164991783984,35.25508352182158],[44.72536339637016,35.25926931056605],[44.73280480469167,35.261129675219536],[44.73631879959793,35.26557383136027],[44.739109327133114,35.27172332927783],[44.7418998562798,35.285159213967006],[44.75275190772034,35.30634655335503],[44.75698937675312,35.32182364996102],[44.7530619641093,35.336732281161765],[44.74686079296739,35.353604640822724],[44.7439669133181,35.36840994072562],[44.751511670808355,35.38796946254005],[44.78799523282121,35.42840627603288],[44.7982271635319,35.44522693726171],[44.803394809602175,35.45920542885179],[44.80225792460469,35.47062590367498],[44.78303429672998,35.492175003332584],[44.761743606204845,35.51176036849772],[44.75492231880912,35.52178560514616],[44.7524418493728,35.53160411939923],[44.75605919606871,35.56036203660284],[44.75915978603662,35.596303016947125],[44.76319054263645,35.60798186630216],[44.76536095423863,35.63353586554729],[44.76939171865028,35.64407787585151],[44.786341590333336,35.66885673385463],[44.78541141200091,35.68559988635763],[44.777143180441286,35.70541778366097],[44.73073774960239,35.73900749226529],[44.70789675676757,35.76440640668608],[44.682471960289305,35.78698905323227],[44.62728152630577,35.88569101285838]]]}\"', 1, NULL, '/uploads/media_69179ba6a687e.jpeg', '2025-11-01 10:27:34', '2025-11-14 21:14:14');
INSERT INTO `provinces` (`id`, `name`, `name_en`, `geojson`, `status`, `geojson_path`, `image`, `created_at`, `updated_at`) VALUES
(3, 'دهۆک', 'Dihok', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.06902474790396,37.313726648433374],[44.064318489259534,37.29940325625107],[44.06772912814223,37.29454564216535],[44.070933065644354,37.293331242634046],[44.07331016672207,37.29170335928498],[44.07548058753146,37.287414268675626],[44.07620405896365,37.28581230430687],[44.077754342373616,37.28452034126821],[44.07982140680571,37.283538528892386],[44.08209517413311,37.28266005021399],[44.08395551777724,37.28131642531453],[44.086952741268995,37.280024468546195],[44.08829634189398,37.27744071104139],[44.088296325776064,37.27413333720917],[44.07930462683298,37.259586424728795],[44.07651412170935,37.25302363120493],[44.07589399346607,37.24901864779054],[44.076204045054105,37.24529791024845],[44.07796104597894,37.2395618399188],[44.07889122806502,37.23359324322791],[44.08075158763071,37.23072523957644],[44.08333540658418,37.22896823207001],[44.08705610180905,37.22793466434253],[44.09325728226022,37.22777967446834],[44.09511763558141,37.22715956460637],[44.09666791221276,37.22571254277728],[44.09935510020591,37.21426628268207],[44.09945845213696,37.21039054564155],[44.09852825965227,37.20767744885326],[44.08757287202938,37.20387930724203],[44.07672081077235,37.19687710276135],[44.06896936146654,37.19065016992309],[44.056980433194596,37.17780859678992],[44.053879832082636,37.17222746710684],[44.05325972194204,37.168300092136576],[44.05780725453817,37.16140131996139],[44.0557402007356,37.15855913981897],[44.05046918866319,37.15538095661454],[44.03755008479459,37.15124686434219],[44.01677616128128,37.142358528896864],[44.01119509912673,37.138353563865635],[43.99620894114402,37.12140373550011],[43.99248823839808,37.11590019640101],[43.98670047292775,37.11155934610898],[43.97863894862339,37.107580263158304],[43.96065556350716,37.10075903799938],[43.95207726735581,37.09835604093544],[43.947736439538225,37.0959530498785],[43.94949344259001,37.09285248342496],[43.9516638448776,37.090682032611625],[43.951353796008945,37.08866670443749],[43.9468062547476,37.08623783447729],[43.90939254379998,37.07414568317648],[43.89192589492543,37.066084082093276],[43.88613814730616,37.0620533820422],[43.88272750236917,37.05611059607205],[43.87838668652779,37.04528440402949],[43.87445927118346,37.040323431065495],[43.86980837595961,37.03668015971538],[43.86164349938767,37.03502651387388],[43.84800093720115,37.02960056935151],[43.82815718730448,37.01184971478694],[43.7966345590215,37.01789583563745],[43.768729281761665,37.026758326766114],[43.69865603950496,37.03675771443403],[43.687183873585056,37.03939322321606],[43.66951054007331,37.047222236851226],[43.6647563113234,37.04838496851839],[43.6605188379956,37.04861748490016],[43.657314899151274,37.04776482471021],[43.65369754754113,37.04574943536444],[43.64935672884575,37.04202873913685],[43.64635949429891,37.038540571116634],[43.64160525992414,37.03032400679691],[43.64067507155191,37.027533414563266],[43.64077843737987,37.02399364616487],[43.63943483547434,37.01569950073864],[43.63581749605479,37.00774138569392],[43.63333703038449,37.00029999599856],[43.6327168999401,36.996475870090386],[43.632923605511294,36.992987714051026],[43.63333701648842,36.989990475722934],[43.63612755334689,36.98526213399633],[43.636644312517944,36.98249741987154],[43.63788455439138,36.979629413629965],[43.640468373791606,36.97642546630505],[43.65070032177449,36.97048274906598],[43.655764593292666,36.968363918894916],[43.66175907486664,36.96707208372729],[43.675711699800324,36.966606939155255],[43.683463174667345,36.96536675547486],[43.69183474815223,36.96174936118955],[43.70878461699682,36.95738269618467],[43.71498579138055,36.95637501858002],[43.720463493473765,36.95441131506507],[43.72366744664393,36.951336645938035],[43.72614791591596,36.94676328336274],[43.727388129439404,36.94175055767732],[43.728214958341184,36.93678965557251],[43.727904901763836,36.932190460974105],[43.72614790756689,36.92733290097223],[43.71777632705784,36.91350946276083],[43.71302208816233,36.90929779784554],[43.70847457459062,36.90686907061132],[43.70299686924745,36.905112048986894],[43.702273395128344,36.90283826973269],[43.7042370829374,36.89689539010644],[43.704753868387996,36.89343318095473],[43.70475385621698,36.89028085548547],[43.704857218991954,36.88782627646907],[43.7054773182421,36.88537155288241],[43.70578738602245,36.880358984682076],[43.706717561354374,36.87754261619475],[43.707544396821376,36.87389949225498],[43.70723433578657,36.86870600064503],[43.706097455068544,36.86245315835111],[43.70165326299121,36.85123927370828],[43.69834598706385,36.84842298646164],[43.69462527430253,36.84826790645049],[43.69111127791898,36.850128265898],[43.674058045365776,36.8546757419595],[43.6648596425195,36.853487189974466],[43.65762495327603,36.850334989443496],[43.654317663691906,36.846691813081826],[43.652560659937336,36.84162749297384],[43.65256066817451,36.83591729008263],[43.651630499291855,36.8310597433494],[43.64883997228549,36.82651222256917],[43.642225382406956,36.82622796555534],[43.6279626751624,36.82847582641937],[43.48295861125101,36.85979177106829],[43.45102256384973,36.87227156396205],[43.43324587271926,36.88550074078649],[43.40306685865733,36.90291581393713],[43.38963098259131,36.91441380383689],[43.37402468997276,36.9244906521148],[43.3587284820991,36.931001967739334],[43.32069461957543,36.934567602278875],[43.3046749301439,36.93022680529959],[43.290618937775605,36.921493470626686],[43.28328086586703,36.911468148426145],[43.25258507399861,36.88159921317693],[43.22323286892589,36.842712745314],[43.21444787884876,36.82013015981492],[43.20535282172223,36.802250087712004],[43.200908636025154,36.786462875733434],[43.18581911525147,36.777987925992086],[43.152539516705,36.76411292847636],[43.11233523735668,36.761322324262856],[42.98149051479133,36.74687877955861],[42.89953170115385,36.71011104337155],[42.88743941310251,36.7123589520393],[42.87276330334771,36.712281422293294],[42.861187772134166,36.71042101120239],[42.85167931915736,36.704659147291615],[42.84341108111155,36.69251513776892],[42.83483279227083,36.70809557727239],[42.829768502790465,36.71458097206356],[42.822327102687915,36.71980032905075],[42.807961050773685,36.725820612060964],[42.79969282211705,36.72631153990058],[42.764449505981894,36.71215225624376],[42.754010862223815,36.711506275692926],[42.747189570684974,36.71980032905075],[42.74967003245056,36.72742256420179],[42.7588684387168,36.73336536001169],[42.78129602024431,36.74029005848551],[42.77251101712595,36.74762805822683],[42.7646561979443,36.74956591788811],[42.75659466637502,36.750237665923336],[42.747189576733376,36.75395848347948],[42.729619575109595,36.7667483220467],[42.69923383368911,36.79429186317088],[42.69034548860725,36.79958870745325],[42.678253204593176,36.80175912373467],[42.66936486555242,36.799743780051],[42.66388717318683,36.79522214374238],[42.65954634917303,36.79072627222419],[42.65468874711639,36.78871079968598],[42.61252078098925,36.789330933079526],[42.596501099458685,36.79602309807499],[42.58285850977262,36.826537968309005],[42.56601200402478,36.83658909470415],[42.53490278302566,36.84953399240097],[42.514852326711406,36.8433586577048],[42.504000273148435,36.851316813938396],[42.50865114344165,36.86180708320839],[42.53490277589536,36.86320237115254],[42.52394738189661,36.87984223580783],[42.51547244392991,36.88728362590017],[42.513198683399025,36.896533720138635],[42.52064008529725,36.91844450082102],[42.504000273897056,36.92172595702716],[42.50327681256796,36.93782321244596],[42.50792767606319,36.95787358552688],[42.50761764127798,36.973066580600204],[42.48808394723074,36.955134847729816],[42.47061731631509,36.95482480525994],[42.452633911867295,36.96549595375065],[42.41460003996588,36.99554570397407],[42.397546836105015,37.0170432023331],[42.38380088595463,37.040710904987705],[42.37680573422336,37.062000673578076],[42.37718550494582,37.06223500037209],[42.37687545938621,37.07675614374616],[42.37119102976851,37.08794397643274],[42.363646288878215,37.098150169004604],[42.357238404374286,37.10998403632419],[42.401886845198476,37.11414400434873],[42.45914432091212,37.12931097734526],[42.54523725445427,37.14088648541642],[42.56125694629342,37.14662255800524],[42.56466761432776,37.15204871026313],[42.57696658329822,37.17923039242839],[42.70233361881443,37.3253455600279],[42.70615767146357,37.33322620372309],[42.707397898084295,37.340150801883276],[42.70946495320198,37.34717879228875],[42.715666140356824,37.35529206532105],[42.722177377412976,37.35890945319482],[42.77158003141029,37.374903230840566],[42.7804683822493,37.3754975288692],[42.79245732084608,37.37433484426786],[42.80113895884541,37.369089675836754],[42.80547978520975,37.351855612836374],[42.81405806187476,37.346817102692604],[42.89674035501996,37.32490630067768],[42.93704796525253,37.32015203964206],[42.979629360282516,37.33183098667663],[43.00515751287426,37.34725637254142],[43.04350143360865,37.360253040839766],[43.08370569689742,37.36883131990946],[43.11481490799522,37.371130916206404],[43.13197147162749,37.36725512437284],[43.263384636461446,37.31069525918186],[43.27030928520288,37.30867991204872],[43.27862919210867,37.307749741732536],[43.28751755229086,37.30914507287635],[43.29687098026,37.31674147767583],[43.30565597728953,37.31997127547329],[43.324156117043096,37.32221908043832],[43.336145065574364,37.32022961760872],[43.36250520682045,37.303908110608994],[43.37603925813246,37.29552821938659],[43.41676029242943,37.279172654733],[43.46326907095704,37.248683506666175],[43.479598832786266,37.24336087924947],[43.492414581026345,37.244756113536894],[43.51711592685819,37.25230092842353],[43.52951825874337,37.25390283499002],[43.542437378858594,37.25230092842353],[43.55060226892363,37.24873532173552],[43.56889572362128,37.237702391877306],[43.5942171769951,37.22946001309291],[43.61829837110236,37.22697942103629],[43.72082442975305,37.23261223768106],[43.74692103538286,37.23064857282775],[43.77064049113915,37.225842529137296],[43.78056237867081,37.220364892441594],[43.802059781900724,37.203570088552034],[43.80939781335762,37.19969424497646],[43.82200689105388,37.20238156310574],[43.83999026820125,37.21710923302898],[43.89363041741948,37.22491244173245],[43.924171178892,37.253076049019135],[43.95378176966563,37.28746668843103],[43.990368687238906,37.31250393869798],[44.035585562425986,37.31824000844659],[44.06902474790396,37.313726648433374]]]}\"', 1, NULL, '/uploads/media_69179bc138f59.jpeg', '2025-11-01 10:27:34', '2025-11-14 21:14:41'),
(4, 'هەلەبجە', 'Halabja', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[45.8,35],[46.2,35],[46.2,35.3],[45.8,35.3],[45.8,35]]]}\"', 1, NULL, '/uploads/media_69179bdeb35cd.jpeg', '2025-11-01 10:27:34', '2025-11-14 21:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `request_more_departments`
--

CREATE TABLE `request_more_departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `center_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_type` enum('student','teacher','center') NOT NULL DEFAULT 'student',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `request_all_departments` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'مۆڵەتی ٥٠ بەش',
  `request_ai_rank` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'سیستەمی AI',
  `request_gis` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'سیستەمی نەخشە (GIS)',
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_more_departments`
--

INSERT INTO `request_more_departments` (`id`, `student_id`, `teacher_id`, `center_id`, `user_type`, `user_id`, `request_all_departments`, `request_ai_rank`, `request_gis`, `reason`, `status`, `admin_notes`, `admin_id`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, NULL, 'student', 13, 0, 1, 0, 'sfrjulsdwfercbvfgrgr', 'pending', NULL, NULL, NULL, '2026-01-29 18:02:00', '2026-01-29 18:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `result_deps`
--

CREATE TABLE `result_deps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `rank` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `result_deps`
--

INSERT INTO `result_deps` (`id`, `user_id`, `student_id`, `department_id`, `rank`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 2, 1, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(2, 10, 2, 3, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(3, 10, 2, 4, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(4, 10, 2, 6, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(5, 10, 2, 8, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(6, 10, 2, 10, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(7, 10, 2, 14, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(8, 10, 2, 16, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(9, 10, 2, 18, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(10, 10, 2, 20, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(11, 10, 2, 22, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(12, 10, 2, 25, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(13, 10, 2, 27, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(14, 10, 2, 29, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(15, 10, 2, 31, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(16, 10, 2, 33, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(17, 10, 2, 35, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(18, 10, 2, 37, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(19, 10, 2, 39, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(20, 10, 2, 41, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(21, 10, 2, 43, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(22, 10, 2, 45, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(23, 10, 2, 47, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(24, 10, 2, 49, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(25, 10, 2, 51, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(26, 10, 2, 5, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(27, 10, 2, 7, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(28, 10, 2, 9, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(29, 10, 2, 11, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(30, 10, 2, 15, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(31, 10, 2, 17, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(32, 10, 2, 19, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(33, 10, 2, 21, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(34, 10, 2, 23, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(35, 10, 2, 24, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(36, 10, 2, 26, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(37, 10, 2, 28, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(38, 10, 2, 30, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(39, 10, 2, 32, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(40, 10, 2, 34, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(41, 10, 2, 36, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(42, 10, 2, 38, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(43, 10, 2, 40, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(44, 10, 2, 42, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(45, 10, 2, 44, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(46, 10, 2, 46, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(47, 10, 2, 48, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(48, 10, 2, 50, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(49, 10, 2, 52, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(50, 10, 2, 54, NULL, 1, '2026-01-20 17:56:54', '2026-01-20 17:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mark` double(6,3) NOT NULL,
  `province` varchar(255) NOT NULL,
  `type` enum('زانستی','وێژەیی') NOT NULL DEFAULT 'زانستی',
  `gender` enum('نێر','مێ') NOT NULL DEFAULT 'نێر',
  `year` int(11) NOT NULL,
  `referral_code` varchar(255) DEFAULT NULL COMMENT 'Get Relation in User Role Student column to rand_code',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `ai_rank` tinyint(1) NOT NULL DEFAULT 0,
  `gis` tinyint(1) NOT NULL DEFAULT 0,
  `all_departments` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mbti_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `mark`, `province`, `type`, `gender`, `year`, `referral_code`, `status`, `ai_rank`, `gis`, `all_departments`, `created_at`, `updated_at`, `mbti_type`) VALUES
(1, 8, 65.540, 'هەولێر', 'زانستی', 'مێ', 1, '2557', 1, 0, 0, 0, '2026-01-20 17:51:47', '2026-01-20 17:51:47', NULL),
(2, 10, 72.000, 'هەولێر', 'زانستی', 'نێر', 1, '2056', 1, 0, 0, 0, '2026-01-20 17:56:54', '2026-01-20 17:56:54', NULL),
(3, 12, 75.320, 'هەولێر', 'زانستی', 'نێر', 2026, '0', 1, 0, 0, 0, '2026-01-29 17:55:43', '2026-01-29 17:55:43', NULL),
(4, 13, 76.000, 'هەولێر', 'زانستی', 'نێر', 2005, '0', 1, 0, 0, 0, '2026-01-29 17:59:15', '2026-01-29 18:01:18', 'ESFJ');

-- --------------------------------------------------------

--
-- Table structure for table `systems`
--

CREATE TABLE `systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `systems`
--

INSERT INTO `systems` (`id`, `name`, `name_en`, `status`, `created_at`, `updated_at`) VALUES
(1, 'زانکۆلاین', 'Zankolain', 1, '2025-11-01 10:27:34', '2025-11-01 10:27:34'),
(2, 'پاراڵیل', 'Parallel', 1, '2025-11-01 10:27:34', '2025-11-01 10:27:34'),
(3, 'ئێواران', 'Evening', 1, '2025-11-01 10:27:34', '2025-11-01 10:27:34');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `referral_code` varchar(255) DEFAULT NULL COMMENT 'Get Relation in User Role Teacher column to rand_code',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `referral_code`, `created_at`, `updated_at`) VALUES
(1, 7, '2557', '2025-11-08 03:44:20', '2025-11-08 03:44:20'),
(2, 9, '2557', '2026-01-20 17:53:19', '2026-01-20 17:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `geojson` longtext DEFAULT NULL,
  `lat` double(10,6) DEFAULT NULL,
  `lng` double(10,6) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `province_id`, `name`, `name_en`, `geojson`, `lat`, `lng`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'زانکۆی سەڵاحەددین', 'Salahaddin University', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.015761,36.164786],[44.016705,36.16483],[44.017011,36.163292],[44.016088,36.163166]]]}\"', 36.164002, 44.016482, '/uploads/media_69179dede94d3.jpeg', 1, '2025-11-01 10:27:34', '2025-11-14 21:23:57'),
(2, 1, 'زانکۆی هەولێری پزیشکی', 'Erbil Medical University', '\"\"', 36.198576, 44.019037, '/uploads/media_69179e07e1691.jpeg', 1, '2025-11-01 10:27:34', '2025-11-15 18:01:43'),
(3, 1, 'زانکۆی کۆیە', 'Koya University', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.661296,36.093464],[44.659612,36.095839],[44.656693,36.097625],[44.652273,36.100806],[44.647542,36.102792],[44.646072,36.100373],[44.645506,36.098713],[44.645806,36.098445],[44.64675,36.098978],[44.646858,36.098878],[44.648767,36.099923],[44.649502,36.099091],[44.647625,36.097946],[44.649272,36.096104],[44.6491,36.096],[44.649588,36.09551],[44.653056,36.0974],[44.658281,36.092189]]]}\"', 36.097275, 44.654853, '/uploads/media_69179e236c30c.jpeg', 1, '2025-11-01 10:27:34', '2025-11-14 21:24:51'),
(4, 1, 'زانکۆی سۆران', 'Soran University', '\"\"', 36.696791, 44.526774, '/uploads/media_69179e38bda02.jpeg', 1, '2025-11-01 10:27:34', '2025-11-15 17:58:09'),
(5, 1, 'زانکۆی پۆلەتەکنیکی هەولێر', 'Erbil Technical University', '\"{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[44.016522,36.159771],[44.016142,36.161443],[44.014929,36.161378],[44.014956,36.161313],[44.015541,36.161062],[44.015391,36.16065],[44.015616,36.159654]]]}\"', 36.159944, 44.016152, '/uploads/media_69179e4c702a8.jpeg', 1, '2025-11-01 10:27:34', '2025-11-14 21:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` enum('admin','center','teacher','student') NOT NULL DEFAULT 'student',
  `rand_code` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Relation to Student column to referral_code and Teacher column to referral_code',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `code`, `password`, `phone`, `role`, `rand_code`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ئەندازیار ئاغا', 100, '$2y$12$s28JP6IlOQoVXLSN68Sn7eC.BDMGPhELgzrStuRvLMYxPEtzQuCoC', NULL, 'admin', 'pmq5gp', 1, NULL, '2025-11-01 10:27:35', '2025-11-02 15:37:20'),
(2, 'Student User', 1002, '$2y$12$zMkim.J4t2uNF/hFkEQ/4.bnA14oosPFxx5usrWE.yoRVq/m8/mnO', NULL, 'student', '7ivy81', 1, NULL, '2025-11-01 10:27:35', '2025-11-01 10:27:35'),
(3, 'سەنتەری لوتکە', 459149, '$2y$12$cwR0HIxep/zgQ7YMiYJs7.cedRFBDz0Fxm3sF.KCRtj5XAu/1.ZX6', '7501110099', 'center', '2557', 1, NULL, '2025-11-02 08:33:19', '2025-11-02 08:33:19'),
(4, 'ناز', 919435, '$2y$12$5UrnFA2KXZiJcMJox5c8n.XMeNOKRePopUgbHoo0YUqFm98b252i2', '7505556666', 'teacher', '2292', 1, NULL, '2025-11-02 08:34:02', '2025-11-02 08:34:02'),
(5, 'نور زکی', 385722, '$2y$12$ixx7LDib3PfBMCcf6Jzpw.bdPZgiYYvLDX7aFSMRCPxclt76XLWHy', NULL, 'admin', '6343', 1, NULL, '2025-11-03 12:45:36', '2026-01-21 19:45:43'),
(6, 'ایوب', 198374, '$2y$12$vevebr/pPf8GjwqpqLJgreWQddWfcvEl2f./kCnTCSIJSlOf1I6FO', NULL, 'center', '4984', 1, NULL, '2025-11-07 12:28:45', '2025-11-07 12:28:45'),
(7, 'اسلام', 769792, '$2y$12$mIc9dug7868tjYTlC0oNcO1FPHQNPJEXAXyfowramrIEOWgxJD3MW', NULL, 'teacher', '5585', 1, NULL, '2025-11-08 03:44:20', '2025-11-08 03:44:20'),
(8, 'aven', 181119, '$2y$12$gbVVnRIfFmdwx5ebB4cF2OCN21JsyWvgvaIDe7LdNKGehE.gwfLy.', '7502223336', 'student', '5288', 1, NULL, '2026-01-20 17:51:47', '2026-01-20 17:51:47'),
(9, 'banaz', 137459, '$2y$12$5VZupLiCmP0.qcBI34gE.uc55Ih9PAubCGmZIN5vkAq0GspfCLuPq', '750888945', 'teacher', '2056', 1, NULL, '2026-01-20 17:53:19', '2026-01-20 17:53:19'),
(10, 'Leslie Parker', 55001, '$2y$12$aBeyYEMtAQH2X47G8a8abeoAOmdEcdSfw988kEHi2GBMKaMPa1xyC', '7507774411', 'student', '4750', 1, NULL, '2026-01-20 17:56:54', '2026-01-20 17:56:54'),
(11, 'عبداللە', 670648, '$2y$12$p9WGhRqiEyGrumIaYkeUmedQ.WAiv3I8Llpens/EPPdLd42p0tHD.', '101', 'admin', '1855', 1, NULL, '2026-01-29 08:48:44', '2026-01-29 08:48:44'),
(12, 'Rinah Soto', 171806, '$2y$12$WtDWby38BIYTpZ/kUcHEKOEn4.2mh0TC980ElKP1mFglMwwouwruq', '2', 'student', '3295', 1, NULL, '2026-01-29 17:55:43', '2026-01-29 17:55:43'),
(13, 'Aurora Leon', 800890, '$2y$12$2y0ON46sBJtAgj31DXvX4OZQsV3Gxdrilb6/lpN89/4ycfQmlFinm', '5', 'student', '1105', 1, NULL, '2026-01-29 17:59:15', '2026-01-29 17:59:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_answers`
--
ALTER TABLE `ai_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ai_answers_student_id_question_id_unique` (`student_id`,`question_id`),
  ADD KEY `ai_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `ai_questions`
--
ALTER TABLE `ai_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_rankings`
--
ALTER TABLE `ai_rankings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ai_rankings_student_id_department_id_unique` (`student_id`,`department_id`),
  ADD KEY `ai_rankings_department_id_foreign` (`department_id`),
  ADD KEY `ai_rankings_student_id_rank_index` (`student_id`,`rank`);

--
-- Indexes for table `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `colleges_name_unique` (`name`),
  ADD UNIQUE KEY `colleges_name_en_unique` (`name_en`),
  ADD KEY `colleges_university_id_foreign` (`university_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departments_system_id_foreign` (`system_id`),
  ADD KEY `departments_province_id_foreign` (`province_id`),
  ADD KEY `departments_university_id_foreign` (`university_id`),
  ADD KEY `departments_college_id_foreign` (`college_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `mbti_answers`
--
ALTER TABLE `mbti_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mbti_answers_user_id_student_id_question_id_unique` (`user_id`,`student_id`,`question_id`),
  ADD KEY `mbti_answers_student_id_foreign` (`student_id`),
  ADD KEY `mbti_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `mbti_questions`
--
ALTER TABLE `mbti_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provinces_name_unique` (`name`),
  ADD UNIQUE KEY `provinces_name_en_unique` (`name_en`);

--
-- Indexes for table `request_more_departments`
--
ALTER TABLE `request_more_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_more_departments_student_id_foreign` (`student_id`),
  ADD KEY `request_more_departments_user_id_foreign` (`user_id`),
  ADD KEY `request_more_departments_admin_id_foreign` (`admin_id`),
  ADD KEY `request_more_departments_status_created_at_index` (`status`,`created_at`),
  ADD KEY `request_more_departments_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `result_deps`
--
ALTER TABLE `result_deps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_deps_user_id_foreign` (`user_id`),
  ADD KEY `result_deps_student_id_foreign` (`student_id`),
  ADD KEY `result_deps_department_id_foreign` (`department_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_mbti_type_index` (`mbti_type`);

--
-- Indexes for table `systems`
--
ALTER TABLE `systems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `systems_name_unique` (`name`),
  ADD UNIQUE KEY `systems_name_en_unique` (`name_en`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teachers_user_id_foreign` (`user_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `universities_name_unique` (`name`),
  ADD UNIQUE KEY `universities_name_en_unique` (`name_en`),
  ADD KEY `universities_province_id_foreign` (`province_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_code_unique` (`code`),
  ADD UNIQUE KEY `users_rand_code_unique` (`rand_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_answers`
--
ALTER TABLE `ai_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_questions`
--
ALTER TABLE `ai_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_rankings`
--
ALTER TABLE `ai_rankings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backups`
--
ALTER TABLE `backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mbti_answers`
--
ALTER TABLE `mbti_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `mbti_questions`
--
ALTER TABLE `mbti_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_more_departments`
--
ALTER TABLE `request_more_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `result_deps`
--
ALTER TABLE `result_deps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `systems`
--
ALTER TABLE `systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_answers`
--
ALTER TABLE `ai_answers`
  ADD CONSTRAINT `ai_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `ai_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ai_answers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ai_rankings`
--
ALTER TABLE `ai_rankings`
  ADD CONSTRAINT `ai_rankings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ai_rankings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `colleges`
--
ALTER TABLE `colleges`
  ADD CONSTRAINT `colleges_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `departments_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `departments_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `departments_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mbti_answers`
--
ALTER TABLE `mbti_answers`
  ADD CONSTRAINT `mbti_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `mbti_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mbti_answers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mbti_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_more_departments`
--
ALTER TABLE `request_more_departments`
  ADD CONSTRAINT `request_more_departments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `request_more_departments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_more_departments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_more_departments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `result_deps`
--
ALTER TABLE `result_deps`
  ADD CONSTRAINT `result_deps_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `result_deps_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `result_deps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `universities`
--
ALTER TABLE `universities`
  ADD CONSTRAINT `universities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
