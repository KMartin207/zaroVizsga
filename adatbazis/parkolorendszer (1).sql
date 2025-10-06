-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Okt 06. 10:48
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
(-1, 'proparking.hu');

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
  `cid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- A tábla adatainak kiíratása `ember`
--

INSERT INTO `ember` (`eid`, `enev`, `email`, `etel`, `estatuszdolg`, `ekomment`, `jogosultsag`, `cid`) VALUES
(1, 'Lenhardt Károly', 'karcsivideo@gmail.com', '063125141652', 1, 'Kristályos dolgozó', 'superadmin', -1),
(2, 'Kothencz Martin', 'kothenczmartin111@gmail.com', '063163527352', 1, 'Jó', 'admin', 1);

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

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kartyaember`
--

CREATE TABLE `kartyaember` (
  `keid` int(11) NOT NULL,
  `kid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
-- Indexek a kiírt táblákhoz
--

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
-- AUTO_INCREMENT a táblához `ceg`
--
ALTER TABLE `ceg`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `ember`
--
ALTER TABLE `ember`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `emberjarmu`
--
ALTER TABLE `emberjarmu`
  MODIFY `ejid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jarmu`
--
ALTER TABLE `jarmu`
  MODIFY `jid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `kartya`
--
ALTER TABLE `kartya`
  MODIFY `kid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `kartyaember`
--
ALTER TABLE `kartyaember`
  MODIFY `keid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `naplo`
--
ALTER TABLE `naplo`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
