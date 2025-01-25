-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Sam 11 Février 2012 à 18:56
-- Version du serveur: 5.1.36
-- Version de PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `decorez`
--

-- --------------------------------------------------------

--
-- Contenu de la table `member`
--

INSERT INTO `member` (`id`, `lastname`, `firstname`, `email`, `phone`, `fax`, `isProfessionnal`, `siretOrSiren`, `company`, `creationDate`, `updateDate`, `password`) VALUES
(1, 'Frappat', 'Maxime', 'lordinaire@gmail.com', '0688554499', '0699887733', 1, '4616546161862', 'LordCorp', '2012-02-11 19:13:04', '2012-02-11 19:13:04', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'),
(2, 'Machin', 'Chose', 'test@gmail.com', '0688774499', '', 0, '', '', '2012-02-11 19:13:52', '2012-02-11 19:13:52', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
