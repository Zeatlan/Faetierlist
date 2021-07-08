-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 20 juil. 2019 à 23:05
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `faetierlist`
--

-- --------------------------------------------------------

--
-- Structure de la table `anime`
--

DROP TABLE IF EXISTS `anime`;
CREATE TABLE IF NOT EXISTS `anime` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(255) NOT NULL,
  `a_shortname` varchar(255) DEFAULT NULL,
  `a_banner` varchar(512) NOT NULL,
  `a_valid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `anime`
--

INSERT INTO `anime` (`a_id`, `a_name`, `a_shortname`, `a_banner`, `a_valid`) VALUES
(1, 'Toradora', '', 'img/anime/27.png', 1),
(2, 'Eromanga sensei', 'Ero', 'img/anime/27.jpg', 1),
(3, 'Rosario to Vampire', '', 'https://cdn.myanimelist.net/images/anime/12/75242.jpg', 1),
(4, 'Amagi Brilliant Park', '', 'https://www.nautiljon.com/images/anime/00/29/amagi_brilliant_park_3492.jpg', 1),
(21, 'Nisekoi', '', 'https://cdn.myanimelist.net/r/167x242/images/anime/13/75587.webp?s=3a238227519e349a1bb5cec2002bf75f', 1),
(26, 'Nagi no Asu Kara', NULL, 'img/anime/26.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `anime_gender`
--

DROP TABLE IF EXISTS `anime_gender`;
CREATE TABLE IF NOT EXISTS `anime_gender` (
  `a_id` int(11) NOT NULL,
  `g_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `anime_gender`
--

INSERT INTO `anime_gender` (`a_id`, `g_id`) VALUES
(26, 1),
(2, 1),
(3, 1),
(3, 2),
(4, 1),
(21, 1),
(21, 2);

-- --------------------------------------------------------

--
-- Structure de la table `gender`
--

DROP TABLE IF EXISTS `gender`;
CREATE TABLE IF NOT EXISTS `gender` (
  `g_id` int(11) NOT NULL AUTO_INCREMENT,
  `g_name` varchar(255) NOT NULL,
  `g_banner` varchar(512) NOT NULL,
  `g_nbvote` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `gender`
--

INSERT INTO `gender` (`g_id`, `g_name`, `g_banner`, `g_nbvote`) VALUES
(1, 'romance', 'img/gender/1.png', 21),
(2, 'harem', 'https://i.ytimg.com/vi/SqYnjxQTwAE/maxresdefault.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `l_pseudo` varchar(255) NOT NULL,
  `l_timestamp` int(11) NOT NULL,
  `l_ipaddress` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`l_pseudo`, `l_timestamp`, `l_ipaddress`) VALUES
('AbdoulMargoul', 1560375411, '192.0.0.12');

-- --------------------------------------------------------

--
-- Structure de la table `log_anime`
--

DROP TABLE IF EXISTS `log_anime`;
CREATE TABLE IF NOT EXISTS `log_anime` (
  `l_uid` int(11) NOT NULL,
  `l_aid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `log_anime`
--

INSERT INTO `log_anime` (`l_uid`, `l_aid`) VALUES
(8, 26);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_pseudo` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_discord` varchar(255) NOT NULL,
  `u_joinedtime` int(11) NOT NULL,
  `u_canVote` int(11) NOT NULL DEFAULT '0',
  `u_admin` int(11) NOT NULL DEFAULT '0',
  `u_avatar` varchar(512) NOT NULL DEFAULT 'https://cdn.discordapp.com/avatars/159985870458322944/b50adff099924dd5e6b72d13f77eb9d7.png?size=2048',
  `u_banner` varchar(512) NOT NULL DEFAULT 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg',
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`u_id`, `u_pseudo`, `u_password`, `u_discord`, `u_joinedtime`, `u_canVote`, `u_admin`, `u_avatar`, `u_banner`) VALUES
(1, 'Zeatlan', 'unmotdepasse', 'zeatn#0727', 0, 1, 1, 'https://cdn.discordapp.com/avatars/159985870458322944/b50adff099924dd5e6b72d13f77eb9d7.png?size=2048', 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg'),
(2, 'Electrow', 'elec', 'elec#7314', 0, 1, 1, 'https://cdn.discordapp.com/avatars/157533731190603777/026ad72b0e0b0a18a7651f03b868c9e2.png?size=2048', 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg'),
(3, 'AbdoulMargoul', '38dcdddf8f8b807a94d381af3b292fc5', 'slythek#4127', 1562762802, 1, 0, 'img/avatar/20.png', 'https://konachan.com/sample/db9d5c84c6fed8d92a92c50c2f645d97/Konachan.com%20-%20281238%20sample.jpg'),
(8, 'RandomDude', 'caae624c253f048d941bb78c915abbd0', 'randomguy#0221', 1562762802, 1, 1, 'img/avatar/8.jpg', 'https://media.discordapp.net/attachments/323466522729119744/598085584195551295/74617380_p0_master1200.png?width=1183&height=677'),
(15, 'Slythek', '7682fe272099ea26efe39c890b33675b', 'ezfezf', 1563223797, 1, 0, 'https://cdn.discordapp.com/avatars/159985870458322944/b50adff099924dd5e6b72d13f77eb9d7.png?size=2048', 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg'),
(10, 'Pumpking', '7682fe272099ea26efe39c890b33675b', 'pump#1234', 1563193732, 1, 0, 'https://cdn.discordapp.com/avatars/159985870458322944/b50adff099924dd5e6b72d13f77eb9d7.png?size=2048', 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg'),
(53, 'zdezda', 'caf38e19e70d7bf35a90b1db653bee5f', 'Zeatlan#0727', 1563663587, 0, 0, 'https://cdn.discordapp.com/avatars/159985870458322944/b50adff099924dd5e6b72d13f77eb9d7.png?size=2048', 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg'),
(52, 'rezafdeza', 'caf38e19e70d7bf35a90b1db653bee5f', 'dezadeza#8521', 1563661850, 0, 0, 'https://cdn.discordapp.com/avatars/159985870458322944/b50adff099924dd5e6b72d13f77eb9d7.png?size=2048', 'http://fc00.deviantart.net/fs70/f/2013/307/3/8/outdoor_hallway_by_mclelun-d6suhm2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `n_id` int(11) NOT NULL AUTO_INCREMENT,
  `n_aid` int(11) NOT NULL,
  `n_gid` int(11) NOT NULL,
  `n_uid` int(11) NOT NULL,
  `n_note` int(11) NOT NULL,
  `n_date` int(11) NOT NULL,
  PRIMARY KEY (`n_id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date`) VALUES
(68, 21, 1, 8, 20, 1563478510),
(67, 21, 2, 8, 20, 1563154048),
(64, 2, 1, 8, 2, 1563473970),
(63, 1, 1, 8, 15, 1563478572),
(81, 21, 1, 15, 0, 1563481011),
(80, 1, 1, 15, 15, 1563478644);

-- --------------------------------------------------------

--
-- Structure de la table `restriction`
--

DROP TABLE IF EXISTS `restriction`;
CREATE TABLE IF NOT EXISTS `restriction` (
  `r_uid` int(11) NOT NULL,
  `r_tid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
