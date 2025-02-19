-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 25, 2024 at 03:54 PM
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
-- Table structure for table `star_logs`
--

DROP TABLE IF EXISTS `star_logs`;
CREATE TABLE IF NOT EXISTS `star_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) DEFAULT NULL,
  `level` text,
  `message` longtext,
  `time` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `star_logs`
--

INSERT INTO `star_logs` (`log_id`, `channel`, `level`, `message`, `time`) VALUES
(3, 'EOLogger', '{\"name\":\"INFO\",\"code\":200}', '[2024-09-25T23:53:00.583221+08:00] EOLogger.INFO: Article updating succeeded {\"route\":\"articles.save.update\",\"data\":{\"banner\":\"http://localhost/sales-training-system/Cdn/images/articles/\",\"modified_by\":\"Eman Olivas\",\"title\":\"testtest\",\"content\":\"&#60;p&#62;fsdf&#60;/p&#62;\",\"category\":\"Blog\",\"is_published\":\"1\",\"created_by\":\"Eman Olivas\",\"csrf_token\":\"971392531be93102a3b03021e3945126ec9d760dec1740b6019c03b636077e66\",\"updated_by\":\"Eman Olivas\"}} []\n', 1727279580);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
