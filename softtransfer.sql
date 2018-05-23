-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2016 at 07:59 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softtransfer`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(8) NOT NULL,
  `address` text NOT NULL,
  `beneficiary_name` varchar(25) NOT NULL,
  `beneficiary_address` text NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `swift` varchar(255) NOT NULL,
  `iban` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `short_name`, `address`, `beneficiary_name`, `beneficiary_address`, `account_number`, `swift`, `iban`) VALUES
(3, 'Banif Bank Malta', 'bbm', 'St. Julians, Malta', '', '', '', '', ''),
(4, 'BNP Paribas', 'bnpp', 'Warsaw, Poland', '', '', '', '', ''),
(5, 'Bank of Valletta (EFT)', 'eft', 'Valletta, Malta', '', '', '', '', ''),
(6, 'Bank of Valletta (C&C FX)', 'bov', 'Valletta, Malta', '', '', '', '', ''),
(7, 'MashreqBank PSC', 'psc', 'Deira, Dubai', '', '', '', '', ''),
(8, 'Raiffeisen Bank International AG', 'rbiag', 'Vienna, Austria', '', '', '', '', ''),
(9, 'AB SwedBank (Orion Securities)', 'absb', 'Vilnius, Lithuania', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `currencys`
--

CREATE TABLE `currencys` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencys`
--

INSERT INTO `currencys` (`id`, `name`) VALUES
(4, 'AED'),
(5, 'AUD'),
(6, 'CAD'),
(1, 'EUR'),
(3, 'GBP'),
(7, 'SAR'),
(2, 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile_phone` varchar(255) NOT NULL,
  `landline_phone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website_url` varchar(255) NOT NULL,
  `account_holder` varchar(255) NOT NULL,
  `beneficiary_address` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `eur_iban` varchar(255) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `swift_bic` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `fname`, `lname`, `address`, `mobile_phone`, `landline_phone`, `fax`, `skype`, `email`, `website_url`, `account_holder`, `beneficiary_address`, `bank_name`, `bank_address`, `eur_iban`, `reference`, `swift_bic`, `created_at`, `updated_at`) VALUES
(34, 'Kellys', 'Ltd', 'Disney Land, London', '77744411125', '5554441125', '5554441125', 'mik.key-123', 'mikkey@gmail.com', 'mikkey.com', 'Jeremy Ox', 'Disney Land, London', 'Disneys Bank', 'Disney Land', '63GWHBDS62', 'JWB36DFGD87', 'OQKDSGDTR', '2016-04-04 09:48:08', '2016-04-10 08:35:15'),
(36, 'Kosta', 'Kosta', 'Kosta St. 123, City, Country', '7777444411', '8888555541', '8888555541', 'kosta-kosta', 'kosta@kosta.com', 'google.com', 'Adam Adams', 'Kosta St. 123, City, Country', 'Bank Name 23', 'Kosta St. 123/98', '7BDK3NB32', '3JM3NK2378FSDH', 'PWKQWDKOW', '2016-04-07 07:00:16', '2016-04-07 07:00:16'),
(37, 'Monicas Shop', 'Ltd', 'swkjsoci.l dcnhwju 423/23', '44457812', '2345678912', '2345678912', 'skype.skype', 'money@utmarkets.com', 'monicas.com', 'Monica Lehass', 'swkjsoci.l dcnhwju 423/23', 'Monicas Bank', 'swkjsoci.l dcnhwju 423/23', 'JHS7DBF3I', 'FEW8734JED7', 'EWEVWEWGW', '2016-04-10 08:40:56', '2016-04-10 08:40:56'),
(38, 'Webb', 'Merch', 'REBERB', '000927836', '777272729', '321321242312', 'skype', 'email@gmail.com', 'www.site.com', 'erhr', 'erherh', 'Bank', 'knml', '8HD983HD', 'DH93723BNDF23', 'OOCWENQCWEC', '2016-04-13 08:14:58', '2016-04-13 08:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `merch_bank`
--

CREATE TABLE `merch_bank` (
  `bank_id` varchar(30) NOT NULL,
  `merchant_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `merch_bank`
--

INSERT INTO `merch_bank` (`bank_id`, `merchant_id`) VALUES
('bnpp,eft,psc', '33'),
('bov,psc,bnpp,eft', '34'),
('bbm,bov,rbiag', '35'),
('bbm,bnpp,rbiag', '36'),
('bbm,bov,psc', '37'),
('bbm,bnpp,rbiag,absb', '38'),
('bbm,bnpp,eft', '39'),
('bbm,bnpp,absb', '40');

-- --------------------------------------------------------

--
-- Table structure for table `merch_currencys`
--

CREATE TABLE `merch_currencys` (
  `merchant_id` int(11) NOT NULL,
  `currency_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `merch_currencys`
--

INSERT INTO `merch_currencys` (`merchant_id`, `currency_id`) VALUES
(33, 'EUR,AUD,SAR'),
(34, 'EUR,AUD,AED,GBP'),
(35, 'EUR,AUD,SAR'),
(36, 'EUR,USD,GBP'),
(37, 'USD,GBP,CAD'),
(38, 'EUR,AED'),
(39, 'EUR,AED,SAR'),
(40, 'EUR,AUD,CAD');

-- --------------------------------------------------------

--
-- Table structure for table `resetpass`
--

CREATE TABLE `resetpass` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resetpass`
--

INSERT INTO `resetpass` (`email`, `token`, `created_at`, `updated_at`) VALUES
('email@gmail.com', '$2y$10$jVtQSupQoqqHvs5xCQTO7uFGdEu.QeL6gIqC88hxLovj/vuF2fnNG', '2016-04-11 10:09:56', '0000-00-00 00:00:00'),
('email2@gmail.com', 'iwxNdrX9ZnSXf2CF5E3f4Wvbj7PppUlq', '2016-04-11 10:22:20', '0000-00-00 00:00:00'),
('email@gmail.com', 'BblPmhzh1NzOGXbpXtKwVz89aYEJNSag', '2016-04-11 11:37:45', '0000-00-00 00:00:00'),
('email@gmail.com', 'o00b4A1uplzDuuuAWK6OOkhiKWluNQ9n', '2016-04-11 11:38:47', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'wVb2FTaeA7kFZU9q58A5C87KMKI1ijQY', '2016-04-11 11:46:32', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'oHseMd3RGJzsOzu8pANta8Wyj2T0yF7S', '2016-04-11 11:46:46', '0000-00-00 00:00:00'),
('elenag@payobin.com', '8PBNLUNREIGDwBIeAqSeAwDKIIWmHoi9', '2016-04-11 11:49:08', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'LmDKCYreu4Ga60PGEDQmSgciZLS5IukO', '2016-04-11 11:50:16', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'gfw4mpWYdOx5yNNwAdGS3oEe9cGbBXfP', '2016-04-11 11:51:40', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'DwiMXek5NVhnSXEKoaoL5TYxbZmFmN1e', '2016-04-11 11:53:00', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'SaWQrpAAgpT34unk5uY11Mad5aUw01G1', '2016-04-11 11:53:40', '0000-00-00 00:00:00'),
('elenag@payobin.com', '0btiNYIsCho59oYiEcqEO1ehRIlKwCea', '2016-04-11 11:55:54', '0000-00-00 00:00:00'),
('elenag@payobin.com', '0zR72VIHPvLv6GsD5hLFuEbIwEEmUADH', '2016-04-11 11:56:35', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'Sl8j9eBjV70iWlNloKY31FZw3WPkbWVA', '2016-04-11 11:57:41', '0000-00-00 00:00:00'),
('email@gmail.com', 'f0jQZZZGJt60dM8TEM1XKNggJFOqkL8w', '2016-04-11 12:00:34', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'noOVFtles9xQVfmoP7gVmCe4f3JUJjJ2', '2016-04-11 12:02:22', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'uHHxL5A1hOTzfIzMn85JTUp3wp1OtsSB', '2016-04-11 12:04:05', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'KTcaIquqsdYVFz6JAmRsUVHcT5Bwde2y', '2016-04-11 12:08:06', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'zH2paMRzaLXkGT9DiTPY6WNjGOp7aGvm', '2016-04-11 12:08:33', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'jfAOnpCOmZrUkdR4UNfKbXqVKpL5Gn5a', '2016-04-11 12:14:48', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'BzKEAdAwe2WeDfFKoz5xAc4APTXlbQzM', '2016-04-11 12:15:14', '0000-00-00 00:00:00'),
('elenag@payobin.com', '8aYRkfSy2kIdZJXXojzlXq1vVTh0Z4PW', '2016-04-11 12:17:25', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'nOCVOr82vOkScgGx5E9BSidbP4QRCiuJ', '2016-04-11 12:45:43', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'vP9na0gURFPVhahvDlCfGyhfBCg58T2S', '2016-04-11 12:45:59', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'vdxcpWha4UblesLQ4qWflbeFgxzHwRrp', '2016-04-11 12:47:07', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'hsvCQ4xi4HTebdz1TV1KrGd0xQDUofe1', '2016-04-13 10:26:30', '0000-00-00 00:00:00'),
('elenag@payobin.com', 'osxJ5IbYQ7EqxExNvMViAtpF8b56YUyk', '2016-04-13 11:03:43', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'UzrHJyfd7uKCF5gmtzDo8FeeKBRNS4UA', '2016-04-13 11:15:34', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'EibzE02cXqksbgoNpJ4cfzH8LY8zzFIY', '2016-04-13 11:16:57', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'JeusMEmOePkakDm7NoVMPXZI9F01ZmYj', '2016-04-13 11:17:07', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'LTijWlc3aeha4jT7ILRSIkxVadMdE9Ue', '2016-04-13 11:19:47', '0000-00-00 00:00:00'),
('kelly@gmail.com', '4olGAB4HhkEtmq2ByeIb4BDMDRgwsQAZ', '2016-04-13 11:28:52', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'gmMVBxJMLVCKkeLjpF2rzYLAWlNK6wtm', '2016-04-13 11:31:56', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'MhnbUU6vXOqk9Pifcjcmk56gfwt3brhj', '2016-04-13 11:33:08', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'mUA2oEPrqbSuUBW2QtJpGWttoyTxrqbu', '2016-04-13 11:33:19', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'ZOpvg9Mawq8376yoT9DTb3tNQKFNdJj7', '2016-04-13 11:37:56', '0000-00-00 00:00:00'),
('kelly@gmail.com', '8YYuXUM8vxiK9kEzIfpjyKFcwO1VVTAK', '2016-04-13 11:38:06', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'bZ9tCz8L4unVnJtIUJwkuv5ustipFDJN', '2016-04-13 11:38:42', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'EZVm1x9YSxQkyq6DVcxCjjdtzJKW1Wef', '2016-04-13 11:38:55', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'xTf5s3THBpLMlpWzv23MrL9DZxRQvQgC', '2016-04-13 11:38:58', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'OEWHgq0hI936vgansKWPW4sFNdlBlDFR', '2016-04-13 11:39:27', '0000-00-00 00:00:00'),
('kelly@gmail.com', 'sj1bsGvKdLIj2ZK4NkcKPg9zYLcfzirZ', '2016-04-13 11:42:01', '0000-00-00 00:00:00'),
('sarah@sarah.com', 'tPtx1Pn64fLoWAIDvNPx4lclwcqLaQAZ', '2016-04-13 11:44:26', '0000-00-00 00:00:00'),
('sarah@sarah.com', '8HDpECDnIukcX11nsrAROum86lb8VKpm', '2016-04-13 11:45:51', '0000-00-00 00:00:00'),
('sarah@sarah.com', 'SeiLYPCjjCble334A8QdHhD0rv198f7R', '2016-04-13 11:46:14', '0000-00-00 00:00:00'),
('sarah@sarah.com', 'nUSM3zVQ3VpxYHEkT6vapSkxCdXp7RJf', '2016-04-13 11:48:23', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`user_id`, `role_id`) VALUES
(11, 8),
(12, 5),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(20, 3),
(21, 3),
(22, 3),
(24, 3),
(25, 3),
(24, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 8),
(28, 8),
(30, 3),
(31, 5);

-- --------------------------------------------------------

--
-- Table structure for table `settlements`
--

CREATE TABLE `settlements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settlements`
--

INSERT INTO `settlements` (`id`, `name`, `merchant_id`, `created_at`, `updated_at`) VALUES
(1, 'settlement-history1.jpg', 34, '2016-04-06 11:07:20', '2016-04-06 14:23:07'),
(2, 'settlement-history2.jpg', 34, '2016-04-06 14:12:24', '2016-04-06 15:16:33'),
(3, '', 34, '2016-04-06 13:01:49', '2016-04-06 13:01:49'),
(5, '123', 34, '2016-04-06 14:12:55', '2016-04-06 14:12:55'),
(6, 'settlement.jpeg', 34, '2016-04-10 07:12:40', '2016-04-10 07:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `merchant_id` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `password`, `merchant_id`, `phone`, `skype`, `created_at`, `updated_at`) VALUES
(3, 'Jane', 'Doe', 'email@gmail.com', '12345', '2', '789456123', 'skepr', '2016-03-14 00:00:00', '2016-03-14 00:00:00'),
(6, 'fwvdvebvdsv', 'bdfabfvc', 'avfafd@email.com', 'wevwEVwe', 'evevWVE', '351343463', 'dvsdvsdv', '2016-03-14 00:00:00', '2016-03-14 00:00:00'),
(7, 'sdvsadvsdv', 'dsvasdasd', 'vsadvadsv@email.com', 'wEWGEegw', 'WEGAEGEAR', '523523553', 'dfbfbdfbdfb', '2016-03-14 00:00:00', '2016-03-14 00:00:00'),
(8, 'aefaearbeabre', 'aereagargarg', 'aegraergarg@gmail.comm', 'ergergre', 'WEFWEE', '452452', 'gdgdgf', '2016-03-14 00:00:00', '2016-03-14 00:00:00'),
(9, 'vsdvsdvsd', 'dsvvsdvsdv', 'sdvsdvrtjuk@email.com', 'jrjtrjt', 'ehrrhreherh', '45757574745', 'fngnttnt', '2016-03-14 00:00:00', '2016-03-14 00:00:00'),
(10, 'fiffjnbbhjhj', 'hhbjhvbjv ', 'bdffnfxxn@gmail.com', 'rgrgaergrarf', 'reaEAbrfb', '4575557', 'fbdxfbf', '2016-03-14 00:00:00', '2016-03-14 00:00:00'),
(11, 'Ellena', 'Golubeva', 'elenag@payobin.com', '$2y$10$DsuT4urDkvKeF4z.eWM4oenfdg0w0zRxmCvvbES8SsPOfAEXYAHoG', '', '995544466', 'payobin.support', '2016-03-14 00:00:00', '2016-04-13 12:13:10'),
(27, 'Support', 'Center', 'support@payobin.com', '$2y$10$Okuk4WDH/vF/XToVVR4yguLjB.VphnpuA5vfAdr50c8UtEnEwP3NG', '', '1111555578', 'payobin.support', '2016-04-07 09:15:39', '2016-04-13 14:06:39'),
(28, 'Application', 'Team', 'application@payobin.com', '$2y$10$/S0Ws0w7zXOJUmP0oSCz8.Zs53.kcrMRF1h5dAjWa4rLhrgukuFUC', '', '555444666', 'application.payobin', '2016-04-07 09:29:39', '2016-04-13 14:06:22'),
(29, 'Kelly', 'Mary', 'kelly@gmail.com', '$2y$10$wZ4/03vfRgvsozsu5o4n0OvcaQjsbxGjQQFjruiYU7D/DtFg3F7l2', '36', '111444777', '55544485', '2016-04-10 08:26:47', '2016-04-10 08:26:47'),
(30, 'Sarah', 'Jones', 'sarah@sarah.com', '$2y$10$oQdSEWxY90FeJBcaM7UNDuv7EL5kfDgTOdpuiQqrmZK8a6LBXxw8.', '36', '123123123', 'skype.sarah', '2016-04-10 08:33:52', '2016-04-10 08:33:52'),
(31, 'Nastya', 'Kapandze', 'anastaciak@payobin.com', '$2y$10$qxtw40LDpX4lhaq/b.wGDuRlKAwj5FYcvmQzYxJKo25ejP8Sh8hHm', '', '45455856825682', '0528258258205', '2016-04-10 10:15:48', '2016-04-10 10:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `wires`
--

CREATE TABLE `wires` (
  `status` varchar(15) NOT NULL,
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `merchant_id` varchar(255) NOT NULL,
  `sent_to_bank` varchar(255) NOT NULL,
  `sending_country` varchar(255) NOT NULL,
  `client_phone` varchar(255) NOT NULL,
  `client_email` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `amount_sent` varchar(255) NOT NULL,
  `amount_received` varchar(25) NOT NULL,
  `kyc` varchar(255) NOT NULL,
  `wc` varchar(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wires`
--

INSERT INTO `wires` (`status`, `id`, `client_name`, `merchant_id`, `sent_to_bank`, `sending_country`, `client_phone`, `client_email`, `currency`, `amount_sent`, `amount_received`, `kyc`, `wc`, `created_at`, `updated_at`) VALUES
('Pending', 19, 'Sebastian Koull', '34', 'MashreqBank PSC', 'Malta', '777455585', 'seba@gmail.com', 'GBP', '5000', '4989.60', 'none', '', '2016-04-05 12:43:52', '2016-04-13 10:03:20'),
('Complience', 27, 'Mortie Dorty', '34', 'Raiffeisen Bank International AG', 'Bangladesh', '44455564', 'mortie@gmail.com', 'EUR', '700', '685.90', 'none', '', '2016-04-10 09:00:07', '2016-04-12 07:17:16'),
('Paid', 28, 'Sarah Morez', '34', 'bnpp', 'Bangladesh', '77788546', 'sarah@gmail.com', 'USD', '600', '598.6', 'approved', '', '2016-04-10 09:15:55', '2016-04-12 07:18:45'),
('Complience', 30, 'Lala Land', '34', 'Bank of Valletta (C&C FX)', 'Cape Verde', '123454567', 'lala@gmail.com', 'GBP', '600.00', '599.80', 'approved', '', '2016-04-12 14:03:36', '2016-04-13 09:33:46'),
('Paid', 31, 'Lalalalala', '34', 'bov', 'Mozambique', '9995554788', 'lalalal@gmail.com', 'CAD', '800.65', '700', 'approved', '', '2016-04-12 14:09:22', '2016-04-13 10:07:48'),
('Complete', 32, 'frbdfbdfbdf', '34', 'eft', 'Austria', '65436346', 'dfbdfbdf@gmail.com', 'EUR', '400', '380', 'approved', '', '2016-04-13 09:30:49', '2016-04-13 10:02:24'),
('Pending', 33, 'rgwergrege', '34', 'bov', 'Bahamas', '43444444444', 'emgrail@gmail.com', 'EUR', '600', '500', 'uploaded', '', '2016-04-13 09:34:09', '2016-04-13 10:07:25'),
('Pending', 34, 'rvwerv ewve', '36', 'rbiag', 'Bangladesh', '34634634634', 'dbfbdfb@gmail.com', 'USD', '500', '-', 'uploaded', 'n', '2016-04-13 13:53:37', '2016-04-13 13:53:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `currencys`
--
ALTER TABLE `currencys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settlements`
--
ALTER TABLE `settlements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wires`
--
ALTER TABLE `wires`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `currencys`
--
ALTER TABLE `currencys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `settlements`
--
ALTER TABLE `settlements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `wires`
--
ALTER TABLE `wires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
