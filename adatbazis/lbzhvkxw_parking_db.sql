-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2025. Okt 14. 10:23
-- Kiszolgáló verziója: 10.11.14-MariaDB-cll-lve-log
-- PHP verzió: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `lbzhvkxw_parking_db`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admin_naplo`
--

CREATE TABLE `admin_naplo` (
  `aid` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_nev` varchar(100) DEFAULT NULL,
  `admin_email` varchar(100) DEFAULT NULL,
  `muvelet` varchar(255) DEFAULT NULL,
  `reszletek` text DEFAULT NULL,
  `ip_cim` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- A tábla adatainak kiíratása `admin_naplo`
--

INSERT INTO `admin_naplo` (`aid`, `admin_id`, `admin_nev`, `admin_email`, `muvelet`, `reszletek`, `ip_cim`, `user_agent`, `created_at`) VALUES
(1, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-09 19:49:51'),
(2, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-09 19:49:56'),
(3, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-09 19:54:22'),
(4, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-09 20:10:11'),
(5, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 20:10:16'),
(6, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-09 20:41:27'),
(7, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 20:42:27'),
(8, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 20:47:16'),
(9, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 20:52:31'),
(10, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 20:55:14'),
(11, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 21:00:04'),
(12, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:41f7:e4fb:3d33:ffa9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-09 21:06:22'),
(13, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 12:12:37'),
(14, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-10 12:18:41'),
(15, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 12:22:46'),
(16, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-10 12:22:49'),
(17, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 12:23:02'),
(18, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-10 12:23:12'),
(19, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 12:23:31'),
(20, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e50b:48b8:ffd:39dd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-10 17:15:11'),
(21, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-10 17:37:17'),
(22, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-10 17:38:17'),
(23, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e50b:48b8:ffd:39dd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-10 17:38:31'),
(24, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-10 17:39:36'),
(25, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-10 17:45:08'),
(26, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-10 17:48:40'),
(27, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-10 17:49:05'),
(28, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e50b:48b8:ffd:39dd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-10 17:49:30'),
(29, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-10 17:50:31'),
(30, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e50b:48b8:ffd:39dd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-10 17:50:42'),
(31, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 5', NULL, NULL, '2025-10-10 17:51:06'),
(32, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 5', NULL, NULL, '2025-10-10 17:51:09'),
(33, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-10 17:51:30'),
(34, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-10 17:51:35'),
(35, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:08:51'),
(36, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-13 08:09:12'),
(37, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 08:09:18'),
(38, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:09:29'),
(39, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-13 08:10:09'),
(40, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott törlése', 'Admin Proparking (ID: 5)', NULL, NULL, '2025-10-13 08:10:22'),
(41, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 08:10:32'),
(42, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:13:38'),
(43, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-13 08:13:47'),
(44, 3, 'Martin', 'kothenczmartin@gmail.com', 'Alkalmazott szerkesztése', 'Lenhardt Károly (ID: 1)', NULL, NULL, '2025-10-13 08:14:35'),
(45, 3, 'Martin', 'kothenczmartin@gmail.com', 'Alkalmazott szerkesztése', 'Lenhardt Károly (ID: 1)', NULL, NULL, '2025-10-13 08:14:45'),
(46, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 08:14:49'),
(47, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:15:17'),
(48, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:21:47'),
(49, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-13 08:22:06'),
(50, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'dezs? (ID: 14)', NULL, NULL, '2025-10-13 08:23:43'),
(51, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'lakatos (ID: 9)', NULL, NULL, '2025-10-13 08:24:41'),
(52, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'lakatos (ID: 9)', NULL, NULL, '2025-10-13 08:24:41'),
(53, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 08:28:31'),
(54, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:28:36'),
(55, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 08:29:08'),
(56, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új admin hozzáadása', 'Martin Admin - admin', NULL, NULL, '2025-10-13 08:29:20'),
(57, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 09:35:10'),
(58, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 09:35:47'),
(59, 12, 'Borsos Dani', 'borosdani@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 09:35:52'),
(60, 12, 'Borsos Dani', 'borosdani@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-13 09:37:20'),
(61, 12, 'Borsos Dani', 'borosdani@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 09:37:28'),
(62, 12, 'Borsos Dani', 'borosdani@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 09:37:42'),
(63, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-13 11:00:16'),
(64, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-13 11:23:16'),
(65, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új alkalmazott hozzáadása', 'demo', NULL, NULL, '2025-10-13 11:24:01'),
(66, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 12:50:42'),
(67, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 12:55:14'),
(68, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 12:57:05'),
(69, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Alkalmazott szerkesztése', 'demo (ID: 16)', NULL, NULL, '2025-10-13 12:57:28'),
(70, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Alkalmazott szerkesztése', 'demo (ID: 16)', NULL, NULL, '2025-10-13 12:58:36'),
(71, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Alkalmazott szerkesztése', 'demo (ID: 16)', NULL, NULL, '2025-10-13 12:59:08'),
(72, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 13:06:54'),
(73, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-13 13:07:02'),
(74, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új cég hozzáadása', 'Etele pláza', NULL, NULL, '2025-10-13 13:18:19'),
(75, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '130.43.210.86', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-13 14:33:10'),
(76, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-13 14:33:26'),
(77, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:b935:79fc:7588:424f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-13 20:16:00'),
(78, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 20:17:41'),
(79, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:b935:79fc:7588:424f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-13 20:17:45'),
(80, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új admin hozzáadása', 'jozsi - admin', NULL, NULL, '2025-10-13 20:20:08'),
(81, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-13 20:20:58'),
(82, 17, 'jozsi', '4e32423@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:b935:79fc:7588:424f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-13 20:21:15'),
(83, 17, 'jozsi', '4e32423@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-13 20:22:03'),
(84, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 09:10:01'),
(85, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 09:11:56'),
(86, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 09:12:01'),
(87, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 09:12:04'),
(88, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 09:12:09'),
(89, 3, 'Martin', 'kothenczmartin@gmail.com', 'Alkalmazott szerkesztése', 'dezs? (ID: 14)', NULL, NULL, '2025-10-14 09:14:45'),
(90, 3, 'Martin', 'kothenczmartin@gmail.com', 'Alkalmazott szerkesztése', 'dezs? (ID: 14)', NULL, NULL, '2025-10-14 09:16:36'),
(91, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 09:58:16');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ceg`
--

CREATE TABLE `ceg` (
  `cid` int(11) NOT NULL,
  `cnev` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `ceg`
--

INSERT INTO `ceg` (`cid`, `cnev`) VALUES
(1, 'proparking.hu'),
(2, 'weiss'),
(10, 'Etele pláza');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ember`
--

CREATE TABLE `ember` (
  `eid` int(11) NOT NULL,
  `enev` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `etel` varchar(12) DEFAULT NULL,
  `estatuszdolg` tinyint(4) DEFAULT NULL,
  `ekomment` varchar(100) DEFAULT NULL,
  `jogosultsag` varchar(100) DEFAULT NULL,
  `cid` int(11) NOT NULL,
  `ejelszo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `ember`
--

INSERT INTO `ember` (`eid`, `enev`, `email`, `etel`, `estatuszdolg`, `ekomment`, `jogosultsag`, `cid`, `ejelszo`) VALUES
(1, 'Lenhardt Károly', 'karcsivideo@gmail.com', '063125141652', 1, 'Kristályos dolgozó', 'superadmin', 2, '$2y$10$rPJYzEpEyu4eFYWO80Gc3e/93C24ZxA6d4mq6OwEpIU2QZ.3sSheS'),
(3, 'Martin', 'kothenczmartin@gmail.com', '2341344', 1, 'Új alkalmazott', 'superadmin', 1, '$2y$10$JlSDp3qkF4DBx3vIJq/ZYuapTlEW6KlMHIolH/eEl5FHb7jjOmJnO'),
(9, 'lakatos', 'laka@gmail.com', '2323136213', 1, 'Új alkalmazott - igazi hazafi, nagyon szorgalmas', 'user', 2, NULL),
(12, 'Borsos Dani', 'borosdani@gmail.com', '23213213', 1, 'Admin felhasználó', 'admin', 1, '$2y$10$giu7xZfN.caYR1BTSLSsJ.lhkI6fwLATqw2tuPQL3paR9i2zaxMcC'),
(13, 'Kis Geri', 'kisgeri@gmail.com', '23443543543', 1, 'Új alkalmazott: Eddig rendes 10.09', 'admin', 2, NULL),
(14, 'dezs?', 'nagydezso@gmail.com', 'nincs olyan', 1, 'Új alkalmazott - a sztárigazolás, nincs bírás vele', 'user', 2, NULL),
(15, 'Martin Admin', 'martinadmin@gmail.com', '342343242342', 1, 'Admin felhasználó', 'admin', 1, '$2y$10$MDtA/cpPVZDDj.aaXeAh7ee4EoAJHDrQRdAtQVlRhOnEUVELZw7DO'),
(16, 'demo', 'demo@gmail.com', '236273', 1, 'Új alkalmazott', 'user', 1, NULL),
(17, 'jozsi', '4e32423@gmail.com', '34342324', 1, 'Admin felhasználó', 'admin', 10, '$2y$10$ii5rlEGtr0dpTBuZz9/rnOf/YnE2Gz1S5SNO4YiqsWHn83Dy.W2PG');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `emberjarmu`
--

CREATE TABLE `emberjarmu` (
  `ejid` int(11) NOT NULL,
  `eid` int(11) DEFAULT NULL,
  `jid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ertekeles`
--

CREATE TABLE `ertekeles` (
  `eid` int(11) NOT NULL,
  `enev` varchar(100) NOT NULL,
  `ecsillag` tinyint(4) NOT NULL CHECK (`ecsillag` between 1 and 5),
  `ekomment` text NOT NULL,
  `edatum` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- A tábla adatainak kiíratása `ertekeles`
--

INSERT INTO `ertekeles` (`eid`, `enev`, `ecsillag`, `ekomment`, `edatum`) VALUES
(1, 'Km25', 5, 'A legjobb oldal', '2025-10-06 19:50:37'),
(2, 'Jh', 5, 'Hubz', '2025-10-07 15:01:46'),
(3, 'Ismeretlen idegen', 5, 'ez csúcsszuper\r\n', '2025-10-07 16:47:40'),
(4, 'Ismeretlen idegen', 5, 'ez csúcsszuper\r\n', '2025-10-07 16:51:41'),
(5, 'xddd', 4, 'kristaly mindent visz', '2025-10-07 16:52:34');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jarmu`
--

CREATE TABLE `jarmu` (
  `jid` int(11) NOT NULL,
  `jrendszam` varchar(8) DEFAULT NULL,
  `jtipus` varchar(20) DEFAULT NULL,
  `jszin` varchar(20) DEFAULT NULL,
  `jkomment` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kartya`
--

CREATE TABLE `kartya` (
  `kid` int(11) NOT NULL,
  `kazonosito` int(11) DEFAULT NULL,
  `kallapot` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `kartya`
--

INSERT INTO `kartya` (`kid`, `kazonosito`, `kallapot`) VALUES
(1, 105263846, 1),
(2, 575315265, 1),
(3, 1003, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kartyaember`
--

CREATE TABLE `kartyaember` (
  `keid` int(11) NOT NULL,
  `kid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `kartyaember`
--

INSERT INTO `kartyaember` (`keid`, `kid`, `eid`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `naplo`
--

CREATE TABLE `naplo` (
  `nid` int(11) NOT NULL,
  `keid` int(11) DEFAULT NULL,
  `ejid` int(11) DEFAULT NULL,
  `nstatusz` tinyint(4) DEFAULT NULL,
  `ndatumtol` date DEFAULT NULL,
  `nidotol` datetime DEFAULT NULL,
  `ndatumig` date DEFAULT NULL,
  `nidoig` datetime DEFAULT NULL,
  `nparkhely` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `naplo`
--

INSERT INTO `naplo` (`nid`, `keid`, `ejid`, `nstatusz`, `ndatumtol`, `nidotol`, `ndatumig`, `nidoig`, `nparkhely`) VALUES
(1, 1, NULL, 1, '2024-10-01', '2024-10-01 08:00:00', '2024-10-01', '2024-10-15 17:00:00', 101),
(2, 2, NULL, 1, '2024-10-01', '2024-10-01 09:15:00', '2024-10-01', '2024-10-01 18:30:00', 102),
(3, 1, NULL, 1, '2024-10-02', '2024-10-02 07:45:00', '2024-10-02', '2024-10-02 16:30:00', 101),
(4, 3, NULL, 1, '2024-10-02', '2024-10-02 08:30:00', '2024-10-02', '2024-10-02 17:45:00', 105),
(5, 2, NULL, 1, '2024-10-03', '2024-10-03 08:00:00', '2024-10-03', '2024-10-03 17:15:00', 102),
(6, 1, NULL, 1, '2024-10-03', '2024-10-03 09:00:00', '2024-10-03', '2024-10-03 18:00:00', 101),
(7, 3, NULL, 1, '2024-10-04', '2024-10-04 07:30:00', '2026-01-15', '2024-10-04 16:45:00', 103),
(8, 2, NULL, 0, '2024-10-04', '2024-10-04 08:45:00', '2025-10-15', NULL, 104),
(9, 1, NULL, 1, NULL, '2025-10-13 19:51:15', NULL, NULL, NULL),
(10, 1, NULL, 1, NULL, '2025-10-13 19:57:42', NULL, NULL, NULL),
(11, 2, NULL, 1, NULL, '2025-10-13 19:58:37', NULL, NULL, NULL),
(12, 2, NULL, 1, NULL, '2025-10-13 19:59:07', NULL, NULL, NULL),
(13, 2, NULL, 1, NULL, '2025-10-13 19:59:28', NULL, NULL, NULL),
(14, 2, NULL, 1, NULL, '2025-10-13 20:01:11', NULL, NULL, NULL),
(15, 2, NULL, 1, NULL, '2025-10-13 20:04:35', NULL, NULL, NULL),
(16, 1, NULL, 1, NULL, '2025-10-13 20:06:23', NULL, NULL, NULL),
(17, 1, NULL, 1, NULL, '2025-10-13 20:06:39', NULL, NULL, NULL),
(18, 2, NULL, 1, NULL, '2025-10-13 20:08:37', NULL, NULL, NULL),
(19, 1, NULL, 1, NULL, '2025-10-13 20:09:18', NULL, NULL, NULL),
(20, 1, NULL, 1, NULL, '2025-10-13 20:14:49', NULL, NULL, NULL),
(21, 1, NULL, 1, NULL, '2025-10-13 20:24:24', NULL, NULL, NULL);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `admin_naplo`
--
ALTER TABLE `admin_naplo`
  ADD PRIMARY KEY (`aid`);

--
-- A tábla indexei `ceg`
--
ALTER TABLE `ceg`
  ADD PRIMARY KEY (`cid`);

--
-- A tábla indexei `ember`
--
ALTER TABLE `ember`
  ADD PRIMARY KEY (`eid`);

--
-- A tábla indexei `emberjarmu`
--
ALTER TABLE `emberjarmu`
  ADD PRIMARY KEY (`ejid`);

--
-- A tábla indexei `ertekeles`
--
ALTER TABLE `ertekeles`
  ADD PRIMARY KEY (`eid`);

--
-- A tábla indexei `jarmu`
--
ALTER TABLE `jarmu`
  ADD PRIMARY KEY (`jid`);

--
-- A tábla indexei `kartya`
--
ALTER TABLE `kartya`
  ADD PRIMARY KEY (`kid`);

--
-- A tábla indexei `kartyaember`
--
ALTER TABLE `kartyaember`
  ADD PRIMARY KEY (`keid`);

--
-- A tábla indexei `naplo`
--
ALTER TABLE `naplo`
  ADD PRIMARY KEY (`nid`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `admin_naplo`
--
ALTER TABLE `admin_naplo`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT a táblához `ceg`
--
ALTER TABLE `ceg`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `ember`
--
ALTER TABLE `ember`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT a táblához `emberjarmu`
--
ALTER TABLE `emberjarmu`
  MODIFY `ejid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `ertekeles`
--
ALTER TABLE `ertekeles`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT a táblához `jarmu`
--
ALTER TABLE `jarmu`
  MODIFY `jid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `kartya`
--
ALTER TABLE `kartya`
  MODIFY `kid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `kartyaember`
--
ALTER TABLE `kartyaember`
  MODIFY `keid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `naplo`
--
ALTER TABLE `naplo`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
