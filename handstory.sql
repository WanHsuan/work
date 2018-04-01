-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 12, 2018 at 03:51 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `handstory`
--

-- --------------------------------------------------------

--
-- Table structure for table `Answer`
--

CREATE TABLE `Answer` (
  `QuestionID` int(11) NOT NULL,
  `AnswerID` int(11) NOT NULL,
  `AnswerContent` varchar(1000) NOT NULL,
  `AnswerDateTime` datetime NOT NULL,
  `StoreID` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Chooseproduct`
--

CREATE TABLE `Chooseproduct` (
  `ShoppingRecordID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ChooseproductCount` int(11) NOT NULL,
  `ChooseproductAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerName` varchar(45) NOT NULL,
  `CustomerUsername` varchar(45) NOT NULL,
  `CustomerPassword` mediumtext NOT NULL,
  `CustomerPhone` varchar(45) DEFAULT NULL,
  `CustomerBirth` date DEFAULT NULL,
  `CustomerMail` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`CustomerID`, `CustomerName`, `CustomerUsername`, `CustomerPassword`, `CustomerPhone`, `CustomerBirth`, `CustomerMail`) VALUES
(1, '123', '123', '134', '', '2018-03-04', '');

-- --------------------------------------------------------

--
-- Table structure for table `CustomerComment`
--

CREATE TABLE `CustomerComment` (
  `CustomerCommentID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `TransactionID` int(11) NOT NULL,
  `CustomerCommentScore` int(2) DEFAULT NULL,
  `CustomerCommentContent` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CustomerComment`
--

INSERT INTO `CustomerComment` (`CustomerCommentID`, `CustomerID`, `TransactionID`, `CustomerCommentScore`, `CustomerCommentContent`) VALUES
(1, 1, 1, 12, '123');

-- --------------------------------------------------------

--
-- Table structure for table `CustomerUse`
--

CREATE TABLE `CustomerUse` (
  `CustomerID` int(11) NOT NULL,
  `CustomerUse` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `StoreID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(45) NOT NULL,
  `ProductPrice` int(3) NOT NULL,
  `ProductPicture` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`StoreID`, `ProductID`, `ProductName`, `ProductPrice`, `ProductPicture`) VALUES
(1, 1, 'test1', 400, '123'),
(2, 1, 'test2', 500, '345');

-- --------------------------------------------------------

--
-- Table structure for table `Question`
--

CREATE TABLE `Question` (
  `QuestionID` int(11) NOT NULL,
  `QuestionContent` varchar(1000) NOT NULL,
  `QuestionDateTime` datetime NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ShoppingRecord`
--

CREATE TABLE `ShoppingRecord` (
  `ShoppingRecordID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `ShoppingRecordDT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `StoreID` int(11) NOT NULL,
  `StoreName` varchar(45) NOT NULL,
  `StoreUsername` varchar(45) NOT NULL,
  `StorePassword` mediumtext NOT NULL,
  `StorePhone` varchar(15) NOT NULL,
  `StoreAddressCity` varchar(10) NOT NULL,
  `StoreAddressDistriction` varchar(10) NOT NULL,
  `StoreAddress` varchar(45) NOT NULL,
  `StoreStyle` varchar(10) DEFAULT NULL,
  `StoreFBName` varchar(10) DEFAULT NULL,
  `StoreFBAddrss` varchar(45) DEFAULT NULL,
  `StoreIGName` varchar(10) DEFAULT NULL,
  `StoreIGAddress` varchar(45) DEFAULT NULL,
  `StorePriceSection` varchar(45) DEFAULT NULL,
  `StoreRate` varchar(10) DEFAULT NULL,
  `StoreLineID` varchar(45) DEFAULT NULL,
  `StoreOffday` varchar(20) DEFAULT NULL,
  `StoreWorkingtime` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`StoreID`, `StoreName`, `StoreUsername`, `StorePassword`, `StorePhone`, `StoreAddressCity`, `StoreAddressDistriction`, `StoreAddress`, `StoreStyle`, `StoreFBName`, `StoreFBAddrss`, `StoreIGName`, `StoreIGAddress`, `StorePriceSection`, `StoreRate`, `StoreLineID`, `StoreOffday`, `StoreWorkingtime`) VALUES
(1, '[Neko+]貓加 Beauty', 'Beautycatplus', 'nekoplus', '0913392399', '台北市', '中正區', '羅斯褔路 三段210 巷8弄 7-13號', NULL, NULL, NULL, NULL, NULL, '800 ~1700', '5.0/30', 'tina10032000', NULL, NULL),
(2, 'MATnail', 'matnailadd', 'add0989', '0989222161', '台北市', '內湖區', '成功路三 段174巷 40號2樓', NULL, NULL, NULL, NULL, NULL, '1300up', '5.0/65', '@matnail', '星期日', '11:00-21:00'),
(3, '康玥', 'cunche123', '55287468', '(02)25117638', '台北市', '中山區', '民生西路 13號3樓', NULL, NULL, NULL, NULL, NULL, '1200up', NULL, NULL, '星期日', '11:00-20:00'),
(4, 'Skyline風采', 'skylinewind', 'windskysky', '(02)25317600', '台北市', '中山區', '中山北路二段39巷6-1號2樓', NULL, NULL, NULL, NULL, NULL, '1999up', '4.8/26', NULL, NULL, '11:00-21:00'),
(5, 'Modei salon', 'modelsalon2531', '08192531', '(02)25310819', '台北市', '中山區', '南京西路5-1號5樓', NULL, NULL, NULL, NULL, NULL, '1400up', '5.0/19', NULL, NULL, '10:30-20:00'),
(6, 'Millisin beauty room', 'millishbeautyroom000', 'beautybeauty0966', '0966872305', '台北市', '中山區', '松江路108巷3號2樓', NULL, NULL, NULL, NULL, NULL, '1380up', '4.9/46', '@lql2691u', '星期日', '11:30-20:30'),
(7, 'Ula studio', 'ulastudio0976', '0976studio', '0976925872', '台北市', '大安區', '市民大道四段68巷4號2樓', NULL, NULL, NULL, NULL, NULL, '1280up', '5.0/22', '@lfm1867m', '星期日', '24hr'),
(8, 'KlaraNails&Eyelash Salon', '', '', '(02)87727186', '台北市', '大安區', '大安路一段83巷7號1樓', NULL, NULL, NULL, NULL, NULL, '1500up', '4.9/98', '@klara', '星期日', '12:00-21:30'),
(9, 'F/A Taipei', 'fatapei2772', '27772tapei', '(02)27721992', '台北市', '大安區', '忠孝東路四段223巷34弄4號', NULL, NULL, NULL, NULL, NULL, '1380up', NULL, NULL, NULL, '10:00-21:30'),
(10, 'Pearl Nail', 'pearlnail2633', 'carfkelwklr23', '(02)26335359', '', '', '台北市內湖區成功路五段400號1樓', NULL, NULL, NULL, NULL, NULL, '1000up', '4.9/166', '@pearlnail', '星期一', '09:00-21:00'),
(11, '美緹藝術指甲', 'mayteanail', 'nail0930', '0930623055', '', '', '台北市中山區長春路137巷6號5樓', NULL, NULL, NULL, NULL, NULL, '1200up', NULL, NULL, NULL, '08:00-23:00'),
(12, 'Friends Beauty', 'friendbeauty2403', 'wqcpjniwgose', '0910172403', '', '', '台北市大安區麗水街7巷11號2樓之1', NULL, NULL, NULL, NULL, NULL, '650up(單色凝膠)', '5.0/29', '@ulu6765n', NULL, '11:00-21:00(星期日19:30)'),
(13, 'Littletiny nail.拇指公主美甲', 'littletiny', '0930323886', '0930323886', '', '', '台北市大安區羅斯福路三段297號3樓之1', NULL, NULL, NULL, NULL, NULL, '800up(單色凝膠) 1380up(藝術凝膠)', NULL, '@littletiny', NULL, '10:00-21:00'),
(14, '粉艾妮美甲沙龍', 'nn980525salon', '0983660231nn', '0983660231', '', '', '台北市內湖區內湖路一段737巷14號2樓', NULL, NULL, NULL, NULL, NULL, '500up', '4.7/58', '@nn980525', '星期日', '10:00-22:30'),
(15, 'CH Essence 艾美佳人', 'chessence', '9559essence', '(02)27929559', '', '', '台北市內湖區金湖路38號1樓', NULL, NULL, NULL, NULL, NULL, '1100up', '4.8/525', NULL, NULL, '11:00-21:00'),
(16, 'Katy凱媞美甲美睫沙龍教育中心', 'katynailsalon', 'teach2765', '(02)27655813', '', '', '台北市松山區八德路四段666號2樓', NULL, NULL, NULL, NULL, NULL, '999up(光療彩膠)', '5.0/43', '@0919655802', NULL, '11:30-21:00'),
(17, 'Fleur Nail&Eyelash Salon 日式專業美甲美睫沙龍', 'fleurnail8772', 'eyelashsalon6210', '(02)87726210', '', '', '台北市大安區敦化南路一段232巷6號1樓', NULL, NULL, NULL, NULL, NULL, '890up(透明光療)', '4.5/139', '@nqw9926v', NULL, '12:00-21:00'),
(18, '日安美甲', 'khxfoainejwefw', 'cebfhuegirs', '0982713355', '', '', '台北市大安區忠孝東路四段112號三樓之8  ', NULL, NULL, NULL, NULL, NULL, '1000up(透明凝膠)', '4.5/12', NULL, '星期日', '12:00-20:00'),
(19, 'Sister 24H時尚美學館', 'sister24fasion', 'sister2562', '(02)25622121', '', '', '台北市中山區林森北路306號2樓', NULL, NULL, NULL, NULL, NULL, '1000up', '4.8/196', NULL, NULL, '00:00-24:00'),
(20, '潔月兒藝術美甲', 'jeanail', 'chemlkr87717490', '(02)87717490', '', '', '台北市大安區忠孝東路四段177號4樓之5', NULL, NULL, NULL, NULL, NULL, '1300up', '4.7/169', NULL, NULL, '11:30-20:00'),
(21, 'Palette巴黎都', 'Palette2752', '8885Palette', '(02)27528885', '', '', '台北市大安區忠孝東路四段166號10樓之2', NULL, NULL, NULL, NULL, NULL, '880up', '4.8/26', NULL, NULL, '11:00-22:00(星期日20:00)'),
(22, 'It Nail 音特藝術美甲', 'itnail2773', 'nailart0608', '(02)27730608', '', '', '台北市大安區忠孝東路4段2號3樓之9', NULL, NULL, NULL, NULL, NULL, '800up', '4.9/108', '@gen5926w', NULL, '11:00-21:00'),
(23, '喬米時尚美學', 'chumifasion', 'mifa25625220', '(02)25625220', '', '', '台北市中山區長安東路一段53巷15號1樓', NULL, NULL, NULL, NULL, NULL, '600up(透明凝膠)', '4.9/75', NULL, NULL, '11:00-21:00'),
(24, '公爵夫人尊爵美甲沙龍', 'madon2778', 'nailmamdomo', '(02)27782705', '', '', '台北市大安區忠孝東路四段59號1樓', NULL, NULL, NULL, NULL, NULL, '1200up', '4.8/61', '@duchess.spa', NULL, '10:00-22:00'),
(25, 'El Amor 美甲沙龍', 'elamornailfasion', 'fasion8773', '(02)87736633', '', '', '台北市大安區敦化南路一段161巷45號', NULL, NULL, NULL, NULL, NULL, '1500up', '5.0/2712', '@elamor', NULL, '13:00-22:30'),
(26, 'Q原創時尚美甲美睫', 'qinitiativefasionnail', 'nailjsfiodja', '(02)27751710', '', '', '台北市大安區忠孝東路三段251巷10弄10號1樓', NULL, NULL, NULL, NULL, NULL, '1000up', '5.0/18', NULL, NULL, '12:00-21:00(星期日20:00)');

-- --------------------------------------------------------

--
-- Table structure for table `StoreComment`
--

CREATE TABLE `StoreComment` (
  `StoreID` int(11) NOT NULL,
  `StoreCommentID` int(11) NOT NULL,
  `StoreCommentScore` int(3) DEFAULT NULL,
  `StoreCommentContent` varchar(1000) DEFAULT NULL,
  `TransactionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `StoreComment`
--

INSERT INTO `StoreComment` (`StoreID`, `StoreCommentID`, `StoreCommentScore`, `StoreCommentContent`, `TransactionID`) VALUES
(15, 1, 11, '111', 1);

-- --------------------------------------------------------

--
-- Table structure for table `StoreMarketing`
--

CREATE TABLE `StoreMarketing` (
  `StoreID` int(11) NOT NULL,
  `StoreMarketingID` int(11) NOT NULL,
  `StoreMarketingCrateTime` datetime NOT NULL,
  `StoreMarketingBeginTime` datetime NOT NULL,
  `StoreMarketingEndTime` datetime NOT NULL,
  `StoreMarketingDM` varchar(64) DEFAULT NULL,
  `StoreMarketingContent` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `StoreMarketing`
--

INSERT INTO `StoreMarketing` (`StoreID`, `StoreMarketingID`, `StoreMarketingCrateTime`, `StoreMarketingBeginTime`, `StoreMarketingEndTime`, `StoreMarketingDM`, `StoreMarketingContent`) VALUES
(1, 1, '2018-01-01 10:21:21', '2018-01-03 11:00:00', '2018-01-09 23:00:00', NULL, '85折'),
(2, 2, '2018-01-08 10:00:00', '2018-01-09 10:00:00', '2018-01-24 00:00:00', NULL, '滿千折百');

-- --------------------------------------------------------

--
-- Table structure for table `Transaction`
--

CREATE TABLE `Transaction` (
  `TransactionID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `TransactionRegisterDate` datetime NOT NULL,
  `TransactionActualDate` datetime DEFAULT NULL,
  `TransactionYesOrNO` int(1) NOT NULL DEFAULT '0',
  `TransactionStyle` varchar(10) NOT NULL,
  `TransactionContent` varchar(100) DEFAULT NULL,
  `TransactionCancel` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Transaction`
--

INSERT INTO `Transaction` (`TransactionID`, `StoreID`, `CustomerID`, `TransactionRegisterDate`, `TransactionActualDate`, `TransactionYesOrNO`, `TransactionStyle`, `TransactionContent`, `TransactionCancel`) VALUES
(1, 15, 1, '2018-03-04 00:00:00', '2018-03-04 00:00:00', 1, '1', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Work`
--

CREATE TABLE `Work` (
  `StoreID` int(11) NOT NULL,
  `WorkID` int(11) NOT NULL,
  `WorkName` varchar(10) NOT NULL,
  `WorkPrice` varchar(10) NOT NULL,
  `WorkFilename` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Work`
--

INSERT INTO `Work` (`StoreID`, `WorkID`, `WorkName`, `WorkPrice`, `WorkFilename`) VALUES
(1, 1, 'red', '400', ''),
(1, 2, '彩虹', '590', ''),
(2, 3, '彩虹', '590', ''),
(3, 4, 'gray', '400', ''),
(4, 5, 'www', '500', ''),
(5, 6, 'deer', '780', ''),
(6, 7, 'art', '1000', ''),
(7, 8, 'bird', '700', ''),
(8, 9, 'bear', '880', ''),
(9, 10, 'high', '900', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answer`
--
ALTER TABLE `Answer`
  ADD PRIMARY KEY (`AnswerID`) USING BTREE,
  ADD KEY `answer_ibfk_1` (`QuestionID`);

--
-- Indexes for table `Chooseproduct`
--
ALTER TABLE `Chooseproduct`
  ADD PRIMARY KEY (`ShoppingRecordID`,`StoreID`,`ProductID`),
  ADD KEY `chooseproduct_ibfk_1` (`StoreID`),
  ADD KEY `chooseproduct_ibfk_3` (`ProductID`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `CustomerComment`
--
ALTER TABLE `CustomerComment`
  ADD PRIMARY KEY (`CustomerCommentID`),
  ADD KEY `TransactionID` (`TransactionID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `CustomerUse`
--
ALTER TABLE `CustomerUse`
  ADD PRIMARY KEY (`CustomerID`,`CustomerUse`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`ProductID`,`StoreID`),
  ADD KEY `StoreID` (`StoreID`);

--
-- Indexes for table `Question`
--
ALTER TABLE `Question`
  ADD PRIMARY KEY (`QuestionID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `ShoppingRecord`
--
ALTER TABLE `ShoppingRecord`
  ADD PRIMARY KEY (`ShoppingRecordID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`StoreID`);

--
-- Indexes for table `StoreComment`
--
ALTER TABLE `StoreComment`
  ADD PRIMARY KEY (`StoreCommentID`),
  ADD KEY `StoreID` (`StoreID`),
  ADD KEY `TransactionID` (`TransactionID`);

--
-- Indexes for table `StoreMarketing`
--
ALTER TABLE `StoreMarketing`
  ADD PRIMARY KEY (`StoreMarketingID`),
  ADD KEY `StoreID` (`StoreID`);

--
-- Indexes for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `StoreID` (`StoreID`);

--
-- Indexes for table `Work`
--
ALTER TABLE `Work`
  ADD PRIMARY KEY (`WorkID`),
  ADD KEY `StoreID` (`StoreID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Answer`
--
ALTER TABLE `Answer`
  MODIFY `AnswerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Customer`
--
ALTER TABLE `Customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `CustomerComment`
--
ALTER TABLE `CustomerComment`
  MODIFY `CustomerCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Question`
--
ALTER TABLE `Question`
  MODIFY `QuestionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ShoppingRecord`
--
ALTER TABLE `ShoppingRecord`
  MODIFY `ShoppingRecordID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `StoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `StoreComment`
--
ALTER TABLE `StoreComment`
  MODIFY `StoreCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `StoreMarketing`
--
ALTER TABLE `StoreMarketing`
  MODIFY `StoreMarketingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Transaction`
--
ALTER TABLE `Transaction`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Work`
--
ALTER TABLE `Work`
  MODIFY `WorkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answer`
--
ALTER TABLE `Answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`QuestionID`) REFERENCES `Question` (`QuestionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Chooseproduct`
--
ALTER TABLE `Chooseproduct`
  ADD CONSTRAINT `chooseproduct_ibfk_1` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chooseproduct_ibfk_2` FOREIGN KEY (`ShoppingRecordID`) REFERENCES `ShoppingRecord` (`ShoppingRecordID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chooseproduct_ibfk_3` FOREIGN KEY (`ProductID`) REFERENCES `Product` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `CustomerComment`
--
ALTER TABLE `CustomerComment`
  ADD CONSTRAINT `customercomment_ibfk_1` FOREIGN KEY (`TransactionID`) REFERENCES `Transaction` (`TransactionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customercomment_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `CustomerUse`
--
ALTER TABLE `CustomerUse`
  ADD CONSTRAINT `customeruse_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Question`
--
ALTER TABLE `Question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ShoppingRecord`
--
ALTER TABLE `ShoppingRecord`
  ADD CONSTRAINT `shoppingrecord_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `StoreComment`
--
ALTER TABLE `StoreComment`
  ADD CONSTRAINT `storecomment_ibfk_1` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `storecomment_ibfk_2` FOREIGN KEY (`TransactionID`) REFERENCES `Transaction` (`TransactionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `StoreMarketing`
--
ALTER TABLE `StoreMarketing`
  ADD CONSTRAINT `storemarketing_ibfk_1` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Work`
--
ALTER TABLE `Work`
  ADD CONSTRAINT `work_ibfk_1` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
