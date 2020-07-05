-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 05 Lip 2020, 22:24
-- Wersja serwera: 8.0.13
-- Wersja PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `todo`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tasks` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `account`
--

INSERT INTO `account` (`id`, `user_id`, `tasks`) VALUES
(15, 37, '{\"done\": [\"Task 1\"], \"current\": [\"Task 2\", \"Task 3\"], \"2020-07-16\": [\"Task 5\"], \"2020-07-30\": [\"Task 4\"]}'),
(16, 38, '{\"done\": [\"dasdasd\", \"ad\", \"das\", \"s\"], \"current\": [\"sdadasd\", \"das\", \"dsad\"]}'),
(17, 39, '{\"done\": [\"ads\"], \"current\": [\"ds\"], \"2019-05-29\": [\"ads\"], \"2019-05-30\": [\"sad\"]}'),
(18, 40, '{\"done\": [\"ads\"], \"current\": [\"ds\"]}');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `token_exp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `password`, `email`, `auth_token`, `token`, `token_exp`) VALUES
(37, '$2y$10$OoBZrqoRYbi4viUEvf5KaeNVdSHXnK/F4/Afo4qK9ew0AdMT5X2hC', 'kozera@buziaczek.pl', 'a25e96eab55e423bee95ece87c0c208521d068a36d539e23a1d8b6e7941c7c76', NULL, NULL),
(38, '$2y$10$9f14RFt6itJ1MiX7HW1Dkecq4wuGFxV3DX9twTspJ1FAjAOkCYrdy', 'majkon@spoko.pl', '27476b986053cf1c3daf4d8ff762bc092c3cdda46f0b58c1e6a10e1ef2536736', NULL, NULL),
(39, '$2y$10$fuZfjZp9rLC8SRCVkDzSbeKhv3/Q0suNBDAo9AyOii5JVg8TCL2yS', 'grako@buziaczek.pl', '429ee443c608cc32691bd9ade171f08001cd709f1a1318127e656ff9d98a5755', NULL, NULL),
(40, '$2y$10$/S3LJhUBoUyIeS.xIsiAn.At.sl5IP2CF0aHL22rM5fNUFF0m5kvC', 'andrzej@gmail.com', '4d1615eb3e0ddbe9d74b2f9446467f790cbb9900e6d3c60ebfdc2de057a76fda', NULL, NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
