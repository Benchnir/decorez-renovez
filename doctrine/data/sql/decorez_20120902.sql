-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Dim 02 Septembre 2012 à 19:22
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
-- Structure de la table `announcement`
--

CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `budget_id` int(11) DEFAULT NULL,
  `duration_id` int(11) DEFAULT NULL,
  `isUrgent` tinyint(1) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `mainPicture` varchar(100) NOT NULL,
  `creationDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4DB9D91C7597D3FE` (`member_id`),
  KEY `IDX_4DB9D91C36ABA6B8` (`budget_id`),
  KEY `IDX_4DB9D91C37B987D8` (`duration_id`),
  KEY `IDX_4DB9D91C98260155` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `announcement`
--

INSERT INTO `announcement` (`id`, `member_id`, `budget_id`, `duration_id`, `isUrgent`, `description`, `mainPicture`, `creationDate`, `updateDate`, `region_id`) VALUES
(6, 1, 1, 1, 0, 'fgdg erg e', '', '2012-03-30 18:45:17', '2012-05-28 17:52:08', 3),
(7, 1, 1, 2, 0, 'En général, quand les constructeurs reçoivent un mail d’une personne souhaitant acquérir un téléphone ou tout autre appareil gratuitement, ils le mettent tout de suite à sa place légitime, c’est à dire à la corbeille. Mais parfois, il arrive que certains aient de la chance, ce qui donne des histoires assez étonnantes.\r\n\r\nSur sa page Facebook, Samsung Canada a reçu un message de Shane, un utilisateur leur demandant un Galaxy S3 gratuitement. Enfin, pas tout à fait, le bonhomme ayant tout de même la bonté d’âme de donner un dessin fait par ses soins en échange. Samsung Canada a pris la requête avec humour, et a finalement envoyé le téléphone au client chanceux. Mieux, la coque de son S3 est unique, puisque customisée avec son propre dessin. Il dispose également d’un fond d’écran personnalisé reprenant lui aussi le dessin. Bref, une histoire étonnante d’un service client plein d’humour.', '', '2012-03-30 18:45:47', '2012-09-02 11:06:19', 12),
(8, 1, 1, 2, 0, 'Un petit projet', '', '2012-05-07 23:22:06', '2012-05-07 23:22:06', 22),
(9, 1, 2, 2, 1, 'On refait notre terrasse et on y arrive pas...', '', '2012-05-07 23:22:06', '2012-05-07 23:22:06', 7),
(10, 2, 2, 1, 0, 'PremiÃ¨re annonce de moumou', '', '2012-05-08 12:35:08', '2012-05-08 12:35:08', 12),
(11, 1, 2, 2, 0, 'C''est une toute nouvelle annonce :)Avec des caractÃ¨res spÃ©ciaux et des accents !!&#e''{~Ã©"''', '', '2012-05-28 17:58:15', '2012-05-28 17:58:15', 12);

-- --------------------------------------------------------

--
-- Structure de la table `announcement_jobs`
--

CREATE TABLE IF NOT EXISTS `announcement_jobs` (
  `job_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  PRIMARY KEY (`job_id`,`announcement_id`),
  KEY `IDX_84A26599BE04EA9` (`job_id`),
  KEY `IDX_84A26599913AEA17` (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `announcement_jobs`
--

INSERT INTO `announcement_jobs` (`job_id`, `announcement_id`) VALUES
(6, 2),
(7, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(11, 1);

-- --------------------------------------------------------

--
-- Structure de la table `budget`
--

CREATE TABLE IF NOT EXISTS `budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `budgetId` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_745EF24DC5AFF782` (`budgetId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `budget`
--

INSERT INTO `budget` (`id`, `budgetId`, `description`) VALUES
(1, 1, '< 5000'),
(2, 2, '< 10000');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `validator_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `validationDate` datetime DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C7597D3FE` (`member_id`),
  KEY `IDX_9474526CED5CA9E6` (`service_id`),
  KEY `IDX_9474526CB0644AEC` (`validator_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `comment`
--

INSERT INTO `comment` (`id`, `member_id`, `service_id`, `validator_id`, `type`, `title`, `message`, `status`, `reason`, `ip`, `validationDate`, `creationDate`, `updateDate`) VALUES
(1, 1, 1, NULL, 0, 'Premier commentaire', 'uhgpsiuerhgp ezurhupz erpgu', 1, '', '', '0000-00-00 00:00:00', '2012-07-09 19:07:03', '2012-07-09 19:07:03'),
(2, 2, 1, NULL, 1, 'uhb ozehrf zgefr ', 'A simple (but not very flexible) solution would be to just use a .filter() with a function:\r\n\r\n$(''div'').filter(function() {\r\nreturn /^show|\\sshow/.test(this.className);\r\n});\r\n\r\nYou could also write a fairly simple selector plugin. Here is one that you can use with any attribute, not just a class:\r\n\r\n(function($) {\r\n$.extend($.expr['':''], {\r\nattrStart: function(element, index, matches, set) {\r\nmatches = matches[3].split(''|'');\r\n\r\nvar attr = matches.length > 1 ? matches.shift() : ''className'',\r\nval = matches.join(''''),\r\nre = new RegExp(''^'' + val + ''|\\\\s'' + val);\r\n\r\nattr = $(element).attr( attr );\r\n\r\nreturn !!attr && re.test(attr);\r\n}\r\n\r\n});\r\n})(jQuery);\r\n\r\nUsage would be something like this, using a "pipe" delimiter between the attribute name and the value you''re looking for:\r\n\r\n$(''div:attrStart(class|show)'')\r\n\r\nThe plugin makes "class" the default attribute, so you could also do this:\r\n\r\n$(''div:attrStart(show)'')', 1, '', '', NULL, '2012-07-09 20:05:13', '2012-07-09 20:05:13'),
(3, 1, 1, NULL, -1, 'Un autre com''', 's dfn pÃ§_fusÃ§pr_ufseÃ§_prfu sÃ§dhsiuvnsiuh uvh iuhf uh dh uhmorgr\r\nt \r\n ert\r\nhty^lj r\r\ntylh ertgore\r\nth oerh\r\n rther\r\nth ehtie*t hrt\r\nh', 1, NULL, '127.0.0.1', NULL, '2012-07-09 19:02:30', '2012-07-09 19:02:30');

-- --------------------------------------------------------

--
-- Structure de la table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `departmentId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8240E25A3CDC2CC0` (`departmentId`),
  KEY `IDX_8240E25A98260155` (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `department`
--

INSERT INTO `department` (`id`, `region_id`, `departmentId`, `name`) VALUES
(1, 22, 1, 'Ain'),
(2, 20, 2, 'Aisne'),
(3, 3, 3, 'Allier'),
(4, 18, 4, 'Alpes de haute provence'),
(5, 18, 5, 'Hautes alpes'),
(6, 18, 6, 'Alpes maritimes');

-- --------------------------------------------------------

--
-- Structure de la table `duration`
--

CREATE TABLE IF NOT EXISTS `duration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `durationId` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7F29E296CFF91C3` (`durationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `duration`
--

INSERT INTO `duration` (`id`, `durationId`, `description`) VALUES
(1, 1, '< 1 jour'),
(2, 2, 'Entre 1 et 3 jours');

-- --------------------------------------------------------

--
-- Structure de la table `job`
--

CREATE TABLE IF NOT EXISTS `job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `jobId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FBD8E0F856D231E7` (`jobId`),
  KEY `IDX_FBD8E0F812469DE2` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `job`
--

INSERT INTO `job` (`id`, `category_id`, `jobId`, `name`) VALUES
(1, 1, 1, 'Gros oeuvre'),
(2, 2, 2, 'Travaux interieurs');

-- --------------------------------------------------------

--
-- Structure de la table `job_category`
--

CREATE TABLE IF NOT EXISTS `job_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `job_category`
--

INSERT INTO `job_category` (`id`, `name`) VALUES
(1, 'Construction'),
(2, 'Décoration');

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `isProfessionnal` tinyint(1) NOT NULL,
  `siretOrSiren` varchar(14) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `role` int(11) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_70E4FA78E7927C74` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `member`
--

INSERT INTO `member` (`id`, `lastname`, `firstname`, `email`, `password`, `phone`, `fax`, `isProfessionnal`, `siretOrSiren`, `company`, `creationDate`, `updateDate`, `role`, `avatar`) VALUES
(1, 'Frappat', 'Maxime', 'test@gmail.com', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '245453646', '', 1, NULL, 'LordCorporation', '2012-03-30 18:20:40', '2012-07-18 21:30:28', 0, 'avatar_1.jpg'),
(2, 'Bench', 'Mounir', 'test2@gmail.com', '109f4b3c50d7b0df729d299bc6f8e9ef9066971f', '54561653', '', 0, NULL, NULL, '2012-05-08 12:32:22', '2012-07-18 21:38:42', 0, 'avatar.jpg'),
(3, 'Admin', 'Istrator', 'admin@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', '', 0, NULL, NULL, '2012-07-11 11:52:53', '2012-07-11 11:52:53', 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regionId` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8CEF4409962506A` (`regionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Contenu de la table `region`
--

INSERT INTO `region` (`id`, `regionId`, `name`) VALUES
(1, 0, 'Toute la france'),
(2, 1, 'Alsace'),
(3, 2, 'Aquitaine'),
(4, 3, 'Auvergne'),
(5, 4, 'Basse Normandie'),
(6, 5, 'Bourgogne'),
(7, 6, 'Bretagne'),
(8, 7, 'Centre'),
(9, 8, 'Champagne Ardenne'),
(10, 9, 'Corse'),
(11, 10, 'Franche Comte'),
(12, 11, 'Haute Normandie'),
(13, 12, 'Ile de France'),
(14, 13, 'Languedoc Roussillon'),
(15, 14, 'Limousin'),
(16, 15, 'Lorraine'),
(17, 16, 'Midi-Pyrénées'),
(18, 17, 'Nord Pas de Calais'),
(19, 18, 'P.A.C.A'),
(20, 19, 'Pays de la Loire'),
(21, 20, 'Picardie'),
(22, 21, 'Poitou Charente'),
(23, 22, 'Rhone Alpes');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `isPartner` tinyint(1) NOT NULL,
  `creationDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `mainJob_id` int(11) DEFAULT NULL,
  `description` varchar(1000) NOT NULL,
  `experience` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E19D9AD27597D3FE` (`member_id`),
  KEY `IDX_E19D9AD225281445` (`mainJob_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `service`
--

INSERT INTO `service` (`id`, `member_id`, `isPartner`, `creationDate`, `updateDate`, `mainJob_id`, `description`, `experience`) VALUES
(1, 1, 0, '2012-05-08 09:20:11', '2012-07-18 19:41:35', 1, 'dgdr regrt rthertrthreth', 15),
(2, 2, 0, '2012-05-08 14:34:22', '2012-05-08 14:34:22', 1, 'qqq', 2);

-- --------------------------------------------------------

--
-- Structure de la table `service_job`
--

CREATE TABLE IF NOT EXISTS `service_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) DEFAULT NULL,
  `evaluation` int(11) NOT NULL,
  `creationDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `price3` int(11) NOT NULL,
  `price1` int(11) NOT NULL,
  `price2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D988938FBE04EA9` (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `service_job`
--

INSERT INTO `service_job` (`id`, `job_id`, `evaluation`, `creationDate`, `updateDate`, `price3`, `price1`, `price2`) VALUES
(6, 1, 4, '2012-05-27 20:37:21', '2012-05-27 20:37:21', 8, 10, 9),
(7, 2, 2, '2012-05-27 21:26:08', '2012-05-27 21:26:08', 10, 20, 15),
(8, 1, 4, '2012-05-28 18:03:01', '2012-05-28 18:03:01', 10, 14, 12),
(9, 2, 1, '2012-05-28 18:19:02', '2012-05-28 18:19:02', 12, 20, 15);

-- --------------------------------------------------------

--
-- Structure de la table `service_jobs`
--

CREATE TABLE IF NOT EXISTS `service_jobs` (
  `service_id` int(11) NOT NULL,
  `servicejob_id` int(11) NOT NULL,
  PRIMARY KEY (`service_id`,`servicejob_id`),
  KEY `IDX_66D0D929ED5CA9E6` (`service_id`),
  KEY `IDX_66D0D92928AA4FD2` (`servicejob_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `service_jobs`
--

INSERT INTO `service_jobs` (`service_id`, `servicejob_id`) VALUES
(1, 6),
(1, 7),
(2, 8),
(2, 9);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `FK_4DB9D91C36ABA6B8` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`),
  ADD CONSTRAINT `FK_4DB9D91C37B987D8` FOREIGN KEY (`duration_id`) REFERENCES `duration` (`id`),
  ADD CONSTRAINT `FK_4DB9D91C7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `FK_4DB9D91C98260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Contraintes pour la table `announcement_jobs`
--
ALTER TABLE `announcement_jobs`
  ADD CONSTRAINT `FK_84A26599913AEA17` FOREIGN KEY (`announcement_id`) REFERENCES `job` (`id`),
  ADD CONSTRAINT `FK_84A26599BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `announcement` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `FK_9474526CB0644AEC` FOREIGN KEY (`validator_id`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `FK_9474526CED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `FK_8240E25A98260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `FK_FBD8E0F812469DE2` FOREIGN KEY (`category_id`) REFERENCES `job_category` (`id`);

--
-- Contraintes pour la table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `FK_E19D9AD225281445` FOREIGN KEY (`mainJob_id`) REFERENCES `job` (`id`),
  ADD CONSTRAINT `FK_E19D9AD27597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`);

--
-- Contraintes pour la table `service_job`
--
ALTER TABLE `service_job`
  ADD CONSTRAINT `FK_D988938FBE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`);

--
-- Contraintes pour la table `service_jobs`
--
ALTER TABLE `service_jobs`
  ADD CONSTRAINT `FK_66D0D92928AA4FD2` FOREIGN KEY (`servicejob_id`) REFERENCES `service_job` (`id`),
  ADD CONSTRAINT `FK_66D0D929ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
