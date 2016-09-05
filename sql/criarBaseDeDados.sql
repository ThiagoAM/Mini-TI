-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Sep 05, 2016 at 03:30 AM
-- Server version: 5.5.42
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `bdMiniTI`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` smallint(5) unsigned NOT NULL,
  `nome` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `email` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `senha` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `telefone` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `endereco` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD KEY `idCliente` (`idCliente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` smallint(5) unsigned NOT NULL AUTO_INCREMENT;