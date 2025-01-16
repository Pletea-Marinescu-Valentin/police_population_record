-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 05:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `policepopulationrecord`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `Address_ID` int(11) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `Number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`Address_ID`, `City`, `Street`, `Number`) VALUES
(7, 'Austin', 'Congress Avenue', '150'),
(3, 'Chicago', 'Michigan Avenue', '300'),
(5, 'Dallas', 'Main Street', '45'),
(9, 'Houston', 'Broadway', '99'),
(2, 'Los Angeles', 'Sunset Boulevard', '200'),
(4, 'Miami', 'Ocean Drive', '10'),
(1, 'New York', '5th Avenue', '100'),
(10, 'Phoenix', 'Camelback Road', '180'),
(8, 'San Francisco', 'Market Street', '20'),
(6, 'Seattle', 'Pine Street', '70');

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE `citizens` (
  `Citizen_ID` int(11) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `SSN` char(13) NOT NULL,
  `Birth_Date` date NOT NULL,
  `Address_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`Citizen_ID`, `Last_Name`, `First_Name`, `SSN`, `Birth_Date`, `Address_ID`) VALUES
(1, 'Smith', 'John', '123456789', '1980-01-15', 1),
(2, 'Doe', 'Jane', '987654321', '1990-03-25', 2),
(3, 'Johnson', 'Emily', '123987456', '2000-07-10', 3),
(4, 'Brown', 'Michael', '456123789', '1975-11-20', 4),
(5, 'Davis', 'Jessica', '321654987', '1985-08-30', 5),
(6, 'Taylor', 'Christopher', '111111111', '1995-04-18', 6),
(7, 'Wilson', 'Jennifer', '222222222', '1988-07-12', 7),
(8, 'Thomas', 'Matthew', '333333333', '1983-09-09', 8),
(9, 'White', 'Ashley', '444444444', '1997-05-25', 9),
(10, 'Lee', 'Daniel', '555555555', '1992-06-17', 10),
(11, 'Evans', 'Chris', '666666666', '1990-08-23', 1),
(12, 'Anderson', 'Laura', '777777777', '1987-04-12', 2),
(13, 'Martinez', 'Carlos', '888888888', '1992-02-28', 3),
(14, 'Clark', 'Sarah', '999999999', '1985-09-15', 4),
(15, 'Harris', 'Thomas', '111222333', '1998-11-11', 5);

-- --------------------------------------------------------

--
-- Table structure for table `citizens_crimes`
--

CREATE TABLE `citizens_crimes` (
  `Citizen_ID` int(11) NOT NULL,
  `Crime_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citizens_crimes`
--

INSERT INTO `citizens_crimes` (`Citizen_ID`, `Crime_ID`) VALUES
(1, 1),
(1, 5),
(2, 2),
(2, 6),
(3, 3),
(3, 7),
(4, 4),
(4, 8),
(5, 5),
(5, 9),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `crimes`
--

CREATE TABLE `crimes` (
  `Crime_ID` int(11) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `Crime_Date` date NOT NULL,
  `Citizen_ID` int(11) DEFAULT NULL,
  `Officer_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`Crime_ID`, `Description`, `Crime_Date`, `Citizen_ID`, `Officer_ID`) VALUES
(1, 'Theft', '2022-01-10', 1, 1),
(2, 'Assault', '2022-02-15', 2, 2),
(3, 'Fraud', '2022-03-05', 3, 3),
(4, 'Vandalism', '2022-04-20', 4, 4),
(5, 'Burglary', '2022-05-15', 5, 5),
(6, 'Arson', '2022-06-25', 6, 1),
(7, 'Homicide', '2022-07-10', 7, 2),
(8, 'Robbery', '2022-08-30', 8, 3),
(9, 'Drug Trafficking', '2022-09-10', 9, 4),
(10, 'Kidnapping', '2022-10-20', 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `driving_licenses`
--

CREATE TABLE `driving_licenses` (
  `License_ID` int(11) NOT NULL,
  `Category` varchar(2) NOT NULL,
  `Issue_Date` date NOT NULL,
  `Expiry_Date` date NOT NULL,
  `Citizen_ID` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `driving_licenses`
--

INSERT INTO `driving_licenses` (`License_ID`, `Category`, `Issue_Date`, `Expiry_Date`, `Citizen_ID`) VALUES
(1, 'B', '2010-01-01', '2030-01-01', 1),
(2, 'A', '2015-06-15', '2035-06-15', 2),
(3, 'C', '2008-03-20', '2028-03-20', 3),
(4, 'D', '2012-07-10', '2032-07-10', 4),
(5, 'B', '2018-11-25', '2028-11-25', 5),
(6, 'B', '2011-02-01', '2031-02-01', 6),
(7, 'C', '2014-09-12', '2034-09-12', 7),
(8, 'A', '2009-06-20', '2029-06-20', 8),
(9, 'D', '2016-03-10', '2036-03-10', 9),
(10, 'B', '2013-11-30', '2033-11-30', 10);

-- --------------------------------------------------------

--
-- Table structure for table `identity_documents`
--

CREATE TABLE `identity_documents` (
  `Document_ID` int(11) NOT NULL,
  `Series` varchar(2) NOT NULL,
  `Number` varchar(6) NOT NULL,
  `Citizen_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `identity_documents`
--

INSERT INTO `identity_documents` (`Document_ID`, `Series`, `Number`, `Citizen_ID`) VALUES
(1, 'AB', '100001', 1),
(2, 'AB', '100002', 2),
(3, 'AB', '100003', 3),
(4, 'AB', '100004', 4),
(5, 'AB', '100005', 5),
(6, 'AB', '100006', 6),
(7, 'AB', '100007', 7),
(8, 'AB', '100008', 8),
(9, 'AB', '100009', 9),
(10, 'AB', '100010', 10);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `Officer_ID` int(11) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Rank` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`Officer_ID`, `Last_Name`, `First_Name`, `Rank`) VALUES
(1, 'Smith', 'Robert', 'Inspector'),
(2, 'Johnson', 'Linda', 'Sergeant'),
(3, 'Brown', 'William', 'Lieutenant'),
(4, 'Taylor', 'Elizabeth', 'Captain'),
(5, 'Davis', 'James', 'Chief');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Officer_ID` int(11) DEFAULT NULL,
  `Citizen_ID` int(11) DEFAULT NULL,
  `Role` enum('Admin','Officer','User') DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Username`, `Password`, `Created_At`, `Officer_ID`, `Citizen_ID`, `Role`) VALUES
(1, 'admin', '$2y$10$EG8Ou6JqviwI8KlgHvfRxetL0cO/Ea9CpGedwgnEO7SM7Bd5RnICS', '2025-01-10 18:13:11', NULL, NULL, 'Admin'),
(2, 'officer1', '$2y$10$Ag8Duvply3PmQ5SDhtHxD.DRHHzMali/ri/IN1Xqkp1GjdeFQJe4G', '2025-01-10 18:13:11', NULL, NULL, 'Officer'),
(3, 'user1', '$2y$10$fXGhnC7EDtjIboltWU3dJ./zW1MURoEBJ0Cd0ZNWDNrIM8UYpGeF2', '2025-01-10 18:13:11', NULL, NULL, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`Address_ID`),
  ADD UNIQUE KEY `UQ_Address` (`City`,`Street`,`Number`);

--
-- Indexes for table `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`Citizen_ID`),
  ADD UNIQUE KEY `SSN` (`SSN`),
  ADD KEY `Address_ID` (`Address_ID`);

--
-- Indexes for table `citizens_crimes`
--
ALTER TABLE `citizens_crimes`
  ADD PRIMARY KEY (`Citizen_ID`,`Crime_ID`),
  ADD KEY `Crime_ID` (`Crime_ID`);

--
-- Indexes for table `crimes`
--
ALTER TABLE `crimes`
  ADD PRIMARY KEY (`Crime_ID`),
  ADD KEY `Citizen_ID` (`Citizen_ID`),
  ADD KEY `Officer_ID` (`Officer_ID`);

--
-- Indexes for table `driving_licenses`
--
ALTER TABLE `driving_licenses`
  ADD PRIMARY KEY (`License_ID`),
  ADD UNIQUE KEY `Citizen_ID` (`Citizen_ID`);

--
-- Indexes for table `identity_documents`
--
ALTER TABLE `identity_documents`
  ADD PRIMARY KEY (`Document_ID`),
  ADD UNIQUE KEY `Citizen_ID` (`Citizen_ID`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`Officer_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `Officer_ID` (`Officer_ID`),
  ADD KEY `Citizen_ID` (`Citizen_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `Address_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `citizens`
--
ALTER TABLE `citizens`
  MODIFY `Citizen_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `crimes`
--
ALTER TABLE `crimes`
  MODIFY `Crime_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `driving_licenses`
--
ALTER TABLE `driving_licenses`
  MODIFY `License_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identity_documents`
--
ALTER TABLE `identity_documents`
  MODIFY `Document_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `Officer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `citizens`
--
ALTER TABLE `citizens`
  ADD CONSTRAINT `citizens_ibfk_1` FOREIGN KEY (`Address_ID`) REFERENCES `addresses` (`Address_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `citizens_crimes`
--
ALTER TABLE `citizens_crimes`
  ADD CONSTRAINT `citizens_crimes_ibfk_1` FOREIGN KEY (`Citizen_ID`) REFERENCES `citizens` (`Citizen_ID`),
  ADD CONSTRAINT `citizens_crimes_ibfk_2` FOREIGN KEY (`Crime_ID`) REFERENCES `crimes` (`Crime_ID`);

--
-- Constraints for table `crimes`
--
ALTER TABLE `crimes`
  ADD CONSTRAINT `crimes_ibfk_1` FOREIGN KEY (`Citizen_ID`) REFERENCES `citizens` (`Citizen_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crimes_ibfk_2` FOREIGN KEY (`Officer_ID`) REFERENCES `officers` (`Officer_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `driving_licenses`
--
ALTER TABLE `driving_licenses`
  ADD CONSTRAINT `driving_licenses_ibfk_1` FOREIGN KEY (`Citizen_ID`) REFERENCES `citizens` (`Citizen_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `identity_documents`
--
ALTER TABLE `identity_documents`
  ADD CONSTRAINT `identity_documents_ibfk_1` FOREIGN KEY (`Citizen_ID`) REFERENCES `citizens` (`Citizen_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Officer_ID`) REFERENCES `officers` (`Officer_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`Citizen_ID`) REFERENCES `citizens` (`Citizen_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
