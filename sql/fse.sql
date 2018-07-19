-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2018 at 04:51 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sngbase`
--

-- --------------------------------------------------------

--
-- Table structure for table `fse`
--

CREATE TABLE `fse` (
  `fse_code` varchar(30) NOT NULL,
  `thainame` varchar(50) NOT NULL,
  `engname` varchar(50) NOT NULL,
  `abbr` varchar(50) NOT NULL,
  `company` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `service_center` varchar(30) NOT NULL,
  `section` varchar(30) NOT NULL,
  `team` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `token` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fse`
--

INSERT INTO `fse` (`fse_code`, `thainame`, `engname`, `abbr`, `company`, `position`, `service_center`, `section`, `team`, `status`, `email`, `phone`, `token`, `username`) VALUES
('0', '', 'Undefined', '', '', '', '', '', '', '', '', '', '', ''),
('103', 'คุณอลงกรณ์   แวงวรรณ', 'Alongkorn   Wangwan', 'AKW', 'SNG', 'FSE Service Center(North)', 'north', 'Service Center', '-', 'ready', 'alongkorn@synergize.co.th', '088-8097233', '', 'Alongkorn'),
('104', 'คุณเฉลิมชัย    พรมสีทา', 'Chaloemchai Promseeta', 'CCP', 'SNG', 'FSE Service Center(east)', 'east', 'Service Center', '-', 'ready', 'chaloemchai@synergize.co.th', '082-7966413', '', 'Chaloemchai'),
('108', 'คุณนิวัฒน์  วัชรศรีไพศาล', 'Niwat  Watcharasripaisan', 'NWW', 'SNG', 'Head of CM&Install', 'central', 'CM&install', '-', 'ready', 'niwat@synergize.co.th', '085-1567676,088-185-0308', '', 'Niwat'),
('109', 'คุณณัทพงศ์      ทัศนัย', 'Natthaphong  Thatsanai', 'NPT', 'SNG', 'Advisor Management', 'central', 'Technical support', '-', 'ready', 'natthaphong@synergize.co.th', '081-8642646', '', 'Natthaphong'),
('110', 'คุณสิทธา   มะลิซ้อน', 'Sitta Malison', 'STM', 'SNG', 'Head of Project', 'central', 'Project', '-', 'ready', 'sitta@synergize.co.th', '091-1194259', '', 'Sitta'),
('112', 'คุณอุทัย         วัลละภา', 'Uthai  Wallapa', 'UTW', 'SNG', 'Electrical technician', 'central', 'CM', '-', 'ready', 'uthai@synergize.co.th', '088-8743026', '', 'Uthai'),
('115', 'คุณดิฐกร ภิญโญสินวัฒน์', 'Dithakorn  Pinyosinwat', 'DKP', 'SNG', 'Head of Corrective Maintenance', 'central', 'PM ', 'PM team 1', 'ready', 'ditthakorn@synergize.co.th', '091-1194261', '', 'Dithakorn'),
('118', 'คุณโฆษิต  ทัศนัย', 'Kosit  Tussanai', 'KST', 'SNG', 'General Techincian ', 'central', 'PM ', 'PM team 1', 'ready', 'kosit@synergize.co.th', '088-0227691', '', 'Kosit'),
('119', 'คุณคามิน       อินธิเดช', 'Chamin  Inthidet', 'CMI', 'SNG', 'Electrical technician', 'central', 'CM', 'CM team 1', 'ready', 'camin@synergize.co.th', '085-6814911', '', 'Chamin'),
('122', 'คุณณรงค์เดช  สุภาพ', 'Narongdech  Supab', 'NDS', 'SNG', 'Electrical technician', 'central', 'PM', 'PM team 2', 'ready', 'narongdech@synergize.co.th', '081-1708071', '', 'Narongdech'),
('123', 'ณฐกร ทองดี', 'Natakorn Thongdee', 'NTK', 'SNG', 'Unicorn', 'Central', 'IT Department', 'DEV', 'Available', 'job.masker@gmail.com', '0922528107', '', 'Natakorn'),
('127', 'คุณไพรัตน์  ทัศนัย', 'Phairat   Thatsanai', 'PRT', 'SNG', 'FSE Operation', 'central', 'PM', 'PM team 1', 'ready', 'phairat@synergize.co.th', '095-3676090', '', 'Phairat'),
('129', 'คุณธีรพงษ์  สัดตะรัตนะ', 'Theerapong  Sattarattana', 'TPS', 'SNG', 'Electrical technician', 'central', 'CM&install', 'CM team 1', 'ready', 'theerapong@synergize.co.th', '095-2046915', '', 'Theerapong'),
('132', 'คุณสุภัทรพงษ์ คงธนสโรช', 'Suphatphong Khongthanasarot', 'SPK', 'SNG', 'Techincian Support', 'central', 'PM', 'PM team 3', 'ready', 'suphatphong@synergize.co.th', '095-2042691', '', 'Suphatphong'),
('134', 'คุณพิทักษ์      สละสำลี', 'Pitak Salasamlee', 'PTS', 'SNG', 'Head of  Critical', 'central', 'Critical', '-', 'ready', 'pitak@synergize.co.th', '097-2913433', '', 'Pitak'),
('136', 'คุณกันตภณ  สิทธิศักดิ์', 'Kantapon Sittisak ', 'KPS', 'SNG', 'Techincian Support', 'south', 'Service Center', '-', 'ready', 'kantapon@synergize.co.th', '', '', 'Kantapon'),
('137', 'คุณสุรพงษ์  บุญวิเศษ', 'Surapong  Bunvisas', 'SPB', 'SNG', 'Electrical technician', 'central', 'Critical', '-', 'ready', 'surapong@synergize.co.th', '095-204-6914', '', 'Surapong'),
('145', 'คุณสุรเชษฐ์    ดำแก้ว', 'Surachet  Dumkeaw', 'SCD', 'SNG', 'Head of PM', 'central', 'PM', '-', 'ready', 'surachet@synergize.co.th', '081-901-6321', '', 'Surachet'),
('153', 'คุณกฤษฎา วงศ์อนรรฆ', 'Kritsada   Vonganak', 'KDV', 'SNG', 'Techical Support', 'central', 'Technical support', '-', 'ready', 'kritsada@synergize.co.th', '087-711-8030', '', 'Kritsada'),
('157', 'คุณกิติพงษ์   เทพนันตา', 'Kitipong  Thananta', 'KPT', 'SNG', 'FSE Operation', 'central', 'CM&install', 'CM team 1', 'ready', 'kitipong@synergize.co.th', '095-368-5868', '', 'Kitipong'),
('163', 'คุณธันยวิทย์  สิทธิศักดิ์', 'Thanyawit  Sitthisak', 'TWS', 'SNG', 'general technician', 'central', 'PM', 'PM team 2', 'ready', 'thanyawit@synergize.co.th', '086-3039386', '', 'Thanyawit'),
('170', 'คุณชาคร  บุญเครือชู', 'Chakorn  Boonkuechoo', 'CKB', 'SNG', 'FSE Operation', 'northeast', 'Service Center', '-', 'ready', 'chakorn@synergize.co.th', '063-9057129', '', 'Chakorn'),
('171', 'คุณจักรกฤษ  บุญเรืองรอด', 'Jackkit   Boonraungrod', 'JKB', 'SNG', 'Techincian Support', 'central', 'PM ', 'PM team 3', 'ready', 'jackkit@synergize.co.th', '063-9057130', '', 'Jackkit'),
('172', 'คุณศุภชัย   คงนก', 'Supphachai  Khongnok', 'SCK', 'SNG', 'Electrical technician', 'central', 'PM', 'PM team 5', 'ready', 'supphachai@synergize.co.th', '063-9057131', '', 'Supphachai'),
('176', 'คุณพชร  พุ่มพวง', 'Pachara   Poompuang', 'PRP', 'SNG', 'FSE Operation', 'central', 'PM', 'PM team 4', 'ready', 'pachara@synergize.co.th', '063-9057132', '', 'Pachara'),
('181', 'คุณสุพจน์ชัย    ทัพซ้าย ', 'Supojchai   Tupsai', 'SCT', 'SNG', 'Electrical technician', 'central', 'Project', '-', 'ready', 'supojchai@synergize.co.th', '064-221-7313', '', 'Supojchai'),
('185', 'คุณรุ่งโรจน์    หินลอย', 'Rungroj  Hinloy', 'RJH', 'SNG', 'FSE Operation', 'central', 'site engineer uob', '-', 'ready', 'rungroj@synergize.co.th', '064-223-6505', '', 'Rungroj'),
('190', 'คุณนัฐพล  สุดแจ้ง', 'Natthapon Sudjang', 'NPS', 'SNG', 'Techincian Support', 'south', 'Service Center', 'south team', 'ready', 'natthapon@synergize.co.th', '', '', 'Natthapon'),
('191', 'คุณคมกริช   การปลื้มจิตต์', 'Komkrit   Kanpluemjill', 'KKK', 'SNG', 'General Techincian ', 'central', 'PM ', 'PM team 4', 'ready', 'komkrit@synergize.co.th', '094-962-1160', '', 'Komkrit'),
('401', 'คุณสมัชชา     รุทรพันธ์', 'Samachcha    Ruttarapan', 'SCR', 'SNGE', 'Electrical technician', 'central', 'CM ', 'CM UOB', 'ready', 'samachcha@sngengineering.com', '063-9057137', '', 'Samachcha'),
('456', 'ธนวัตน์ เพชรชื่น', 'Thanawat Petchuen', 'TPN', 'SNG', 'CTO', 'Central', 'IT Department', 'DEV', 'Available', 'thanawat1petchuen@gmail.com', '0971073060', '', 'Thanawat'),
('637', 'ภูวพงศ์  ศรีวิจารณ์', 'Poowapong Srivijarn', 'PPS', 'SNG', 'Asst. IT Manager', 'Central', 'IT Department', 'DEV', 'Available', 'poowapong@synergize.co.th', '0875858635', '', 'Poowapong'),
('801', 'คุณนิธิศ   จินดาเจี่ย', 'Nitit  Jindajia', 'NTJ', 'SNG', 'Air technician', 'south', 'service Center', 'south team', 'ready', 'nitit@synergize.co.th', '', '', 'Nitit'),
('802', 'คุณวิชัยชาญ   แจ่มจักษุ', 'Wichaichan  Chaemchaksu', 'WCC', 'SNGE', 'air technician', 'central', 'CM', 'CM UOB', 'ready', 'wichaichan@sngengineering.com', '063-8482089', '', 'Wichaichan'),
('803', 'คุณจีรศักดิ์  จันทร์ประสิทธิ์', 'Chirasak  Chanprasit', 'CSC', 'SNGE', 'general technician', 'central', 'CM', '-', 'ready', 'chirasak@sngengineering.com', '', '', 'Chirasak'),
('804', 'คุณธีรยุทธ  นราพงษ์', 'Thirayut  Naraphong', 'TYN', 'SNGE', 'general technician', 'central', 'CM ', 'CM UOB', 'ready', 'thirayut@sngengineering.com', '', '', 'Thirayut'),
('805', 'คุณประภัทร์  เรืองศรี', 'Prapat ruangsri', 'PPR', 'SNGE', 'air technician', 'central', '', '', 'ready', 'prapat@sngengineering.com', '', '', 'Prapat'),
('806', 'คุณบัณฑิต   หูพารักษา', 'Bandit  Hupharaksa', 'BDH', 'SNGE', 'Air technician', 'central', 'Service Center', '-', 'ready', 'bandit@sngengineering.com', '', '', 'Bandit'),
('807', 'คุณฉัตรมงคล  วงศ์กาอินทร์  ', 'Chatmongkol  Wongkain', 'CKW', 'SNGE', 'general technician', 'central', 'Project', '-', 'ready', 'chatmongkol@sngengineering.com', '', '', 'Chatmongkol');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fse`
--
ALTER TABLE `fse`
  ADD PRIMARY KEY (`fse_code`),
  ADD UNIQUE KEY `abbr` (`abbr`),
  ADD UNIQUE KEY `thainame` (`thainame`),
  ADD UNIQUE KEY `engname` (`engname`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fse`
--
ALTER TABLE `fse`
  ADD CONSTRAINT `fse_fk1` FOREIGN KEY (`username`) REFERENCES `account` (`username_tag`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
