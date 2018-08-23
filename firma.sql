-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 22 Sie 2018, 19:15
-- Wersja serwera: 10.1.31-MariaDB
-- Wersja PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `firma`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `id_pracownika` int(11) NOT NULL,
  `imie` varchar(20) NOT NULL,
  `nazwisko` varchar(40) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `pracownicy`
--

INSERT INTO `pracownicy` (`id_pracownika`, `imie`, `nazwisko`, `username`, `password`) VALUES
(1, 'Jan', 'Kowalski', 'jkowal', 'zaq12wsx'),
(2, 'Anna', 'Nowak', 'anowak', 'zaq12wsx'),
(3, 'Olaf', 'Wojdziak', 'owojdz', 'zaq12wsx'),
(4, 'Kubuś', 'Puchatek', 'kpucha', 'zaq12wsx'),
(5, 'admin', 'admin', 'admin', 'zaq12wsx');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacje`
--

CREATE TABLE `rezerwacje` (
  `id_rezerwacji` int(11) NOT NULL,
  `data` date NOT NULL,
  `czas_start` time NOT NULL,
  `czas_stop` time NOT NULL,
  `id_pracownika` int(11) NOT NULL,
  `nazwa_sali` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `rezerwacje`
--

INSERT INTO `rezerwacje` (`id_rezerwacji`, `data`, `czas_start`, `czas_stop`, `id_pracownika`, `nazwa_sali`) VALUES
(85, '2018-07-20', '09:00:00', '11:00:00', 1, 'Biała'),
(86, '2018-07-20', '11:30:00', '14:00:00', 1, 'Czerwona'),
(87, '2018-07-20', '10:00:00', '12:00:00', 1, 'Zielona'),
(88, '2018-07-20', '07:30:00', '09:00:00', 1, 'Niebieska'),
(91, '2018-07-21', '07:00:00', '08:00:00', 4, 'Czerwona'),
(92, '2018-07-20', '09:00:00', '10:30:00', 1, 'Szara'),
(93, '2018-07-22', '11:00:00', '12:00:00', 1, 'Biała'),
(94, '2018-07-22', '07:00:00', '08:00:00', 1, 'Czerwona'),
(95, '2018-07-22', '07:00:00', '08:00:00', 1, 'Szara'),
(98, '2018-07-22', '07:00:00', '08:00:00', 2, 'Biała'),
(99, '2018-07-22', '07:00:00', '08:00:00', 1, 'Zielona'),
(100, '2018-07-22', '07:00:00', '09:00:00', 1, 'Niebieska'),
(101, '2018-07-22', '13:00:00', '16:00:00', 4, 'Niebieska'),
(109, '2018-07-24', '07:00:00', '08:00:00', 4, 'Czerwona'),
(110, '2018-07-24', '07:00:00', '13:00:00', 4, 'Niebieska'),
(111, '2018-07-27', '07:00:00', '08:30:00', 4, 'Czerwona'),
(113, '2018-07-27', '13:00:00', '16:00:00', 4, 'Czerwona'),
(114, '2018-07-27', '09:30:00', '10:30:00', 4, 'Czerwona'),
(115, '2018-07-28', '07:00:00', '08:00:00', 4, 'Zielona'),
(117, '2018-07-28', '13:00:00', '16:00:00', 3, 'Niebieska'),
(118, '2018-08-16', '08:00:00', '11:00:00', 4, 'Zielona'),
(120, '2018-08-16', '07:00:00', '09:00:00', 4, 'Szara'),
(121, '2018-08-16', '07:00:00', '10:30:00', 1, 'Czerwona'),
(122, '2018-08-17', '07:00:00', '08:00:00', 1, 'Czerwona'),
(188, '2018-08-18', '10:00:00', '11:00:00', 1, 'Czerwona'),
(190, '2018-08-18', '07:30:00', '09:00:00', 1, 'Szara'),
(192, '2018-08-19', '08:00:00', '09:00:00', 1, 'Zielona'),
(193, '2018-08-19', '07:30:00', '09:00:00', 1, 'Czerwona'),
(194, '2018-08-19', '10:00:00', '12:00:00', 1, 'Zielona'),
(195, '2018-08-19', '09:30:00', '10:00:00', 1, 'Czerwona'),
(196, '2018-08-19', '10:00:00', '10:30:00', 1, 'Czerwona'),
(198, '2018-08-19', '10:30:00', '11:00:00', 1, 'Czerwona'),
(199, '2018-08-19', '09:00:00', '09:30:00', 1, 'Czerwona'),
(200, '2018-08-19', '11:00:00', '12:00:00', 1, 'Czerwona'),
(201, '2018-08-19', '07:00:00', '08:00:00', 1, 'Biała'),
(202, '2018-08-19', '08:30:00', '10:00:00', 1, 'Biała'),
(203, '2018-08-19', '12:00:00', '14:00:00', 1, 'Biała'),
(204, '2018-08-19', '11:30:00', '12:00:00', 1, 'Biała'),
(205, '2018-08-19', '10:00:00', '10:30:00', 1, 'Biała'),
(206, '2018-08-19', '07:00:00', '08:30:00', 1, 'Niebieska'),
(207, '2018-08-19', '11:00:00', '11:30:00', 1, 'Biała'),
(208, '2018-08-19', '11:00:00', '12:00:00', 1, 'Niebieska');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sale`
--

CREATE TABLE `sale` (
  `id_sali` int(11) NOT NULL,
  `nazwa_sali` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `sale`
--

INSERT INTO `sale` (`id_sali`, `nazwa_sali`) VALUES
(1, 'Czerwona'),
(2, 'Szara'),
(3, 'Biała'),
(4, 'Zielona'),
(5, 'Niebieska');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`id_pracownika`);

--
-- Indeksy dla tabeli `rezerwacje`
--
ALTER TABLE `rezerwacje`
  ADD PRIMARY KEY (`id_rezerwacji`);

--
-- Indeksy dla tabeli `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id_sali`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `id_pracownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `rezerwacje`
--
ALTER TABLE `rezerwacje`
  MODIFY `id_rezerwacji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT dla tabeli `sale`
--
ALTER TABLE `sale`
  MODIFY `id_sali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
