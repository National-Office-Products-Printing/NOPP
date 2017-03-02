-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- Estrutura da tabela `contact_support`

CREATE TABLE IF NOT EXISTS `contact_support` (
  `contact_support_id` int(10) NOT NULL AUTO_INCREMENT,
  `contact_support_date` datetime NOT NULL,
  `contact_support_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_support_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_support_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_support_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_support_message` text COLLATE utf8_unicode_ci,
  `contact_support_ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_support_newsletter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_support_sendtome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`contact_support_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
