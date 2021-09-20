-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 04, 2019 at 05:31 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gobilling_fresh`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible_to_customer` int(11) NOT NULL DEFAULT '0',
  `description_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_data` longtext COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activities_project_id_foreign` (`project_id`),
  KEY `activities_user_id_foreign` (`user_id`),
  KEY `activities_customer_id_foreign` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=414 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `project_id`, `user_id`, `customer_id`, `full_name`, `visible_to_customer`, `description_key`, `additional_data`, `created_at`) VALUES
(1, 1, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-09 06:41:02'),
(2, 1, 1, 0, 'Admin', 0, 'Add new Team Member', 'Admin', '2018-12-09 06:41:02'),
(3, 1, 1, 0, 'Admin', 0, 'Add new Team Member', 'Borna', '2018-12-09 06:41:03'),
(4, 1, 1, 0, 'Admin', 0, 'Add new Team Member', 'Md. Milon Hossain', '2018-12-09 06:41:03'),
(5, 2, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-09 06:42:52'),
(6, 2, 1, 0, 'Admin', 0, 'Add new Team Member', 'Admin', '2018-12-09 06:42:53'),
(7, 2, 1, 0, 'Admin', 0, 'Add new Team Member', 'Borna', '2018-12-09 06:42:53'),
(8, 2, 1, 0, 'Admin', 0, 'Add new Team Member', 'Md. Milon Hossain', '2018-12-09 06:42:53'),
(9, 3, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-09 06:56:37'),
(10, 3, 1, 0, 'Admin', 0, 'Add new Team Member', 'Admin', '2018-12-09 06:56:37'),
(11, 3, 1, 0, 'Admin', 0, 'Add new Team Member', 'Md. Milon Hossain', '2018-12-09 06:56:38'),
(12, 4, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-22 07:44:38'),
(13, 4, 1, 0, 'Admin', 0, 'Add new Team Member', 'rafiq', '2018-12-22 07:44:38'),
(14, 4, 1, 0, 'Admin', 0, 'Add new Team Member', 'Rafiq', '2018-12-22 07:44:38'),
(15, 5, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-24 05:45:36'),
(16, 5, 1, 0, 'Admin', 1, 'Add new Team Member', 'rafiq', '2018-12-24 05:45:38'),
(17, 5, 1, 0, 'Admin', 0, 'Add new Team Member', 'Md. Milon Hossain', '2018-12-23 13:09:29'),
(18, 6, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-22 07:48:15'),
(19, 6, 1, 0, 'Admin', 0, 'Add new Team Member', 'Rafiq', '2018-12-22 07:48:15'),
(20, 6, 1, 0, 'Admin', 0, 'Add new Team Member', 'Borna', '2018-12-22 07:48:15'),
(21, 7, 1, 0, 'Admin', 1, 'A new project has been created', NULL, '2018-12-24 05:52:17'),
(22, 7, 1, 0, 'Admin', 1, 'Add new Team Member', 'Admin', '2018-12-24 05:52:16'),
(23, 7, 1, 0, 'Admin', 1, 'Add new Team Member', 'rafiq', '2018-12-24 05:52:15'),
(24, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 06:24:49'),
(25, 7, 1, 0, 'Admin', 0, 'A new payment of <strong>$99</strong> has been paid on INV-0014', NULL, '2018-12-24 06:50:38'),
(26, 7, NULL, 0, '', 0, 'A new payment of <strong>$0</strong> has been paid on INV-0014', NULL, '2018-12-24 07:34:18'),
(27, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 08:06:00'),
(28, 7, NULL, 0, '', 0, 'A new payment of <strong>$6000</strong> has been paid on INV-0015', NULL, '2018-12-24 08:07:36'),
(29, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 08:08:41'),
(30, 7, NULL, 0, '', 0, 'A new payment of <strong>$1000</strong> has been paid on INV-0016', NULL, '2018-12-24 08:09:06'),
(31, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 08:12:00'),
(32, 7, NULL, 0, '', 0, 'A new payment of <strong>$900</strong> has been paid on INV-0017', NULL, '2018-12-24 08:12:28'),
(33, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 08:14:18'),
(34, 7, NULL, 0, '', 0, 'A new payment of <strong>$800</strong> has been paid on INV-0018', NULL, '2018-12-24 08:14:54'),
(35, 7, NULL, 0, '', 0, 'A new payment of <strong>$0</strong> has been paid on INV-0018', NULL, '2018-12-24 08:15:05'),
(36, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 08:16:13'),
(37, 7, NULL, 0, '', 0, 'A new payment of <strong>$700</strong> has been paid on INV-0019', NULL, '2018-12-24 08:16:44'),
(38, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-24 08:18:19'),
(39, 7, NULL, 0, 'Customer Techvillage', 0, 'A new payment of <strong>$600</strong> has been paid on INV-0020', NULL, '2018-12-24 08:18:43'),
(40, 7, NULL, 0, 'Customer Techvillage', 0, 'A new payment of <strong>$0</strong> has been paid on INV-0020', NULL, '2018-12-24 08:18:58'),
(41, 7, NULL, 0, 'Customer Techvillage', 0, 'A new payment of <strong>$0</strong> has been paid on INV-0020', NULL, '2018-12-24 08:20:11'),
(42, 7, 1, 0, 'Admin', 0, 'Added new team member', 'Rafiq', '2018-12-24 09:22:25'),
(43, 7, 1, 0, 'Admin', 0, 'New team member has been added', 'Borna', '2018-12-24 12:15:15'),
(44, 7, 1, 0, 'Admin', 0, 'Team member from project has been removed', 'rafiq', '2018-12-24 12:34:27'),
(45, 7, 1, 0, 'Admin', 0, 'Team member from project has been removed', 'Borna', '2018-12-24 12:34:27'),
(46, 7, 1, 0, 'Admin', 0, 'New team member <strong>Rafiq</strong> has been added', 'Rafiq', '2018-12-25 05:33:56'),
(47, 7, 1, 0, 'Admin', 0, 'Team member <strong>rafiq</strong> has been removed', 'rafiq', '2018-12-25 05:37:53'),
(48, 7, 1, 0, 'Admin', 0, 'Team member <strong>Rafiq</strong> has been removed', 'Rafiq', '2018-12-25 05:37:54'),
(49, 3, 15, 0, 'Rafiq', 0, 'Add new task assignee of task First task', 'Md. Milon Hossain', '2018-12-27 06:33:31'),
(50, 3, 15, 0, 'Rafiq', 0, 'Add new task assignee of task New task 1', 'Admin', '2018-12-27 06:35:45'),
(51, 3, 15, 0, 'Rafiq', 0, 'New team member has been added', 'Rafiq', '2018-12-27 06:37:04'),
(52, 3, 15, 0, 'Rafiq', 0, 'Add new task assignee of task New task 1', 'Rafiq', '2018-12-27 06:37:21'),
(53, 8, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-27 06:38:31'),
(54, 8, 1, 0, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2018-12-27 06:38:31'),
(55, 8, 1, 0, 'Admin', 0, 'Team member <strong>Admin</strong> has been removed', 'Admin', '2018-12-27 06:41:44'),
(56, 8, 1, 0, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2018-12-27 06:42:35'),
(57, 8, 1, 0, 'Admin', 0, 'New team member <strong>Borna</strong> has been added', 'Borna', '2018-12-27 06:42:35'),
(58, 8, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2018-12-27 06:42:35'),
(59, 9, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-27 07:02:54'),
(60, 9, 1, 0, 'Admin', 0, 'New team member <strong>Borna</strong> has been added', 'Borna', '2018-12-27 07:02:54'),
(61, 10, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2018-12-27 07:05:20'),
(62, 10, 1, 0, 'Admin', 0, 'New team member <strong>Borna</strong> has been added', 'Borna', '2018-12-27 07:05:20'),
(63, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task New task 1', 'Md. Milon Hossain', '2018-12-27 07:32:25'),
(64, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task New task 1', 'Admin', '2018-12-27 07:32:29'),
(65, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task New task 1', 'Admin', '2018-12-27 07:32:32'),
(66, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task New task 1', 'Admin', '2018-12-27 07:32:41'),
(67, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task New task 1', 'Admin', '2018-12-27 07:32:44'),
(68, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task New task 1', 'Md. Milon Hossain', '2018-12-27 07:32:47'),
(69, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task New task 1', 'Md. Milon Hossain', '2018-12-27 07:32:50'),
(70, 1, NULL, 0, 'Ratool Apparals', 0, 'Add a comment of task when changing status for payout as success this error occur, but if reload page status changing as s', NULL, '2018-12-27 07:43:45'),
(71, 1, 1, 0, 'Admin', 0, 'Update comment of task when changing status for payout as success this error occur, but if reload page status changing as s', NULL, '2018-12-27 07:45:04'),
(72, 9, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-27 08:02:49'),
(73, 9, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-27 08:05:03'),
(74, 10, 15, 0, 'Rafiq', 0, 'A new invoice has been created', NULL, '2018-12-27 09:33:31'),
(75, 9, 15, 0, 'Rafiq', 0, 'A new payment of <strong>$77</strong> has been paid on INV-0032', NULL, '2018-12-27 09:35:20'),
(76, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Admin', '2018-12-27 09:51:56'),
(77, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Md. Milon Hossain', '2018-12-27 09:51:32'),
(78, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Admin', '2018-12-27 09:51:35'),
(79, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Md. Milon Hossain', '2018-12-27 09:51:37'),
(80, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Rafiq', '2018-12-27 09:51:40'),
(81, 9, 15, 0, 'Rafiq', 0, 'A new invoice has been created', NULL, '2018-12-27 10:00:05'),
(82, 9, 15, 0, 'Rafiq', 0, 'A new invoice has been created', NULL, '2018-12-27 10:01:41'),
(83, 9, 15, 0, 'Rafiq', 0, 'A new invoice has been created', NULL, '2018-12-27 10:06:15'),
(84, 9, 15, 0, 'Rafiq', 0, 'A new invoice has been created', NULL, '2018-12-27 10:13:55'),
(85, 9, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-27 10:14:52'),
(86, 9, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-27 10:15:21'),
(87, 9, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2018-12-27 10:16:37'),
(88, 9, 15, 0, 'Rafiq', 0, 'A new invoice has been created', NULL, '2018-12-27 10:17:00'),
(89, 9, 15, 0, 'Rafiq', 0, 'A new payment of <strong>$120</strong> has been paid on INV-0040', NULL, '2018-12-27 10:52:07'),
(90, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Rafiq', '2018-12-28 10:38:57'),
(91, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Rafiq', '2018-12-28 10:39:03'),
(92, 3, 1, 0, 'Admin', 0, 'Change Priority of task New task 1', 'Medium', '2018-12-28 10:51:46'),
(93, 3, 1, 0, 'Admin', 0, 'Change Priority of task New task 1', 'High', '2018-12-28 10:51:50'),
(94, 3, 1, 0, 'Admin', 0, 'Change Priority of task New task 1', 'Low', '2018-12-28 10:51:51'),
(95, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'High', '2018-12-28 10:54:42'),
(96, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Low', '2018-12-28 10:54:44'),
(97, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Medium', '2018-12-28 10:54:46'),
(98, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Rafiq', '2018-12-28 10:55:46'),
(99, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Rafiq', '2018-12-28 10:55:49'),
(100, 3, 1, 0, 'Admin', 0, 'Add a comment of task First task', NULL, '2018-12-28 11:33:50'),
(101, 3, 1, 0, 'Admin', 0, 'Change Status of task First task', 'Testing', '2018-12-28 11:49:07'),
(102, 3, 1, 0, 'Admin', 0, 'Change Status of task First task', ' Not Started', '2018-12-28 11:49:14'),
(103, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'High', '2019-01-01 04:44:49'),
(104, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Low', '2019-01-01 04:45:00'),
(105, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'High', '2019-01-01 04:45:02'),
(106, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Low', '2019-01-01 04:45:03'),
(107, 3, 1, 0, 'Admin', 0, 'Change Status of task First task', 'Testing', '2019-01-01 04:45:10'),
(108, 3, 1, 0, 'Admin', 0, 'Change Status of task First task', ' Not Started', '2019-01-01 04:45:16'),
(109, 3, 1, 0, 'Admin', 0, 'Add a comment of task First task', NULL, '2019-01-01 05:17:18'),
(110, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 05:36:04'),
(111, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 06:52:44'),
(112, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 06:52:57'),
(113, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 07:22:11'),
(114, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 07:22:32'),
(115, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 07:25:31'),
(116, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 07:25:56'),
(117, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 07:26:07'),
(118, 3, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task First task', NULL, '2019-01-01 08:29:27'),
(119, 3, 1, 0, 'Admin', 0, 'Update comment of task First task', NULL, '2019-01-01 08:30:37'),
(120, 3, 1, 0, 'Admin', 0, 'Update comment of task First task', NULL, '2019-01-01 08:30:43'),
(121, 3, 1, 0, 'Admin', 0, 'Update comment of task First task', NULL, '2019-01-01 08:30:51'),
(122, 9, 1, 0, 'Admin', 0, 'Add a comment of task task task', NULL, '2019-01-01 08:31:12'),
(123, 9, 1, 0, 'Admin', 0, 'Add new task assignee of task task task', 'Borna', '2019-01-01 08:31:30'),
(124, 9, 1, 0, 'Admin', 0, 'Change Status of task task task', 'Testing', '2019-01-01 09:48:09'),
(125, 9, 17, 0, 'Borna', 0, 'Add a comment of task task task', NULL, '2019-01-01 10:00:47'),
(126, 9, 17, 0, 'Borna', 0, 'Add a comment of task task task', NULL, '2019-01-01 10:00:53'),
(127, 9, 17, 0, 'Borna', 0, 'Update comment of task task task', NULL, '2019-01-01 10:01:01'),
(128, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Admin', '2019-01-02 06:19:44'),
(129, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Md. Milon Hossain', '2019-01-02 06:19:46'),
(130, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Rafiq', '2019-01-02 06:19:49'),
(131, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Admin', '2019-01-02 06:26:25'),
(132, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Md. Milon Hossain', '2019-01-02 06:26:50'),
(133, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Rafiq', '2019-01-02 06:26:52'),
(134, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Admin', '2019-01-02 06:30:52'),
(135, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Md. Milon Hossain', '2019-01-02 06:30:54'),
(136, 3, 1, 0, 'Admin', 0, 'Remove task assignee of task First task', 'Rafiq', '2019-01-02 06:30:57'),
(137, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Md. Milon Hossain', '2019-01-02 06:31:11'),
(138, 3, 1, 0, 'Admin', 0, 'Add new task assignee of task First task', 'Admin', '2019-01-02 06:31:16'),
(139, 3, 1, 0, 'Admin', 0, 'Change Status of task First task', 'Complete', '2019-01-03 07:58:44'),
(140, 3, 1, 0, 'Admin', 0, 'Change Status of task First task', ' Not Started', '2019-01-03 07:58:55'),
(141, 3, 1, 0, 'Admin', 0, 'Add a comment of task First task', NULL, '2019-01-03 13:05:07'),
(142, 4, 1, 0, 'Admin', 0, 'Change Priority of task Rafiq Task', 'High', '2019-01-05 11:44:18'),
(143, 4, 1, 0, 'Admin', 0, 'Change Priority of task Rafiq Task', 'Low', '2019-01-05 11:44:20'),
(144, 1, 1, 0, 'Admin', 0, 'Change Priority of task task2', 'Medium', '2019-01-05 11:44:31'),
(145, 1, 1, 0, 'Admin', 0, 'Change Priority of task task2', 'High', '2019-01-05 11:44:33'),
(146, 1, 1, 0, 'Admin', 0, 'Change Status of task hello', 'Testing', '2019-01-05 12:59:24'),
(147, 1, 1, 0, 'Admin', 0, 'Change Status of task hello', 'In Progress', '2019-01-05 12:59:27'),
(148, 1, 1, 0, 'Admin', 0, 'Change Status of task hello', ' Not Started', '2019-01-05 12:59:30'),
(149, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Medium', '2019-01-06 05:37:12'),
(150, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'High', '2019-01-06 05:37:15'),
(151, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Low', '2019-01-06 05:37:18'),
(152, 3, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'Medium', '2019-01-06 05:38:07'),
(153, 7, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2019-01-06 11:12:22'),
(154, 7, NULL, 0, 'Customer Techvillage', 0, 'A new payment of <strong>$850</strong> has been paid on INV-0070', NULL, '2019-01-06 11:30:58'),
(155, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'Medium', '2019-01-07 07:30:02'),
(156, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'Low', '2019-01-07 07:30:19'),
(157, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'Medium', '2019-01-07 07:30:26'),
(158, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'Low', '2019-01-07 07:30:38'),
(159, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'High', '2019-01-07 07:30:44'),
(160, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'Low', '2019-01-07 07:30:47'),
(161, 5, 1, 0, 'Admin', 0, 'Change Status of task New task', 'Testing', '2019-01-07 07:30:50'),
(162, 5, 1, 0, 'Admin', 0, 'Change Status of task New task', 'Awaiting Feedback', '2019-01-07 07:30:57'),
(163, 5, 1, 0, 'Admin', 0, 'Change Status of task New task', ' Not Started', '2019-01-07 07:30:59'),
(164, 5, 1, 0, 'Admin', 0, 'Change Priority of task New task', 'Medium', '2019-01-07 07:31:01'),
(165, 5, 1, 0, 'Admin', 0, 'Change Status of task New task', 'Testing', '2019-01-07 07:31:15'),
(166, 11, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-08 05:29:04'),
(167, 11, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-08 05:29:04'),
(168, 11, 1, 0, 'Admin', 0, 'Change Status of task Task Time test', 'Complete', '2019-01-08 05:34:27'),
(169, 12, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-08 06:00:34'),
(170, 12, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-08 06:00:34'),
(171, 12, 1, 0, 'Admin', 0, 'All team member from project has been removed', NULL, '2019-01-08 06:00:57'),
(172, 12, 1, 0, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2019-01-08 06:01:09'),
(173, 12, 1, 0, 'Admin', 0, 'New team member <strong>Borna</strong> has been added', 'Borna', '2019-01-08 06:01:09'),
(174, 12, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-08 06:01:09'),
(175, 2, 1, 0, 'Admin', 0, 'Change Priority of task Task assignee default login user.', 'Medium', '2019-01-08 07:09:13'),
(176, 11, 1, 0, 'Admin', 0, 'Change Status of task Task Time test', ' Not Started', '2019-01-08 07:42:34'),
(177, 11, 1, 0, 'Admin', 0, 'Add new task assignee of task Another Task of Project Accounting Software', 'Md. Milon Hossain', '2019-01-08 07:52:00'),
(178, 12, 1, 0, 'Admin', 0, 'Add new task assignee of task Task 1 for e-commerce', 'Md. Milon Hossain', '2019-01-08 08:48:12'),
(179, 13, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-08 11:10:31'),
(180, 13, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-08 11:10:32'),
(181, 14, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-08 11:20:20'),
(182, 14, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-08 11:20:20'),
(183, 15, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-08 11:25:27'),
(184, 15, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-08 11:25:27'),
(185, 9, 1, 0, 'Admin', 0, 'Change Status of task task task', 'In Progress', '2019-01-08 12:28:28'),
(186, 14, 1, 0, 'Admin', 0, 'Change Status of task Customer Details', 'Testing', '2019-01-08 12:46:05'),
(187, 14, 1, 0, 'Admin', 0, 'Change Status of task Customer Details', 'In Progress', '2019-01-08 12:46:11'),
(188, 14, 1, 0, 'Admin', 0, 'Change Status of task Customer Details', 'Complete', '2019-01-08 12:46:14'),
(189, 14, 1, 0, 'Admin', 0, 'Change Status of task Seconfd task', 'Complete', '2019-01-08 12:46:23'),
(190, 14, 1, 0, 'Admin', 0, 'Change Status of task Customer Details', 'In Progress', '2019-01-08 12:48:00'),
(191, 14, 1, 0, 'Admin', 0, 'Change Status of task Customer Details', 'Complete', '2019-01-08 12:48:08'),
(192, 5, 1, 0, 'Admin', 0, 'Change Status of task First task', 'Testing', '2019-01-08 12:52:23'),
(193, 5, 1, 0, 'Admin', 0, 'Change Status of task First task', 'Complete', '2019-01-08 12:52:25'),
(194, 16, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-09 05:09:09'),
(195, 16, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-09 05:09:09'),
(196, 16, 1, 0, 'Admin', 0, 'Change Status of task First Task', 'Complete', '2019-01-09 05:10:04'),
(197, 16, 1, 0, 'Admin', 0, 'Change Status of task Second Task', 'Complete', '2019-01-09 05:10:41'),
(198, 16, 1, 0, 'Admin', 0, 'Change Status of task Third Task', 'Complete', '2019-01-09 05:10:55'),
(199, 16, 1, 0, 'Admin', 0, 'Change Status of task First Task', 'Testing', '2019-01-09 05:11:04'),
(200, 12, 1, 0, 'Admin', 0, 'Add new task assignee of task E-commerce project task', 'Md. Milon Hossain', '2019-01-09 06:37:19'),
(201, 12, 1, 0, 'Admin', 0, 'Add new task assignee of task E-commerce project task', 'Borna', '2019-01-09 06:37:20'),
(202, 12, 1, 0, 'Admin', 0, 'Remove task assignee of task E-commerce project task', 'Admin', '2019-01-09 06:37:44'),
(203, 1, 1, 0, 'Admin', 0, 'Remove task assignee of task dfhdfjgfjhgkjghk', 'Admin', '2019-01-09 06:55:42'),
(204, 16, 1, 0, 'Admin', 0, 'Change Status of task Fourth Task', 'Complete', '2019-01-09 07:42:01'),
(205, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'Complete', '2019-01-09 07:42:03'),
(206, 16, 1, 0, 'Admin', 0, 'Change Status of task Second Task', 'Complete', '2019-01-09 07:42:06'),
(207, 16, 1, 0, 'Admin', 0, 'Change Status of task First Task', 'Complete', '2019-01-09 07:42:07'),
(208, 16, 1, 0, 'Admin', 0, 'Change Status of task Second Task', 'In Progress', '2019-01-09 07:42:36'),
(209, 17, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-09 07:49:18'),
(210, 17, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-09 07:49:18'),
(211, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'In Progress', '2019-01-09 07:52:22'),
(212, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'Complete', '2019-01-09 07:52:30'),
(213, 16, 1, 0, 'Admin', 0, 'Change Status of task First Task', 'Complete', '2019-01-09 07:53:48'),
(214, 16, 1, 0, 'Admin', 0, 'Change Status of task Second Task', 'Complete', '2019-01-09 07:53:56'),
(215, 17, 1, 0, 'Admin', 0, 'Add new milestone ', 'Design', '2019-01-09 07:57:24'),
(216, 17, 1, 0, 'Admin', 0, 'Update milestone ', 'Design', '2019-01-09 08:01:21'),
(217, 17, 1, 0, 'Admin', 0, 'Add new milestone ', 'Planning', '2019-01-09 08:03:16'),
(218, 17, 1, 0, 'Admin', 0, 'Add new milestone ', 'Development', '2019-01-09 08:03:42'),
(219, 17, 1, 0, 'Admin', 0, 'Add new milestone ', 'Testing', '2019-01-09 08:04:08'),
(220, 17, 1, 0, 'Admin', 0, 'Add new task assignee of task Need edit option for milestone', 'Md. Milon Hossain', '2019-01-09 08:15:42'),
(221, 17, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Need edit option for milestone', NULL, '2019-01-09 10:32:32'),
(222, 1, 1, 0, 'Admin', 0, 'A new invoice has been created', NULL, '2019-01-09 09:57:38'),
(223, 1, 1, 0, 'Admin', 0, 'A new payment of <strong>$8000</strong> has been paid on INV-0075', NULL, '2019-01-09 09:58:02'),
(224, 1, 1, 0, 'Admin', 0, 'A new payment of <strong>$50</strong> has been paid on INV-0075', NULL, '2019-01-09 10:01:20'),
(225, 17, 1, 0, 'Admin', 0, 'Add new file ', 'bank11.jpg', '2019-01-09 10:33:28'),
(226, 17, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Rafiq</strong> has been added', 'Rafiq', '2019-01-09 11:03:00'),
(227, 16, 18, 0, 'Md. Milon Hossain', 0, 'Add new file ', 'Know your customer.docx', '2019-01-09 11:07:41'),
(228, 17, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Need edit option for milestone', NULL, '2019-01-09 11:11:45'),
(229, 17, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Need edit option for milestone', 'In Progress', '2019-01-09 11:14:35'),
(230, 16, 1, 0, 'Admin', 0, 'Change Status of task Second Task', 'Complete', '2019-01-09 11:22:57'),
(231, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'Testing', '2019-01-09 11:27:16'),
(232, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'Complete', '2019-01-09 11:27:18'),
(233, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'Testing', '2019-01-09 11:32:47'),
(234, 16, 1, 0, 'Admin', 0, 'Change Status of task fifth task', 'Complete', '2019-01-09 11:32:51'),
(235, 5, 1, 0, 'Admin', 0, 'Change Priority of task First task', 'High', '2019-01-09 13:12:35'),
(236, 5, 1, 0, 'Admin', 0, 'Change Status of task First task', 'In Progress', '2019-01-09 13:14:50'),
(237, 5, 1, 0, 'Admin', 0, 'Change Status of task First task', 'Complete', '2019-01-09 13:14:52'),
(238, 5, 1, 0, 'Admin', 0, 'Change Status of task First task', ' Not Started', '2019-01-09 13:14:56'),
(239, 16, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Task projetct', 'Complete', '2019-01-10 05:32:11'),
(240, 16, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Task projetct', 'In Progress', '2019-01-10 05:32:32'),
(241, 16, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Task projetct', 'Complete', '2019-01-10 05:32:34'),
(242, 18, 18, 0, 'Md. Milon Hossain', 0, 'A new project has been created', NULL, '2019-01-10 06:05:40'),
(243, 18, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 06:05:40'),
(244, 18, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Cart Module', 'Complete', '2019-01-10 06:14:59'),
(245, 18, 1, 0, 'Admin', 0, 'New team member has been added', 'Borna', '2019-01-10 06:18:37'),
(246, 18, 1, 0, 'Admin', 0, 'Add new task assignee of task Cart Module', 'Borna', '2019-01-10 06:18:59'),
(247, 18, 1, 0, 'Admin', 0, 'Remove task assignee of task Cart Module', 'Borna', '2019-01-10 06:19:18'),
(248, 18, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Cart Module', NULL, '2019-01-10 06:22:09'),
(249, 18, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Cart Module', NULL, '2019-01-10 06:22:48'),
(250, 18, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Cart Module', NULL, '2019-01-10 06:24:14'),
(251, 19, 18, 0, 'Md. Milon Hossain', 0, 'A new project has been created', NULL, '2019-01-10 06:44:56'),
(252, 19, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 06:44:56'),
(253, 19, 18, 0, 'Md. Milon Hossain', 0, 'Add new milestone ', 'Cart Design', '2019-01-10 06:46:43'),
(254, 20, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-10 06:58:41'),
(255, 20, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 06:58:42'),
(256, 20, 1, 0, 'Admin', 0, 'Add new task assignee of task when changing status for payout as success this error occur, but if reload page status changing as s', 'Md. Milon Hossain', '2019-01-10 07:01:26'),
(257, 19, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Milestone check again', 'Awaiting Feedback', '2019-01-10 07:15:47'),
(258, 19, 18, 0, 'Md. Milon Hossain', 0, 'Change Priority of task Milestone check again', 'High', '2019-01-10 07:32:49'),
(259, 19, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Milestone check', 'Complete', '2019-01-10 07:40:55'),
(260, 19, 18, 0, 'Md. Milon Hossain', 0, 'Change Priority of task Milestone check again', 'Medium', '2019-01-10 07:40:58'),
(261, 19, 18, 0, 'Md. Milon Hossain', 0, 'Add new milestone ', 'Cart Development', '2019-01-10 07:43:41'),
(262, 19, 18, 0, 'Md. Milon Hossain', 0, 'Update milestone ', 'Cart Design', '2019-01-10 07:43:55'),
(263, 19, 18, 0, 'Md. Milon Hossain', 0, 'Update milestone ', 'Cart Development', '2019-01-10 07:44:08'),
(264, 19, 18, 0, 'Md. Milon Hossain', 0, 'Change Status of task Milestone check', ' Not Started', '2019-01-10 07:53:08'),
(265, 21, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-10 07:55:55'),
(266, 21, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 07:55:56'),
(267, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Md. Milon Hossain', '2019-01-10 08:00:07'),
(268, 19, 1, 0, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Md. Milon Hossain', '2019-01-10 08:02:07'),
(269, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Md. Milon Hossain', '2019-01-10 08:04:20'),
(270, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check', 'Md. Milon Hossain', '2019-01-10 08:12:46'),
(271, 19, 1, 0, 'Admin', 0, 'Add new task assignee of task Milestone check', 'Md. Milon Hossain', '2019-01-10 08:14:16'),
(272, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-10 08:23:12'),
(273, 19, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Rafiq</strong> has been added', 'Rafiq', '2019-01-10 09:26:32'),
(274, 19, 18, 0, 'Md. Milon Hossain', 0, 'Team member <strong>Md. Milon Hossain</strong> has been removed', 'Md. Milon Hossain', '2019-01-10 09:29:03'),
(275, 19, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:29:37'),
(276, 19, 1, 0, 'Admin', 0, 'Team member <strong>Md. Milon Hossain</strong> has been removed', 'Md. Milon Hossain', '2019-01-10 09:43:08'),
(277, 19, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:43:28'),
(278, 22, 18, 0, 'Md. Milon Hossain', 0, 'A new project has been created', NULL, '2019-01-10 09:46:25'),
(279, 22, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:46:25'),
(280, 23, 18, 0, 'Md. Milon Hossain', 0, 'A new project has been created', NULL, '2019-01-10 09:47:27'),
(281, 23, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:47:27'),
(282, 24, 18, 0, 'Md. Milon Hossain', 0, 'A new project has been created', NULL, '2019-01-10 09:48:16'),
(283, 24, 18, 0, 'Md. Milon Hossain', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:48:16'),
(284, 25, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-10 09:54:40'),
(285, 25, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:54:40'),
(286, 26, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-10 09:56:21'),
(287, 26, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-10 09:56:21'),
(288, 21, 1, 0, 'Admin', 0, 'Add new task assignee of task Invoice module', 'Md. Milon Hossain', '2019-01-10 10:12:12'),
(289, 19, 1, 0, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Md. Milon Hossain', '2019-01-10 10:12:57'),
(290, 20, 1, 0, 'Admin', 0, 'Change Status of task when changing status for payout as success this error occur, but if reload page status changing as s', 'Complete', '2019-01-10 10:32:41'),
(291, 21, 1, 0, 'Admin', 0, 'Add a comment of task Invoice module', NULL, '2019-01-10 12:38:04'),
(292, 19, 1, 0, 'Admin', 0, 'Change Status of task Milestone check again', 'Complete', '2019-01-13 05:04:38'),
(293, 19, 1, 0, 'Admin', 0, 'Change Status of task Milestone check again', 'Awaiting Feedback', '2019-01-13 05:04:42'),
(294, 19, 1, 0, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Rafiq', '2019-01-13 06:43:10'),
(295, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Rafiq', '2019-01-13 06:51:04'),
(296, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Rafiq', '2019-01-13 06:53:31'),
(297, 19, 1, 0, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Rafiq', '2019-01-13 06:53:34'),
(298, 19, 1, 0, 'Admin', 0, 'Change Status of task Milestone check', 'Complete', '2019-01-14 06:06:45'),
(299, 8, 1, 0, 'Admin', 0, 'Change Status of task API inetegration', 'Awaiting Feedback', '2019-01-14 06:07:03'),
(300, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Rafiq', '2019-01-14 06:30:28'),
(301, 19, 15, 0, 'Rafiq', 0, 'Add new task assignee of task Milestone check', 'Md. Milon Hossain', '2019-01-14 06:44:17'),
(302, 19, 1, 0, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Md. Milon Hossain', '2019-01-14 06:46:20'),
(303, 19, 1, 0, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Md. Milon Hossain', '2019-01-14 07:00:43'),
(304, 20, 1, 0, 'Admin', 0, 'Add new milestone ', 'Design', '2019-01-14 07:02:18'),
(305, 20, 1, 0, 'Admin', 0, 'Add new milestone ', 'Testing', '2019-01-14 07:02:58'),
(306, 20, 1, 0, 'Admin', 0, 'Add new milestone ', 'Planning', '2019-01-14 07:03:24'),
(307, 20, 1, 0, 'Admin', 0, 'Change Status of task Design chnage of front end', 'In Progress', '2019-01-14 07:05:30'),
(308, 20, 1, 0, 'Admin', 0, 'Change Priority of task Design chnage of front end', 'Medium', '2019-01-14 07:05:33'),
(309, 20, 1, 0, 'Admin', 0, 'Add new milestone ', 'Api', '2019-01-14 07:14:05'),
(310, 20, 1, 0, 'Admin', 0, 'Delete Milestone ', 'Api', '2019-01-14 07:14:22'),
(311, 20, 1, 0, 'Admin', 0, 'New team member has been added', 'Borna', '2019-01-14 07:29:03'),
(312, 20, 1, 0, 'Admin', 0, 'Add new task assignee of task Design chnage of front end', 'Md. Milon Hossain', '2019-01-14 07:31:28'),
(313, 20, 1, 0, 'Admin', 0, 'Add a comment of task Design chnage of front end', NULL, '2019-01-14 07:31:51'),
(314, 20, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Design chnage of front end', NULL, '2019-01-14 07:32:10'),
(315, 20, 1, 0, 'Admin', 0, 'Add a comment of task Design chnage of front end', NULL, '2019-01-14 07:33:16'),
(316, 20, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Design chnage of front end', NULL, '2019-01-14 07:33:35'),
(317, 20, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Design chnage of front end', NULL, '2019-01-14 07:34:46'),
(318, 20, 18, 0, 'Md. Milon Hossain', 0, 'Change Priority of task Design chnage of front end', 'Low', '2019-01-14 07:40:09'),
(319, 20, 18, 0, 'Md. Milon Hossain', 0, 'Change Priority of task Design chnage of front end', 'High', '2019-01-14 07:40:12'),
(320, 20, 1, 0, 'Admin', 0, 'Remove task assignee of task Design chnage of front end', 'Md. Milon Hossain', '2019-01-14 07:46:04'),
(321, 20, 1, 0, 'Admin', 0, 'Add new task assignee of task Design chnage of front end', 'Md. Milon Hossain', '2019-01-14 07:46:41'),
(322, 20, 1, 0, 'Admin', 0, 'Add new task assignee of task Design chnage of front end', 'Borna', '2019-01-14 08:15:26'),
(323, 27, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-15 05:04:48'),
(324, 27, 1, 0, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2019-01-15 05:04:49'),
(325, 27, 1, 0, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-15 05:04:49'),
(326, 28, 1, 0, 'Admin', 0, 'A new project has been created', NULL, '2019-01-15 06:01:25'),
(327, 28, 1, 0, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2019-01-15 06:01:25'),
(328, 21, 1, 0, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2019-01-15 07:23:29'),
(329, 21, 1, 0, 'Admin', 0, 'New team member <strong>Rafiq</strong> has been added', 'Rafiq', '2019-01-15 07:23:29'),
(330, 21, 1, 0, 'Admin', 0, 'New team member <strong>Borna</strong> has been added', 'Borna', '2019-01-15 07:23:29'),
(331, 21, 1, 0, 'Admin', 0, 'Add new task assignee of task Invoice module', 'Admin', '2019-01-15 07:23:51'),
(332, 21, 1, 0, 'Admin', 0, 'Add new task assignee of task Invoice module', 'Rafiq', '2019-01-15 07:23:53'),
(333, 21, 1, 0, 'Admin', 0, 'Add new task assignee of task Invoice module', 'Borna', '2019-01-15 07:23:56'),
(334, 21, 1, 0, 'Admin', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 07:46:18'),
(335, 21, 1, 0, 'Admin', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 11:22:45'),
(336, 21, 1, 0, 'Admin', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 11:23:33'),
(337, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 11:41:11'),
(338, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 12:35:24'),
(339, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 12:37:08'),
(340, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 12:37:21'),
(341, 21, 18, 0, 'Md. Milon Hossain', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 12:44:03'),
(342, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 12:52:02'),
(343, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-15 13:05:19'),
(344, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-16 04:53:47'),
(345, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-16 04:59:50'),
(346, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-16 05:24:37'),
(347, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-16 05:48:21'),
(348, 21, NULL, 0, '', 0, 'Add a comment of task Invoice module', NULL, '2019-01-16 08:16:02'),
(349, 21, 1, 0, 'Admin', 0, 'Update comment of task Invoice module', NULL, '2019-01-17 05:39:38'),
(350, 21, 1, 0, 'Admin', 1, 'Add a comment of task Invoice module', NULL, '2019-01-17 06:31:07'),
(351, 21, 1, 0, 'Admin', 1, 'Add a comment of task Invoice module', NULL, '2019-01-17 06:31:06'),
(352, 21, NULL, 0, 'Customer Techvillage', 1, 'Add a comment of task Invoice module', NULL, '2019-01-17 06:31:05'),
(353, 21, NULL, 5, 'Customer Techvillage', 1, 'Add a comment of task Invoice module', NULL, '2019-01-17 06:31:04'),
(354, 21, NULL, 5, 'Customer Techvillage', 1, 'Add a comment of task Invoice module', NULL, '2019-01-17 06:37:29'),
(355, 21, NULL, 5, 'Customer Techvillage', 1, 'Add a comment of task Invoice module', NULL, '2019-01-17 06:38:36'),
(356, 21, 1, NULL, 'Admin', 0, 'Update milestone ', 'New MileStone', '2019-01-17 07:51:45'),
(357, 21, 1, NULL, 'Admin', 0, 'Change Priority of task Report', 'High', '2019-01-19 09:48:49'),
(358, 21, 1, NULL, 'Admin', 0, 'Change Status of task Report', 'Awaiting Feedback', '2019-01-19 09:49:49'),
(359, 21, 1, NULL, 'Admin', 0, 'Change Status of task Report', 'In Progress', '2019-01-19 10:01:00'),
(360, 21, 1, NULL, 'Admin', 0, 'Change Status of task Report', 'Complete', '2019-01-19 10:01:23'),
(361, 21, 1, NULL, 'Admin', 0, 'Change Priority of task Report', 'Medium', '2019-01-19 10:01:52'),
(362, 21, 1, NULL, 'Admin', 0, 'Change Priority of task Report', 'Low', '2019-01-19 10:01:54'),
(363, 21, NULL, 5, 'Customer Techvillage', 0, 'Add a comment of task Report', NULL, '2019-01-19 11:16:10'),
(364, 21, 1, NULL, 'Admin', 0, 'Change Status of task Invoice module', 'Complete', '2019-01-20 09:02:41'),
(365, 21, 1, NULL, 'Admin', 0, 'Change Status of task Task from customer panel', 'Complete', '2019-01-20 09:02:43'),
(366, 21, 1, NULL, 'Admin', 0, 'Change Status of task Task With Milestone', 'Complete', '2019-01-20 09:02:46'),
(367, 27, 1, NULL, 'Admin', 0, 'A new project has been created', NULL, '2019-01-20 13:11:32'),
(368, 27, 1, NULL, 'Admin', 0, 'New team member <strong>Admin</strong> has been added', 'Admin', '2019-01-20 13:11:33'),
(369, 27, 1, NULL, 'Admin', 0, 'New team member <strong>Rafiq</strong> has been added', 'Rafiq', '2019-01-20 13:11:33'),
(370, 27, 1, NULL, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-20 13:11:33'),
(371, 27, 1, NULL, 'Admin', 0, 'Add a comment of task Test task for customer-1', NULL, '2019-01-21 09:27:20'),
(372, 27, NULL, 5, 'Customer Techvillage', 0, 'Add a comment of task Test task for customer-1', NULL, '2019-01-21 09:31:03'),
(373, 27, 1, NULL, 'Admin', 0, 'Add a comment of task Test task for customer-1', NULL, '2019-01-21 09:31:54'),
(374, 27, NULL, 5, 'Customer Techvillage', 0, 'Add a comment of task Test task for customer-1', NULL, '2019-01-21 09:33:13'),
(375, 27, 1, NULL, 'Admin', 0, 'Add new task assignee of task Test task for customer-1', 'Md. Milon Hossain', '2019-01-21 09:43:54'),
(376, 27, 1, NULL, 'Admin', 0, 'Add new task assignee of task Test task for customer-1', 'Rafiq', '2019-01-21 09:49:34'),
(377, 27, 1, NULL, 'Admin', 0, 'Add new task assignee of task test-2', 'Md. Milon Hossain', '2019-01-21 09:59:28'),
(378, 27, 1, NULL, 'Admin', 0, 'Add new milestone ', 'Milestone 1 for pay money APP', '2019-01-21 10:21:40'),
(379, 27, 1, NULL, 'Admin', 0, 'Add new milestone ', 'Milestone 2 for pay money APP.', '2019-01-21 10:22:00'),
(380, 27, 1, NULL, 'Admin', 0, 'Update milestone ', 'Milestone 1 for pay money APP', '2019-01-21 10:24:49'),
(381, 27, 1, NULL, 'Admin', 0, 'Update milestone ', 'Milestone 1 for pay money APP', '2019-01-21 10:39:13'),
(382, 27, 1, NULL, 'Admin', 0, 'Update milestone ', 'Milestone 1 for pay money APP', '2019-01-21 10:40:01'),
(383, 20, 1, NULL, 'Admin', 0, 'Change Priority of task Design chnage of front end', 'Low', '2019-01-21 10:45:00'),
(384, 20, 1, NULL, 'Admin', 0, 'Change Priority of task Design chnage of front end', 'High', '2019-01-21 10:45:03'),
(385, 27, 1, NULL, 'Admin', 0, 'Change Status of task Test task for customer-1', 'Awaiting Feedback', '2019-01-21 11:32:18'),
(386, 27, 1, NULL, 'Admin', 0, 'Change Status of task Test task for customer-1', 'In Progress', '2019-01-21 11:32:21'),
(387, 27, 1, NULL, 'Admin', 0, 'Update milestone ', 'Milestone 1 for pay money APP', '2019-01-21 11:33:00'),
(388, 27, 1, NULL, 'Admin', 0, 'Update milestone ', 'Milestone 1 for pay money APP', '2019-01-22 11:03:56'),
(389, 27, 18, NULL, 'Md. Milon Hossain', 0, 'Change Status of task Test task for customer-1', 'Complete', '2019-01-22 11:41:05'),
(390, 27, 18, NULL, 'Md. Milon Hossain', 0, 'Change Status of task test-2', 'Complete', '2019-01-22 11:42:29'),
(391, 28, 1, NULL, 'Admin', 0, 'A new project has been created', NULL, '2019-01-23 08:26:09'),
(392, 28, 1, NULL, 'Admin', 0, 'New team member <strong>Md. Milon Hossain</strong> has been added', 'Md. Milon Hossain', '2019-01-23 08:26:09'),
(393, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Medium', '2019-01-23 10:13:34'),
(394, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'High', '2019-01-23 10:13:39'),
(395, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Low', '2019-01-23 10:13:41'),
(396, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Medium', '2019-01-23 10:43:44'),
(397, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'High', '2019-01-23 10:43:47'),
(398, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Low', '2019-01-23 10:43:49'),
(399, 2, 1, NULL, 'Admin', 0, 'Change Status of task Test task for hourly rate', 'In Progress', '2019-01-23 10:43:55'),
(400, 2, 1, NULL, 'Admin', 0, 'Change Status of task Test task for hourly rate', 'Testing', '2019-01-23 10:43:58'),
(401, 2, 1, NULL, 'Admin', 0, 'Change Status of task Test task for hourly rate', ' Not Started', '2019-01-23 10:44:01'),
(402, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Medium', '2019-01-23 10:44:06'),
(403, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Low', '2019-01-23 10:44:08'),
(404, 2, 1, NULL, 'Admin', 0, 'Change Priority of task Test task for hourly rate', 'Low', '2019-01-23 10:45:04'),
(405, 27, 1, NULL, 'Admin', 0, 'New team member has been added', 'Borna', '2019-01-24 10:00:43'),
(406, 27, 1, NULL, 'Admin', 0, 'Add new task assignee of task assignee mail link', 'Borna', '2019-01-24 10:00:56'),
(407, 19, 1, NULL, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Rafiq', '2019-01-31 07:04:05'),
(408, 19, 1, NULL, 'Admin', 0, 'Remove task assignee of task Milestone check again', 'Rafiq', '2019-01-31 07:04:11'),
(409, 19, 1, NULL, 'Admin', 0, 'Add new task assignee of task Milestone check again', 'Rafiq', '2019-01-31 07:04:21'),
(410, 19, 1, NULL, 'Admin', 0, 'Add a comment of task Milestone check', NULL, '2019-01-31 07:05:11'),
(411, 19, 1, NULL, 'Admin', 0, 'Add a comment of task Milestone check', NULL, '2019-01-31 07:05:18'),
(412, 21, 1, NULL, 'Admin', 0, 'Remove task assignee of task Invoice module', 'Rafiq', '2019-02-02 04:55:15'),
(413, 21, 1, NULL, 'Admin', 0, 'Add new task assignee of task Invoice module', 'Rafiq', '2019-02-02 04:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `backup`
--

DROP TABLE IF EXISTS `backup`;
CREATE TABLE IF NOT EXISTS `backup` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `backup`
--

INSERT INTO `backup` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '2019-01-20-063202.sql', '2019-01-20 00:32:02', NULL),
(2, '2019-01-27-122154.sql', '2019-01-27 06:21:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_type_id` int(10) UNSIGNED NOT NULL,
  `account_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `account_no` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `gl_account_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `bank_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `branch_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch_city` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `swift_code` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_account` tinyint(4) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bank_accounts_account_type_id_foreign` (`account_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `account_type_id`, `account_name`, `account_no`, `gl_account_id`, `currency_id`, `bank_name`, `branch_name`, `branch_city`, `swift_code`, `bank_address`, `default_account`, `deleted`) VALUES
(1, 1, 'Techvillage', '123456', 2, 2, 'Brac Bank', NULL, NULL, NULL, 'Nikunja-2, Khilkhet, Dhaka-1219', 0, 0),
(2, 4, 'Online Bank(Paypal)', '123456', 6, 1, 'Paypal', NULL, NULL, NULL, 'Us', 0, 0),
(3, 1, 'tezt Account', '001', 2, 4, 'Bank test', NULL, NULL, NULL, NULL, 0, 0),
(4, 1, 'Test Account2', 'number123456', 4, 2, 'Test bank ltd', 'uttara', 'Dhaka', 'swiftswift123456', 'City Hall Park Path, uttara, dhaka', 0, 0),
(5, 1, 'John Bradon', '1001', 7, 4, 'Bank of America Corp', 'USA', 'USA', '123456789', 'USA', 0, 0),
(6, 1, 'Mr. Russel', '300001', 8, 1, 'Bank of America', 'New york', 'New york', '321', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bank_account_type`
--

DROP TABLE IF EXISTS `bank_account_type`;
CREATE TABLE IF NOT EXISTS `bank_account_type` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank_account_type`
--

INSERT INTO `bank_account_type` (`id`, `name`) VALUES
(1, 'Savings Account'),
(2, 'Chequing Account'),
(3, 'Credit Account'),
(4, 'Cash Account');

-- --------------------------------------------------------

--
-- Table structure for table `bank_deposits`
--

DROP TABLE IF EXISTS `bank_deposits`;
CREATE TABLE IF NOT EXISTS `bank_deposits` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `from_gl_account_id` int(10) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `transaction_date` date NOT NULL,
  `exchange_rate` int(11) NOT NULL,
  `description` text NOT NULL,
  `total_amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_deposits`
--

INSERT INTO `bank_deposits` (`id`, `bank_account_id`, `user_id`, `from_gl_account_id`, `reference_id`, `payment_method_id`, `type`, `reference`, `transaction_date`, `exchange_rate`, `description`, `total_amount`) VALUES
(1, 1, 1, 2, 19, 2, 11, '001/2018', '2018-12-17', 1, 'test deposit', 500),
(2, 1, 15, 2, 49, 2, 11, '002/2018', '2018-12-27', 1, 'First Deposite', 25100),
(3, 1, 1, 2, 84, 1, 11, '003/2019', '2019-01-21', 0, 'dews', 10);

-- --------------------------------------------------------

--
-- Table structure for table `bank_trans`
--

DROP TABLE IF EXISTS `bank_trans`;
CREATE TABLE IF NOT EXISTS `bank_trans` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `trans_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `account_no` int(11) NOT NULL,
  `trans_date` date NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` int(11) NOT NULL,
  `attachment` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank_trans`
--

INSERT INTO `bank_trans` (`id`, `amount`, `trans_type`, `account_no`, `trans_date`, `user_id`, `reference_id`, `type`, `reference`, `description`, `payment_method`, `attachment`, `created_at`) VALUES
(1, 5, 'cash-in-by-sale', 2, '2018-12-17', 1, 18, 15, '018/2018', 'Online Payment for INV-0007', 1, '', '2018-12-17 04:55:28'),
(2, 500, 'deposit', 1, '2018-12-17', 1, 19, 11, '001/2018', 'test deposit', 2, '', '2018-12-17 05:03:13'),
(3, 8000, 'cash-in-by-sale', 2, '2018-12-20', 1, 20, 15, '019/2018', 'Payment for INV-0009', 2, '', '2018-12-20 11:22:34'),
(4, 1583555, 'cash-in-by-sale', 1, '2018-12-23', 1, 21, 15, '020/2018', 'Payment for INV-0006', 2, '', '2018-12-23 09:17:01'),
(5, 8000, 'cash-in-by-sale', 1, '2018-12-23', 1, 22, 15, '021/2018', 'Payment for INV-0012', 2, '', '2018-12-23 09:56:55'),
(6, 36816, 'cash-in-by-sale', 1, '2018-12-23', 1, 23, 15, '022/2018', 'Payment for INV-0004', 2, '', '2018-12-23 10:03:38'),
(7, 400, 'cash-in-by-sale', 1, '2018-12-23', 1, 24, 15, '023/2018', 'Payment for INV-0008', 2, '', '2018-12-23 10:05:05'),
(8, 8000, 'cash-in-by-sale', 1, '2018-12-23', 1, 25, 15, '024/2018', 'Payment for INV-0011', 2, '', '2018-12-23 10:05:27'),
(9, 28350, 'cash-in-by-sale', 1, '2018-12-23', 1, 26, 15, '025/2018', 'Payment for INV-0003', 2, '', '2018-12-23 10:06:58'),
(10, 36426, 'cash-in-by-sale', 1, '2018-12-23', 1, 27, 15, '026/2018', 'Payment for INV-0004', 2, '', '2018-12-23 10:07:30'),
(11, 36114, 'cash-in-by-sale', 1, '2018-12-23', 1, 28, 15, '027/2018', 'Payment for INV-0004', 2, '', '2018-12-23 10:10:37'),
(12, 35724, 'cash-in-by-sale', 1, '2018-12-23', 1, 29, 15, '028/2018', 'Payment for INV-0004', 2, '', '2018-12-23 10:14:23'),
(13, 35334, 'cash-in-by-sale', 1, '2018-12-23', 1, 30, 15, '029/2018', 'Payment for INV-0004', 2, '', '2018-12-23 10:15:19'),
(14, 5000, 'cash-in-by-sale', 1, '2018-12-24', 1, 31, 15, '030/2018', 'Payment for INV-0014', 2, '', '2018-12-24 06:48:18'),
(15, 4950, 'cash-in-by-sale', 1, '2018-12-24', 1, 32, 15, '031/2018', 'Payment for INV-0014', 2, '', '2018-12-24 06:50:38'),
(16, 9801, 'cash-in-by-sale', 2, '2018-12-24', 1, 33, 15, '032/2018', 'Online Payment for INV-0014', 1, '', '2018-12-24 07:33:10'),
(17, 0, 'cash-in-by-sale', 2, '2018-12-24', 1, 34, 15, '033/2018', 'Online Payment for INV-0014', 1, '', '2018-12-24 07:33:23'),
(18, 0, 'cash-in-by-sale', 2, '2018-12-24', 1, 35, 15, '034/2018', 'Online Payment for INV-0014', 1, '', '2018-12-24 07:34:18'),
(19, 8000, 'cash-in-by-sale', 1, '2018-12-24', 1, 36, 15, '035/2018', 'Payment for INV-0013', 2, '', '2018-12-24 07:42:15'),
(20, 6000, 'cash-in-by-sale', 2, '2018-12-24', 1, 37, 15, '036/2018', 'Online Payment for INV-0015', 1, '', '2018-12-24 08:07:36'),
(21, 1000, 'cash-in-by-sale', 2, '2018-12-25', 1, 38, 15, '037/2018', 'Payment for INV-0016', 1, '', '2018-12-24 08:09:06'),
(22, 900, 'cash-in-by-sale', 2, '2018-12-24', 1, 39, 15, '038/2018', 'Online Payment for INV-0017', 1, '', '2018-12-24 08:12:28'),
(23, 800, 'cash-in-by-sale', 2, '2018-12-24', 1, 40, 15, '039/2018', 'Online Payment for INV-0018', 1, '', '2018-12-24 08:14:54'),
(24, 0, 'cash-in-by-sale', 2, '2018-12-24', 1, 41, 15, '040/2018', 'Online Payment for INV-0018', 1, '', '2018-12-24 08:15:05'),
(25, 700, 'cash-in-by-sale', 2, '2018-12-24', 1, 42, 15, '041/2018', 'Online Payment for INV-0019', 1, '', '2018-12-24 08:16:44'),
(26, 600, 'cash-in-by-sale', 2, '2018-12-24', 1, 43, 15, '042/2018', 'Online Payment for INV-0020', 1, '', '2018-12-24 08:18:43'),
(27, 0, 'cash-in-by-sale', 2, '2018-12-24', 1, 44, 15, '043/2018', 'Online Payment for INV-0020', 1, '', '2018-12-24 08:18:58'),
(28, 0, 'cash-in-by-sale', 2, '2018-12-24', 1, 45, 15, '044/2018', 'Online Payment for INV-0020', 1, '', '2018-12-24 08:20:11'),
(29, 8000, 'cash-in-by-sale', 1, '2018-12-26', 15, 46, 15, '045/2018', 'Payment for INV-0024', 2, '', '2018-12-26 13:04:12'),
(30, 250000, 'cash-in-by-sale', 1, '2018-12-27', 15, 47, 15, '046/2018', 'Payment for INV-0025', 2, '', '2018-12-27 05:04:20'),
(31, 35022, 'cash-in-by-sale', 1, '2018-12-27', 15, 48, 15, '047/2018', 'Payment for INV-0004', 2, '', '2018-12-27 05:06:20'),
(32, 25100, 'deposit', 1, '2018-12-27', 15, 49, 11, '002/2018', 'First Deposite', 2, '', '2018-12-27 05:24:51'),
(33, -4001000, 'cash-out-by-transfer', 1, '2018-12-27', 15, 50, 13, '001/2018', 'First Transfer', 1, '', '2018-12-27 05:29:20'),
(34, 50000, 'cash-in-by-transfer', 2, '2018-12-27', 15, 50, 13, '001/2018', 'First Transfer', 1, '', '2018-12-27 05:29:20'),
(35, -2102000, 'cash-out-by-transfer', 1, '2018-12-27', 1, 51, 13, '002/2018', 'admin transfer', 1, '', '2018-12-27 05:44:36'),
(36, 25000, 'cash-in-by-transfer', 2, '2018-12-27', 1, 51, 13, '002/2018', 'admin transfer', 1, '', '2018-12-27 05:44:36'),
(37, -2500, 'expense', 2, '2018-12-27', 1, 52, 12, '001/2018', 'Admin Expense', 1, '', '2018-12-27 05:57:05'),
(38, -3000, 'expense', 2, '2018-12-27', 15, 53, 12, '002/2018', 'Rafiq Expense', 1, '', '2018-12-27 05:57:30'),
(39, -6.25, 'cash-out-by-purchase', 2, '2018-12-27', 15, 54, 16, '001/2018', 'Payment for PO-0001', 2, '', '2018-12-27 06:02:11'),
(40, -50, 'cash-out-by-purchase', 2, '2018-12-27', 1, 55, 16, '002/2018', 'Payment for PO-0002', 1, '', '2018-12-27 06:02:42'),
(41, 16000, 'cash-in-by-sale', 1, '2018-12-27', 1, 56, 15, '048/2018', 'Payment for INV-0028', 2, '', '2018-12-27 07:51:45'),
(42, 1600, 'cash-in-by-sale', 1, '2018-12-27', 1, 57, 15, '049/2018', 'Payment for INV-0028', 2, '', '2018-12-27 07:54:24'),
(43, 6160, 'cash-in-by-sale', 1, '2018-12-27', 15, 58, 15, '050/2018', 'Payment for INV-0032', 2, '', '2018-12-27 09:35:19'),
(44, 9600, 'cash-in-by-sale', 1, '2018-12-27', 15, 59, 15, '051/2018', 'Payment for INV-0040', 2, '', '2018-12-27 10:52:07'),
(45, 8000, 'cash-in-by-sale', 1, '2018-12-27', 1, 60, 15, '052/2018', 'Payment for INV-0045', 2, '', '2018-12-27 11:03:48'),
(46, 12, 'cash-in-by-sale', 1, '2019-01-02', 1, 61, 15, '053/2019', 'Payment for INV-0064', 2, '', '2019-01-02 12:52:20'),
(47, 500, 'Cash-in', 3, '2019-01-03', 1, 0, 10, 'opening balance', 'opening balance', 1, '', '2019-01-03 04:07:31'),
(48, -50, 'cash-out-by-purchase', 1, '2019-01-03', 1, 62, 16, '003/2019', 'Payment for PO-0003', 2, '', '2019-01-03 12:44:40'),
(49, -1000, 'cash-out-by-purchase', 1, '2019-01-03', 1, 63, 16, '004/2019', 'Payment for PO-0003', 2, '', '2019-01-03 12:46:32'),
(50, 1000, 'Cash-in', 4, '2019-01-05', 1, 0, 10, 'opening balance', 'opening balance', 1, '', '2019-01-04 23:09:06'),
(51, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:28:36'),
(52, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:28:54'),
(53, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:34:20'),
(54, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 66, 15, '056/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:34:27'),
(55, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 66, 15, '056/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:34:39'),
(56, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:36:34'),
(57, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 66, 15, '056/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:37:41'),
(58, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 66, 15, '056/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:38:11'),
(59, 80000, 'cash-in-by-sale', 4, '2019-01-05', 1, 64, 15, '054/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:40:10'),
(60, 80000, 'cash-in-by-sale', 4, '2019-01-05', 1, 64, 15, '054/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:40:25'),
(61, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:41:27'),
(62, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:42:59'),
(63, 800, 'cash-in-by-sale', 4, '2019-01-06', 1, 65, 15, '055/2019', 'Bank payment for INV-0010', 2, '', '2019-01-06 09:44:34'),
(64, 200, 'cash-in-by-sale', 4, '2019-01-06', 1, 68, 15, '058/2019', 'Bank payment for INV-0066', 2, '', '2019-01-06 09:56:36'),
(66, 150, 'cash-in-by-sale', 4, '2019-01-06', 1, 70, 15, '060/2019', 'Bank payment for INV-0068', 2, '', '2019-01-06 10:07:26'),
(67, 3000, 'cash-in-by-sale', 4, '2019-01-06', 1, 71, 15, '061/2019', 'Bank payment for INV-0069', 2, '', '2019-01-06 10:11:42'),
(68, 3001, 'cash-in-by-sale', 4, '2019-01-06', 1, 72, 15, '062/2019', 'Bank payment for INV-0069', 2, '', '2019-01-06 10:12:33'),
(69, 850, 'cash-in-by-sale', 4, '2019-01-06', 1, 73, 15, '063/2019', 'Bank payment for INV-0070', 2, '', '2019-01-06 11:30:28'),
(70, 850, 'cash-in-by-sale', 4, '2019-01-06', 1, 73, 15, '063/2019', 'Bank payment for INV-0070', 2, '', '2019-01-06 11:30:38'),
(71, 850, 'cash-in-by-sale', 4, '2019-01-06', 1, 73, 15, '063/2019', 'Bank payment for INV-0070', 2, '', '2019-01-06 11:30:58'),
(72, 50, 'cash-in-by-sale', 4, '2019-01-06', 1, 74, 15, '064/2019', 'Bank payment for INV-0071', 2, '', '2019-01-06 12:31:16'),
(73, 50, 'cash-in-by-sale', 4, '2019-01-06', 1, 75, 15, '065/2019', 'Bank payment for INV-0071', 2, '', '2019-01-06 12:57:41'),
(74, 500, 'cash-in-by-sale', 4, '2019-01-06', 1, 76, 15, '066/2019', 'Bank payment for INV-0073', 2, '', '2019-01-06 13:12:20'),
(75, -120, 'cash-out-by-purchase', 1, '2019-01-07', 1, 78, 16, '005/2019', 'Payment for PO-0006', 2, '', '2019-01-07 10:40:22'),
(76, 960, 'cash-in-by-sale', 1, '2019-01-09', 1, 79, 15, '068/2019', 'Payment for INV-0075', 2, '', '2019-01-09 09:58:02'),
(77, 6, 'cash-in-by-sale', 1, '2019-01-09', 1, 80, 15, '069/2019', 'Payment for INV-0075', 1, '', '2019-01-09 10:01:20'),
(78, 100, 'cash-in-by-sale', 2, '2019-01-15', 1, 81, 15, '070/2019', 'Payment for INV-0079', 2, '', '2019-01-15 05:10:49'),
(79, -55, 'cash-out-by-purchase', 4, '2019-01-21', 1, 82, 16, '006/2019', 'Payment for PO-0008', 2, '', '2019-01-21 08:50:01'),
(80, 8000, 'cash-in-by-sale', 1, '2019-01-21', 1, 83, 15, '071/2019', 'Payment for INV-0082', 2, '', '2019-01-21 11:48:05'),
(81, 10, 'deposit', 1, '2019-01-21', 1, 84, 11, '003/2019', 'dews', 1, '', '2019-01-21 11:49:50'),
(82, 50, 'cash-in-by-sale', 2, '2019-01-23', 1, 86, 15, '073/2019', 'Payment for INV-0087', 2, '', '2019-01-23 10:07:33'),
(83, 100, 'cash-in-by-sale', 4, '2019-01-23', 1, 85, 15, '072/2019', 'Bank payment for INV-0087', 2, '', '2019-01-23 10:08:32'),
(84, 67433.33333333334, 'cash-in-by-sale', 1, '2019-01-23', 1, 87, 15, '074/2019', 'Payment for INV-0087', 1, '', '2019-01-23 10:13:10'),
(85, 0, 'Cash-in', 5, '2019-01-24', 17, 0, 10, 'opening balance', 'opening balance', 1, '', '2019-01-24 05:56:55'),
(86, 10, 'cash-in-by-sale', 5, '2019-01-24', 1, 89, 15, '076/2019', 'Bank payment for INV-0089', 2, '', '2019-01-24 12:59:11'),
(87, 250, 'cash-in-by-sale', 5, '2019-01-24', 1, 90, 15, '077/2019', 'Payment for INV-0089', 2, '', '2019-01-24 13:08:54'),
(88, 20, 'cash-in-by-sale', 5, '1970-01-01', 1, 91, 15, '078/2019', 'Bank payment for INV-0093', 2, '', '2019-01-29 06:55:18'),
(89, 5, 'cash-in-by-sale', 5, '1970-01-01', 1, 92, 15, '079/2019', 'Bank payment for INV-0093', 2, '', '2019-01-29 07:26:19'),
(90, 0.22, 'cash-in-by-sale', 4, '2019-01-29', 1, 93, 15, '080/2019', 'Payment for INV-0093', 1, '', '2019-01-29 07:49:40'),
(91, 0.33, 'cash-in-by-sale', 4, '2019-01-29', 1, 94, 15, '081/2019', 'Payment for INV-0093', 1, '', '2019-01-29 07:56:13'),
(92, 0.044, 'cash-in-by-sale', 3, '2019-01-29', 1, 95, 15, '082/2019', 'Payment for INV-0093', 1, '', '2019-01-29 12:01:22'),
(93, 79.81, 'cash-in-by-sale', 3, '2019-01-29', 1, 96, 15, '083/2019', 'Payment for INV-0094', 1, '', '2019-01-29 12:06:58'),
(94, 100, 'cash-in-by-sale', 5, '2019-01-29', 1, 97, 15, '084/2019', 'Payment for INV-0095', 1, '', '2019-01-29 13:06:37'),
(95, 30, 'cash-in-by-sale', 2, '2019-01-30', 1, 98, 15, '085/2019', 'Payment for INV-0096', 2, '', '2019-01-30 09:38:34'),
(96, 5600, 'cash-in-by-sale', 1, '2019-01-30', 1, 99, 15, '086/2019', 'Payment for INV-0096', 1, '', '2019-01-30 10:14:51'),
(97, 1000, 'cash-in-by-sale', 3, '2019-01-30', 1, 100, 15, '087/2019', 'Payment for INV-0096', 1, '', '2019-01-30 10:47:57'),
(98, 960, 'cash-in-by-sale', 3, '2019-01-30', 1, 101, 15, '088/2019', 'Payment for INV-0096', 1, '', '2019-01-30 10:54:07'),
(99, 400, 'cash-in-by-sale', 3, '2019-01-30', 1, 102, 15, '089/2019', 'Payment for INV-0096', 1, '', '2019-01-30 10:56:21'),
(100, 500, 'Cash-in', 6, '2019-01-30', 1, 0, 10, 'opening balance', 'opening balance', 1, '', '2019-01-30 05:14:10'),
(103, 550, 'cash-in-by-sale', 5, '1970-01-01', 1, 106, 15, '091/2019', 'Bank payment for INV-0099', 2, '', '2019-01-30 11:47:49'),
(104, 50, 'cash-in-by-sale', 5, '1970-01-01', 1, 107, 15, '092/2019', 'Bank payment for INV-0099', 2, '', '2019-01-30 11:55:21'),
(107, 500, 'cash-in-by-sale', 5, '1970-01-01', 1, 110, 15, '093/2019', 'Bank payment for INV-0101', 2, '', '2019-01-31 06:58:38'),
(108, 57, 'cash-in-by-sale', 5, '1970-01-01', 1, 111, 15, '094/2019', 'Bank payment for INV-0101', 2, '', '2019-01-31 07:16:24'),
(109, -25, 'cash-out-by-purchase', 4, '2019-01-31', 1, 112, 16, '007/2019', 'Payment for PO-0009', 1, '', '2019-01-31 10:20:55'),
(110, -10, 'cash-out-by-purchase', 5, '2019-01-31', 1, 113, 16, '008/2019', 'Payment for PO-0009', 1, '', '2019-01-31 10:40:34'),
(111, 100, 'cash-in-by-sale', 5, '2019-01-31', 1, 114, 15, '095/2019', 'Payment for INV-0100', 1, '', '2019-01-31 10:56:23'),
(113, -1729000, 'cash-out-by-purchase', 4, '2019-01-31', 1, 116, 16, '010/2019', 'Payment for PO-0010', 2, '', '2019-01-31 11:42:36'),
(114, -42875, 'cash-out-by-purchase', 1, '2019-01-31', 1, 117, 16, '011/2019', 'Payment for PO-0011', 1, '', '2019-01-31 12:28:31'),
(115, -32.51, 'expense', 5, '2019-02-03', 1, 118, 12, '003/2019', 'This is a demo expenses', 2, '', '2019-02-03 13:07:15'),
(116, -39.85, 'expense', 3, '2019-02-03', 1, 119, 12, '004/2019', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five cent', 2, '', '2019-02-03 13:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transfers`
--

DROP TABLE IF EXISTS `bank_transfers`;
CREATE TABLE IF NOT EXISTS `bank_transfers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_account_id` int(10) UNSIGNED NOT NULL,
  `to_account_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `from_currency_id` int(11) NOT NULL,
  `to_currency_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `memo` varchar(200) NOT NULL,
  `from_exchange_rate` double NOT NULL,
  `amount` double NOT NULL,
  `bank_charge` double NOT NULL,
  `to_exchange_rate` double NOT NULL,
  `incoming_amount` double NOT NULL,
  `transfered_currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_transfers`
--

INSERT INTO `bank_transfers` (`id`, `from_account_id`, `to_account_id`, `user_id`, `from_currency_id`, `to_currency_id`, `transaction_date`, `reference_id`, `type`, `reference`, `memo`, `from_exchange_rate`, `amount`, `bank_charge`, `to_exchange_rate`, `incoming_amount`, `transfered_currency_id`, `payment_method_id`) VALUES
(1, 1, 2, 15, 2, 1, '2018-12-27', 50, 13, '001/2018', 'First Transfer', 1, 50000, 1000, 80, 0, 1, 1),
(2, 1, 2, 1, 2, 1, '2018-12-27', 51, 13, '002/2018', 'admin transfer', 1, 25000, 2000, 84, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
CREATE TABLE IF NOT EXISTS `calendar_events` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `noti_time` int(11) DEFAULT NULL,
  `noti_every` tinyint(4) DEFAULT NULL,
  `event_color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_public` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `title`, `description`, `start_date`, `end_date`, `noti_time`, `noti_every`, `event_color`, `is_public`, `created_by`, `created_at`) VALUES
(1, 'Birthday', 'Birthday', '2019-01-24 00:00:00', '2019-01-24 00:00:00', 10, 1, '#8e44ad', 0, 1, '2019-01-23 06:11:17'),
(2, 'Birthday', 'Birthday', '2019-01-24 00:00:00', '2019-01-24 00:00:00', 10, 1, '#2ecc71', 0, 1, '2019-01-23 06:11:48'),
(3, 'Birthday', 'Birthday', '2019-01-24 00:00:00', '2019-01-24 00:00:00', 10, 2, '#8e44ad', 0, 1, '2019-01-23 06:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country`, `code`) VALUES
(1, 'United States', 'US'),
(2, 'Canada', 'CA'),
(3, 'Afghanistan', 'AF'),
(4, 'Albania', 'AL'),
(5, 'Algeria', 'DZ'),
(6, 'American Samoa', 'AS'),
(7, 'Andorra', 'AD'),
(8, 'Angola', 'AO'),
(9, 'Anguilla', 'AI'),
(10, 'Antarctica', 'AQ'),
(11, 'Antigua and/or Barbuda', 'AG'),
(12, 'Argentina', 'AR'),
(13, 'Armenia', 'AM'),
(14, 'Aruba', 'AW'),
(15, 'Australia', 'AU'),
(16, 'Austria', 'AT'),
(17, 'Azerbaijan', 'AZ'),
(18, 'Bahamas', 'BS'),
(19, 'Bahrain', 'BH'),
(20, 'Bangladesh', 'BD'),
(21, 'Barbados', 'BB'),
(22, 'Belarus', 'BY'),
(23, 'Belgium', 'BE'),
(24, 'Belize', 'BZ'),
(25, 'Benin', 'BJ'),
(26, 'Bermuda', 'BM'),
(27, 'Bhutan', 'BT'),
(28, 'Bolivia', 'BO'),
(29, 'Bosnia and Herzegovina', 'BA'),
(30, 'Botswana', 'BW'),
(31, 'Bouvet Island', 'BV'),
(32, 'Brazil', 'BR'),
(33, 'British lndian Ocean Territory', 'IO'),
(34, 'Brunei Darussalam', 'BN'),
(35, 'Bulgaria', 'BG'),
(36, 'Burkina Faso', 'BF'),
(37, 'Burundi', 'BI'),
(38, 'Cambodia', 'KH'),
(39, 'Cameroon', 'CM'),
(40, 'Cape Verde', 'CV'),
(41, 'Cayman Islands', 'KY'),
(42, 'Central African Republic', 'CF'),
(43, 'Chad', 'TD'),
(44, 'Chile', 'CL'),
(45, 'China', 'CN'),
(46, 'Christmas Island', 'CX'),
(47, 'Cocos (Keeling) Islands', 'CC'),
(48, 'Colombia', 'CO'),
(49, 'Comoros', 'KM'),
(50, 'Congo', 'CG'),
(51, 'Cook Islands', 'CK'),
(52, 'Costa Rica', 'CR'),
(53, 'Croatia (Hrvatska)', 'HR'),
(54, 'Cuba', 'CU'),
(55, 'Cyprus', 'CY'),
(56, 'Czech Republic', 'CZ'),
(57, 'Democratic Republic of Congo', 'CD'),
(58, 'Denmark', 'DK'),
(59, 'Djibouti', 'DJ'),
(60, 'Dominica', 'DM'),
(61, 'Dominican Republic', 'DO'),
(62, 'East Timor', 'TP'),
(63, 'Ecudaor', 'EC'),
(64, 'Egypt', 'EG'),
(65, 'El Salvador', 'SV'),
(66, 'Equatorial Guinea', 'GQ'),
(67, 'Eritrea', 'ER'),
(68, 'Estonia', 'EE'),
(69, 'Ethiopia', 'ET'),
(70, 'Falkland Islands (Malvinas)', 'FK'),
(71, 'Faroe Islands', 'FO'),
(72, 'Fiji', 'FJ'),
(73, 'Finland', 'FI'),
(74, 'France', 'FR'),
(75, 'France, Metropolitan', 'FX'),
(76, 'French Guiana', 'GF'),
(77, 'French Polynesia', 'PF'),
(78, 'French Southern Territories', 'TF'),
(79, 'Gabon', 'GA'),
(80, 'Gambia', 'GM'),
(81, 'Georgia', 'GE'),
(82, 'Germany', 'DE'),
(83, 'Ghana', 'GH'),
(84, 'Gibraltar', 'GI'),
(85, 'Greece', 'GR'),
(86, 'Greenland', 'GL'),
(87, 'Grenada', 'GD'),
(88, 'Guadeloupe', 'GP'),
(89, 'Guam', 'GU'),
(90, 'Guatemala', 'GT'),
(91, 'Guinea', 'GN'),
(92, 'Guinea-Bissau', 'GW'),
(93, 'Guyana', 'GY'),
(94, 'Haiti', 'HT'),
(95, 'Heard and Mc Donald Islands', 'HM'),
(96, 'Honduras', 'HN'),
(97, 'Hong Kong', 'HK'),
(98, 'Hungary', 'HU'),
(99, 'Iceland', 'IS'),
(100, 'India', 'IN'),
(101, 'Indonesia', 'ID'),
(102, 'Iran (Islamic Republic of)', 'IR'),
(103, 'Iraq', 'IQ'),
(104, 'Ireland', 'IE'),
(105, 'Israel', 'IL'),
(106, 'Italy', 'IT'),
(107, 'Ivory Coast', 'CI'),
(108, 'Jamaica', 'JM'),
(109, 'Japan', 'JP'),
(110, 'Jordan', 'JO'),
(111, 'Kazakhstan', 'KZ'),
(112, 'Kenya', 'KE'),
(113, 'Kiribati', 'KI'),
(114, 'Korea, Democratic People\'s Republic of', 'KP'),
(115, 'Korea, Republic of', 'KR'),
(116, 'Kuwait', 'KW'),
(117, 'Kyrgyzstan', 'KG'),
(118, 'Lao People\'s Democratic Republic', 'LA'),
(119, 'Latvia', 'LV'),
(120, 'Lebanon', 'LB'),
(121, 'Lesotho', 'LS'),
(122, 'Liberia', 'LR'),
(123, 'Libyan Arab Jamahiriya', 'LY'),
(124, 'Liechtenstein', 'LI'),
(125, 'Lithuania', 'LT'),
(126, 'Luxembourg', 'LU'),
(127, 'Macau', 'MO'),
(128, 'Macedonia', 'MK'),
(129, 'Madagascar', 'MG'),
(130, 'Malawi', 'MW'),
(131, 'Malaysia', 'MY'),
(132, 'Maldives', 'MV'),
(133, 'Mali', 'ML'),
(134, 'Malta', 'MT'),
(135, 'Marshall Islands', 'MH'),
(136, 'Martinique', 'MQ'),
(137, 'Mauritania', 'MR'),
(138, 'Mauritius', 'MU'),
(139, 'Mayotte', 'TY'),
(140, 'Mexico', 'MX'),
(141, 'Micronesia, Federated States of', 'FM'),
(142, 'Moldova, Republic of', 'MD'),
(143, 'Monaco', 'MC'),
(144, 'Mongolia', 'MN'),
(145, 'Montserrat', 'MS'),
(146, 'Morocco', 'MA'),
(147, 'Mozambique', 'MZ'),
(148, 'Myanmar', 'MM'),
(149, 'Namibia', 'NA'),
(150, 'Nauru', 'NR'),
(151, 'Nepal', 'NP'),
(152, 'Netherlands', 'NL'),
(153, 'Netherlands Antilles', 'AN'),
(154, 'New Caledonia', 'NC'),
(155, 'New Zealand', 'NZ'),
(156, 'Nicaragua', 'NI'),
(157, 'Niger', 'NE'),
(158, 'Nigeria', 'NG'),
(159, 'Niue', 'NU'),
(160, 'Norfork Island', 'NF'),
(161, 'Northern Mariana Islands', 'MP'),
(162, 'Norway', 'NO'),
(163, 'Oman', 'OM'),
(164, 'Pakistan', 'PK'),
(165, 'Palau', 'PW'),
(166, 'Panama', 'PA'),
(167, 'Papua New Guinea', 'PG'),
(168, 'Paraguay', 'PY'),
(169, 'Peru', 'PE'),
(170, 'Philippines', 'PH'),
(171, 'Pitcairn', 'PN'),
(172, 'Poland', 'PL'),
(173, 'Portugal', 'PT'),
(174, 'Puerto Rico', 'PR'),
(175, 'Qatar', 'QA'),
(176, 'Republic of South Sudan', 'SS'),
(177, 'Reunion', 'RE'),
(178, 'Romania', 'RO'),
(179, 'Russian Federation', 'RU'),
(180, 'Rwanda', 'RW'),
(181, 'Saint Kitts and Nevis', 'KN'),
(182, 'Saint Lucia', 'LC'),
(183, 'Saint Vincent and the Grenadines', 'VC'),
(184, 'Samoa', 'WS'),
(185, 'San Marino', 'SM'),
(186, 'Sao Tome and Principe', 'ST'),
(187, 'Saudi Arabia', 'SA'),
(188, 'Senegal', 'SN'),
(189, 'Serbia', 'RS'),
(190, 'Seychelles', 'SC'),
(191, 'Sierra Leone', 'SL'),
(192, 'Singapore', 'SG'),
(193, 'Slovakia', 'SK'),
(194, 'Slovenia', 'SI'),
(195, 'Solomon Islands', 'SB'),
(196, 'Somalia', 'SO'),
(197, 'South Africa', 'ZA'),
(198, 'South Georgia South Sandwich Islands', 'GS'),
(199, 'Spain', 'ES'),
(200, 'Sri Lanka', 'LK'),
(201, 'St. Helena', 'SH'),
(202, 'St. Pierre and Miquelon', 'PM'),
(203, 'Sudan', 'SD'),
(204, 'Suriname', 'SR'),
(205, 'Svalbarn and Jan Mayen Islands', 'SJ'),
(206, 'Swaziland', 'SZ'),
(207, 'Sweden', 'SE'),
(208, 'Switzerland', 'CH'),
(209, 'Syrian Arab Republic', 'SY'),
(210, 'Taiwan', 'TW'),
(211, 'Tajikistan', 'TJ'),
(212, 'Tanzania, United Republic of', 'TZ'),
(213, 'Thailand', 'TH'),
(214, 'Togo', 'TG'),
(215, 'Tokelau', 'TK'),
(216, 'Tonga', 'TO'),
(217, 'Trinidad and Tobago', 'TT'),
(218, 'Tunisia', 'TN'),
(219, 'Turkey', 'TR'),
(220, 'Turkmenistan', 'TM'),
(221, 'Turks and Caicos Islands', 'TC'),
(222, 'Tuvalu', 'TV'),
(223, 'Uganda', 'UG'),
(224, 'Ukraine', 'UA'),
(225, 'United Arab Emirates', 'AE'),
(226, 'United Kingdom', 'GB'),
(227, 'United States minor outlying islands', 'UM'),
(228, 'Uruguay', 'UY'),
(229, 'Uzbekistan', 'UZ'),
(230, 'Vanuatu', 'VU'),
(231, 'Vatican City State', 'VA'),
(232, 'Venezuela', 'VE'),
(233, 'Vietnam', 'VN'),
(234, 'Virgin Islands (British)', 'VG'),
(235, 'Virgin Islands (U.S.)', 'VI'),
(236, 'Wallis and Futuna Islands', 'WF'),
(237, 'Western Sahara', 'EH'),
(238, 'Yemen', 'YE'),
(239, 'Yugoslavia', 'YU'),
(240, 'Zaire', 'ZR'),
(241, 'Zambia', 'ZM'),
(242, 'Zimbabwe', 'ZW');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `exchange_rate` double NOT NULL,
  `exchange_from` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`, `symbol`, `exchange_rate`, `exchange_from`) VALUES
(1, 'USD', '$', 1, 'local'),
(2, 'BDT', '৳', 0, ''),
(3, 'EGP', '£', 0, ''),
(4, 'EUR', '€', 0, ''),
(5, 'INR', '₨', 0, ''),
(6, 'LKR', 'Rs', 0, ''),
(7, 'MYR', 'RM', 0, ''),
(8, 'NPR', '₨', 0, ''),
(9, 'PKR', '₨', 0, ''),
(10, 'VES', 'BsB', 1.2, 'local'),
(11, 'Venezuelan bolívar', 'Bs.S', 0.25, 'local');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `sales_type` int(11) NOT NULL,
  `customer_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `is_activated` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `first_name`, `last_name`, `email`, `password`, `address`, `phone`, `tax_id`, `currency_id`, `sales_type`, `customer_type`, `timezone`, `remember_token`, `inactive`, `is_activated`, `created_at`, `updated_at`) VALUES
(1, 'Ratool Apparals', 'Ratool', 'Apparals', 'ratoolapparals@gmail.com', '$2y$10$6N5yeWQx3n5lzpxhuUOYxunaRvIim95NCImcaNg/kZLo3L.4WYzTq', '', '8801932792385', NULL, 1, 0, NULL, NULL, 'BgU9WA42hyiUYXHWzWYmO7yjfHmC6TClvc2uKsMJNRYCEYJvPWnpByX6VBnW', 0, 0, '2018-12-09 00:18:02', '2019-01-20 03:47:08'),
(2, 'Steve Jobs', 'Steve', 'Jobs', 'milon.techvill@gmail.com', '$2y$10$Zcyhbt9jH54dXgTNeXb.WO//3KAuJiA3reDkVxFBraLcPr0gloQDK', '', '123456', NULL, 1, 0, NULL, NULL, 'G28fUHA7JVfSKTEmbtvz1IAuN4PMwHvsGDbuKv39501LlxGGycTdpe9aLa7Z', 0, 0, '2018-12-09 00:41:52', '2018-12-26 23:59:09'),
(3, 'Mike Kazi', 'Mike', 'Kazi', 'kazi@gmail.com', '$2y$10$U87Yp.umT.u.RGY7uHiSIua5es5j8ngKmHNiDksnuJLPJZJNkozWy', '', NULL, NULL, 2, 0, NULL, NULL, 'VGKH3BFajvtGvfszgiedfCXZHP1oYsTgIlS0hdxwGHe5h1f3BpkM7xv9gZQa', 0, 0, '2018-12-09 05:17:57', '2019-01-30 07:07:00'),
(4, '', 'Tamim', 'Iqbal', 'tamim@bcb.com', '$2y$10$4WBNqrwoZWgdS2uEfOHDlOA5NiFMRbJYoiMPA/SDuiVtQ6rbn79z6', '', '123', NULL, 1, 0, NULL, 'Asia/Dhaka', 'BvwEaxzmtX6trYg1oEebnuLijm9BeMH7cQZraCLUD6pSXRahEPHHRT2zOLpm', 0, 0, '2018-12-09 07:09:03', '2019-01-19 23:36:04'),
(5, '', 'Customer', 'Techvillage', 'customer@techvill.net', '$2y$10$JLxAFdl8k0NnJE18Aws1K.IviBaKi9qTS91RaVZyhQWolw58jOB36', '', '123', NULL, 1, 0, NULL, 'Asia/Dhaka', 'z1dalSauvZrMmTPBJ7SurpdEfI6ni5DGBpJ9Pe1AnBBZVciDUpzKSih3ZwAP', 0, 0, '2018-12-10 00:20:03', '2019-01-19 05:17:06'),
(6, 'Test customer', 'Test', 'customer', 'testcustomer@yahoo.com', '', '', NULL, NULL, 1, 0, NULL, NULL, 'LHdXg2U9zeW2CsefOQupxgoqxAG5ucweXJDVx9iJmbtsRgdVAImNuP5jv9m1', 0, 0, '2018-12-27 01:04:08', '2019-01-27 07:01:49'),
(7, '', 'customer', '123', 'customer.321@gmail.com', '$2y$10$mVkVa5IGbT64eQs24ZEMkealpMIpQ2SluGFO1bYvy0s0D.I0yZ3O2', '', NULL, NULL, 1, 0, NULL, NULL, 'pxBBPxrNnSvK32k9CjlPh2qmAfPGHpvLH9qjUCgLEYImq2VILnrQKBcyHMRy', 0, 0, '2018-12-27 01:06:39', '2019-01-14 01:54:43'),
(8, '', 'Farzana', 'borna', 'borna.techvill@gmail.com', '$2y$10$4Nmlp7o47TxktFQpor1cSeJJwbQjTtBUs.mmFUZEf6k2Nczx0mULy', '', NULL, NULL, 1, 0, NULL, NULL, 'pT1f0lcJJrbjHB4UvV5lLjIh82yxFnRmHev121WcbOv9GTysNrzZoRdEPvJD', 0, 0, '2019-01-10 00:34:54', '2019-01-19 23:38:29'),
(9, 'MD. Ariful Alam', 'MD. Ariful', 'Alam', 'arif.techvill@gmail.com', '$2y$10$hMY2wLiy93ORMndakuPGDearc3cUTK5jabBIgcIni0fSxXekR2z1O', '', '011111111111', '12', 2, 0, NULL, NULL, '5cUmDUpLywc7aiY7GMVIXNCfmCNH871Oz17yV6EXUTC8FfVcVAkoCFZTspm9', 0, 0, '2019-01-20 00:58:14', '2019-01-20 01:27:22'),
(10, 'Milon Hossain', 'Milon', 'Hossain', 'milon.techvill1@gmail.com', '', '', '123456', NULL, 1, 0, NULL, NULL, '', 0, 0, '2019-01-23 04:11:29', '2019-01-23 04:11:29'),
(11, 'customer name', 'customer', 'name', 'customer321@gmail.com', '$2y$10$axubMo1jk3cea.GNyUeA/ulsd2m4EP0wBB/QprdGLtppEvrIVCtfm', '', NULL, NULL, 10, 0, NULL, NULL, '', 0, 0, '2019-01-27 07:02:56', '2019-01-27 07:03:51'),
(12, 'Shakib Mostahid', 'Shakib', 'Mostahid', 'mostahid@gmail.com', '', '', '01111111111', NULL, 1, 0, NULL, NULL, '', 0, 0, '2019-01-29 01:18:50', '2019-01-29 01:18:50'),
(13, 'Urmy Khan', 'Urmy', 'Khan', 'nishat1@gmail.com', '', '', '11111111', NULL, 2, 0, NULL, NULL, '', 0, 0, '2019-01-29 01:42:13', '2019-01-29 01:42:13'),
(14, 'Gracie Leannon', 'Gracie', 'Leannon', 'rowena43@example.net', '', '', '234234234', NULL, 1, 0, NULL, NULL, '', 0, 0, '2019-02-03 00:38:13', '2019-02-03 00:38:13'),
(15, 'Borna techvill', 'Borna', 'techvill', 'borna.techvill123@yahoo.com', '', '', '123456', NULL, 1, 0, NULL, NULL, '', 0, 0, '2019-02-03 01:24:56', '2019-02-03 01:27:10'),
(16, 'Borna techvill', 'Borna', 'techvill', '', '', '', '01715235467', NULL, 1, 0, NULL, NULL, '7lA7ejhUPRvAHDZpqqul0lfXuC9KHdDp8kEcU3ajdZX1upya2h38aFPDIGts', 0, 0, '2019-02-03 03:27:01', '2019-02-03 03:27:49'),
(17, 'Walton Primo', 'Walton', 'Primo', 'walton@bd.com', '', '', '123456', NULL, 1, 0, NULL, NULL, '', 1, 0, '2019-02-03 04:03:56', '2019-02-03 04:18:15');

-- --------------------------------------------------------

--
-- Table structure for table `customer_activations`
--

DROP TABLE IF EXISTS `customer_activations`;
CREATE TABLE IF NOT EXISTS `customer_activations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_activations`
--

INSERT INTO `customer_activations` (`id`, `customer_id`, `token`, `created_at`) VALUES
(1, 4, 'qE1wBNcFxhyusgftKZRC7Z3ECnWObe', '2018-12-09 13:09:04'),
(2, 5, 'sVeyw1gpUmtBtK0BlEF51jH1IUmTap', '2018-12-10 06:20:04'),
(3, 7, 't0iNDkXtsJFvbxnOgzBruDgQwkOmv0', '2018-12-27 07:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `customer_transactions`
--

DROP TABLE IF EXISTS `customer_transactions`;
CREATE TABLE IF NOT EXISTS `customer_transactions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `payment_type_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(11) UNSIGNED NOT NULL,
  `order_reference` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_reference` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `invoice_exchange_rate` double NOT NULL,
  `transaction_date` date NOT NULL,
  `amount` double NOT NULL,
  `exchange_rate` double NOT NULL,
  `incoming_amount` double NOT NULL,
  `status` enum('Pending','Approved','Declined') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Approved',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_transactions_user_id_foreign` (`user_id`),
  KEY `customer_transactions_payment_type_id_foreign` (`payment_type_id`),
  KEY `customer_transactions_customer_id_foreign` (`customer_id`),
  KEY `customer_transaction_reference_id_idx` (`reference_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_transactions`
--

INSERT INTO `customer_transactions` (`id`, `user_id`, `payment_type_id`, `customer_id`, `invoice_id`, `order_reference`, `invoice_reference`, `reference_id`, `reference`, `currency_id`, `invoice_exchange_rate`, `transaction_date`, `amount`, `exchange_rate`, `incoming_amount`, `status`, `created_at`) VALUES
(1, 1, 1, 5, 14, 'QN-0007', 'INV-0007', 18, '018/2018', 1, 85, '2018-12-17', 5, 1, 0, 'Approved', '2018-12-17 04:55:28'),
(2, 1, 2, 1, 19, 'QN-0010', 'INV-0009', 20, '019/2018', 1, 80, '2018-12-20', 8000, 1, 0, 'Approved', '2018-12-20 11:22:34'),
(3, 1, 2, 5, 12, 'QN-0006', 'INV-0006', 21, '020/2018', 1, 79, '2018-12-23', 20045, 1, 0, 'Approved', '2018-12-23 09:17:01'),
(4, 1, 2, 3, 25, 'QN-0013', 'INV-0012', 22, '021/2018', 2, 1, '2018-12-23', 8000, 1, 0, 'Approved', '2018-12-23 09:56:55'),
(5, 1, 2, 2, 8, 'QN-0004', 'INV-0004', 23, '022/2018', 1, 78, '2018-12-23', 472, 1, 0, 'Approved', '2018-12-23 10:03:38'),
(6, 1, 2, 1, 16, 'QN-0008', 'INV-0008', 24, '023/2018', 1, 20, '2018-12-23', 20, 1, 0, 'Approved', '2018-12-23 10:05:05'),
(7, 1, 2, 3, 23, 'QN-0012', 'INV-0011', 25, '024/2018', 2, 1, '2018-12-23', 8000, 1, 0, 'Approved', '2018-12-23 10:05:27'),
(8, 1, 2, 3, 6, 'QN-0003', 'INV-0003', 26, '025/2018', 2, 1, '2018-12-23', 28350, 1, 0, 'Approved', '2018-12-23 10:06:58'),
(9, 1, 2, 2, 8, 'QN-0004', 'INV-0004', 27, '026/2018', 1, 78, '2018-12-23', 467, 1, 0, 'Approved', '2018-12-23 10:07:30'),
(10, 1, 2, 2, 8, 'QN-0004', 'INV-0004', 28, '027/2018', 1, 78, '2018-12-23', 463, 1, 0, 'Approved', '2018-12-23 10:10:37'),
(11, 1, 2, 2, 8, 'QN-0004', 'INV-0004', 29, '028/2018', 1, 78, '2018-12-23', 458, 1, 0, 'Approved', '2018-12-23 10:14:23'),
(12, 1, 2, 2, 8, 'QN-0004', 'INV-0004', 30, '029/2018', 1, 78, '2018-12-23', 453, 1, 0, 'Approved', '2018-12-23 10:15:19'),
(13, 1, 2, 5, 29, 'QN-0015', 'INV-0014', 31, '030/2018', 1, 50, '2018-12-24', 100, 1, 0, 'Approved', '2018-12-24 06:48:18'),
(14, 1, 2, 5, 29, 'QN-0015', 'INV-0014', 32, '031/2018', 1, 50, '2018-12-24', 99, 1, 0, 'Approved', '2018-12-24 06:50:38'),
(15, 1, 1, 5, 29, 'QN-0015', 'INV-0014', 33, '032/2018', 1, 50, '2018-12-24', 9801, 1, 0, 'Approved', '2018-12-24 07:33:10'),
(16, 1, 1, 5, 29, 'QN-0015', 'INV-0014', 34, '033/2018', 1, 50, '2018-12-24', 0, 1, 0, 'Approved', '2018-12-24 07:33:23'),
(17, 1, 1, 5, 29, 'QN-0015', 'INV-0014', 35, '034/2018', 1, 50, '2018-12-24', 0, 1, 0, 'Approved', '2018-12-24 07:34:18'),
(18, 1, 2, 3, 27, 'QN-0014', 'INV-0013', 36, '035/2018', 2, 1, '2018-12-24', 8000, 1, 0, 'Approved', '2018-12-24 07:42:15'),
(19, 1, 1, 5, 31, 'QN-0016', 'INV-0015', 37, '036/2018', 1, 50, '2018-12-24', 6000, 1, 0, 'Approved', '2018-12-24 08:07:36'),
(20, 1, 1, 5, 33, 'QN-0017', 'INV-0016', 38, '037/2018', 1, 50, '2018-12-25', 1000, 1, 0, 'Approved', '2018-12-24 08:09:06'),
(21, 1, 1, 5, 35, 'QN-0018', 'INV-0017', 39, '038/2018', 1, 50, '2018-12-24', 900, 1, 0, 'Approved', '2018-12-24 08:12:28'),
(22, 1, 1, 5, 37, 'QN-0019', 'INV-0018', 40, '039/2018', 1, 50, '2018-12-24', 800, 1, 0, 'Approved', '2018-12-24 08:14:54'),
(23, 1, 1, 5, 37, 'QN-0019', 'INV-0018', 41, '040/2018', 1, 50, '2018-12-24', 0, 1, 0, 'Approved', '2018-12-24 08:15:05'),
(24, 1, 1, 5, 39, 'QN-0020', 'INV-0019', 42, '041/2018', 1, 50, '2018-12-24', 700, 1, 0, 'Approved', '2018-12-24 08:16:44'),
(25, 1, 1, 5, 41, 'QN-0021', 'INV-0020', 43, '042/2018', 1, 50, '2018-12-24', 600, 1, 0, 'Approved', '2018-12-24 08:18:43'),
(26, 1, 1, 5, 41, 'QN-0021', 'INV-0020', 44, '043/2018', 1, 50, '2018-12-24', 0, 1, 0, 'Approved', '2018-12-24 08:18:58'),
(27, 1, 1, 5, 41, 'QN-0021', 'INV-0020', 45, '044/2018', 1, 50, '2018-12-24', 0, 1, 0, 'Approved', '2018-12-24 08:20:11'),
(28, 15, 2, 3, 51, 'QN-0027', 'INV-0024', 46, '045/2018', 2, 1, '2018-12-26', 8000, 1, 0, 'Approved', '2018-12-26 13:04:12'),
(29, 15, 2, 2, 53, 'QN-0028', 'INV-0025', 47, '046/2018', 1, 50, '2018-12-27', 5000, 1, 0, 'Approved', '2018-12-27 05:04:20'),
(30, 15, 2, 2, 8, 'QN-0004', 'INV-0004', 48, '047/2018', 1, 78, '2018-12-27', 449, 1, 0, 'Approved', '2018-12-27 05:06:20'),
(31, 1, 2, 1, 60, 'QN-0032', 'INV-0028', 56, '048/2018', 1, 80, '2018-12-27', 200, 1, 0, 'Approved', '2018-12-27 07:51:45'),
(32, 1, 2, 1, 60, 'QN-0032', 'INV-0028', 57, '049/2018', 1, 80, '2018-12-27', 20, 1, 0, 'Approved', '2018-12-27 07:54:24'),
(33, 15, 2, 1, 68, 'QN-0036', 'INV-0032', 58, '050/2018', 1, 80, '2018-12-27', 77, 1, 0, 'Approved', '2018-12-27 09:35:19'),
(34, 15, 2, 2, 88, 'QN-0047', 'INV-0040', 59, '051/2018', 1, 80, '2018-12-27', 120, 1, 0, 'Approved', '2018-12-27 10:52:07'),
(35, 1, 2, 2, 98, 'QN-0052', 'INV-0045', 60, '052/2018', 1, 80, '2018-12-27', 100, 1, 0, 'Approved', '2018-12-27 11:03:48'),
(36, 1, 2, 1, 136, 'QN-0071', 'INV-0064', 61, '053/2019', 1, 0.12, '2019-01-02', 100, 1, 0, 'Approved', '2019-01-02 12:52:20'),
(37, 1, 2, 5, 21, 'QN-0011', 'INV-0010', 64, '054/2019', 1, 60, '2019-01-05', 80000, 1, 0, 'Approved', '2019-01-05 12:36:24'),
(38, 1, 2, 5, 21, 'QN-0011', 'INV-0010', 65, '055/2019', 1, 60, '2019-01-06', 800, 1, 0, 'Approved', '2019-01-06 09:28:13'),
(39, 1, 2, 5, 21, 'QN-0011', 'INV-0010', 66, '056/2019', 1, 60, '2019-01-06', 800, 1, 0, 'Approved', '2019-01-06 09:34:05'),
(40, 1, 2, 5, 21, 'QN-0011', 'INV-0010', 67, '057/2019', 1, 60, '2019-01-06', 800, 1, 0, 'Pending', '2019-01-06 09:45:44'),
(41, 1, 2, 5, 152, 'QN-0073', 'INV-0066', 68, '058/2019', 1, 81, '2019-01-06', 200, 1, 0, 'Approved', '2019-01-06 09:51:33'),
(42, 1, 2, 5, 154, 'QN-0074', 'INV-0067', 69, '059/2019', 1, 81, '2019-01-06', 100, 1, 0, 'Approved', '2019-01-06 10:02:42'),
(43, 1, 2, 5, 156, 'QN-0075', 'INV-0068', 70, '060/2019', 1, 81, '2019-01-06', 150, 1, 0, 'Approved', '2019-01-06 10:07:09'),
(44, 1, 2, 5, 158, 'QN-0076', 'INV-0069', 71, '061/2019', 1, 79, '2019-01-06', 3000, 1, 0, 'Approved', '2019-01-06 10:11:14'),
(45, 1, 2, 5, 158, 'QN-0076', 'INV-0069', 72, '062/2019', 1, 79, '2019-01-06', 3001, 1, 0, 'Approved', '2019-01-06 10:12:16'),
(46, 1, 2, 5, 160, 'QN-0077', 'INV-0070', 73, '063/2019', 1, 81, '2019-01-06', 850, 1, 0, 'Approved', '2019-01-06 11:23:43'),
(47, 1, 2, 5, 162, 'QN-0078', 'INV-0071', 74, '064/2019', 1, 81, '2019-01-06', 50, 1, 0, 'Approved', '2019-01-06 12:31:06'),
(48, 1, 2, 5, 162, 'QN-0078', 'INV-0071', 75, '065/2019', 1, 81, '2019-01-06', 50, 1, 0, 'Approved', '2019-01-06 12:32:12'),
(49, 1, 2, 5, 166, 'QN-0080', 'INV-0073', 76, '066/2019', 1, 81, '2019-01-06', 500, 1, 0, 'Approved', '2019-01-06 12:58:50'),
(50, 1, 2, 5, 168, 'QN-0081', 'INV-0074', 77, '067/2019', 1, 81, '2019-01-07', 600, 1, 0, 'Pending', '2019-01-07 05:15:10'),
(51, 1, 2, 2, 170, 'QN-0082', 'INV-0075', 79, '068/2019', 1, 0.12, '2019-01-09', 8000, 1, 0, 'Approved', '2019-01-09 09:58:02'),
(52, 1, 1, 2, 170, 'QN-0082', 'INV-0075', 80, '069/2019', 1, 0.12, '2019-01-09', 50, 1, 0, 'Approved', '2019-01-09 10:01:20'),
(53, 1, 2, 1, 185, 'QN-0091', 'INV-0079', 81, '070/2019', 1, 90, '2019-01-15', 100, 1, 0, 'Approved', '2019-01-15 05:10:49'),
(54, 1, 2, 3, 195, 'QN-0098', 'INV-0082', 83, '071/2019', 2, 0.0125, '2019-01-21', 8000, 1, 0, 'Approved', '2019-01-21 11:48:05'),
(55, 1, 2, 5, 206, 'QN-0104', 'INV-0087', 85, '072/2019', 1, 1, '2019-01-23', 100, 1, 0, 'Approved', '2019-01-23 09:59:57'),
(56, 1, 2, 5, 206, 'QN-0104', 'INV-0087', 86, '073/2019', 1, 1, '2019-01-23', 50, 1, 0, 'Approved', '2019-01-23 10:07:33'),
(57, 1, 1, 5, 206, 'QN-0104', 'INV-0087', 87, '074/2019', 1, 1, '2019-01-23', 8092, 0.12, 0, 'Approved', '2019-01-23 10:13:10'),
(58, 1, 2, 5, 210, 'QN-0106', 'INV-0089', 88, '075/2019', 1, 1, '2019-01-24', 40, 1, 0, 'Declined', '2019-01-24 12:21:40'),
(59, 1, 2, 5, 210, 'QN-0106', 'INV-0089', 89, '076/2019', 1, 1, '2019-01-24', 10, 1, 0, 'Approved', '2019-01-24 12:57:35'),
(60, 1, 2, 5, 210, 'QN-0106', 'INV-0089', 90, '077/2019', 1, 1, '2019-01-24', 30, 0.12, 0, 'Approved', '2019-01-24 13:08:54'),
(61, 1, 2, 5, 218, 'QN-0110', 'INV-0093', 91, '078/2019', 1, 0.11, '2019-01-29', 20, 1, 0, 'Approved', '2019-01-29 06:54:28'),
(62, 1, 2, 5, 218, 'QN-0110', 'INV-0093', 92, '079/2019', 1, 0.11, '2019-01-29', 5, 1, 0, 'Approved', '2019-01-29 07:26:01'),
(63, 1, 1, 5, 218, 'QN-0110', 'INV-0093', 93, '080/2019', 1, 0.11, '2019-01-29', 2, 1, 0, 'Approved', '2019-01-29 07:49:40'),
(64, 1, 1, 5, 218, 'QN-0110', 'INV-0093', 94, '081/2019', 1, 0.11, '2019-01-29', 3, 1, 0, 'Approved', '2019-01-29 07:56:13'),
(65, 1, 1, 5, 218, 'QN-0110', 'INV-0093', 95, '082/2019', 1, 0.11, '2019-01-29', 20, 50, 0.044, 'Approved', '2019-01-29 12:01:22'),
(66, 1, 1, 1, 220, 'QN-0111', 'INV-0094', 96, '083/2019', 1, 79, '2019-01-29', 99, 98, 79.81, 'Approved', '2019-01-29 12:06:58'),
(67, 1, 1, 5, 222, 'QN-0112', 'INV-0095', 97, '084/2019', 1, 80, '2019-01-29', 50, 40, 100, 'Approved', '2019-01-29 13:06:37'),
(68, 1, 2, 5, 224, 'QN-0113', 'INV-0096', 98, '085/2019', 1, 80, '2019-01-30', 30, 1, 0, 'Approved', '2019-01-30 09:38:34'),
(69, 1, 1, 5, 224, 'QN-0113', 'INV-0096', 99, '086/2019', 1, 80, '2019-01-30', 70, 1, 0, 'Approved', '2019-01-30 10:14:51'),
(70, 1, 1, 5, 224, 'QN-0113', 'INV-0096', 100, '087/2019', 1, 80, '2019-01-30', 500, 40, 1000, 'Approved', '2019-01-30 10:47:57'),
(71, 1, 1, 5, 224, 'QN-0113', 'INV-0096', 101, '088/2019', 1, 80, '2019-01-30', 600, 50, 960, 'Approved', '2019-01-30 10:54:07'),
(72, 1, 1, 5, 224, 'QN-0113', 'INV-0096', 102, '089/2019', 1, 80, '2019-01-30', 200, 70, 400, 'Approved', '2019-01-30 10:56:21'),
(73, 1, 2, 5, 224, 'QN-0113', 'INV-0096', 105, '090/2019', 1, 80, '2019-01-30', 800, 1, 0, 'Pending', '2019-01-30 11:37:13'),
(74, 1, 2, 5, 230, 'QN-0116', 'INV-0099', 106, '091/2019', 1, 1, '2019-01-30', 550, 1, 0, 'Approved', '2019-01-30 11:45:59'),
(75, 1, 2, 5, 230, 'QN-0116', 'INV-0099', 107, '092/2019', 1, 1, '2019-01-30', 50, 1, 0, 'Approved', '2019-01-30 11:55:01'),
(76, 1, 2, 5, 234, 'QN-0118', 'INV-0101', 110, '093/2019', 1, 1, '2019-01-31', 500, 1, 0, 'Approved', '2019-01-31 06:57:44'),
(77, 1, 2, 5, 234, 'QN-0118', 'INV-0101', 111, '094/2019', 1, 1, '2019-01-31', 57, 1, 0, 'Approved', '2019-01-31 07:16:05'),
(78, 1, 1, 3, 232, 'QN-0117', 'INV-0100', 114, '095/2019', 2, 50, '2019-01-31', 5000, 50, 0, 'Approved', '2019-01-31 10:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `custom_item_orders`
--

DROP TABLE IF EXISTS `custom_item_orders`;
CREATE TABLE IF NOT EXISTS `custom_item_orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL,
  `tax_type_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` double NOT NULL,
  `discount_percent` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_item_orders_tax_type_id_foreign` (`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cust_branch`
--

DROP TABLE IF EXISTS `cust_branch`;
CREATE TABLE IF NOT EXISTS `cust_branch` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `br_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `br_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `br_contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_zip_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_country_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_zip_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_country_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cust_branch_customer_id_foreign` (`customer_id`),
  KEY `cust_branch_shipping_country_id_foreign` (`shipping_country_id`),
  KEY `cust_branch_billing_country_id_foreign` (`billing_country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cust_branch`
--

INSERT INTO `cust_branch` (`id`, `customer_id`, `br_name`, `br_address`, `br_contact`, `billing_street`, `billing_city`, `billing_state`, `billing_zip_code`, `billing_country_id`, `shipping_street`, `shipping_city`, `shipping_state`, `shipping_zip_code`, `shipping_country_id`) VALUES
(1, 1, 'Ratool Apparals', NULL, NULL, 'Baridhara DOHS', 'Dhaka', NULL, NULL, 'BD', 'Baridhara DOHS', 'Dhaka', NULL, NULL, 'BD'),
(2, 2, 'Steve Jobs', NULL, NULL, 'Torento', 'US', NULL, NULL, 'US', NULL, NULL, NULL, NULL, 'US'),
(3, 3, 'Mike Kazi', NULL, NULL, 'Nikunja-2', 'Dhaka', NULL, NULL, 'BD', NULL, NULL, NULL, NULL, NULL),
(4, 4, 'Tamim Iqbal', NULL, NULL, 'Dhaka', 'Dhaka', NULL, NULL, 'BD', 'Dhaka', 'Dhaka', NULL, NULL, 'BD'),
(5, 5, 'Customer Techvillage', NULL, NULL, 'Nikunja-2', 'Dhaka', NULL, NULL, 'BD', 'Nikunja-2', 'Dhaka', NULL, NULL, 'BD'),
(6, 6, 'Test customer', NULL, NULL, 'Dhaka', 'Dhaka', NULL, NULL, 'BD', 'Dhaka', 'Dhaka', NULL, NULL, 'BD'),
(7, 7, 'customer 123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, 'Farzana borna', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 9, 'MD. Ariful Alam', NULL, NULL, 'Test Street', 'Test City', 'Test State', '12345', 'BS', 'Test Street', 'Test City', 'Test State', '12346', 'BD'),
(10, 10, 'Milon Hossain', NULL, NULL, 'Nikunja-2', 'Dhaka', 'Bd', '1213', 'BD', 'Nikunja-2', 'Dhaka', 'Bd', '1213', 'BD'),
(11, 11, 'customer name', NULL, NULL, 'USA', 'New york', NULL, NULL, 'BR', 'USA', 'New york', NULL, NULL, 'BR'),
(12, 12, 'Shakib Mostahid', NULL, NULL, 'Dummy Street', 'Demo City', 'Dummy State', '1234', 'US', 'Dummy Street', 'Demo City', 'Dummy State', '1234', 'US'),
(13, 13, 'Urmy Khan', NULL, NULL, 'Dummy', 'Dummy', NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL),
(14, 14, 'Gracie Leannon', NULL, NULL, '267 Wolf Forge Suite 668', 'Marcellemouth', 'Vermont', '61208', 'GB', NULL, NULL, NULL, NULL, NULL),
(15, 15, 'Borna techvill', NULL, NULL, 'New york', 'New york', NULL, NULL, 'US', 'New york', 'New york', NULL, NULL, 'US'),
(16, 16, 'Borna techvill', NULL, NULL, 'Dhaka', 'Dhaka', 'Dhaka', '1216', 'BD', 'Dhaka', 'Dhaka', 'Dhaka', '1216', 'BD'),
(17, 17, 'Walton Primo', NULL, NULL, 'Dhaka', 'Dhaka', NULL, 'Dhaka', 'BD', 'Dhaka', 'Dhaka', NULL, 'Dhaka', 'BD');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Developer'),
(2, 'Gobbilng  project  support team'),
(3, 'vrent project support team'),
(4, 'stockpile  project  support team'),
(5, 'paymoney project  support team'),
(6, 'multivendor  project  support team');

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

DROP TABLE IF EXISTS `email_config`;
CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_encryption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_port` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1= varified, 0= unverified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`id`, `email_protocol`, `email_encryption`, `smtp_host`, `smtp_port`, `smtp_email`, `smtp_username`, `smtp_password`, `from_address`, `from_name`, `status`) VALUES
(1, 'smtp', 'tls', 'smtp.gmail.com', '587', 'stockpile.techvill@gmail.com', 'stockpile.techvill@gmail.com', 'xgldhlpedszmglvj', 'stockpile.techvill@gmail.com', 'stockpile.techvill@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_temp_details`
--

DROP TABLE IF EXISTS `email_temp_details`;
CREATE TABLE IF NOT EXISTS `email_temp_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `temp_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('email','sms') COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_temp_details`
--

INSERT INTO `email_temp_details` (`id`, `temp_id`, `subject`, `body`, `lang`, `type`, `lang_id`) VALUES
(1, 2, 'Your Quotation # {order_reference_no} from {company_name} has been shipped', 'Hi {customer_name},<br><br>Thank you for your Quotation. Here’s a brief overview of your shipment:<br>Quotation # {order_reference_no} was packed on {packed_date} and shipped on {delivery_date}.<br> <br><b>Shipping address   </b><br><br>{shipping_street}<br>{shipping_city}<br>{shipping_state}<br>{shipping_zip_code}<br>{shipping_country}<br><br><b>Item Summery</b><br>{item_information}<br> <br>If you have any questions, please feel free to reply to this email.<br><br>Regards<br>{company_name}<br><br><br>', 'en', 'email', 1),
(9, 1, 'Payment information for Quotation#{order_reference_no} and Invoice#{invoice_reference_no}.', '<p>Hi {customer_name},</p><p>Thank you for purchase our product and pay for this.</p><p>We just want to confirm a few details about payment information:</p><p><b>Customer Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}</p><p>{billing_country}<br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b></i></p><p><i>Quotation No : {order_reference_no}</i><br><i></i></p><p><i>Invoice No : {invoice_reference_no}</i><br></p><p><br></p><p>Regards,</p><p>{company_name}<br></p><br><br><br><br><br><br>', 'en', 'email', 1),
(17, 3, 'Payment information for Quotation#{order_reference_no} and Invoice#{invoice_reference_no}.', '<p>Hi {customer_name},</p><p>Thank you for purchase our product and pay for this.</p><p>We just want to confirm a few details about payment information:</p><p><b>Customer Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}<br></p><p>{billing_country}<br>&nbsp; &nbsp; &nbsp; &nbsp; <br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b><br>Quotation No : {order_reference_no}<br>&nbsp;</i><i>Invoice No : {invoice_reference_no}<br>&nbsp;</i>Regards,</p><p>{company_name} <br></p><br>', 'en', 'email', 1),
(25, 4, 'Your Invoice # {invoice_reference_no} for Quotation #{order_reference_no} from {company_name} has been created.', '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no} is for Quotation #{order_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Quotation summary<br></b></p><p><b></b>{invoice_summery}<br></p><p>Regards,</p><p>{company_name}<br></p><br><br>', 'en', 'email', 1),
(33, 5, 'Your Quotation # {order_reference_no} from {company_name} has been created.', '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your Quotation #{order_reference_no} that was created on {order_date}. The order total is {currency}{total_amount}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Quotation summary<br></b></p><p><b></b>{order_summery}<br></p><p>Regards,</p><p>{company_name}</p><br>\r\n\r\n<br>', 'en', 'email', 1),
(41, 6, 'Your purchase # {invoice_reference_no} from {company_name} has been created.', '<p>Hi {customer_name},</p><p>Thank you for your purchase. Here’s a brief overview of your purchase: Purchase no #{invoice_reference_no} . The puchase total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Purchase summary<br></b></p><p><b></b>{invoice_summery}<br></p><p>Regards,</p><p>{company_name}<br></p><br><br>', 'en', 'email', 1),
(49, 7, '{ticket_subject} #Ticket ID: {ticket_no}', '<p></p>\r\n<p>Hello {customer_name},</p><p>\r\n\r\nA new support ticket has been assigned to you.\r\n\r\n<br></p>{ticket_message}<br>\r\n            <br>\r\n            Ticket ID :{ticket_no}<br>\r\n            Subject :{ticket_subject}<br>\r\n            Status :{ticket_status}<br>\r\n            Ticket Url :{details}<br>\r\n           <p>Regards,</p><p>{company_name}</p>\r\n\r\n<br><br>', 'en', 'email', 1),
(57, 8, '{ticket_subject} #Ticket ID: {ticket_no}', '<p></p>\r\n<p>Hello {customer_name},</p>{ticket_message}<br>\r\n            <br>\r\n            Ticket ID :{ticket_no}<br>\r\n            Subject :{ticket_subject}<br>\r\n            Status :{ticket_status}<br>\r\n            Ticket Url :{details}<br>\r\n           <p>Regards,</p><p>{company_name}</p>\r\n\r\n<p><br></p><br><br>', 'en', 'email', 1),
(65, 9, '{ticket_subject} #Ticket ID: {ticket_no}', '<p></p>\r\n<p>Hello {member_name},</p>{ticket_message}<br>\r\n            <br>\r\n            Ticket ID :{ticket_no}<br><div>\r\n            Subject :{ticket_subject}</div><div>Project Name : {project_name}<br></div>\r\n            Status :{ticket_status}<br>\r\n            Ticket Url :{details}<br>\r\n           <p>Regards,</p><p>{customer_name}</p>\r\n\r\n<p><br></p><br><br>', 'en', 'email', 1),
(73, 10, 'New Task Assigned #{task_name}.', '<p></p>\r\n\r\n<p>Hello {assignee_name},</p><p>\r\n\r\nA new support task has been assigned to you.&nbsp;</p>Task Name : {task_name}<br>\r\n            Start date&nbsp; &nbsp;: {start_date}<div>\r\n\r\nDue date&nbsp; &nbsp; : {due_date}&nbsp;</div><div>\r\n\r\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: {priority}\r\n\r\n&nbsp;\r\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : {ticket_status}<br>\r\n           <p>Regards,</p><p>{company_name}</p>\r\n\r\n<br>\r\n\r\n<br>\r\n                    </div>', 'en', 'email', 1),
(81, 11, 'New Support Ticket Reply #{ticket_no}.', '<p>Hi {customer_name},</p><p>A new support ticket reply from {customer_name} and Ticket no #{ticket_no} .</p><p>Assignee  Name#{assignee_name}.</p><p>Ticket Subject#{ticket_subject}</p><p>Priority#{ticket_priority}</p><p>Please Check the email as soon as possible.</p>\r\n            <p>{details}</p>\r\n           <p>Regards,</p><p>{company_name}<br></p><br><br>', 'en', 'email', 1),
(82, 15, 'Payment information for Purchase #{purchase_reference_no}', '<p>Hi {supplier_name},</p><p>We just want to confirm a few details about payment information:</p><p><b>Supplier Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}</p><p>{billing_country}<br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b></i></p><p><i>Purchase Order No : {purchase_reference_no}</i><br><i></i></p><p><br></p><p>Regards,</p><p>{company_name}<br></p><br><br><br><br><br><br>', 'en', 'email', 1),
(90, 16, 'Welcome to Support Techvillage! Please verify your account.', '<p>Welcome {profile_name}! </p><p>Please active your account : {link_url}.<p> Thank you and feel free to reply to this email if you have any questions!</p>', 'en', 'email', 1),
(126, 5, 'Subject', 'Body ar&nbsp;Quotation', 'ar', 'email', 2),
(127, 5, 'Subject', 'Body po&nbsp;Quotation', 'pt', 'email', 4),
(128, 5, 'Subject', 'Body ch&nbsp;Quotation', 'ch', 'email', 10),
(129, 5, 'Subject', 'Body ch&nbsp;Quotation', 'JP', 'email', 11),
(130, 5, 'Subject', 'Body ch&nbsp;Quotation', 'fr', 'email', 12),
(131, 5, 'Subject', 'Body ch&nbsp;Quotation', 'tr', 'email', 14),
(132, 5, 'Subject', 'Body ch&nbsp;Quotation', 'rs', 'email', 15),
(134, 5, 'Subject', 'Body jp&nbsp;Quotation', 'hn', 'email', 17),
(135, 6, 'Subject', 'Body ar purchase', 'ar', 'email', 2),
(136, 6, 'Subject', 'Body po&nbsp;\r\n\r\npurchase', 'pt', 'email', 4),
(137, 6, 'Subject', 'Body ch&nbsp;\r\n\r\npurchase', 'ch', 'email', 10),
(138, 6, 'Subject', 'Body jp&nbsp;\r\n\r\npurchase', 'JP', 'email', 11),
(139, 6, 'Subject', 'Body fr&nbsp;\r\n\r\npurchase', 'fr', 'email', 12),
(140, 6, 'Subject', 'Body tu&nbsp;\r\n\r\npurchase', 'tr', 'email', 14),
(141, 6, 'Subject', 'Body rus&nbsp;\r\n\r\npurchase', 'rs', 'email', 15),
(143, 6, 'Subject', 'Body hn&nbsp;\r\n\r\npurchase', 'hn', 'email', 17),
(154, 7, 'Subject', 'Body', 'ar', 'email', 2),
(155, 7, 'Subject', 'Body', 'pt', 'email', 4),
(156, 7, 'Subject', 'Body ch Assignee', 'ch', 'email', 10),
(157, 7, 'Subject', 'Body jp', 'JP', 'email', 11),
(158, 7, 'Subject', 'Body', 'fr', 'email', 12),
(159, 7, 'Subject', 'Body', 'tr', 'email', 14),
(160, 7, 'Subject', 'Body', 'rs', 'email', 15),
(162, 7, 'Subject', 'Body', 'hn', 'email', 17),
(164, 5, 'demo subject', 'Hi {customer_name},\r\nThank you for your order. Here\'s a brief overview of your Quotation #{order_reference_no} that was created on {order_date. The order total is {currency}{total_amount}.\r\nIf you have any question, Please feel free to reply to this email.\r\nstockpile.techvill@gmail.com\r\n\r\nBilling address\r\n\r\n{billing_street}\r\n{billing_city}\r\n{billing_state}\r\n{billing_zip_code}\r\n{billing_country}\r\n\r\nQuotation Summary\r\n\r\n{order_summery}\r\n\r\nRegards,\r\n{company_name}', 'en', 'sms', 1),
(165, 6, 'demo subject', 'demo body', 'en', 'sms', 1),
(166, 4, 'demo subject', 'Hi {customer_name},\r\n\r\nThank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no} is for Quotation #{order_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.\r\n\r\nIf you have any questions, please feel free to reply to this email.\r\n\r\nBilling address\r\n\r\n {billing_street}\r\n\r\n {billing_city}\r\n\r\n {billing_state}\r\n\r\n {billing_zip_code}\r\n\r\n {billing_country}\r\n\r\n\r\n\r\nQuotation summary\r\n\r\n{invoice_summery}\r\n\r\nRegards,\r\n\r\n{company_name}', 'en', 'sms', 1),
(167, 1, 'demo subject', 'demo body', 'en', 'sms', 1),
(168, 7, 'demo subject', 'demo body', 'en', 'sms', 1),
(169, 8, 'demo subject', 'demo body', 'en', 'sms', 1),
(170, 9, 'demo subject', 'demo body', 'en', 'sms', 1),
(175, 5, 'SMS Title', 'Text Message ar quotation', 'ar', 'sms', 2),
(176, 5, 'SMS Title', 'Text Message po quotation', 'pt', 'sms', 4),
(177, 5, 'SMS Title', 'Text Message jp quotation', 'JP', 'sms', 11),
(178, 5, 'SMS Title', 'Text Message fr quotation', 'fr', 'sms', 12),
(179, 5, 'SMS Title', 'Text Message tu quotation', 'tr', 'sms', 14),
(180, 5, 'SMS Title', 'Text Message rus quotation', 'rs', 'sms', 15),
(182, 5, 'SMS Title', 'Text Message hn quotation', 'hn', 'sms', 17),
(185, 6, 'SMS Title', 'Text Message ar purchase', 'ar', 'sms', 2),
(186, 6, 'SMS Title', 'Text Message po purchase', 'pt', 'sms', 4),
(187, 6, 'SMS Title', 'Text Message jp purchase', 'JP', 'sms', 11),
(188, 6, 'SMS Title', 'Text Message fr purchase', 'fr', 'sms', 12),
(189, 6, 'SMS Title', 'Text Message tu purchase', 'tr', 'sms', 14),
(190, 6, 'SMS Title', 'Text Message rus purchase', 'rs', 'sms', 15),
(192, 6, 'SMS Title', 'Text Message hn purchase', 'hn', 'sms', 17),
(195, 4, 'SMS Title', 'Text Message', 'ar', 'sms', 2),
(196, 4, 'SMS Title', 'Text Message', 'pt', 'sms', 4),
(197, 4, 'SMS Title', 'Text Message', 'JP', 'sms', 11),
(198, 4, 'SMS Title', 'Text Message', 'fr', 'sms', 12),
(199, 4, 'SMS Title', 'Text Message', 'tr', 'sms', 14),
(200, 4, 'SMS Title', 'Text Message', 'rs', 'sms', 15),
(202, 4, 'SMS Title', 'Text Message', 'hn', 'sms', 17),
(207, 10, 'Subject', 'Body', 'ar', 'email', 2),
(208, 10, 'Subject', 'Body', 'pt', 'email', 4),
(209, 10, 'Subject', 'Body', 'JP', 'email', 11),
(210, 10, 'Subject', 'Body', 'fr', 'email', 12),
(211, 10, 'Subject', 'Body', 'tr', 'email', 14),
(212, 10, 'Subject', 'Body', 'rs', 'email', 15),
(214, 10, 'Subject', 'Body', 'hn', 'email', 17),
(217, 13, 'You have assign a project', '<p></p>\r\n<p>Hello {assignee},</p><p>\r\n\r\nA new project has been assigned to you.\r\n\r\n<br></p>\r\n            <br>\r\n            Project Name :{project_name}<br>Customer Name:{customer_name}<br><div>Start Date: {start_date}</div><div>Status: {status}</div><div><br></div><div><b>Project Details</b></div><div>{details}<br></div>\r\n           <p>Regards,</p><p>{company_name}</p>\r\n\r\n<br><br>\r\n                    <br>', 'en', 'email', 1),
(218, 13, 'Subject', 'Body', 'ar', 'email', 2),
(219, 13, 'Subject', 'Body', 'pt', 'email', 4),
(220, 13, 'Subject', 'Body', 'JP', 'email', 11),
(221, 13, 'Subject', 'Body', 'fr', 'email', 12),
(222, 13, 'Subject', 'Body', 'tr', 'email', 14),
(223, 13, 'Subject', 'Body', 'rs', 'email', 15),
(225, 13, 'Subject', 'Body', 'hn', 'email', 17),
(228, 12, 'A new project has been created.', '<p></p>\r\n<p>Hello {customer_name},</p><p>\r\n\r\nA new project has been created.\r\n\r\n<br></p>\r\n            <br>\r\n            Project Name :{project_name}<br><div>Start Date: {start_date}</div><div>Status: {status}</div><div><br></div><div><b>Project Details</b></div><div>{details}<br></div>\r\n           <p>Regards,</p><p>{company_name}</p>\r\n\r\n<br><br>\r\n                    <br>', 'en', 'email', 1),
(229, 12, 'Subject', 'Body', 'ar', 'email', 2),
(230, 12, 'Subject', 'Body', 'pt', 'email', 4),
(231, 12, 'Subject', 'Body', 'JP', 'email', 11),
(232, 12, 'Subject', 'Body', 'fr', 'email', 12),
(233, 12, 'Subject', 'Body', 'tr', 'email', 14),
(234, 12, 'Subject', 'Body', 'rs', 'email', 15),
(236, 12, 'Subject', 'Body', 'hn', 'email', 17);

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

DROP TABLE IF EXISTS `exchange_rates`;
CREATE TABLE IF NOT EXISTS `exchange_rates` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `rate` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `currency_id`, `rate`, `date`) VALUES
(1, 2, 1, '2018-12-09'),
(2, 2, 1, '2018-12-09'),
(3, 2, 1, '2018-12-09'),
(4, 2, 1, '2018-12-11'),
(5, 2, 1, '2018-12-11'),
(6, 2, 1, '2018-12-11'),
(7, 2, 1, '2018-12-11'),
(8, 2, 1, '2018-12-17'),
(9, 1, 1, '2018-12-20'),
(10, 2, 1, '2018-12-23'),
(11, 2, 1, '2018-12-23'),
(12, 2, 1, '2018-12-23'),
(13, 2, 1, '2018-12-23'),
(14, 2, 1, '2018-12-23'),
(15, 2, 1, '2018-12-23'),
(16, 2, 1, '2018-12-23'),
(17, 2, 1, '2018-12-23'),
(18, 2, 1, '2018-12-23'),
(19, 2, 1, '2018-12-23'),
(20, 2, 1, '2018-12-24'),
(21, 2, 1, '2018-12-24'),
(22, 2, 1, '2018-12-24'),
(23, 2, 1, '2018-12-26'),
(24, 2, 1, '2018-12-27'),
(25, 2, 1, '2018-12-27'),
(26, 2, 1, '2018-12-27'),
(27, 2, 1, '2018-12-27'),
(28, 2, 1, '2018-12-27'),
(29, 1, 80, '2018-12-27'),
(30, 1, 80, '2018-12-27'),
(31, 1, 80, '2018-12-27'),
(32, 1, 80, '2018-12-27'),
(33, 2, 1, '2018-12-27'),
(34, 2, 1, '2018-12-27'),
(35, 2, 1, '2018-12-27'),
(36, 2, 1, '2018-12-27'),
(37, 2, 1, '2018-12-27'),
(38, 2, 1, '2019-01-02'),
(39, 2, 1, '2019-01-03'),
(40, 2, 1, '2019-01-03'),
(41, 2, 1, '2019-01-03'),
(42, 2, 1, '2019-01-07'),
(43, 2, 1, '2019-01-09'),
(44, 2, 1, '2019-01-09'),
(45, 1, 80, '2019-01-15'),
(46, 2, 0.0125, '2019-01-21'),
(47, 1, 1, '2019-01-23'),
(48, 2, 0.12, '2019-01-23'),
(49, 4, 0.12, '2019-01-24'),
(50, 2, 1, '2019-01-29'),
(51, 2, 1, '2019-01-29'),
(52, 4, 50, '2019-01-29'),
(53, 4, 98, '2019-01-29'),
(54, 4, 40, '2019-01-29'),
(55, 1, 1, '2019-01-30'),
(56, 2, 1, '2019-01-30'),
(57, 4, 40, '2019-01-30'),
(58, 4, 50, '2019-01-30'),
(59, 4, 70, '2019-01-30'),
(60, 2, 1, '2019-01-31'),
(61, 4, 50, '2019-01-31'),
(62, 4, 50, '2019-01-31'),
(63, 4, 80, '2019-01-31'),
(64, 2, 1, '2019-01-31'),
(65, 2, 1, '2019-01-31'),
(66, 4, 50, '2019-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_transaction_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `reference` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `exchange_rate` double NOT NULL,
  `amount` double NOT NULL,
  `note` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `bank_transaction_id`, `user_id`, `reference_id`, `type`, `reference`, `currency_id`, `payment_method_id`, `exchange_rate`, `amount`, `note`, `transaction_date`, `created_at`) VALUES
(1, 37, 1, 52, 12, '001/2018', 1, 1, 80, 2500, 'Admin Expense', '2018-12-27', '2018-12-27 05:57:05'),
(2, 38, 15, 53, 12, '002/2018', 1, 1, 80, 3000, 'Rafiq Expense', '2018-12-27', '2018-12-27 05:57:30'),
(3, 115, 1, 118, 12, '003/2019', 4, 2, 50, 32.51, 'This is a demo expenses', '2019-02-03', '2019-02-03 13:07:15'),
(4, 116, 1, 119, 12, '004/2019', 4, 2, 50, 39.85, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a', '2019-02-03', '2019-02-03 13:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `ticket_reply_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `original_file_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visible_to_customer` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 = yes , 0 = no',
  `external` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_link` mediumtext COLLATE utf8_unicode_ci,
  `thumnail_link` mediumtext COLLATE utf8_unicode_ci,
  `task_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `user_id`, `customer_id`, `project_id`, `ticket_id`, `ticket_reply_id`, `file_name`, `original_file_name`, `file_type`, `last_activity`, `created_at`, `visible_to_customer`, `external`, `external_link`, `thumnail_link`, `task_id`, `invoice_id`, `payment_id`) VALUES
(12, 1, 3, 0, 0, 0, 'desert_1545547094.jpg', 'Desert.jpg', 'jpg', NULL, '2018-12-23 06:38:14', 0, NULL, NULL, NULL, NULL, 27, NULL),
(13, 1, 3, 0, 0, 0, 'jellyfish_1545547094.jpg', 'Jellyfish.jpg', 'jpg', NULL, '2018-12-23 06:38:14', 0, NULL, NULL, NULL, NULL, 27, NULL),
(16, 1, 3, 0, 0, 0, 'chrysanthemum_1545556414.jpg', 'Chrysanthemum.jpg', 'jpg', NULL, '2018-12-23 09:13:34', 0, NULL, NULL, NULL, NULL, 23, NULL),
(18, 1, 3, 0, 0, 0, 'invoice_1545031968_1545559619.pdf', 'invoice_1545031968.pdf', 'pdf', NULL, '2018-12-23 10:06:59', 0, NULL, NULL, NULL, NULL, 6, NULL),
(19, 1, 2, 0, 0, 0, 'jellyfish_1545559650.jpg', 'Jellyfish.jpg', 'jpg', NULL, '2018-12-23 10:07:30', 0, NULL, NULL, NULL, NULL, 8, NULL),
(20, 1, 2, 0, 0, 0, 'invoice_1545032235_1545560063.pdf', 'invoice_1545032235.pdf', 'pdf', NULL, '2018-12-23 10:14:23', 0, NULL, NULL, NULL, NULL, 8, 11),
(21, 1, 2, 0, 0, 0, 'desert_1545560120.jpg', 'Desert.jpg', 'jpg', NULL, '2018-12-23 10:15:20', 0, NULL, NULL, NULL, NULL, 8, 12),
(22, 1, 0, 0, 2, 0, 'invoice_1545031818_1545563408.pdf', 'invoice_1545031818.pdf', 'pdf', NULL, '2018-12-23 11:10:08', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 1, 1, 0, 0, 0, 'bank_1545897105.jpg', 'bank.jpg', 'jpg', NULL, '2018-12-27 07:51:45', 0, NULL, NULL, NULL, NULL, 60, 31),
(24, 1, 1, 0, 0, 0, 'quotation_1545895778_1545897264.pdf', 'quotation_1545895778.pdf', 'pdf', NULL, '2018-12-27 07:54:24', 0, NULL, NULL, NULL, NULL, 60, 32),
(37, 1, 1, 0, 0, 0, '5a54a4e82aa50_1546433231.png', '5a54a4e82aa50.png', 'png', NULL, '2019-01-02 12:47:11', 0, NULL, NULL, NULL, NULL, 136, NULL),
(45, 1, 1, 0, 0, 0, 'screenshot_13_1546766917.png', 'Screenshot_13.png', 'png', NULL, '2019-01-06 09:28:37', 0, NULL, NULL, NULL, NULL, 150, NULL),
(46, 1, 5, 0, 0, 0, 'lighthouse_1546773823.jpg', 'Lighthouse.jpg', 'jpg', NULL, '2019-01-06 11:23:43', 0, NULL, NULL, NULL, NULL, 160, 46),
(47, 1, 5, 0, 0, 0, 'hydrangeas_1546777866.jpg', 'Hydrangeas.jpg', 'jpg', NULL, '2019-01-06 12:31:06', 0, NULL, NULL, NULL, NULL, 162, 47),
(48, 1, 5, 0, 0, 0, 'koala_1546777933.jpg', 'Koala.jpg', 'jpg', NULL, '2019-01-06 12:32:13', 0, NULL, NULL, NULL, NULL, 162, 48),
(49, 1, 5, 0, 0, 0, 'desert_1546779530.jpg', 'Desert.jpg', 'jpg', NULL, '2019-01-06 12:58:50', 0, NULL, NULL, NULL, NULL, 166, 49),
(50, 1, 5, 0, 0, 0, 'desert_1546838110.jpg', 'Desert.jpg', 'jpg', NULL, '2019-01-07 05:15:10', 0, NULL, NULL, NULL, NULL, 168, 50),
(52, 1, 0, 0, 9, 0, '5a54a4e82aa50_1546926587.png', '5a54a4e82aa50.png', 'png', NULL, '2019-01-08 05:49:47', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 1, 0, 0, 9, 0, '5a54a4e82aa50_1546926587.png', '5a54a4e82aa50.png', 'png', NULL, '2019-01-08 05:49:47', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 1, 0, 0, 9, 0, 'bank_1546926587.jpg', 'bank.jpg', 'jpg', NULL, '2019-01-08 05:49:47', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 1, 0, 0, 9, 0, 'download  (2)_1546926587.jpg', 'download  (2).jpg', 'jpg', NULL, '2019-01-08 05:49:47', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 0, 2, 0, 14, 32, 'refund_1546936589.docx', 'Refund.docx', 'docx', NULL, '2019-01-08 08:36:29', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 0, 2, 0, 14, 32, 'know your customer_1546936589.docx', 'Know your customer.docx', 'docx', NULL, '2019-01-08 08:36:29', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 1, 0, 0, 16, 0, 'appointment_letter_1547026885.docx', 'Appointment_letter.docx', 'docx', NULL, '2019-01-09 09:41:25', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 1, 0, 0, 16, 0, 'bank11_1547026885.jpg', 'bank11.jpg', 'jpg', NULL, '2019-01-09 09:41:25', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 1, 2, 0, 0, 0, 'know your customer_1547027882.docx', 'Know your customer.docx', 'docx', NULL, '2019-01-09 09:58:02', 0, NULL, NULL, NULL, NULL, 170, 51),
(77, 1, 2, 0, 0, 0, 'bank11_1547028022.jpg', 'bank11.jpg', 'jpg', NULL, '2019-01-09 10:00:22', 0, NULL, NULL, NULL, NULL, 170, NULL),
(78, 1, 2, 0, 0, 0, 'bank11_1547028080.jpg', 'bank11.jpg', 'jpg', NULL, '2019-01-09 10:01:20', 0, NULL, NULL, NULL, NULL, 170, 52),
(79, 1, 0, 17, 0, 0, '1547030008.jpg', 'bank11.jpg', 'jpg', NULL, '2019-01-09 10:33:28', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 18, 0, 16, 0, 0, '1547032061.docx', 'Know your customer.docx', 'docx', NULL, '2019-01-09 11:07:41', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 1, 0, 0, 0, 0, 'appointment_letter_1547104600.docx', 'Appointment_letter.docx', 'docx', NULL, '2019-01-10 07:16:40', 0, NULL, NULL, NULL, 48, NULL, NULL),
(85, 1, 0, 0, 0, 0, 'bank11 (1)_1547104600.jpg', 'bank11 (1).jpg', 'jpg', NULL, '2019-01-10 07:16:40', 0, NULL, NULL, NULL, 48, NULL, NULL),
(86, 17, 0, 0, 17, 36, '5a54a4e82aa50_1547104927.png', '5a54a4e82aa50.png', 'png', NULL, '2019-01-10 07:22:06', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 17, 0, 0, 17, 36, 'musicial_1547104927.jpg', 'Musicial.jpg', 'jpg', NULL, '2019-01-10 07:22:07', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 1, 0, 0, 0, 0, 'bank11_1547107019.jpg', 'bank11.jpg', 'jpg', NULL, '2019-01-10 07:56:59', 0, NULL, NULL, NULL, 49, NULL, NULL),
(89, 1, 0, 0, 0, 0, 'know your customer_1547107019.docx', 'Know your customer.docx', 'docx', NULL, '2019-01-10 07:56:59', 0, NULL, NULL, NULL, 49, NULL, NULL),
(90, 1, 0, 0, 0, 0, 'admitcard_qozsaw_1547625914.pdf', 'AdmitCard_QOZSAW.pdf', 'pdf', NULL, '2019-01-16 08:05:14', 0, NULL, NULL, NULL, 49, NULL, NULL),
(91, 0, 5, 0, 0, 0, 'screenshot_3_1547898750.png', 'Screenshot_3.png', 'png', NULL, '2019-01-19 11:52:30', 0, NULL, NULL, NULL, 56, NULL, NULL),
(92, 0, 5, 0, 0, 0, 'screenshot_3_1547898847.png', 'Screenshot_3.png', 'png', NULL, '2019-01-19 11:54:07', 0, NULL, NULL, NULL, 56, NULL, NULL),
(93, 0, 5, 0, 0, 0, 'screenshot_1_1547899254.png', 'Screenshot_1.png', 'png', NULL, '2019-01-19 12:00:54', 0, NULL, NULL, NULL, 56, NULL, NULL),
(94, 0, 5, 0, 0, 0, 'screenshot_3_1547899313.png', 'Screenshot_3.png', 'png', NULL, '2019-01-19 12:01:53', 0, NULL, NULL, NULL, 56, NULL, NULL),
(95, 0, 5, 0, 0, 0, 'screenshot_2_1547899327.png', 'Screenshot_2.png', 'png', NULL, '2019-01-19 12:02:07', 0, NULL, NULL, NULL, 56, NULL, NULL),
(96, 0, 5, 0, 0, 0, 'screenshot_1_1547900565.png', 'Screenshot_1.png', 'png', NULL, '2019-01-19 12:22:45', 0, NULL, NULL, NULL, 56, NULL, NULL),
(100, 0, 5, 0, 0, 0, 'screenshot_1_1547900682.png', 'Screenshot_1.png', 'png', NULL, '2019-01-19 12:24:42', 0, NULL, NULL, NULL, 49, NULL, NULL),
(102, 0, 5, 0, 0, 0, 'logo_1547901510.png', 'logo.png', 'png', NULL, '2019-01-19 12:38:30', 0, NULL, NULL, NULL, 49, NULL, NULL),
(103, 0, 5, 0, 0, 0, 'screenshot_3_1547966710.png', 'Screenshot_3.png', 'png', NULL, '2019-01-20 06:45:10', 0, NULL, NULL, NULL, 49, NULL, NULL),
(116, 0, 5, 0, 0, 0, 'screenshot_3_1547970151.png', 'Screenshot_3.png', 'png', NULL, '2019-01-20 07:42:31', 0, NULL, NULL, NULL, 49, NULL, NULL),
(117, 0, 5, 0, 0, 0, 'screenshot_2_1547970237.png', 'Screenshot_2.png', 'png', NULL, '2019-01-20 07:43:57', 0, NULL, NULL, NULL, 57, NULL, NULL),
(118, 0, 5, 0, 0, 0, 'screenshot_2_1547974910.png', 'Screenshot_2.png', 'png', NULL, '2019-01-20 09:01:50', 0, NULL, NULL, NULL, 58, NULL, NULL),
(119, 1, 0, 0, 0, 0, '505601_6177262_1166671_thumbnail_1548048193.jpg', '505601_6177262_1166671_thumbnail.jpg', 'jpg', NULL, '2019-01-21 05:23:13', 0, NULL, NULL, NULL, 62, NULL, NULL),
(120, 1, 0, 0, 0, 0, 'appointment_letter (1)_1548048193.docx', 'Appointment_letter (1).docx', 'docx', NULL, '2019-01-21 05:23:13', 0, NULL, NULL, NULL, 62, NULL, NULL),
(121, 1, 0, 0, 0, 0, 'beyond_1548048193.jpg', 'beyond.jpg', 'jpg', NULL, '2019-01-21 05:23:13', 0, NULL, NULL, NULL, 62, NULL, NULL),
(122, 1, 0, 0, 0, 0, '505601_6177262_1166671_thumbnail_1548062425.jpg', '505601_6177262_1166671_thumbnail.jpg', 'jpg', NULL, '2019-01-21 09:20:25', 0, NULL, NULL, NULL, 63, NULL, NULL),
(123, 1, 0, 0, 0, 0, 'beyond_1548062425.jpg', 'beyond.jpg', 'jpg', NULL, '2019-01-21 09:20:25', 0, NULL, NULL, NULL, 63, NULL, NULL),
(124, 1, 0, 0, 0, 0, 'appointment_letter (1)_1548064099.docx', 'Appointment_letter (1).docx', 'docx', NULL, '2019-01-21 09:48:19', 0, NULL, NULL, NULL, 63, NULL, NULL),
(125, 1, 0, 0, 0, 0, 'beyond-logo_1548064107.jpg', 'Beyond-logo.jpg', 'jpg', NULL, '2019-01-21 09:48:27', 0, NULL, NULL, NULL, 63, NULL, NULL),
(126, 1, 0, 0, 0, 0, 'beyond_1548064114.png', 'beyond.png', 'png', NULL, '2019-01-21 09:48:34', 0, NULL, NULL, NULL, 63, NULL, NULL),
(127, 0, 5, 0, 0, 0, 'anzdoc_logo_1548064977.png', 'anzdoc_logo.png', 'png', NULL, '2019-01-21 10:02:57', 0, NULL, NULL, NULL, 63, NULL, NULL),
(128, 0, 5, 0, 0, 0, 'bootstrap_1548064986.png', 'bootstrap.png', 'png', NULL, '2019-01-21 10:03:06', 0, NULL, NULL, NULL, 63, NULL, NULL),
(129, 0, 5, 0, 0, 0, 'appointment_letter (1)_1548065073.docx', 'Appointment_letter (1).docx', 'docx', NULL, '2019-01-21 10:04:33', 0, NULL, NULL, NULL, 63, NULL, NULL),
(130, 18, 0, 0, 0, 0, 'screenshot_2_1548067725.png', 'Screenshot_2.png', 'png', NULL, '2019-01-21 10:48:45', 0, NULL, NULL, NULL, 45, NULL, NULL),
(131, 18, 0, 0, 0, 0, 'fin_1548071011.png', 'fin.png', 'png', NULL, '2019-01-21 11:43:31', 0, NULL, NULL, NULL, 63, NULL, NULL),
(132, 18, 0, 0, 0, 0, 'invoice_1547468767_1548071070.pdf', 'invoice_1547468767.pdf', 'pdf', NULL, '2019-01-21 11:44:30', 0, NULL, NULL, NULL, 63, NULL, NULL),
(133, 18, 0, 0, 0, 0, 'book1_1548071096.xlsx', 'Book1.xlsx', 'xlsx', NULL, '2019-01-21 11:44:56', 0, NULL, NULL, NULL, 63, NULL, NULL),
(134, 18, 0, 0, 0, 0, 'demo_wait_1548071108.gif', 'demo_wait.gif', 'gif', NULL, '2019-01-21 11:45:08', 0, NULL, NULL, NULL, 63, NULL, NULL),
(135, 0, 5, 0, 0, 0, 'appointment_letter (1)_1548226337.docx', 'Appointment_letter (1).docx', 'docx', NULL, '2019-01-23 06:52:17', 0, NULL, NULL, NULL, 70, NULL, NULL),
(136, 0, 5, 0, 0, 0, '505601_6177262_1166671_thumbnail_1548226337.jpg', '505601_6177262_1166671_thumbnail.jpg', 'jpg', NULL, '2019-01-23 06:52:17', 0, NULL, NULL, NULL, 70, NULL, NULL),
(137, 0, 5, 0, 0, 0, 'beyond-logo_1548226338.jpg', 'Beyond-logo.jpg', 'jpg', NULL, '2019-01-23 06:52:18', 0, NULL, NULL, NULL, 70, NULL, NULL),
(138, 0, 5, 0, 0, 0, 'beyond_1548226919.png', 'beyond.png', 'png', NULL, '2019-01-23 07:01:59', 0, NULL, NULL, NULL, 71, NULL, NULL),
(139, 1, 0, 0, 0, 0, 'screenshot_3_1548234352.png', 'Screenshot_3.png', 'png', NULL, '2019-01-23 09:05:52', 0, NULL, NULL, NULL, 46, NULL, NULL),
(140, 0, 5, 0, 0, 0, 'screenshot_3_1548237154.png', 'Screenshot_3.png', 'png', NULL, '2019-01-23 09:52:34', 0, NULL, NULL, NULL, 61, NULL, NULL),
(141, 0, 5, 0, 0, 0, 'screenshot_3_1548237201.png', 'Screenshot_3.png', 'png', NULL, '2019-01-23 09:53:21', 0, NULL, NULL, NULL, 61, NULL, NULL),
(142, 1, 5, 0, 0, 0, '505601_6177262_1166671_thumbnail_1548237343.jpg', '505601_6177262_1166671_thumbnail.jpg', 'jpg', NULL, '2019-01-23 09:55:43', 0, NULL, NULL, NULL, NULL, 206, NULL),
(143, 1, 5, 0, 0, 0, '1_1548237343.jpg', '1.jpg', 'jpg', NULL, '2019-01-23 09:55:43', 0, NULL, NULL, NULL, NULL, 206, NULL),
(144, 1, 5, 0, 0, 0, 'anzdoc_logo_1548237343.png', 'anzdoc_logo.png', 'png', NULL, '2019-01-23 09:55:43', 0, NULL, NULL, NULL, NULL, 206, NULL),
(145, 1, 5, 0, 0, 0, 'cc_pay_new_1548237597.html', 'cc_pay_new.html', 'html', NULL, '2019-01-23 09:59:57', 0, NULL, NULL, NULL, NULL, 206, 55),
(146, 1, 0, 0, 0, 0, 'screenshot_2_1548238711.png', 'Screenshot_2.png', 'png', NULL, '2019-01-23 10:18:31', 0, NULL, NULL, NULL, 51, NULL, NULL),
(147, 0, 5, 0, 0, 0, 'screenshot_2_1548238906.png', 'Screenshot_2.png', 'png', NULL, '2019-01-23 10:21:46', 0, NULL, NULL, NULL, 58, NULL, NULL),
(148, 1, 0, 0, 0, 0, 'screenshot_1_1548239039.png', 'Screenshot_1.png', 'png', NULL, '2019-01-23 10:23:59', 0, NULL, NULL, NULL, 58, NULL, NULL),
(149, 1, 0, 0, 0, 0, 'screenshot_3_1548239280.png', 'Screenshot_3.png', 'png', NULL, '2019-01-23 10:28:00', 0, NULL, NULL, NULL, 49, NULL, NULL),
(150, 1, 0, 0, 0, 0, 'screenshot_2_1548239408.png', 'Screenshot_2.png', 'png', NULL, '2019-01-23 10:30:08', 0, NULL, NULL, NULL, 52, NULL, NULL),
(152, 1, 0, 0, 0, 0, 'screenshot_3_1548239545.png', 'Screenshot_3.png', 'png', NULL, '2019-01-23 10:32:25', 0, NULL, NULL, NULL, 58, NULL, NULL),
(153, 1, 0, 0, 0, 0, 'screenshot_2_1548239817.png', 'Screenshot_2.png', 'png', NULL, '2019-01-23 10:36:57', 0, NULL, NULL, NULL, 55, NULL, NULL),
(158, 1, 0, 0, 0, 0, 'screenshot_2_1548240759.png', 'Screenshot_2.png', 'png', NULL, '2019-01-23 10:52:39', 0, NULL, NULL, NULL, 46, NULL, NULL),
(159, 1, 5, 0, 0, 0, 'screenshot_47_1548332500.png', 'Screenshot_47.png', 'png', NULL, '2019-01-24 12:21:40', 0, NULL, NULL, NULL, NULL, 210, 58),
(160, 1, 5, 0, 0, 0, 'screenshot_48_1548334655.png', 'Screenshot_48.png', 'png', NULL, '2019-01-24 12:57:35', 0, NULL, NULL, NULL, NULL, 210, 59),
(161, 1, 5, 0, 0, 0, '1_9yjw0zvy1pik41eterjzgq_1548335334.jpeg', '1_9YJw0zVY1pIK41eTErjzgQ.jpeg', 'jpeg', NULL, '2019-01-24 13:08:54', 0, NULL, NULL, NULL, NULL, 210, 60),
(162, 1, 5, 0, 0, 0, '1_9yjw0zvy1pik41eterjzgq_1548744601.jpeg', '1_9YJw0zVY1pIK41eTErjzgQ.jpeg', 'jpeg', NULL, '2019-01-29 06:50:01', 0, NULL, NULL, NULL, NULL, 218, NULL),
(163, 1, 5, 0, 0, 0, '1_1548744868.jpg', '1.jpg', 'jpg', NULL, '2019-01-29 06:54:28', 0, NULL, NULL, NULL, NULL, 218, 61),
(164, 1, 5, 0, 0, 0, '1_1548746761.jpg', '1.jpg', 'jpg', NULL, '2019-01-29 07:26:01', 0, NULL, NULL, NULL, NULL, 218, 62),
(165, 1, 5, 0, 0, 0, '1_1548848233.jpg', '1.jpg', 'jpg', NULL, '2019-01-30 11:37:13', 0, NULL, NULL, NULL, NULL, 224, 73),
(166, 1, 0, 0, 0, 0, 'screenshot_2_1548848268.png', 'Screenshot_2.png', 'png', NULL, '2019-01-30 11:37:48', 0, NULL, NULL, NULL, 49, NULL, NULL),
(167, 1, 5, 0, 0, 0, '1_1548848759.jpg', '1.jpg', 'jpg', NULL, '2019-01-30 11:45:59', 0, NULL, NULL, NULL, NULL, 230, 74),
(168, 1, 5, 0, 0, 0, '1_1548849302.jpg', '1.jpg', 'jpg', NULL, '2019-01-30 11:55:02', 0, NULL, NULL, NULL, NULL, 230, 75),
(169, 1, 5, 0, 0, 0, '1_1547891739_1548913895.jpg', '1_1547891739.jpg', 'jpg', NULL, '2019-01-31 05:51:35', 0, NULL, NULL, NULL, NULL, 238, NULL),
(170, 1, 5, 0, 0, 0, '1_1547891739_1548913962.jpg', '1_1547891739.jpg', 'jpg', NULL, '2019-01-31 05:52:42', 0, NULL, NULL, NULL, NULL, 240, NULL),
(171, 1, 5, 0, 0, 0, '1_9yjw0zvy1pik41eterjzgq_1548917864.jpeg', '1_9YJw0zVY1pIK41eTErjzgQ.jpeg', 'jpeg', NULL, '2019-01-31 06:57:44', 0, NULL, NULL, NULL, NULL, 234, 76),
(172, 0, 5, 0, 0, 0, 'screenshot_1_1548918499.png', 'Screenshot_1.png', 'png', NULL, '2019-01-31 07:08:19', 0, NULL, NULL, NULL, 72, NULL, NULL),
(173, 0, 5, 0, 0, 0, 'screenshot_2_1548918508.png', 'Screenshot_2.png', 'png', NULL, '2019-01-31 07:08:28', 0, NULL, NULL, NULL, 72, NULL, NULL),
(174, 0, 5, 0, 0, 0, 'screenshot_3_1548918511.png', 'Screenshot_3.png', 'png', NULL, '2019-01-31 07:08:31', 0, NULL, NULL, NULL, 72, NULL, NULL),
(175, 1, 5, 0, 0, 0, '1_1547891739_1548918965.jpg', '1_1547891739.jpg', 'jpg', NULL, '2019-01-31 07:16:05', 0, NULL, NULL, NULL, NULL, 234, 77);

-- --------------------------------------------------------

--
-- Table structure for table `general_ledger_transactions`
--

DROP TABLE IF EXISTS `general_ledger_transactions`;
CREATE TABLE IF NOT EXISTS `general_ledger_transactions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `reference_type` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `gl_account_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchange_rate` double NOT NULL,
  `amount` double NOT NULL,
  `comment` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_reversed` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `transaction_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `general_ledger_transactions`
--

INSERT INTO `general_ledger_transactions` (`id`, `reference_id`, `reference_type`, `user_id`, `gl_account_id`, `currency_id`, `exchange_rate`, `amount`, `comment`, `is_reversed`, `transaction_date`, `created_at`) VALUES
(1, 18, 15, 1, 4, 1, 85, -425, 'Online Payment for INV-0007', 'no', '2018-12-17', '2018-12-17 04:55:28'),
(2, 18, 15, 1, 6, 1, 85, 425, 'Online Payment for INV-0007', 'no', '2018-12-17', '2018-12-17 04:55:28'),
(3, 19, 11, 1, 2, 2, 1, -500, '-', 'no', '2018-12-17', '2018-12-17 05:03:13'),
(4, 19, 11, 1, 2, 2, 1, 500, 'test deposit', 'no', '2018-12-17', '2018-12-17 05:03:13'),
(5, 20, 15, 1, 4, 1, 80, -640000, 'Payment for INV-0009', 'no', '2018-12-20', '2018-12-20 11:22:34'),
(6, 20, 15, 1, 6, 1, 80, 640000, 'Payment for INV-0009', 'no', '2018-12-20', '2018-12-20 11:22:34'),
(7, 21, 15, 1, 4, 1, 79, -1583555, 'Payment for INV-0006', 'no', '2018-12-23', '2018-12-23 09:17:01'),
(8, 21, 15, 1, 2, 2, 79, 1583555, 'Payment for INV-0006', 'no', '2018-12-23', '2018-12-23 09:17:01'),
(9, 22, 15, 1, 4, 2, 1, -8000, 'Payment for INV-0012', 'no', '2018-12-23', '2018-12-23 09:56:55'),
(10, 22, 15, 1, 2, 2, 1, 8000, 'Payment for INV-0012', 'no', '2018-12-23', '2018-12-23 09:56:55'),
(11, 23, 15, 1, 4, 1, 78, -36816, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:03:38'),
(12, 23, 15, 1, 2, 2, 78, 36816, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:03:38'),
(13, 24, 15, 1, 4, 1, 20, -400, 'Payment for INV-0008', 'no', '2018-12-23', '2018-12-23 10:05:05'),
(14, 24, 15, 1, 2, 2, 20, 400, 'Payment for INV-0008', 'no', '2018-12-23', '2018-12-23 10:05:05'),
(15, 25, 15, 1, 4, 2, 1, -8000, 'Payment for INV-0011', 'no', '2018-12-23', '2018-12-23 10:05:27'),
(16, 25, 15, 1, 2, 2, 1, 8000, 'Payment for INV-0011', 'no', '2018-12-23', '2018-12-23 10:05:27'),
(17, 26, 15, 1, 4, 2, 1, -28350, 'Payment for INV-0003', 'no', '2018-12-23', '2018-12-23 10:06:58'),
(18, 26, 15, 1, 2, 2, 1, 28350, 'Payment for INV-0003', 'no', '2018-12-23', '2018-12-23 10:06:58'),
(19, 27, 15, 1, 4, 1, 78, -36426, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:07:30'),
(20, 27, 15, 1, 2, 2, 78, 36426, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:07:30'),
(21, 28, 15, 1, 4, 1, 78, -36114, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:10:37'),
(22, 28, 15, 1, 2, 2, 78, 36114, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:10:37'),
(23, 29, 15, 1, 4, 1, 78, -35724, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:14:23'),
(24, 29, 15, 1, 2, 2, 78, 35724, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:14:23'),
(25, 30, 15, 1, 4, 1, 78, -35334, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:15:19'),
(26, 30, 15, 1, 2, 2, 78, 35334, 'Payment for INV-0004', 'no', '2018-12-23', '2018-12-23 10:15:19'),
(27, 31, 15, 1, 4, 1, 50, -5000, 'Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 06:48:19'),
(28, 31, 15, 1, 2, 2, 50, 5000, 'Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 06:48:19'),
(29, 32, 15, 1, 4, 1, 50, -4950, 'Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 06:50:38'),
(30, 32, 15, 1, 2, 2, 50, 4950, 'Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 06:50:38'),
(31, 33, 15, 1, 4, 1, 50, -490050, 'Online Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 07:33:10'),
(32, 33, 15, 1, 6, 1, 50, 490050, 'Online Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 07:33:10'),
(33, 34, 15, 1, 4, 1, 50, -0, 'Online Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 07:33:23'),
(34, 34, 15, 1, 6, 1, 50, 0, 'Online Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 07:33:23'),
(35, 35, 15, 1, 4, 1, 50, -0, 'Online Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 07:34:18'),
(36, 35, 15, 1, 6, 1, 50, 0, 'Online Payment for INV-0014', 'no', '2018-12-24', '2018-12-24 07:34:18'),
(37, 36, 15, 1, 4, 2, 1, -8000, 'Payment for INV-0013', 'no', '2018-12-24', '2018-12-24 07:42:15'),
(38, 36, 15, 1, 2, 2, 1, 8000, 'Payment for INV-0013', 'no', '2018-12-24', '2018-12-24 07:42:15'),
(39, 37, 15, 1, 4, 1, 50, -300000, 'Online Payment for INV-0015', 'no', '2018-12-24', '2018-12-24 08:07:36'),
(40, 37, 15, 1, 6, 1, 50, 300000, 'Online Payment for INV-0015', 'no', '2018-12-24', '2018-12-24 08:07:36'),
(41, 38, 15, 1, 4, 1, 50, -50000, 'Online Payment for INV-0016', 'no', '0000-00-00', '2018-12-24 08:09:06'),
(42, 38, 15, 1, 6, 1, 50, 50000, 'Online Payment for INV-0016', 'no', '0000-00-00', '2018-12-24 08:09:06'),
(43, 39, 15, 1, 4, 1, 50, -45000, 'Online Payment for INV-0017', 'no', '2018-12-24', '2018-12-24 08:12:28'),
(44, 39, 15, 1, 6, 1, 50, 45000, 'Online Payment for INV-0017', 'no', '2018-12-24', '2018-12-24 08:12:28'),
(45, 40, 15, 1, 4, 1, 50, -40000, 'Online Payment for INV-0018', 'no', '2018-12-24', '2018-12-24 08:14:54'),
(46, 40, 15, 1, 6, 1, 50, 40000, 'Online Payment for INV-0018', 'no', '2018-12-24', '2018-12-24 08:14:54'),
(47, 41, 15, 1, 4, 1, 50, -0, 'Online Payment for INV-0018', 'no', '2018-12-24', '2018-12-24 08:15:05'),
(48, 41, 15, 1, 6, 1, 50, 0, 'Online Payment for INV-0018', 'no', '2018-12-24', '2018-12-24 08:15:05'),
(49, 42, 15, 1, 4, 1, 50, -35000, 'Online Payment for INV-0019', 'no', '2018-12-24', '2018-12-24 08:16:44'),
(50, 42, 15, 1, 6, 1, 50, 35000, 'Online Payment for INV-0019', 'no', '2018-12-24', '2018-12-24 08:16:44'),
(51, 43, 15, 1, 4, 1, 50, -30000, 'Online Payment for INV-0020', 'no', '2018-12-24', '2018-12-24 08:18:43'),
(52, 43, 15, 1, 6, 1, 50, 30000, 'Online Payment for INV-0020', 'no', '2018-12-24', '2018-12-24 08:18:43'),
(53, 44, 15, 1, 4, 1, 50, -0, 'Online Payment for INV-0020', 'no', '2018-12-24', '2018-12-24 08:18:58'),
(54, 44, 15, 1, 6, 1, 50, 0, 'Online Payment for INV-0020', 'no', '2018-12-24', '2018-12-24 08:18:58'),
(55, 45, 15, 1, 4, 1, 50, -0, 'Online Payment for INV-0020', 'no', '2018-12-24', '2018-12-24 08:20:11'),
(56, 45, 15, 1, 6, 1, 50, 0, 'Online Payment for INV-0020', 'no', '2018-12-24', '2018-12-24 08:20:11'),
(57, 46, 15, 15, 4, 2, 1, -8000, 'Payment for INV-0024', 'no', '2018-12-26', '2018-12-26 13:04:12'),
(58, 46, 15, 15, 2, 2, 1, 8000, 'Payment for INV-0024', 'no', '2018-12-26', '2018-12-26 13:04:12'),
(59, 47, 15, 15, 4, 1, 50, -250000, 'Payment for INV-0025', 'no', '2018-12-27', '2018-12-27 05:04:20'),
(60, 47, 15, 15, 2, 2, 50, 250000, 'Payment for INV-0025', 'no', '2018-12-27', '2018-12-27 05:04:20'),
(61, 48, 15, 15, 4, 1, 78, -35022, 'Payment for INV-0004', 'no', '2018-12-27', '2018-12-27 05:06:20'),
(62, 48, 15, 15, 2, 2, 78, 35022, 'Payment for INV-0004', 'no', '2018-12-27', '2018-12-27 05:06:20'),
(63, 49, 11, 15, 2, 2, 1, -25100, '-', 'no', '2018-12-27', '2018-12-27 05:24:51'),
(64, 49, 11, 15, 2, 2, 1, 25100, 'First Deposite', 'no', '2018-12-27', '2018-12-27 05:24:51'),
(65, 50, 13, 15, 2, 2, 1, -4001000, 'First Transfer', 'no', '2018-12-27', '2018-12-27 05:29:20'),
(66, 50, 13, 15, 6, 1, 80, 4000000, 'First Transfer', 'no', '2018-12-27', '2018-12-27 05:29:20'),
(67, 50, 13, 15, 1, 2, 1, 1000, 'Bank Charge', 'no', '2018-12-27', '2018-12-27 05:29:20'),
(68, 51, 13, 1, 2, 2, 1, -2102000, 'admin transfer', 'no', '2018-12-27', '2018-12-27 05:44:36'),
(69, 51, 13, 1, 6, 1, 84, 2100000, 'admin transfer', 'no', '2018-12-27', '2018-12-27 05:44:36'),
(70, 51, 13, 1, 1, 2, 1, 2000, 'Bank Charge', 'no', '2018-12-27', '2018-12-27 05:44:36'),
(71, 52, 12, 1, 6, 1, 80, -200000, 'Admin Expense', 'no', '2018-12-27', '2018-12-27 05:57:05'),
(72, 52, 12, 1, 4, 1, 80, 200000, '-', 'no', '2018-12-27', '2018-12-27 05:57:05'),
(73, 53, 12, 15, 6, 1, 80, -240000, 'Rafiq Expense', 'no', '2018-12-27', '2018-12-27 05:57:30'),
(74, 53, 12, 15, 4, 1, 80, 240000, '-', 'no', '2018-12-27', '2018-12-27 05:57:30'),
(75, 54, 16, 15, 6, 1, 1, 500, 'Payment for PO-0001', 'no', '2018-12-27', '2018-12-27 06:02:11'),
(76, 54, 16, 15, 6, 2, 1, -500, 'Payment for PO-0001', 'no', '2018-12-27', '2018-12-27 06:02:11'),
(77, 55, 16, 1, 6, 1, 1, 4000, 'Payment for PO-0002', 'no', '2018-12-27', '2018-12-27 06:02:42'),
(78, 55, 16, 1, 6, 2, 1, -4000, 'Payment for PO-0002', 'no', '2018-12-27', '2018-12-27 06:02:42'),
(79, 56, 15, 1, 4, 1, 80, -16000, 'Payment for INV-0028', 'no', '2018-12-27', '2018-12-27 07:51:45'),
(80, 56, 15, 1, 2, 2, 80, 16000, 'Payment for INV-0028', 'no', '2018-12-27', '2018-12-27 07:51:45'),
(81, 57, 15, 1, 4, 1, 80, -1600, 'Payment for INV-0028', 'no', '2018-12-27', '2018-12-27 07:54:24'),
(82, 57, 15, 1, 2, 2, 80, 1600, 'Payment for INV-0028', 'no', '2018-12-27', '2018-12-27 07:54:24'),
(83, 58, 15, 15, 4, 1, 80, -6160, 'Payment for INV-0032', 'no', '2018-12-27', '2018-12-27 09:35:19'),
(84, 58, 15, 15, 2, 2, 80, 6160, 'Payment for INV-0032', 'no', '2018-12-27', '2018-12-27 09:35:19'),
(85, 59, 15, 15, 4, 1, 80, -9600, 'Payment for INV-0040', 'no', '2018-12-27', '2018-12-27 10:52:07'),
(86, 59, 15, 15, 2, 2, 80, 9600, 'Payment for INV-0040', 'no', '2018-12-27', '2018-12-27 10:52:07'),
(87, 60, 15, 1, 4, 1, 80, -8000, 'Payment for INV-0045', 'no', '2018-12-27', '2018-12-27 11:03:48'),
(88, 60, 15, 1, 2, 2, 80, 8000, 'Payment for INV-0045', 'no', '2018-12-27', '2018-12-27 11:03:48'),
(89, 61, 15, 1, 4, 1, 0.12, -12, 'Payment for INV-0064', 'no', '2019-01-02', '2019-01-02 12:52:20'),
(90, 61, 15, 1, 2, 2, 0.12, 12, 'Payment for INV-0064', 'no', '2019-01-02', '2019-01-02 12:52:20'),
(91, 62, 16, 1, 6, 2, 1, 50, 'Payment for PO-0003', 'no', '2019-01-03', '2019-01-03 12:44:40'),
(92, 62, 16, 1, 2, 2, 1, -50, 'Payment for PO-0003', 'no', '2019-01-03', '2019-01-03 12:44:40'),
(93, 63, 16, 1, 6, 2, 1, 1000, 'Payment for PO-0003', 'no', '2019-01-03', '2019-01-03 12:46:32'),
(94, 63, 16, 1, 2, 2, 1, -1000, 'Payment for PO-0003', 'no', '2019-01-03', '2019-01-03 12:46:32'),
(95, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:28:36'),
(96, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:28:36'),
(97, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:28:54'),
(98, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:28:54'),
(99, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:34:20'),
(100, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:34:20'),
(101, 66, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:34:27'),
(102, 66, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:34:27'),
(103, 66, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:34:39'),
(104, 66, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:34:39'),
(105, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:36:34'),
(106, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:36:34'),
(107, 66, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:37:42'),
(108, 66, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:37:42'),
(109, 66, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:38:11'),
(110, 66, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:38:11'),
(111, 64, 15, 1, 4, 1, 60, -4800000, 'Bank payment for INV-0010', 'no', '2019-01-05', '2019-01-06 09:40:10'),
(112, 64, 15, 1, 4, 1, 60, 4800000, 'Bank payment for INV-0010', 'no', '2019-01-05', '2019-01-06 09:40:11'),
(113, 64, 15, 1, 4, 1, 60, -4800000, 'Bank payment for INV-0010', 'no', '2019-01-05', '2019-01-06 09:40:25'),
(114, 64, 15, 1, 4, 1, 60, 4800000, 'Bank payment for INV-0010', 'no', '2019-01-05', '2019-01-06 09:40:25'),
(115, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:41:27'),
(116, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:41:27'),
(117, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:42:59'),
(118, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:42:59'),
(119, 65, 15, 1, 4, 1, 60, -48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:44:34'),
(120, 65, 15, 1, 4, 1, 60, 48000, 'Bank payment for INV-0010', 'no', '2019-01-06', '2019-01-06 09:44:34'),
(121, 68, 15, 1, 4, 1, 81, -16200, 'Bank payment for INV-0066', 'no', '2019-01-06', '2019-01-06 09:56:36'),
(122, 68, 15, 1, 4, 1, 81, 16200, 'Bank payment for INV-0066', 'no', '2019-01-06', '2019-01-06 09:56:36'),
(125, 70, 15, 1, 4, 1, 81, -12150, 'Bank payment for INV-0068', 'no', '2019-01-06', '2019-01-06 10:07:26'),
(126, 70, 15, 1, 4, 1, 81, 12150, 'Bank payment for INV-0068', 'no', '2019-01-06', '2019-01-06 10:07:26'),
(127, 71, 15, 1, 4, 1, 79, -237000, 'Bank payment for INV-0069', 'no', '2019-01-06', '2019-01-06 10:11:42'),
(128, 71, 15, 1, 4, 1, 79, 237000, 'Bank payment for INV-0069', 'no', '2019-01-06', '2019-01-06 10:11:42'),
(129, 72, 15, 1, 4, 1, 79, -237079, 'Bank payment for INV-0069', 'no', '2019-01-06', '2019-01-06 10:12:33'),
(130, 72, 15, 1, 4, 1, 79, 237079, 'Bank payment for INV-0069', 'no', '2019-01-06', '2019-01-06 10:12:33'),
(131, 73, 15, 1, 4, 1, 81, -68850, 'Bank payment for INV-0070', 'no', '2019-01-06', '2019-01-06 11:30:28'),
(132, 73, 15, 1, 4, 1, 81, 68850, 'Bank payment for INV-0070', 'no', '2019-01-06', '2019-01-06 11:30:28'),
(133, 73, 15, 1, 4, 1, 81, -68850, 'Bank payment for INV-0070', 'no', '2019-01-06', '2019-01-06 11:30:38'),
(134, 73, 15, 1, 4, 1, 81, 68850, 'Bank payment for INV-0070', 'no', '2019-01-06', '2019-01-06 11:30:38'),
(135, 73, 15, 1, 4, 1, 81, -68850, 'Bank payment for INV-0070', 'no', '2019-01-06', '2019-01-06 11:30:58'),
(136, 73, 15, 1, 4, 1, 81, 68850, 'Bank payment for INV-0070', 'no', '2019-01-06', '2019-01-06 11:30:58'),
(137, 74, 15, 1, 4, 1, 81, -4050, 'Bank payment for INV-0071', 'no', '2019-01-06', '2019-01-06 12:31:16'),
(138, 74, 15, 1, 4, 1, 81, 4050, 'Bank payment for INV-0071', 'no', '2019-01-06', '2019-01-06 12:31:16'),
(139, 75, 15, 1, 4, 1, 81, -4050, 'Bank payment for INV-0071', 'no', '2019-01-06', '2019-01-06 12:57:41'),
(140, 75, 15, 1, 4, 1, 81, 4050, 'Bank payment for INV-0071', 'no', '2019-01-06', '2019-01-06 12:57:41'),
(141, 76, 15, 1, 4, 1, 81, -40500, 'Bank payment for INV-0073', 'no', '2019-01-06', '2019-01-06 13:12:20'),
(142, 76, 15, 1, 4, 1, 81, 40500, 'Bank payment for INV-0073', 'no', '2019-01-06', '2019-01-06 13:12:20'),
(143, 78, 16, 1, 6, 1, 0.12, 120, 'Payment for PO-0006', 'no', '2019-01-07', '2019-01-07 10:40:22'),
(144, 78, 16, 1, 2, 2, 0.12, -120, 'Payment for PO-0006', 'no', '2019-01-07', '2019-01-07 10:40:22'),
(145, 79, 15, 1, 4, 1, 0.12, -960, 'Payment for INV-0075', 'no', '2019-01-09', '2019-01-09 09:58:02'),
(146, 79, 15, 1, 2, 2, 0.12, 960, 'Payment for INV-0075', 'no', '2019-01-09', '2019-01-09 09:58:02'),
(147, 80, 15, 1, 4, 1, 0.12, -6, 'Payment for INV-0075', 'no', '2019-01-09', '2019-01-09 10:01:20'),
(148, 80, 15, 1, 2, 2, 0.12, 6, 'Payment for INV-0075', 'no', '2019-01-09', '2019-01-09 10:01:20'),
(149, 81, 15, 1, 4, 1, 90, -9000, 'Payment for INV-0079', 'no', '2019-01-15', '2019-01-15 05:10:49'),
(150, 81, 15, 1, 6, 1, 90, 9000, 'Payment for INV-0079', 'no', '2019-01-15', '2019-01-15 05:10:49'),
(151, 82, 16, 1, 6, 4, 0.11, 55, 'Payment for PO-0008', 'no', '2019-01-21', '2019-01-21 08:50:01'),
(152, 82, 16, 1, 4, 2, 0.11, -55, 'Payment for PO-0008', 'no', '2019-01-21', '2019-01-21 08:50:01'),
(153, 83, 15, 1, 4, 2, 0.0125, -100, 'Payment for INV-0082', 'no', '2019-01-21', '2019-01-21 11:48:05'),
(154, 83, 15, 1, 2, 2, 0.0125, 100, 'Payment for INV-0082', 'no', '2019-01-21', '2019-01-21 11:48:05'),
(155, 84, 11, 1, 2, 2, 0.0125, -0.125, '-', 'no', '2019-01-21', '2019-01-21 11:49:50'),
(156, 84, 11, 1, 2, 2, 0.0125, 0.125, 'dews', 'no', '2019-01-21', '2019-01-21 11:49:50'),
(157, 86, 15, 1, 4, 1, 1, -50, 'Payment for INV-0087', 'no', '2019-01-23', '2019-01-23 10:07:33'),
(158, 86, 15, 1, 6, 1, 1, 50, 'Payment for INV-0087', 'no', '2019-01-23', '2019-01-23 10:07:33'),
(159, 85, 15, 1, 4, 1, 1, -100, 'Bank payment for INV-0087', 'no', '2019-01-23', '2019-01-23 10:08:32'),
(160, 85, 15, 1, 4, 1, 1, 100, 'Bank payment for INV-0087', 'no', '2019-01-23', '2019-01-23 10:08:32'),
(161, 87, 15, 1, 4, 1, 1, -8092, 'Payment for INV-0087', 'no', '2019-01-23', '2019-01-23 10:13:10'),
(162, 87, 15, 1, 2, 2, 0.12, 8092, 'Payment for INV-0087', 'no', '2019-01-23', '2019-01-23 10:13:10'),
(163, 89, 15, 1, 4, 1, 1, -10, 'Bank payment for INV-0089', 'no', '2019-01-24', '2019-01-24 12:59:11'),
(164, 89, 15, 1, 7, 1, 1, 10, 'Bank payment for INV-0089', 'no', '2019-01-24', '2019-01-24 12:59:11'),
(165, 90, 15, 1, 4, 1, 1, -30, 'Payment for INV-0089', 'no', '2019-01-24', '2019-01-24 13:08:54'),
(166, 90, 15, 1, 7, 4, 0.12, 30, 'Payment for INV-0089', 'no', '2019-01-24', '2019-01-24 13:08:54'),
(167, 91, 15, 1, 4, 1, 0.11, -2.2, 'Bank payment for INV-0093', 'no', '1970-01-01', '2019-01-29 06:55:18'),
(168, 91, 15, 1, 7, 1, 0.11, 2.2, 'Bank payment for INV-0093', 'no', '1970-01-01', '2019-01-29 06:55:19'),
(169, 92, 15, 1, 4, 1, 0.11, -0.55, 'Bank payment for INV-0093', 'no', '1970-01-01', '2019-01-29 07:26:19'),
(170, 92, 15, 1, 7, 1, 0.11, 0.55, 'Bank payment for INV-0093', 'no', '1970-01-01', '2019-01-29 07:26:19'),
(171, 93, 15, 1, 4, 1, 0.11, -0.22, 'Payment for INV-0093', 'no', '2019-01-29', '2019-01-29 07:49:40'),
(172, 93, 15, 1, 4, 2, 0.11, 0.22, 'Payment for INV-0093', 'no', '2019-01-29', '2019-01-29 07:49:40'),
(173, 94, 15, 1, 4, 1, 0.11, -0.33, 'Payment for INV-0093', 'no', '2019-01-29', '2019-01-29 07:56:13'),
(174, 94, 15, 1, 4, 2, 0.11, 0.33, 'Payment for INV-0093', 'no', '2019-01-29', '2019-01-29 07:56:13'),
(175, 95, 15, 1, 4, 1, 0.11, -2.2, 'Payment for INV-0093', 'no', '2019-01-29', '2019-01-29 12:01:22'),
(176, 95, 15, 1, 2, 4, 50, 2.1999999999999997, 'Payment for INV-0093', 'no', '2019-01-29', '2019-01-29 12:01:22'),
(177, 95, 15, 1, 1, 2, 1, -4.4408920985006e-16, 'Exchange Variance', 'no', '2019-01-29', '2019-01-29 12:01:22'),
(178, 96, 15, 1, 4, 1, 79, -7821, 'Payment for INV-0094', 'no', '2019-01-29', '2019-01-29 12:06:58'),
(179, 96, 15, 1, 2, 4, 98, 7821.38, 'Payment for INV-0094', 'no', '2019-01-29', '2019-01-29 12:06:58'),
(180, 96, 15, 1, 1, 2, 1, -0.38000000000010914, 'Exchange Variance', 'no', '2019-01-29', '2019-01-29 12:06:58'),
(181, 97, 15, 1, 4, 1, 80, -4000, 'Payment for INV-0095', 'no', '2019-01-29', '2019-01-29 13:06:37'),
(182, 97, 15, 1, 7, 4, 40, 4000, 'Payment for INV-0095', 'no', '2019-01-29', '2019-01-29 13:06:37'),
(183, 98, 15, 1, 4, 1, 80, -2400, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 09:38:34'),
(184, 98, 15, 1, 6, 1, 80, 2400, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 09:38:35'),
(185, 99, 15, 1, 4, 1, 80, -5600, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:14:51'),
(186, 99, 15, 1, 2, 2, 80, 5600, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:14:51'),
(187, 100, 15, 1, 4, 1, 80, -40000, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:47:57'),
(188, 100, 15, 1, 2, 4, 40, 40000, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:47:57'),
(189, 101, 15, 1, 4, 1, 80, -48000, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:54:07'),
(190, 101, 15, 1, 2, 4, 50, 48000, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:54:07'),
(191, 102, 15, 1, 4, 1, 80, -16000, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:56:21'),
(192, 102, 15, 1, 2, 4, 70, 28000, 'Payment for INV-0096', 'no', '2019-01-30', '2019-01-30 10:56:21'),
(193, 102, 15, 1, 1, 2, 1, -12000, 'Exchange Variance', 'no', '2019-01-30', '2019-01-30 10:56:21'),
(194, 106, 15, 1, 4, 1, 1, -550, 'Bank payment for INV-0099', 'no', '1970-01-01', '2019-01-30 11:47:49'),
(195, 106, 15, 1, 7, 1, 1, 550, 'Bank payment for INV-0099', 'no', '1970-01-01', '2019-01-30 11:47:49'),
(196, 107, 15, 1, 4, 1, 1, -50, 'Bank payment for INV-0099', 'no', '1970-01-01', '2019-01-30 11:55:21'),
(197, 107, 15, 1, 7, 1, 1, 50, 'Bank payment for INV-0099', 'no', '1970-01-01', '2019-01-30 11:55:21'),
(198, 110, 15, 1, 4, 1, 1, -500, 'Bank payment for INV-0101', 'no', '1970-01-01', '2019-01-31 06:58:38'),
(199, 110, 15, 1, 7, 1, 1, 500, 'Bank payment for INV-0101', 'no', '1970-01-01', '2019-01-31 06:58:38'),
(200, 111, 15, 1, 4, 1, 1, -57, 'Bank payment for INV-0101', 'no', '1970-01-01', '2019-01-31 07:16:24'),
(201, 111, 15, 1, 7, 1, 1, 57, 'Bank payment for INV-0101', 'no', '1970-01-01', '2019-01-31 07:16:24'),
(202, 112, 16, 1, 6, 2, 1, 25, 'Payment for PO-0009', 'no', '2019-01-31', '2019-01-31 10:20:55'),
(203, 112, 16, 1, 4, 2, 1, -25, 'Payment for PO-0009', 'no', '2019-01-31', '2019-01-31 10:20:55'),
(204, 113, 16, 1, 6, 4, 1, 500, 'Payment for PO-0009', 'no', '2019-01-31', '2019-01-31 10:40:34'),
(205, 113, 16, 1, 7, 2, 1, -500, 'Payment for PO-0009', 'no', '2019-01-31', '2019-01-31 10:40:34'),
(206, 114, 15, 1, 4, 2, 1, -5000, 'Payment for INV-0100', 'no', '2019-01-31', '2019-01-31 10:56:23'),
(207, 114, 15, 1, 7, 4, 50, 5000, 'Payment for INV-0100', 'no', '2019-01-31', '2019-01-31 10:56:23'),
(208, 115, 16, 1, 6, 1, 70, 6125, 'Payment for PO-0010', 'yes', '2019-01-31', '2019-01-31 11:22:44'),
(209, 115, 16, 1, 2, 4, 80, -6160, 'Payment for PO-0010', 'yes', '2019-01-31', '2019-01-31 11:22:44'),
(210, 115, 16, 1, 1, 2, 1, 35, 'Exchange Variance', 'yes', '2019-01-31', '2019-01-31 11:22:44'),
(211, 116, 16, 1, 6, 1, 70, 1729000, 'Payment for PO-0010', 'no', '2019-01-31', '2019-01-31 11:42:36'),
(212, 116, 16, 1, 4, 2, 70, -1729000, 'Payment for PO-0010', 'no', '2019-01-31', '2019-01-31 11:42:36'),
(213, 117, 16, 1, 6, 4, 50, 42875, 'Payment for PO-0011', 'no', '2019-01-31', '2019-01-31 12:28:31'),
(214, 117, 16, 1, 2, 2, 50, -42875, 'Payment for PO-0011', 'no', '2019-01-31', '2019-01-31 12:28:31'),
(215, 115, 16, 1, 6, 1, 70, -6125, '009/2019(reversal)', 'yes', '2019-01-31', '2019-01-31 13:18:40'),
(216, 115, 16, 1, 2, 4, 80, 6160, '009/2019(reversal)', 'yes', '2019-01-31', '2019-01-31 13:18:40'),
(217, 115, 16, 1, 1, 2, 1, -35, '009/2019(reversal)', 'yes', '2019-01-31', '2019-01-31 13:18:40'),
(218, 118, 12, 1, 7, 4, 50, -1625.5, 'This is a demo expenses', 'no', '2019-02-03', '2019-02-03 13:07:15'),
(219, 118, 12, 1, 10, 4, 50, 1625.5, '-', 'no', '2019-02-03', '2019-02-03 13:07:15'),
(220, 119, 12, 1, 2, 4, 50, -1992.5, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type a', 'no', '2019-02-03', '2019-02-03 13:12:35'),
(221, 119, 12, 1, 10, 4, 50, 1992.5, '-', 'no', '2019-02-03', '2019-02-03 13:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `income_expense_categories`
--

DROP TABLE IF EXISTS `income_expense_categories`;
CREATE TABLE IF NOT EXISTS `income_expense_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `income_expense_categories`
--

INSERT INTO `income_expense_categories` (`id`, `name`, `type`) VALUES
(1, 'Exchange Varience', 'none'),
(2, 'Sales', 'income'),
(3, 'Sallery', 'income'),
(4, 'Utility Bill', 'expense'),
(5, 'Repair & MaintEnance', 'expense'),
(6, 'Advertising', 'expense'),
(7, 'Meals & Entertainment', 'expense'),
(8, 'Test Category 1', 'income'),
(9, 'test', 'income'),
(10, 'Others', 'expense');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payment_terms`
--

DROP TABLE IF EXISTS `invoice_payment_terms`;
CREATE TABLE IF NOT EXISTS `invoice_payment_terms` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `terms` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `days_before_due` int(11) NOT NULL,
  `defaults` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoice_payment_terms`
--

INSERT INTO `invoice_payment_terms` (`id`, `terms`, `days_before_due`, `defaults`) VALUES
(1, 'Cash on deleivery', 0, 1),
(2, 'Net15', 15, 0),
(3, 'Net30', 30, 0),
(4, 'Net10', 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `item_type` enum('product','service') COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `item_name` varchar(199) COLLATE utf8_unicode_ci NOT NULL,
  `available_variant` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` int(10) DEFAULT NULL,
  `weight_unit_id` int(11) DEFAULT NULL,
  `manage_stock_level` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hsn` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_status` tinyint(4) NOT NULL DEFAULT '0',
  `alert_item_quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `weight_unit_id` (`weight_unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `stock_id`, `category_id`, `item_type`, `parent_id`, `item_name`, `available_variant`, `size`, `color`, `weight`, `weight_unit_id`, `manage_stock_level`, `description`, `item_image`, `hsn`, `inactive`, `deleted_status`, `alert_item_quantity`, `created_at`, `updated_at`) VALUES
(1, 'T-1000', 1, 'service', 0, 'IT Support', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, NULL),
(2, 'T-2000', 1, 'product', 0, 'Samsung Galaxy S7', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, '2019-01-22 00:09:07'),
(3, 'T-1001', 1, 'service', 0, 'Software Development', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, '2019-01-21 06:45:07'),
(4, 'T-1002', 1, 'service', 0, 'Graphics Design', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, NULL),
(5, 'T-1003', 1, 'service', 0, 'Web Design', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, NULL),
(6, 'T-1004', 1, 'service', 0, 'Web Development', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, NULL),
(7, '11', 2, 'product', 0, 'test', NULL, NULL, NULL, NULL, NULL, 'no', '', 'bank.jpg', NULL, 0, 0, NULL, NULL, NULL),
(8, '1', 1, 'service', 3, 'TEST', NULL, NULL, NULL, NULL, NULL, 'no', 'test', NULL, NULL, 0, 0, NULL, NULL, NULL),
(9, '12', 1, 'product', 0, 'Blackberry', NULL, NULL, NULL, NULL, NULL, 'no', '', 'worlds_most_beautiful_walet.png', NULL, 0, 0, NULL, NULL, NULL),
(10, '1', 1, 'product', 9, 'BLACKBERRY1', NULL, NULL, NULL, NULL, NULL, 'no', 'blackberry1', NULL, NULL, 0, 0, NULL, NULL, NULL),
(11, '1', 1, 'product', 9, 'BLACKBERRY2', NULL, NULL, NULL, NULL, NULL, 'no', 'blackberry2', NULL, NULL, 0, 0, NULL, NULL, NULL),
(12, '1212', 1, 'product', 0, 'Nokia N3', NULL, NULL, NULL, NULL, NULL, 'no', '', 'images.jpg', 'aaa', 0, 0, NULL, NULL, NULL),
(13, '1', 1, 'product', 12, 'NOKIA', NULL, NULL, NULL, NULL, NULL, 'no', 'is it required?', NULL, NULL, 0, 0, NULL, NULL, NULL),
(14, 'ITEM71', 2, 'product', 0, '71 watch', NULL, NULL, NULL, NULL, NULL, 'yes', '', 'Desert.jpg', NULL, 0, 0, NULL, NULL, NULL),
(15, 'ITEM81', 1, 'product', 0, 'Watch 81', NULL, NULL, NULL, NULL, NULL, 'yes', '', NULL, NULL, 0, 1, NULL, NULL, NULL),
(16, 'ITEM91', 1, 'product', 0, 'dfdsfs', NULL, NULL, NULL, NULL, NULL, 'yes', '', NULL, NULL, 0, 0, 60, NULL, NULL),
(17, 'ITEM92', 1, 'product', 0, 'dfdsfsddd', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 1, NULL, NULL, NULL),
(18, 'ITEM21', 1, 'product', 0, 'check item', NULL, NULL, NULL, NULL, NULL, 'yes', '', NULL, NULL, 0, 0, 50, NULL, '2019-01-08 01:42:41'),
(19, '13', 1, 'product', 0, 'Oppo F7', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, NULL, 0, 0, NULL, NULL, NULL),
(20, '1', 1, 'product', 19, 'OPPO F7 (NEW)', NULL, NULL, NULL, NULL, NULL, 'no', 'test', NULL, NULL, 0, 0, NULL, NULL, NULL),
(21, 'DSA', 1, 'product', 0, 'dsfd', NULL, NULL, NULL, NULL, NULL, 'no', '', NULL, 'dsf', 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_custom_variants`
--

DROP TABLE IF EXISTS `item_custom_variants`;
CREATE TABLE IF NOT EXISTS `item_custom_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `variant_title` varchar(240) NOT NULL,
  `variant_value` varchar(240) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_custom_variants_item_id_idx` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_tax_types`
--

DROP TABLE IF EXISTS `item_tax_types`;
CREATE TABLE IF NOT EXISTS `item_tax_types` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tax_rate` double(8,2) NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item_tax_types`
--

INSERT INTO `item_tax_types` (`id`, `name`, `tax_rate`, `defaults`) VALUES
(1, 'Tax Exempt', 0.00, 0),
(2, 'Sales Tax', 15.00, 0),
(3, 'Purchases Tax', 15.00, 0),
(4, 'Normal', 5.00, 0),
(6, 'Gst', 20.00, 0),
(7, 'others', 2.50, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_unit`
--

DROP TABLE IF EXISTS `item_unit`;
CREATE TABLE IF NOT EXISTS `item_unit` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `abbr` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item_unit`
--

INSERT INTO `item_unit` (`id`, `abbr`, `name`, `inactive`, `created_at`, `updated_at`) VALUES
(1, 'each', 'Each', 0, NULL, NULL),
(2, 'unit', 'unit name', 0, '2019-01-27 04:30:42', '2019-01-27 04:30:42'),
(3, 'nw', 'new', 0, '2019-01-27 06:48:59', '2019-01-27 06:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(23, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:440:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Borna,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Invoice module<br>\\r\\n            Start date&nbsp; &nbsp;: 10-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:34:\\\"New Task Assigned #Invoice module.\\\";s:5:\\\"email\\\";s:18:\\\"borna606@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547537096, 1547537036),
(16, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:449:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Md. Milon Hossain,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Milestone check<br>\\r\\n            Start date&nbsp; &nbsp;: 10-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : Complete<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:35:\\\"New Task Assigned #Milestone check.\\\";s:5:\\\"email\\\";s:24:\\\"milon.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547448316, 1547448256),
(17, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:467:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Md. Milon Hossain,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Milestone check again<br>\\r\\n            Start date&nbsp; &nbsp;: 10-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Medium\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : Awaiting Feedback<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:41:\\\"New Task Assigned #Milestone check again.\\\";s:5:\\\"email\\\";s:24:\\\"milon.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547449303, 1547449243),
(18, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:466:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Md. Milon Hossain,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Design chnage of front end<br>\\r\\n            Start date&nbsp; &nbsp;: 14-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Medium\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : In Progress<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:46:\\\"New Task Assigned #Design chnage of front end.\\\";s:5:\\\"email\\\";s:24:\\\"milon.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547451148, 1547451088),
(19, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:464:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Md. Milon Hossain,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Design chnage of front end<br>\\r\\n            Start date&nbsp; &nbsp;: 14-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: High\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : In Progress<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:46:\\\"New Task Assigned #Design chnage of front end.\\\";s:5:\\\"email\\\";s:24:\\\"milon.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547452061, 1547452001),
(20, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:452:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Borna,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Design chnage of front end<br>\\r\\n            Start date&nbsp; &nbsp;: 14-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: High\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : In Progress<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:46:\\\"New Task Assigned #Design chnage of front end.\\\";s:5:\\\"email\\\";s:18:\\\"borna606@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547453786, 1547453726),
(21, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:440:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Admin,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Invoice module<br>\\r\\n            Start date&nbsp; &nbsp;: 10-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:34:\\\"New Task Assigned #Invoice module.\\\";s:5:\\\"email\\\";s:18:\\\"admin@techvill.net\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547537091, 1547537031),
(22, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:440:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Rafiq,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Invoice module<br>\\r\\n            Start date&nbsp; &nbsp;: 10-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:34:\\\"New Task Assigned #Invoice module.\\\";s:5:\\\"email\\\";s:24:\\\"rafiq.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1547537093, 1547537033),
(24, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:462:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Md. Milon Hossain,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Test task for customer-1<br>\\r\\n            Start date&nbsp; &nbsp;: 2019-01-21<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 1970-01-01&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:44:\\\"New Task Assigned #Test task for customer-1.\\\";s:5:\\\"email\\\";s:24:\\\"milon.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1548063894, 1548063834),
(25, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:450:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Rafiq,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Test task for customer-1<br>\\r\\n            Start date&nbsp; &nbsp;: 2019-01-21<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 1970-01-01&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:44:\\\"New Task Assigned #Test task for customer-1.\\\";s:5:\\\"email\\\";s:24:\\\"rafiq.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1548064234, 1548064174),
(26, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:444:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Md. Milon Hossain,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : test-2<br>\\r\\n            Start date&nbsp; &nbsp;: 2019-01-21<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 1970-01-01&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:26:\\\"New Task Assigned #test-2.\\\";s:5:\\\"email\\\";s:24:\\\"milon.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1548064828, 1548064768),
(27, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:444:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Borna,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : assignee mail link<br>\\r\\n            Start date&nbsp; &nbsp;: 24-01-2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01-01-1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; :  Not Started<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:38:\\\"New Task Assigned #assignee mail link.\\\";s:5:\\\"email\\\";s:18:\\\"borna606@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1548324116, 1548324056),
(28, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:455:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Rafiq,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Milestone check again<br>\\r\\n            Start date&nbsp; &nbsp;: 01\\/10\\/2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01\\/01\\/1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Medium\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : Awaiting Feedback<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:41:\\\"New Task Assigned #Milestone check again.\\\";s:5:\\\"email\\\";s:24:\\\"rafiq.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1548918305, 1548918245),
(29, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:455:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Rafiq,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Milestone check again<br>\\r\\n            Start date&nbsp; &nbsp;: 01\\/10\\/2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01\\/01\\/1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Medium\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : Awaiting Feedback<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:41:\\\"New Task Assigned #Milestone check again.\\\";s:5:\\\"email\\\";s:24:\\\"rafiq.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1548918321, 1548918261),
(30, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendEmailJob\",\"command\":\"O:21:\\\"App\\\\Jobs\\\\SendEmailJob\\\":8:{s:7:\\\"\\u0000*\\u0000data\\\";a:3:{s:7:\\\"message\\\";s:438:\\\"<p><\\/p>\\r\\n\\r\\n<p>Hello Rafiq,<\\/p><p>\\r\\n\\r\\nA new support task has been assigned to you.&nbsp;<\\/p>Task Name : Invoice module<br>\\r\\n            Start date&nbsp; &nbsp;: 10\\/Jan\\/2019<div>\\r\\n\\r\\nDue date&nbsp; &nbsp; : 01\\/Jan\\/1970&nbsp;<\\/div><div>\\r\\n\\r\\nPriority&nbsp; &nbsp; &nbsp; &nbsp;: Low\\r\\n\\r\\n&nbsp;\\r\\n<br>Status&nbsp; &nbsp; &nbsp; &nbsp; : Complete<br>\\r\\n           <p>Regards,<\\/p><p>Techvillage Support<\\/p>\\r\\n\\r\\n<br>\\r\\n\\r\\n<br>\\r\\n                    <\\/div>\\\";s:7:\\\"subject\\\";s:34:\\\"New Task Assigned #Invoice module.\\\";s:5:\\\"email\\\";s:24:\\\"rafiq.techvill@gmail.com\\\";}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";i:60;s:7:\\\"chained\\\";a:0:{}s:6:\\\"\\u0000*\\u0000job\\\";N;}\"}}', 0, NULL, 1549083379, 1549083319);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `flag` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  `default` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `deletable` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'Yes',
  `direction` enum('ltr','rtl') NOT NULL DEFAULT 'ltr',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `status`, `default`, `deletable`, `direction`) VALUES
(1, 'English', 'en', 'en.jpg', 'Active', '1', 'No', 'ltr'),
(2, 'عربى', 'ar', 'ar.png', 'Active', '0', 'No', 'rtl'),
(4, 'Português', 'pt', 'fr.png', 'Active', '0', 'No', 'ltr'),
(10, 'China', 'ch', 'ch.png', 'Inactive', '0', 'Yes', 'ltr'),
(11, 'Japani', 'JP', 'jp.png', 'Active', '0', 'Yes', 'ltr'),
(12, 'France', 'fr', 'fr.png', 'Active', '0', 'Yes', 'ltr'),
(14, 'Turki', 'tr', 'tr.png', 'Active', '0', 'Yes', 'ltr'),
(15, 'Russian', 'rs', 'rs.png', 'Active', '0', 'Yes', 'ltr'),
(17, 'Hindi', 'hn', 'hn.png', 'Active', '', 'Yes', 'ltr');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip_code` varchar(50) DEFAULT NULL,
  `country_id` varchar(20) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `description` text,
  `status_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `assignee_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `last_contact` datetime DEFAULT NULL,
  `last_status_change` datetime DEFAULT NULL,
  `last_lead_status` int(11) DEFAULT NULL,
  `date_converted` date DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `is_lost` enum('yes','no') NOT NULL DEFAULT 'no',
  `is_public` enum('yes','no') NOT NULL DEFAULT 'no',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `first_name`, `last_name`, `email`, `street`, `city`, `state`, `zip_code`, `country_id`, `phone`, `website`, `company`, `description`, `status_id`, `source_id`, `assignee_id`, `user_id`, `last_contact`, `last_status_change`, `last_lead_status`, `date_converted`, `date_assigned`, `is_lost`, `is_public`, `created_at`) VALUES
(10, 'Shakib', 'Mostahid', 'mostahid@gmail.com', 'Dummy Street', 'Demo City', 'Dummy State', '1234', '1', '01111111111', 'www.shakib.com', 'SM Enterprice', 'Dummy Description', 1, 2, 17, 1, '2019-01-05 19:00:00', '2019-01-29 07:18:50', 2, '2019-01-29', '2019-01-28', 'no', 'no', '2019-01-28 19:05:50'),
(11, 'Urmy', 'Khan', 'nishat1@gmail.com', NULL, NULL, NULL, NULL, NULL, '11111111', 'www.nishat.com', 'Nish Ltd.', NULL, 1, 2, 18, 1, '2019-01-28 19:00:00', '2019-01-29 07:42:13', 9, '2019-01-29', '2019-01-28', 'no', 'no', '2019-01-28 19:07:00'),
(12, 'Tareq', 'Ahmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 1, 18, 1, NULL, '2019-01-31 13:31:15', 8, NULL, '2019-01-28', 'no', 'no', '2019-01-28 19:10:46'),
(15, 'Test For tag', 'Arif', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'no', 'no', '2019-01-29 16:15:45'),
(16, 'Test For tag 2', 'Arif', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 1, NULL, 1, '2019-02-23 14:00:00', '2019-01-31 06:36:36', 9, NULL, NULL, 'no', 'no', '2019-01-29 18:22:44'),
(19, 'Gracie', 'Leannon', 'rowena43@example.net', '267 Wolf Forge Suite 668', 'Marcellemouth', 'Vermont', '61208', '226', '234234234', NULL, 'Collins, Durgan and Reinger', NULL, 1, 2, NULL, 1, NULL, '2019-02-03 06:38:12', 10, '2019-02-03', NULL, 'no', 'no', '2019-01-31 14:43:30'),
(25, 'Borna', 'techvill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 18, 1, NULL, '2019-02-03 07:24:56', 2, '2019-02-03', '2019-02-03', 'no', 'no', '2019-02-03 12:46:59'),
(26, 'Borna', 'techvill', NULL, 'DHAka', 'Dhaka', 'Dhaka', '1216', '20', NULL, NULL, 'Techvillage', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 1, 17, 1, '2019-02-02 13:00:00', '2019-02-03 09:27:01', 8, '2019-02-03', '2019-02-03', 'no', 'no', '2019-02-03 14:26:01'),
(27, 'Walton', 'Primo', 'walton@bd.com', 'Dhaka', 'Dhaka', NULL, 'Dhaka', '20', NULL, 'walton.bd', 'Walton', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 1, NULL, 1, '2019-02-08 15:58:00', '2019-02-03 10:03:56', 9, '2019-02-03', NULL, 'no', 'no', '2019-02-03 15:59:07'),
(28, 'Lead', 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 2, NULL, 1, '2019-02-12 19:00:00', NULL, NULL, NULL, NULL, 'no', 'no', '2019-02-03 17:21:09');

-- --------------------------------------------------------

--
-- Table structure for table `lead_sources`
--

DROP TABLE IF EXISTS `lead_sources`;
CREATE TABLE IF NOT EXISTS `lead_sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_sources`
--

INSERT INTO `lead_sources` (`id`, `name`, `status`) VALUES
(1, 'Facebook', 'active'),
(2, 'Google', 'active'),
(3, 'Skype', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `lead_status`
--

DROP TABLE IF EXISTS `lead_status`;
CREATE TABLE IF NOT EXISTS `lead_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_status`
--

INSERT INTO `lead_status` (`id`, `name`, `color`, `status`) VALUES
(1, 'Customer', '#00a65a', 'inactive'),
(2, 'Contacted', NULL, 'active'),
(8, 'Proposal Sent', '#BCBE36', 'active'),
(9, 'Qualified', '#A261FA', 'active'),
(10, 'Working', '#4483F0', 'active'),
(11, 'New', '#44DBF0', 'active'),
(14, 'Test status', '#ddd', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loc_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `location_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `delivery_address` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `loc_code`, `location_name`, `delivery_address`, `email`, `phone`, `fax`, `contact`, `inactive`, `created_at`, `updated_at`) VALUES
(1, 'PL', 'Primary Location', 'Primary Location', '', '', '', 'Primary Location', 0, '2018-11-05 01:21:54', '2019-01-17 03:50:39'),
(2, 'JA', 'Jackson Av', '125 Hayes St, San Francisco, CA 94102, USA', '', '', '', 'Jackson Av', 0, '2018-11-05 01:21:54', '2019-01-17 06:45:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_100000_create_activities_table', 1),
('2014_10_12_100000_create_calendar_events_table', 1),
('2014_10_12_100000_create_customer_transactions_table', 1),
('2014_10_12_100000_create_departments_table', 1),
('2014_10_12_100000_create_files_table', 1),
('2014_10_12_100000_create_milestones_table', 1),
('2014_10_12_100000_create_notes_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2014_10_12_100000_create_priorities_table', 1),
('2014_10_12_100000_create_project_settings_table', 1),
('2014_10_12_100000_create_project_status_table', 1),
('2014_10_12_100000_create_projectmembers_table', 1),
('2014_10_12_100000_create_projects_table', 1),
('2014_10_12_100000_create_purchase_type_table', 1),
('2014_10_12_100000_create_receive_order_details_table', 1),
('2014_10_12_100000_create_receive_orders_table', 1),
('2014_10_12_100000_create_supplier_transactions_table', 1),
('2014_10_12_100000_create_tags_in_table', 1),
('2014_10_12_100000_create_tags_table', 1),
('2014_10_12_100000_create_task_assigns_table', 1),
('2014_10_12_100000_create_task_comments_table', 1),
('2014_10_12_100000_create_tasks_table', 1),
('2014_10_12_100000_create_ticket_reply_table', 1),
('2014_10_12_100000_create_ticket_status_table', 1),
('2014_10_12_100000_create_tickets_table', 1),
('2014_10_12_100000_create_user_departments_table', 1),
('2015_09_26_161159_entrust_setup_tables', 1),
('2016_08_30_100832_create_users_table', 1),
('2016_08_30_104058_create_security_role_table', 1),
('2016_08_30_104506_create_stock_category_table', 1),
('2016_08_30_105339_create_location_table', 1),
('2016_08_30_110408_create_items_table', 1),
('2016_08_30_114231_create_item_unit_table', 1),
('2016_09_02_070031_create_stock_master_table', 1),
('2016_09_20_123717_create_stock_move_table', 1),
('2016_10_05_113244_create_customers_table', 1),
('2016_10_05_113333_create_sales_orders_table', 1),
('2016_10_05_113356_create_sales_order_details_table', 1),
('2016_10_18_060431_create_supplier_table', 1),
('2016_10_18_063931_create_purch_order_table', 1),
('2016_10_18_064211_create_purch_order_detail_table', 1),
('2016_11_15_121343_create_preference_table', 1),
('2016_12_01_130110_create_shipment_table', 1),
('2016_12_01_130443_create_shipment_details_table', 1),
('2016_12_03_051429_create_sale_price_table', 1),
('2016_12_03_052017_create_sales_types_table', 1),
('2016_12_03_061206_create_purchase_price_table', 1),
('2016_12_03_062131_create_payment_term_table', 1),
('2016_12_03_062247_create_payment_history_table', 1),
('2016_12_03_062932_create_item_tax_type_table', 1),
('2016_12_03_063827_create_invoice_payment_term_table', 1),
('2016_12_03_064157_create_email_temp_details_table', 1),
('2016_12_03_064747_create_email_config_table', 1),
('2016_12_03_065532_create_cust_branch_table', 1),
('2016_12_03_065915_create_currency_table', 1),
('2016_12_03_070030_create_country_table', 1),
('2016_12_03_070030_create_stock_transfer_table', 1),
('2016_12_03_071018_create_backup_table', 1),
('2017_03_20_104506_create_bank_account_type_table', 1),
('2017_03_20_104506_create_bank_accounts_table', 1),
('2017_03_20_104506_create_bank_trans_table', 1),
('2017_03_20_104506_create_custom_item_orders_table', 1),
('2017_03_20_104506_create_income_expense_categories_table', 1),
('2017_03_20_104506_create_month_table', 1),
('2017_04_10_062131_create_payment_gateway_table', 1),
('2018_09_10_062131_create_sms_config_table', 2),
('2019_01_09_100552_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
CREATE TABLE IF NOT EXISTS `milestones` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int(10) UNSIGNED NOT NULL,
  `milestone_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `visible_to_customer` tinyint(4) DEFAULT NULL COMMENT '1=yes, 0=no',
  `due_date` date DEFAULT NULL,
  `milestone_order` int(11) DEFAULT NULL,
  `color` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `milestones_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `project_id`, `milestone_name`, `description`, `visible_to_customer`, `due_date`, `milestone_order`, `color`, `created_at`) VALUES
(5, 19, 'Cart Design', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,', 0, '2019-01-15', 2, NULL, '2019-01-10 06:46:43'),
(6, 19, 'Cart Development', 'The .table-bordered class adds borders on all sides of the table and cells:The .table-bordered class adds borders on all sides of the table and cells:The .table-bordered class adds borders on all sides of the table and cells:', 0, '2019-01-20', 1, NULL, '2019-01-10 07:43:41'),
(7, 20, 'Design', 'design related task', 0, '2019-01-20', 1, NULL, '2019-01-14 07:02:18'),
(8, 20, 'Testing', 'Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. \r\n\r\nTesting related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. \r\n\r\nTesting related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task.', 0, '2019-01-30', 1, NULL, '2019-01-14 07:02:58'),
(9, 20, 'Planning', 'PTesting related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. \r\n\r\nTesting related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task.', 0, '2019-01-06', 1, NULL, '2019-01-14 07:03:24'),
(11, 21, 'New MileStone', 'Description, Description, Description, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, DescriptionDescription, Description', 1, '2019-01-17', 1, NULL, '2019-01-17 07:46:28'),
(12, 27, 'Milestone 1 for pay money APP', 'Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. \r\n\r\nMilestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. \r\nMilestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. \r\nMilestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP.', 1, '2019-01-29', 1, NULL, '2019-01-21 10:21:40'),
(13, 27, 'Milestone 2 for pay money APP.', 'Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. \r\n\r\nMilestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. \r\nMilestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP. Milestone 1 for pay money APP.', 0, '2019-01-28', 1, NULL, '2019-01-21 10:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `months`;
CREATE TABLE IF NOT EXISTS `months` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`id`, `name`) VALUES
(1, 'January'),
(2, 'February'),
(3, 'March'),
(4, 'Appril'),
(5, 'May'),
(6, 'June'),
(7, 'July'),
(8, 'August'),
(9, 'September'),
(10, 'October'),
(11, 'November'),
(12, 'December');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `related_to_id` int(10) UNSIGNED DEFAULT NULL,
  `related_to_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notes_project_id_foreign` (`project_id`),
  KEY `notes_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `project_id`, `user_id`, `related_to_id`, `related_to_type`, `subject`, `content`, `created_at`) VALUES
(4, NULL, 1, 5, 'customer', 'dfdf', 'fsdfd', '2019-01-07 19:09:39'),
(5, NULL, 1, 5, 'customer', 'sfdsf', 'dsfsd', '2019-01-07 19:11:13'),
(6, NULL, 1, 5, 'customer', 'sfsd', 'fdsfds', '2019-01-07 13:14:10'),
(8, NULL, 1, 7, 'customer', 'Phone verification button(cancel, get code, verify, verification message)', 'Phone verification button(cancel, get code, verify, verification message)Phone verification button(cancel, get code, verify, verification message)Phone verification button(cancel, get code, verify, verification message)Phone verification button(cancel, get code, verify, verification message)', '2019-01-09 07:50:58'),
(9, NULL, 1, 7, 'customer', 'test', 'test', '2019-01-09 07:51:10'),
(15, NULL, 1, 8, 'customer', 'Task Note forBorna', 'Payment is partialf', '2019-01-10 07:17:51'),
(16, NULL, 1, 8, 'customer', 'test', 'test', '2019-01-14 05:45:47'),
(17, 27, 1, NULL, 'project', '', '<p>Note about pay money mobile app</p>', '2019-01-24 16:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'customer.321@gmail.com', '8b29633a250082a7c19b3178984490fd56d5ae06dfeaaa3e4e51dc1480b3fe88', '2018-12-27 01:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway`
--

DROP TABLE IF EXISTS `payment_gateway`;
CREATE TABLE IF NOT EXISTS `payment_gateway` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_gateway`
--

INSERT INTO `payment_gateway` (`id`, `name`, `value`, `site`) VALUES
(1, 'username', 'techvillage_business_api1.gmail.com', 'PayPal'),
(2, 'password', '9DDYZX2JLA6QL668', 'PayPal'),
(3, 'signature', 'AFcWxV21C7fd0v3bYYYRCpSSRl31ABayz5pdk84jno7.Udj6-U8ffwbT', 'PayPal'),
(4, 'mode', 'sandbox', 'PayPal'),
(6, 'bank_account_id', '2', 'PayPal'),
(7, 'bank_account_id', '5', 'Bank');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

DROP TABLE IF EXISTS `payment_history`;
CREATE TABLE IF NOT EXISTS `payment_history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `payment_type_id` int(10) UNSIGNED NOT NULL,
  `order_reference` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_reference` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_date` date NOT NULL,
  `amount` double DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchange_rate` double NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_history_transaction_id_foreign` (`transaction_id`),
  KEY `payment_history_payment_type_id_foreign` (`payment_type_id`),
  KEY `payment_history_user_id_foreign` (`user_id`),
  KEY `payment_history_customer_id_foreign` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_terms`
--

DROP TABLE IF EXISTS `payment_terms`;
CREATE TABLE IF NOT EXISTS `payment_terms` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_terms`
--

INSERT INTO `payment_terms` (`id`, `name`, `defaults`) VALUES
(1, 'Paypal', 1),
(2, 'Bank', 0),
(4, 'stripe', 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_group` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES
(1, 'manage_relationship', 'Manage Relationship', 'Manage Relationship', 'manage relationships', NULL, NULL),
(2, 'manage_customer', 'Manage Customers', 'Manage Customers', 'customers', NULL, NULL),
(3, 'add_customer', 'Add Customer', 'Add Customer', 'customers', NULL, NULL),
(4, 'edit_customer', 'Edit Customer', 'Edit Customer', 'customers', NULL, NULL),
(5, 'delete_customer', 'Delete Customer', 'Delete Customer', 'customers', NULL, NULL),
(6, 'manage_supplier', 'Manage Suppliers', 'Manage Suppliers', 'suppliers', NULL, NULL),
(7, 'add_supplier', 'Add Supplier', 'Add Supplier', 'suppliers', NULL, NULL),
(8, 'edit_supplier', 'Edit Supplier', 'Edit Supplier', 'suppliers', NULL, NULL),
(9, 'delete_supplier', 'Delete Supplier', 'Delete Supplier', 'suppliers', NULL, NULL),
(10, 'manage_item', 'Manage Items', 'Manage Items', 'items', NULL, NULL),
(11, 'add_item', 'Add Item', 'Add Item', 'items', NULL, NULL),
(12, 'edit_item', 'Edit Item', 'Edit Item', 'items', NULL, NULL),
(13, 'delete_item', 'Delete Item', 'Delete Item', 'items', NULL, NULL),
(14, 'manage_sale', 'Manage Sales', 'Manage Sales', 'manage sales', NULL, NULL),
(15, 'manage_quotation', 'Manage Quotations', 'Manage Quotations', 'quotations', NULL, NULL),
(16, 'add_quotation', 'Add Quotation', 'Add Quotation', 'quotations', NULL, NULL),
(17, 'edit_quotation', 'Edit Quotation', 'Edit Quotation', 'quotations', NULL, NULL),
(18, 'delete_quotation', 'Delete Quotation', 'Delete Quotation', 'quotations', NULL, NULL),
(19, 'manage_invoice', 'Manage Invoices', 'Manage Invoices', 'invoices', NULL, NULL),
(20, 'add_invoice', 'Add Invoice', 'Add Invoice', 'invoices', NULL, NULL),
(21, 'edit_invoice', 'Edit Invoice', 'Edit Invoice', 'invoices', NULL, NULL),
(22, 'delete_invoice', 'Delete Invoice', 'Delete Invoice', 'invoices', NULL, NULL),
(23, 'manage_payment', 'Manage Payment', 'Manage Payment', 'customer payments', NULL, NULL),
(24, 'add_payment', 'Add Payment', 'Add Payment', 'customer payments', NULL, NULL),
(25, 'edit_payment', 'Edit Payment', 'Edit Payment', 'customer payments', NULL, NULL),
(26, 'delete_payment', 'Delete Payment', 'Delete Payment', 'customer payments', NULL, NULL),
(27, 'manage_purchase', 'Manage Purchase', 'Manage Purchase', 'purchases', NULL, NULL),
(28, 'add_purchase', 'Add Purchase', 'Add Purchase', 'purchases', NULL, NULL),
(29, 'edit_purchase', 'Edit Purchase', 'Edit Purchase', 'purchases', NULL, NULL),
(30, 'delete_purchase', 'Delete Purchase', 'Delete Purchase', 'purchases', NULL, NULL),
(31, 'manage_banking_transaction', 'Manage Banking & Transactions', 'Manage Banking & Transactions', 'banking and transactions', NULL, NULL),
(32, 'manage_bank_account', 'Manage Bank Accounts', 'Manage Bank Accounts', 'bank account', NULL, NULL),
(33, 'add_bank_account', 'Add Bank Account', 'Add Bank Account', 'bank account', NULL, NULL),
(34, 'edit_bank_account', 'Edit Bank Account', 'Edit Bank Account', 'bank account', NULL, NULL),
(35, 'delete_bank_account', 'Delete Bank Account', 'Delete Bank Account', 'bank account', NULL, NULL),
(36, 'manage_deposit', 'Manage Deposit', 'Manage Deposit', 'deposits', NULL, NULL),
(37, 'add_deposit', 'Add Deposit', 'Add Deposit', 'deposits', NULL, NULL),
(38, 'edit_deposit', 'Edit Deposit', 'Edit Deposit', 'deposits', NULL, NULL),
(39, 'delete_deposit', 'Delete Deposit', 'Delete Deposit', 'deposits', NULL, NULL),
(40, 'manage_balance_transfer', 'Manage Balance Transfer', 'Manage Balance Transfer', 'balance transfer', NULL, NULL),
(41, 'add_balance_transfer', 'Add Balance Transfer', 'Add Balance Transfer', 'balance transfer', NULL, NULL),
(42, 'edit_balance_transfer', 'Edit Balance Transfer', 'Edit Balance Transfer', 'balance transfer', NULL, NULL),
(43, 'delete_balance_transfer', 'Delete Balance Transfer', 'Delete Balance Transfer', 'balance transfer', NULL, NULL),
(44, 'manage_transaction', 'Manage Transactions', 'Manage Transactions', 'transactions', NULL, NULL),
(45, 'manage_expense', 'Manage Expense', 'Manage Expense', 'expenses', NULL, NULL),
(46, 'add_expense', 'Add Expense', 'Add Expense', 'expenses', NULL, NULL),
(47, 'edit_expense', 'Edit Expense', 'Edit Expense', 'expenses', NULL, NULL),
(48, 'delete_expense', 'Delete Expense', 'Delete Expense', 'expenses', NULL, NULL),
(49, 'manage_report', 'Manage Report', 'Manage Report', 'manage reports', NULL, NULL),
(50, 'manage_stock_on_hand', 'Manage Inventory Stock On Hand', 'Manage Inventory Stock On Hand', 'manage stock on hand', NULL, NULL),
(51, 'manage_sale_report', 'Manage Sales Report', 'Manage Sales Report', 'manage sale report', NULL, NULL),
(52, 'manage_sale_history_report', 'Manage Sales History Report', 'Manage Sales History Report', 'manage sale history report', NULL, NULL),
(53, 'manage_purchase_report', 'Manage Purchase Report', 'Manage Purchase Report', 'manage purchase report', NULL, NULL),
(54, 'manage_team_report', 'Manage Team Member Report', 'Manage Team Member Report', 'manage team report', NULL, NULL),
(55, 'manage_expense_report', 'Manage Expense Report', 'Manage Expense Report', 'manage expense report', NULL, NULL),
(56, 'manage_income_report', 'Manage Income Report', 'Manage Income Report', 'manage income report', NULL, NULL),
(57, 'manage_income_vs_expense', 'Manage Income vs Expense', 'Manage Income vs Expense', 'manage income vs expense', NULL, NULL),
(58, 'manage_setting', 'Manage Settings', 'Manage Settings', 'manage setting', NULL, NULL),
(59, 'manage_company_setting', 'Manage Company Setting', 'Manage Company Setting', 'manage company setting', NULL, NULL),
(60, 'manage_team_member', 'Manage Team Member', 'Manage Team Member', 'team members', NULL, NULL),
(61, 'add_team_member', 'Add Team Member', 'Add Team Member', 'team members', NULL, NULL),
(62, 'edit_team_member', 'Edit Team Member', 'Edit Team Member', 'team members', NULL, NULL),
(63, 'delete_team_member', 'Delete Team Member', 'Delete Team Member', 'team members', NULL, NULL),
(64, 'manage_role', 'Manage Roles', 'Manage Roles', 'roles', NULL, NULL),
(65, 'add_role', 'Add Role', 'Add Role', 'roles', NULL, NULL),
(66, 'edit_role', 'Edit Role', 'Edit Role', 'roles', NULL, NULL),
(67, 'delete_role', 'Delete Role', 'Delete Role', 'roles', NULL, NULL),
(68, 'manage_location', 'Manage Location', 'Manage Location', 'locations', NULL, NULL),
(69, 'add_location', 'Add Location', 'Add Location', 'locations', NULL, NULL),
(70, 'edit_location', 'Edit Location', 'Edit Location', 'locations', NULL, NULL),
(71, 'delete_location', 'Delete Location', 'Delete Location', 'locations', NULL, NULL),
(72, 'manage_general_setting', 'Manage General Settings', 'Manage General Settings', 'manage general settings', NULL, NULL),
(73, 'manage_item_category', 'Manage Item Category', 'Manage Item Category', 'item categories', NULL, NULL),
(74, 'add_item_category', 'Add Item Category', 'Add Item Category', 'item categories', NULL, NULL),
(75, 'edit_item_category', 'Edit Item Category', 'Edit Item Category', 'item categories', NULL, NULL),
(76, 'delete_item_category', 'Delete Item Category', 'Delete Item Category', 'item categories', NULL, NULL),
(77, 'manage_income_expense_category', 'Manage Income Expense Category', 'Manage Income Expense Category', 'income expense categories', NULL, NULL),
(78, 'add_income_expense_category', 'Add Income Expense Category', 'Add Income Expense Category', 'income expense categories', NULL, NULL),
(79, 'edit_income_expense_category', 'Edit Income Expense Category', 'Edit Income Expense Category', 'income expense categories', NULL, NULL),
(80, 'delete_income_expense_category', 'Delete Income Expense Category', 'Delete Income Expense Category', 'income expense categories', NULL, NULL),
(81, 'manage_unit', 'Manage Unit', 'Manage Unit', 'units', NULL, NULL),
(82, 'add_unit', 'Add Unit', 'Add Unit', 'units', NULL, NULL),
(83, 'edit_unit', 'Edit Unit', 'Edit Unit', 'units', NULL, NULL),
(84, 'delete_unit', 'Delete Unit', 'Delete Unit', 'units', NULL, NULL),
(85, 'manage_db_backup', 'Manage Database Backup', 'Manage Database Backup', 'database backups', NULL, NULL),
(86, 'add_db_backup', 'Add Database Backup', 'Add Database Backup', 'database backups', NULL, NULL),
(87, 'delete_db_backup', 'Delete Database Backup', 'Delete Database Backup', 'database backups', NULL, NULL),
(88, 'manage_email_setup', 'Manage Email Setup', 'Manage Email Setup', 'email setup', NULL, NULL),
(89, 'manage_finance', 'Manage Finance', 'Manage Finance', 'finances', NULL, NULL),
(90, 'manage_tax', 'Manage Taxs', 'Manage Taxs', 'taxs', NULL, NULL),
(91, 'add_tax', 'Add Tax', 'Add Tax', 'taxs', NULL, NULL),
(92, 'edit_tax', 'Edit Tax', 'Edit Tax', 'taxs', NULL, NULL),
(93, 'delete_tax', 'Delete Tax', 'Delete Tax', 'taxs', NULL, NULL),
(94, 'manage_currency', 'Manage Currency', 'Manage Currency', 'currencies', NULL, NULL),
(95, 'add_currency', 'Add Currency', 'Add Currency', 'currencies', NULL, NULL),
(96, 'edit_currency', 'Edit Currency', 'Edit Currency', 'currencies', NULL, NULL),
(97, 'delete_currency', 'Delete Currency', 'Delete Currency', 'currencies', NULL, NULL),
(98, 'manage_payment_term', 'Manage Payment Term', 'Manage Payment Term', 'payment terms', NULL, NULL),
(99, 'add_payment_term', 'Add Payment Term', 'Add Payment Term', 'payment terms', NULL, NULL),
(100, 'edit_payment_term', 'Edit Payment Term', 'Edit Payment Term', 'payment terms', NULL, NULL),
(101, 'delete_payment_term', 'Delete Payment Term', 'Delete Payment Term', 'payment terms', NULL, NULL),
(102, 'manage_payment_method', 'Manage Payment Method', 'Manage Payment Method', 'payment methods', NULL, NULL),
(103, 'add_payment_method', 'Add Payment Method', 'Add Payment Method', 'payment methods', NULL, NULL),
(104, 'edit_payment_method', 'Edit Payment Method', 'Edit Payment Method', 'payment methods', NULL, NULL),
(105, 'delete_payment_method', 'Delete Payment Method', 'Delete Payment Method', 'payment methods', NULL, NULL),
(106, 'manage_payment_gateway', 'Manage Payment Method', 'Manage Payment Gateway', 'payment gateway', NULL, NULL),
(107, 'manage_email_template', 'Manage Email Template', 'Manage Email Template', 'email template', NULL, NULL),
(108, 'manage_quotation_email_template', 'Manage Quotation Template', 'Manage Quotation Email Template', 'manage quotation email template', NULL, NULL),
(109, 'manage_invoice_email_template', 'Manage Invoice Email Template', 'Manage Invoice Email Template', 'manage invoice email template', NULL, NULL),
(110, 'manage_payment_email_template', 'Manage Payment Email Template', 'Manage Payment Email Template', 'manage payment email template', NULL, NULL),
(111, 'manage_preference', 'Manage Preference', 'Manage Preference', 'preference', NULL, NULL),
(112, 'manage_barcode', 'Manage barcode/label', 'Manage barcode/label', 'barcode', NULL, NULL),
(113, 'edit_db_backup', 'Download Database Backup', 'Download Database Backup', 'database backups', NULL, NULL),
(114, 'manage_purch_payment', 'Manage Purchase Payment', 'Manage Purchase Payment', 'purchase payments', NULL, NULL),
(115, 'add_purch_payment', 'Add Purchase Payment', 'Add Purchase Payment', 'purchase payments', NULL, NULL),
(116, 'edit_purch_payment', 'Edit Purchase Payment', 'Edit Purchase Payment', 'purchase payments', NULL, NULL),
(117, 'delete_purch_payment', 'Delete Purchase Payment', 'Delete Purchase Payment', 'purchase payments', NULL, NULL),
(118, 'manage_ticket', 'Manage Ticket', 'Manage Ticket', 'tickets', NULL, NULL),
(119, 'add_ticket', 'Add Ticket', 'Add Ticket', 'tickets', NULL, NULL),
(120, 'edit_ticket', 'Edit Ticket', 'Edit Ticket', 'tickets', NULL, NULL),
(121, 'delete_ticket', 'Delete Ticket', 'Delete Ticket', 'tickets', NULL, NULL),
(122, 'manage_project', 'Manage Project', 'Manage Project', 'projects', NULL, NULL),
(123, 'add_project', 'Add Project', 'Add Project', 'projects', NULL, NULL),
(124, 'edit_project', 'Edit Project', 'Edit Project', 'projects', NULL, NULL),
(125, 'delete_project', 'Delete Project', 'Delete Project', 'projects', NULL, NULL),
(126, 'manage_task', 'Manage Task', 'Manage Task', 'tasks', NULL, NULL),
(127, 'add_task', 'Add Task', 'Add Task', 'tasks', NULL, NULL),
(128, 'edit_task', 'Edit Task', 'Edit Task', 'tasks', NULL, NULL),
(129, 'delete_task', 'Delete Task', 'Delete Task', 'tasks', NULL, NULL),
(130, 'manage_milestone', 'Manage Milestone', 'Manage Milestone', 'milestones', NULL, NULL),
(131, 'add_milestone', 'Add Milestone', 'Add Milestone', 'milestones', NULL, NULL),
(132, 'edit_milestone', 'Edit Milestone', 'Edit Milestone', 'milestones', NULL, NULL),
(133, 'delete_milestone', 'Delete Milestone', 'Delete Milestone', 'milestones', NULL, NULL),
(134, 'manage_department', 'Manage Department', 'Manage Department', 'departments', NULL, NULL),
(135, 'add_department', 'Add Department', 'Add Department', 'departments', NULL, NULL),
(136, 'edit_department', 'Edit Department', 'Edit Department', 'departments', NULL, NULL),
(137, 'delete_department', 'Delete Department', 'Delete Department', 'departments', NULL, NULL),
(138, 'manage_other_setting', 'Manage Other Settings', 'Manage Other Settings', 'other settings', NULL, NULL),
(139, 'edit_purchase_type', 'Edit Purchase Type', 'Edit Purchase Type', 'purchase type', NULL, NULL),
(140, 'manage_purch_receive', 'Manage Purchase Receive', 'Manage Purchase Receive', 'purchase receive', NULL, NULL),
(141, 'edit_purchase_receive', 'Edit Purchase Receive', 'Edit Purchase Receive', 'purchase receive', NULL, NULL),
(142, 'delete_purchase_receive', 'Delete Purchase Receive', 'Delete Purchase Receive', 'purchase receive', NULL, NULL),
(143, 'add_calendar_event', 'Add Calendar Event', 'Add Calendar Event', 'calendar', NULL, NULL),
(144, 'edit_calendar_event', 'Edit Calendar Event', 'Edit Calendar Event', 'calendar', NULL, NULL),
(145, 'delete_calendar_event', 'Delete Calendar Event', 'Delete Calendar Event', 'calendar', NULL, NULL),
(146, 'edit_task_comment', 'Edit Task Comment', 'Edit Task Comment', 'task comment', NULL, NULL),
(147, 'delete_task_comment', 'Delete Task Comment', 'Delete Task Comment', 'task comment', NULL, NULL),
(151, 'add_task_assignee', 'Add Task Assignee', 'Add Task Assignee', 'task assignee', NULL, NULL),
(152, 'delete_task_assignee', 'Delete Task Assignee', 'Delete Task Assignee', 'task assignee', NULL, NULL),
(153, 'delete_project_file', 'Delete Project File', 'Delete Project File', 'project file', NULL, NULL),
(154, 'add_project_note', 'Add Project Note', 'Add Project Note', 'project note', NULL, NULL),
(155, 'edit_project_note', 'Edit Project Note', 'Edit Project Note', 'project note', NULL, NULL),
(156, 'manage_general_ledger', 'Manage General Ledger', 'Manage General Ledger', 'general ledger', NULL, NULL),
(157, 'manage_default_income_expense_category', 'Default Income Expense Category', 'Default Income Expense Category', 'default income expense category', NULL, NULL),
(158, 'manage_sms_setup', ' Manage SMS Setup', ' Manage SMS Setup', 'sms setup', NULL, NULL),
(159, 'own_quotation', 'View Own Quotations', 'View Own Quotations', 'quotations', NULL, NULL),
(160, 'own_invoice', 'View Own Invoice', 'View Own Invoice', 'invoices', NULL, NULL),
(161, 'own_payment', 'View Own Payment', 'View Own Payment', 'customer payments', NULL, NULL),
(162, 'own_purchase', 'View Own Purchase', 'View Own Purchase', 'purchases', NULL, NULL),
(163, 'own_deposit', 'View Own Deposit', 'View Own Deposit', 'deposits', NULL, NULL),
(164, 'own_balance_transfer', 'View Own Balance Transfer', 'View Own Balance Transfer', 'balance transfer', NULL, NULL),
(165, 'own_transaction', 'View Own Transactions', 'View Own Transactions', 'transactions', NULL, NULL),
(166, 'own_expense', 'View Own Expense', 'View Own Expense', 'expenses', NULL, NULL),
(167, 'own_purchase_payment', 'View Own Purchase Payment', 'View Own Purchase Payment', 'purchase payments', NULL, NULL),
(168, 'own_purchase_receive', 'View Own Purchase Receive', 'View Own Purchase Receive', 'purchase receive', NULL, NULL),
(169, 'delete_stock_transfer', 'Delete Stock Transfer', 'Delete Stock Transfer', 'stock transfer', NULL, NULL),
(170, 'edit_stock_adjustment', 'Edit Stock Adjustment', 'Edit Stock Adjustment', 'Stock Adjustment', NULL, NULL),
(171, 'delete_stock_adjustment', 'Delete Stock Adjustment', 'Delete Stock Adjustment', 'Stock Adjustment', NULL, NULL),
(172, 'own_task', 'View Own Task', 'View Own Task', 'tasks', NULL, NULL),
(173, 'own_ticket', 'View Own Ticket', 'View Own Ticket', 'tickets', NULL, NULL),
(174, 'own_project', 'View Own Project', 'View Own Project', 'projects', NULL, NULL),
(175, 'manage_language', 'Manage Languages', 'Manage Languages', 'Languages', NULL, NULL),
(176, 'add_language', 'Add Language', 'Add Language', 'Languages', NULL, NULL),
(177, 'delete_language', 'Delete Languages', 'Delete Languages', 'Languages', NULL, NULL),
(178, 'edit_language', 'Edit Language', 'Edit Language', 'Languages', NULL, NULL),
(179, 'add_task_timer', 'Add Task Timer', 'Add Task Timer', 'task timer', NULL, NULL),
(180, 'delete_task_timer', 'Delete Task Timer', 'Delete Task Timer', 'task timer', NULL, NULL),
(191, 'add_customer_note', 'Add Customer Note', 'Add Customer Note', 'Customer Note', NULL, NULL),
(192, 'edit_customer_note', 'Edit Customer Note', 'Edit Customer Note', 'Customer Note', NULL, NULL),
(193, 'delete_customer_note', 'Delete Customer Note', 'Delete Customer Note', 'Customer Note', NULL, NULL),
(194, 'manage_lead_status', 'Manage Lead Status', 'Manage Lead Status', 'Lead Status', NULL, NULL),
(195, 'add_lead_status', 'Add Lead Status', 'Add Lead Status', 'Lead Status', NULL, NULL),
(196, 'edit_lead_status', 'Edit Lead Status', 'Edit Lead Status', 'Lead Status', NULL, NULL),
(197, 'delete_lead_status', 'Delete Lead Status', 'Delete Lead Status', 'Lead Status', NULL, NULL),
(198, 'manage_lead_source', 'Manage Lead Source', 'Manage Lead Source', 'Lead Source', NULL, NULL),
(199, 'add_lead_source', 'Add Lead Source', 'Add Lead Source', 'Lead Source', NULL, NULL),
(200, 'edit_lead_source', 'Edit Lead Source', 'Edit Lead Source', 'Lead Source', NULL, NULL),
(201, 'delete_lead_source', 'Delete Lead Source', 'Delete Lead Source', 'Lead Source', NULL, NULL),
(202, 'manage_lead', 'Manage Lead', 'Manage Lead', 'Leads', NULL, NULL),
(203, 'add_lead', 'Add Lead', 'Add Lead', 'Leads', NULL, NULL),
(204, 'edit_lead', 'Edit Lead', 'Edit Lead', 'Leads', NULL, NULL),
(205, 'delete_lead', 'Delete Lead', 'Delete Lead', 'Leads', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(19, 2),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(40, 6),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(44, 5),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(81, 5),
(82, 1),
(82, 5),
(83, 1),
(83, 5),
(84, 1),
(84, 5),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(119, 2),
(120, 1),
(121, 1),
(121, 2),
(122, 1),
(123, 1),
(123, 2),
(124, 1),
(124, 2),
(125, 1),
(125, 2),
(126, 1),
(127, 1),
(127, 2),
(128, 1),
(128, 2),
(129, 1),
(129, 2),
(130, 1),
(130, 2),
(131, 1),
(131, 2),
(132, 1),
(132, 2),
(133, 1),
(133, 2),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(144, 6),
(145, 1),
(146, 1),
(147, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(154, 2),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(162, 2),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 2),
(173, 2),
(174, 2),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(191, 1),
(192, 1),
(193, 1),
(194, 1),
(195, 1),
(196, 1),
(197, 1),
(198, 1),
(198, 9),
(199, 1),
(199, 9),
(200, 1),
(201, 1),
(202, 1),
(203, 1),
(204, 1),
(205, 1);

-- --------------------------------------------------------

--
-- Table structure for table `preference`
--

DROP TABLE IF EXISTS `preference`;
CREATE TABLE IF NOT EXISTS `preference` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `preference`
--

INSERT INTO `preference` (`id`, `category`, `field`, `value`) VALUES
(1, 'preference', 'row_per_page', '25'),
(2, 'preference', 'date_format', '0'),
(3, 'preference', 'date_sepa', '/'),
(4, 'preference', 'soft_name', 'goBilling'),
(5, 'company', 'site_short_name', 'TS'),
(6, 'preference', 'percentage', '0'),
(7, 'preference', 'quantity', '0'),
(8, 'preference', 'date_format_type', 'yyyy/mm/dd'),
(9, 'company', 'company_name', 'Techvillage Support'),
(10, 'company', 'company_email', 'admin@techvill.net'),
(11, 'company', 'company_phone', '123465798'),
(12, 'company', 'company_street', 'City Hall Park Path'),
(13, 'company', 'company_city', 'New York'),
(14, 'company', 'company_state', 'New York'),
(15, 'company', 'company_zipCode', '10007'),
(16, 'company', 'company_country_id', 'United States'),
(17, 'company', 'dflt_lang', 'en'),
(18, 'company', 'dflt_currency_id', '2'),
(19, 'company', 'sates_type_id', '1'),
(21, 'company', 'company_gstin', '15'),
(22, 'gl_account', 'supplier_debit_gl_acc', '6'),
(23, 'gl_account', 'supplier_credit_gl_acc', '4'),
(24, 'gl_account', 'customer_debit_gl_acc', '4'),
(25, 'gl_account', 'customer_credit_gl_acc', '4'),
(26, 'gl_account', 'user_transaction_debit_gl_acc', '2'),
(27, 'gl_account', 'user_transaction_credit_gl_acc', '3'),
(28, 'gl_account', 'bank_charge_gl_acc', '1'),
(29, 'preference', 'default_timezone', 'Africa/Accra'),
(38, 'company', 'company_logo', '1_1548592437.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--

DROP TABLE IF EXISTS `priorities`;
CREATE TABLE IF NOT EXISTS `priorities` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`id`, `name`) VALUES
(1, 'Low'),
(2, 'Medium'),
(3, 'High');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_detail` longtext COLLATE utf8_unicode_ci,
  `project_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `charge_type` int(10) UNSIGNED NOT NULL,
  `project_begin_date` date DEFAULT NULL,
  `project_due_date` date DEFAULT NULL,
  `improvement` int(11) NOT NULL,
  `improvement_from_tasks` int(11) DEFAULT NULL COMMENT '1=yes, 0=no',
  `project_completed_date` date DEFAULT NULL,
  `project_cost` decimal(8,2) DEFAULT NULL,
  `per_hour_project_scale` decimal(8,2) DEFAULT NULL,
  `estimated_hours` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `projects_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_detail`, `project_name`, `project_type`, `customer_id`, `user_id`, `position`, `charge_type`, `project_begin_date`, `project_due_date`, `improvement`, `improvement_from_tasks`, `project_completed_date`, `project_cost`, `per_hour_project_scale`, `estimated_hours`, `created_at`) VALUES
(19, '<p>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book. It ha\r\n\r\n\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book. It ha\r\n\r\n<br></p>', 'E-Commerce Project', 'in_house', 0, 1, 1, 1, '2019-01-10', NULL, 50, 1, NULL, '500000.00', NULL, '2000.00', '2019-01-10 06:44:56'),
(20, '<p>projects details</p>', 'payMoney', 'product', 0, 1, 1, 1, '2019-01-10', NULL, 20, 1, NULL, '1000.00', NULL, '5000.00', '2019-01-10 06:58:41'),
(21, '<p>test</p>', 'goBilling', 'customer', 5, 18, 3, 1, '2019-01-22', NULL, 40, 1, NULL, '100.00', NULL, '5000.00', '2019-01-10 07:55:55'),
(26, '<p>Details<br></p>', 'Project Email Test', 'customer', 2, 1, 1, 1, '2019-01-10', NULL, 0, 1, NULL, NULL, NULL, NULL, '2019-01-10 09:56:21'),
(27, '<p>\r\n\r\nWe need a new module called Recurring Invoice. It is an invoice template when you create a recurring invoice. \r\n\r\n<br></p><p>\r\n\r\nWe need a new module called Recurring Invoice. It is an invoice template when you create a recurring invoice. \r\n\r\n<br></p>', 'Pay Mobile App', 'customer', 5, 1, 1, 1, '2019-01-23', NULL, 25, 1, NULL, '100.00', NULL, '10.00', '2019-01-20 13:11:32'),
(28, '<p>gfjgfj</p>', 'Customization of pay app', 'customer', 5, 1, 2, 2, '2019-01-23', '2019-01-30', 0, 1, NULL, NULL, '100.00', '1500.00', '2019-01-23 08:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

DROP TABLE IF EXISTS `project_members`;
CREATE TABLE IF NOT EXISTS `project_members` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_members_project_id_foreign` (`project_id`),
  KEY `project_members_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `project_id`, `user_id`) VALUES
(41, 20, 18),
(42, 21, 18),
(43, 19, 15),
(45, 19, 18),
(50, 26, 18),
(51, 20, 17),
(55, 21, 1),
(56, 21, 15),
(57, 21, 17),
(58, 27, 1),
(59, 27, 15),
(60, 27, 18),
(61, 28, 18),
(62, 27, 17);

-- --------------------------------------------------------

--
-- Table structure for table `project_settings`
--

DROP TABLE IF EXISTS `project_settings`;
CREATE TABLE IF NOT EXISTS `project_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int(10) UNSIGNED NOT NULL,
  `setting_label` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `setting_value` enum('on','off') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_settings_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_settings`
--

INSERT INTO `project_settings` (`id`, `project_id`, `setting_label`, `setting_value`) VALUES
(140, 21, 'customer_create_task', 'on'),
(141, 21, 'customer_view_comment', 'on'),
(142, 21, 'customer_upload_attachment', 'on'),
(143, 21, 'customer_view_milestone', 'on'),
(144, 21, 'customer_view_activity', 'on'),
(148, 27, 'customer_add_comment', 'on'),
(149, 27, 'customer_view_comment', 'on'),
(150, 27, 'customer_view_attachment', 'on'),
(151, 27, 'customer_upload_attachment', 'on'),
(152, 27, 'customer_view_assignee', 'on'),
(153, 27, 'customer_view_milestone', 'on'),
(155, 27, 'customer_view_timesheet', 'on'),
(156, 27, 'customer_view_activity', 'on'),
(159, 27, 'customer_view_logged_time', 'on'),
(160, 21, 'customer_view_task', 'on'),
(161, 27, 'customer_view_task', 'on'),
(162, 27, 'customer_create_task', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `project_status`
--

DROP TABLE IF EXISTS `project_status`;
CREATE TABLE IF NOT EXISTS `project_status` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_status`
--

INSERT INTO `project_status` (`id`, `name`) VALUES
(1, 'Not Started'),
(2, 'In Progress'),
(3, 'On Hold'),
(4, 'Cancelled'),
(5, 'Finished');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_prices`
--

DROP TABLE IF EXISTS `purchase_prices`;
CREATE TABLE IF NOT EXISTS `purchase_prices` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(10) UNSIGNED NOT NULL,
  `price` double DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `purchase_prices_item_id_foreign` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `purchase_prices`
--

INSERT INTO `purchase_prices` (`id`, `item_id`, `price`) VALUES
(1, 1, NULL),
(2, 2, 5000),
(3, 3, 0),
(4, 4, NULL),
(5, 5, NULL),
(6, 6, NULL),
(7, 7, 2),
(8, 8, 0),
(9, 9, 20),
(10, 10, 0),
(11, 11, 0),
(12, 12, 150),
(13, 13, 0.22),
(14, 14, NULL),
(15, 15, NULL),
(16, 16, NULL),
(17, 17, NULL),
(18, 18, 0),
(19, 19, 10),
(20, 20, 0),
(21, 21, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_tax`
--

DROP TABLE IF EXISTS `purchase_tax`;
CREATE TABLE IF NOT EXISTS `purchase_tax` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purch_id` int(11) UNSIGNED NOT NULL,
  `purch_details_id` int(11) UNSIGNED NOT NULL,
  `tax_id` double NOT NULL,
  `tax_amount` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purch_id` (`purch_id`),
  KEY `purch_details_id` (`purch_details_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_tax`
--

INSERT INTO `purchase_tax` (`id`, `purch_id`, `purch_details_id`, `tax_id`, `tax_amount`) VALUES
(1, 3, 3, 4, 100),
(2, 4, 4, 4, 100),
(3, 5, 5, 4, 10),
(4, 6, 6, 4, 150),
(17, 7, 7, 4, 5),
(18, 7, 7, 6, 20),
(19, 7, 8, 4, 5),
(20, 7, 8, 6, 20),
(23, 8, 9, 4, 38095.238095238106),
(24, 8, 10, 4, 4.761904761904759),
(25, 9, 12, 4, 25);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_type`
--

DROP TABLE IF EXISTS `purchase_type`;
CREATE TABLE IF NOT EXISTS `purchase_type` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `defaults` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `purchase_type`
--

INSERT INTO `purchase_type` (`id`, `type`, `defaults`) VALUES
(1, 'auto', 0),
(2, 'manually', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purch_orders`
--

DROP TABLE IF EXISTS `purch_orders`;
CREATE TABLE IF NOT EXISTS `purch_orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `inv_type` enum('quantity','hours','amount') COLLATE utf8_unicode_ci NOT NULL,
  `discount_on` enum('before','after') COLLATE utf8_unicode_ci NOT NULL,
  `tax_type` enum('exclusive','inclusive') COLLATE utf8_unicode_ci NOT NULL,
  `m_tax` tinyint(2) NOT NULL,
  `m_detail_description` tinyint(2) NOT NULL,
  `m_item_discount` tinyint(2) NOT NULL,
  `m_shn` tinyint(2) NOT NULL,
  `m_sub_discount` tinyint(2) NOT NULL,
  `m_sub_shipping` tinyint(2) NOT NULL,
  `m_sub_custom_amount` tinyint(2) NOT NULL,
  `s_other_discount` double DEFAULT NULL,
  `s_other_discount_type` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `s_shipping` double DEFAULT NULL,
  `s_custom_amount_title` varchar(199) COLLATE utf8_unicode_ci DEFAULT NULL,
  `s_custom_amount` double NOT NULL,
  `currency_id` int(11) NOT NULL,
  `exchange_rate` double NOT NULL,
  `discount_type` enum('flat','percentage') COLLATE utf8_unicode_ci NOT NULL,
  `purchase_receive_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `comment_check` tinyint(4) NOT NULL DEFAULT '0',
  `ord_date` date NOT NULL,
  `reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `into_stock_location` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `paid_amount` double NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purch_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purch_orders_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `purch_orders`
--

INSERT INTO `purch_orders` (`id`, `supplier_id`, `user_id`, `inv_type`, `discount_on`, `tax_type`, `m_tax`, `m_detail_description`, `m_item_discount`, `m_shn`, `m_sub_discount`, `m_sub_shipping`, `m_sub_custom_amount`, `s_other_discount`, `s_other_discount_type`, `s_shipping`, `s_custom_amount_title`, `s_custom_amount`, `currency_id`, `exchange_rate`, `discount_type`, `purchase_receive_type`, `comments`, `comment_check`, `ord_date`, `reference`, `into_stock_location`, `total`, `paid_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 15, 'quantity', 'before', 'exclusive', 1, 1, 0, 0, 1, 1, 0, NULL, '%', NULL, NULL, 0, 2, 1, 'flat', 'auto', NULL, 0, '2018-12-27', 'PO-0001', 'PL', 500, 500, '2018-12-26 23:17:30', '2018-12-27 00:02:11'),
(2, 1, 1, 'quantity', 'before', 'exclusive', 1, 1, 0, 0, 1, 1, 0, NULL, '%', NULL, NULL, 0, 2, 1, 'flat', 'auto', NULL, 0, '2018-12-27', 'PO-0002', 'PL', 4000, 4000, '2018-12-26 23:18:14', '2018-12-27 00:02:42'),
(3, 1, 1, 'quantity', 'after', 'exclusive', 1, 0, 0, 0, 1, 1, 1, 4, '%', NULL, NULL, 0, 2, 1, 'flat', 'manually', 'Test purchases.', 0, '2019-01-03', 'PO-0003', 'PL', 1923.81, 1050, '2019-01-03 05:46:38', '2019-01-03 06:46:32'),
(4, 2, 1, 'quantity', 'before', 'exclusive', 1, 1, 0, 0, 1, 1, 0, 3, '%', 1, NULL, 0, 1, 0.12, 'flat', 'manually', 'test', 1, '2019-01-06', 'PO-0004', 'PL', 1941, 0, '2019-01-06 00:24:20', '2019-01-06 00:42:31'),
(5, 2, 1, 'quantity', 'before', 'exclusive', 1, 1, 0, 0, 1, 1, 0, 2, '%', NULL, NULL, 0, 1, 0.12, 'flat', 'manually', 'test', 1, '2019-01-07', 'PO-0005', 'JA', 196, 0, '2019-01-07 04:21:43', NULL),
(6, 2, 1, 'quantity', 'before', 'exclusive', 1, 1, 0, 0, 1, 1, 0, 2, '%', 2, NULL, 0, 1, 0.12, 'flat', 'auto', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries', 1, '2019-01-07', 'PO-0006', 'PL', 2942, 1000, '2019-01-07 04:39:39', '2019-01-07 04:40:22'),
(7, 1, 1, 'quantity', 'before', 'exclusive', 1, 1, 0, 0, 1, 1, 0, 10, '$', 5, NULL, 0, 2, 1, 'flat', 'auto', NULL, 0, '2019-01-14', 'PO-0007', 'PL', 245, 0, '2019-01-13 23:34:15', '2019-01-14 00:48:04'),
(8, 3, 1, 'quantity', 'before', 'inclusive', 1, 0, 1, 0, 1, 1, 0, 4, '%', 10, NULL, 0, 4, 0.11, 'flat', 'manually', 'We need a new module called Recurring Invoice. It is an invoice template when you create a recurring invoice.', 1, '2019-01-22', 'PO-0008', 'PL', 768102.7, 500, '2019-01-21 01:28:03', '2019-01-21 02:50:01'),
(9, 1, 1, 'quantity', 'before', 'exclusive', 1, 0, 0, 0, 0, 0, 0, 0, '%', 0, NULL, 0, 2, 1, 'flat', 'auto', NULL, 0, '2019-01-31', 'PO-0009', 'PL', 525, 525, '2019-01-31 04:04:45', '2019-01-31 04:40:34'),
(10, 2, 1, 'quantity', 'before', 'exclusive', 1, 0, 0, 0, 1, 1, 0, 0.85, '%', NULL, NULL, 0, 1, 70, 'flat', 'manually', NULL, 0, '2019-01-31', 'PO-0010', 'PL', 24787.5, 24700, '2019-01-31 05:16:48', '2019-01-31 07:18:40'),
(11, 3, 1, 'quantity', 'before', 'inclusive', 1, 1, 0, 0, 1, 1, 0, 2, '%', NULL, NULL, 0, 4, 50, 'flat', 'manually', NULL, 0, '2019-01-31', 'PO-0011', 'PL', 857.5, 857.5, '2019-01-31 06:26:57', '2019-01-31 06:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `purch_order_details`
--

DROP TABLE IF EXISTS `purch_order_details`;
CREATE TABLE IF NOT EXISTS `purch_order_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purch_order_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discount` double NOT NULL,
  `discount_type` enum('%','$') COLLATE utf8_unicode_ci NOT NULL,
  `shn` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sorting_no` tinyint(2) NOT NULL,
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `tax_type_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  `is_inventory` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purch_order_details_purch_order_id_foreign` (`purch_order_id`),
  KEY `purch_order_details_item_id_foreign` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `purch_order_details`
--

INSERT INTO `purch_order_details` (`id`, `purch_order_id`, `item_id`, `description`, `item_name`, `discount`, `discount_type`, `shn`, `sorting_no`, `qty_invoiced`, `discount_percent`, `unit_price`, `tax_type_id`, `quantity_ordered`, `quantity_received`, `is_inventory`) VALUES
(1, 1, 2, NULL, 'Samsung Galaxy S7', 0, '%', NULL, 1, 1, 0, 500, NULL, 1, 1, 1),
(2, 2, 2, NULL, 'Samsung Galaxy S7', 0, '%', NULL, 1, 1, 0, 4000, NULL, 1, 1, 1),
(3, 3, 9, NULL, 'Blackberry', 0, '%', NULL, 1, 100, 0, 20, NULL, 100, 100, 1),
(4, 4, 9, NULL, 'Blackberry', 0, '', '', 1, 100, 0, 20, NULL, 100, 0, 1),
(5, 5, 7, NULL, 'test', 0, '%', NULL, 1, 10, 0, 20, NULL, 10, 0, 1),
(6, 6, 12, NULL, 'Nokia N3', 0, '%', NULL, 1, 20, 0, 150, NULL, 20, 20, 1),
(7, 7, 9, NULL, 'Blackberry', 0, '', '', 1, 1, 0, 100, NULL, 1, 1, 1),
(8, 7, 11, NULL, 'blackberry2', 0, '', '', 2, 1, 0, 100, NULL, 1, 1, 1),
(9, 8, 9, '', 'Blackberry', 2, '$', '', 1, 100, 0, 8000, NULL, 100, 100, 1),
(10, 8, 2, '', 'Samsung Galaxy S7', 2, '%', '', 2, 1, 0, 100, NULL, 1, 1, 1),
(11, 8, 10, '', 'blackberry1', 3, '%', '', 3, 1, 0, 0.75, NULL, 1, 1, 1),
(12, 9, 19, NULL, 'Oppo F7', 0, '%', NULL, 1, 50, 0, 10, NULL, 50, 50, 1),
(13, 10, 9, NULL, 'Blackberry', 0, '%', NULL, 1, 500, 0, 50, NULL, 500, 500, 1),
(14, 11, 9, NULL, 'Blackberry', 0, '%', NULL, 1, 3500, 0, 0.25, NULL, 3500, 3500, 1);

-- --------------------------------------------------------

--
-- Table structure for table `receive_orders`
--

DROP TABLE IF EXISTS `receive_orders`;
CREATE TABLE IF NOT EXISTS `receive_orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purch_order_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `receive_date` date NOT NULL,
  `order_receive_no` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receive_orders_purch_order_id_foreign` (`purch_order_id`),
  KEY `receive_orders_user_id_foreign` (`user_id`),
  KEY `receive_orders_supplier_id_foreign` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `receive_orders`
--

INSERT INTO `receive_orders` (`id`, `purch_order_id`, `user_id`, `supplier_id`, `reference`, `location`, `receive_date`, `order_receive_no`, `created_at`, `updated_at`) VALUES
(1, 1, 15, 1, 'PO-0001', 'PL', '2018-12-27', '', NULL, NULL),
(2, 2, 1, 1, 'PO-0002', 'PL', '2018-12-27', '', NULL, NULL),
(3, 3, 1, 1, 'PO-0003', 'PL', '2019-01-03', '', NULL, NULL),
(4, 6, 1, 2, 'PO-0006', 'PL', '2019-01-07', '', NULL, NULL),
(5, 7, 1, 1, 'PO-0007', 'PL', '2019-01-14', '', NULL, NULL),
(6, 8, 1, 3, 'PO-0008', 'PL', '2019-01-21', 'Order rc-001', NULL, NULL),
(7, 8, 1, 3, 'PO-0008', 'PL', '2019-01-21', '', NULL, NULL),
(8, 9, 1, 1, 'PO-0009', 'PL', '2019-01-31', '', NULL, NULL),
(9, 10, 1, 2, 'PO-0010', 'PL', '2019-01-31', 'manually received', NULL, NULL),
(10, 11, 1, 3, 'PO-0011', 'PL', '2019-01-31', 'manually received', NULL, NULL),
(11, 11, 1, 3, 'PO-0011', 'PL', '2019-01-31', 'Received 3000 Blackberry phone', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `receive_order_details`
--

DROP TABLE IF EXISTS `receive_order_details`;
CREATE TABLE IF NOT EXISTS `receive_order_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purch_order_id` int(10) UNSIGNED NOT NULL,
  `po_details_id` int(10) UNSIGNED NOT NULL,
  `receive_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` double NOT NULL,
  `quantity` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `receive_order_details_purch_order_id_foreign` (`purch_order_id`),
  KEY `receive_order_details_receive_id_foreign` (`receive_id`),
  KEY `receive_order_details_item_id_foreign` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `receive_order_details`
--

INSERT INTO `receive_order_details` (`id`, `purch_order_id`, `po_details_id`, `receive_id`, `item_id`, `item_name`, `unit_price`, `quantity`) VALUES
(1, 1, 0, 1, 2, 'Samsung Galaxy S7', 500, 1),
(2, 2, 0, 2, 2, 'Samsung Galaxy S7', 4000, 1),
(3, 3, 3, 3, 9, 'Blackberry', 20, 100),
(4, 6, 0, 4, 12, 'Nokia N3', 150, 20),
(5, 7, 0, 5, 9, 'Blackberry', 100, 1),
(6, 7, 0, 5, 11, 'blackberry2', 100, 1),
(7, 8, 9, 6, 9, 'Blackberry', 8000, 99),
(8, 8, 10, 6, 2, 'Samsung Galaxy S7', 100, 1),
(9, 8, 11, 6, 10, 'blackberry1', 0.75, 1),
(10, 8, 9, 7, 9, 'Blackberry', 8000, 1),
(11, 9, 0, 8, 19, 'Oppo F7', 10, 50),
(12, 10, 13, 9, 9, 'Blackberry', 50, 500),
(13, 11, 14, 10, 9, 'Blackberry', 0.25, 500),
(14, 11, 14, 11, 9, 'Blackberry', 0.25, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
CREATE TABLE IF NOT EXISTS `reference` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int(10) UNSIGNED NOT NULL,
  `reference` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reference`
--

INSERT INTO `reference` (`id`, `type`, `reference`) VALUES
(1, 15, '001/2018'),
(2, 15, '002/2018'),
(3, 15, '003/2018'),
(4, 15, '004/2018'),
(5, 15, '005/2018'),
(6, 15, '006/2018'),
(7, 15, '007/2018'),
(8, 15, '008/2018'),
(9, 15, '009/2018'),
(10, 15, '010/2018'),
(11, 15, '011/2018'),
(12, 15, '012/2018'),
(13, 15, '013/2018'),
(14, 15, '014/2018'),
(15, 15, '015/2018'),
(16, 15, '016/2018'),
(17, 15, '017/2018'),
(18, 15, '018/2018'),
(19, 11, '001/2018'),
(20, 15, '019/2018'),
(21, 15, '020/2018'),
(22, 15, '021/2018'),
(23, 15, '022/2018'),
(24, 15, '023/2018'),
(25, 15, '024/2018'),
(26, 15, '025/2018'),
(27, 15, '026/2018'),
(28, 15, '027/2018'),
(29, 15, '028/2018'),
(30, 15, '029/2018'),
(31, 15, '030/2018'),
(32, 15, '031/2018'),
(33, 15, '032/2018'),
(34, 15, '033/2018'),
(35, 15, '034/2018'),
(36, 15, '035/2018'),
(37, 15, '036/2018'),
(38, 15, '037/2018'),
(39, 15, '038/2018'),
(40, 15, '039/2018'),
(41, 15, '040/2018'),
(42, 15, '041/2018'),
(43, 15, '042/2018'),
(44, 15, '043/2018'),
(45, 15, '044/2018'),
(46, 15, '045/2018'),
(47, 15, '046/2018'),
(48, 15, '047/2018'),
(49, 11, '002/2018'),
(50, 13, '001/2018'),
(51, 13, '002/2018'),
(52, 12, '001/2018'),
(53, 12, '002/2018'),
(54, 16, '001/2018'),
(55, 16, '002/2018'),
(56, 15, '048/2018'),
(57, 15, '049/2018'),
(58, 15, '050/2018'),
(59, 15, '051/2018'),
(60, 15, '052/2018'),
(61, 15, '053/2019'),
(62, 16, '003/2019'),
(63, 16, '004/2019'),
(64, 15, '054/2019'),
(65, 15, '055/2019'),
(66, 15, '056/2019'),
(67, 15, '057/2019'),
(68, 15, '058/2019'),
(69, 15, '059/2019'),
(70, 15, '060/2019'),
(71, 15, '061/2019'),
(72, 15, '062/2019'),
(73, 15, '063/2019'),
(74, 15, '064/2019'),
(75, 15, '065/2019'),
(76, 15, '066/2019'),
(77, 15, '067/2019'),
(78, 16, '005/2019'),
(79, 15, '068/2019'),
(80, 15, '069/2019'),
(81, 15, '070/2019'),
(82, 16, '006/2019'),
(83, 15, '071/2019'),
(84, 11, '003/2019'),
(85, 15, '072/2019'),
(86, 15, '073/2019'),
(87, 15, '074/2019'),
(88, 15, '075/2019'),
(89, 15, '076/2019'),
(90, 15, '077/2019'),
(91, 15, '078/2019'),
(92, 15, '079/2019'),
(93, 15, '080/2019'),
(94, 15, '081/2019'),
(95, 15, '082/2019'),
(96, 15, '083/2019'),
(97, 15, '084/2019'),
(98, 15, '085/2019'),
(99, 15, '086/2019'),
(100, 15, '087/2019'),
(101, 15, '088/2019'),
(102, 15, '089/2019'),
(105, 15, '090/2019'),
(106, 15, '091/2019'),
(107, 15, '092/2019'),
(110, 15, '093/2019'),
(111, 15, '094/2019'),
(112, 16, '007/2019'),
(113, 16, '008/2019'),
(114, 15, '095/2019'),
(115, 16, '009/2019'),
(116, 16, '010/2019'),
(117, 16, '011/2019'),
(118, 12, '003/2019'),
(119, 12, '004/2019');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'Admin User', NULL, NULL),
(2, 'test', 'test', 'test', NULL, NULL),
(9, 'user role', 'user role', 'user roleuser role', '2019-01-27 06:40:40', '2019-01-27 06:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 2),
(15, 1),
(16, 2),
(17, 1),
(18, 2),
(19, 1),
(20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

DROP TABLE IF EXISTS `sales_orders`;
CREATE TABLE IF NOT EXISTS `sales_orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `trans_type` mediumint(9) NOT NULL,
  `invoice_type` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `discount_type` enum('flat','percentage') COLLATE utf8_unicode_ci NOT NULL,
  `tax_type` enum('exclusive','inclusive') COLLATE utf8_unicode_ci NOT NULL,
  `reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `customer_ref` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_reference_id` int(11) NOT NULL,
  `order_reference` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `comment_check` tinyint(4) NOT NULL,
  `ord_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `order_type` int(11) NOT NULL,
  `from_stk_loc` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_type` varchar(12) COLLATE utf8_unicode_ci NOT NULL COMMENT 'quantity, hours, amount only (diff from invoice_type)',
  `discount_on` enum('before','after') COLLATE utf8_unicode_ci NOT NULL,
  `currency_id` int(10) NOT NULL,
  `exchange_rate` double NOT NULL,
  `m_tax` tinyint(2) NOT NULL,
  `m_detail_description` tinyint(2) NOT NULL,
  `m_item_discount` tinyint(2) NOT NULL,
  `m_shn` tinyint(2) NOT NULL,
  `m_sub_discount` tinyint(2) NOT NULL,
  `m_sub_shipping` tinyint(2) NOT NULL,
  `m_sub_custom_amount` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `s_other_discount` double DEFAULT NULL,
  `s_other_discount_type` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `s_shipping` double DEFAULT NULL,
  `s_custom_amount_title` varchar(199) COLLATE utf8_unicode_ci DEFAULT NULL,
  `s_custom_amount` double NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `paid_amount` double NOT NULL DEFAULT '0',
  `payment_term` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_orders_branch_id_foreign` (`branch_id`),
  KEY `sales_orders_customer_id_foreign` (`customer_id`),
  KEY `sales_orders_payment_id_foreign` (`payment_id`),
  KEY `sales_orders_user_id_foreign` (`user_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `trans_type`, `invoice_type`, `project_id`, `customer_id`, `branch_id`, `user_id`, `discount_type`, `tax_type`, `reference`, `customer_ref`, `order_reference_id`, `order_reference`, `comments`, `comment_check`, `ord_date`, `due_date`, `order_type`, `from_stk_loc`, `payment_id`, `inv_type`, `discount_on`, `currency_id`, `exchange_rate`, `m_tax`, `m_detail_description`, `m_item_discount`, `m_shn`, `m_sub_discount`, `m_sub_shipping`, `m_sub_custom_amount`, `s_other_discount`, `s_other_discount_type`, `s_shipping`, `s_custom_amount_title`, `s_custom_amount`, `total`, `paid_amount`, `payment_term`, `created_at`, `updated_at`) VALUES
(1, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0001', NULL, 0, NULL, 'Delivery on 31/12/2018', 1, '2018-12-09', NULL, 0, 'PL', '2', 'hours', 'before', 1, 78, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10500, 0, 0, '2018-12-09 00:21:02', NULL),
(2, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0001', NULL, 1, 'QN-0001', 'Delivery on 31/12/2018', 1, '2018-12-09', NULL, 0, 'PL', '2', 'hours', 'before', 1, 78, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10500, 0, 1, '2018-12-09 00:21:02', '2018-12-11 01:28:02'),
(3, 201, 'indirectOrder', 2, 2, 2, 1, 'flat', 'exclusive', 'QN-0002', NULL, 0, NULL, NULL, 0, '2018-12-09', NULL, 0, 'PL', '2', 'hours', 'before', 1, 78, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 500, 0, 0, '2018-12-09 03:58:45', NULL),
(5, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0003', NULL, 0, NULL, NULL, 0, '2018-12-09', NULL, 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10800, 0, 0, '2018-12-09 06:24:35', NULL),
(6, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0003', NULL, 5, 'QN-0003', NULL, 0, '2018-12-22', '2019-01-06', 0, 'PL', '2', 'hours', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 28350, 28350, 1, '2018-12-09 06:24:35', '2018-12-23 04:06:58'),
(7, 201, 'indirectOrder', NULL, 2, 2, 1, 'flat', 'exclusive', 'QN-0004', NULL, 0, NULL, NULL, 0, '2018-12-09', NULL, 0, 'PL', '2', 'hours', 'after', 1, 78, 1, 1, 1, 0, 0, 0, '0', 0, '%', 0, NULL, 0, 44415, 0, 0, '2018-12-09 06:43:52', NULL),
(8, 202, 'directInvoice', NULL, 2, 2, 1, 'flat', 'exclusive', 'INV-0004', NULL, 7, 'QN-0004', NULL, 0, '2018-12-09', NULL, 0, 'PL', '2', 'hours', 'before', 1, 78, 1, 1, 1, 0, 0, 1, '0', 0, '%', 0, NULL, 0, 47250, 2762, 1, '2018-12-09 06:43:52', '2018-12-26 23:06:20'),
(9, 201, 'indirectOrder', NULL, 4, 4, 1, 'flat', 'exclusive', 'QN-0005', NULL, 0, NULL, NULL, 0, '2018-12-10', NULL, 0, 'PL', '2', 'hours', 'before', 1, 79, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 11500, 0, 0, '2018-12-09 23:15:54', NULL),
(11, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0006', NULL, 0, NULL, NULL, 0, '2018-12-10', NULL, 0, 'PL', '2', 'hours', 'before', 1, 79, 1, 1, 1, 0, 1, 1, '0', 10, '$', 5, NULL, 0, 6545, 0, 0, '2018-12-10 00:22:53', NULL),
(12, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0006', NULL, 11, 'QN-0006', 'test customer panel', 0, '2018-12-10', NULL, 0, 'PL', '2', 'hours', 'before', 1, 79, 1, 1, 1, 0, 1, 1, '0', 10, '$', 5, NULL, 0, 20045, 20045, 1, '2018-12-10 00:22:53', '2018-12-23 03:17:02'),
(13, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0007', NULL, 0, NULL, NULL, 0, '2018-12-11', NULL, 0, 'PL', '2', 'hours', 'before', 1, 85, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5, 0, 0, '2018-12-10 23:13:48', NULL),
(14, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0007', NULL, 13, 'QN-0007', NULL, 0, '2018-12-11', NULL, 0, 'PL', '2', 'hours', 'before', 1, 85, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5, 5, 1, '2018-12-10 23:13:48', '2018-12-16 22:55:28'),
(15, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0008', NULL, 0, NULL, NULL, 0, '2018-12-11', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 20, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 20, 0, 0, '2018-12-11 01:29:13', NULL),
(16, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0008', NULL, 15, 'QN-0008', NULL, 0, '2019-01-01', '1970-01-01', 0, 'PL', '2', 'quantity', 'after', 1, 20, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 20, 20, 1, '2018-12-11 01:29:13', '2019-01-01 02:39:43'),
(17, 201, 'directOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0009', NULL, 0, NULL, NULL, 0, '2018-12-17', NULL, 0, 'PL', '1', 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-16 23:57:51', NULL),
(18, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0010', NULL, 0, NULL, NULL, 0, '2018-12-19', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-19 05:13:33', NULL),
(19, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0009', NULL, 18, 'QN-0010', NULL, 0, '2018-12-19', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 8000, 1, '2018-12-19 05:13:33', '2018-12-20 05:22:34'),
(20, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0011', NULL, 0, NULL, NULL, 0, '2018-12-22', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 60, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 80000, 0, 0, '2018-12-22 00:08:46', NULL),
(21, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0010', NULL, 20, 'QN-0011', NULL, 0, '2018-12-22', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 60, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 80000, 0, 2, '2018-12-22 00:08:46', NULL),
(22, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0012', NULL, 0, NULL, NULL, 0, '2018-12-22', '2018-12-22', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-22 04:27:16', NULL),
(23, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0011', NULL, 22, 'QN-0012', NULL, 0, '2018-12-23', '1970-01-01', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 8000, 1, '2018-12-22 04:27:16', '2018-12-23 04:05:27'),
(24, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0013', NULL, 0, NULL, NULL, 0, '2018-12-23', '2018-12-23', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-22 23:28:18', NULL),
(25, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0012', NULL, 24, 'QN-0013', NULL, 0, '2018-12-23', '2018-12-23', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 8000, 1, '2018-12-22 23:28:18', '2018-12-23 03:56:55'),
(26, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0014', NULL, 0, NULL, NULL, 0, '2018-12-23', '2018-12-23', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-23 00:38:14', NULL),
(27, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0013', NULL, 26, 'QN-0014', NULL, 0, '2018-12-23', '2018-12-23', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 8000, 1, '2018-12-23 00:38:14', '2018-12-24 01:42:15'),
(28, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0015', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10000, 0, 0, '2018-12-24 00:24:05', NULL),
(29, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0014', NULL, 28, 'QN-0015', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10000, 10000, 1, '2018-12-24 00:24:05', '2018-12-24 01:33:10'),
(30, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0016', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 0, 0, '2018-12-24 02:06:00', NULL),
(31, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0015', NULL, 30, 'QN-0016', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 6000, 1, '2018-12-24 02:06:00', '2018-12-24 02:07:36'),
(32, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0017', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2018-12-24 02:08:41', NULL),
(33, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0016', NULL, 32, 'QN-0017', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 1000, 1, '2018-12-24 02:08:41', '2018-12-24 02:09:06'),
(34, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0018', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 900, 0, 0, '2018-12-24 02:12:00', NULL),
(35, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0017', NULL, 34, 'QN-0018', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 900, 900, 1, '2018-12-24 02:12:00', '2018-12-24 02:12:28'),
(36, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0019', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 800, 0, 0, '2018-12-24 02:14:18', NULL),
(37, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0018', NULL, 36, 'QN-0019', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 800, 800, 1, '2018-12-24 02:14:18', '2018-12-24 02:14:54'),
(38, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0020', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 700, 0, 0, '2018-12-24 02:16:13', NULL),
(39, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0019', NULL, 38, 'QN-0020', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 700, 700, 1, '2018-12-24 02:16:13', '2018-12-24 02:16:44'),
(40, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0021', NULL, 0, NULL, NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 600, 0, 0, '2018-12-24 02:18:19', NULL),
(41, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0020', NULL, 40, 'QN-0021', NULL, 0, '2018-12-24', '2018-12-24', 0, 'PL', '2', 'hours', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 600, 600, 1, '2018-12-24 02:18:19', '2018-12-24 02:18:43'),
(42, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0022', NULL, 0, NULL, NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1166.67, 0, 0, '2018-12-26 00:58:56', NULL),
(43, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0021', NULL, 42, 'QN-0022', NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1166.67, 0, 1, '2018-12-26 00:58:57', NULL),
(44, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0023', NULL, 0, NULL, NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1200, 0, 0, '2018-12-26 01:00:13', NULL),
(45, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0022', NULL, 44, 'QN-0023', NULL, 0, '2018-12-26', '1970-01-01', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1200, 0, 1, '2018-12-26 01:00:13', '2018-12-26 01:00:50'),
(46, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0024', NULL, 0, NULL, NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2018-12-26 01:11:31', NULL),
(47, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0023', NULL, 46, 'QN-0024', NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 1, '2018-12-26 01:11:31', NULL),
(48, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0025', NULL, 0, NULL, NULL, 0, '2018-12-26', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-26 06:37:42', NULL),
(49, 201, 'directOrder', NULL, 2, 2, 15, 'flat', 'exclusive', 'QN-0026', NULL, 0, NULL, NULL, 0, '2018-12-26', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 7800, 0, 0, '2018-12-26 06:40:33', NULL),
(50, 201, 'indirectOrder', NULL, 3, 3, 15, 'flat', 'exclusive', 'QN-0027', NULL, 0, NULL, NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-26 06:57:10', NULL),
(51, 202, 'directInvoice', NULL, 3, 3, 15, 'flat', 'exclusive', 'INV-0024', NULL, 50, 'QN-0027', NULL, 0, '2018-12-26', '2018-12-26', 0, 'PL', '2', 'quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 8000, 1, '2018-12-26 06:57:10', '2018-12-26 07:04:12'),
(52, 201, 'indirectOrder', NULL, 2, 2, 15, 'flat', 'exclusive', 'QN-0028', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 0, '2018-12-26 23:04:14', NULL),
(53, 202, 'directInvoice', NULL, 2, 2, 15, 'flat', 'exclusive', 'INV-0025', NULL, 52, 'QN-0028', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 5000, 1, '2018-12-26 23:04:14', '2018-12-26 23:04:20'),
(54, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0029', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 920, 0, 0, '2018-12-27 00:21:25', NULL),
(55, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0026', NULL, 54, 'QN-0029', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 920, 0, 1, '2018-12-27 00:21:25', NULL),
(56, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0030', NULL, 0, NULL, NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 2, '%', NULL, NULL, 0, 7840, 0, 0, '2018-12-27 01:30:13', NULL),
(57, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0031', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2018-12-27 01:48:14', NULL),
(58, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0027', NULL, 57, 'QN-0031', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 1, '2018-12-27 01:48:14', NULL),
(59, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0032', NULL, 0, NULL, NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 40000, 0, 0, '2018-12-27 01:51:11', NULL),
(60, 202, 'indirectInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0028', NULL, 59, 'QN-0032', NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 40000, 220, 0, '2018-12-27 01:51:13', '2018-12-27 01:54:24'),
(61, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0033', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 01:56:16', NULL),
(63, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0034', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-27 01:57:25', NULL),
(64, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0030', NULL, 63, 'QN-0034', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 1, '2018-12-27 01:57:25', NULL),
(65, 201, 'indirectOrder', 9, 1, 1, 1, 'flat', 'exclusive', 'QN-0035', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 02:02:49', NULL),
(67, 201, 'indirectOrder', 9, 1, 1, 1, 'flat', 'exclusive', 'QN-0036', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 02:05:03', NULL),
(68, 202, 'directInvoice', 9, 1, 1, 1, 'flat', 'exclusive', 'INV-0032', NULL, 67, 'QN-0036', NULL, 0, '2018-12-27', '1970-01-01', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 1, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 7760, 77, 1, '2018-12-27 02:05:03', '2018-12-27 03:35:19'),
(69, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0037', NULL, 0, NULL, NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 500, 0, 0, '2018-12-27 02:14:21', NULL),
(70, 201, 'directOrder', NULL, 2, 2, 1, 'flat', 'exclusive', 'QN-0038', NULL, 0, NULL, NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 1, 0, 1, 1, '0', 1, '%', NULL, NULL, 0, 194, 0, 0, '2018-12-27 02:16:25', '2018-12-27 02:17:06'),
(71, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0039', NULL, 0, NULL, NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 1, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 7760, 0, 1, '2018-12-27 02:19:49', NULL),
(72, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0033', NULL, 71, 'QN-0039', NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 1, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 7760, 0, 1, '2018-12-27 02:19:49', NULL),
(73, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0040', NULL, 0, NULL, 'rfgrfghhg', 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 2, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 02:22:06', NULL),
(75, 201, 'indirectOrder', 10, 6, 6, 15, 'flat', 'exclusive', 'QN-0041', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 03:33:31', NULL),
(77, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'inclusive', 'QN-0042', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 0, '2018-12-27 03:39:20', NULL),
(78, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'inclusive', 'INV-0035', NULL, 77, 'QN-0042', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 1, '2018-12-27 03:39:20', NULL),
(79, 201, 'indirectOrder', 9, 2, 2, 15, 'flat', 'exclusive', 'QN-0043', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 04:00:05', NULL),
(81, 201, 'indirectOrder', 9, 2, 2, 15, 'flat', 'exclusive', 'QN-0044', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 04:01:41', NULL),
(83, 201, 'indirectOrder', 9, 2, 2, 15, 'flat', 'exclusive', 'QN-0045', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 04:06:15', NULL),
(85, 201, 'indirectOrder', 9, 2, 2, 15, 'flat', 'exclusive', 'QN-0046', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 0, 0, 0, '2018-12-27 04:13:55', NULL),
(87, 201, 'indirectOrder', 9, 2, 2, 1, 'flat', 'inclusive', 'QN-0047', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 120, 0, 0, '2018-12-27 04:14:52', NULL),
(88, 202, 'directInvoice', 9, 2, 2, 1, 'flat', 'inclusive', 'INV-0040', NULL, 87, 'QN-0047', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 120, 120, 1, '2018-12-27 04:14:52', '2018-12-27 04:52:07'),
(89, 201, 'indirectOrder', 9, 2, 2, 1, 'flat', 'exclusive', 'QN-0048', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 0, 0, '2018-12-27 04:15:21', NULL),
(90, 202, 'directInvoice', 9, 2, 2, 1, 'flat', 'exclusive', 'INV-0041', NULL, 89, 'QN-0048', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 0, 1, '2018-12-27 04:15:21', NULL),
(91, 201, 'indirectOrder', 9, 2, 2, 1, 'flat', 'exclusive', 'QN-0049', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 0, '2018-12-27 04:16:37', NULL),
(92, 202, 'directInvoice', 9, 2, 2, 1, 'flat', 'exclusive', 'INV-0042', NULL, 91, 'QN-0049', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 1, '2018-12-27 04:16:37', NULL),
(93, 201, 'indirectOrder', 9, 2, 2, 15, 'flat', 'exclusive', 'QN-0050', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 0, '2018-12-27 04:17:00', NULL),
(94, 202, 'directInvoice', 9, 2, 2, 15, 'flat', 'exclusive', 'INV-0043', NULL, 93, 'QN-0050', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 1, '2018-12-27 04:17:00', NULL),
(95, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0051', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 16800, 0, 0, '2018-12-27 04:32:52', NULL),
(96, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0044', NULL, 95, 'QN-0051', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 16800, 0, 1, '2018-12-27 04:32:52', NULL),
(97, 201, 'directOrder', NULL, 2, 2, 1, 'flat', 'exclusive', 'QN-0052', NULL, 0, NULL, NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2018-12-27 04:54:40', NULL),
(98, 202, 'indirectInvoice', NULL, 2, 2, 1, 'flat', 'exclusive', 'INV-0045', NULL, 97, 'QN-0052', NULL, 0, '2018-12-27', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 100, 0, '2018-12-27 04:57:53', '2018-12-27 05:03:48'),
(99, 201, 'indirectOrder', NULL, 4, 4, 1, 'flat', 'inclusive', 'QN-0053', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 0, '2018-12-27 05:08:05', NULL),
(100, 202, 'directInvoice', NULL, 4, 4, 1, 'flat', 'inclusive', 'INV-0046', NULL, 99, 'QN-0053', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 1, '2018-12-27 05:08:05', NULL),
(101, 201, 'indirectOrder', NULL, 7, 7, 1, 'flat', 'inclusive', 'QN-0054', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 2000, 0, 0, '2018-12-27 05:09:22', NULL),
(102, 202, 'directInvoice', NULL, 7, 7, 1, 'flat', 'inclusive', 'INV-0047', NULL, 101, 'QN-0054', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 2000, 0, 1, '2018-12-27 05:09:22', NULL),
(103, 201, 'indirectOrder', NULL, 6, 6, 1, 'flat', 'inclusive', 'QN-0055', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2018-12-27 05:35:04', NULL),
(104, 202, 'directInvoice', NULL, 6, 6, 1, 'flat', 'inclusive', 'INV-0048', NULL, 103, 'QN-0055', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 1, '2018-12-27 05:35:04', NULL),
(105, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0056', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 0, '2018-12-27 06:17:03', NULL),
(106, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0049', NULL, 105, 'QN-0056', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 1, '2018-12-27 06:17:03', NULL),
(107, 201, 'indirectOrder', NULL, 2, 2, 1, 'flat', 'inclusive', 'QN-0057', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 2000, 0, 0, '2018-12-27 06:32:11', NULL),
(108, 202, 'directInvoice', NULL, 2, 2, 1, 'flat', 'inclusive', 'INV-0050', NULL, 107, 'QN-0057', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 2000, 0, 1, '2018-12-27 06:32:11', NULL),
(109, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0058', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 0, '2018-12-27 06:36:45', NULL),
(110, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0051', NULL, 109, 'QN-0058', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 1, '2018-12-27 06:36:45', NULL),
(111, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0059', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 0, '2018-12-27 06:45:37', NULL),
(112, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0052', NULL, 111, 'QN-0059', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 1, '2018-12-27 06:45:37', NULL),
(113, 201, 'indirectOrder', NULL, 2, 2, 1, 'flat', 'inclusive', 'QN-0060', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 920, 0, 0, '2018-12-27 06:46:39', NULL),
(114, 202, 'directInvoice', NULL, 2, 2, 1, 'flat', 'inclusive', 'INV-0053', NULL, 113, 'QN-0060', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 920, 0, 1, '2018-12-27 06:46:39', NULL),
(115, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0061', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 0, '2018-12-27 06:48:43', NULL),
(116, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0054', NULL, 115, 'QN-0061', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 1, '2018-12-27 06:48:43', NULL),
(117, 201, 'indirectOrder', NULL, 4, 4, 1, 'flat', 'inclusive', 'QN-0062', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 920, 0, 0, '2018-12-27 06:50:35', NULL),
(118, 202, 'directInvoice', NULL, 4, 4, 1, 'flat', 'inclusive', 'INV-0055', NULL, 117, 'QN-0062', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'hours', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 920, 0, 1, '2018-12-27 06:50:35', NULL),
(119, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'inclusive', 'QN-0063', NULL, 0, NULL, NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'after', 2, 1, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 168, 0, 0, '2018-12-27 06:59:06', NULL),
(120, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'inclusive', 'INV-0056', NULL, 119, 'QN-0063', NULL, 0, '2018-12-27', '2018-12-27', 0, 'PL', '2', 'quantity', 'after', 2, 1, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 168, 0, 1, '2018-12-27 06:59:06', NULL),
(121, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0064', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1640, 0, 0, '2018-12-27 22:59:42', NULL),
(122, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0057', NULL, 121, 'QN-0064', NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1640, 0, 1, '2018-12-27 22:59:42', NULL),
(123, 201, 'indirectOrder', NULL, 4, 4, 1, 'flat', 'inclusive', 'QN-0065', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1680, 0, 0, '2018-12-27 23:01:47', NULL),
(124, 202, 'directInvoice', NULL, 4, 4, 1, 'flat', 'inclusive', 'INV-0058', NULL, 123, 'QN-0065', NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1680, 0, 1, '2018-12-27 23:01:47', NULL),
(125, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0066', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1125, 0, 0, '2018-12-27 23:04:12', NULL),
(126, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0059', NULL, 125, 'QN-0066', NULL, 0, '2018-12-28', '1970-01-01', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1840, 0, 1, '2018-12-27 23:04:12', '2018-12-27 23:05:32'),
(127, 201, 'indirectOrder', NULL, 7, 7, 1, 'flat', 'exclusive', 'QN-0067', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', 0, NULL, 0, 2100, 0, 0, '2018-12-27 23:55:52', NULL),
(128, 202, 'directInvoice', NULL, 7, 7, 1, 'flat', 'exclusive', 'INV-0060', NULL, 127, 'QN-0067', NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', 0, NULL, 0, 2100, 0, 1, '2018-12-27 23:55:52', NULL),
(129, 201, 'indirectOrder', NULL, 6, 6, 1, 'flat', 'exclusive', 'QN-0068', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 2000, 0, 0, '2018-12-27 23:58:22', NULL),
(130, 202, 'directInvoice', NULL, 6, 6, 1, 'flat', 'exclusive', 'INV-0061', NULL, 129, 'QN-0068', NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 2000, 0, 1, '2018-12-27 23:58:22', NULL),
(131, 201, 'indirectOrder', NULL, 2, 2, 1, 'flat', 'inclusive', 'QN-0069', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1600, 0, 0, '2018-12-27 23:59:36', NULL),
(132, 202, 'directInvoice', NULL, 2, 2, 1, 'flat', 'inclusive', 'INV-0062', NULL, 131, 'QN-0069', NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1600, 0, 1, '2018-12-27 23:59:36', NULL),
(133, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0070', NULL, 0, NULL, NULL, 0, '2018-12-28', '2018-12-28', 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1680, 0, 0, '2018-12-28 00:01:03', NULL),
(134, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0063', NULL, 133, 'QN-0070', NULL, 0, '2018-12-28', '1970-01-01', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 0, 1, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 2100, 0, 1, '2018-12-28 00:01:03', '2018-12-28 00:28:01'),
(135, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0071', NULL, 0, NULL, NULL, 0, '2019-01-02', '2019-01-02', 0, 'PL', '2', 'quantity', 'after', 1, 0.12, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 108012, 0, 0, '2019-01-02 06:32:36', NULL),
(136, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0064', NULL, 135, 'QN-0071', NULL, 0, '2019-01-02', '1970-01-01', 0, 'PL', '2', 'quantity', 'after', 1, 0.12, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 80010, 100, 1, '2019-01-02 06:32:36', '2019-01-02 06:52:20'),
(149, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0072', NULL, 0, NULL, 'test', 1, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'after', 1, 0.12, 1, 1, 1, 1, 1, 1, '1', 3, '%', 5, NULL, 10, 2508.75, 0, 0, '2019-01-06 03:28:37', NULL),
(150, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0065', NULL, 149, 'QN-0072', 'test', 1, '2019-01-06', '1970-01-01', 0, 'PL', '2', 'quantity', 'after', 1, 0.12, 1, 1, 1, 1, 1, 1, '1', 3, '%', 5, NULL, 10, 2508.75, 0, 1, '2019-01-06 03:28:37', '2019-01-06 03:29:03'),
(151, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0073', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 0, '2019-01-06 03:48:52', NULL),
(152, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0066', NULL, 151, 'QN-0073', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 200, 0, 1, '2019-01-06 03:48:52', NULL),
(153, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0074', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2019-01-06 04:02:06', NULL),
(154, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0067', NULL, 153, 'QN-0074', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 1, '2019-01-06 04:02:06', NULL),
(155, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0075', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 150, 0, 0, '2019-01-06 04:05:45', NULL),
(156, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0068', NULL, 155, 'QN-0075', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 150, 150, 1, '2019-01-06 04:05:45', '2019-01-06 04:07:26'),
(157, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'inclusive', 'QN-0076', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'hours', 'after', 1, 79, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 0, 0, '2019-01-06 04:10:08', NULL),
(158, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'inclusive', 'INV-0069', NULL, 157, 'QN-0076', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'hours', 'after', 1, 79, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 6001, 1, '2019-01-06 04:10:09', '2019-01-06 04:12:33'),
(159, 201, 'indirectOrder', 7, 5, 5, 1, 'flat', 'exclusive', 'QN-0077', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'hours', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 850, 0, 0, '2019-01-06 05:12:22', NULL),
(160, 202, 'directInvoice', 7, 5, 5, 1, 'flat', 'exclusive', 'INV-0070', NULL, 159, 'QN-0077', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'hours', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 850, 2550, 1, '2019-01-06 05:12:22', '2019-01-06 05:30:58'),
(161, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0078', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5050, 0, 0, '2019-01-06 06:30:29', NULL),
(162, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0071', NULL, 161, 'QN-0078', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5050, 100, 1, '2019-01-06 06:30:29', '2019-01-06 06:57:41'),
(163, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0079', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'after', 1, 0.12, 1, 1, 0, 0, 1, 1, '0', 3, '%', NULL, NULL, 0, 7771.43, 0, 0, '2019-01-06 06:45:05', NULL),
(164, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0072', NULL, 163, 'QN-0079', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'after', 1, 0.12, 1, 1, 0, 0, 1, 1, '0', 3, '%', NULL, NULL, 0, 7771.43, 0, 1, '2019-01-06 06:45:05', NULL),
(165, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0080', NULL, 0, NULL, NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 500, 0, 0, '2019-01-06 06:58:29', NULL),
(166, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0073', NULL, 165, 'QN-0080', NULL, 0, '2019-01-06', '2019-01-06', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 500, 500, 1, '2019-01-06 06:58:29', '2019-01-06 07:12:20'),
(167, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0081', NULL, 0, NULL, NULL, 0, '2019-01-07', '2019-01-07', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 600, 0, 0, '2019-01-06 23:14:25', NULL),
(168, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0074', NULL, 167, 'QN-0081', NULL, 0, '2019-01-07', '2019-01-07', 0, 'PL', '2', 'quantity', 'before', 1, 81, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 600, 0, 1, '2019-01-06 23:14:25', NULL),
(169, 201, 'indirectOrder', 1, 2, 2, 1, 'flat', 'exclusive', 'QN-0082', NULL, 0, NULL, NULL, 0, '2019-01-09', '2019-01-09', 0, 'PL', '2', 'quantity', 'before', 1, 0.12, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2019-01-09 03:57:38', NULL),
(170, 202, 'directInvoice', 1, 2, 2, 1, 'flat', 'exclusive', 'INV-0075', NULL, 169, 'QN-0082', NULL, 0, '2019-01-09', '1970-01-01', 0, 'PL', '2', 'quantity', 'before', 1, 0.12, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8100, 8050, 1, '2019-01-09 03:57:38', '2019-01-09 04:01:20'),
(171, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0083', NULL, 0, NULL, NULL, 0, '2019-01-13', '2019-01-13', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 50, '$', 50, NULL, 0, 2000, 0, 0, '2019-01-13 00:28:40', NULL),
(172, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0076', NULL, 171, 'QN-0083', NULL, 0, '2019-01-13', '2019-01-13', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 50, '$', 50, NULL, 0, 2000, 0, 1, '2019-01-13 00:28:41', NULL),
(174, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0084', NULL, 0, NULL, NULL, 0, '2019-01-13', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 1150, 0, 0, '2019-01-13 01:19:27', NULL),
(176, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0085', NULL, 0, NULL, NULL, 0, '2019-01-13', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2019-01-13 01:31:35', NULL),
(177, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0086', NULL, 0, NULL, NULL, 0, '2019-01-13', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2019-01-13 01:47:47', NULL),
(178, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0087', NULL, 0, NULL, NULL, 0, '2019-01-13', '2019-01-13', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2019-01-13 01:47:53', NULL),
(179, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0077', NULL, 178, 'QN-0087', NULL, 0, '2019-01-13', '2019-01-13', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 1, '2019-01-13 01:47:53', NULL),
(180, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0088', NULL, 0, NULL, NULL, 0, '2019-01-13', NULL, 0, 'PL', '2', 'quantity', 'after', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 92, 0, 0, '2019-01-13 04:35:20', '2019-01-13 06:16:23'),
(181, 201, 'indirectOrder', NULL, 2, 2, 1, 'flat', 'inclusive', 'QN-0089', NULL, 0, NULL, NULL, 0, '2019-01-13', '2019-01-13', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2019-01-13 04:35:28', NULL),
(182, 202, 'directInvoice', NULL, 2, 2, 1, 'flat', 'inclusive', 'INV-0078', NULL, 181, 'QN-0089', NULL, 0, '2019-01-13', '2019-01-13', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 1, '2019-01-13 04:35:28', NULL),
(183, 201, 'directOrder', NULL, 3, 3, 1, 'flat', 'inclusive', 'QN-0090', NULL, 0, NULL, NULL, 0, '2019-01-13', NULL, 0, 'PL', '2', 'hours', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', 10, '%', NULL, NULL, 0, 90, 0, 0, '2019-01-13 05:51:30', '2019-01-13 06:26:35'),
(184, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0091', NULL, 0, NULL, NULL, 0, '2019-01-15', '2019-01-15', 0, 'PL', '2', 'quantity', 'before', 1, 90, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2019-01-14 23:09:34', NULL),
(185, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0079', NULL, 184, 'QN-0091', NULL, 0, '2019-01-15', '2019-01-15', 0, 'PL', '2', 'quantity', 'before', 1, 90, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 100, 1, '2019-01-14 23:09:34', '2019-01-14 23:10:49'),
(186, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'inclusive', 'QN-0092', NULL, 0, NULL, NULL, 0, '2019-01-15', '2019-01-15', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', 5, NULL, 0, 95, 0, 0, '2019-01-14 23:11:52', NULL),
(187, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'inclusive', 'INV-0080', NULL, 186, 'QN-0092', NULL, 0, '2019-01-15', '2019-01-15', 0, 'PL', '2', 'quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', 10, '%', 5, NULL, 0, 95, 0, 1, '2019-01-14 23:11:52', NULL),
(188, 201, 'directOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0093', NULL, 0, NULL, NULL, 0, '2019-01-20', NULL, 0, 'PL', '2', 'Quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 6000, 0, 0, '2019-01-20 00:38:57', NULL),
(189, 201, 'directOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0094', NULL, 0, NULL, NULL, 0, '2019-01-20', NULL, 0, 'PL', '2', 'Quantity', 'before', 2, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 15500, 0, 0, '2019-01-20 00:47:40', NULL),
(190, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0095', NULL, 0, NULL, NULL, 0, '2019-01-20', NULL, 0, 'PL', '2', 'Quantity', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 2240000, 0, 0, '2019-01-20 00:59:28', NULL),
(191, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0096', NULL, 0, NULL, NULL, 0, '2019-01-20', '2019-01-20', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1440000, 0, 0, '2019-01-20 03:03:27', NULL),
(192, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0081', NULL, 191, 'QN-0096', NULL, 0, '2019-01-20', '2019-01-20', 0, 'PL', '2', 'hours', 'before', 1, 80, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1440000, 0, 1, '2019-01-20 03:03:27', NULL),
(193, 201, 'directOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0097', NULL, 0, NULL, NULL, 0, '2019-01-21', NULL, 0, 'PL', '2', 'Quantity', 'before', 2, 0.0125, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10000, 0, 0, '2019-01-21 05:46:45', NULL),
(194, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0098', NULL, 0, NULL, NULL, 0, '2019-01-21', '2019-01-21', 0, 'PL', '2', 'quantity', 'before', 2, 0.0125, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2019-01-21 05:47:56', NULL),
(195, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0082', NULL, 194, 'QN-0098', NULL, 0, '2019-01-21', '2019-01-21', 0, 'PL', '2', 'quantity', 'before', 2, 0.0125, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 8000, 1, '2019-01-21 05:47:56', '2019-01-21 05:48:05'),
(196, 202, 'indirectInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0083', NULL, 193, 'QN-0097', NULL, 0, '2019-01-21', NULL, 0, 'PL', '2', 'Quantity', 'before', 2, 0.0125, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 10000, 0, 0, '2019-01-22 04:07:06', NULL),
(197, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0099', NULL, 0, NULL, NULL, 0, '2019-01-22', NULL, 0, 'PL', '1', 'Quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2019-01-22 04:45:08', NULL),
(198, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0100', NULL, 0, NULL, NULL, 0, '2019-01-22', NULL, 0, 'PL', '1,2', 'Quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 1000, 0, 0, '2019-01-22 05:04:54', NULL),
(199, 201, 'directOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0101', NULL, 0, NULL, NULL, 0, '2019-01-22', NULL, 0, 'PL', NULL, 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 0, '2019-01-22 05:47:10', '2019-01-22 05:49:03'),
(200, 202, 'indirectInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0084', NULL, 199, 'QN-0101', NULL, 0, '2019-01-22', NULL, 0, 'PL', NULL, 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 0, '2019-01-22 05:49:25', NULL),
(201, 201, 'indirectOrder', NULL, 2, 2, 1, 'flat', 'exclusive', 'QN-0102', NULL, 0, NULL, NULL, 0, '2019-01-22', '2019-01-22', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2019-01-22 06:11:09', NULL),
(202, 202, 'directInvoice', NULL, 2, 2, 1, 'flat', 'exclusive', 'INV-0085', NULL, 201, 'QN-0102', NULL, 0, '2019-01-22', '1970-01-01', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 1, '2019-01-22 06:11:09', '2019-01-22 06:15:41'),
(203, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0103', NULL, 0, NULL, NULL, 0, '2019-01-22', '2019-01-22', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2019-01-22 06:16:30', NULL),
(204, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0086', NULL, 203, 'QN-0103', NULL, 0, '2019-01-22', '1970-01-01', 0, 'PL', NULL, 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 1, '2019-01-22 06:16:30', '2019-01-22 06:59:07'),
(205, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0104', NULL, 0, NULL, 'test', 1, '2019-01-23', '2019-01-23', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', 2, NULL, 0, 8242, 0, 0, '2019-01-23 03:55:43', NULL),
(206, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0087', NULL, 205, 'QN-0104', 'test', 1, '2019-01-23', '2019-01-23', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', 2, NULL, 0, 8242, 8242, 1, '2019-01-23 03:55:43', '2019-01-23 04:13:10'),
(207, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0105', NULL, 0, NULL, NULL, 0, '2019-01-23', '2019-01-23', 0, 'PL', '1,2,4', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 7845, 0, 0, '2019-01-23 05:06:07', NULL),
(208, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0088', NULL, 207, 'QN-0105', NULL, 0, '2019-01-23', '2019-01-23', 0, 'PL', '1,2,4', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 7845, 0, 1, '2019-01-23 05:06:07', NULL),
(209, 201, 'directOrder', NULL, 5, 5, 17, 'flat', 'exclusive', 'QN-0106', NULL, 0, NULL, NULL, 0, '2019-01-24', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 1, 1, 0, 0, 0, 1, 1, '0', 2, '%', 2, NULL, 0, 7940, 0, 0, '2019-01-24 05:58:00', '2019-01-24 05:58:29'),
(210, 202, 'indirectInvoice', NULL, 5, 5, 17, 'flat', 'exclusive', 'INV-0089', NULL, 209, 'QN-0106', NULL, 0, '2019-01-24', NULL, 0, 'PL', '2', 'quantity', 'before', 1, 1, 1, 0, 0, 0, 1, 1, '0', 2, '%', 2, NULL, 0, 7940, 40, 0, '2019-01-24 05:58:34', '2019-01-24 07:08:54'),
(211, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0107', NULL, 0, NULL, NULL, 0, '2019-01-27', '2019-01-27', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8010, 0, 0, '2019-01-27 03:20:31', NULL),
(212, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0090', NULL, 211, 'QN-0107', NULL, 0, '2019-01-27', '2019-01-27', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8010, 0, 1, '2019-01-27 03:20:31', NULL);
INSERT INTO `sales_orders` (`id`, `trans_type`, `invoice_type`, `project_id`, `customer_id`, `branch_id`, `user_id`, `discount_type`, `tax_type`, `reference`, `customer_ref`, `order_reference_id`, `order_reference`, `comments`, `comment_check`, `ord_date`, `due_date`, `order_type`, `from_stk_loc`, `payment_id`, `inv_type`, `discount_on`, `currency_id`, `exchange_rate`, `m_tax`, `m_detail_description`, `m_item_discount`, `m_shn`, `m_sub_discount`, `m_sub_shipping`, `m_sub_custom_amount`, `s_other_discount`, `s_other_discount_type`, `s_shipping`, `s_custom_amount_title`, `s_custom_amount`, `total`, `paid_amount`, `payment_term`, `created_at`, `updated_at`) VALUES
(213, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0108', NULL, 0, NULL, NULL, 0, '2019-01-27', '2019-01-27', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 4220005, 0, 0, '2019-01-27 06:54:44', NULL),
(214, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0091', NULL, 213, 'QN-0108', NULL, 0, '2019-01-27', '2019-01-27', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 4220005, 0, 1, '2019-01-27 06:54:44', NULL),
(215, 201, 'directOrder', NULL, 5, 5, 1, 'flat', 'inclusive', 'QN-0109', NULL, 0, NULL, NULL, 0, '2019-01-27', NULL, 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', NULL, NULL, 0, 7840, 0, 0, '2019-01-27 07:04:59', '2019-01-27 07:05:28'),
(216, 202, 'indirectInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0092', NULL, 215, 'QN-0109', NULL, 0, '2019-01-27', '2019-02-11', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', 2, '%', NULL, NULL, 0, 7840, 0, 0, '2019-01-27 07:05:37', '2019-01-27 07:08:30'),
(217, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'inclusive', 'QN-0110', NULL, 0, NULL, 'This is a test note', 1, '2019-01-29', '2019-01-29', 0, 'PL', '1,2', 'quantity', 'before', 1, 0.11, 1, 0, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 7943, 0, 0, '2019-01-29 00:50:01', NULL),
(218, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'inclusive', 'INV-0093', NULL, 217, 'QN-0110', 'This is a test note', 1, '2019-01-29', '2019-01-29', 0, 'PL', '1,2', 'quantity', 'before', 1, 0.11, 1, 0, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 7943, 50, 1, '2019-01-29 00:50:01', '2019-01-29 06:01:22'),
(219, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0111', NULL, 0, NULL, NULL, 0, '2019-01-29', '2019-01-29', 0, 'PL', '1', 'quantity', 'before', 1, 79, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2019-01-29 06:05:59', NULL),
(220, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0094', NULL, 219, 'QN-0111', NULL, 0, '2019-01-29', '2019-01-29', 0, 'PL', '1', 'quantity', 'before', 1, 79, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 99, 1, '2019-01-29 06:05:59', '2019-01-29 06:06:58'),
(221, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0112', NULL, 0, NULL, NULL, 0, '2019-01-29', '2019-01-29', 0, 'PL', '1', 'quantity', 'before', 1, 80, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 9025, 0, 0, '2019-01-29 07:02:20', NULL),
(222, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0095', NULL, 221, 'QN-0112', NULL, 0, '2019-01-29', '2019-01-29', 0, 'PL', '1', 'quantity', 'before', 1, 80, 1, 0, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 9025, 50, 1, '2019-01-29 07:02:20', '2019-01-29 07:06:37'),
(223, 201, 'directOrder', NULL, 5, 5, 1, 'flat', 'inclusive', 'QN-0113', NULL, 0, NULL, 'Test Invoice', 0, '2019-01-30', NULL, 0, 'PL', '1,2', 'quantity', 'before', 1, 80, 1, 0, 0, 0, 0, 0, '0', 0, '%', 0, NULL, 0, 16200, 0, 0, '2019-01-30 02:24:13', NULL),
(224, 202, 'indirectInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0096', NULL, 223, 'QN-0113', 'Test Invoice', 0, '1970-01-01', NULL, 0, 'PL', '1,2', 'quantity', 'before', 1, 80, 1, 0, 0, 0, 0, 0, '0', 0, '%', 0, NULL, 0, 16200, 1400, 0, '2019-01-30 02:24:20', '2019-01-30 04:56:21'),
(225, 201, 'indirectOrder', NULL, 1, 1, 1, 'flat', 'exclusive', 'QN-0114', NULL, 0, NULL, NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2019-01-30 05:17:01', NULL),
(226, 202, 'directInvoice', NULL, 1, 1, 1, 'flat', 'exclusive', 'INV-0097', NULL, 225, 'QN-0114', NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 1, '2019-01-30 05:17:01', NULL),
(227, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0115', NULL, 0, NULL, NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 0, '2019-01-30 05:17:20', NULL),
(228, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0098', NULL, 227, 'QN-0115', NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 100, 0, 1, '2019-01-30 05:17:20', NULL),
(229, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0116', NULL, 0, NULL, NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 0, 0, '2019-01-30 05:45:39', NULL),
(230, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0099', NULL, 229, 'QN-0116', NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8000, 600, 1, '2019-01-30 05:45:40', '2019-01-30 05:55:21'),
(231, 201, 'indirectOrder', NULL, 3, 3, 1, 'flat', 'exclusive', 'QN-0117', NULL, 0, NULL, NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1', 'quantity', 'before', 2, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 0, 0, '2019-01-30 07:06:10', NULL),
(232, 202, 'directInvoice', NULL, 3, 3, 1, 'flat', 'exclusive', 'INV-0100', NULL, 231, 'QN-0117', NULL, 0, '2019-01-30', '2019-01-30', 0, 'PL', '1', 'quantity', 'before', 2, 50, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 5000, 5000, 1, '2019-01-30 07:06:10', '2019-01-31 04:56:23'),
(233, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0118', NULL, 0, NULL, NULL, 0, '2019-01-31', '2019-01-31', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8557, 0, 0, '2019-01-30 23:46:28', NULL),
(234, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0101', NULL, 233, 'QN-0118', NULL, 0, '2019-01-31', '2019-01-31', 0, 'PL', '1,2', 'quantity', 'before', 1, 1, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 8557, 557, 1, '2019-01-30 23:46:28', '2019-01-31 01:16:24'),
(235, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0119', NULL, 0, NULL, NULL, 0, '2019-01-31', '2019-01-31', 0, 'PL', '1', 'quantity', 'before', 1, 60, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 438.75, 0, 0, '2019-01-30 23:48:16', NULL),
(236, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0102', NULL, 235, 'QN-0119', NULL, 0, '2019-01-31', '2019-01-31', 0, 'PL', '1', 'quantity', 'before', 1, 60, 1, 1, 0, 0, 1, 1, '0', NULL, '%', NULL, NULL, 0, 438.75, 0, 1, '2019-01-30 23:48:16', NULL),
(237, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'inclusive', 'QN-0120', NULL, 0, NULL, 'This is a test invoice', 1, '2019-01-31', '2019-01-31', 0, 'PL', '1', 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', 2, '%', 5, NULL, 0, 51.55, 0, 0, '2019-01-30 23:51:35', NULL),
(239, 201, 'indirectOrder', NULL, 5, 5, 1, 'flat', 'exclusive', 'QN-0121', NULL, 0, NULL, 'This is a test invoice.', 1, '2019-01-31', '2019-01-31', 0, 'PL', NULL, 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', 2, '%', NULL, NULL, 0, 49.94, 0, 0, '2019-01-30 23:52:42', NULL),
(240, 202, 'directInvoice', NULL, 5, 5, 1, 'flat', 'exclusive', 'INV-0103', NULL, 239, 'QN-0121', 'This is a test invoice.', 1, '2019-01-31', '2019-01-31', 0, 'PL', NULL, 'quantity', 'before', 1, 50, 1, 1, 0, 0, 1, 1, '0', 2, '%', NULL, NULL, 0, 49.94, 0, 1, '2019-01-30 23:52:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_details`
--

DROP TABLE IF EXISTS `sales_order_details`;
CREATE TABLE IF NOT EXISTS `sales_order_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sales_order_id` int(10) UNSIGNED NOT NULL,
  `trans_type` int(11) NOT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_type_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` double NOT NULL DEFAULT '0',
  `qty_sent` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `is_inventory` double NOT NULL,
  `discount_percent` double NOT NULL DEFAULT '0',
  `discount` double NOT NULL DEFAULT '0',
  `discount_type` enum('%','$') COLLATE utf8_unicode_ci NOT NULL DEFAULT '%',
  `shn` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `sorting_no` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_order_details_sales_order_id_foreign` (`sales_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_order_details`
--

INSERT INTO `sales_order_details` (`id`, `sales_order_id`, `trans_type`, `item_id`, `tax_type_id`, `description`, `item_name`, `unit_price`, `qty_sent`, `quantity`, `is_inventory`, `discount_percent`, `discount`, `discount_type`, `shn`, `sorting_no`, `created_at`, `updated_at`) VALUES
(1, 1, 201, NULL, 0, 'LC, BBLC, Export Invoice', 'Garments ERP (Commercial Module)', 100, 0, 100, 0, 0, 0, '%', '', 1, '2018-12-09 06:21:02', NULL),
(2, 2, 202, NULL, 0, 'LC, BBLC, Export Invoice', 'Garments ERP (Commercial Module)', 100, 0, 100, 0, 0, 0, '%', '', 1, '2018-12-09 06:21:02', NULL),
(3, 3, 201, NULL, 0, 'Payeer Payment Gateway Integration. Payeer Payment Gateway Integration. Payeer Payment Gateway Integration. Payeer Payment Gateway Integration', 'Payeer Payment Gateway Integration', 100, 0, 5, 0, 0, 0, '%', '', 1, '2018-12-09 09:58:46', NULL),
(5, 5, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-09 12:24:35', NULL),
(6, 6, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 2, 1, 0, 0, '', '', 1, '2018-12-09 12:24:35', NULL),
(7, 6, 202, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '', '', 2, '2018-12-09 12:26:19', NULL),
(8, 7, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 10, '%', '', 1, '2018-12-09 12:43:52', NULL),
(9, 8, 202, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 10, '%', '', 1, '2018-12-09 12:43:52', NULL),
(10, 7, 201, 5, 0, NULL, 'Web Design', 8000, 0, 1, 1, 0, 10, '%', '', 2, '2018-12-09 12:43:52', NULL),
(11, 8, 202, 5, 0, NULL, 'Web Design', 8000, 0, 1, 1, 0, 10, '%', '', 2, '2018-12-09 12:43:52', NULL),
(12, 7, 201, 6, 0, NULL, 'Web Development', 16000, 0, 1, 1, 0, 10, '%', '', 3, '2018-12-09 12:43:52', NULL),
(13, 8, 202, 6, 0, NULL, 'Web Development', 16000, 0, 1, 1, 0, 10, '%', '', 3, '2018-12-09 12:43:53', NULL),
(14, 7, 201, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 10, '%', '', 4, '2018-12-09 12:43:53', NULL),
(15, 8, 202, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 10, '%', '', 4, '2018-12-09 12:43:53', NULL),
(16, 7, 201, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 10, '%', '', 5, '2018-12-09 12:43:53', NULL),
(17, 8, 202, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 10, '%', '', 5, '2018-12-09 12:43:53', NULL),
(18, 9, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-10 05:15:54', NULL),
(20, 11, 201, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 200, '$', '', 1, '2018-12-10 06:22:53', NULL),
(21, 12, 202, 1, 0, NULL, 'IT Support', 5000, 0, 3, 1, 0, 200, '$', '', 1, '2018-12-10 06:22:53', NULL),
(22, 13, 201, 6, 0, NULL, 'Web Development', 5, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-11 05:13:48', NULL),
(23, 14, 202, 6, 0, NULL, 'Web Development', 5, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-11 05:13:48', NULL),
(24, 15, 201, 2, 0, NULL, 'Samsung Galaxy S7', 20, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-11 07:29:13', NULL),
(25, 16, 202, 2, 0, '', 'Samsung Galaxy S7', 20, 0, 1, 1, 0, 0, '', '', 1, '2018-12-11 07:29:13', NULL),
(26, 17, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-17 05:57:52', NULL),
(27, 18, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-19 11:13:33', NULL),
(28, 19, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-19 11:13:33', NULL),
(29, 20, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 10, 1, 0, 0, '%', '', 1, '2018-12-22 06:08:46', NULL),
(30, 21, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 10, 1, 0, 0, '%', '', 1, '2018-12-22 06:08:46', NULL),
(31, 22, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-22 10:27:16', NULL),
(32, 23, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2018-12-22 10:27:16', NULL),
(33, 24, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-23 05:28:18', NULL),
(34, 25, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-23 05:28:18', NULL),
(35, 26, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-23 06:38:14', NULL),
(36, 27, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-23 06:38:14', NULL),
(37, 28, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 06:24:05', NULL),
(38, 29, 202, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 06:24:05', NULL),
(39, 30, 201, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:06:00', NULL),
(40, 31, 202, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:06:00', NULL),
(41, 32, 201, 4, 0, NULL, 'Graphics Design', 500, 0, 2, 1, 0, 0, '%', '', 1, '2018-12-24 08:08:41', NULL),
(42, 33, 202, 4, 0, NULL, 'Graphics Design', 500, 0, 2, 1, 0, 0, '%', '', 1, '2018-12-24 08:08:41', NULL),
(43, 34, 201, 3, 0, NULL, 'Software Development', 900, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:12:00', NULL),
(44, 35, 202, 3, 0, NULL, 'Software Development', 900, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:12:00', NULL),
(45, 36, 201, 3, 0, NULL, 'Software Development', 800, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:14:18', NULL),
(46, 37, 202, 3, 0, NULL, 'Software Development', 800, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:14:18', NULL),
(47, 38, 201, 3, 0, NULL, 'Software Development', 700, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:16:13', NULL),
(48, 39, 202, 3, 0, NULL, 'Software Development', 700, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:16:13', NULL),
(49, 40, 201, 3, 0, NULL, 'Software Development', 600, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:18:19', NULL),
(50, 41, 202, 3, 0, NULL, 'Software Development', 600, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-24 08:18:19', NULL),
(51, 42, 201, NULL, 0, NULL, 'tr', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-26 06:58:57', NULL),
(52, 43, 202, NULL, 0, NULL, 'tr', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-26 06:58:57', NULL),
(53, 44, 201, NULL, 0, NULL, 'tr', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-26 07:00:13', NULL),
(54, 45, 202, 0, 0, NULL, 'tr', 1000, 0, 1, 0, 0, 0, '', '', 1, '2018-12-26 07:00:13', NULL),
(55, 46, 201, NULL, 0, NULL, 'test', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-26 07:11:31', NULL),
(56, 47, 202, NULL, 0, NULL, 'test', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-26 07:11:31', NULL),
(57, 48, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-26 12:37:42', NULL),
(58, 49, 201, 2, 0, NULL, 'Samsung Galaxy S7', 7800, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-26 12:40:33', NULL),
(59, 50, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-26 12:57:10', NULL),
(60, 51, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-26 12:57:10', NULL),
(61, 52, 201, 2, 0, NULL, 'Samsung Galaxy S7', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 05:04:14', NULL),
(62, 53, 202, 2, 0, NULL, 'Samsung Galaxy S7', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 05:04:14', NULL),
(63, 54, 201, NULL, 0, NULL, 'test', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 06:21:25', NULL),
(64, 55, 202, NULL, 0, NULL, 'test', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 06:21:25', NULL),
(65, 56, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 07:30:13', NULL),
(66, 57, 201, 7, 0, NULL, 'test', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 07:48:14', NULL),
(67, 58, 202, 7, 0, NULL, 'test', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 07:48:14', NULL),
(68, 59, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 5, 1, 0, 0, '%', '', 1, '2018-12-27 07:51:11', NULL),
(69, 60, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 5, 1, 0, 0, '%', '', 1, '2018-12-27 07:51:13', NULL),
(70, 61, 201, 2, 0, NULL, 'Samsung Galaxy S7', 500, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 07:56:16', NULL),
(72, 63, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 07:57:25', NULL),
(73, 64, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 07:57:26', NULL),
(74, 65, 201, 4, 0, NULL, 'Graphics Design', 6000, 0, 10, 1, 0, 0, '%', '', 1, '2018-12-27 08:02:49', NULL),
(76, 67, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 08:05:03', NULL),
(77, 68, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 3, '%', '', 1, '2018-12-27 08:05:03', NULL),
(78, 69, 201, 2, 0, NULL, 'Samsung Galaxy S7', 500, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 08:14:21', NULL),
(79, 70, 201, 2, 0, NULL, 'Samsung Galaxy S7', 200, 0, 1, 1, 0, 2, '%', '', 1, '2018-12-27 08:16:25', NULL),
(80, 71, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 3, '%', '', 1, '2018-12-27 08:19:49', NULL),
(81, 72, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 3, '%', '', 1, '2018-12-27 08:19:50', NULL),
(82, 73, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 08:22:06', NULL),
(84, 75, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 09:33:31', NULL),
(86, 77, 201, NULL, 0, NULL, 'test 1', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 09:39:20', NULL),
(87, 78, 202, NULL, 0, NULL, 'test 1', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 09:39:20', NULL),
(88, 77, 201, NULL, 0, NULL, 'Test 2', 100, 0, 1, 0, 0, 0, '%', '', 2, '2018-12-27 09:39:20', NULL),
(89, 78, 202, NULL, 0, NULL, 'Test 2', 100, 0, 1, 0, 0, 0, '%', '', 2, '2018-12-27 09:39:20', NULL),
(90, 79, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:00:05', NULL),
(92, 81, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:01:41', NULL),
(94, 83, 201, 3, 0, NULL, 'Software Development', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:06:15', NULL),
(96, 85, 201, 3, 0, NULL, 'Software Development', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:13:55', NULL),
(98, 87, 201, 1, 0, NULL, 'IT Support', 120, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:14:52', NULL),
(99, 88, 202, 1, 0, NULL, 'IT Support', 120, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:14:52', NULL),
(100, 89, 201, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:15:21', NULL),
(101, 90, 202, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:15:21', NULL),
(102, 91, 201, 3, 0, NULL, 'Software Development', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:16:37', NULL),
(103, 92, 202, 3, 0, NULL, 'Software Development', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:16:37', NULL),
(104, 93, 201, 3, 0, NULL, 'Software Development', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:17:00', NULL),
(105, 94, 202, 3, 0, NULL, 'Software Development', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:17:00', NULL),
(106, 95, 201, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:32:52', NULL),
(107, 96, 202, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:32:52', NULL),
(108, 95, 201, 5, 0, NULL, 'Web Design', 8000, 0, 1, 1, 0, 0, '%', '', 2, '2018-12-27 10:32:52', NULL),
(109, 96, 202, 5, 0, NULL, 'Web Design', 8000, 0, 1, 1, 0, 0, '%', '', 2, '2018-12-27 10:32:52', NULL),
(110, 97, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:54:40', NULL),
(111, 98, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 10:57:53', NULL),
(112, 99, 201, 1, 0, NULL, 'IT Support', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 11:08:05', NULL),
(113, 100, 202, 1, 0, NULL, 'IT Support', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 11:08:05', NULL),
(114, 99, 201, 4, 0, NULL, 'Graphics Design', 100, 0, 1, 1, 0, 0, '%', '', 2, '2018-12-27 11:08:06', NULL),
(115, 100, 202, 4, 0, NULL, 'Graphics Design', 100, 0, 1, 1, 0, 0, '%', '', 2, '2018-12-27 11:08:06', NULL),
(116, 101, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 11:09:22', NULL),
(117, 102, 202, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 11:09:22', NULL),
(118, 101, 201, NULL, 0, NULL, 'Test 2', 1000, 0, 1, 0, 0, 0, '%', '', 2, '2018-12-27 11:09:22', NULL),
(119, 102, 202, NULL, 0, NULL, 'Test 2', 1000, 0, 1, 0, 0, 0, '%', '', 2, '2018-12-27 11:09:22', NULL),
(120, 103, 201, NULL, 0, NULL, 'tr', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 11:35:04', NULL),
(121, 104, 202, NULL, 0, NULL, 'tr', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 11:35:04', NULL),
(122, 105, 201, 4, 0, NULL, 'Graphics Design', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:17:03', NULL),
(123, 106, 202, 4, 0, NULL, 'Graphics Design', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:17:03', NULL),
(124, 105, 201, 5, 0, NULL, 'Web Design', 100, 0, 1, 1, 0, 0, '%', '', 2, '2018-12-27 12:17:03', NULL),
(125, 106, 202, 5, 0, NULL, 'Web Design', 100, 0, 1, 1, 0, 0, '%', '', 2, '2018-12-27 12:17:03', NULL),
(126, 107, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 12:32:11', NULL),
(127, 108, 202, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 12:32:11', NULL),
(128, 107, 201, NULL, 0, NULL, 'Item 2', 1000, 0, 1, 0, 0, 0, '%', '', 2, '2018-12-27 12:32:11', NULL),
(129, 108, 202, NULL, 0, NULL, 'Item 2', 1000, 0, 1, 0, 0, 0, '%', '', 2, '2018-12-27 12:32:11', NULL),
(130, 109, 201, NULL, 0, NULL, 'Phone', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 12:36:45', NULL),
(131, 110, 202, NULL, 0, NULL, 'Phone', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 12:36:45', NULL),
(132, 111, 201, 1, 0, NULL, 'IT Support', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:45:37', NULL),
(133, 112, 202, 1, 0, NULL, 'IT Support', 100, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:45:37', NULL),
(134, 113, 201, 1, 0, NULL, 'IT Support', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:46:39', NULL),
(135, 114, 202, 1, 0, NULL, 'IT Support', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:46:39', NULL),
(136, 115, 201, NULL, 0, NULL, 'de', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 12:48:43', NULL),
(137, 116, 202, NULL, 0, NULL, 'de', 100, 0, 1, 0, 0, 0, '%', '', 1, '2018-12-27 12:48:43', NULL),
(138, 117, 201, 5, 0, NULL, 'Web Design', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:50:35', NULL),
(139, 118, 202, 5, 0, NULL, 'Web Design', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2018-12-27 12:50:35', NULL),
(140, 119, 201, NULL, 0, NULL, 'T-Shirt', 100, 0, 1, 0, 0, 10, '%', '', 1, '2018-12-27 12:59:06', NULL),
(141, 120, 202, NULL, 0, NULL, 'T-Shirt', 100, 0, 1, 0, 0, 10, '%', '', 1, '2018-12-27 12:59:06', NULL),
(142, 119, 201, NULL, 0, NULL, 'T-Shirt2', 100, 0, 1, 0, 0, 10, '%', '', 2, '2018-12-27 12:59:06', NULL),
(143, 120, 202, NULL, 0, NULL, 'T-Shirt2', 100, 0, 1, 0, 0, 10, '%', '', 2, '2018-12-27 12:59:06', NULL),
(144, 121, 201, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 04:59:42', NULL),
(145, 122, 202, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 04:59:42', NULL),
(146, 121, 201, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 04:59:42', NULL),
(147, 122, 202, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 04:59:42', NULL),
(148, 123, 201, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 05:01:47', NULL),
(149, 124, 202, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 05:01:47', NULL),
(150, 123, 201, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 05:01:47', NULL),
(151, 124, 202, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 05:01:47', NULL),
(152, 125, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 10, '%', '', 1, '2018-12-28 05:04:12', NULL),
(153, 126, 202, 0, 0, '', 'Item 1', 1000, 0, 1, 0, 0, 10, '%', '', 1, '2018-12-28 05:04:12', NULL),
(154, 126, 202, NULL, 0, NULL, 'Item 2', 1000, 0, 1, 0, 0, 10, '%', '', 2, '2018-12-28 05:05:32', NULL),
(155, 127, 201, 1, 0, NULL, 'IT Support', 1000, 0, 1, 1, 0, 10, '%', '', 1, '2018-12-28 05:55:52', NULL),
(156, 128, 202, 1, 0, NULL, 'IT Support', 1000, 0, 1, 1, 0, 10, '%', '', 1, '2018-12-28 05:55:52', NULL),
(157, 127, 201, 3, 0, NULL, 'Software Development', 1000, 0, 1, 1, 0, 10, '%', '', 2, '2018-12-28 05:55:53', NULL),
(158, 128, 202, 3, 0, NULL, 'Software Development', 1000, 0, 1, 1, 0, 10, '%', '', 2, '2018-12-28 05:55:53', NULL),
(159, 129, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 10, '%', '', 1, '2018-12-28 05:58:22', NULL),
(160, 130, 202, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 10, '%', '', 1, '2018-12-28 05:58:23', NULL),
(161, 129, 201, NULL, 0, NULL, 'Item 2', 1000, 0, 1, 0, 0, 10, '%', '', 2, '2018-12-28 05:58:23', NULL),
(162, 130, 202, NULL, 0, NULL, 'Item 2', 1000, 0, 1, 0, 0, 10, '%', '', 2, '2018-12-28 05:58:23', NULL),
(163, 131, 201, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 05:59:36', NULL),
(164, 132, 202, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 05:59:36', NULL),
(165, 131, 201, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 05:59:36', NULL),
(166, 132, 202, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 05:59:36', NULL),
(167, 133, 201, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 06:01:03', NULL),
(168, 134, 202, 0, 0, '', 'Item 1', 100, 0, 10, 0, 0, 10, '%', '', 1, '2018-12-28 06:01:03', NULL),
(169, 133, 201, NULL, 0, NULL, 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 06:01:03', NULL),
(170, 134, 202, 0, 0, '', 'Item 2', 100, 0, 10, 0, 0, 10, '%', '', 2, '2018-12-28 06:01:03', NULL),
(171, 135, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 10, 1, 0, 0, '%', '', 1, '2019-01-02 12:32:36', NULL),
(172, 136, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 10, 1, 0, 0, '', '', 1, '2019-01-02 12:32:36', NULL),
(173, 135, 201, 7, 0, NULL, 'test', 10, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-02 12:32:36', NULL),
(174, 136, 202, 7, 0, NULL, 'test', 10, 0, 1, 1, 0, 0, '', '', 2, '2019-01-02 12:32:36', NULL),
(183, 149, 201, NULL, 0, 'tes2', 'Nokia-1200', 500, 0, 5, 0, 0, 2, '%', 'aa', 1, '2019-01-06 09:28:37', NULL),
(184, 150, 202, 0, 0, 'tes2', 'Nokia-1200', 500, 0, 5, 0, 0, 2, '%', '123', 1, '2019-01-06 09:28:37', NULL),
(185, 151, 201, 9, 0, NULL, 'Blackberry', 200, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 09:48:52', NULL),
(186, 152, 202, 9, 0, NULL, 'Blackberry', 200, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 09:48:52', NULL),
(187, 153, 201, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 10:02:05', NULL),
(188, 154, 202, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 10:02:05', NULL),
(189, 155, 201, 9, 0, NULL, 'Blackberry', 150, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 10:05:46', NULL),
(190, 156, 202, 9, 0, NULL, 'Blackberry', 150, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 10:05:46', NULL),
(191, 157, 201, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 10:10:09', NULL),
(192, 158, 202, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 10:10:09', NULL),
(193, 159, 201, 3, 0, NULL, 'Software Development', 850, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 11:12:22', NULL),
(194, 160, 202, 3, 0, NULL, 'Software Development', 850, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 11:12:22', NULL),
(195, 161, 201, 2, 0, NULL, 'Samsung Galaxy S7', 5050, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 12:30:29', NULL),
(196, 162, 202, 2, 0, NULL, 'Samsung Galaxy S7', 5050, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 12:30:29', NULL),
(197, 163, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 12:45:05', NULL),
(198, 164, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 12:45:05', NULL),
(199, 165, 201, 2, 0, NULL, 'Samsung Galaxy S7', 500, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 12:58:29', NULL),
(200, 166, 202, 2, 0, NULL, 'Samsung Galaxy S7', 500, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-06 12:58:29', NULL),
(201, 167, 201, 2, 0, NULL, 'Samsung Galaxy S7', 600, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-07 05:14:25', NULL),
(202, 168, 202, 2, 0, NULL, 'Samsung Galaxy S7', 600, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-07 05:14:25', NULL),
(203, 169, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-09 09:57:38', NULL),
(204, 170, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-09 09:57:38', NULL),
(205, 170, 202, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '', '', 2, '2019-01-09 10:01:01', NULL),
(206, 171, 201, 7, 0, NULL, 'test', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-13 06:28:41', NULL),
(207, 172, 202, 7, 0, NULL, 'test', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-13 06:28:41', NULL),
(208, 171, 201, 14, 0, NULL, '71 watch', 1000, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-13 06:28:41', NULL),
(209, 172, 202, 14, 0, NULL, '71 watch', 1000, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-13 06:28:41', NULL),
(211, 174, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-13 07:19:27', NULL),
(213, 176, 201, NULL, 0, NULL, 'Item 1', 100, 0, 10, 0, 0, 0, '%', '', 1, '2019-01-13 07:31:35', NULL),
(214, 177, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-13 07:47:47', NULL),
(215, 178, 201, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-13 07:47:53', NULL),
(216, 179, 202, NULL, 0, NULL, 'Item 1', 1000, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-13 07:47:53', NULL),
(217, 180, 201, 0, 0, NULL, 'Item 2', 100, 0, 1, 0, 0, 0, '', '', 1, '2019-01-13 10:35:20', NULL),
(218, 181, 201, NULL, 0, NULL, 'Item 2', 100, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-13 10:35:28', NULL),
(219, 182, 202, NULL, 0, NULL, 'Item 2', 100, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-13 10:35:28', NULL),
(220, 183, 201, 7, 0, NULL, 'test', 100, 0, 1, 1, 0, 0, '', '', 1, '2019-01-13 11:51:30', NULL),
(221, 184, 201, 12, 0, NULL, 'Nokia N3', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-15 05:09:34', NULL),
(222, 185, 202, 12, 0, NULL, 'Nokia N3', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-15 05:09:34', NULL),
(223, 186, 201, NULL, 0, NULL, 'Item 1', 100, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-15 05:11:52', NULL),
(224, 187, 202, NULL, 0, NULL, 'Item 1', 100, 0, 1, 0, 0, 0, '%', '', 1, '2019-01-15 05:11:52', NULL),
(225, 188, 201, 4, 0, NULL, 'Graphics Design', 6000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-20 06:38:57', NULL),
(226, 189, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-20 06:47:40', NULL),
(227, 189, 201, 8, 0, NULL, 'test', 500, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-20 06:47:40', NULL),
(228, 189, 201, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '%', '', 3, '2019-01-20 06:47:40', NULL),
(229, 190, 201, 3, 0, NULL, 'Software Development', 800000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-20 06:59:28', NULL),
(230, 190, 201, 5, 0, NULL, 'Web Design', 640000, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-20 06:59:28', NULL),
(231, 190, 201, 3, 0, NULL, 'Software Development', 800000, 0, 1, 1, 0, 0, '%', '', 3, '2019-01-20 06:59:28', NULL),
(232, 191, 201, 2, 0, NULL, 'Samsung Galaxy S7', 640000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-20 09:03:27', NULL),
(233, 192, 202, 2, 0, NULL, 'Samsung Galaxy S7', 640000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-20 09:03:27', NULL),
(234, 191, 201, 3, 0, NULL, 'Software Development', 800000, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-20 09:03:27', NULL),
(235, 192, 202, 3, 0, NULL, 'Software Development', 800000, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-20 09:03:27', NULL),
(236, 193, 201, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-21 11:46:45', NULL),
(237, 194, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-21 11:47:56', NULL),
(238, 195, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-21 11:47:56', NULL),
(239, 196, 202, 3, 0, NULL, 'Software Development', 10000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-22 10:07:06', NULL),
(240, 197, 201, 3, 0, NULL, 'Software Development', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-22 10:45:08', NULL),
(241, 198, 201, 3, 0, NULL, 'Software Development', 1000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-22 11:04:54', NULL),
(242, 199, 201, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-22 11:47:10', NULL),
(243, 200, 202, 1, 0, NULL, 'IT Support', 5000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-22 11:49:25', NULL),
(244, 201, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-22 12:11:09', NULL),
(245, 202, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-22 12:11:09', NULL),
(246, 203, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-22 12:16:30', NULL),
(247, 204, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-22 12:16:30', NULL),
(248, 205, 201, 2, 0, 'This is a test invoice', 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-23 09:55:43', NULL),
(249, 206, 202, 2, 0, 'This is a test invoice', 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-23 09:55:43', NULL),
(250, 207, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-23 11:06:07', NULL),
(251, 208, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-23 11:06:07', NULL),
(252, 209, 201, 2, 0, '', 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-24 11:58:00', NULL),
(253, 209, 201, 9, 0, '', 'Blackberry', 100, 0, 1, 1, 0, 0, '', '', 2, '2019-01-24 11:58:00', NULL),
(254, 210, 202, 2, 0, '', 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-24 11:58:34', NULL),
(255, 210, 202, 9, 0, '', 'Blackberry', 100, 0, 1, 1, 0, 0, '', '', 2, '2019-01-24 11:58:34', NULL),
(256, 211, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-27 09:20:31', NULL),
(257, 212, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-27 09:20:31', NULL),
(258, 211, 201, 7, 0, NULL, 'test', 10, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-27 09:20:31', NULL),
(259, 212, 202, 7, 0, NULL, 'test', 10, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-27 09:20:31', NULL),
(260, 213, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 500, 1, 0, 0, '%', '', 1, '2019-01-27 12:54:44', NULL),
(261, 214, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 500, 1, 0, 0, '%', '', 1, '2019-01-27 12:54:44', NULL),
(262, 215, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-27 13:04:59', NULL),
(263, 216, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '', '', 1, '2019-01-27 13:05:37', NULL),
(264, 217, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-29 06:50:01', NULL),
(265, 218, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-29 06:50:02', NULL),
(266, 217, 201, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-29 06:50:02', NULL),
(267, 218, 202, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 2, '2019-01-29 06:50:02', NULL),
(268, 219, 201, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-29 12:05:59', NULL),
(269, 220, 202, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-29 12:05:59', NULL),
(270, 221, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-29 13:02:20', NULL),
(271, 222, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-29 13:02:20', NULL),
(272, 221, 201, 19, 0, NULL, 'Oppo F7', 50, 0, 20, 1, 0, 0, '%', '', 2, '2019-01-29 13:02:20', NULL),
(273, 222, 202, 19, 0, NULL, 'Oppo F7', 50, 0, 20, 1, 0, 0, '%', '', 2, '2019-01-29 13:02:20', NULL),
(274, 223, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 2, 1, 0, 0, '%', '', 1, '2019-01-30 08:24:14', NULL),
(275, 223, 201, 9, 0, NULL, 'Blackberry', 100, 0, 2, 1, 0, 0, '%', '', 2, '2019-01-30 08:24:14', NULL),
(276, 224, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 2, 1, 0, 0, '%', '', 1, '2019-01-30 08:24:20', NULL),
(277, 224, 202, 9, 0, NULL, 'Blackberry', 100, 0, 2, 1, 0, 0, '%', '', 2, '2019-01-30 08:24:20', NULL),
(278, 225, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 11:17:01', NULL),
(279, 226, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 11:17:01', NULL),
(280, 227, 201, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 11:17:20', NULL),
(281, 228, 202, 9, 0, NULL, 'Blackberry', 100, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 11:17:20', NULL),
(282, 229, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 11:45:40', NULL),
(283, 230, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 11:45:40', NULL),
(284, 231, 201, 9, 0, NULL, 'Blackberry', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 13:06:10', NULL),
(285, 232, 202, 9, 0, NULL, 'Blackberry', 5000, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-30 13:06:10', NULL),
(286, 233, 201, 2, 0, NULL, 'Samsung Galaxy S7', 8557, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-31 05:46:28', NULL),
(287, 234, 202, 2, 0, NULL, 'Samsung Galaxy S7', 8557, 0, 1, 1, 0, 0, '%', '', 1, '2019-01-31 05:46:28', NULL),
(288, 235, 201, 9, 0, NULL, 'Blackberry', 0.25, 0, 1755, 1, 0, 0, '%', '', 1, '2019-01-31 05:48:16', NULL),
(289, 236, 202, 9, 0, NULL, 'Blackberry', 0.25, 0, 1755, 1, 0, 0, '%', '', 1, '2019-01-31 05:48:16', NULL),
(290, 237, 201, 20, 0, NULL, 'Oppo F7 (New)', 0.95, 0, 50, 1, 0, 0, '%', '', 1, '2019-01-31 05:51:35', NULL),
(292, 239, 201, 20, 0, NULL, 'Oppo F7 (New)', 0.98, 0, 52, 1, 0, 0, '%', '', 1, '2019-01-31 05:52:42', NULL),
(293, 240, 202, 20, 0, NULL, 'Oppo F7 (New)', 0.98, 0, 52, 1, 0, 0, '%', '', 1, '2019-01-31 05:52:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_tax`
--

DROP TABLE IF EXISTS `sales_tax`;
CREATE TABLE IF NOT EXISTS `sales_tax` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) UNSIGNED NOT NULL,
  `sales_details_id` int(11) UNSIGNED NOT NULL,
  `tax` double NOT NULL,
  `tax_amount` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_id` (`sales_id`),
  KEY `sales_details_id` (`sales_details_id`)
) ENGINE=InnoDB AUTO_INCREMENT=339 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_tax`
--

INSERT INTO `sales_tax` (`id`, `sales_id`, `sales_details_id`, `tax`, `tax_amount`) VALUES
(1, 1, 1, 4, 500),
(2, 2, 2, 4, 500),
(7, 4, 4, 3, 75),
(8, 4, 4, 4, 25),
(9, 5, 5, 2, 1200),
(10, 5, 5, 3, 1200),
(11, 5, 5, 4, 400),
(21, 7, 8, 2, 1500),
(23, 7, 10, 2, 1200),
(25, 7, 14, 2, 900),
(27, 7, 16, 2, 750),
(29, 8, 9, 2, 1500),
(30, 8, 11, 2, 1200),
(31, 8, 13, 2, 2400),
(32, 8, 15, 2, 900),
(33, 8, 17, 2, 750),
(34, 9, 18, 2, 1500),
(35, 10, 19, 2, 1500),
(36, 11, 20, 2, 750),
(37, 11, 20, 3, 750),
(38, 11, 20, 4, 250),
(51, 12, 21, 2, 2250),
(52, 12, 21, 3, 2250),
(53, 12, 21, 4, 750),
(72, 6, 6, 2, 2400),
(73, 6, 6, 3, 2400),
(74, 6, 6, 4, 800),
(75, 6, 7, 2, 750),
(76, 6, 7, 3, 750),
(77, 6, 7, 4, 250),
(78, 42, 51, 5, 166.66666666666663),
(79, 43, 52, 5, 166.66666666666663),
(80, 44, 53, 4, 47.619047619047706),
(81, 44, 53, 5, 166.66666666666663),
(84, 45, 54, 4, 50),
(85, 45, 54, 5, 200),
(86, 46, 55, 2, 130.43478260869563),
(87, 46, 55, 3, 130.43478260869563),
(88, 46, 55, 4, 47.619047619047706),
(89, 46, 55, 5, 166.66666666666663),
(90, 47, 56, 2, 130.43478260869563),
(91, 47, 56, 3, 130.43478260869563),
(92, 47, 56, 4, 47.619047619047706),
(93, 47, 56, 5, 166.66666666666663),
(94, 54, 63, 4, 47.619047619047706),
(95, 54, 63, 5, 166.66666666666663),
(96, 55, 64, 4, 47.619047619047706),
(97, 55, 64, 5, 166.66666666666663),
(98, 57, 66, 4, 4.761904761904759),
(99, 57, 66, 5, 16.666666666666657),
(100, 58, 67, 4, 4.761904761904759),
(101, 58, 67, 5, 16.666666666666657),
(102, 77, 86, 3, 13.043478260869563),
(103, 77, 86, 5, 16.666666666666657),
(104, 78, 87, 3, 13.043478260869563),
(105, 78, 87, 5, 16.666666666666657),
(106, 77, 88, 3, 13.043478260869563),
(107, 77, 88, 5, 16.666666666666657),
(108, 78, 89, 3, 13.043478260869563),
(109, 78, 89, 5, 16.666666666666657),
(110, 87, 98, 4, 5.714285714285722),
(111, 87, 98, 5, 20),
(112, 88, 99, 4, 5.714285714285722),
(113, 88, 99, 5, 20),
(114, 89, 100, 5, 1000),
(115, 90, 101, 5, 1000),
(116, 95, 106, 3, 750),
(117, 95, 106, 4, 250),
(118, 96, 107, 3, 750),
(119, 96, 107, 4, 250),
(120, 95, 108, 2, 1200),
(121, 95, 108, 5, 1600),
(122, 96, 109, 2, 1200),
(123, 96, 109, 5, 1600),
(124, 99, 112, 4, 4.761904761904759),
(125, 99, 112, 5, 16.666666666666657),
(126, 100, 113, 4, 4.761904761904759),
(127, 100, 113, 5, 16.666666666666657),
(128, 99, 114, 4, 4.761904761904759),
(129, 99, 114, 5, 16.666666666666657),
(130, 100, 115, 4, 4.761904761904759),
(131, 100, 115, 5, 16.666666666666657),
(132, 101, 116, 4, 47.619047619047706),
(133, 101, 116, 5, 166.66666666666663),
(134, 102, 117, 4, 47.619047619047706),
(135, 102, 117, 5, 166.66666666666663),
(136, 101, 118, 4, 47.619047619047706),
(137, 101, 118, 5, 166.66666666666663),
(138, 102, 119, 4, 47.619047619047706),
(139, 102, 119, 5, 166.66666666666663),
(140, 103, 120, 4, 4.761904761904759),
(141, 103, 120, 5, 16.666666666666657),
(142, 104, 121, 4, 4.761904761904759),
(143, 104, 121, 5, 16.666666666666657),
(144, 105, 122, 4, 4.761904761904759),
(145, 105, 122, 5, 16.666666666666657),
(146, 106, 123, 4, 4.761904761904759),
(147, 106, 123, 5, 16.666666666666657),
(148, 105, 124, 4, 4.761904761904759),
(149, 105, 124, 5, 16.666666666666657),
(150, 106, 125, 4, 4.761904761904759),
(151, 106, 125, 5, 16.666666666666657),
(152, 107, 126, 2, 130.43478260869563),
(153, 107, 126, 4, 47.619047619047706),
(154, 108, 127, 2, 130.43478260869563),
(155, 108, 127, 4, 47.619047619047706),
(156, 107, 128, 4, 47.619047619047706),
(157, 107, 128, 5, 166.66666666666663),
(158, 108, 129, 4, 47.619047619047706),
(159, 108, 129, 5, 166.66666666666663),
(160, 109, 130, 4, 4.761904761904759),
(161, 109, 130, 5, 16.666666666666657),
(162, 110, 131, 4, 4.761904761904759),
(163, 110, 131, 5, 16.666666666666657),
(164, 111, 132, 4, 4.761904761904759),
(165, 111, 132, 6, 16.666666666666657),
(166, 112, 133, 4, 4.761904761904759),
(167, 112, 133, 6, 16.666666666666657),
(168, 113, 134, 4, 47.619047619047706),
(169, 113, 134, 6, 166.66666666666663),
(170, 114, 135, 4, 47.619047619047706),
(171, 114, 135, 6, 166.66666666666663),
(172, 115, 136, 4, 4.761904761904759),
(173, 115, 136, 6, 16.666666666666657),
(174, 116, 137, 4, 4.761904761904759),
(175, 116, 137, 6, 16.666666666666657),
(176, 117, 138, 4, 47.619047619047706),
(177, 117, 138, 6, 166.66666666666663),
(178, 118, 139, 4, 47.619047619047706),
(179, 118, 139, 6, 166.66666666666663),
(180, 119, 140, 4, 4.761904761904759),
(181, 119, 140, 6, 16.666666666666657),
(182, 120, 141, 4, 4.761904761904759),
(183, 120, 141, 6, 16.666666666666657),
(184, 119, 142, 4, 4.761904761904759),
(185, 119, 142, 6, 16.666666666666657),
(186, 120, 143, 4, 4.761904761904759),
(187, 120, 143, 6, 16.666666666666657),
(188, 121, 144, 4, 47.619047619047706),
(189, 121, 144, 6, 166.66666666666663),
(190, 122, 145, 4, 47.619047619047706),
(191, 122, 145, 6, 166.66666666666663),
(192, 123, 148, 4, 47.619047619047706),
(193, 123, 148, 6, 166.66666666666663),
(194, 124, 149, 4, 47.619047619047706),
(195, 124, 149, 6, 166.66666666666663),
(196, 123, 150, 4, 47.619047619047706),
(197, 123, 150, 6, 166.66666666666663),
(198, 124, 151, 4, 47.619047619047706),
(199, 124, 151, 6, 166.66666666666663),
(200, 125, 152, 4, 50),
(201, 125, 152, 6, 200),
(204, 126, 153, 4, 50),
(205, 126, 153, 6, 200),
(206, 126, 154, 4, 50),
(207, 126, 154, 6, 200),
(208, 127, 155, 4, 50),
(209, 127, 155, 6, 200),
(210, 128, 156, 4, 50),
(211, 128, 156, 6, 200),
(212, 127, 157, 4, 50),
(213, 127, 157, 6, 200),
(214, 128, 158, 4, 50),
(215, 128, 158, 6, 200),
(216, 129, 159, 4, 50),
(217, 129, 159, 6, 200),
(218, 130, 160, 4, 50),
(219, 130, 160, 6, 200),
(220, 129, 161, 4, 50),
(221, 129, 161, 6, 200),
(222, 130, 162, 4, 50),
(223, 130, 162, 6, 200),
(224, 131, 163, 4, 47.619047619047706),
(225, 131, 163, 6, 166.66666666666663),
(226, 132, 164, 4, 47.619047619047706),
(227, 132, 164, 6, 166.66666666666663),
(228, 131, 165, 4, 47.619047619047706),
(229, 131, 165, 6, 166.66666666666663),
(230, 132, 166, 4, 47.619047619047706),
(231, 132, 166, 6, 166.66666666666663),
(232, 133, 167, 4, 47.619047619047706),
(233, 133, 167, 6, 166.66666666666663),
(236, 133, 169, 4, 47.619047619047706),
(237, 133, 169, 6, 166.66666666666663),
(244, 134, 168, 4, 50),
(245, 134, 168, 6, 200),
(246, 134, 170, 4, 50),
(247, 134, 170, 6, 200),
(249, 135, 171, 2, 12000),
(250, 135, 171, 3, 12000),
(251, 135, 171, 4, 4000),
(255, 135, 173, 2, 1.5),
(256, 135, 173, 4, 0.5),
(265, 136, 172, 2, 10434.782608695648),
(266, 136, 172, 3, 10434.782608695648),
(267, 136, 172, 4, 3809.5238095238165),
(268, 136, 172, 6, 13333.333333333328),
(269, 136, 174, 2, 1.3043478260869552),
(270, 136, 174, 4, 0.4761904761904763),
(277, 149, 183, 4, 125),
(279, 150, 184, 4, 125),
(280, 163, 197, 4, 380.95238095238165),
(281, 164, 198, 4, 380.95238095238165),
(282, 171, 206, 2, 130.43478260869563),
(283, 172, 207, 2, 130.43478260869563),
(284, 171, 208, 6, 166.66666666666663),
(285, 172, 209, 6, 166.66666666666663),
(286, 174, 211, 4, 50),
(287, 174, 211, 6, 200),
(288, 176, 213, 4, 47.619047619047706),
(289, 176, 213, 6, 166.66666666666663),
(290, 177, 214, 4, 47.619047619047706),
(291, 177, 214, 6, 166.66666666666663),
(292, 178, 215, 4, 47.619047619047706),
(293, 178, 215, 6, 166.66666666666663),
(294, 179, 216, 4, 47.619047619047706),
(295, 179, 216, 6, 166.66666666666663),
(298, 181, 218, 4, 4.761904761904759),
(299, 181, 218, 6, 16.666666666666657),
(300, 182, 219, 4, 4.761904761904759),
(301, 182, 219, 6, 16.666666666666657),
(315, 180, 217, 4, 4.761904761904759),
(316, 180, 217, 6, 16.666666666666657),
(318, 183, 220, 3, 13.043478260869563),
(319, 184, 221, 6, 16.666666666666657),
(320, 185, 222, 6, 16.666666666666657),
(321, 186, 223, 4, 4.761904761904759),
(322, 186, 223, 6, 16.666666666666657),
(323, 187, 224, 4, 4.761904761904759),
(324, 187, 224, 6, 16.666666666666657),
(325, 205, 248, 4, 400),
(326, 206, 249, 4, 400),
(327, 213, 260, 4, 200000),
(328, 213, 260, 7, 100000),
(329, 214, 261, 4, 200000),
(330, 214, 261, 7, 100000),
(331, 217, 264, 4, 380.95238095238165),
(332, 218, 265, 4, 380.95238095238165),
(333, 217, 266, 7, 2.439024390243901),
(334, 218, 267, 7, 2.439024390243901),
(335, 221, 272, 7, 25),
(336, 222, 273, 7, 25),
(337, 223, 274, 4, 761.9047619047633),
(338, 224, 276, 4, 761.9047619047633);

-- --------------------------------------------------------

--
-- Table structure for table `sales_types`
--

DROP TABLE IF EXISTS `sales_types`;
CREATE TABLE IF NOT EXISTS `sales_types` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sales_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `tax_included` int(11) NOT NULL,
  `factor` double NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_types`
--

INSERT INTO `sales_types` (`id`, `sales_type`, `tax_included`, `factor`, `defaults`) VALUES
(1, 'Retail', 1, 0, 1),
(2, 'Wholesale', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sale_prices`
--

DROP TABLE IF EXISTS `sale_prices`;
CREATE TABLE IF NOT EXISTS `sale_prices` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(10) UNSIGNED NOT NULL,
  `sales_type_id` int(10) UNSIGNED NOT NULL,
  `curr_abrev` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_prices_item_id_foreign` (`item_id`),
  KEY `sale_prices_sales_type_id_foreign` (`sales_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sale_prices`
--

INSERT INTO `sale_prices` (`id`, `item_id`, `sales_type_id`, `curr_abrev`, `price`) VALUES
(1, 1, 1, 'USD', 5000),
(2, 1, 2, 'USD', 5000),
(3, 2, 1, 'USD', 8000),
(4, 2, 2, 'USD', 8000),
(5, 3, 1, 'USD', 1000),
(6, 3, 2, 'USD', 10000),
(7, 4, 1, 'USD', 6000),
(8, 4, 2, 'USD', 6000),
(9, 5, 1, 'USD', 8000),
(10, 5, 2, 'USD', 8000),
(11, 6, 1, 'USD', 16000),
(12, 6, 2, 'USD', 16000),
(13, 7, 1, 'USD', 10),
(14, 7, 2, 'USD', 10),
(15, 8, 1, 'USD', 0),
(16, 8, 2, 'USD', 0),
(17, 9, 1, 'USD', 100),
(18, 9, 2, 'USD', 100),
(19, 10, 1, 'USD', 0),
(20, 10, 2, 'USD', 0),
(21, 11, 1, 'USD', 0),
(22, 11, 2, 'USD', 0),
(23, 12, 1, 'USD', 2000),
(24, 12, 2, 'USD', 2000),
(25, 13, 1, 'USD', 10.15),
(26, 13, 2, 'USD', 0),
(27, 14, 1, 'USD', 50),
(28, 14, 2, 'USD', 50),
(29, 15, 1, 'USD', 50),
(30, 15, 2, 'USD', 50),
(31, 16, 1, 'USD', 20),
(32, 16, 2, 'USD', 20),
(33, 17, 1, 'USD', 20),
(34, 17, 2, 'USD', 20),
(35, 18, 1, 'USD', 20),
(36, 18, 2, 'USD', 20),
(37, 19, 1, 'USD', 50),
(38, 19, 2, 'USD', 50),
(39, 20, 1, 'USD', 0),
(40, 20, 2, 'USD', 0),
(41, 21, 1, 'USD', 0),
(42, 21, 2, 'USD', 0);

-- --------------------------------------------------------

--
-- Table structure for table `security_role`
--

DROP TABLE IF EXISTS `security_role`;
CREATE TABLE IF NOT EXISTS `security_role` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sections` text COLLATE utf8_unicode_ci,
  `areas` text COLLATE utf8_unicode_ci,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `security_role`
--

INSERT INTO `security_role` (`id`, `role`, `description`, `sections`, `areas`, `inactive`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'System Administrator', 'a:26:{s:8:\"category\";s:3:\"100\";s:4:\"unit\";s:3:\"600\";s:3:\"loc\";s:3:\"200\";s:4:\"item\";s:3:\"300\";s:4:\"user\";s:3:\"400\";s:4:\"role\";s:3:\"500\";s:8:\"customer\";s:3:\"700\";s:8:\"purchase\";s:3:\"900\";s:8:\"supplier\";s:4:\"1000\";s:7:\"payment\";s:4:\"1400\";s:6:\"backup\";s:4:\"1500\";s:5:\"email\";s:4:\"1600\";s:9:\"emailtemp\";s:4:\"1700\";s:10:\"preference\";s:4:\"1800\";s:3:\"tax\";s:4:\"1900\";s:10:\"currencies\";s:4:\"2100\";s:11:\"paymentterm\";s:4:\"2200\";s:13:\"paymentmethod\";s:4:\"2300\";s:14:\"companysetting\";s:4:\"2400\";s:10:\"iecategory\";s:4:\"2600\";s:7:\"expense\";s:4:\"2700\";s:7:\"deposit\";s:4:\"3000\";s:9:\"quotation\";s:4:\"2800\";s:7:\"invoice\";s:4:\"2900\";s:12:\"bank_account\";s:4:\"3100\";s:21:\"bank_account_transfer\";s:4:\"3200\";}', 'a:59:{s:7:\"cat_add\";s:3:\"101\";s:8:\"cat_edit\";s:3:\"102\";s:10:\"cat_delete\";s:3:\"103\";s:8:\"unit_add\";s:3:\"601\";s:9:\"unit_edit\";s:3:\"602\";s:11:\"unit_delete\";s:3:\"603\";s:7:\"loc_add\";s:3:\"201\";s:8:\"loc_edit\";s:3:\"202\";s:10:\"loc_delete\";s:3:\"203\";s:8:\"item_add\";s:3:\"301\";s:9:\"item_edit\";s:3:\"302\";s:11:\"item_delete\";s:3:\"303\";s:8:\"user_add\";s:3:\"401\";s:9:\"user_edit\";s:3:\"402\";s:11:\"user_delete\";s:3:\"403\";s:12:\"customer_add\";s:3:\"701\";s:13:\"customer_edit\";s:3:\"702\";s:15:\"customer_delete\";s:3:\"703\";s:12:\"purchase_add\";s:3:\"901\";s:13:\"purchase_edit\";s:3:\"902\";s:15:\"purchase_delete\";s:3:\"903\";s:12:\"supplier_add\";s:4:\"1001\";s:13:\"supplier_edit\";s:4:\"1002\";s:15:\"supplier_delete\";s:4:\"1003\";s:11:\"payment_add\";s:4:\"1401\";s:12:\"payment_edit\";s:4:\"1402\";s:14:\"payment_delete\";s:4:\"1403\";s:10:\"backup_add\";s:4:\"1501\";s:15:\"backup_download\";s:4:\"1502\";s:7:\"tax_add\";s:4:\"1901\";s:8:\"tax_edit\";s:4:\"1902\";s:10:\"tax_delete\";s:4:\"1903\";s:14:\"currencies_add\";s:4:\"2101\";s:15:\"currencies_edit\";s:4:\"2102\";s:17:\"currencies_delete\";s:4:\"2103\";s:15:\"paymentterm_add\";s:4:\"2201\";s:16:\"paymentterm_edit\";s:4:\"2202\";s:18:\"paymentterm_delete\";s:4:\"2203\";s:17:\"paymentmethod_add\";s:4:\"2301\";s:18:\"paymentmethod_edit\";s:4:\"2302\";s:20:\"paymentmethod_delete\";s:4:\"2303\";s:11:\"expense_add\";s:4:\"2701\";s:12:\"expense_edit\";s:4:\"2702\";s:14:\"expense_delete\";s:4:\"2703\";s:11:\"deposit_add\";s:4:\"3001\";s:12:\"deposit_edit\";s:4:\"3002\";s:14:\"deposit_delete\";s:4:\"3003\";s:13:\"quotation_add\";s:4:\"2801\";s:14:\"quotation_edit\";s:4:\"2802\";s:16:\"quotation_delete\";s:4:\"2803\";s:11:\"invoice_add\";s:4:\"2901\";s:12:\"invoice_edit\";s:4:\"2902\";s:14:\"invoice_delete\";s:4:\"2903\";s:16:\"bank_account_add\";s:4:\"3101\";s:17:\"bank_account_edit\";s:4:\"3102\";s:19:\"bank_account_delete\";s:4:\"3103\";s:25:\"bank_account_transfer_add\";s:4:\"3201\";s:26:\"bank_account_transfer_edit\";s:4:\"3202\";s:28:\"bank_account_transfer_delete\";s:4:\"3203\";}', 0, '2018-11-05 01:21:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
CREATE TABLE IF NOT EXISTS `shipments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `packed_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment_details`
--

DROP TABLE IF EXISTS `shipment_details`;
CREATE TABLE IF NOT EXISTS `shipment_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `shipment_id` int(10) UNSIGNED NOT NULL,
  `order_no` int(11) NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `tax_type_id` int(10) UNSIGNED NOT NULL,
  `unit_price` double NOT NULL,
  `quantity` double NOT NULL,
  `discount_percent` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipment_details_shipment_id_foreign` (`shipment_id`),
  KEY `shipment_details_item_id_foreign` (`item_id`),
  KEY `shipment_details_tax_type_id_foreign` (`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_config`
--

DROP TABLE IF EXISTS `sms_config`;
CREATE TABLE IF NOT EXISTS `sms_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secretkey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `default_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sms_config`
--

INSERT INTO `sms_config` (`id`, `type`, `status`, `key`, `secretkey`, `default`, `default_number`) VALUES
(1, 'nexmo', 'Active', '1aaf1a0a', 'eWi2sO2WZrkqfi7R', 'Yes', '8801521108069');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment`
--

DROP TABLE IF EXISTS `stock_adjustment`;
CREATE TABLE IF NOT EXISTS `stock_adjustment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `location` varchar(10) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `total` double NOT NULL,
  `note` text,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_adjustment`
--

INSERT INTO `stock_adjustment` (`id`, `user_id`, `location`, `trans_type`, `total`, `note`, `date`) VALUES
(1, 1, 'PL', 601, 2, 'Test stock adjustment', '2019-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_details`
--

DROP TABLE IF EXISTS `stock_adjustment_details`;
CREATE TABLE IF NOT EXISTS `stock_adjustment_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `adjustment_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `quantity` double NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_adjustment_details`
--

INSERT INTO `stock_adjustment_details` (`id`, `adjustment_id`, `item_id`, `description`, `quantity`, `created_at`) VALUES
(1, 1, 11, 'blackberry2', 2, '2019-02-03 12:53:17');

-- --------------------------------------------------------

--
-- Table structure for table `stock_category`
--

DROP TABLE IF EXISTS `stock_category`;
CREATE TABLE IF NOT EXISTS `stock_category` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dflt_units` int(11) NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stock_category`
--

INSERT INTO `stock_category` (`id`, `description`, `dflt_units`, `inactive`, `created_at`, `updated_at`) VALUES
(1, 'Default', 1, 0, '2018-11-05 01:21:57', NULL),
(2, 'Hardware', 1, 0, '2018-11-05 01:21:57', NULL),
(3, 'Health & Beauty', 1, 0, '2018-11-05 01:21:57', NULL),
(4, 'Hardware', 1, 0, '2019-01-24 02:36:06', '2019-01-24 02:36:06'),
(6, 'Maintenance', 2, 0, '2019-01-27 06:43:58', '2019-01-27 06:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `stock_master`
--

DROP TABLE IF EXISTS `stock_master`;
CREATE TABLE IF NOT EXISTS `stock_master` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `tax_type_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `long_description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `units` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `stock_master_item_id_foreign` (`item_id`),
  KEY `stock_master_category_id_foreign` (`category_id`),
  KEY `stock_master_tax_type_id_foreign` (`tax_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stock_master`
--

INSERT INTO `stock_master` (`id`, `item_id`, `category_id`, `tax_type_id`, `description`, `long_description`, `units`, `inactive`, `deleted_status`) VALUES
(1, 1, 1, 1, 'IT Support', NULL, 'Each', 0, 0),
(2, 2, 1, 1, 'Samsung Galaxy S7', NULL, 'Each', 0, 0),
(3, 3, 1, 1, 'Software Development', NULL, 'Each', 0, 0),
(4, 4, 1, 1, 'Graphics Design', NULL, 'Each', 0, 0),
(5, 5, 1, 1, 'Web Design', NULL, 'Each', 0, 0),
(6, 6, 1, 1, 'Web Development', NULL, 'Each', 0, 0),
(7, 7, 2, 1, 'test', NULL, 'Each', 0, 0),
(8, 8, 1, 1, 'test', 'test', 'Each', 0, 0),
(9, 9, 1, 1, 'Blackberry', NULL, 'Each', 0, 0),
(10, 10, 1, 1, 'blackberry1', 'blackberry1', 'Each', 0, 0),
(11, 11, 1, 1, 'blackberry2', 'blackberry2', 'Each', 0, 0),
(12, 12, 1, 1, 'Nokia N3', NULL, 'Each', 0, 0),
(13, 13, 1, 1, 'Nokia', 'is it required?', 'Each', 0, 0),
(14, 14, 2, 1, '71 watch', NULL, 'Each', 0, 0),
(15, 15, 1, 1, 'Watch 81', NULL, 'Each', 0, 1),
(16, 16, 1, 1, 'dfdsfs', NULL, 'Each', 0, 0),
(17, 17, 1, 1, 'dfdsfsddd', NULL, 'Each', 0, 1),
(18, 18, 1, 1, 'sdfsdf', NULL, 'Each', 0, 0),
(19, 19, 1, 1, 'Oppo F7', NULL, 'Each', 0, 0),
(20, 20, 1, 1, 'Oppo F7 (New)', 'test', 'Each', 0, 0),
(21, 21, 1, 1, 'dsfd', NULL, 'unit name', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_moves`
--

DROP TABLE IF EXISTS `stock_moves`;
CREATE TABLE IF NOT EXISTS `stock_moves` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(10) UNSIGNED NOT NULL,
  `order_no` int(11) NOT NULL,
  `trans_type` int(11) NOT NULL DEFAULT '0',
  `loc_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tran_date` date NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `order_reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_reference_id` int(11) NOT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `receive_id` int(11) NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qty` double NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `stock_moves_item_id_foreign` (`item_id`),
  KEY `stock_moves_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stock_moves`
--

INSERT INTO `stock_moves` (`id`, `item_id`, `order_no`, `trans_type`, `loc_code`, `tran_date`, `user_id`, `order_reference`, `reference`, `transaction_reference_id`, `transfer_id`, `receive_id`, `note`, `qty`, `price`) VALUES
(1, 2, 5, 202, 'PL', '2018-12-09', 1, 'QN-0003', 'store_out_6', 6, NULL, 0, NULL, -2, 0),
(2, 1, 6, 202, 'PL', '2018-12-09', 1, 'INV-0003', 'store_out_6', 6, NULL, 0, NULL, -1, 0),
(3, 3, 7, 202, 'PL', '2018-12-09', 1, 'QN-0004', 'store_out_8', 8, NULL, 0, NULL, -1, 0),
(4, 5, 7, 202, 'PL', '2018-12-09', 1, 'QN-0004', 'store_out_8', 8, NULL, 0, NULL, -1, 0),
(5, 6, 7, 202, 'PL', '2018-12-09', 1, 'QN-0004', 'store_out_8', 8, NULL, 0, NULL, -1, 0),
(6, 4, 7, 202, 'PL', '2018-12-09', 1, 'QN-0004', 'store_out_8', 8, NULL, 0, NULL, -1, 0),
(7, 1, 7, 202, 'PL', '2018-12-09', 1, 'QN-0004', 'store_out_8', 8, NULL, 0, NULL, -1, 0),
(9, 1, 11, 202, 'PL', '2018-12-10', 1, 'QN-0006', 'store_out_12', 12, NULL, 0, NULL, -3, 0),
(10, 6, 13, 202, 'PL', '2018-12-11', 1, 'QN-0007', 'store_out_14', 14, NULL, 0, NULL, -1, 0),
(11, 2, 15, 202, 'PL', '2018-12-11', 1, 'QN-0008', 'store_out_16', 16, NULL, 0, NULL, -1, 0),
(12, 2, 18, 202, 'PL', '2018-12-19', 1, 'QN-0010', 'store_out_19', 19, NULL, 0, NULL, -1, 0),
(13, 2, 20, 202, 'PL', '2018-12-22', 1, 'QN-0011', 'store_out_21', 21, NULL, 0, NULL, -10, 0),
(14, 2, 22, 202, 'PL', '2018-12-22', 1, 'QN-0012', 'store_out_23', 23, NULL, 0, NULL, -1, 0),
(15, 2, 24, 202, 'PL', '2018-12-23', 1, 'QN-0013', 'store_out_25', 25, NULL, 0, NULL, -1, 0),
(16, 2, 26, 202, 'PL', '2018-12-23', 1, 'QN-0014', 'store_out_27', 27, NULL, 0, NULL, -1, 0),
(17, 3, 28, 202, 'PL', '2018-12-24', 1, 'QN-0015', 'store_out_29', 29, NULL, 0, NULL, -1, 0),
(18, 4, 30, 202, 'PL', '2018-12-24', 1, 'QN-0016', 'store_out_31', 31, NULL, 0, NULL, -1, 0),
(19, 4, 32, 202, 'PL', '2018-12-24', 1, 'QN-0017', 'store_out_33', 33, NULL, 0, NULL, -2, 0),
(20, 3, 34, 202, 'PL', '2018-12-24', 1, 'QN-0018', 'store_out_35', 35, NULL, 0, NULL, -1, 0),
(21, 3, 36, 202, 'PL', '2018-12-24', 1, 'QN-0019', 'store_out_37', 37, NULL, 0, NULL, -1, 0),
(22, 3, 38, 202, 'PL', '2018-12-24', 1, 'QN-0020', 'store_out_39', 39, NULL, 0, NULL, -1, 0),
(23, 3, 40, 202, 'PL', '2018-12-24', 1, 'QN-0021', 'store_out_41', 41, NULL, 0, NULL, -1, 0),
(24, 2, 50, 202, 'PL', '2018-12-26', 15, 'QN-0027', 'store_out_51', 51, NULL, 0, NULL, -1, 0),
(25, 2, 52, 202, 'PL', '2018-12-27', 15, 'QN-0028', 'store_out_53', 53, NULL, 0, NULL, -1, 0),
(26, 2, 0, 102, 'PL', '2018-12-27', 15, '', 'store_in_1', 1, NULL, 1, NULL, 1, 500),
(27, 2, 0, 102, 'PL', '2018-12-27', 1, '', 'store_in_2', 2, NULL, 2, NULL, 1, 4000),
(28, 7, 57, 202, 'PL', '2018-12-27', 1, 'QN-0031', 'store_out_58', 58, NULL, 0, NULL, -1, 0),
(29, 2, 59, 202, 'PL', '2018-12-27', 1, 'QN-0032', 'store_out_60', 60, NULL, 0, NULL, -5, 8000),
(31, 2, 63, 202, 'PL', '2018-12-27', 1, 'QN-0034', 'store_out_64', 64, NULL, 0, NULL, -1, 0),
(33, 2, 67, 202, 'PL', '2018-12-27', 1, 'QN-0036', 'store_out_68', 68, NULL, 0, NULL, -1, 0),
(34, 2, 71, 202, 'PL', '2018-12-27', 1, 'QN-0039', 'store_out_72', 72, NULL, 0, NULL, -1, 8000),
(41, 1, 87, 202, 'PL', '2018-12-27', 1, 'QN-0047', 'store_out_88', 88, NULL, 0, NULL, -1, 0),
(42, 1, 89, 202, 'PL', '2018-12-27', 1, 'QN-0048', 'store_out_90', 90, NULL, 0, NULL, -1, 0),
(43, 3, 91, 202, 'PL', '2018-12-27', 1, 'QN-0049', 'store_out_92', 92, NULL, 0, NULL, -1, 0),
(44, 3, 93, 202, 'PL', '2018-12-27', 15, 'QN-0050', 'store_out_94', 94, NULL, 0, NULL, -1, 0),
(45, 1, 95, 202, 'PL', '2018-12-27', 1, 'QN-0051', 'store_out_96', 96, NULL, 0, NULL, -1, 0),
(46, 5, 95, 202, 'PL', '2018-12-27', 1, 'QN-0051', 'store_out_96', 96, NULL, 0, NULL, -1, 0),
(47, 2, 97, 202, 'PL', '2018-12-27', 1, 'QN-0052', 'store_out_98', 98, NULL, 0, NULL, -1, 8000),
(48, 1, 99, 202, 'PL', '2018-12-27', 1, 'QN-0053', 'store_out_100', 100, NULL, 0, NULL, -1, 0),
(49, 4, 99, 202, 'PL', '2018-12-27', 1, 'QN-0053', 'store_out_100', 100, NULL, 0, NULL, -1, 0),
(50, 4, 105, 202, 'PL', '2018-12-27', 1, 'QN-0056', 'store_out_106', 106, NULL, 0, NULL, -1, 0),
(51, 5, 105, 202, 'PL', '2018-12-27', 1, 'QN-0056', 'store_out_106', 106, NULL, 0, NULL, -1, 0),
(52, 1, 111, 202, 'PL', '2018-12-27', 1, 'QN-0059', 'store_out_112', 112, NULL, 0, NULL, -1, 0),
(53, 1, 113, 202, 'PL', '2018-12-27', 1, 'QN-0060', 'store_out_114', 114, NULL, 0, NULL, -1, 0),
(54, 5, 117, 202, 'PL', '2018-12-27', 1, 'QN-0062', 'store_out_118', 118, NULL, 0, NULL, -1, 0),
(55, 1, 127, 202, 'PL', '2018-12-28', 1, 'QN-0067', 'store_out_128', 128, NULL, 0, NULL, -1, 0),
(56, 3, 127, 202, 'PL', '2018-12-28', 1, 'QN-0067', 'store_out_128', 128, NULL, 0, NULL, -1, 0),
(57, 2, 135, 202, 'PL', '2019-01-02', 1, 'QN-0071', 'store_out_136', 136, NULL, 0, NULL, -10, 0),
(58, 7, 135, 202, 'PL', '2019-01-02', 1, 'QN-0071', 'store_out_136', 136, NULL, 0, NULL, -1, 0),
(59, 3, 0, 102, 'PL', '2019-01-03', 1, '', 'store_in_3', 3, NULL, 3, NULL, 100, 20),
(64, 9, 151, 202, 'PL', '2019-01-06', 1, 'QN-0073', 'store_out_152', 152, NULL, 0, NULL, -1, 0),
(65, 9, 153, 202, 'PL', '2019-01-06', 1, 'QN-0074', 'store_out_154', 154, NULL, 0, NULL, -1, 0),
(66, 9, 155, 202, 'PL', '2019-01-06', 1, 'QN-0075', 'store_out_156', 156, NULL, 0, NULL, -1, 0),
(67, 4, 157, 202, 'PL', '2019-01-06', 1, 'QN-0076', 'store_out_158', 158, NULL, 0, NULL, -1, 0),
(68, 3, 159, 202, 'PL', '2019-01-06', 1, 'QN-0077', 'store_out_160', 160, NULL, 0, NULL, -1, 0),
(69, 2, 161, 202, 'PL', '2019-01-06', 1, 'QN-0078', 'store_out_162', 162, NULL, 0, NULL, -1, 0),
(70, 2, 163, 202, 'PL', '2019-01-06', 1, 'QN-0079', 'store_out_164', 164, NULL, 0, NULL, -1, 0),
(71, 2, 165, 202, 'PL', '2019-01-06', 1, 'QN-0080', 'store_out_166', 166, NULL, 0, NULL, -1, 0),
(72, 2, 167, 202, 'PL', '2019-01-07', 1, 'QN-0081', 'store_out_168', 168, NULL, 0, NULL, -1, 0),
(73, 12, 0, 102, 'PL', '2019-01-07', 1, '', 'store_in_6', 6, NULL, 4, NULL, 20, 150),
(74, 14, 0, 603, 'PL', '2019-01-08', 1, '', 'store_in_14', 14, NULL, 0, 'Added from initial stock', 21, 20),
(75, 15, 0, 603, 'PL', '2019-01-08', 1, '', 'store_in_15', 15, NULL, 0, 'Added from initial stock', 5, 10),
(76, 16, 0, 603, 'PL', '2019-01-08', 1, '', 'store_in_16', 16, NULL, 0, 'Added from initial stock', 20, 20),
(77, 18, 0, 603, 'PL', '2019-01-08', 1, '', 'store_in_18', 18, NULL, 0, 'Added from initial stock', 10, 52),
(78, 2, 169, 202, 'PL', '2019-01-09', 1, 'QN-0082', 'store_out_170', 170, NULL, 0, NULL, -1, 0),
(79, 9, 170, 202, 'PL', '2019-01-09', 1, 'INV-0075', 'store_out_170', 170, NULL, 0, NULL, -1, 0),
(80, 7, 171, 202, 'PL', '2019-01-13', 1, 'QN-0083', 'store_out_172', 172, NULL, 0, NULL, -1, 0),
(81, 14, 171, 202, 'PL', '2019-01-13', 1, 'QN-0083', 'store_out_172', 172, NULL, 0, NULL, -1, 0),
(82, 9, 0, 102, 'PL', '2019-01-14', 1, '', 'store_in_7', 7, NULL, 5, NULL, 1, 100),
(83, 11, 0, 102, 'PL', '2019-01-14', 1, '', 'store_in_7', 7, NULL, 5, NULL, 1, 100),
(84, 12, 184, 202, 'PL', '2019-01-15', 1, 'QN-0091', 'store_out_185', 185, NULL, 0, NULL, -1, 0),
(85, 2, 191, 202, 'PL', '2019-01-20', 1, 'QN-0096', 'store_out_192', 192, NULL, 0, NULL, -1, 0),
(86, 3, 191, 202, 'PL', '2019-01-20', 1, 'QN-0096', 'store_out_192', 192, NULL, 0, NULL, -1, 0),
(87, 9, 0, 102, 'PL', '2019-01-21', 1, '', 'store_in_8', 8, NULL, 6, NULL, 99, 8000),
(88, 2, 0, 102, 'PL', '2019-01-21', 1, '', 'store_in_8', 8, NULL, 6, NULL, 1, 100),
(89, 10, 0, 102, 'PL', '2019-01-21', 1, '', 'store_in_8', 8, NULL, 6, NULL, 1, 0.75),
(90, 9, 0, 102, 'PL', '2019-01-21', 1, '', 'store_in_8', 8, NULL, 7, NULL, 1, 8000),
(91, 2, 194, 202, 'PL', '2019-01-21', 1, 'QN-0098', 'store_out_195', 195, NULL, 0, NULL, -1, 0),
(92, 3, 193, 202, 'PL', '2019-01-22', 1, 'QN-0097', 'store_out_196', 196, NULL, 0, NULL, -1, 10000),
(93, 1, 199, 202, 'PL', '2019-01-22', 1, 'QN-0101', 'store_out_200', 200, NULL, 0, NULL, -1, 5000),
(94, 2, 201, 202, 'PL', '2019-01-22', 1, 'QN-0102', 'store_out_202', 202, NULL, 0, NULL, -1, 0),
(95, 2, 203, 202, 'PL', '2019-01-22', 1, 'QN-0103', 'store_out_204', 204, NULL, 0, NULL, -1, 0),
(96, 2, 205, 202, 'PL', '2019-01-23', 1, 'QN-0104', 'store_out_206', 206, NULL, 0, NULL, -1, 0),
(97, 2, 207, 202, 'PL', '2019-01-23', 1, 'QN-0105', 'store_out_208', 208, NULL, 0, NULL, -1, 0),
(98, 2, 209, 202, 'PL', '2019-01-24', 17, 'QN-0106', 'store_out_210', 210, NULL, 0, NULL, -1, 8000),
(99, 9, 209, 202, 'PL', '2019-01-24', 17, 'QN-0106', 'store_out_210', 210, NULL, 0, NULL, -1, 100),
(100, 2, 211, 202, 'PL', '2019-01-27', 1, 'QN-0107', 'store_out_212', 212, NULL, 0, NULL, -1, 0),
(101, 7, 211, 202, 'PL', '2019-01-27', 1, 'QN-0107', 'store_out_212', 212, NULL, 0, NULL, -1, 0),
(102, 2, 213, 202, 'PL', '2019-01-27', 1, 'QN-0108', 'store_out_214', 214, NULL, 0, NULL, -500, 0),
(103, 2, 215, 202, 'PL', '2019-01-27', 1, 'QN-0109', 'store_out_216', 216, NULL, 0, NULL, -1, 8000),
(104, 2, 217, 202, 'PL', '2019-01-29', 1, 'QN-0110', 'store_out_218', 218, NULL, 0, NULL, -1, 0),
(105, 9, 217, 202, 'PL', '2019-01-29', 1, 'QN-0110', 'store_out_218', 218, NULL, 0, NULL, -1, 0),
(106, 9, 219, 202, 'PL', '2019-01-29', 1, 'QN-0111', 'store_out_220', 220, NULL, 0, NULL, -1, 0),
(107, 2, 221, 202, 'PL', '2019-01-29', 1, 'QN-0112', 'store_out_222', 222, NULL, 0, NULL, -1, 0),
(108, 19, 221, 202, 'PL', '2019-01-29', 1, 'QN-0112', 'store_out_222', 222, NULL, 0, NULL, -20, 0),
(109, 2, 223, 202, 'PL', '2019-01-30', 1, 'QN-0113', 'store_out_224', 224, NULL, 0, NULL, -2, 8000),
(110, 9, 223, 202, 'PL', '2019-01-30', 1, 'QN-0113', 'store_out_224', 224, NULL, 0, NULL, -2, 100),
(111, 2, 225, 202, 'PL', '2019-01-30', 1, 'QN-0114', 'store_out_226', 226, NULL, 0, NULL, -1, 0),
(112, 9, 227, 202, 'PL', '2019-01-30', 1, 'QN-0115', 'store_out_228', 228, NULL, 0, NULL, -1, 0),
(113, 2, 229, 202, 'PL', '2019-01-30', 1, 'QN-0116', 'store_out_230', 230, NULL, 0, NULL, -1, 0),
(114, 9, 231, 202, 'PL', '2019-01-30', 1, 'QN-0117', 'store_out_232', 232, NULL, 0, NULL, -1, 0),
(115, 2, 233, 202, 'PL', '2019-01-31', 1, 'QN-0118', 'store_out_234', 234, NULL, 0, NULL, -1, 0),
(116, 9, 235, 202, 'PL', '2019-01-31', 1, 'QN-0119', 'store_out_236', 236, NULL, 0, NULL, -1755, 0),
(118, 20, 239, 202, 'PL', '2019-01-31', 1, 'QN-0121', 'store_out_240', 240, NULL, 0, NULL, -52, 0),
(119, 19, 0, 102, 'PL', '2019-01-31', 1, '', 'store_in_9', 9, NULL, 8, NULL, 50, 10),
(120, 9, 0, 102, 'PL', '2019-01-31', 1, '', 'store_in_10', 10, NULL, 9, NULL, 500, 50),
(121, 9, 0, 102, 'PL', '2019-01-31', 1, '', 'store_in_11', 11, NULL, 10, NULL, 500, 0.25),
(122, 9, 0, 102, 'PL', '2019-01-31', 1, '', 'store_in_11', 11, NULL, 11, NULL, 3000, 0.25),
(123, 10, 0, 401, 'JA', '2019-02-03', 1, '', 'moved_from_PL', 1, 1, 0, NULL, 0, 0),
(124, 10, 0, 402, 'PL', '2019-02-03', 1, '', 'moved_in_JA', 1, NULL, 0, NULL, -0, 0),
(125, 11, 0, 601, 'PL', '2019-02-03', 1, '', 'adjustment_in_1', 1, NULL, 0, 'Test stock adjustment', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer`
--

DROP TABLE IF EXISTS `stock_transfer`;
CREATE TABLE IF NOT EXISTS `stock_transfer` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `source` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `qty` int(11) NOT NULL,
  `transfer_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_transfer_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stock_transfer`
--

INSERT INTO `stock_transfer` (`id`, `user_id`, `source`, `destination`, `note`, `qty`, `transfer_date`) VALUES
(1, 1, 'PL', 'JA', NULL, 0, '2019-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supp_name`, `email`, `address`, `contact`, `tax_id`, `currency_id`, `city`, `state`, `zipcode`, `country`, `inactive`, `created_at`, `updated_at`) VALUES
(1, 'First Supplier', 'supplier@gmail.com', 'City Hall Park Path', NULL, NULL, 2, 'New York', 'New York', '10007', 'US', 0, '2018-12-26 23:15:53', '2019-01-19 23:36:37'),
(2, 'test supplier', 'test@gmail.com', 'Dhaka', NULL, NULL, 1, 'Dhaka', NULL, NULL, 'US', 0, '2019-01-05 23:45:48', NULL),
(3, 'fiona', 'fiona@gmail.com', 'USA', NULL, NULL, 4, 'NEW York', NULL, NULL, 'US', 0, '2019-01-21 00:37:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_transactions`
--

DROP TABLE IF EXISTS `supplier_transactions`;
CREATE TABLE IF NOT EXISTS `supplier_transactions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `purchase_exchange_rate` decimal(10,0) NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(10) UNSIGNED NOT NULL,
  `purch_order_reference` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type_id` int(10) UNSIGNED NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `exchange_rate` double NOT NULL,
  `incoming_amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `supplier_transactions_user_id_foreign` (`user_id`),
  KEY `supplier_transactions_supplier_id_foreign` (`supplier_id`),
  KEY `supplier_transactions_payment_type_id_foreign` (`payment_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `supplier_transactions`
--

INSERT INTO `supplier_transactions` (`id`, `user_id`, `reference_id`, `reference`, `currency_id`, `purchase_exchange_rate`, `supplier_id`, `purchase_id`, `purch_order_reference`, `payment_type_id`, `transaction_date`, `amount`, `exchange_rate`, `incoming_amount`, `created_at`) VALUES
(1, 15, 54, '001/2018', 2, '1', 1, 1, 'PO-0001', 2, '2018-12-27', 500, 80, 0, '2018-12-27 06:02:11'),
(2, 1, 55, '002/2018', 2, '1', 1, 2, 'PO-0002', 1, '2018-12-27', 4000, 80, 0, '2018-12-27 06:02:42'),
(3, 1, 62, '003/2019', 2, '1', 1, 3, 'PO-0003', 2, '2019-01-03', 50, 1, 0, '2019-01-03 12:44:40'),
(4, 1, 63, '004/2019', 2, '1', 1, 3, 'PO-0003', 2, '2019-01-03', 1000, 1, 0, '2019-01-03 12:46:32'),
(5, 1, 78, '005/2019', 1, '0', 2, 6, 'PO-0006', 2, '2019-01-07', 1000, 1, 0, '2019-01-07 10:40:22'),
(6, 1, 82, '006/2019', 4, '0', 3, 8, 'PO-0008', 2, '2019-01-21', 500, 1, 0, '2019-01-21 08:50:01'),
(7, 1, 112, '007/2019', 2, '1', 1, 9, 'PO-0009', 1, '2019-01-31', 25, 1, 0, '2019-01-31 10:20:55'),
(8, 1, 113, '008/2019', 2, '1', 1, 9, 'PO-0009', 1, '2019-01-31', 500, 50, 0, '2019-01-31 10:40:34'),
(10, 1, 116, '010/2019', 1, '70', 2, 10, 'PO-0010', 2, '2019-01-31', 24700, 1, 0, '2019-01-31 11:42:36'),
(11, 1, 117, '011/2019', 4, '50', 3, 11, 'PO-0011', 1, '2019-01-31', 857.5, 1, 0, '2019-01-31 12:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'erp'),
(2, 'garments erp'),
(3, 'secure'),
(5, 'Laravel'),
(14, 'project'),
(15, 'javascript'),
(16, 'another'),
(17, 'task'),
(18, 'check list'),
(21, 'test'),
(22, 'add'),
(23, 'new'),
(24, 'jquey'),
(25, 'html'),
(26, 'css'),
(30, 'cart'),
(31, 'axios'),
(32, 'Vue'),
(33, 'java'),
(34, 'python'),
(36, 'milestone'),
(41, 'Html'),
(42, 'responsivr'),
(43, 'testing'),
(44, 'currency'),
(45, 'ng gbn'),
(46, 'gjgfj'),
(47, 'gfj'),
(49, 'sznn'),
(50, 'ariff'),
(51, 'pubg'),
(52, 'lead13'),
(53, 'lead15'),
(54, 'test2lead'),
(55, 'test2lead2'),
(56, 'abc1'),
(57, 'aabc1'),
(58, 'newtestlead'),
(59, 'fffffffffffffffffffffffffffffffffffffffffffffffffffff'),
(60, 'gdfgdfggggggggggggggggggggggggg'),
(61, 'fsdfsd'),
(62, 'fsdfsdfsd'),
(63, 'fsdf'),
(64, 'sdf'),
(65, 'f'),
(66, 'sdfdsfdsf'),
(67, 'fsdfsdfdsf'),
(68, 'fsdfsdfsdf'),
(69, 'newTagSZzn'),
(73, 'NewTagTodelte'),
(79, 'Fixing bug'),
(80, 'review'),
(81, 'Test'),
(82, 'Follow up'),
(83, 'php'),
(84, 'js');

-- --------------------------------------------------------

--
-- Table structure for table `tags_in`
--

DROP TABLE IF EXISTS `tags_in`;
CREATE TABLE IF NOT EXISTS `tags_in` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `reff_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_in_tag_id_foreign` (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=930 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags_in`
--

INSERT INTO `tags_in` (`id`, `type`, `tag_id`, `reff_id`) VALUES
(1, 'project', 1, 1),
(2, 'project', 2, 1),
(5, 'project', 5, 10),
(408, 'project', 14, 9),
(435, 'project', 15, 11),
(459, 'project', 5, 12),
(460, 'task', 5, 14),
(463, 'task', 5, 14),
(484, 'task', 5, 14),
(551, 'task', 21, 25),
(552, 'task', 22, 25),
(553, 'task', 23, 25),
(555, 'task', 24, 27),
(556, 'task', 5, 14),
(558, 'task', 25, 31),
(559, 'task', 26, 31),
(562, 'task', 25, 32),
(563, 'task', 26, 32),
(574, 'task', 22, 2),
(575, 'task', 21, 2),
(588, 'task', 30, 39),
(589, 'task', 31, 39),
(590, 'task', 5, 40),
(592, 'task', 32, 40),
(593, 'task', 5, 41),
(595, 'task', 32, 41),
(596, 'task', 5, 42),
(599, 'task', 32, 43),
(600, 'task', 33, 43),
(603, 'task', 33, 44),
(604, 'task', 34, 44),
(607, 'task', 36, 45),
(619, 'task', 36, 46),
(623, 'task', 36, 45),
(626, 'task', 36, 46),
(634, 'task', 36, 45),
(643, 'task', 36, 47),
(645, 'task', 36, 46),
(653, 'task', 36, 45),
(655, 'task', 36, 45),
(662, 'task', 36, 45),
(667, 'task', 36, 46),
(668, 'task', 41, 51),
(669, 'task', 42, 51),
(670, 'task', 43, 51),
(671, 'task', 41, 53),
(672, 'task', 42, 53),
(673, 'task', 43, 53),
(674, 'task', 41, 53),
(675, 'task', 43, 53),
(676, 'task', 42, 53),
(677, 'task', 41, 51),
(678, 'task', 42, 51),
(679, 'task', 43, 51),
(680, 'task', 42, 51),
(681, 'task', 41, 51),
(682, 'task', 43, 51),
(685, 'task', 36, 45),
(692, 'task', 36, 46),
(693, 'task', 41, 54),
(694, 'task', 42, 54),
(695, 'task', 43, 54),
(696, 'task', 41, 47),
(697, 'task', 42, 47),
(698, 'task', 43, 47),
(700, 'project', 33, 27),
(701, 'task', 36, 45),
(707, 'task', 36, 45),
(711, 'task', 36, 45),
(716, 'task', 36, 45),
(721, 'task', 36, 45),
(730, 'task', 36, 49),
(736, 'task', 36, 45),
(741, 'task', 36, 45),
(748, 'task', 36, 49),
(749, 'task', 36, 49),
(757, 'task', 36, 45),
(762, 'task', 36, 49),
(767, 'task', 36, 49),
(772, 'task', 36, 49),
(776, 'task', 36, 49),
(782, 'task', 36, 49),
(787, 'task', 36, 49),
(792, 'task', 36, 49),
(793, 'task', 44, 59),
(796, 'task', 36, 45),
(800, 'task', 36, 49),
(805, 'task', 36, 52),
(807, 'project', 45, 28),
(808, 'project', 46, 28),
(809, 'project', 47, 28),
(810, 'task', 36, 45),
(815, 'task', 41, 45),
(816, 'task', 42, 45),
(817, 'task', 43, 45),
(818, 'task', 41, 58),
(819, 'task', 42, 58),
(820, 'task', 43, 58),
(823, 'task', 36, 57),
(826, 'task', 41, 57),
(827, 'task', 42, 57),
(828, 'task', 43, 57),
(833, 'task', 36, 45),
(838, 'task', 36, 46),
(843, 'task', 36, 45),
(844, 'lead', 49, 15),
(848, 'task', 36, 65),
(850, 'task', 42, 65),
(851, 'task', 41, 65),
(852, 'task', 43, 65),
(857, 'lead', 54, 16),
(858, 'lead', 55, 16),
(859, 'task', 44, 47),
(861, 'task', 36, 59),
(865, 'task', 41, 59),
(866, 'task', 42, 59),
(867, 'task', 43, 59),
(872, 'task', 36, 74),
(877, 'task', 41, 49),
(878, 'task', 43, 49),
(879, 'task', 42, 49),
(880, 'task', 36, 49),
(881, 'task', 44, 49),
(886, 'task', 36, 47),
(887, 'task', 43, 47),
(888, 'task', 41, 47),
(889, 'task', 44, 47),
(890, 'task', 42, 47),
(892, 'task', 23, 45),
(893, 'task', 36, 45),
(894, 'task', 43, 45),
(895, 'task', 41, 45),
(896, 'task', 42, 45),
(897, 'task', 44, 45),
(898, 'task', 36, 47),
(913, 'lead', 73, 19),
(916, 'task', 36, 45),
(922, 'lead', 79, 25),
(923, 'lead', 80, 25),
(924, 'lead', 81, 25),
(925, 'lead', 82, 25),
(926, 'task', 36, 45),
(927, 'lead', 25, 26),
(928, 'lead', 83, 26),
(929, 'lead', 84, 26);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `description` longtext CHARACTER SET utf8,
  `priority` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `finished_date` date DEFAULT NULL,
  `added_from` int(10) UNSIGNED NOT NULL,
  `status` int(10) UNSIGNED NOT NULL,
  `recurring_type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `repeat_every` int(10) UNSIGNED DEFAULT NULL,
  `recurring` int(10) UNSIGNED NOT NULL,
  `recurring_ends_on` date DEFAULT NULL,
  `retated_to_id` int(10) UNSIGNED DEFAULT NULL,
  `related_to_type` int(10) DEFAULT NULL COMMENT '1=Project, 2= Customer, 3=Ticket',
  `custom_recurring` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_recurring_date` date DEFAULT NULL,
  `is_public` tinyint(4) UNSIGNED DEFAULT '0',
  `billable` tinyint(4) UNSIGNED DEFAULT '0',
  `billed` tinyint(4) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `hourly_rate` decimal(11,2) DEFAULT NULL,
  `milestone_id` int(10) UNSIGNED DEFAULT NULL,
  `visible_to_customer` tinyint(4) UNSIGNED DEFAULT '0',
  `deadline_notified` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `priority`, `created_at`, `start_date`, `due_date`, `finished_date`, `added_from`, `status`, `recurring_type`, `repeat_every`, `recurring`, `recurring_ends_on`, `retated_to_id`, `related_to_type`, `custom_recurring`, `last_recurring_date`, `is_public`, `billable`, `billed`, `invoice_id`, `hourly_rate`, `milestone_id`, `visible_to_customer`, `deadline_notified`) VALUES
(45, 'Milestone check again', '<p>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s,\r\n\r\n\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s,\r\n\r\n\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s,\r\n\r\n<br></p>', 2, '2019-01-10 00:48:30', '2019-01-10', NULL, NULL, 1, 4, NULL, NULL, 0, NULL, 19, 1, 0, NULL, 1, 1, 0, 0, NULL, 5, 1, 0),
(46, 'Milestone check', '<p>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s,\r\n\r\n\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s,\r\n\r\n<br></p>', 1, '2019-01-10 00:57:19', '2019-01-10', NULL, NULL, 18, 5, NULL, NULL, 0, '1970-01-01', 19, 1, 0, NULL, 0, 0, 0, 0, NULL, 5, 0, 0),
(47, 'when changing status for payout as success this error occur, but if reload page status changing as s', '<p>when changing status for payout as success this error occur, but if reload page status.&nbsp;\r\n\r\nwhen changing status for payout as success this error occur, but if reload page status. \r\n\r\n\r\n\r\nwhen changing status for payout as success this error occur, but if reload page status. \r\n\r\n\r\n\r\nwhen changing status for payout as success this error occur, but if reload page status. \r\n\r\n<br></p>', 1, '2019-01-10 01:00:18', '2019-01-10', NULL, NULL, 1, 5, 'week', 1, 1, '2019-01-15', 20, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(48, 'API inetegration', '<p>\r\n\r\n<strong></strong>Lorem Ipsum&nbsp;<strong></strong>is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\n<br></p>', 1, '2019-01-10 01:16:40', '2019-01-10', NULL, NULL, 1, 4, NULL, NULL, 0, NULL, 8, 2, 0, NULL, 0, 0, 0, 0, '50.00', NULL, 0, 0),
(49, 'Invoice module', '<p>Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module Invoice module <br></p>', 1, '2019-01-10 01:56:59', '2019-01-10', NULL, NULL, 1, 5, 'week', 1, 1, '2019-01-18', 21, 1, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0),
(51, 'Design chnage of front end', '<p>Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task. Testing related task.&nbsp;</p><p><br></p>', 3, '2019-01-14 01:05:12', '2019-01-14', NULL, NULL, 1, 2, NULL, NULL, 0, NULL, 20, 1, 0, NULL, 0, 0, 0, 0, NULL, 7, 0, 0),
(52, 'tesing is started', NULL, 1, '2019-01-14 01:11:14', '2019-01-14', NULL, NULL, 1, 1, NULL, NULL, 0, NULL, 20, 1, 0, NULL, 0, 0, 0, 0, NULL, 8, 0, 0),
(53, 'APi', NULL, 1, '2019-01-14 01:14:35', '2019-01-14', NULL, NULL, 1, 1, NULL, NULL, 0, NULL, 20, 1, 0, NULL, 0, 0, 0, 0, NULL, 7, 0, 0),
(54, 'API 2', NULL, 1, '2019-01-14 01:59:13', '2019-01-14', NULL, NULL, 1, 1, NULL, NULL, 0, '1970-01-01', 20, 1, 0, NULL, 0, 0, 0, 0, '100.00', NULL, 0, 0),
(55, 'Test task for hourly rate', NULL, 1, '2019-01-14 02:22:44', '2019-01-14', NULL, NULL, 1, 1, NULL, NULL, 0, NULL, 2, 2, 0, NULL, 0, 0, 0, 0, '150.00', NULL, 0, 0),
(56, 'Report', NULL, 1, '2019-01-19 03:42:02', '2019-01-19', '2019-01-19', NULL, 1, 5, NULL, NULL, 0, NULL, 21, 1, 0, NULL, 0, 0, 0, 0, NULL, 11, 0, 0),
(57, 'Task from customer panel', '<p>Description<br></p>', 1, '2019-01-20 07:43:57', '2019-01-20', NULL, NULL, 5, 5, NULL, NULL, 0, NULL, 21, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(58, 'Task With Milestone', '<p>sad<br></p>', 1, '2019-01-20 07:51:22', '2019-01-20', NULL, NULL, 1, 5, NULL, NULL, 0, NULL, 21, 1, 0, NULL, NULL, 1, 0, 0, NULL, 11, 1, 0),
(59, 'Multi Currency', '<p>Details<br></p>', 1, '2019-01-20 04:30:13', '2019-01-20', NULL, NULL, 1, 1, NULL, NULL, 0, NULL, 21, 1, 0, NULL, 1, 0, 0, 0, NULL, 11, 1, 0),
(60, 'Checklist module', '<p>Details <br></p>', 1, '2019-01-20 06:57:42', '2019-01-20', NULL, NULL, 1, 1, NULL, NULL, 0, '1970-01-01', 21, 1, 0, NULL, NULL, NULL, 0, 0, NULL, 11, 1, 0),
(61, 'Customer Details', '<p>sa<br></p>', 1, '2019-01-20 06:58:46', '2019-01-20', NULL, NULL, 1, 1, NULL, NULL, 0, '1970-01-01', 21, 1, 0, NULL, NULL, 1, 0, 0, NULL, NULL, 1, 0),
(62, 'In front end past property booking don\'t need any cancel option.(Improvement)', '<p>Details of task.</p>', 2, '2019-01-20 23:23:13', '2019-01-21', NULL, NULL, 1, 1, 'week', 1, 1, '2019-01-31', 5, 2, 0, NULL, 0, 0, 0, 0, '10.00', NULL, 0, 0),
(63, 'Test task for customer-1', '<p>Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1.<br></p><p>Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1.<br></p><p>Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1.<br></p>', 1, '2019-01-21 03:20:25', '2019-01-21', NULL, NULL, 18, 5, NULL, NULL, 0, NULL, 27, 1, 0, NULL, NULL, NULL, 0, 0, NULL, 12, 1, 0),
(64, 'test-2', '<p>test</p>', 1, '2019-01-21 03:58:30', '2019-01-21', NULL, NULL, 1, 5, NULL, NULL, 0, NULL, 27, 1, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 1, 0),
(65, 'IS it visible to customer?', '<p>IS it visible to customer?IS it visible to customer?IS it visible to customer?IS it visible to customer?IS it visible to customer?IS it visible to customer?IS it visible to customer?IS it visible to customer?IS it visible to customer?<br></p>', 1, '2019-01-22 05:52:16', '2019-01-22', NULL, NULL, 18, 1, NULL, NULL, 0, NULL, 21, 1, 0, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, 0),
(66, 'visible to customer button is unchecked.', NULL, 1, '2019-01-22 05:58:08', '2019-01-22', NULL, NULL, 18, 1, NULL, NULL, 0, NULL, 21, 1, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 0),
(67, 'Task from customer', NULL, 1, '2019-01-23 06:35:44', '2019-01-23', NULL, NULL, 5, 1, NULL, NULL, 0, NULL, 27, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(68, 'Task from customer', NULL, 1, '2019-01-23 06:38:32', '2019-01-23', NULL, NULL, 5, 1, NULL, NULL, 0, NULL, 27, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(69, 'Task from customer', NULL, 1, '2019-01-23 06:38:46', '2019-01-23', NULL, NULL, 5, 1, NULL, NULL, 0, NULL, 27, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(70, 'New task from customer', '<p>New task from customer.&nbsp;\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n<br></p><p>\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n\r\n\r\nNew task from customer. \r\n\r\n<br></p>', 1, '2019-01-23 06:52:17', '2019-01-23', NULL, NULL, 5, 1, NULL, NULL, 0, NULL, 27, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(71, 'test task for', '<p>\r\n\r\nNew task from customer. \r\n\r\n<br></p>', 1, '2019-01-23 07:01:59', '2019-01-23', '2019-01-25', NULL, 5, 1, NULL, NULL, 0, NULL, 27, 1, 0, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0),
(72, 'Hello', '<p>HelloHelloHelloHelloHelloHelloHello<br></p>', 1, '2019-01-23 02:24:40', '2019-01-23', NULL, NULL, 1, 1, NULL, NULL, 0, '1970-01-01', 27, 1, 0, NULL, NULL, NULL, 0, 0, NULL, 12, 1, 0),
(74, 'what should be the assignee name?', NULL, 1, '2019-01-24 04:40:55', '2019-01-24', NULL, NULL, 17, 1, NULL, NULL, 0, '1970-01-01', 21, 1, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_assigns`
--

DROP TABLE IF EXISTS `task_assigns`;
CREATE TABLE IF NOT EXISTS `task_assigns` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `assigned_from` int(10) UNSIGNED NOT NULL,
  `is_assigned_from_customer` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_assigns_user_id_foreign` (`user_id`),
  KEY `task_assigns_task_id_foreign` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_assigns`
--

INSERT INTO `task_assigns` (`id`, `user_id`, `task_id`, `assigned_from`, `is_assigned_from_customer`) VALUES
(10, 18, 47, 1, 0),
(13, 18, 49, 1, 0),
(18, 18, 46, 15, 0),
(19, 18, 45, 1, 0),
(21, 18, 51, 1, 0),
(22, 17, 51, 1, 0),
(23, 1, 55, 1, 0),
(24, 1, 49, 1, 0),
(26, 17, 49, 1, 0),
(27, 1, 56, 1, 0),
(28, 1, 59, 1, 0),
(29, 1, 60, 1, 0),
(30, 1, 61, 1, 0),
(31, 1, 62, 1, 0),
(32, 1, 63, 1, 0),
(33, 18, 63, 1, 0),
(34, 15, 63, 1, 0),
(35, 1, 64, 1, 0),
(36, 18, 64, 1, 0),
(37, 18, 65, 18, 0),
(38, 18, 66, 18, 0),
(39, 1, 72, 1, 0),
(42, 17, 74, 17, 0),
(44, 15, 45, 1, 0),
(45, 15, 49, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

DROP TABLE IF EXISTS `task_comments`;
CREATE TABLE IF NOT EXISTS `task_comments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` mediumtext COLLATE utf8_unicode_ci,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `file_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_comments_task_id_foreign` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_comments`
--

INSERT INTO `task_comments` (`id`, `content`, `task_id`, `user_id`, `customer_id`, `file_id`, `created_at`) VALUES
(30, 'task', 45, 18, 0, 0, '2019-01-10 00:49:30'),
(31, 'test', 49, 0, 8, 0, '2019-01-15 21:13:18'),
(32, '<p>h<br></p>', 49, 1, 0, 0, '2019-01-10 06:38:03'),
(33, '<p>Hello,</p><p>Please do this work as urgent.</p><p>Thank you.<br></p>', 51, 1, 0, 0, '2019-01-14 01:31:50'),
(34, '<p>ok</p>', 51, 18, 0, 0, '2019-01-14 01:32:10'),
(35, '<p>do it againg<br></p>', 51, 1, 0, 0, '2019-01-14 01:33:16'),
(37, '<p>test</p>', 51, 18, 0, 0, '2019-01-14 01:34:46'),
(38, '<p>admin comment<br></p>', 49, 1, 0, 0, '2019-01-15 01:46:18'),
(39, '<p>\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n<br></p>', 49, 1, 0, 0, '2019-01-15 05:22:45'),
(40, '<p>\n<blockquote><b>The CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n</b></blockquote></p><br>', 49, 1, 0, 0, '2019-01-15 05:23:33'),
(43, 'In the example above, we are retrieving the model from the database before calling the delete method. However, if you know the primary key of the model, you may delete the model without retrieving it by calling the destroy method. In addition to a single primary key as its argument, the destroy method will accept multiple primary keys, an array of primary keys, or a collection of primary keysIn the example above, we are retrieving the model from the database before calling the delete method. However, if you know the primary key of the model, you may delete the model without retrieving it by calling the destroy method. In addition to a single primary key as its argument, the destroy method will accept multiple primary keys, an array of primary keys, or a collection of primary keysIn the example above, we are retrieving the model from the database before calling the delete method. However, if you know the primary key of the model, you may delete the model without retrieving it by calling the destroy method. In addition to a single primary key as its argument, the destroy method will accept multiple primary keys, an array of primary keys, or a collection of primary keysIn the example above, we are retrieving the model from the database before calling the delete method. However, if you know the primary key of the model, you may delete the model without retrieving it by calling the destroy method. In addition to a single primary key as its argument, the destroy method will accept multiple primary keys, an array of primary keys, or a collection of primary keys', 49, 0, 5, 0, NULL),
(44, 'Example: Use submitHandler to process something and then using the default submit. Note that \"form\" refers to a DOM element, this way the validation isn\'t triggered again.Example: Use submitHandler to process something and then using the default submit. Note that \"form\" refers to a DOM element, this way the validation isn\'t triggered again.Example: Use submitHandler to process something and then using the default submit. Note that \"form\" refers to a DOM element, this way the validation isn\'t triggered again.', 49, 0, 5, 0, NULL),
(45, '<h1>\nThe CONCAT() function adds two or more strings together. <br></h1><p><br></p><p>\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n\nThe CONCAT() function adds two or more strings together.\n\n<br></p>', 49, 18, 0, 0, '2019-01-15 06:44:03'),
(46, 'The CONCAT() function adds two or more strings together. The CONCAT() function adds two or more strings together. The CONCAT() function adds two or more strings together. The CONCAT() function adds two or more strings together. The CONCAT() function adds two or more strings together.', 49, 0, 5, 0, NULL),
(50, 'The CONCAT() function adds two or more strings together. The CONCAT() function adds two or more strings together. The CONCAT() function adds two or more strings.', 49, 0, 5, 0, NULL),
(52, 'frds', 49, 0, 5, 0, NULL),
(53, '<p>Admin Comment<br></p>', 49, 1, 0, 0, '2019-01-16 23:55:19'),
(54, '<p>fsdffd<br></p>', 49, 1, 0, 0, '2019-01-16 23:55:42'),
(55, 'customer comment', 49, 0, 5, 0, NULL),
(56, 'another customer comment, another customer comment, another customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer commentanother customer comment', 49, 0, 5, 0, NULL),
(57, 'customer comment', 49, 0, 5, 0, NULL),
(58, 'sdad', 49, 0, 5, 0, NULL),
(59, 'Task comment', 56, 0, 5, 0, NULL),
(60, '<p>Hello,</p><p>Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1Test task for customer-1<br></p><p>Test task for customer-1Test task for customer-1Test task for customer-1<br></p><p>Thank you.</p>', 63, 1, 0, 0, '2019-01-21 03:27:19'),
(61, 'Hi,\r\nTest task for customer-1Test task for customer-1Test task for customer-1\r\nThank youuuuuuuuuu.', 63, 0, 5, 0, NULL),
(62, '<p>hey,</p><p>this is test comment.</p><p>Thanks</p>', 63, 1, 0, 0, '2019-01-21 03:31:54'),
(63, 'test  test test', 63, 0, 5, 0, NULL),
(64, '<p>Task comment<br></p>', 46, 1, 0, 0, '2019-01-31 01:05:11'),
(65, '<p>Task comment<br></p>', 46, 1, 0, 0, '2019-01-31 01:05:18');

-- --------------------------------------------------------

--
-- Table structure for table `task_status`
--

DROP TABLE IF EXISTS `task_status`;
CREATE TABLE IF NOT EXISTS `task_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task_status`
--

INSERT INTO `task_status` (`id`, `name`, `color`) VALUES
(1, ' Not Started', '#F56954'),
(2, 'In Progress', '#1dd48d'),
(3, 'Testing', '#5a4d4d'),
(4, 'Awaiting Feedback', '#489c61'),
(5, 'Complete', '');

-- --------------------------------------------------------

--
-- Table structure for table `task_timer`
--

DROP TABLE IF EXISTS `task_timer`;
CREATE TABLE IF NOT EXISTS `task_timer` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `start_time` varchar(64) NOT NULL,
  `end_time` varchar(64) DEFAULT NULL,
  `hourly_rate` double NOT NULL DEFAULT '0',
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task_timer`
--

INSERT INTO `task_timer` (`id`, `user_id`, `task_id`, `start_time`, `end_time`, `hourly_rate`, `note`) VALUES
(2, 18, 45, '1547056805', '1547056816', 0, NULL),
(3, 18, 45, '1547104561', '1547104566', 0, 'ds'),
(4, 18, 45, '1547105576', '1547105578', 0, 'ds'),
(5, 18, 46, '1547105605', '1547105612', 0, 'fd'),
(6, 18, 46, '1547105613', '1547106050', 0, 'ds'),
(7, 18, 45, '1547106772', '1547107171', 0, 'xs'),
(8, 18, 46, '1547107511', '1547107670', 0, 'ds'),
(9, 18, 46, '1547107940', '1547108064', 0, 'ds'),
(10, 18, 45, '1547448307', '1547448312', 0, NULL),
(11, 18, 51, '1547451673', '1547451677', 0, NULL),
(15, 17, 51, '1547453946', '1547453987', 0, NULL),
(16, 17, 51, '1547453953', '1547540356', 0, NULL),
(17, 1, 49, '1547546367', '1547546381', 0, 'Logged time'),
(18, 1, 49, '1547625814', '1547625821', 0, NULL),
(19, 1, 49, '1547710058', '1547710091', 0, 'gfd'),
(20, 18, 49, '1546349856', '1547732258', 0, 's'),
(21, 18, 63, '1547569068', '1548090053', 0, 'This is a test note.'),
(22, 1, 63, '1548065937', '1548065952', 0, 'hello'),
(23, 1, 63, '1548156600', '1548156608', 0, 'notes'),
(24, 18, 63, '1548156936', '1548157069', 0, 'fhd'),
(25, 17, 74, '1548326497', '1548326503', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_replying` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'not used',
  `customer_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `priority_id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED DEFAULT NULL,
  `ticket_key` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `admin` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `last_reply` timestamp NULL DEFAULT NULL,
  `customer_read` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_read` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `assigned_member_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_cutomer_id_foreign` (`customer_id`),
  KEY `tickets_department_id_foreign` (`department_id`),
  KEY `tickets_priority_id_foreign` (`priority_id`),
  KEY `tickets_status_id_foreign` (`status_id`),
  KEY `tickets_project_id_foreign` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `admin_replying`, `customer_id`, `email`, `name`, `department_id`, `priority_id`, `status_id`, `ticket_key`, `subject`, `message`, `admin`, `date`, `project_id`, `last_reply`, `customer_read`, `user_read`, `assigned_member_id`) VALUES
(1, 0, 2, 'job@gmail.com', 'Steve Jobs', 1, 1, 5, 'TIC-5c120c97d1eab', 'If balance is not available with fees not showing error msg but if its just exact amount then error ', '<p>If balance is not available with fees not showing error msg but if its just exact amount then error msg comes.If balance is not available with fees not showing error msg but if its just exact amount then error msg comes.<br></p>', 1, '2018-12-13 01:39:03', 1, '2018-12-17 06:57:07', 0, 1, 1),
(2, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 1, 1, 1, 'TIC-5c1f6d10acd70', 'new Ticket', '<p>There is some new issue</p><p>gfjgfj</p>', 1, '2018-12-23 05:10:08', 3, '2019-01-23 01:54:37', 1, 1, 18),
(3, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 2, 1, 1, 'TIC-12e6b8054f1188150a04bdabd4db99bd', 'Test email two times', '<p>issue for two times email</p>', 0, '2018-12-24 06:22:54', 3, '2018-12-24 23:17:16', 1, 1, 15),
(4, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 2, 1, 1, 'TIC-cb420c920ef7bcf8811bb43e5714ed03', 'test mail', '<p>new issue</p>', 0, '2018-12-24 06:38:06', 5, '2018-12-24 06:38:06', 1, 0, NULL),
(5, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 1, 1, 1, 'TIC-5c246abfcae9f', 'test11', '<p>test</p>', 1, '2018-12-27 00:01:35', 1, '2018-12-27 00:02:52', 1, 0, 1),
(6, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 1, 1, 5, 'TIC-dab5b8612de604413d590d3f73f6389f', 'support ticket for invoice', '<p>support ticket for invoicesupport ticket for invoicesupport ticket for invoice<br></p>', 0, '2018-12-27 00:04:07', 3, '2018-12-27 00:04:47', 1, 1, 18),
(7, 0, 7, 'customer.321@gmail.com', 'customer 123', 1, 1, 1, 'TIC-143ce4e62e235ab354010261ed3528b9', 'Unable to install gobilling', '<p>test<br></p>', 0, '2018-12-27 01:10:44', 3, '2018-12-27 01:10:44', 1, 1, 1),
(8, 0, 1, 'ratoolapparals@gmail.com', 'Ratool Apparals', 1, 1, 1, 'TIC-5c25ca6da31a1', 'Help post', '<p>Help postHelp postHelp postHelp post<br></p>', 1, '2018-12-28 01:02:05', 1, '2018-12-28 01:02:05', 0, 1, 1),
(9, 0, 7, 'customer.321@gmail.com', 'customer 123', 1, 1, 1, 'TIC-5c3439fb4118e', 'Payments with coin payments', '<p>Payments with coin payments.\r\n\r\nPayments with coin payments.\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments.</p><p>\r\n\r\nPayments with coin payments.\r\n\r\nPayments with coin payments.\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments.&nbsp;</p><p>\r\n\r\nPayments with coin payments.\r\n\r\nPayments with coin payments.\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n\r\n\r\nPayments with coin payments. \r\n\r\n&nbsp;\r\n\r\n&nbsp; \r\n\r\n<br></p>', 1, '2019-01-07 23:49:47', 11, '2019-01-07 23:49:47', 0, 1, 1),
(10, 0, 2, 'job@gmail.com', 'Steve Jobs', 1, 1, 1, 'TIC-5c343dc04fe93', 'Test', '<p>test</p>', 1, '2019-01-08 00:05:52', 12, '2019-01-08 00:05:52', 0, 1, 1),
(11, 0, 2, 'job@gmail.com', 'Steve Jobs', 1, 1, 1, 'TIC-5c343e0fec414', 'Test1', '<p>test1</p>', 1, '2019-01-08 00:07:11', 1, '2019-01-08 00:07:11', 0, 1, 1),
(12, 0, 7, 'customer.321@gmail.com', 'customer 123', 1, 1, 1, 'TIC-5c3444dc51c3e', 'Test2', '<p>test2</p>', 1, '2019-01-08 00:36:12', 1, '2019-01-08 00:36:12', 1, 0, 1),
(13, 0, 3, 'kazi@gmail.com', 'Mike Kazi', 1, 1, 1, 'TIC-5c34478778136', 'Ticket Check', '<p>Ticket body<br></p>', 1, '2019-01-08 00:47:35', 1, '2019-01-08 00:47:35', 1, 1, 18),
(14, 0, 2, 'job@gmail.com', 'Steve Jobs', 1, 1, 5, 'TIC-5c3460806c53e', 'Support ticket tset', '<p>Support ticket tsetSupport ticket tsetSupport ticket tsetSupport ticket tsetSupport ticket tsetSupport ticket tset<br></p>', 1, '2019-01-08 02:34:08', 1, '2019-01-08 02:41:40', 0, 1, 17),
(15, 0, 1, 'ratoolapparals@gmail.com', 'Ratool Apparals', 1, 1, 5, 'TIC-5c34626d8a992', 'Hello3214566', '<p>test</p>', 1, '2019-01-08 02:42:21', 1, '2019-01-08 02:46:19', 0, 1, 1),
(16, 0, 7, 'customer.321@gmail.com', 'customer 123', 1, 1, 1, 'TIC-5c35c1c4d330b', 'Ticket for paymoney app Api', '<p>\r\n<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book.\r\n\r\n\r\n\r\n\r\n<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book.\r\n\r\n\r\n\r\n<br></p><p>\r\n<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book.\r\n\r\n\r\n\r\n\r\n<strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book.\r\n\r\n\r\n\r\n<br></p><p>Thank you</p>', 1, '2019-01-09 03:41:24', 17, '2019-01-09 03:41:24', 0, 1, 17),
(17, 0, 8, 'borna.techvill@gmail.com', 'Farzana borna', 1, 2, 5, 'TIC-07c4d481728fa37da787d336a701cd9f', 'Installation problem with paymoney Mobile app', '<p>Hello,</p><p>Installation problem with paymoney Mobile appInstallation problem with paymoney Mobile appInstallation problem with paymoney Mobile appInstallation problem with paymoney Mobile appInstallation problem with paymoney Mobile appInstallation problem with paymoney Mobile appInstallation problem with paymoney Mobile appInstallation problem with paymoney Mobile app</p><p>Thank you.<br></p>', 0, '2019-01-10 01:19:04', 20, '2019-01-10 01:22:06', 1, 1, 18),
(18, 0, 8, 'borna.techvill@gmail.com', 'Farzana borna', 1, 1, 1, 'TIC-5c36f324cf4c4', 'This is a test ticket for customer', '<p>Installation problem with paymoney Mobile app.&nbsp;\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n<br></p><p>\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n<br></p><p>\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n<br></p><p>\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n\r\n\r\nInstallation problem with paymoney Mobile app. \r\n\r\n<br></p><p><br></p><p>Thank you.</p>', 1, '2019-01-10 01:24:20', 19, '2019-01-10 01:25:03', 1, 1, 17),
(19, 0, 1, 'ratoolapparals@gmail.com', 'Ratool Apparals', 1, 1, 1, 'TIC-5c371e984d8ed', 'test', '<p>test</p>', 1, '2019-01-10 04:29:44', 20, '2019-01-10 04:29:44', 0, 1, 17),
(20, 0, 1, 'ratoolapparals@gmail.com', 'Ratool Apparals', 1, 1, 5, 'TIC-5c371ffcf13ee', 'Ticket', '<p>\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book. It has survived not \r\nonly five centuries, but also the leap into electronic typesetting, \r\nremaining essentially unchanged. It was popularised in the 1960s with \r\nthe release of Letraset sheets containing Lorem Ipsum passages, and more\r\n recently with desktop publishing software like Aldus PageMaker \r\nincluding versions of Lorem Ipsum.\r\n\r\n\r\n<strong>Lorem Ipsum</strong> is simply dummy text of the printing and \r\ntypesetting industry. Lorem Ipsum has been the industry\'s standard dummy\r\n text ever since the 1500s, when an unknown printer took a galley of \r\ntype and scrambled it to make a type specimen book. It has survived not \r\nonly five centuries, but also the leap into electronic typesetting, \r\nremaining essentially unchanged. It was popularised in the 1960s with \r\nthe release of Letraset sheets containing Lorem Ipsum passages, and more\r\n recently with desktop publishing software like Aldus PageMaker \r\nincluding versions of Lorem Ipsum.\r\n\r\n<br></p>', 18, '2019-01-10 04:35:40', 19, '2019-01-10 04:39:27', 0, 1, 17),
(21, 0, 1, 'ratoolapparals@gmail.com', 'Ratool Apparals', 1, 1, 5, 'TIC-5c37276d7f216', 'Ticket from project', '<p>Ticket Body<br></p>', 1, '2019-01-10 05:07:25', 21, '2019-01-10 05:07:53', 0, 1, 18),
(22, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 1, 3, 1, 'TIC-cb7079eccb1a0cd0a955391d5c83c410', 'Ticket from customer', '<p>Ticket body<br></p>', 0, '2019-01-10 05:38:21', 20, '2019-01-10 05:38:21', 1, 1, 18),
(23, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 1, 1, 1, 'TIC-5c3730d366896', 'Your Quotation # QO-0002 from RATOOL APPARELS LTD has been created.', '<p>Details<br></p>', 18, '2019-01-10 05:47:31', 19, '2019-01-10 05:47:31', 1, 1, 1),
(24, 0, 1, 'ratoolapparals@gmail.com', 'Ratool Apparals', 1, 1, 5, 'TIC-5c3c3838d3382', 'Tetsing ticket to chaeck assignee name', '<p>test<br></p>', 1, '2019-01-14 01:20:24', 20, '2019-01-14 01:22:20', 0, 1, 17),
(25, 0, 8, 'borna.techvill@gmail.com', 'Farzana borna', 1, 2, 5, 'TIC-eba92e12f3e6d9cde9753981804df392', 'Testing ticket for assignee2', '<p>test<br></p>', 0, '2019-01-14 01:23:28', 20, '2019-01-14 01:25:18', 0, 1, 18),
(26, 0, 5, 'customer@techvill.net', 'Customer Techvillage', 1, 2, 1, 'TIC-9f486e5a29ceb98f591c3fc576d1ab19', 'New ticket', '<p>test</p>', 0, '2019-01-23 01:28:16', 27, '2019-01-23 01:30:52', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

DROP TABLE IF EXISTS `ticket_replies`;
CREATE TABLE IF NOT EXISTS `ticket_replies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'not used',
  `customer_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `message` mediumtext COLLATE utf8_unicode_ci,
  `attachment` int(10) UNSIGNED NOT NULL,
  `admin` int(11) NOT NULL COMMENT 'login User',
  PRIMARY KEY (`id`),
  KEY `ticket_replies_ticket_id_foreign` (`ticket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `user_id`, `customer_id`, `name`, `email`, `date`, `message`, `attachment`, `admin`) VALUES
(1, 1, 0, 0, 'Steve Jobs', 'job@gmail.com', '2018-12-13 03:25:25', '<p>ttt<br></p>', 0, 1),
(2, 1, 0, 0, 'Steve Jobs', 'job@gmail.com', '2018-12-17 06:36:34', '<p>reply<br></p>', 0, 1),
(3, 1, 0, 0, 'Steve Jobs', 'job@gmail.com', '2018-12-17 06:56:53', '<p>e</p>', 0, 1),
(4, 1, 0, 0, 'Steve Jobs', 'job@gmail.com', '2018-12-17 06:57:07', '<p>s</p>', 0, 1),
(5, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:00:04', '<p>What about my email..</p>', 0, 0),
(6, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:01:04', '<p>What about my email..</p>', 0, 0),
(7, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:06:24', '<p>hi</p>', 0, 0),
(8, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:08:18', '<p>hi</p>', 0, 0),
(9, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:09:52', '<p>hi</p>', 0, 0),
(10, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:10:27', '<p>this is for assignee</p>', 0, 0),
(11, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:11:56', '<p>this is for both</p>', 0, 0),
(12, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 07:15:01', '<p>for test</p>', 0, 0),
(13, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:53:58', '<p>no</p>', 0, 0),
(14, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:54:10', '<p>no</p>', 0, 0),
(15, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:57:20', '<p>no</p>', 0, 0),
(16, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:57:23', '<p>no</p>', 0, 0),
(17, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:58:00', '<p>no</p>', 0, 0),
(18, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:58:26', '<p>no</p>', 0, 0),
(19, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 22:59:41', '<p>no</p>', 0, 0),
(20, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:00:18', '<p>no</p>', 0, 0),
(21, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:03:08', '<p>no</p>', 0, 0),
(22, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:03:43', '<p>no</p>', 0, 0),
(23, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:04:07', '<p>no</p>', 0, 0),
(24, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:08:18', '<p>no</p>', 0, 0),
(25, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:15:13', '<p>where</p>', 0, 0),
(26, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:15:48', '<p>there</p>', 0, 0),
(27, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:16:12', '<p>come</p>', 0, 0),
(28, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:16:45', '<p>again</p>', 0, 0),
(29, 3, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-24 23:17:16', '<p>now</p>', 0, 0),
(30, 5, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2018-12-27 00:02:52', '<p>reply<br></p>', 0, 0),
(31, 6, 0, 0, 'Customer Techvillage', 'customer@techvill.net', '2018-12-27 00:04:47', '<p>hello</p>', 0, 1),
(32, 14, 0, 2, 'Steve Jobs', 'job@gmail.com', '2019-01-08 02:36:29', '<p>Test for this ticket<br></p>', 0, 0),
(33, 14, 0, 0, 'Steve Jobs', 'job@gmail.com', '2019-01-08 02:40:48', '<p>Hello, <br></p><p>This reply is from as a team member.</p><p>thank you.<br></p>', 0, 18),
(34, 14, 0, 0, 'Steve Jobs', 'job@gmail.com', '2019-01-08 02:41:40', '<p>This is from admin</p>', 0, 1),
(35, 15, 0, 0, 'Ratool Apparals', 'ratoolapparals@gmail.com', '2019-01-08 02:46:19', '<p>test</p>', 0, 1),
(36, 17, 0, 0, 'Farzana borna', 'borna.techvill@gmail.com', '2019-01-10 01:22:06', '<p>Hi,</p><p>Please provide you cpanel details to us.</p><p><br></p>', 0, 17),
(37, 18, 0, 8, 'Farzana borna', 'borna.techvill@gmail.com', '2019-01-10 01:25:03', '<p>Have some technical problem.<br></p>', 0, 0),
(38, 20, 0, 0, 'Ratool Apparals', 'ratoolapparals@gmail.com', '2019-01-10 04:39:27', '<p>Admin reply<br></p>', 0, 1),
(39, 21, 0, 0, 'Ratool Apparals', 'ratoolapparals@gmail.com', '2019-01-10 05:07:53', '<p>reply<br></p>', 0, 1),
(40, 24, 0, 0, 'Ratool Apparals', 'ratoolapparals@gmail.com', '2019-01-14 01:20:51', '<p>ghkghk<br></p>', 0, 1),
(41, 24, 0, 0, 'Ratool Apparals', 'ratoolapparals@gmail.com', '2019-01-14 01:21:24', '<p>test</p>', 0, 18),
(42, 24, 0, 0, 'Ratool Apparals', 'ratoolapparals@gmail.com', '2019-01-14 01:22:20', '<p>gfjgf<br></p>', 0, 1),
(43, 25, 0, 0, 'Farzana borna', 'borna.techvill@gmail.com', '2019-01-14 01:24:18', '<p>test<br></p>', 0, 1),
(44, 25, 0, 0, 'Farzana borna', 'borna.techvill@gmail.com', '2019-01-14 01:25:18', '<p>tgdfhdfjgf fnfgj fjfgj</p>', 0, 18),
(45, 26, 0, 0, 'Customer Techvillage', 'customer@techvill.net', '2019-01-23 01:28:52', '<p>ghg</p>', 0, 1),
(46, 26, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2019-01-23 01:30:52', '<p>hdfgh<br></p>', 0, 0),
(47, 2, 0, 5, 'Customer Techvillage', 'customer@techvill.net', '2019-01-23 01:54:37', '<p>cghc<br></p>', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_status`
--

DROP TABLE IF EXISTS `ticket_status`;
CREATE TABLE IF NOT EXISTS `ticket_status` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isdefault` tinyint(4) NOT NULL DEFAULT '0',
  `statuscolor` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `statusorder` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_status`
--

INSERT INTO `ticket_status` (`id`, `name`, `isdefault`, `statuscolor`, `statusorder`) VALUES
(1, 'Open', 1, '#ff2d42', 0),
(2, 'In progress', 0, '#84c529', 0),
(3, 'On Hold', 0, '#c0c0c0', 0),
(4, 'Closed', 0, '#03a9f4', 0),
(5, 'Answered', 0, '#0000ff', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `password`, `full_name`, `role_id`, `phone`, `email`, `picture`, `inactive`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', '$2y$10$JnOJmdBYGYoqwlsbAaTGoOV1klI6NMFurDdBcVLs6fMzKSJhHQOgC', 'Admin', 1, NULL, 'admin@techvill.net', 'Desert.jpg', 0, 'g0YobPG56t5vSw98gxF5SLl0c1mF8Fz2FAaSVXtWHUzfqSJ5q9gdyQeJI3AW', '2018-08-14 04:25:15', '2019-01-19 23:36:55', NULL),
(15, '', '$2y$10$ZnQxJIzXauYcGNmfqa.Hwe82BtuzsgIkrUrbG9TxgZCHRFs4m8BYm', 'Rafiq', 1, NULL, 'rafiq.techvill@gmail.com', '', 0, 'eCSTy3dHgPH7FTYkFdusX6YtYywfxl0jOd7Ew4FlWWj87633WrVSNWw360xu', '2018-11-21 07:11:38', '2019-01-27 06:41:31', NULL),
(17, '', '$2y$10$O4OzW8XLLm13/Qr9czpkpusW804wH71cwTulFNP0wqwR7rAUkt86S', 'Borna', 1, NULL, 'borna606@gmail.com', '', 0, 'bL2SNBtleNnH21rULhJ4TYUJQ9rrez0s97B12zT9mhu7UbQiljQguQ9RDLJ5', '2018-11-25 01:35:11', '2019-02-03 04:19:02', NULL),
(18, '', '$2y$10$r9RA/L.HT3s11F7YEBYnxOooW1xD0vA5bxp4kcA76tlc8J0Ae7j1C', 'Md. Milon Hossain', 2, '01719283384', 'milon.techvill@gmail.com', 'bank.jpg', 0, 'FiWQwXP2pZ16NIRdMahhgURtO1UIgiyB5lv5iA4Xdqsikba48lztUiupJpy6', '2018-12-01 23:20:45', '2019-01-27 06:49:58', NULL),
(20, '', '$2y$10$.d715jbgPLITNH3C0foJO.OM80mvfS8ckKW8QIamx6R7pJ7x.x3rO', 'dfdfd', 1, '574564', 'rafiq@gmail.com', '', 0, '7wafak0kW9AaMeWMPeuz3GpOCHlbT2dMyOuC2XZTHhCBtirLGuQ6ulTSfKrs', '2019-01-15 01:14:39', '2019-01-19 23:38:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_departments`
--

DROP TABLE IF EXISTS `user_departments`;
CREATE TABLE IF NOT EXISTS `user_departments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_departments_user_id_foreign` (`user_id`),
  KEY `user_departments_department_id_foreign` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_departments`
--

INSERT INTO `user_departments` (`id`, `user_id`, `department_id`) VALUES
(6, 9, 1),
(7, 8, 1),
(8, 12, 1),
(9, 12, 2),
(10, 12, 3),
(11, 12, 4),
(12, 12, 5),
(13, 12, 6),
(14, 13, 1),
(15, 13, 2),
(16, 13, 3),
(17, 13, 4),
(18, 13, 5),
(19, 13, 6),
(20, 14, 1),
(21, 14, 2),
(22, 14, 3),
(24, 14, 5),
(27, 16, 1),
(28, 16, 3),
(29, 16, 5),
(30, 15, 2),
(31, 19, 1),
(32, 19, 3),
(33, 20, 1),
(34, 20, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD CONSTRAINT `bank_accounts_account_type_id_foreign` FOREIGN KEY (`account_type_id`) REFERENCES `bank_account_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_transactions`
--
ALTER TABLE `customer_transactions`
  ADD CONSTRAINT `customer_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_transactions_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `bank_account_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `custom_item_orders`
--
ALTER TABLE `custom_item_orders`
  ADD CONSTRAINT `custom_item_orders_tax_type_id_foreign` FOREIGN KEY (`tax_type_id`) REFERENCES `item_tax_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cust_branch`
--
ALTER TABLE `cust_branch`
  ADD CONSTRAINT `cust_branch_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `milestones`
--
ALTER TABLE `milestones`
  ADD CONSTRAINT `milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `payment_history_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `bank_account_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_history_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `bank_trans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_settings`
--
ALTER TABLE `project_settings`
  ADD CONSTRAINT `project_settings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_prices`
--
ALTER TABLE `purchase_prices`
  ADD CONSTRAINT `purchase_prices_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purch_orders`
--
ALTER TABLE `purch_orders`
  ADD CONSTRAINT `purch_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purch_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purch_order_details`
--
ALTER TABLE `purch_order_details`
  ADD CONSTRAINT `purch_order_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purch_order_details_purch_order_id_foreign` FOREIGN KEY (`purch_order_id`) REFERENCES `purch_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receive_orders`
--
ALTER TABLE `receive_orders`
  ADD CONSTRAINT `receive_orders_purch_order_id_foreign` FOREIGN KEY (`purch_order_id`) REFERENCES `purch_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receive_order_details`
--
ALTER TABLE `receive_order_details`
  ADD CONSTRAINT `receive_order_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_order_details_purch_order_id_foreign` FOREIGN KEY (`purch_order_id`) REFERENCES `purch_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_order_details_receive_id_foreign` FOREIGN KEY (`receive_id`) REFERENCES `receive_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_order_details`
--
ALTER TABLE `sales_order_details`
  ADD CONSTRAINT `sales_order_details_sales_order_id_foreign` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD CONSTRAINT `sale_prices_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_prices_sales_type_id_foreign` FOREIGN KEY (`sales_type_id`) REFERENCES `sales_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipment_details`
--
ALTER TABLE `shipment_details`
  ADD CONSTRAINT `shipment_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipment_details_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipment_details_tax_type_id_foreign` FOREIGN KEY (`tax_type_id`) REFERENCES `item_tax_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_master`
--
ALTER TABLE `stock_master`
  ADD CONSTRAINT `stock_master_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_master_tax_type_id_foreign` FOREIGN KEY (`tax_type_id`) REFERENCES `item_tax_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  ADD CONSTRAINT `stock_transfer_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier_transactions`
--
ALTER TABLE `supplier_transactions`
  ADD CONSTRAINT `supplier_transactions_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supplier_transactions_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supplier_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tags_in`
--
ALTER TABLE `tags_in`
  ADD CONSTRAINT `tags_in_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
