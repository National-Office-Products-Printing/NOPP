-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- Estrutura da tabela `contact_recurring`

CREATE TABLE IF NOT EXISTS `contact_recurring` (
  `contact_recurring_id` int(10) NOT NULL AUTO_INCREMENT,
  `contact_recurring_date` datetime NOT NULL,
  `contact_recurring_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_message` text COLLATE utf8_unicode_ci,
  `contact_recurring_customerid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_plan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_price` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_newsletter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_recurring_sendtome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`contact_recurring_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
