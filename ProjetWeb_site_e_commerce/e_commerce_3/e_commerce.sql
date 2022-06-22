-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 21 juin 2022 à 16:47
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e_commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `administration`
--

DROP TABLE IF EXISTS `administration`;
CREATE TABLE IF NOT EXISTS `administration` (
  `id` int(11) NOT NULL,
  `champ` varchar(50) NOT NULL,
  `croissant` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `administration`
--

INSERT INTO `administration` (`id`, `champ`, `croissant`) VALUES
(1, 'Prix', 0);

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL DEFAULT 'default-image.png',
  `quantite` int(32) NOT NULL,
  `prix` int(32) NOT NULL,
  `categorie` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL DEFAULT 'Pas de decription',
  PRIMARY KEY (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `nom`, `image`, `quantite`, `prix`, `categorie`, `description`) VALUES
(30, 'Câble Lightning vers USB (0,5 m)', 'ME291.jpg', 25, 25, 'Câble', ''),
(31, 'Câble de charge \'combo\' USB vers USB-C et Micro USB', 'cable-samsung.jpg', 5, 20, 'Câble', ''),
(45, 'Câble USB-C - USB 3.0 3 Metres', 'Cable-USB-C.jpg', 7, 8, 'Câble', ''),
(46, 'Souris ergonomique verticale USB (noire)', 'LD0003421412_2.jpg', 8, 30, 'Souris', ''),
(47, 'Accuratus Junior', 'Accuratus.jpg', 50, 10, 'Souris', ''),
(48, 'Magic Mouse', 'MK2E3.jpg', 2, 85, 'Souris', ''),
(49, 'Airpulse P100X', 'airpulse.jpg', 1, 450, 'Enceinte', ''),
(50, 'Ricoh Theta Z1', 'theta.jpg', 10, 1200, 'Caméra', ''),
(51, 'GoPro HERO9', 'gopro.jpg', 3, 430, 'Caméra', ''),
(52, 'Kingston SSD NV1 250 Go', 'king-ssd.jpg', 2, 55, 'Disque SSD', '');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(11) NOT NULL,
  `nb_article` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `nb_article`, `id_user`) VALUES
(1, 3, 31),
(9, 5, 31),
(10, 1, 33);

-- --------------------------------------------------------

--
-- Structure de la table `commande_article`
--

DROP TABLE IF EXISTS `commande_article`;
CREATE TABLE IF NOT EXISTS `commande_article` (
  `id_commande` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_article` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  KEY `id_user` (`id_user`),
  KEY `id_commande` (`id_commande`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande_article`
--

INSERT INTO `commande_article` (`id_commande`, `id_user`, `id_article`, `quantite`) VALUES
(1, 31, 47, 2),
(1, 31, 30, 1),
(9, 31, 46, 1),
(9, 31, 45, 1),
(9, 31, 52, 2),
(9, 31, 30, 1),
(10, 33, 50, 1);

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id_user`, `prenom`, `nom`, `email`, `password`, `isadmin`) VALUES
(31, 't', 'utilisateur', 'utilisateur@gmail.com', '$2y$10$6fJihUUiYAuRah2t6W1w.eqMAh7bLR4aUvh0Lc5ksfBAJ73VpbH5O', 0),
(32, 'Istrateur', 'Admin', 'administrateur@gmail.com', '$2y$10$l8r4xV86ibcT9Wp7Xxx/ZOxtSgfe6bK1xYKyAECgcX4YC32ZVEL0q', 1),
(33, 'User', 'User', 'user@mail.com', '$2y$10$oiDgSNZ9G.kdf4j0N9je4uhLcXyHvPk2Q3w0cH8nk6hAMtcSnO/KG', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `compte` (`id_user`);

--
-- Contraintes pour la table `commande_article`
--
ALTER TABLE `commande_article`
  ADD CONSTRAINT `commande_article_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `compte` (`id_user`),
  ADD CONSTRAINT `commande_article_ibfk_2` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `commande_article_ibfk_3` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
