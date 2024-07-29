-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 01, 2024 at 01:39 PM
-- Server version: 8.0.21
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `star`
--

-- --------------------------------------------------------

--
-- Table structure for table `star_accounts`
--

DROP TABLE IF EXISTS `star_accounts`;
CREATE TABLE IF NOT EXISTS `star_accounts` (
  `account_id` bigint NOT NULL AUTO_INCREMENT,
  `organization_id` bigint NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `account_permission` text,
  `registered_at` int NOT NULL,
  PRIMARY KEY (`account_id`),
  KEY `fk_account_organization` (`organization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `star_accounts`
--

INSERT INTO `star_accounts` (`account_id`, `organization_id`, `username`, `email`, `password`, `account_type`, `status`, `account_permission`, `registered_at`) VALUES
(1, 1, 'eman', 'eman.olivas@gmail.com', '9aa126e302832b2c95e29b11263b5e9f', 'Administrator', 'active', NULL, 1714397335);

-- --------------------------------------------------------

--
-- Table structure for table `star_accounts_login`
--

DROP TABLE IF EXISTS `star_accounts_login`;
CREATE TABLE IF NOT EXISTS `star_accounts_login` (
  `account_login_id` bigint NOT NULL AUTO_INCREMENT,
  `account_id` bigint NOT NULL,
  `session_id` text COMMENT 'a User session id',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT 'Possible value 1 or 0',
  `login_details` text COMMENT 'Possible value \r\n{"ip_address":"158.62.33.138","user_agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari\\/537.36 Edg/122.0.0.0","browser_name":"Edge","browser_version":"122.0.0.0","platform":"Windows 10","location":{"continent":"Asia","timezone":"Asia\\/Manila","country_name":"Philippines","country_code":"PH","region_name":"Metro Manila","city":"Quezon City","latitude":"14.6475","longitude":"121.0494","location_accuracy_radius":"10"}}\r\nJSON Format',
  `login_at` int NOT NULL DEFAULT '0' COMMENT 'epoch of time',
  PRIMARY KEY (`account_login_id`),
  KEY `fk_account_logins` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `star_accounts_login`
--

INSERT INTO `star_accounts_login` (`account_login_id`, `account_id`, `session_id`, `status`, `login_details`, `login_at`) VALUES
(1, 1, '5qdi3hn0qbr22eih6f88gmi5ck', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714401541),
(2, 1, '5js0qn1uq7se3e86r9l7lnoce2', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714466450),
(3, 1, 'pu7pjai20d872rp02ca2os8g80', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714466884),
(4, 1, 'vdsufvoir3k1uft0a5nko31f5r', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714466892),
(5, 1, '87eok7m6ihme9ldptkt41116es', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467148),
(6, 1, 'u9f4m1mmscd7hpbvh105a10hh8', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467259),
(7, 1, 'u9f4m1mmscd7hpbvh105a10hh8', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467282),
(8, 1, 'q9g9bqrjm406pnan1shm9sj262', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467293),
(9, 1, 'q9g9bqrjm406pnan1shm9sj262', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467336),
(10, 1, 'kcsfc5klnstpoif8u5uuuhq0hc', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467426),
(11, 1, 'kcsfc5klnstpoif8u5uuuhq0hc', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467518),
(12, 1, 'jnkme2ekg3r22ore84svn8bmqt', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467597),
(13, 1, 'jnkme2ekg3r22ore84svn8bmqt', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467713),
(14, 1, 'dg344gbqe1q4dch01gjrfmrul6', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714467865),
(15, 1, 'ouor6v5rjh2u3um8kdohcch181', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714468013),
(16, 1, 'b4etgjhkjt04t3j93l147h6jjf', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714468206),
(17, 1, 'lqnhcf9o1opqnnlgdha1nkg1hl', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714468378),
(18, 1, 'riin5vvnstkag8popggd6cvdth', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"geo\":{\"ip\":\"158.62.34.164\",\"city\":\"Quezon City\",\"region\":\"Metro Manila\",\"country\":\"PH\",\"loc\":\"14.6488,121.0509\",\"org\":\"AS132199 Globe Telecom Inc.\",\"postal\":\"1100\",\"timezone\":\"Asia\\/Manila\",\"readme\":\"https:\\/\\/ipinfo.io\\/missingauth\"},\"browser\":\"Unknown Browser\"}', 1714468478),
(25, 1, '2lnbjlm43tcd9jpdcpmp9ncnre', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714476865),
(26, 1, '1p5l42ac2en0c1fmnbjoosimd4', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714478303),
(27, 1, 'vnr297ksc867gmggo99dthhtlm', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714479130),
(28, 1, 'vnr297ksc867gmggo99dthhtlm', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714479595),
(29, 1, 'a8cfoj4erpr86c2ao649en708n', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714479658),
(30, 1, 'ctdsr428k33krj873nculnbqgq', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714535995),
(31, 1, '6e9n6k7uesigsq97a1m74l7rph', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714538966),
(32, 1, '6e9n6k7uesigsq97a1m74l7rph', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714539015),
(33, 1, '6e9n6k7uesigsq97a1m74l7rph', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714539118),
(34, 1, 'nhoun8j1899vfgumlanpl18f3u', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545285),
(35, 1, 'nhoun8j1899vfgumlanpl18f3u', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545315),
(36, 1, 'nhoun8j1899vfgumlanpl18f3u', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545328),
(37, 1, 'nhoun8j1899vfgumlanpl18f3u', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545383),
(38, 1, 'nhoun8j1899vfgumlanpl18f3u', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545620),
(39, 1, 's7sbtdorcohdo7tvotn9b61f7t', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545689),
(40, 1, '4durhjm2qr3pf214cg3sjm5lhu', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545744),
(41, 1, '10u5en3hahd4nb9ibn6v1nvq82', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714545877),
(42, 1, '69oka8m25vt0h7nggmpvtc1tvq', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714546063),
(43, 1, '9pk5rvusasa0mdho3g95tr2qfp', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714546142),
(44, 1, 'bdaen3mqml1ieboaecs3tslkio', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714546234),
(45, 1, '309or3sdp5bo9t7c7dbs3mbqfa', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714546242),
(46, 1, 'kki67q1c29elefr1g5d86n4b4b', 0, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714546290),
(47, 1, 'cq4ntcb7fgl4e4acdk7mntp83l', 1, '{\"userAgent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/124.0.0.0 Safari\\/537.36 Edg\\/124.0.0.0\",\"browser\":\"Unknown Browser\"}', 1714546428);

-- --------------------------------------------------------

--
-- Table structure for table `star_accounts_profile`
--

DROP TABLE IF EXISTS `star_accounts_profile`;
CREATE TABLE IF NOT EXISTS `star_accounts_profile` (
  `profile_id` bigint NOT NULL AUTO_INCREMENT,
  `account_id` bigint NOT NULL,
  `contact_number` text COMMENT '["","",""...]',
  `profile_image` text,
  `name` text COMMENT ' {\r\n "prefix": "",\r\n "firstname": "",\r\n "lastname": "",\r\n "suffix": "",\r\n }',
  `birthdate` date DEFAULT NULL,
  `address` text COMMENT '{\r\n "permanent": [\r\n  "region": "",\r\n  "province": "",\r\n  "municipality": "",\r\n  "barangay": ""\r\n ],\r\n "current": [\r\n  "region": "",\r\n  "province": "",\r\n  "municipality": "",\r\n  "barangay": ""\r\n ]\r\n }',
  `skills` text COMMENT '["","","",""...]',
  `certifications` text COMMENT '["","","",""...]',
  `professions` text COMMENT '["","","",""...]',
  `work_experience` text COMMENT '\r\n "company": "",\r\n "position": "",\r\n "job_description": "",\r\n "date": [\r\n  "date_hire": "",\r\n  "date_resigned": ""\r\n ]\r\n }',
  `education` text COMMENT '{\r\n "school": "",\r\n "degree": "",\r\n "graduated_at": 0\r\n }',
  `social_profile` text COMMENT '{\r\n "facebook": "",\r\n "linkedIn": "",\r\n ...\r\n }',
  `updated_at` int NOT NULL,
  PRIMARY KEY (`profile_id`),
  KEY `fk_profile_account` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `star_accounts_profile`
--

INSERT INTO `star_accounts_profile` (`profile_id`, `account_id`, `contact_number`, `profile_image`, `name`, `birthdate`, `address`, `skills`, `certifications`, `professions`, `work_experience`, `education`, `social_profile`, `updated_at`) VALUES
(1, 1, NULL, 'http://localhost/sales-training-system/Cdn/images/kyc-picture-sample.png', '{\"prefix\": \"\", \"firstname\": \"Eman\", \"lastname\": \"Olivas\", \"suffix\": \"\"}', '1988-08-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1714397335);

-- --------------------------------------------------------

--
-- Table structure for table `star_organizations`
--

DROP TABLE IF EXISTS `star_organizations`;
CREATE TABLE IF NOT EXISTS `star_organizations` (
  `organization_id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `logo` text,
  `privileges` text,
  `created_at` int NOT NULL,
  PRIMARY KEY (`organization_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `star_organizations`
--

INSERT INTO `star_organizations` (`organization_id`, `name`, `description`, `logo`, `privileges`, `created_at`) VALUES
(1, 'The Organization', 'test Organization', NULL, NULL, 1714397335);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `star_accounts`
--
ALTER TABLE `star_accounts`
  ADD CONSTRAINT `fk_account_organization` FOREIGN KEY (`organization_id`) REFERENCES `star_organizations` (`organization_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `star_accounts_login`
--
ALTER TABLE `star_accounts_login`
  ADD CONSTRAINT `fk_account_logins` FOREIGN KEY (`account_id`) REFERENCES `star_accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `star_accounts_profile`
--
ALTER TABLE `star_accounts_profile`
  ADD CONSTRAINT `fk_profile_account` FOREIGN KEY (`account_id`) REFERENCES `star_accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
