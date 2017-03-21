-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- Estrutura da tabela `contact_service`

CREATE TABLE IF NOT EXISTS `contact_service` (
  `contact_service_id` int(10) NOT NULL AUTO_INCREMENT,
  `contact_service_date` datetime NOT NULL,
  `contact_service_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_message` text COLLATE utf8_unicode_ci,
  `contact_service_service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_price` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_newsletter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_service_sendtome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`contact_service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
