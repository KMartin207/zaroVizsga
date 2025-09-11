-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Sze 11. 14:59
-- Kiszolgáló verziója: 10.4.6-MariaDB
-- PHP verzió: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `parkolorendszer`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admin`
--

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `afelhasz` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `ajelszo` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ember`
--

CREATE TABLE `ember` (
  `eid` int(11) NOT NULL,
  `enev` varchar(50) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `etel` varchar(12) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `estatuszdolg` tinyint(4) DEFAULT NULL,
  `ekomment` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `emberjarmu`
--

CREATE TABLE `emberjarmu` (
  `ejid` int(11) NOT NULL,
  `eid` int(11) DEFAULT NULL,
  `jid` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jarmu`
--

CREATE TABLE `jarmu` (
  `jid` int(11) NOT NULL,
  `jrendszam` varchar(8) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `jtipus` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `jszin` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `jkomment` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kartya`
--

CREATE TABLE `kartya` (
  `kid` int(11) NOT NULL,
  `kallapot` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kartyaember`
--

CREATE TABLE `kartyaember` (
  `keid` int(11) NOT NULL,
  `kid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

--
-- A tábla indexei `ember`
--
ALTER TABLE `ember`
  ADD PRIMARY KEY (`eid`);

--
-- A tábla indexei `emberjarmu`
--
ALTER TABLE `emberjarmu`
  ADD PRIMARY KEY (`ejid`),
  ADD KEY `eid` (`eid`),
  ADD KEY `jid` (`jid`);

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
  ADD PRIMARY KEY (`keid`),
  ADD KEY `kid` (`kid`),
  ADD KEY `eid` (`eid`);

--
-- A tábla indexei `naplo`
--
ALTER TABLE `naplo`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `keid` (`keid`),
  ADD KEY `ejid` (`ejid`);

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `emberjarmu`
--
ALTER TABLE `emberjarmu`
  ADD CONSTRAINT `emberjarmu_ibfk_1` FOREIGN KEY (`eid`) REFERENCES `ember` (`eid`),
  ADD CONSTRAINT `emberjarmu_ibfk_2` FOREIGN KEY (`jid`) REFERENCES `jarmu` (`jid`);

--
-- Megkötések a táblához `kartyaember`
--
ALTER TABLE `kartyaember`
  ADD CONSTRAINT `kartyaember_ibfk_1` FOREIGN KEY (`kid`) REFERENCES `kartya` (`kid`),
  ADD CONSTRAINT `kartyaember_ibfk_2` FOREIGN KEY (`eid`) REFERENCES `ember` (`eid`);

--
-- Megkötések a táblához `naplo`
--
ALTER TABLE `naplo`
  ADD CONSTRAINT `naplo_ibfk_1` FOREIGN KEY (`keid`) REFERENCES `kartyaember` (`keid`),
  ADD CONSTRAINT `naplo_ibfk_2` FOREIGN KEY (`ejid`) REFERENCES `emberjarmu` (`ejid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
