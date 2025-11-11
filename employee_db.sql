-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 08:50 AM
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
-- Database: `employee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `employeedetail`
--

CREATE TABLE `employeedetail` (
  `ID` int(11) NOT NULL,
  `EmpFirstName` varchar(150) DEFAULT NULL,
  `EmpLastName` varchar(150) DEFAULT NULL,
  `EmpEmail` varchar(200) NOT NULL,
  `EmpPassword` varchar(255) NOT NULL,
  `NTMCode` varchar(50) DEFAULT NULL,
  `JoiningDate` date DEFAULT NULL,
  `EmpSalary` decimal(10,2) DEFAULT NULL,
  `EmpDesignation` varchar(150) DEFAULT NULL,
  `TotalLeaves` int(11) NOT NULL DEFAULT 0,
  `EmpExperience` varchar(100) DEFAULT NULL,
  `RegistrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `ProfilePhoto` varchar(255) DEFAULT 'default_avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeedetail`
--

INSERT INTO `employeedetail` (`ID`, `EmpFirstName`, `EmpLastName`, `EmpEmail`, `EmpPassword`, `NTMCode`, `JoiningDate`, `EmpSalary`, `EmpDesignation`, `TotalLeaves`, `EmpExperience`, `RegistrationDate`, `reset_token`, `token_expiry`, `ProfilePhoto`) VALUES
(15, 'Chirag', 'Gohil', 'chiraggohil22@gmail.com', '11223344', '101', '2022-07-15', 850000.00, 'Sr. Software Developer', 0, '5', '2025-09-30 15:38:34', NULL, NULL, 'profile_15_1759248801.jpg'),
(16, 'Rohan', 'Patel', 'rohanpatel23@gmail.com', '11223344', '102', '2023-02-01', 450000.00, 'Jr. Software Developer', 0, '2', '2025-09-30 15:40:18', NULL, NULL, 'profile_16_1759248775.jpeg'),
(17, 'Vraj', 'Patel', 'vrajpatel@gmail.com', '11223344', '103', '2021-08-07', 1200000.00, 'Project Manager', 0, '8', '2025-09-30 15:41:48', NULL, NULL, 'profile_17_1759248727.jpg'),
(18, 'Raj', 'Parmar', 'rajparmar2203@gmail.com', '11223344', '104', '2023-06-09', 350000.00, 'Project Manager', 0, '2', '2025-09-30 15:43:41', NULL, NULL, 'profile_18_1759248696.jpg'),
(19, 'Tanish', 'Patel', 'tanishpatel@gmail.com', '11223344', '105', '2022-02-09', 2000000.00, 'UI/UX Designer', 0, '3', '2025-09-30 15:45:25', NULL, NULL, 'profile_19_1759248657.jpg'),
(20, 'Bhakti', 'Patel', 'bhaktipatel21@gmail.com', '11223344', '106', '2024-06-11', 2500000.00, 'HR Manager', 0, '1', '2025-09-30 15:47:16', NULL, NULL, 'profile_20_1759248617.jpg'),
(21, 'Moksha', 'Patel', 'mokshapatel@gmail.com', '11223344', '107', '2022-06-08', 2000000.00, 'Quality Analyst', 0, '3', '2025-09-30 15:50:20', NULL, NULL, 'profile_21_1759248582.jpg'),
(22, 'Hetakshi', 'Parmar', 'hetakshi2202@gmail.com', '11223344', '108', '2024-05-03', 10000.00, 'Marketing Executive', 0, '1', '2025-09-30 15:51:29', NULL, NULL, 'profile_22_1759248524.jpg'),
(24, 'Sakshi', 'Patel', 'sakshipatel@gmail.com', '11223344', '108', '2021-06-19', 10000.00, 'Graphic Designer', 0, '4', '2025-09-30 15:57:43', NULL, NULL, 'profile_24_1759248491.png'),
(25, 'Rutu', 'Patel', 'rutupatel@gmail.com', '11223344', '109', '2023-08-12', 2000000.00, 'Team Lead', 0, '2', '2025-09-30 16:01:24', NULL, NULL, 'profile_25_1759248877.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `ID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `LeaveReason` text NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`ID`, `EmployeeID`, `LeaveReason`, `StartDate`, `EndDate`, `Status`) VALUES
(18, 15, 'Sickness', '2025-11-15', '2025-11-16', 'Rejected'),
(19, 16, 'Doctor\'s Appointment', '2025-11-05', '2025-11-07', 'Approved'),
(20, 15, 'Family Medical Emergency', '2025-11-13', '2025-11-15', 'Approved'),
(21, 17, 'Family Member\'s Sickness', '2025-11-18', '2025-11-19', 'Rejected'),
(22, 17, 'Personal Work', '2025-12-10', '2025-11-15', 'Approved'),
(23, 21, 'Planned Vacation', '2025-12-14', '2025-12-21', 'Rejected'),
(24, 18, 'Mental Health Day', '2025-11-25', '2025-11-27', 'Rejected'),
(25, 19, 'Family Function/Event', '2026-01-01', '2026-01-08', 'Approved'),
(26, 20, 'Home Emergency', '2025-11-26', '2025-11-28', 'Approved'),
(27, 22, 'Vehicle Breakdown/Transport Issue ', '2025-12-11', '2025-12-12', 'Rejected'),
(28, 24, 'Sad Demise', '2025-12-09', '2025-12-20', 'Approved'),
(29, 25, 'Child\'s School Event', '2026-02-11', '2026-02-12', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `ID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `TaskTitle` varchar(255) NOT NULL,
  `TaskDescription` text DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `Status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`ID`, `EmployeeID`, `TaskTitle`, `TaskDescription`, `DueDate`, `Status`) VALUES
(16, 15, 'New Feature Module Design', 'Naye \'Payment Gateway\' module ke liye system architecture design karei…', '2025-11-08', 'Completed'),
(17, 15, 'Technology Evaluation', 'Project ki performance badhane ke liye \'Redis\' vs \'Memcached\' ki tulna karein. Ek short report (Proof of Concept - POC) taiyaar karein ki hamare use case ke liye kaun sa behtar hai', '2025-11-29', 'In Progress'),
(18, 15, 'System Scalability Audit', 'Hamare current application ki scalability bottlenecks', '2025-11-10', 'In Progress'),
(19, 16, 'Database Schema Refactoring Plan', 'Maujooda database schema mein performance issues ko analyze karein aur data migration plan ke saath ek naya, optimized schema design karein.', '2025-11-28', 'Completed'),
(20, 17, 'Microservice Communication Strategy', '\'User Service\' aur \'Order Service\' ke beech data sync ke liye (e.g., RabbitMQ ya Kafka) ek message queue system design aur implement karne ka plan banayein.', '2025-11-30', 'Pending'),
(21, 17, 'Critical Bug Fix', 'Production environment mein aa rahi \'User Session Timeout\' ki critical bug ko debug karein aur fix karein.', '2025-12-05', 'In Progress'),
(22, 21, 'API Performance Optimization', '/api/v1/products endpoint jo abhi 2 second le raha hai, uski performance optimize karein taaki response time 200ms se kam ho jaaye', '2025-11-21', 'In Progress'),
(23, 18, 'Security Vulnerability Patch', 'Application mein \'SQL Injection\' vulnerability ke liye code ko audit karein aur usse fix karne ke liye prepared statements ya ORM ka istemaal karein.', '2025-11-28', 'Completed'),
(24, 19, 'User Onboarding Flow', 'Naye users ke liye complete registration aur welcome process (onboarding) ko design karein. Isme \'Sign Up\' screen se lekar \'Main Dashboard\' tak ke har step ka low-fidelity wireframe (Figma ya Balsamiq mein) banayein. Focus user ko aasani se app shuru karwane par hona chahiye.', '2025-11-30', 'Pending'),
(25, 19, 'Checkout Page Usability Audit & Redesign', 'Hamari website ke current \'Checkout\' page ko analyze karein. User ko payment karne mein aa rahi 3 badi problems (friction points) ko pehchanein. Un problems ko solve karne ke liye usi page ka ek improved version (redesign mockup) banayein.', '2025-12-05', 'Pending'),
(26, 20, 'Employee Engagement Survey & Action Plan', 'Ek anonymous (gumnaam) employee satisfaction survey banayein aur conduct karein. Survey se prapt data ko analyze karein aur 3 mukhya kshetron (areas) ki pehchaan karein jahaan sudhaar ki zaroorat hai. Agle quarter ke liye in sudhaaron par ek action plan taiyaar karein.', '2025-12-27', 'In Progress'),
(27, 20, 'Hiring Process Optimization', '\'Software Developer\' role ke liye maujooda (current) hiring process ka audit karein. Bharti mein lagne waale samay (time-to-hire) ko 20% tak kam karne ka plan banayein. Isme job description ko behtar banana, interview rounds ko streamline karna aur managers ke liye ek standard feedback form banana shaamil hai.', '2025-12-24', 'Completed'),
(28, 22, 'Competitor Analysis Report', 'Hamare 3 main competitors (pratiyogiyon) ke is mahine ki marketing activities (jaise naye offers, social media campaigns, ya ads) ko analyze karein. Ek short report banayein ki woh kya achha kar rahe hain aur hum kya seekh sakte hain.', '2025-11-25', 'Pending'),
(29, 22, 'Weekly Social Media Calendar', 'Agle 7 dinon ke liye hamare Instagram aur Facebook page ke liye ek content calendar plan karein. Isme shaamil ho: 5 posts ke liye captions, 3 story ideas, aur har post ke liye istemaal hone waale hashtags (#).', '2026-01-16', 'In Progress'),
(30, 24, 'Social Media Creatives', 'Marketing Executive dwara banaye gaye social media calendar ke liye 5 post images (1080x1080 pixels) aur 3 Instagram stories (1080x1920 pixels) design karein. Design company ki brand guidelines ke anusaar hona chahiye', '2025-11-22', 'In Progress'),
(31, 25, 'Weekly Sprint Planning & Task Delegation', 'Agle hafte ke project goals ko define karein. Badi tasks ko chhote, manageable sub-tasks mein todein aur unhein team members ko unki strengths (kshamata) aur workload ke aadhaar par assign (delegate) karein.', '2025-12-24', 'In Progress'),
(32, 25, 'Team Performance Review & Blocker Removal', 'Har team member ke saath 1-on-1 meeting karein. Unke current progress ko review karein, unhein unke kaam mein aa rahi kisi bhi rukawat (blockers) ko pehchaanein aur unhein door karne mein madad karein.', '2026-01-22', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(11) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `Password`, `AdminEmail`, `reset_token`, `token_expiry`) VALUES
(1, 'Admin', '12345', 'admin@example.com', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employeedetail`
--
ALTER TABLE `employeedetail`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EmpEmail` (`EmpEmail`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employeedetail`
--
ALTER TABLE `employeedetail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeedetail` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employeedetail` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
