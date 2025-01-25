-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Sam 11 Février 2012 à 21:47
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
-- Contenu de la table `job`
--

INSERT INTO `job` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Architecture - Conception'),
(2, 1, 'Construction - Extension'),
(3, 1, 'Terrassement'),
(4, 1, 'Maçonnerie - Démolition'),
(5, 1, 'Charpente - Couverture'),
(6, 1, 'Assainissement'),
(7, 2, 'Rénovation'),
(8, 2, 'Aménagement'),
(9, 2, 'Cuisine'),
(10, 2, 'Salle de bains - WC'),
(11, 2, 'Isolation'),
(12, 3, 'Façade'),
(13, 3, 'Sol extérieur'),
(14, 3, 'Véranda'),
(15, 3, 'Jardin - Terrasse'),
(16, 4, 'Isolation'),
(17, 4, 'Porte - Fenêtre');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `FK_FBD8E0F812469DE2` FOREIGN KEY (`category_id`) REFERENCES `job_category` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
