-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 10 mars 2022 à 14:10
-- Version du serveur :  10.3.34-MariaDB-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `customBox`
--

-- --------------------------------------------------------

--
-- Structure de la table `boite`
--

CREATE TABLE `boite` (
  `id` int(11) NOT NULL,
  `taille` text NOT NULL,
  `poidsmax` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `boite`
--

INSERT INTO `boite` (`id`, `taille`, `poidsmax`) VALUES
(1, 'petite', 0.7),
(2, 'moyenne', 1.5),
(3, 'grande', 3.2);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(1, 'Beauté'),
(2, 'Bijoux'),
(3, 'Décoration'),
(4, 'Produit ménager'),
(5, 'Upcycling');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idCommande` int(5) NOT NULL,
  `idBoite` int(5) NOT NULL,
  `message` text NOT NULL,
  `loginCreateur` varchar(256) NOT NULL,
  `couleur` varchar(50) NOT NULL,
  `destinataire` text NOT NULL,
  `lien` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `liste_commande`
--

CREATE TABLE `liste_commande` (
  `idCommande` int(5) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `qte` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `titre` text NOT NULL,
  `description` text NOT NULL,
  `categorie` int(11) NOT NULL,
  `poids` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `titre`, `description`, `categorie`, `poids`) VALUES
(1, 'Crème', 'Une crème hydratante et parfumée qui rend la peau douce', 1, 0.3),
(2, 'Savon', 'Un savon qui respecte la peau', 1, 0.2),
(3, 'Shampoing', 'Shampoing doux et démêlant', 1, 0.4),
(4, 'Bracelet', 'Un bracelet en tissu aux couleurs plaisantes', 2, 0.15),
(5, 'Tableau', 'Aquarelle ou peinture à l\'huile', 3, 0.6),
(6, 'Essuie-main', 'Utile au quotidien', 4, 0.45),
(7, 'Gel', 'Gel hydroalcoolique et Antibactérien', 4, 0.25),
(8, 'Masque', 'masque chirurgical jetable categorie 1', 4, 0.35),
(9, 'Gilet', 'Gilet décoré par nos couturières', 5, 0.55),
(10, 'Marque page', 'Joli marque page pour accompagner vos lectures favorites', 5, 0.1),
(11, 'Sac', 'Sac éco-responsable avec décorations variées', 5, 0.26),
(12, 'Surprise', 'Pochette surprise pour faire plaisir aux petits et grands', 5, 0.7),
(13, 'T-shirt', 'T-shirt peint à la main et avec pochoir', 5, 0.32);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `Login` varchar(256) NOT NULL,
  `Mdp` varchar(256) NOT NULL,
  `Telephone` varchar(11) NOT NULL,
  `Mail` varchar(256) NOT NULL,
  `Niveau_acces` int(10) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idCommande`);

--
-- Index pour la table `liste_commande`
--
ALTER TABLE `liste_commande`
  ADD PRIMARY KEY (`idCommande`,`idProduit`,`qte`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCommande` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
