-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Време на генериране: 27 яну 2026 в 16:33
-- Версия на сървъра: 10.4.32-MariaDB
-- Версия на PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данни: `celebrating_events_organizer`
--

-- --------------------------------------------------------

--
-- Структура на таблица `dates`
--

CREATE TABLE `dates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `dates`
--

INSERT INTO `dates` (`id`, `owner_id`, `date`, `title`) VALUES
(2, 1, '2003-06-12', 'Моят рожден ден'),
(64, 2, '2002-12-29', 'Моят рожден ден'),
(72, 3, '2003-06-26', 'Моят рожден ден'),
(73, 3, '2007-05-12', 'Рожденият ден на брат ми');

-- --------------------------------------------------------

--
-- Структура на таблица `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `celebrating_date` char(10) NOT NULL,
  `organizer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `organized_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(256) NOT NULL,
  `location` varchar(256) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `events`
--

INSERT INTO `events` (`id`, `celebrating_date`, `organizer_id`, `organized_id`, `title`, `location`, `description`) VALUES
(1, '2026-12-29', 2, 1, 'Title 1', 'London', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum'),
(2, '2026-12-29', 2, 1, 'Title 2', 'London', 'Some desc'),
(3, '2026-12-29', 2, 1, 'Title 3', 'London', 'Some desc'),
(4, '2026-12-29', 2, 1, 'Title 4', 'London', 'Some desc'),
(5, '2026-12-29', 2, 1, 'Title 5', 'LA', 'dexc'),
(6, '2026-06-12', 2, 1, 'Title 6', 'Center', 'Some desc'),
(8, '2026-05-12', 1, 3, 'Парти изненада за брата на test3', 'Пицария &quot;Верди&quot;, Център', 'Луд купон в пицарията!');

-- --------------------------------------------------------

--
-- Структура на таблица `followers`
--

CREATE TABLE `followers` (
  `follower_id` bigint(20) UNSIGNED NOT NULL,
  `followed_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `followers`
--

INSERT INTO `followers` (`follower_id`, `followed_id`) VALUES
(1, 2),
(1, 3),
(2, 1),
(2, 3),
(3, 1);

-- --------------------------------------------------------

--
-- Структура на таблица `gifts`
--

CREATE TABLE `gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_guest_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `gifts`
--

INSERT INTO `gifts` (`id`, `event_id`, `assigned_guest_id`, `description`) VALUES
(3, 1, 3, 'Тестов подарък от test3'),
(5, 1, 2, 'Тестов подарък от test2'),
(8, 8, 2, 'Да донеса подарък'),
(10, 8, 2, 'Да донеса картичка'),
(11, 8, 1, 'Да направя резервация');

-- --------------------------------------------------------

--
-- Структура на таблица `guests`
--

CREATE TABLE `guests` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `guest_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `guests`
--

INSERT INTO `guests` (`event_id`, `guest_id`) VALUES
(1, 2),
(1, 3),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(8, 1),
(8, 2);

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(320) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `password`) VALUES
(1, 'test', 'test@example.com', 'Erica Hendricks', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8'),
(2, 'test2', 'test2@example.com', 'Lilah Finley', '6cf615d5bcaac778352a8f1f3360d23f02f34ec182e259897fd6ce485d7870d4'),
(3, 'test3', 'test3@example.com', 'Emma Wong', '5906ac361a137e2d286465cd6588ebb5ac3f5ae955001100bc41577c3d751764'),
(4, 'test4', 'test4@example.com', 'John Doe', '5906ac361a137e2d286465cd6588ebb5ac3f5ae955001100bc41577c3d751764'),
(5, 'test5', 'test5@example.com', 'Natalee Rosario', 'b9c950640e1b3740e98acb93e669c65766f6670dd1609ba91ff41052ba48c6f3'),
(7, 'test6', 'test6@example.com', 'Test Name', '598a1a400c1dfdf36974e69d7e1bc98593f2e15015eed8e9b7e47a83b31693d5');

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dates_owner_id_fk` (`owner_id`);

--
-- Индекси за таблица `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_organizer_id_fk` (`organizer_id`),
  ADD KEY `events_organized_id_fk` (`organized_id`);

--
-- Индекси за таблица `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `followers_followed_id_fk` (`followed_id`);

--
-- Индекси за таблица `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gifts_event_id_fk` (`event_id`),
  ADD KEY `gifts_assigned_guest_id_fk` (`assigned_guest_id`);

--
-- Индекси за таблица `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`event_id`,`guest_id`),
  ADD KEY `guests_guest_id_fk` (`guest_id`);

--
-- Индекси за таблица `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gifts`
--
ALTER TABLE `gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `dates`
--
ALTER TABLE `dates`
  ADD CONSTRAINT `dates_owner_id_fk` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_organized_id_fk` FOREIGN KEY (`organized_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_organizer_id_fk` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения за таблица `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_followed_id_fk` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_follower_id_fk` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `gifts`
--
ALTER TABLE `gifts`
  ADD CONSTRAINT `gifts_assigned_guest_id_fk` FOREIGN KEY (`assigned_guest_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gifts_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `guests_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guests_guest_id_fk` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
