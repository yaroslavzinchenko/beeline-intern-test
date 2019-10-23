-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 23 2019 г., 18:27
-- Версия сервера: 5.7.25
-- Версия PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `beeline-intern-test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `USER_EMAIL` varchar(100) NOT NULL,
  `BEELINE_VALUE` int(11) NOT NULL,
  `MF_VALUE` int(11) NOT NULL,
  `MTS_VALUE` int(11) NOT NULL,
  `TIME_KEY` timestamp NOT NULL,
  `TEST` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `data`
--

INSERT INTO `data` (`id`, `status`, `USER_EMAIL`, `BEELINE_VALUE`, `MF_VALUE`, `MTS_VALUE`, `TIME_KEY`, `TEST`) VALUES
(360, 'success', 'zin.yar@mail.ru', 598, 131, 258, '2019-10-23 09:59:39', 1),
(361, 'success', 'zin.yar@mail.ru', 442, 558, 947, '2019-10-23 09:59:42', 1),
(362, 'success', 'zin.yar@mail.ru', 987, 854, 94, '2019-10-23 09:59:45', 1),
(363, 'success', 'zin.yar@mail.ru', 986, 994, 162, '2019-10-23 09:59:48', 1),
(364, 'success', 'zin.yar@mail.ru', 54, 748, 507, '2019-10-23 09:59:52', 1),
(365, 'success', 'zin.yar@mail.ru', 933, 839, 245, '2019-10-23 10:00:03', 1),
(366, 'success', 'zin.yar@mail.ru', 177, 145, 582, '2019-10-23 10:02:00', 1),
(367, 'success', 'zin.yar@mail.ru', 73, 305, 331, '2019-10-23 10:05:31', 1),
(368, 'success', 'zin.yar@mail.ru', 643, 740, 974, '2019-10-23 10:21:42', 1),
(369, 'success', 'zin.yar@mail.ru', 555, 939, 211, '2019-10-23 10:23:23', 1),
(370, 'success', 'zin.yar@mail.ru', 418, 826, 693, '2019-10-23 10:24:45', 1),
(371, 'success', 'zin.yar@mail.ru', 289, 301, 155, '2019-10-23 10:27:44', 1),
(372, 'success', 'zin.yar@mail.ru', 160, 75, 807, '2019-10-23 10:27:49', 1),
(373, 'success', 'zin.yar@mail.ru', 368, 305, 134, '2019-10-23 10:28:11', 1),
(374, 'success', 'zin.yar@mail.ru', 669, 841, 123, '2019-10-23 10:29:56', 1),
(375, 'success', 'zin.yar@mail.ru', 764, 751, 259, '2019-10-23 10:31:29', 1),
(376, 'success', 'zin.yar@mail.ru', 554, 103, 699, '2019-10-23 10:32:06', 1),
(377, 'success', 'zin.yar@mail.ru', 241, 846, 905, '2019-10-23 10:32:27', 1),
(378, 'success', 'zin.yar@mail.ru', 542, 418, 666, '2019-10-23 10:32:46', 1),
(379, 'success', 'zin.yar@mail.ru', 630, 678, 147, '2019-10-23 10:33:07', 1),
(380, 'success', 'zin.yar@mail.ru', 164, 508, 349, '2019-10-23 10:45:42', 1),
(381, 'success', 'zin.yar@mail.ru', 652, 1, 534, '2019-10-23 10:45:46', 1),
(382, 'success', 'zin.yar@mail.ru', 687, 754, 636, '2019-10-23 10:45:49', 1),
(383, 'success', 'zin.yar@mail.ru', 422, 104, 6, '2019-10-23 10:49:31', 1),
(384, 'success', 'zin.yar@mail.ru', 365, 17, 984, '2019-10-23 10:49:34', 1),
(385, 'success', 'zin.yar@mail.ru', 699, 849, 970, '2019-10-23 10:50:03', 1),
(386, 'success', 'zin.yar@mail.ru', 846, 871, 1000, '2019-10-23 10:51:10', 1),
(387, 'success', 'zin.yar@mail.ru', 748, 952, 893, '2019-10-23 10:51:44', 1),
(388, 'success', 'zin.yar@mail.ru', 903, 30, 793, '2019-10-23 10:52:00', 1),
(389, 'success', 'zin.yar@mail.ru', 50, 565, 655, '2019-10-23 10:55:06', 1),
(390, 'success', 'zin.yar@mail.ru', 659, 489, 577, '2019-10-23 10:55:17', 1),
(391, 'success', 'zin.yar@mail.ru', 362, 865, 952, '2019-10-23 10:55:34', 1),
(392, 'success', 'zin.yar@mail.ru', 857, 643, 79, '2019-10-23 10:59:45', 1),
(393, 'success', 'zin.yar@mail.ru', 770, 621, 989, '2019-10-23 11:02:20', 1),
(394, 'success', 'zin.yar@mail.ru', 35, 176, 243, '2019-10-23 11:02:48', 1),
(395, 'success', 'zin.yar@mail.ru', 808, 5, 976, '2019-10-23 11:03:50', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=396;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
