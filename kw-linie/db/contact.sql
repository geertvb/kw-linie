-- phpMyAdmin SQL Dump
-- version 3.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 18, 2009 at 11:13 AM
-- Server version: 5.1.31
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `kwlinie_be`
--

-- --------------------------------------------------------

--
-- Table structure for table `kwl_contact`
--

CREATE TABLE IF NOT EXISTS `kwl_contact` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `voornaam` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `straat` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `nummer` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `postcode` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `gemeente` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `telefoon` varchar(24) CHARACTER SET utf8 DEFAULT NULL,
  `gsm` varchar(24) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `kwl_contact`
--

INSERT INTO `kwl_contact` (`contact_id`, `naam`, `voornaam`, `straat`, `nummer`, `postcode`, `gemeente`, `telefoon`, `gsm`, `email`) VALUES
(1, 'Van Bastelaere', 'Geert', 'Vlinderlaan', '6 bus 2', '3000', 'Leuven', '014 / 40 70 22', '0495/27 14 54', 'geert.van.bastelaere@telenet.be'),
(2, 'Smeyers', 'Evi', 'Vlinderlaan', '6/2', '3000', NULL, '016/407022', '0496/239954', 'evi.smeyers@telenet.be');
