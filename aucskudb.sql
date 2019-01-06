-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 06 2018 г., 19:11
-- Версия сервера: 5.7.21
-- Версия PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `aucsku`
--

-- --------------------------------------------------------

--
-- Структура таблицы `invoice`
--

CREATE TABLE `invoice` (
  `invoiceid` int(12) NOT NULL,
  `numinv` int(7) NOT NULL,
  `numauc` varchar(19) NOT NULL,
  `dateinv` date DEFAULT NULL,
  `qtyinv` int(25) NOT NULL,
  `skucode` varchar(7) NOT NULL,
  `userid` int(10) NOT NULL,
  `invoicefield2` varchar(25) DEFAULT NULL,
  `remarkinv` tinytext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `invoiceprice`
--

CREATE TABLE `invoiceprice` (
  `invpriceid` int(10) NOT NULL,
  `priceinv` double NOT NULL,
  `amountinv` double NOT NULL,
  `skucode` int(7) NOT NULL,
  `numauc` varchar(19) NOT NULL,
  `qtyinv` int(25) NOT NULL,
  `numinv` int(7) NOT NULL,
  `invoicepricefield2` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoiceid`),
  ADD KEY `invoiceid` (`invoiceid`);

--
-- Индексы таблицы `invoiceprice`
--
ALTER TABLE `invoiceprice`
  ADD PRIMARY KEY (`invpriceid`),
  ADD UNIQUE KEY `invpriceid` (`invpriceid`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoiceid` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT для таблицы `invoiceprice`
--
ALTER TABLE `invoiceprice`
  MODIFY `invpriceid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
