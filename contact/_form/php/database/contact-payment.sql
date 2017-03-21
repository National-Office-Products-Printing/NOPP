-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- Estrutura da tabela `contact_payment`

CREATE TABLE IF NOT EXISTS `contact_payment` (
  `contact_payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `contact_payment_date` datetime NOT NULL,
  `contact_payment_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_message` text COLLATE utf8_unicode_ci,
  `contact_payment_customerid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_price` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_newsletter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_payment_sendtome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`contact_payment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
