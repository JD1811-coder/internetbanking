-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 11:06 AM
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
-- Database: `internetbanking`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_feedback`
--

CREATE TABLE `client_feedback` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `feedback_message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_feedback`
--

INSERT INTO `client_feedback` (`id`, `client_id`, `subject`, `feedback_message`, `submission_date`, `reply`) VALUES
(6, 8, 'Complaint Regarding Debit Card Issue', 'Dear Customer Support Team,\n\nI am facing an issue with my debit card ending in 2895. Recently, I attempted to use it for an online transaction, but it was declined despite having sufficient balance. Additionally, I tried withdrawing cash from an ATM, but the transaction failed, and the amount was deducted from my account.\n\nI kindly request you to investigate this issue and process a refund for the deducted amount. Please let me know if any further details are required. Your prompt assistance in resolving this matter would be greatly appreciated.\n\nLooking forward to your response.', '2025-04-14 09:00:55', 'sorry for inconvinecing'),
(7, 11, 'Issue with Fund Transfer', 'I tried to transfer funds to another account, but the transaction failed.', '2025-03-24 05:30:38', 'We apologize for the inconvenience. Please check if the recipient details are correct and try again.'),
(8, 11, 'ertyu', 'erftgyhuj', '2025-04-01 06:55:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ib_acc_types`
--

CREATE TABLE `ib_acc_types` (
  `acctype_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `rate` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `min_balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_acc_types`
--

INSERT INTO `ib_acc_types` (`acctype_id`, `name`, `description`, `rate`, `code`, `is_active`, `min_balance`) VALUES
(1, 'Savings Account', '<p>Savings accounts&nbsp;are typically the first official bank account anybody opens. Children may open an account with a parent to begin a pattern of saving. Teenagers open accounts to stash cash earned&nbsp;from a first job&nbsp;or household chores.</p><p>Savings accounts are an excellent place to park&nbsp;emergency cash. Opening a savings account also marks the beginning of your relationship with a financial institution. For example, when joining a credit union, your &ldquo;share&rdquo; or savings account establishes your membership.</p>', '7', 'ACC-CAT-4EZFO', 1, 1000.00),
(2, ' Retirement Account', '<p>Retirement accounts&nbsp;offer&nbsp;tax advantages. In very general terms, you get to&nbsp;avoid paying income tax on interest&nbsp;you earn from a savings account or CD each year. But you may have to pay taxes on those earnings at a later date. Still, keeping your money sheltered from taxes may help you over the long term. Most banks offer IRAs (both&nbsp;Traditional IRAs&nbsp;and&nbsp;Roth IRAs), and they may also provide&nbsp;retirement accounts for small businesses</p>', '10', 'ACC-CAT-1QYDV', 1, 0.00),
(4, 'Recurring deposit Account', '<p><strong>Recurring deposit account or RD account</strong> is opened by those who want to save certain amount of money regularly for a certain period of time and earn a higher interest rate.&nbsp;In RD&nbsp;account a&nbsp;fixed amount is deposited&nbsp;every month for a specified period and the total amount is repaid with interest at the end of the particular fixed period.&nbsp;</p><p>The period of deposit is minimum six months and maximum ten years.&nbsp;The interest rates vary&nbsp;for different plans based on the amount one saves and the period of time and also on banks. No withdrawals are allowed from the RD account. However, the bank may allow to close the account before the maturity period.</p><p>These accounts can be opened in single or joint names. Banks are also providing the Nomination facility to the RD account holders.&nbsp;</p>', '11', 'ACC-CAT-VBQLE', 1, 6000.00),
(5, 'Fixed Deposit Account', '<p>In <strong>Fixed Deposit Account</strong> (also known as <strong>FD Account</strong>), a particular sum of money is deposited in a bank for specific&nbsp;period of time. It&rsquo;s one time deposit and one time take away (withdraw) account.&nbsp;The money deposited in this account can not be withdrawn before the expiry of period.&nbsp;</p><p>However, in case of need,&nbsp; the depositor can ask for closing the fixed deposit prematurely by paying a penalty. The penalty amount varies with banks.</p><p>A high interest rate is paid on fixed deposits. The rate of interest paid for fixed deposit vary according to amount, period and also from bank to bank.</p>', '22', 'ACC-CAT-A86GO', 1, 10000.00),
(7, 'Current account', '<p><strong>Current account</strong> is mainly for business per<strong>s</strong>ons, firms, companies, public enterprises etc and are never used for the purpose of investment or savings.These deposits are the most liquid deposits and there are no limits for number of transactions or the amount of transactions in a day. While, there is no interest paid on amount held in the account, banks charges certain &nbsp;service charges, on such accounts. The current accounts do not have any fixed maturity as thegadegagagase are on continuous basis accounts.</p>', '0', 'ACC-CAT-4O8QW', 1, 5000.00),
(8, 'Salary Account', '<p>A <strong>salary account</strong> is a bank account where an employer directly deposits an employee&rsquo;s salary. It usually has <strong>zero balance requirements</strong>, <strong>free debit card and chequebook</strong>, <strong>higher transaction limits</strong>, <strong>overdraft facility</strong>, and <strong>easy loan approvals</strong>. It also provides <strong>internet and mobile banking</strong> for seamless transactions.</p>', '6.5', 'ACC-CAT-27DQV', 1, 0.00),
(9, 'Minor Account', '<p>A Minor Account is a special type of bank account designed for individuals below the legal adult age, typically managed by a parent or guardian. These accounts help minors develop financial literacy and savings habits from an early age. While the account is in the minor&rsquo;s name, the guardian has control over transactions, withdrawals, and fund management until the minor reaches adulthood. Many banks offer minor accounts with benefits like zero minimum balance, higher interest rates, and restrictions on certain transactions to ensure financial safety. Upon reaching the legal age, the minor can convert the account into a regular savings account.</p>', '3.5', 'ACC-CAT-UBY52', 1, 500.00),
(11, 'Joint Account', '<p>A Joint Account is a shared bank account owned by two or more individuals, allowing them to manage and access funds collectively. It is commonly used by spouses, business partners, family members, or anyone who wishes to share financial responsibilities. This type of account offers convenience for handling shared expenses such as household bills, savings, or business transactions. Depending on the account terms, withdrawals and transactions may require authorization from one or all account holders. A joint account promotes financial transparency and trust while providing an efficient way to manage funds together.</p>', '3.5', 'ACC-CAT-8IQVP', 1, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `ib_admin`
--

CREATE TABLE `ib_admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_admin`
--

INSERT INTO `ib_admin` (`admin_id`, `name`, `email`, `number`, `password`, `profile_pic`, `is_active`, `otp`, `otp_expiry`) VALUES
(2, 'System Administrator', 'dholajenil2024.katargam@gmail.com', 'iBank-ADM-0516', '$2y$10$mEBPVimHrR243fyzxkg2M.Icpuru6ITL3vCZeL8vGjvcgx7G6WH5S', 'admin-icn.png', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ib_bankaccounts`
--

CREATE TABLE `ib_bankaccounts` (
  `account_id` int(20) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type_id` int(11) DEFAULT NULL,
  `acc_amount` decimal(10,2) NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_bankaccounts`
--

INSERT INTO `ib_bankaccounts` (`account_id`, `acc_name`, `account_number`, `acc_type_id`, `acc_amount`, `client_id`, `created_at`, `is_active`) VALUES
(15, 'Arin Gabani', '287359614', 7, 1417600.93, 8, '2025-04-08 07:51:09.032375', 1),
(16, 'Harshit Rana', '705239816', 1, 124057.31, 6, '2025-04-08 15:11:56.060858', 1),
(23, 'Jenil Dhola', '573608192', 4, 269896.83, 11, '2025-06-08 15:52:17.242555', 1),
(35, 'Sahil Gohil', '964031285', 9, 406857.39, 15, '2025-04-05 16:07:08.370035', 1);

--
-- Triggers `ib_bankaccounts`
--
DELIMITER $$
CREATE TRIGGER `update_bank_balance` AFTER UPDATE ON `ib_bankaccounts` FOR EACH ROW BEGIN
    UPDATE ib_bank_main_account
    SET total_balance = (200000000 + (SELECT IFNULL(SUM(acc_amount), 0) FROM ib_bankaccounts))
    WHERE id = 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ib_bank_main_account`
--

CREATE TABLE `ib_bank_main_account` (
  `id` int(11) NOT NULL,
  `account_name` varchar(255) NOT NULL DEFAULT 'Bank Main Account',
  `account_number` varchar(20) NOT NULL DEFAULT '999999999',
  `total_balance` decimal(15,2) NOT NULL DEFAULT 200000000.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ib_bank_main_account`
--

INSERT INTO `ib_bank_main_account` (`id`, `account_name`, `account_number`, `total_balance`, `created_at`) VALUES
(1, 'Bank Main Account', '999999999', 202218412.46, '2025-04-03 12:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `ib_clients`
--

CREATE TABLE `ib_clients` (
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `aadhar_number` varchar(12) NOT NULL,
  `pan_number` varchar(10) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `otp` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_clients`
--

INSERT INTO `ib_clients` (`client_id`, `name`, `phone`, `address`, `email`, `aadhar_number`, `pan_number`, `password`, `profile_pic`, `client_number`, `is_active`, `otp`, `otp_expiry`) VALUES
(6, 'Harshit Rana', '7412545454', '23 Hinkle Deegan Lake Road', 'HarshitR34@gmail.com', '489954692767', 'YFGOZ3386S', '55c3b5386c486feb662a0785f340938f518d547f', 'download.jpg', 'iBank-CLIENT-1698', 1, NULL, NULL),
(8, 'arin gabani', '8799050118', 'apple complex, jahangirura,surat', 'jenildhola1811@gmail.com', '234567890355', 'IHXPD1193P', '$2y$10$UAVdv8DrRbL4LrXWZ4nlK.wzJmOEb/WSLl6tnrBEW6NU7KqA2PhGO', '1744621056_arin.jpg', 'iBank-CLIENT-0423', 1, '552614', '2025-04-14 14:35:30'),
(11, 'Jenil Dhola', '7412545454', 'A-2/203,DEVI COMPLEX,DABHOLI CHAR RASTA', 'shreeji.gamer.bot@gmail.com', '987698769876', 'PAXEQ2346Q', '$2y$10$6yxMhsckua3wiAT2cx3rzuTM6Uhr54Safv0nkS/xDymtFPdFDXMkq', '1743489329_1744698229_1742010849_1749620958_JD1.jpeg', 'iBank-CLIENT-2438', 1, '333165', '2025-02-28 13:39:16'),
(15, 'Sahil Gohil', '6352419645', 'A-103, Riivanta Riverview, Variyav, Surat', 'cleintss2023@gmail.com', '876587658765', 'OPKFW7221P', '$2y$10$tnXEHcPfGDA/hDMbeQd/Z.CFd5AlFASauXVLC7BN7ImCIbogDP/BS', '1741630492_sahil.jpg', 'iBank-CLIENT-0458', 1, '625736', '2025-03-02 22:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `ib_nominees`
--

CREATE TABLE `ib_nominees` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `nominee_name` varchar(255) NOT NULL,
  `relation` varchar(100) NOT NULL,
  `nominee_email` varchar(255) DEFAULT NULL,
  `nominee_phone` varchar(20) DEFAULT NULL,
  `nominee_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `aadhar_number` varchar(12) NOT NULL,
  `pan_number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ib_nominees`
--

INSERT INTO `ib_nominees` (`id`, `client_id`, `nominee_name`, `relation`, `nominee_email`, `nominee_phone`, `nominee_address`, `created_at`, `is_active`, `aadhar_number`, `pan_number`) VALUES
(1, 11, 'Bhavnaben Dhola', 'Mother', 'jenildhola1811@gmail.com', '99025063124', 'A-2/203,DEVI COMPLEX,DABHOLI CHAR RASTA', '2025-02-20 06:29:08', 1, '785496325896', 'IHXOD1193P'),
(6, 11, 'dineshbhai dhola', 'Father', 'jenildhola1811@gmail.com', '8799050118', 'A-2/203,DEVI COMPLEX,DABHOLI CHAR RASTA', '2025-02-28 17:00:36', 1, '978546321574', 'OPKFW7221Z'),
(9, 15, 'dilipbhai gohil', 'Father', 'Dilip123@gmail.com', '7689567865', 'Parth Complex, Dhanmora,Surat', '2025-03-10 14:06:27', 1, '876563768452', 'VDIKA7345S'),
(10, 18, 'jnhybgtvfrcd', 'HBGVFCD', 'jenildhola1811@gmail.com', '8799050118', 'A-2/203,DEVI COMPLEX,DABHOLI CHAR RASTA', '2025-05-11 05:19:03', 1, '998756254234', 'FDGYK1234R'),
(11, 18, 'KDHFUD', 'GFXSY', 'kirtanmoradiya27@gmail.com', '9979735065', 'not', '2025-05-11 05:19:36', 1, '798765745653', 'BNVJU1234K');

-- --------------------------------------------------------

--
-- Table structure for table `ib_notifications`
--

CREATE TABLE `ib_notifications` (
  `notification_id` int(20) NOT NULL,
  `notification_details` text NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_notifications`
--

INSERT INTO `ib_notifications` (`notification_id`, `notification_details`, `created_at`, `is_active`) VALUES
(30, 'Christine Moore Has Transfered Rs.20 From Bank Account 421873905 To Bank Account 287359614', '2024-12-16 14:37:17.891954', 1),
(31, 'Jenil Dhola has deposited Rs.100000 into bank account 864790325', '2024-12-16 14:42:23.963486', 1),
(32, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 864790325', '2024-12-16 14:42:35.789915', 1),
(33, 'Jenil Dhola Has Transferred Rs.50000 From Bank Account 864790325 To Bank Account 724310586', '2024-12-16 14:42:46.593408', 1),
(34, 'Jenil Dhola has deposited Rs.100 into bank account 864790325', '2025-01-16 09:28:30.966334', 1),
(35, 'kirtanmoradiya has deposited Rs.50000 into bank account 573608192', '2025-02-02 05:55:05.862578', 1),
(36, 'kirtanmoradiya Has Withdrawn Rs. 10000 From Bank Account 573608192', '2025-02-02 07:12:37.265701', 1),
(37, 'kirtanmoradiya Has Transferred Rs.100 From Bank Account 573608192 To Bank Account 287359614', '2025-02-02 07:12:56.670711', 1),
(38, 'Christine Moore has deposited Rs.100 to bank account 421873905', '2025-02-03 17:20:33.553365', 1),
(44, 'Jenil Dhola has deposited Rs.50000 into bank account ', '2025-02-19 04:50:02.093556', 1),
(45, 'Jenil Dhola Has Withdrawn Rs. 50000 From Bank Account 864790325', '2025-02-19 05:27:49.696966', 1),
(46, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 864790325', '2025-02-19 05:27:55.390326', 1),
(47, 'Jenil Dhola Has Transferred Rs.20 From Bank Account 864790325 To Bank Account 864790325', '2025-02-19 05:32:22.010848', 1),
(48, 'Jenil Dhola has deposited Rs.789 into bank account ', '2025-02-19 07:53:30.468552', 1),
(49, 'Harry Den has deposited Rs.50000 into bank account ', '2025-02-19 07:55:14.389922', 1),
(60, 'Jenil Dhola has deposited Rs. 50000 into bank account 23', '2025-02-21 14:25:42.244813', 1),
(61, 'Utsav Chheta has deposited Rs. 1000000 into bank account 20', '2025-02-21 14:32:41.558193', 1),
(62, 'Utsav Chheta Has Withdrawn Rs. 50000 From Bank Account 730459816', '2025-02-22 08:51:35.204500', 1),
(63, 'Utsav Chheta has transferred Rs. 50000 from Bank Account 730459816 to Bank Account 573608192', '2025-02-22 08:55:20.094948', 1),
(64, 'Jenil Dhola has deposited Rs. 10000 into bank account 23', '2025-02-23 12:09:58.242033', 1),
(65, 'Jenil Dhola Has Withdrawn Rs. 1500 From Bank Account 573608192', '2025-02-23 12:10:13.835518', 1),
(66, 'A deposit of Rs. 100 has been made into Bank Account 357146928', '2025-02-23 12:29:48.061887', 1),
(67, 'Jenil Dhola has deposited Rs. 10000 into bank account 24', '2025-02-23 12:30:06.881084', 1),
(68, 'A deposit of Rs. 1000000 has been made into Bank Account 287359614', '2025-02-23 12:44:19.052409', 1),
(69, 'A deposit of Rs. 1000000 has been made into Bank Account 287359614', '2025-02-23 12:47:10.886948', 1),
(70, 'A deposit of Rs. 100000 has been made into Bank Account 573608192', '2025-02-23 17:12:16.964737', 1),
(71, 'Harry Den has withdrawn Rs. 222 from Bank Account 357146928', '2025-02-25 06:49:22.746139', 1),
(72, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:49:26.454014', 1),
(73, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:49:30.749347', 1),
(74, 'Harry Den has withdrawn Rs. 5000 from Bank Account 357146928', '2025-02-25 06:49:34.124488', 1),
(75, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:49:46.156092', 1),
(76, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:49:50.267011', 1),
(77, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:49:53.830524', 1),
(78, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:51:09.058123', 1),
(79, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:51:15.887096', 1),
(80, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:51:21.204757', 1),
(81, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:51:37.327515', 1),
(82, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:53:10.349008', 1),
(83, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:53:17.204486', 1),
(84, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 06:53:55.687697', 1),
(86, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 07:27:00.944058', 1),
(87, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 07:29:45.407215', 1),
(88, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 07:31:05.870439', 1),
(89, 'Harry Den has withdrawn Rs. 1200 from Bank Account 357146928', '2025-02-25 07:33:22.520159', 1),
(90, 'Harry Den has withdrawn Rs. 1200 from Bank Account 357146928', '2025-02-25 07:34:30.657984', 1),
(91, 'Arin Gabani has withdrawn Rs. 18000 from Bank Account 287359614', '2025-02-25 07:35:25.769536', 1),
(92, 'Arin Gabani has withdrawn Rs. 18000 from Bank Account 287359614', '2025-02-25 07:35:56.496813', 1),
(93, 'Arin Gabani has withdrawn Rs. 18000 from Bank Account 287359614', '2025-02-25 07:38:06.903347', 1),
(94, 'Arin Gabani has withdrawn Rs. 1200 from Bank Account 287359614', '2025-02-25 07:51:10.546682', 1),
(95, 'Harry Den has withdrawn Rs. 100 from Bank Account 357146928', '2025-02-25 07:52:08.535846', 1),
(96, 'A deposit of Rs. 18000 has been made into Bank Account 357146928', '2025-02-25 07:56:06.177748', 1),
(97, 'A deposit of Rs. 52000 has been made into Bank Account 357146928', '2025-02-25 07:56:15.624178', 1),
(98, 'Jenil Dhola Has Withdrawn Rs. 25000 From Bank Account 573608192', '2025-03-01 11:55:50.296623', 1),
(99, 'Harry Den Has Withdrawn Rs. 100 From Bank Account 357146928', '2025-03-01 12:08:44.103562', 1),
(100, 'Jenil Dhola Has Withdrawn Rs. 9000 From Bank Account 529714806', '2025-03-01 12:09:19.146649', 1),
(101, 'A deposit of Rs. 1000000 has been made into Bank Account 287359614', '2025-03-01 12:09:37.984075', 1),
(102, 'Arin gabani Has Withdrawn Rs. 50000 From Bank Account 287359614', '2025-03-01 12:09:59.891171', 1),
(103, 'Arin gabani Has Withdrawn Rs. 50000 From Bank Account 287359614', '2025-03-01 12:10:07.797564', 1),
(104, 'Jenil Dhola has deposited Rs. 6000 into bank account 23', '2025-03-01 12:17:09.335715', 1),
(105, 'Jenil Dhola has deposited Rs. 5000 into bank account 23', '2025-03-02 11:36:14.289504', 1),
(106, 'Harry Den Has Withdrawn Rs. 50000 From Bank Account 357146928', '2025-03-02 11:41:27.165799', 1),
(107, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-05-08 09:26:34.840175', 1),
(108, 'Jenil Dhola has deposited Rs. 100000 into bank account 23', '2025-03-07 16:54:24.102925', 1),
(109, 'Sahil Gohil has deposited Rs. 10000 into bank account 35', '2025-05-10 10:09:03.123606', 1),
(110, 'Sahil Gohil has deposited Rs. 20000 into bank account 35', '2025-05-10 10:14:37.731518', 1),
(111, 'Sahil Gohil has deposited Rs. 0 into bank account 35', '2025-03-10 13:55:07.600201', 1),
(112, 'Sahil Gohil has deposited Rs. 0 into bank account 35', '2025-03-10 13:56:26.212009', 1),
(113, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 13:58:31.383023', 1),
(114, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 13:59:55.939828', 1),
(115, 'Hari Pandya Has Withdrawn Rs. -1 From Bank Account 357146928', '2025-03-10 14:13:52.617812', 1),
(116, 'Hari Pandya Has Withdrawn Rs. -10000 From Bank Account 357146928', '2025-03-10 14:15:22.130324', 1),
(117, 'Hari Pandya Has Withdrawn Rs. 1 From Bank Account 357146928', '2025-03-10 14:16:09.576538', 1),
(118, 'Hari Pandya Has Withdrawn Rs. 0 From Bank Account 357146928', '2025-03-10 14:16:26.303856', 1),
(119, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 14:17:11.091503', 1),
(120, 'Hari Pandya Has Withdrawn Rs. 1 From Bank Account 357146928', '2025-03-10 14:20:44.658987', 1),
(121, 'Hari Pandya Has Withdrawn Rs. 0 From Bank Account 357146928', '2025-03-10 14:21:01.178761', 1),
(122, 'Hari Pandya Has Withdrawn Rs. 0 From Bank Account 357146928', '2025-03-10 14:21:16.629031', 1),
(123, 'Hari Pandya Has Withdrawn Rs. -1 From Bank Account 357146928', '2025-03-10 14:21:34.361824', 1),
(124, 'Hari Pandya Has Withdrawn Rs. 10000 From Bank Account 357146928', '2025-03-10 14:48:17.402657', 1),
(125, 'Sahil Gohil Has Withdrawn Rs. 100 From Bank Account 964031285', '2025-03-10 14:57:36.801831', 1),
(126, 'Sahil Gohil Has Withdrawn Rs. 15000 From Bank Account 964031285', '2025-03-10 14:57:48.759843', 1),
(127, 'Hari Pandya Has Withdrawn Rs. 100 From Bank Account 357146928', '2025-03-10 15:02:27.706796', 1),
(128, 'Sahil Gohil has deposited Rs. 20000 into bank account 35', '2025-03-10 15:16:21.012073', 1),
(129, 'Hari Pandya Has Withdrawn Rs. 25000 From Bank Account 357146928', '2025-03-10 15:25:49.600803', 1),
(130, 'Hari Pandya Has Withdrawn Rs. 1 From Bank Account 357146928', '2025-03-10 15:26:02.350246', 1),
(131, 'Hari Pandya Has Withdrawn Rs. 100 From Bank Account 357146928', '2025-03-10 15:26:09.577235', 1),
(132, 'Hari Pandya Has Withdrawn Rs. 100 From Bank Account 357146928', '2025-03-10 15:27:05.683161', 1),
(133, 'Hari Pandya Has Withdrawn Rs. -1 From Bank Account 357146928', '2025-03-10 15:30:35.430361', 1),
(134, 'Hari Pandya Has Withdrawn Rs. 100 From Bank Account 357146928', '2025-03-10 15:41:39.929704', 1),
(135, 'Sahil Gohil Has Withdrawn Rs. -1 From Bank Account 964031285', '2025-03-10 15:43:09.343191', 1),
(136, 'Sahil Gohil Has Withdrawn Rs. -10 From Bank Account 964031285', '2025-03-10 15:43:25.079138', 1),
(137, 'Sahil Gohil Has Withdrawn Rs. -1 From Bank Account 964031285', '2025-03-10 15:50:13.438727', 1),
(138, 'Sahil Gohil Has Withdrawn Rs. -1 From Bank Account 964031285', '2025-03-10 15:51:37.547886', 1),
(139, 'Sahil Gohil Has Withdrawn Rs. 1 From Bank Account 964031285', '2025-03-10 15:51:49.442232', 1),
(140, 'Sahil Gohil Has Withdrawn Rs. 1 From Bank Account 964031285', '2025-03-10 15:53:15.213669', 1),
(141, 'Sahil Gohil has deposited Rs. 1 into bank account 35', '2025-03-10 15:59:25.101694', 1),
(142, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 16:00:12.318133', 1),
(143, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 16:06:29.045512', 1),
(144, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 16:08:21.949085', 1),
(145, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 16:08:47.832639', 1),
(146, 'Sahil Gohil Has Withdrawn Rs. -12 From Bank Account 964031285', '2025-03-10 16:09:20.980224', 1),
(147, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 16:10:04.542000', 1),
(148, 'Sahil Gohil Has Withdrawn Rs. 100 From Bank Account 964031285', '2025-03-10 16:14:44.816740', 1),
(149, 'Sahil Gohil Has Withdrawn Rs. 100 From Bank Account 964031285', '2025-03-10 16:14:53.095940', 1),
(150, 'Sahil Gohil Has Withdrawn Rs. 0 From Bank Account 964031285', '2025-03-10 16:15:04.042354', 1),
(151, 'Sahil Gohil Has Withdrawn Rs. 100 From Bank Account 964031285', '2025-03-10 16:16:19.668470', 1),
(152, 'Sahil Gohil Has Withdrawn Rs. 1000 From Bank Account 964031285', '2025-03-10 16:16:32.137183', 1),
(153, 'arin gabani has deposited Rs. 100 into bank account 15', '2025-03-11 04:11:25.302142', 1),
(154, 'A deposit of Rs. 5000 has been made into Bank Account 964031285', '2025-05-11 04:55:44.084419', 1),
(155, 'A deposit of Rs. 100 has been made into Bank Account 287359614', '2025-05-11 05:06:14.752464', 1),
(156, 'utsav chheta has deposited Rs. 10000 into bank account 36', '2025-05-11 05:17:09.741981', 1),
(157, 'utsav chheta has deposited Rs. 10000 into bank account 36', '2025-03-11 13:52:56.957874', 1),
(158, 'A deposit of Rs. 10000 has been made into Bank Account 357146928', '2025-03-11 14:35:16.857714', 1),
(159, 'A deposit of Rs. 10000 has been made into Bank Account 705239816', '2025-03-11 14:36:32.681377', 1),
(160, 'Jenil Dhola has deposited Rs. 10000 into bank account 23', '2025-04-12 11:19:10.942834', 1),
(161, 'Jenil Dhola has deposited Rs. 1000 into bank account 23', '2025-03-15 03:55:52.093165', 1),
(162, 'Hari Pandya Has Withdrawn Rs. 1000 From Bank Account 357146928', '2025-03-15 04:11:37.040563', 1),
(163, 'Sahil Gohil Has Withdrawn Rs. 100 From Bank Account 964031285', '2025-03-15 04:17:28.306316', 1),
(164, 'Sahil Gohil Has Withdrawn Rs. 10000 From Bank Account 964031285', '2025-03-15 04:20:31.024712', 1),
(165, 'Jenil Dhola Has Withdrawn Rs. 1000 From Bank Account 573608192', '2025-03-15 04:27:32.826158', 1),
(166, 'Jenil Dhola Has Withdrawn Rs. 1000 From Bank Account 573608192', '2025-03-15 04:27:44.902404', 1),
(167, 'A deposit of Rs. 10000 has been made into Bank Account 705239816', '2025-04-15 04:41:52.723376', 1),
(168, 'A deposit of Rs. 10000 has been made into Bank Account 705239816', '2025-04-15 04:43:36.776129', 1),
(169, 'Hari Pandya Has Withdrawn Rs. 1000 From Bank Account 357146928', '2025-04-15 06:49:53.688491', 1),
(170, 'Hari Pandya Has Withdrawn Rs. 2500 From Bank Account 357146928', '2025-04-15 06:50:09.883209', 1),
(171, 'Jenil Dhola has deposited Rs. 1000 into bank account 23', '2025-04-15 07:14:37.095598', 1),
(172, 'A deposit of Rs. 900000 has been made into Bank Account 573608192', '2025-05-15 07:19:55.175864', 1),
(173, 'Jenil Dhola Has Withdrawn Rs. 1 From Bank Account 573608192', '2025-05-15 07:20:32.288912', 1),
(174, 'Hari Pandya Has Withdrawn Rs. 10 From Bank Account 357146928', '2025-05-17 09:29:27.575591', 1),
(175, 'Jenil Dhola Has Withdrawn Rs. 100000 From Bank Account 573608192', '2025-03-17 09:32:51.556243', 1),
(176, 'Jenil Dhola Has Withdrawn Rs. 829812 From Bank Account 573608192', '2025-03-17 09:33:14.788757', 1),
(177, 'A deposit of Rs. 10000 has been made into Bank Account 573608192', '2025-03-17 09:49:53.200979', 1),
(178, 'Jenil Dhola Has Withdrawn Rs. 10 From Bank Account 573608192', '2025-03-17 09:51:03.491673', 1),
(179, 'Jenil Dhola Has Withdrawn Rs. 10 From Bank Account 573608192', '2025-03-17 09:51:13.712417', 1),
(180, 'Jenil Dhola Has Withdrawn Rs. 10 From Bank Account 573608192', '2025-03-17 10:05:35.899765', 1),
(181, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-03-17 10:06:13.816217', 1),
(182, 'A deposit of Rs. 100 has been made into Bank Account 357146928', '2025-03-17 10:14:39.280639', 1),
(183, 'A deposit of Rs. 100 has been made into Bank Account 964031285', '2025-03-17 11:20:29.110143', 1),
(184, 'A deposit of Rs. 500 has been made into Bank Account 964031285', '2025-04-19 11:27:40.892859', 1),
(185, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-04-19 11:35:33.916972', 1),
(186, 'arin gabani Has Withdrawn Rs. 100 From Bank Account 287359614', '2025-04-19 11:37:17.223960', 1),
(187, 'arin gabani Has Withdrawn Rs. 100 From Bank Account 287359614', '2025-04-19 11:37:29.516824', 1),
(188, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-03-22 10:34:33.187445', 1),
(189, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-03-22 10:34:41.644056', 1),
(190, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-03-22 10:35:48.882444', 1),
(191, 'Jenil Dhola Has Withdrawn Rs. 1 From Bank Account 573608192', '2025-03-22 10:35:51.850194', 1),
(192, 'Harshit Rana Has Withdrawn Rs. 100 From Bank Account 705239816', '2025-03-22 10:43:40.094174', 1),
(193, 'Harshit Rana Has Withdrawn Rs. 100 From Bank Account 705239816', '2025-03-22 10:43:53.936585', 1),
(194, 'Jenil Dhola Has Withdrawn Rs. 1000 From Bank Account 573608192', '2025-03-28 07:48:37.446812', 1),
(195, 'Jenil Dhola Has Withdrawn Rs. 10000 From Bank Account 573608192', '2025-03-28 08:03:26.271727', 1),
(196, 'A deposit of Rs. 5000 has been made into Bank Account 357146928', '2025-03-28 08:03:37.891967', 1),
(197, 'Jenil Dhola Has Withdrawn Rs. 100 From Bank Account 573608192', '2025-03-28 08:03:56.307659', 1),
(198, 'A deposit of Rs. 10000 has been made into Bank Account 705239816', '2025-03-30 04:13:04.949049', 1),
(199, 'A deposit of Rs. 45 has been made into Bank Account 357146928', '2025-03-30 04:53:54.705261', 1),
(200, 'A deposit of Rs. 1 has been made into Bank Account 573608192', '2025-04-01 07:03:08.216485', 1),
(201, 'Jenil Dhola Has Withdrawn Rs. 67000 From Bank Account 573608192', '2025-04-01 15:09:48.782853', 1),
(202, 'arin gabani Has Withdrawn Rs. 100000 From Bank Account 287359614', '2025-04-05 06:14:29.035075', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ib_staff`
--

CREATE TABLE `ib_staff` (
  `staff_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staff_number` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `sex` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `aadhaar_number` varchar(12) NOT NULL,
  `pan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_staff`
--

INSERT INTO `ib_staff` (`staff_id`, `name`, `staff_number`, `phone`, `email`, `password`, `sex`, `profile_pic`, `is_active`, `otp`, `otp_expiry`, `aadhaar_number`, `pan`) VALUES
(3, 'Jay Shah', 'iBank-STAFF-6785', '9945899345', 'dharmika192@gmail.com', '$2y$10$QFW4Q/CDJoJSnbTGll..Uu3cRxo6UU58S/hn6U7HNq65LJz1uyuXG', 'Male', 'jay shah.jpg', 1, '993417', '2025-03-11 12:25:21', '987687654567', 'IJHKJ9876L'),
(4, 'Rahul Dravid', 'iBank-STAFF-6724', '7656789876', 'wall@gmail.com', 'd95d3bbedb4dcba5a8e891968853002354b028e9', 'Male', 'rahul.jpg', 1, NULL, NULL, '456834567685', 'MNBVC9876R');

-- --------------------------------------------------------

--
-- Table structure for table `ib_systemsettings`
--

CREATE TABLE `ib_systemsettings` (
  `id` int(20) NOT NULL,
  `sys_name` longtext NOT NULL,
  `sys_tagline` longtext NOT NULL,
  `sys_logo` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_systemsettings`
--

INSERT INTO `ib_systemsettings` (`id`, `sys_name`, `sys_tagline`, `sys_logo`, `is_active`) VALUES
(1, 'DigitalBankX', 'Digital banking revolution', 'bank.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ib_transactions`
--

CREATE TABLE `ib_transactions` (
  `tr_id` int(20) NOT NULL,
  `tr_code` varchar(200) NOT NULL,
  `account_id` int(20) NOT NULL,
  `tr_type` varchar(200) NOT NULL,
  `tr_status` varchar(200) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `transaction_amt` varchar(200) NOT NULL,
  `receiving_acc_no` varchar(200) DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ib_transactions`
--

INSERT INTO `ib_transactions` (`tr_id`, `tr_code`, `account_id`, `tr_type`, `tr_status`, `client_id`, `transaction_amt`, `receiving_acc_no`, `created_at`, `is_active`) VALUES
(95, 'dU1ykHVsqDFP7g9ShcZR', 23, 'Deposit', 'Success', 11, '10000', '', '2025-02-23 12:09:58.241214', 1),
(96, 'vgmrt9xW5d1n2aMQkV6j', 23, 'Withdrawal', 'Success ', 11, '1500', '', '2025-02-23 12:10:13.832713', 1),
(97, 'DJyFP59gKakXtfqrpViW', 23, 'Transfer', 'Success', 11, '10000', '705239816', '2025-02-23 12:27:23.000000', 1),
(100, '4eE7TRJAi5vklOWbstVQ', 23, 'Transfer', 'Success', 11, '100', '705239816', '2025-02-23 12:36:08.000000', 1),
(102, 'vZRaVb5SqrjT4wJsQF8A', 15, 'Deposit', 'Success', 8, '1000000', '', '2025-02-23 12:47:10.885519', 1),
(106, '4eE7TRJAi5vklOWbstVQ', 23, 'Transfer', 'Success', 11, '100', '705239816', '2025-02-23 13:10:15.000000', 1),
(107, 'lRWZzpieXyKbJhcuHfw5', 23, 'Transfer', 'Success', 11, '10000', '357146928', '2025-02-23 13:11:41.000000', 1),
(111, 'P8lqXfcMVSADgUoreLCb', 15, 'Transfer', 'Success', 8, '100', '529714806', '2025-02-23 14:15:41.000000', 1),
(120, '2yXC9BRlKaMUjquiSGdN', 23, 'Deposit', 'Success', 11, '100000', '', '2025-02-23 17:12:16.964154', 1),
(121, '9oAhuYv0sZIFJe2EWOkN', 23, 'Transfer', 'Success', 11, '4000', '730459816', '2025-02-23 17:12:36.000000', 1),
(122, 'wzKmyf1F38cYgPTj7H2R', 23, 'Transfer', 'Success', 11, '78000', '287359614', '2025-02-23 17:12:42.000000', 1),
(143, 'OJGSDurUIQlo8F9q42Xp', 15, 'Withdrawal', 'Success', 8, '18000', NULL, '2025-02-25 07:35:25.769176', 1),
(144, 'OJGSDurUIQlo8F9q42Xp', 15, 'Withdrawal', 'Success', 8, '18000', NULL, '2025-02-25 07:35:56.496047', 1),
(145, 'OJGSDurUIQlo8F9q42Xp', 15, 'Withdrawal', 'Success', 8, '18000', NULL, '2025-02-25 07:38:06.902375', 1),
(146, 'eFv5sWty7h2z6HN4VpfI', 15, 'Withdrawal', 'Success', 8, '1200', NULL, '2025-02-25 07:51:10.544527', 1),
(148, 'w7t3buIPxXLBYz69NWl2', 15, 'Transfer', 'Success', 8, '1200', '864790325', '2025-02-25 07:53:05.000000', 1),
(152, 'hQR23ziWvyEAeUZmtMrN', 15, 'Transfer', 'Success', 8, '18000', '730459816', '2025-02-25 07:55:18.000000', 1),
(155, 'rHSMnDi7cXoEZhFIJwut', 16, 'Transfer', 'Success', 6, '100', '287359614', '2025-02-25 08:52:27.000000', 1),
(156, 'ZSnEfPBAlHI5hLo7vCX4', 23, 'Withdrawal', 'Success ', 11, '25000', NULL, '2025-03-01 11:55:50.293460', 1),
(159, 'gtKpC6rMczAyUaYu5Slj', 15, 'Deposit', 'Success', 8, '1000000', NULL, '2025-03-01 12:09:37.983716', 1),
(160, 'Xmaz801KbMOI3nu64xVi', 15, 'Withdrawal', 'Success ', 8, '50000', NULL, '2025-03-01 12:09:59.889484', 1),
(161, 'QTgSnD7s2XZWI4h5Hxbu', 15, 'Withdrawal', 'Success ', 8, '50000', NULL, '2025-03-01 12:10:07.795601', 1),
(162, 'WLmHJwBo14XyjF72r3u8', 23, 'Transfer', 'Success', 11, '100', '357146928', '2025-03-01 12:11:07.000000', 1),
(165, '9FnwvaJcdq0B8yWko1pV', 23, 'Deposit', 'Success', 11, '6000', NULL, '2025-03-01 12:17:09.335452', 1),
(166, 'FbQurL7NqXKdWEozBCji', 23, 'Transfer', 'Success', 11, '6000', '529714806', '2025-03-01 12:18:48.000000', 1),
(167, 'mfjgDwEaTxvrCR8cI6uq', 23, 'Deposit', 'Success', 11, '5000', NULL, '2025-03-02 11:36:14.286271', 1),
(168, '8pWHm4oSnVQx5fzrPh2Z', 15, 'Transfer', 'Success', 8, '10000', '573608192', '2025-03-02 11:40:54.000000', 1),
(184, 'q3VHEGI1U0NOle47L96D', 23, 'Transfer', 'Success', 11, '487', '705239816', '2025-03-02 13:01:59.000000', 1),
(204, 'GFcYuTbBtK8xVCjhlDo9', 23, 'Deposit', 'Success', 11, '100000', NULL, '2025-03-07 16:54:24.102416', 1),
(221, 'odatixyL1lAkwQ5YvJqE', 35, 'Withdrawal', 'Success ', 15, '100', NULL, '2025-03-10 14:57:36.799022', 1),
(222, 'By1riua2vCHdpOTM4Vwx', 35, 'Withdrawal', 'Success ', 15, '15000', NULL, '2025-03-10 14:57:48.757350', 1),
(223, 'LY1eniNRZa3jrGJVASMg', 35, 'Transfer', 'Success', 15, '500', '705239816', '2025-03-10 14:58:05.000000', 1),
(224, 'Du4YrlhWLvKEJ2TjUbiq', 15, 'Transfer', 'Success', 8, '1200', '964031285', '2025-03-10 14:59:08.000000', 1),
(226, 'YHkWqvzF6CUV0DZoL1hI', 35, 'Deposit', 'Success', 15, '20000', NULL, '2025-03-10 15:16:21.011716', 1),
(275, 'dEsxjtyZ64cR8ighSkFJ', 35, 'Withdrawal', 'Success ', 15, '100', NULL, '2025-03-15 04:17:28.304574', 1),
(303, 'KwjG3NnTbhSHkEMf18xr', 23, 'Transfer', 'Success', 11, '100', '287359614', '2025-03-22 07:31:36.000000', 1),
(304, 'CcZHG6IeX4Owas17Pbd0', 23, 'Withdrawal', 'Success ', 11, '100', NULL, '2025-03-22 10:34:33.185925', 1),
(309, 'sDF6KhTUIye0aLo3cCpw', 16, 'Withdrawal', 'Success ', 6, '100', NULL, '2025-03-22 10:43:40.092910', 1),
(337, 'AqThYMflPWekF05ym94s', 23, 'Withdrawal', 'Success ', 11, '1000', NULL, '2025-03-28 07:48:37.442022', 1),
(338, 'fFEOJupB5gclneavU2Cj', 23, 'Withdrawal', 'Success ', 11, '10000', NULL, '2025-03-28 08:03:26.271179', 1),
(340, 'WYUNFyp6flvozIV0Xerg', 23, 'Withdrawal', 'Success ', 11, '100', NULL, '2025-03-28 08:03:56.301852', 1),
(341, 'NYfXc45h6qy8urbRzlgQ', 23, 'Transfer', 'Success', 11, '100', '705239816', '2025-03-28 08:07:42.000000', 1),
(342, 'UwMQ0GBnjAsva3XyqhmP', 23, 'Transfer', 'Success', 11, '100', '357146928', '2025-03-28 08:37:50.000000', 1),
(345, '403xYEsaLGqZ2nfQNVyt', 16, 'Deposit', 'Success', 6, '10000', NULL, '2025-03-30 04:13:04.944211', 1),
(349, 'dvKr2ODRHgEUW10XwF4J', 23, 'Transfer', 'Success', 11, '1000', '357146928', '2025-03-30 04:49:13.000000', 1),
(362, '1c5ca7ae765c51264f39', 16, 'Deposit', 'Success', 6, '713.67', NULL, '2025-04-03 06:55:21.000000', 1),
(363, 'd63202994bab65285542', 23, 'Deposit', 'Success', 11, '3628.2', NULL, '2025-04-03 06:55:21.000000', 1),
(364, '231c5a24297c78cb2155', 35, 'Deposit', 'Success', 15, '15.14', NULL, '2025-04-03 06:55:21.000000', 1),
(365, '1d4a74379903941eb019', 15, 'Deposit', 'Success', 8, '300000.00', NULL, '2025-04-05 06:13:07.434786', 1),
(387, '03771046fd754e76e807', 35, 'Deposit', 'Success', 15, '100000.00', NULL, '2025-04-05 15:57:26.772864', 1),
(388, '6c17b880a2fd2fb9181d', 23, 'Deposit', 'Success', 11, '100000.00', NULL, '2025-04-05 15:57:34.900939', 1),
(389, '0f4d4f3219f9ff8acec6', 35, 'Deposit', 'Success', 15, '120000.00', NULL, '2025-04-05 15:57:41.489113', 1),
(391, 'XI3TwhWfOcb8uZrJyQ9A', 23, 'Transfer', 'Success', 11, '10000', '287359614', '2025-04-05 16:07:24.000000', 1),
(392, 'B9mfUosY2bcXeQW6hnSE', 15, 'Transfer', 'Success', 8, '122', '573608192', '2025-04-08 07:27:36.000000', 1),
(394, 'X73yxL4utqGrPlEO5F80', 23, 'Transfer', 'Success', 11, '2928', '287359614', '2025-04-08 07:35:05.000000', 1),
(395, 'PtaBEoXhI40TSWZe5ysn', 23, 'Transfer', 'Success', 11, '200', '287359614', '2025-04-08 07:50:20.000000', 1),
(396, 'cVjz8SFYHOqw7sEygTMk', 15, 'Transfer', 'Success', 8, '677', '573608192', '2025-04-08 07:51:09.000000', 1),
(397, 'juASztFa8b6veXfqOwcx', 23, 'Transfer', 'Success', 11, '1000', '705239816', '2025-04-08 15:07:59.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `interest_log`
--

CREATE TABLE `interest_log` (
  `id` int(11) NOT NULL,
  `month_year` varchar(7) NOT NULL,
  `deposited_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interest_log`
--

INSERT INTO `interest_log` (`id`, `month_year`, `deposited_by`, `created_at`) VALUES
(8, '2025-03', 2, '2025-03-19 15:31:10'),
(13, '2025-04', 2, '2025-04-03 12:25:21');

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `application_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected','recommended') NOT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `admin_review_id` int(11) DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `staff_remark` text DEFAULT NULL,
  `admin_remark` text NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `loan_type_id` int(11) DEFAULT NULL,
  `is_approved_by_staff` tinyint(1) DEFAULT 0,
  `income_salary` decimal(10,2) NOT NULL,
  `loan_duration_years` int(11) NOT NULL DEFAULT 0,
  `loan_duration_months` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `applicant_name`, `loan_amount`, `application_date`, `status`, `reviewed_by`, `admin_review_id`, `review_date`, `staff_remark`, `admin_remark`, `client_id`, `loan_type_id`, `is_approved_by_staff`, `income_salary`, `loan_duration_years`, `loan_duration_months`) VALUES
(28, 'Harshit Rana', 120000.00, '2025-03-05 13:49:35', 'approved', 3, 2, '2025-04-05 21:27:41', 'good', 'ridee', 15, 11, 0, 25000.00, 1, 6),
(31, 'Arin gabani', 300000.00, '2025-04-14 14:47:07', 'approved', 3, 2, '2025-04-05 21:27:18', 'reject', 'goo study', 8, 5, 0, 30000.00, 7, 0),
(43, 'Jenil Dhola', 100000.00, '2025-04-01 12:24:18', 'approved', 3, 2, '2025-04-05 21:27:34', 'ds', 'approve', 11, 4, 0, 50000.00, 1, 5),
(44, 'Sahil Gohil', 100000.00, '2025-04-05 11:46:29', 'approved', 3, 2, '2025-04-05 21:27:26', 'recommned', 'amabani', 15, 6, 0, 19000.00, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `loan_emi_schedule`
--

CREATE TABLE `loan_emi_schedule` (
  `id` int(11) NOT NULL,
  `loan_id` int(10) UNSIGNED NOT NULL,
  `emi_number` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_emi_schedule`
--

INSERT INTO `loan_emi_schedule` (`id`, `loan_id`, `emi_number`, `due_date`, `amount`, `status`, `created_at`) VALUES
(1, 31, 1, '2025-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(2, 31, 2, '2025-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(3, 31, 3, '2025-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(4, 31, 4, '2025-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(5, 31, 5, '2025-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(6, 31, 6, '2025-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(7, 31, 7, '2025-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(8, 31, 8, '2025-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(9, 31, 9, '2026-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(10, 31, 10, '2026-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(11, 31, 11, '2026-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(12, 31, 12, '2026-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(13, 31, 13, '2026-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(14, 31, 14, '2026-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(15, 31, 15, '2026-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(16, 31, 16, '2026-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(17, 31, 17, '2026-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(18, 31, 18, '2026-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(19, 31, 19, '2026-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(20, 31, 20, '2026-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(21, 31, 21, '2027-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(22, 31, 22, '2027-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(23, 31, 23, '2027-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(24, 31, 24, '2027-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(25, 31, 25, '2027-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(26, 31, 26, '2027-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(27, 31, 27, '2027-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(28, 31, 28, '2027-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(29, 31, 29, '2027-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(30, 31, 30, '2027-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(31, 31, 31, '2027-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(32, 31, 32, '2027-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(33, 31, 33, '2028-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(34, 31, 34, '2028-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(35, 31, 35, '2028-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(36, 31, 36, '2028-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(37, 31, 37, '2028-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(38, 31, 38, '2028-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(39, 31, 39, '2028-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(40, 31, 40, '2028-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(41, 31, 41, '2028-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(42, 31, 42, '2028-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(43, 31, 43, '2028-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(44, 31, 44, '2028-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(45, 31, 45, '2029-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(46, 31, 46, '2029-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(47, 31, 47, '2029-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(48, 31, 48, '2029-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(49, 31, 49, '2029-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(50, 31, 50, '2029-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(51, 31, 51, '2029-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(52, 31, 52, '2029-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(53, 31, 53, '2029-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(54, 31, 54, '2029-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(55, 31, 55, '2029-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(56, 31, 56, '2029-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(57, 31, 57, '2030-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(58, 31, 58, '2030-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(59, 31, 59, '2030-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(60, 31, 60, '2030-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(61, 31, 61, '2030-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(62, 31, 62, '2030-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(63, 31, 63, '2030-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(64, 31, 64, '2030-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(65, 31, 65, '2030-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(66, 31, 66, '2030-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(67, 31, 67, '2030-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(68, 31, 68, '2030-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(69, 31, 69, '2031-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(70, 31, 70, '2031-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(71, 31, 71, '2031-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(72, 31, 72, '2031-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(73, 31, 73, '2031-05-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(74, 31, 74, '2031-06-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(75, 31, 75, '2031-07-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(76, 31, 76, '2031-08-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(77, 31, 77, '2031-09-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(78, 31, 78, '2031-10-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(79, 31, 79, '2031-11-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(80, 31, 80, '2031-12-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(81, 31, 81, '2032-01-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(82, 31, 82, '2032-02-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(83, 31, 83, '2032-03-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(84, 31, 84, '2032-04-01', 5008.93, 'pending', '2025-04-05 15:57:18'),
(85, 44, 1, '2025-05-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(86, 44, 2, '2025-06-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(87, 44, 3, '2025-07-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(88, 44, 4, '2025-08-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(89, 44, 5, '2025-09-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(90, 44, 6, '2025-10-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(91, 44, 7, '2025-11-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(92, 44, 8, '2025-12-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(93, 44, 9, '2026-01-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(94, 44, 10, '2026-02-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(95, 44, 11, '2026-03-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(96, 44, 12, '2026-04-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(97, 44, 13, '2026-05-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(98, 44, 14, '2026-06-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(99, 44, 15, '2026-07-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(100, 44, 16, '2026-08-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(101, 44, 17, '2026-09-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(102, 44, 18, '2026-10-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(103, 44, 19, '2026-11-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(104, 44, 20, '2026-12-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(105, 44, 21, '2027-01-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(106, 44, 22, '2027-02-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(107, 44, 23, '2027-03-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(108, 44, 24, '2027-04-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(109, 44, 25, '2027-05-01', 4750.00, 'pending', '2025-04-05 15:57:26'),
(110, 43, 1, '2025-05-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(111, 43, 2, '2025-06-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(112, 43, 3, '2025-07-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(113, 43, 4, '2025-08-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(114, 43, 5, '2025-09-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(115, 43, 6, '2025-10-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(116, 43, 7, '2025-11-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(117, 43, 8, '2025-12-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(118, 43, 9, '2026-01-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(119, 43, 10, '2026-02-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(120, 43, 11, '2026-03-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(121, 43, 12, '2026-04-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(122, 43, 13, '2026-05-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(123, 43, 14, '2026-06-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(124, 43, 15, '2026-07-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(125, 43, 16, '2026-08-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(126, 43, 17, '2026-09-01', 6715.69, 'pending', '2025-04-05 15:57:34'),
(127, 28, 1, '2025-05-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(128, 28, 2, '2025-06-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(129, 28, 3, '2025-07-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(130, 28, 4, '2025-08-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(131, 28, 5, '2025-09-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(132, 28, 6, '2025-10-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(133, 28, 7, '2025-11-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(134, 28, 8, '2025-12-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(135, 28, 9, '2026-01-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(136, 28, 10, '2026-02-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(137, 28, 11, '2026-03-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(138, 28, 12, '2026-04-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(139, 28, 13, '2026-05-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(140, 28, 14, '2026-06-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(141, 28, 15, '2026-07-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(142, 28, 16, '2026-08-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(143, 28, 17, '2026-09-01', 7466.67, 'pending', '2025-04-05 15:57:41'),
(144, 28, 18, '2026-10-01', 7466.67, 'pending', '2025-04-05 15:57:41');

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `loan_id` int(10) UNSIGNED NOT NULL,
  `emi_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('paid','pending') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_payments`
--

INSERT INTO `loan_payments` (`id`, `client_id`, `loan_id`, `emi_date`, `amount`, `status`, `created_at`) VALUES
(11, 8, 31, '2025-05-09', 5009.00, 'paid', '2025-05-14 09:24:16'),
(13, 8, 31, '2025-05-21', 5009.00, 'paid', '2025-05-10 09:53:30'),
(14, 8, 31, '2025-05-19', 5009.00, 'paid', '2025-05-10 09:55:30'),
(17, 15, 28, '2025-05-10', 7200.00, 'paid', '2025-05-10 10:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `max_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`id`, `type_name`, `description`, `interest_rate`, `max_amount`, `created_at`, `is_active`) VALUES
(2, 'Home Loan', 'A Home Loan is a long-term financial product designed to help individuals buy property.', 8.25, 5000000.00, '2025-02-04 05:48:43', 1),
(3, 'Car Loan', 'A Car Loan is used to finance the purchase of a vehicle, often with fixed interest rates.', 7.50, 30000.00, '2025-02-04 05:48:43', 1),
(4, 'Personal Loan', 'A Personal Loan is an unsecured loan for personal expenses such as travel or medical needs.', 10.00, 20000.00, '2025-02-04 05:48:43', 1),
(5, 'Education Loan', 'An Education Loan helps students finance their tuition and other academic expenses.', 5.75, 100000.00, '2025-02-04 05:48:43', 1),
(6, 'Business Loan', 'A Business Loan provides funds to entrepreneurs and companies for business growth.', 9.00, 250000.00, '2025-02-04 05:48:43', 1),
(7, 'Gold Loan', 'A Gold Loan is secured against gold jewelry and has a lower interest rate.', 6.50, 50000.00, '2025-02-04 05:48:43', 1),
(8, 'Credit Card Loan', 'A Credit Card Loan is an extension of credit card limits for financial flexibility.', 12.50, 15000.00, '2025-02-04 05:48:43', 1),
(9, 'Agriculture Loan', 'An Agriculture Loan supports farmers in purchasing equipment, seeds, and livestock.', 4.50, 120000.00, '2025-02-04 05:48:43', 1),
(10, 'Mortgage Loan', 'A Mortgage Loan allows individuals to borrow against the value of their property.', 7.80, 450000.00, '2025-02-04 05:48:43', 1),
(11, 'Two WheelerLoan', 'A Two-Wheeler Loan is used to finance the purchase of motorcycles and scooters.', 8.00, 0.00, '2025-02-04 05:48:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `admin_id`, `token`, `expiry`) VALUES
(1, 2, 'bdbae7f327ee9d12d0c5c47a5bee32db6828d3fed359ef8caaf6d77fb192350a', '2025-01-21 15:43:18'),
(2, 2, 'd5cd7042b2dfc604af69a4f7fbf752bffc0f95c564f3bcf1a15a12fbb5714b72', '2025-01-23 15:41:40'),
(3, 2, 'bdb9e6e793564daa0689436de8b68a372335dcb5a040edcb4a47cf326a3d476e', '2025-01-23 15:41:42'),
(4, 2, '4478e3d37bfbf2e8e4f5816ca4559aff7de939cf3105d4514c9fd2e6636ad151', '2025-01-23 16:02:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client_feedback`
--
ALTER TABLE `client_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  ADD PRIMARY KEY (`acctype_id`);

--
-- Indexes for table `ib_admin`
--
ALTER TABLE `ib_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `fk_ib_bankaccounts_clients` (`client_id`),
  ADD KEY `fk_acc_type` (`acc_type_id`);

--
-- Indexes for table `ib_bank_main_account`
--
ALTER TABLE `ib_bank_main_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- Indexes for table `ib_clients`
--
ALTER TABLE `ib_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `ib_nominees`
--
ALTER TABLE `ib_nominees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `ib_staff`
--
ALTER TABLE `ib_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  ADD PRIMARY KEY (`tr_id`),
  ADD KEY `fk_account` (`account_id`),
  ADD KEY `fk_client` (`client_id`);

--
-- Indexes for table `interest_log`
--
ALTER TABLE `interest_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `month_year` (`month_year`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `loan_type_id` (`loan_type_id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `fk_admin_review` (`admin_review_id`);

--
-- Indexes for table `loan_emi_schedule`
--
ALTER TABLE `loan_emi_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_payment` (`client_id`,`loan_id`,`emi_date`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client_feedback`
--
ALTER TABLE `client_feedback`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  MODIFY `acctype_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ib_admin`
--
ALTER TABLE `ib_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  MODIFY `account_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `ib_bank_main_account`
--
ALTER TABLE `ib_bank_main_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_clients`
--
ALTER TABLE `ib_clients`
  MODIFY `client_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ib_nominees`
--
ALTER TABLE `ib_nominees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `ib_staff`
--
ALTER TABLE `ib_staff`
  MODIFY `staff_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  MODIFY `tr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT for table `interest_log`
--
ALTER TABLE `interest_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `loan_emi_schedule`
--
ALTER TABLE `loan_emi_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_feedback`
--
ALTER TABLE `client_feedback`
  ADD CONSTRAINT `client_feedback_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `ib_clients` (`client_id`) ON DELETE CASCADE;

--
-- Constraints for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  ADD CONSTRAINT `fk_acc_type` FOREIGN KEY (`acc_type_id`) REFERENCES `ib_acc_types` (`acctype_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ib_bankaccounts_clients` FOREIGN KEY (`client_id`) REFERENCES `ib_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  ADD CONSTRAINT `fk_account` FOREIGN KEY (`account_id`) REFERENCES `ib_bankaccounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`client_id`) REFERENCES `ib_clients` (`client_id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD CONSTRAINT `fk_admin_review` FOREIGN KEY (`admin_review_id`) REFERENCES `ib_admin` (`admin_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_client_id` FOREIGN KEY (`client_id`) REFERENCES `ib_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_applications_ibfk_1` FOREIGN KEY (`reviewed_by`) REFERENCES `ib_staff` (`staff_id`),
  ADD CONSTRAINT `loan_applications_ibfk_2` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`);

--
-- Constraints for table `loan_emi_schedule`
--
ALTER TABLE `loan_emi_schedule`
  ADD CONSTRAINT `fk_loan_id` FOREIGN KEY (`loan_id`) REFERENCES `loan_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `ib_clients` (`client_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_ibfk_2` FOREIGN KEY (`loan_id`) REFERENCES `loan_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `ib_admin` (`admin_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
