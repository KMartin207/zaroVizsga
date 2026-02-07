-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2026. Feb 07. 13:50
-- Kiszolgáló verziója: 10.11.15-MariaDB-cll-lve-log
-- PHP verzió: 8.4.17

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
(91, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 09:58:16'),
(92, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 8', NULL, NULL, '2025-10-14 10:48:10'),
(93, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 8', NULL, NULL, '2025-10-14 10:48:18'),
(94, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '12 (ID: 12)', NULL, NULL, '2025-10-14 11:03:45'),
(95, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '13 (ID: 13)', NULL, NULL, '2025-10-14 11:04:37'),
(96, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-10-14 11:05:26'),
(97, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:05:32'),
(98, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '13 (ID: 13)', NULL, NULL, '2025-10-14 11:05:51'),
(99, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-10-14 11:05:59'),
(100, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:06:19'),
(101, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-10-14 11:07:02'),
(102, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:07:04'),
(103, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-10-14 11:07:17'),
(104, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:07:19'),
(105, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-10-14 11:07:28'),
(106, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '13 (ID: 13)', NULL, NULL, '2025-10-14 11:07:47'),
(107, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:07:50'),
(108, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-10-14 11:08:06'),
(109, 13, 'Kis Geri', 'kisgeri@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:08:21'),
(110, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:09:06'),
(111, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:09:17'),
(112, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:12:43'),
(113, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:12:50'),
(114, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:31:32'),
(115, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:31:36'),
(116, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:33:50'),
(117, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:33:57'),
(118, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 8', NULL, NULL, '2025-10-14 11:34:09'),
(119, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:36:06'),
(120, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:36:10'),
(121, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:48:11'),
(122, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:48:16'),
(123, 3, 'Martin', 'laka@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 11:50:48'),
(124, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 11:50:57'),
(125, 15, 'Martin Admin', 'demo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 12:04:27'),
(126, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 12:04:34'),
(127, 3, 'Martin', 'laka@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 12:05:52'),
(128, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 12:05:57'),
(129, 3, 'Martin', 'laka@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 12:12:01'),
(130, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 12:12:05'),
(131, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 9', NULL, NULL, '2025-10-14 12:12:37'),
(132, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 9', NULL, NULL, '2025-10-14 12:12:41'),
(133, 3, 'Martin', 'laka@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 12:16:26'),
(134, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 12:16:33'),
(135, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 12:52:52'),
(136, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-14 12:54:08'),
(137, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 13:38:02'),
(138, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 9', NULL, NULL, '2025-10-14 13:38:12'),
(139, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 9', NULL, NULL, '2025-10-14 13:38:42'),
(140, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 9', NULL, NULL, '2025-10-14 14:17:31'),
(141, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 13', NULL, NULL, '2025-10-14 14:18:17'),
(142, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 17', NULL, NULL, '2025-10-14 14:18:21'),
(143, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 18', NULL, NULL, '2025-10-14 14:19:15'),
(144, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 15', NULL, NULL, '2025-10-14 14:19:47'),
(145, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 16', NULL, NULL, '2025-10-14 14:20:07'),
(146, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 5', NULL, NULL, '2025-10-14 14:20:21'),
(147, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:2d9d:7025:8add:127b', 'Mozilla/5.0 (iPad; CPU OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.41 Mobile/15E148 Safari/604.1', '2025-10-14 18:26:29'),
(148, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:4132:3fc4:a37f:ef85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-14 18:50:02'),
(149, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 1003 - Inaktív', NULL, NULL, '2025-10-14 19:28:09'),
(150, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 105263846 - Inaktív', NULL, NULL, '2025-10-14 19:28:32'),
(151, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 1003 - Aktív', NULL, NULL, '2025-10-14 19:28:39'),
(152, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 105263846 - Aktív', NULL, NULL, '2025-10-14 19:28:39'),
(153, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: HMM-170 → Kártya ID: 1', NULL, NULL, '2025-10-14 19:29:53'),
(154, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya szerkesztése', 'Kártya ID: 575315265', NULL, NULL, '2025-10-14 19:30:25'),
(155, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés eltávolítása', 'Kapcsolat ID: 3', NULL, NULL, '2025-10-14 19:32:34'),
(156, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: SAA-234 → Kártya ID: 1', NULL, NULL, '2025-10-14 19:33:32'),
(157, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 321432432432', NULL, NULL, '2025-10-14 19:36:07'),
(158, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 3 → Alkalmazott ID: 13', NULL, NULL, '2025-10-14 19:42:39'),
(159, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 11)', NULL, NULL, '2025-10-14 19:44:22'),
(160, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Borsos Dani (Kapcsolat ID: 10)', NULL, NULL, '2025-10-14 19:44:30'),
(161, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 11 → Alkalmazott: Kis Geri', NULL, NULL, '2025-10-14 19:44:34'),
(162, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 9 → Alkalmazott: Kis Geri', NULL, NULL, '2025-10-14 19:44:37'),
(163, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 3 → Alkalmazott: jozsi', NULL, NULL, '2025-10-14 19:47:35'),
(164, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű törlése', 'Rendszám: SAA-234', NULL, NULL, '2025-10-14 19:48:00'),
(165, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű törlése', 'Rendszám: HMM-170', NULL, NULL, '2025-10-14 19:48:02'),
(166, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 342453254325435', NULL, NULL, '2025-10-14 19:49:36'),
(167, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 45435432134332432', NULL, NULL, '2025-10-14 19:49:49'),
(168, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 45435432134332432', NULL, NULL, '2025-10-14 19:50:07'),
(169, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 45435432134332432', NULL, NULL, '2025-10-14 19:50:13'),
(170, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 1212', NULL, NULL, '2025-10-14 19:54:22'),
(171, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 12)', NULL, NULL, '2025-10-14 19:55:00'),
(172, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: jozsi (Kapcsolat ID: 17)', NULL, NULL, '2025-10-14 19:55:07'),
(173, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: jozsi (Kapcsolat ID: 16)', NULL, NULL, '2025-10-14 19:55:10'),
(174, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: jozsi (Kapcsolat ID: 18)', NULL, NULL, '2025-10-14 19:55:12'),
(175, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:55:15'),
(176, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:55:20'),
(177, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:55:23'),
(178, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:55:25'),
(179, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 453432523432', NULL, NULL, '2025-10-14 19:55:38'),
(180, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 3423254324532432', NULL, NULL, '2025-10-14 19:55:52'),
(181, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 21)', NULL, NULL, '2025-10-14 19:55:56'),
(182, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 34243243223143', NULL, NULL, '2025-10-14 19:57:26'),
(183, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 22)', NULL, NULL, '2025-10-14 19:58:10'),
(184, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: lakatos (Kapcsolat ID: 15)', NULL, NULL, '2025-10-14 19:58:13'),
(185, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 20)', NULL, NULL, '2025-10-14 19:58:17'),
(186, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 13)', NULL, NULL, '2025-10-14 19:58:19'),
(187, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: jozsi (Kapcsolat ID: 14)', NULL, NULL, '2025-10-14 19:58:25'),
(188, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 1003', NULL, NULL, '2025-10-14 19:58:27'),
(189, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 575315265', NULL, NULL, '2025-10-14 19:58:29'),
(190, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:58:31'),
(191, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:58:32'),
(192, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 2147483647', NULL, NULL, '2025-10-14 19:58:34'),
(193, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 34243243223143', NULL, NULL, '2025-10-14 19:58:35'),
(194, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Martin Admin (Kapcsolat ID: 19)', NULL, NULL, '2025-10-14 19:58:40'),
(195, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 1212', NULL, NULL, '2025-10-14 19:58:42'),
(196, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 575315265', NULL, NULL, '2025-10-14 20:00:08'),
(197, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 38564486141755652', NULL, NULL, '2025-10-14 20:00:33'),
(198, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Lenhardt Károly (Kapcsolat ID: 1)', NULL, NULL, '2025-10-14 20:00:40'),
(199, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 105263846', NULL, NULL, '2025-10-14 20:00:43'),
(200, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 105263846', NULL, NULL, '2025-10-14 20:01:11'),
(201, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 123456', NULL, NULL, '2025-10-14 20:13:55'),
(202, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 123456 - Inaktív', NULL, NULL, '2025-10-14 20:14:18'),
(203, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 123456 - Aktív', NULL, NULL, '2025-10-14 20:14:49'),
(204, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: jozsi (Kapcsolat ID: 26)', NULL, NULL, '2025-10-14 20:15:26'),
(205, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya törlése', 'Kártya ID: 123456', NULL, NULL, '2025-10-14 20:15:27'),
(206, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 321432423', NULL, NULL, '2025-10-14 20:15:51'),
(207, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Inaktív', NULL, NULL, '2025-10-14 20:17:29'),
(208, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Aktív', NULL, NULL, '2025-10-14 20:17:30'),
(209, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 575315265 - Inaktív', NULL, NULL, '2025-10-14 20:17:33'),
(210, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-17 08:30:53'),
(211, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: jozsi (Kapcsolat ID: 27)', NULL, NULL, '2025-10-17 08:32:59'),
(212, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 24 → Alkalmazott: dezs?', NULL, NULL, '2025-10-17 08:33:16'),
(213, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: HMM-170 → Kártya ID: 21', NULL, NULL, '2025-10-17 08:33:56'),
(214, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: KYS-123 → Kártya ID: 21', NULL, NULL, '2025-10-17 08:34:06'),
(215, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Inaktív', NULL, NULL, '2025-10-17 08:34:44'),
(216, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Aktív', NULL, NULL, '2025-10-17 08:34:44'),
(217, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 575315265 - Aktív', NULL, NULL, '2025-10-17 08:35:32'),
(218, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Inaktív', NULL, NULL, '2025-10-17 08:35:32'),
(219, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 23', NULL, NULL, '2025-10-17 08:47:37'),
(220, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-17 11:49:45'),
(221, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: AIDS-001 → Kártya ID: 21', NULL, NULL, '2025-10-17 11:53:45'),
(222, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Aktív', NULL, NULL, '2025-10-17 11:54:06'),
(223, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Inaktív', NULL, NULL, '2025-10-17 11:54:12'),
(224, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 38564486141755652 - Aktív', NULL, NULL, '2025-10-17 11:54:16'),
(225, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 27', NULL, NULL, '2025-10-17 11:58:03'),
(226, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 26', NULL, NULL, '2025-10-17 11:58:11'),
(227, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 25', NULL, NULL, '2025-10-17 11:58:17'),
(228, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 24', NULL, NULL, '2025-10-17 11:58:24'),
(229, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 10:00:12'),
(230, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 45345654', NULL, NULL, '2025-10-21 10:01:19'),
(231, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Lenhardt Károly (Kapcsolat ID: 29)', NULL, NULL, '2025-10-21 10:01:25'),
(232, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 105263846 - Inaktív', NULL, NULL, '2025-10-21 10:01:39'),
(233, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 105263846 - Aktív', NULL, NULL, '2025-10-21 10:01:53'),
(234, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: 342-ghf → Kártya ID: 21', NULL, NULL, '2025-10-21 10:04:27'),
(235, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: 3463246 → Kártya ID: 21', NULL, NULL, '2025-10-21 10:04:33'),
(236, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű törlése', 'Rendszám: 3463246', NULL, NULL, '2025-10-21 10:04:38'),
(237, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű törlése', 'Rendszám: 342-ghf', NULL, NULL, '2025-10-21 10:04:50'),
(238, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 6', NULL, NULL, '2025-10-21 10:36:53'),
(239, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 5', NULL, NULL, '2025-10-21 10:36:55'),
(240, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 4', NULL, NULL, '2025-10-21 10:36:57'),
(241, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 2', NULL, NULL, '2025-10-21 10:36:59'),
(242, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 2', NULL, NULL, '2025-10-21 10:38:23'),
(243, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 10:41:16'),
(244, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új admin hozzáadása', 'lucaex - superadmin', NULL, NULL, '2025-10-21 10:45:10'),
(245, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 10:45:29'),
(246, 18, 'lucaex', 'szaron@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 10:45:40'),
(247, 18, 'lucaex', 'szaron@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 3', NULL, NULL, '2025-10-21 10:45:48'),
(248, 18, 'lucaex', 'szaron@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-21 10:46:02'),
(249, 18, 'lucaex', 'szaron@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-21 10:46:21'),
(250, 18, 'lucaex', 'szaron@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 10:46:24'),
(251, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 24', NULL, NULL, '2025-10-21 12:12:12'),
(252, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 24', NULL, NULL, '2025-10-21 12:12:41'),
(253, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 25', NULL, NULL, '2025-10-21 12:12:45'),
(254, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 23', NULL, NULL, '2025-10-21 12:12:46'),
(255, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 12:59:10'),
(256, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 13:27:45'),
(257, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 13:27:53'),
(258, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 13:28:51'),
(259, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '15 (ID: 15)', NULL, NULL, '2025-10-21 13:29:34'),
(260, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Alkalmazott szerkesztése', 'demo (ID: 16)', NULL, NULL, '2025-10-21 13:29:43'),
(261, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 13:44:27'),
(262, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 13:45:32'),
(263, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 13:45:40'),
(264, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 13:45:48'),
(265, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 13:45:54'),
(266, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 13:48:31'),
(267, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 14:03:19'),
(268, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '15 (ID: 15)', NULL, NULL, '2025-10-21 14:03:36'),
(269, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '15 (ID: 15)', NULL, NULL, '2025-10-21 14:11:05'),
(270, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:41:51'),
(271, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 14:42:31'),
(272, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:42:34'),
(273, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 14:42:43'),
(274, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:47:44'),
(275, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 14:47:53'),
(276, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:47:58'),
(277, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 14:48:02'),
(278, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:48:11'),
(279, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 14:48:14'),
(280, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:50:53'),
(281, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 14:50:57'),
(282, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:52:24'),
(283, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 14:52:31'),
(284, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 14:52:38'),
(285, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-21 14:52:45'),
(286, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 2', NULL, NULL, '2025-10-21 14:53:25'),
(287, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 1', NULL, NULL, '2025-10-21 14:53:29'),
(288, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e00e:c0a1:82b7:a45b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 17:58:50'),
(289, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 17:59:17'),
(290, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e00e:c0a1:82b7:a45b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 17:59:30'),
(291, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 18:14:40'),
(292, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e00e:c0a1:82b7:a45b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-21 18:34:04'),
(293, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-21 18:49:05'),
(294, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-22 10:06:14'),
(295, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 6', NULL, NULL, '2025-10-22 10:07:40'),
(296, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 6', NULL, NULL, '2025-10-22 10:16:55'),
(297, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 9', NULL, NULL, '2025-10-22 10:17:10'),
(298, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 7', NULL, NULL, '2025-10-22 10:17:16'),
(299, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 9', NULL, NULL, '2025-10-22 10:17:20'),
(300, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 3', NULL, NULL, '2025-10-22 10:17:25'),
(301, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új alkalmazott hozzáadása', 'Lacika', NULL, NULL, '2025-10-22 10:19:06'),
(302, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 123456', NULL, NULL, '2025-10-22 10:19:22');
INSERT INTO `admin_naplo` (`aid`, `admin_id`, `admin_nev`, `admin_email`, `muvelet`, `reszletek`, `ip_cim`, `user_agent`, `created_at`) VALUES
(303, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-22 10:21:14'),
(304, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-22 10:24:19'),
(305, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '3 (ID: 3)', NULL, NULL, '2025-10-22 10:25:02'),
(306, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-22 10:25:08'),
(307, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-22 10:26:29'),
(308, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: KYS-123 → Kártya ID: 26', NULL, NULL, '2025-10-22 10:28:22'),
(309, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '130.43.220.84', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-24 10:53:12'),
(310, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '77.110.158.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 17:33:15'),
(311, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-10-24 17:35:09'),
(312, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 12', NULL, NULL, '2025-10-24 17:35:36'),
(313, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-24 17:36:55'),
(314, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '77.110.158.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 17:37:52'),
(315, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '94.44.112.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-25 16:28:02'),
(316, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 13', NULL, NULL, '2025-10-25 16:28:12'),
(317, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 13', NULL, NULL, '2025-10-25 16:28:18'),
(318, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 12', NULL, NULL, '2025-10-25 16:28:22'),
(319, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 11', NULL, NULL, '2025-10-25 16:28:24'),
(320, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 10', NULL, NULL, '2025-10-25 16:28:27'),
(321, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 8', NULL, NULL, '2025-10-25 16:28:33'),
(322, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 10:59:09'),
(323, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 11:14:09'),
(324, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 11:14:29'),
(325, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:1c3e:e90f:4611:7045', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 11:15:39'),
(326, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 11:29:29'),
(327, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 11:30:11'),
(328, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 11:31:39'),
(329, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '89.134.19.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 11:31:56'),
(330, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 11:45:12'),
(331, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36 Edg/141.0.0.0', '2025-10-27 11:45:20'),
(332, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 11:47:32'),
(333, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '89.134.19.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 11:47:54'),
(334, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Lacika (Kapcsolat ID: 30)', NULL, NULL, '2025-10-27 11:59:16'),
(335, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 12:00:21'),
(336, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 12:02:55'),
(337, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 12:07:00'),
(338, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '89.134.19.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 12:10:56'),
(339, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 12:12:55'),
(340, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 3', NULL, NULL, '2025-10-27 12:13:11'),
(341, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: Kys → Kártya ID: 20', NULL, NULL, '2025-10-27 12:14:43'),
(342, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 12:25:57'),
(343, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:1c3e:e90f:4611:7045', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 12:29:08'),
(344, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:adc9:8624:1759:ab7c', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 13:39:45'),
(345, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 13:54:47'),
(346, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 13:58:21'),
(347, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 14:13:21'),
(348, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 14:14:08'),
(349, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:88ac:e2ea:dea8:5fa', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 14:14:50'),
(350, 3, 'Martin', 'kothenczmartin@gmail.com', 'Manuális kijelentkeztetés', 'Kis Geri - 2025-10-27T14:14 (Parkolás ID: 12)', NULL, NULL, '2025-10-27 14:15:08'),
(351, 3, 'Martin', 'kothenczmartin@gmail.com', 'Manuális kijelentkeztetés', 'Lenhardt Károly - 2025-10-27T14:23 (Parkolás ID: 11)', NULL, NULL, '2025-10-27 14:23:43'),
(352, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 14:29:31'),
(353, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 14:29:42'),
(354, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 14:33:28'),
(355, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '15 (ID: 15)', NULL, NULL, '2025-10-27 14:34:13'),
(356, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:88ac:e2ea:dea8:5fa', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 14:34:25'),
(357, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 14:44:56'),
(358, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 14:55:43'),
(359, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2133', NULL, NULL, '2025-10-27 14:57:32'),
(360, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kód törlése', 'Kód ID: 1', NULL, NULL, '2025-10-27 14:58:47'),
(361, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2234', NULL, NULL, '2025-10-27 14:59:10'),
(362, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 15:10:56'),
(363, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 15:11:43'),
(364, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1122 - Cég: 2', NULL, NULL, '2025-10-27 15:12:57'),
(365, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:203e:6346:6a01:702', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 15:13:39'),
(366, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 15:14:04'),
(367, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:203e:6346:6a01:702', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 15:14:22'),
(368, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1234 - Cég: 1', NULL, NULL, '2025-10-27 15:14:49'),
(369, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 15:26:56'),
(370, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 15:30:28'),
(371, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 4321 - Cég: 2', NULL, NULL, '2025-10-27 15:31:09'),
(372, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 38564486293381380', NULL, NULL, '2025-10-27 15:41:32'),
(373, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű hozzáadása', 'Rendszám: SAA-234 → Kártya ID: 27', NULL, NULL, '2025-10-27 15:41:50'),
(374, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 15:45:56'),
(375, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 15:47:20'),
(376, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kód törlése', 'Kód ID: 5', NULL, NULL, '2025-10-27 15:56:30'),
(377, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 3322 - Cég: 2', NULL, NULL, '2025-10-27 15:56:39'),
(378, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kód törlése', 'Kód ID: 6', NULL, NULL, '2025-10-27 15:56:46'),
(379, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 3322 - Cég: 2', NULL, NULL, '2025-10-27 15:56:57'),
(380, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 16:02:56'),
(381, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:bc06:d318:ec8:22d8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 16:06:28'),
(382, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kód törlése', 'Kód ID: 4', NULL, NULL, '2025-10-27 16:07:27'),
(383, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2231 - Cég: 1', NULL, NULL, '2025-10-27 16:07:42'),
(384, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2211 - Cég: 2', NULL, NULL, '2025-10-27 16:08:29'),
(385, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 16:21:56'),
(386, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '89.134.19.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 16:28:01'),
(387, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1112 - Cég: 2', NULL, NULL, '2025-10-27 16:29:23'),
(388, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-27 16:43:56'),
(389, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:5c62:cead:69d5:690f', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-10-27 21:36:47'),
(390, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:04:08'),
(391, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:06:35'),
(392, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:06:50'),
(393, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:07:14'),
(394, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:07:19'),
(395, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:07:28'),
(396, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:08:47'),
(397, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:08:49'),
(398, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:10:00'),
(399, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:10:01'),
(400, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:12:23'),
(401, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:27:23'),
(402, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:49aa:9340:9a71:ddb2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 18:27:31'),
(403, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kis Geri (Kapcsolat ID: 25)', NULL, NULL, '2025-10-28 18:31:11'),
(404, 3, 'Martin', 'kothenczmartin@gmail.com', 'Manuális kijelentkeztetés', ' - 2025-10-28T18:31 (Parkolás ID: 12)', NULL, NULL, '2025-10-28 18:31:21'),
(405, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1233 - Cég: 2', NULL, NULL, '2025-10-28 18:32:23'),
(406, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1233 - Cég: 2', NULL, NULL, '2025-10-28 18:34:14'),
(407, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1233 - Cég: 2', NULL, NULL, '2025-10-28 18:37:12'),
(408, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1233 - Cég: 2', NULL, NULL, '2025-10-28 18:41:07'),
(409, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-10-28 18:42:33'),
(410, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 08:43:26'),
(411, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-03 08:59:13'),
(412, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 09:11:21'),
(413, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jármű törlése', 'Rendszám: Kys', NULL, NULL, '2025-11-03 09:17:06'),
(414, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 11:35:14'),
(415, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2222 - Cég: 1', NULL, NULL, '2025-11-03 11:39:02'),
(416, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-03 11:50:58'),
(417, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 11:51:39'),
(418, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 11:51:49'),
(419, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-11-03 11:52:12'),
(420, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Alkalmazott szerkesztése', 'jozsi (ID: 17)', NULL, NULL, '2025-11-03 11:53:13'),
(421, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-03 12:06:58'),
(422, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:e0f8:f9:6ae5:f8b6', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-11-03 23:34:22'),
(423, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '94.44.252.90', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', '2025-11-04 08:30:49'),
(424, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 08:32:17'),
(425, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 3333 - Cég: 2', NULL, NULL, '2025-11-04 08:32:44'),
(426, 3, 'Martin', 'kothenczmartin@gmail.com', 'Jelszó szerkesztése', '15 (ID: 15)', NULL, NULL, '2025-11-04 08:40:18'),
(427, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-11-04 08:40:23'),
(428, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új alkalmazott hozzáadása', 'igazgato', NULL, NULL, '2025-11-04 08:44:51'),
(429, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új alkalmazott hozzáadása', 'igazgato', NULL, NULL, '2025-11-04 08:44:57'),
(430, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 12345678910', NULL, NULL, '2025-11-04 08:46:03'),
(431, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 12345678910 - Inaktív', NULL, NULL, '2025-11-04 08:46:14'),
(432, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya státusz váltás', 'Kártya ID: 12345678910 - Aktív', NULL, NULL, '2025-11-04 08:46:17'),
(433, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 08:47:19'),
(434, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 08:49:02'),
(435, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 08:49:09'),
(436, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 08:55:25'),
(437, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 08:58:39'),
(438, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2312 - Cég: 2', NULL, NULL, '2025-11-04 08:59:12'),
(439, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 09:54:20'),
(440, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1232 - Cég: 2', NULL, NULL, '2025-11-04 09:55:47'),
(441, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 10:09:39'),
(442, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 10:09:49'),
(443, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 10:12:50'),
(444, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 10:13:01'),
(445, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 10:13:14'),
(446, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 10:13:24'),
(447, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 10:28:57'),
(448, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 10:40:55'),
(449, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 1132 - Cég: 2', NULL, NULL, '2025-11-04 10:41:45'),
(450, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-04 10:55:57'),
(451, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-05 13:50:14'),
(452, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kód törlése', 'Kód ID: 8', NULL, NULL, '2025-11-05 13:50:21'),
(453, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-05 14:05:22'),
(454, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-11 10:03:01'),
(455, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-11 10:18:50'),
(456, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-11 13:48:21'),
(457, 3, 'Martin', 'kothenczmartin@gmail.com', 'Manuális kijelentkeztetés', 'Kis Geri - 2025-11-17T13:49 (Parkolás ID: 25)', NULL, NULL, '2025-11-11 13:49:34'),
(458, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-11 14:03:23'),
(459, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-11 14:11:25'),
(460, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új alkalmazott hozzáadása', 'Kovács László Bálint', NULL, NULL, '2025-11-11 14:12:27'),
(461, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kártya hozzáadása', 'Kártya ID: 91054841', NULL, NULL, '2025-11-11 14:12:45'),
(462, 3, 'Martin', 'kothenczmartin@gmail.com', 'Alkalmazott szerkesztése', 'Kovacs Laszlo Balint (ID: 22)', NULL, NULL, '2025-11-11 14:15:06'),
(463, 3, 'Martin', 'kothenczmartin@gmail.com', 'Manuális kijelentkeztetés', 'Kovacs Laszlo Balint - 2025-11-11T14:16 (Parkolás ID: 32)', NULL, NULL, '2025-11-11 14:16:42'),
(464, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-11 14:26:25'),
(465, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-19 10:00:47'),
(466, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 16', NULL, NULL, '2025-11-19 10:00:58'),
(467, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 17', NULL, NULL, '2025-11-19 10:01:45'),
(468, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 17', NULL, NULL, '2025-11-19 10:01:51'),
(469, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 15', NULL, NULL, '2025-11-19 10:08:17'),
(470, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 15', NULL, NULL, '2025-11-19 10:10:19'),
(471, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 18', NULL, NULL, '2025-11-19 10:10:57'),
(472, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 18', NULL, NULL, '2025-11-19 10:11:06'),
(473, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 18', NULL, NULL, '2025-11-19 10:11:11'),
(474, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 18', NULL, NULL, '2025-11-19 10:11:37'),
(475, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-11-19 10:16:08'),
(476, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-19 10:23:47'),
(477, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 20', NULL, NULL, '2025-11-19 10:24:10'),
(478, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 20', NULL, NULL, '2025-11-19 10:24:27'),
(479, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés szerkesztése', 'Értékelés ID: 20', NULL, NULL, '2025-11-19 10:36:03'),
(480, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-08 11:07:27'),
(481, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-15 11:07:21'),
(482, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2025-12-15 11:07:27'),
(483, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-12-15 11:23:17'),
(484, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 09:54:01'),
(485, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2025-12-16 10:09:03'),
(486, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2026-01-13 11:05:36'),
(487, 1, 'Lenhardt Károly', 'karcsivideo@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-01-13 11:09:03'),
(488, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-02 11:23:52'),
(489, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kötelező jelszó beállítás', 'Saját jelszó beállítva', NULL, NULL, '2026-02-02 11:23:59'),
(490, 3, 'Martin', 'kothenczmartin@gmail.com', 'Értékelés törlése', 'Értékelés ID: 22', NULL, NULL, '2026-02-02 11:24:21'),
(491, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-02 11:39:41'),
(492, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 10:08:58'),
(493, 3, 'Martin', 'kothenczmartin@gmail.com', 'Új kód hozzáadása', 'Kód: 2232 - Cég: 1', NULL, NULL, '2026-02-03 10:23:45'),
(494, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kód törlése', 'Kód ID: 20', NULL, NULL, '2026-02-03 10:23:49'),
(495, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 10:27:44'),
(496, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-03 10:29:18'),
(497, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 10:29:25'),
(498, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-03 10:44:51'),
(499, 15, 'Martin Admin', 'martinadmin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 10:53:31'),
(500, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 12:13:14'),
(501, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-03 12:28:31'),
(502, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 13:59:26'),
(503, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-03 13:59:27'),
(504, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-03 13:59:51'),
(505, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-03 14:15:51'),
(506, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:d14a:a002:332b:6e76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-04 18:32:27'),
(507, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 22 → Alkalmazott: Kovacs Laszlo Balint', NULL, NULL, '2026-02-04 18:33:20'),
(508, 3, 'Martin', 'kothenczmartin@gmail.com', 'Tulajdonos törlése kártyáról', 'Tulajdonos: Kovacs Laszlo Balint (Kapcsolat ID: 34)', NULL, NULL, '2026-02-04 18:34:37'),
(509, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '2a02:ab88:2b89:4600:d14a:a002:332b:6e76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-04 18:35:35'),
(510, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kártya hozzárendelés', 'Kártya ID: 22 → Alkalmazott: Kovacs Laszlo Balint', NULL, NULL, '2026-02-04 18:35:44'),
(511, 3, 'Martin', 'kothenczmartin@gmail.com', 'Manuális kijelentkeztetés', 'Kovacs Laszlo Balint - 2026-02-04T18:37 (Parkolás ID: 34)', NULL, NULL, '2026-02-04 18:37:18'),
(512, 3, 'Martin', 'kothenczmartin@gmail.com', 'Kijelentkezés', 'Sikeres kijelentkezés', NULL, NULL, '2026-02-04 18:50:35'),
(513, 3, 'Martin', 'kothenczmartin@gmail.com', 'Bejelentkezés', 'Sikeres bejelentkezés', '195.199.251.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 13:01:36');

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
  `ejelszo` varchar(255) DEFAULT NULL,
  `efelhasz` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `ember`
--

INSERT INTO `ember` (`eid`, `enev`, `email`, `etel`, `estatuszdolg`, `ekomment`, `jogosultsag`, `cid`, `ejelszo`, `efelhasz`) VALUES
(1, 'Lenhardt Károly', 'karcsivideo@gmail.com', '063125141652', 1, 'Kristályos dolgozó', 'superadmin', 2, '$2y$10$AnaVjwPq73Y5WboWXuNHyu/z6ZypafZZ5lGCz.0K5rNWLib9ww2zq', 'df'),
(3, 'Martin', 'kothenczmartin@gmail.com', '2341344', 1, 'Új alkalmazott', 'superadmin', 1, '$2y$10$SrFJyI9lz0Gci3zVfkAZVuDBsb8p/Rv7/6PSGuCVo1iODjorjdx2u', 'km25'),
(9, 'lakatos', 'laka@gmail.com', '2323136213', 1, 'Új alkalmazott - igazi hazafi, nagyon szorgalmas', 'user', 2, NULL, 'szkoti'),
(12, 'Borsos Dani', 'borosdani@gmail.com', '23213213', 1, 'Admin felhasználó', 'admin', 1, '$2y$10$9.bA7I9KZdz.RoGm/7HTzubxEtg/KM1ESwlNYYZaIJg5ge6qW2e9e', NULL),
(13, 'Kis Geri', 'kisgeri@gmail.com', '23443543543', 1, 'Új alkalmazott: Eddig rendes 10.09', 'admin', 2, '$2y$10$i7SNViiXzXnwAqbR1J/a2ePisSciADFocnGtWMZLhxS59PAxzZ9T6', 'km'),
(14, 'dezs?', 'nagydezso@gmail.com', 'nincs olyan', 1, 'Új alkalmazott - a sztárigazolás, nincs bírás vele', 'user', 2, NULL, NULL),
(15, 'Martin Admin', 'martinadmin@gmail.com', '342343242342', 1, 'Admin felhasználó', 'admin', 1, NULL, NULL),
(16, 'demo', 'demo@gmail.com', '236273', 1, 'Új alkalmazott', 'user', 1, NULL, NULL),
(17, 'jozsi', '4e32423@gmail.com', '34342324', 1, 'Admin felhasználó - Karcsi rabszolgája', 'admin', 10, '$2y$10$ii5rlEGtr0dpTBuZz9/rnOf/YnE2Gz1S5SNO4YiqsWHn83Dy.W2PG', 'jozsi'),
(19, 'Lacika', 'laci@gmail.com', '3242543534', 1, 'Új alkalmazott', 'user', 2, NULL, 'ciganygyilkos'),
(20, 'igazgato', 'igazgato@gmail.com', '233243243243', 1, 'Új alkalmazott', 'user', 2, NULL, NULL),
(21, 'igazgato', 'igazgato@gmail.com', '233243243243', 1, 'Új alkalmazott', 'user', 2, NULL, 'igazgato'),
(22, 'Kovacs Laszlo Balint', 'dfdfdfbdufd@gmail.com', '343543534454', 1, 'Új alkalmazott', 'user', 2, NULL, 'László');

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
(3, 'vv', 4, 'Kiváló szolgáltatás!', '2025-10-21 18:05:06'),
(7, 'ssssssssssss', 5, 'Kiváló szolgáltatás!', '2025-10-22 10:09:07'),
(9, 'dfgfdgdf', 5, 'ffffffff', '2025-10-22 10:10:18'),
(14, '????????', 5, 'szupi az oldal', '2025-11-03 11:38:28'),
(15, 'Lakatos Krisztián', 5, 'Kiváló szolgáltatás more', '2025-11-03 11:53:36'),
(19, 'Szalai Bálint Zsolt', 2, 'utalom az oldalt az egesz szar,szarul van megcsinálva,drága nem tetszik és cigány vagyok', '2025-11-19 10:11:28'),
(18, 'Tiszlavicz Patrik', 5, 'gyere gym', '2025-11-19 10:09:57'),
(20, 'Áron - megfogom magamat olni', 1, 'luca massal van,ongyilkos leszek vegzek magammal elegem van', '2025-11-19 10:21:49'),
(21, 'Szalai Bálint Zsolt', 4, 'szet fogok mindenkit szurkalni a weissbe,mindekit kirablok mert egy brazil cserediak vagyok', '2025-11-19 10:35:52'),
(23, 'Elementalisták', 3, 'Nem rossz, kevesebb emoji kéne az oldalra és több fontawesome ikon', '2025-12-11 09:25:30'),
(24, 'fgnvjfgv', 5, 'fjgnfgfdgdfgdfgfd', '2026-02-06 12:15:24'),
(25, 'erwwer', 5, 'fgdgdfgfdgbbvc10', '2026-02-06 12:42:55');

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
  `kazonosito` bigint(20) DEFAULT NULL,
  `kallapot` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `kartya`
--

INSERT INTO `kartya` (`kid`, `kazonosito`, `kallapot`) VALUES
(20, 575315265, 1),
(21, 38564486141755652, 1),
(22, 105263846, 1),
(24, 321432423, 1),
(25, 45345654, 1),
(26, 123456, 1),
(27, 38564486293381380, 1),
(28, 12345678910, 1),
(29, 91054841, 1);

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
(2, 2, 2),
(4, 4, 4),
(23, 20, 3),
(24, 21, 1),
(28, 24, 14),
(31, 27, 13),
(32, 28, 21),
(33, 29, 22),
(35, 22, 22);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kodok`
--

CREATE TABLE `kodok` (
  `id` int(11) NOT NULL,
  `kod` varchar(4) NOT NULL,
  `hasznalt` tinyint(4) DEFAULT 0,
  `keszitve` timestamp NULL DEFAULT current_timestamp(),
  `felhasznalva` timestamp NULL DEFAULT NULL,
  `lejarat` date DEFAULT NULL,
  `keszitette` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- A tábla adatainak kiíratása `kodok`
--

INSERT INTO `kodok` (`id`, `kod`, `hasznalt`, `keszitve`, `felhasznalva`, `lejarat`, `keszitette`, `cid`) VALUES
(2, '2234', 1, '2025-10-27 13:59:10', '2025-10-27 13:59:43', '2025-10-28', 3, NULL),
(7, '3322', 1, '2025-10-27 14:56:57', '2025-10-27 14:57:45', '2025-10-29', 3, 2),
(9, '2211', 1, '2025-10-27 15:08:29', '2025-10-27 15:08:53', '2025-10-28', 3, 2),
(10, '1112', 1, '2025-10-27 15:29:23', '2025-10-27 15:29:38', '2025-10-29', 3, 2),
(14, '1233', 1, '2025-10-28 17:41:07', '2025-10-28 17:41:24', '2025-10-29', 3, 2),
(15, '2222', 0, '2025-11-03 10:39:02', NULL, '2025-11-05', 3, 1),
(16, '3333', 1, '2025-11-04 07:32:44', '2025-11-04 07:35:00', '2025-11-05', 3, 2),
(17, '2312', 1, '2025-11-04 07:59:12', '2025-11-04 08:01:08', '2025-11-06', 3, 2),
(18, '1232', 1, '2025-11-04 08:55:47', '2025-11-04 09:10:33', '2025-11-29', 3, 2),
(19, '1132', 1, '2025-11-04 09:41:45', '2025-11-04 09:42:36', '2025-11-06', 3, 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `naplo`
--

CREATE TABLE `naplo` (
  `nid` int(11) NOT NULL,
  `keid` int(11) DEFAULT NULL,
  `ejid` int(11) DEFAULT NULL,
  `nidotol` datetime DEFAULT NULL,
  `nidoig` datetime DEFAULT NULL,
  `nparkhely` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `naplo`
--

INSERT INTO `naplo` (`nid`, `keid`, `ejid`, `nidotol`, `nidoig`, `nparkhely`) VALUES
(1, 1, NULL, '2025-10-14 08:00:00', '2025-10-01 13:45:15', 101),
(2, 2, NULL, '2025-10-14 09:15:00', '2025-10-14 17:30:00', 102),
(3, 9, NULL, '2025-10-14 18:29:11', '2025-10-15 18:32:29', NULL),
(4, 9, NULL, '2025-10-14 18:34:05', '2025-10-01 13:45:13', 10),
(5, 24, NULL, '2025-10-14 20:05:33', '2025-10-20 18:32:37', 69),
(6, 25, NULL, '2025-10-14 20:07:08', '2025-10-14 20:11:39', 66),
(7, 25, NULL, '2025-10-14 20:12:23', '2025-10-01 13:45:09', NULL),
(8, 23, NULL, '2025-10-14 20:17:08', '2025-10-01 13:45:06', NULL),
(9, 24, NULL, '2025-10-21 18:32:45', '2025-10-08 13:45:04', 2),
(10, 30, NULL, '2025-10-21 10:20:46', '2025-10-27 14:20:16', 420),
(11, 24, NULL, '2025-10-25 13:45:17', '2025-10-27 14:23:00', 30),
(12, 25, NULL, '2025-10-25 13:45:45', '2025-10-28 18:31:00', 3),
(13, 31, NULL, '2025-10-27 15:46:36', '2025-10-27 15:46:57', 0),
(14, 31, NULL, '2025-10-27 15:47:31', '2025-10-27 15:47:53', 0),
(15, 31, NULL, '2025-10-27 15:48:11', '2025-10-27 15:48:51', 0),
(16, 31, NULL, '2025-10-27 15:54:41', '2025-10-27 15:55:35', 0),
(17, 31, NULL, '2025-10-27 16:15:35', '2025-10-27 16:16:20', 0),
(18, 31, NULL, '2025-10-27 16:17:04', '2025-10-27 16:24:00', 0),
(19, 31, NULL, '2025-10-27 16:27:50', '2025-10-27 16:28:53', 0),
(20, 31, NULL, '2025-10-27 16:31:13', '2025-10-28 18:27:17', 0),
(21, 31, NULL, '2025-10-28 18:28:26', '2025-10-28 18:34:18', 8),
(22, 31, NULL, '2025-10-28 18:37:40', '2025-11-03 23:32:36', NULL),
(23, 31, NULL, '2025-11-03 23:33:19', '2025-11-03 23:33:31', NULL),
(24, 31, NULL, '2025-11-03 23:34:38', '2025-11-03 23:36:47', NULL),
(25, 31, NULL, '2025-11-11 13:49:12', '2025-11-17 13:49:00', 23),
(26, 31, NULL, '2025-11-11 13:53:11', '2025-11-11 13:54:38', NULL),
(27, 31, NULL, '2025-11-11 14:01:37', '2025-11-11 14:03:18', NULL),
(28, 31, NULL, '2025-11-11 14:04:22', '2025-11-11 14:06:23', NULL),
(29, 31, NULL, '2025-11-11 14:07:22', '2025-11-11 14:08:53', NULL),
(30, 31, NULL, '2025-11-11 14:10:49', '2025-11-11 14:11:09', NULL),
(31, 33, NULL, '2025-11-11 14:12:56', '2025-11-11 14:15:25', 12),
(32, 33, NULL, '2025-11-11 14:16:31', '2025-11-11 14:16:00', 34),
(33, 35, NULL, '2026-02-04 18:35:48', '2026-02-04 18:36:47', NULL),
(34, 35, NULL, '2026-02-04 18:37:07', '2026-02-04 18:37:00', NULL),
(35, 35, NULL, '2026-02-04 18:37:25', '2026-02-04 18:40:49', NULL),
(36, 35, NULL, '2026-02-04 18:41:10', '2026-02-04 18:43:09', NULL),
(37, 35, NULL, '2026-02-04 18:45:15', '2026-02-04 18:46:06', NULL),
(38, 35, NULL, '2026-02-04 18:47:32', '2026-02-04 19:06:36', NULL),
(39, 35, NULL, '2026-02-04 19:07:25', '2026-02-04 19:07:44', NULL),
(40, 35, NULL, '2026-02-04 19:08:16', '2026-02-04 19:09:22', NULL),
(41, 35, NULL, '2026-02-04 19:09:44', '2026-02-04 19:10:27', NULL),
(42, 35, NULL, '2026-02-04 19:15:37', '2026-02-04 19:16:08', NULL),
(43, 35, NULL, '2026-02-04 19:17:04', '2026-02-04 19:37:10', NULL),
(44, 35, NULL, '2026-02-04 19:38:23', '2026-02-04 19:44:33', NULL),
(45, 35, NULL, '2026-02-04 19:44:56', NULL, NULL);

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
-- A tábla indexei `kodok`
--
ALTER TABLE `kodok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kod` (`kod`);

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
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;

--
-- AUTO_INCREMENT a táblához `ceg`
--
ALTER TABLE `ceg`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `ember`
--
ALTER TABLE `ember`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT a táblához `emberjarmu`
--
ALTER TABLE `emberjarmu`
  MODIFY `ejid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `ertekeles`
--
ALTER TABLE `ertekeles`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT a táblához `jarmu`
--
ALTER TABLE `jarmu`
  MODIFY `jid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `kartya`
--
ALTER TABLE `kartya`
  MODIFY `kid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT a táblához `kartyaember`
--
ALTER TABLE `kartyaember`
  MODIFY `keid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT a táblához `kodok`
--
ALTER TABLE `kodok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `naplo`
--
ALTER TABLE `naplo`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
