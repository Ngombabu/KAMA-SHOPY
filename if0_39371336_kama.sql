-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : sql111.infinityfree.com
-- Généré le :  mer. 22 oct. 2025 à 10:45
-- Version du serveur :  11.4.7-MariaDB
-- Version de PHP :  7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `if0_39371336_kama`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `postnom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id`, `nom`, `postnom`, `prenom`, `email`, `mdp`) VALUES
(236595971, 'ngombabu', 'mutala', 'chris', 'chtisngombabu2@gmail.com', '2002');

-- --------------------------------------------------------

--
-- Structure de la table `chaussure`
--

CREATE TABLE `chaussure` (
  `id` int(11) NOT NULL,
  `id_vendeur` int(5) NOT NULL,
  `marque` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `taille` varchar(50) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `couleur` varchar(50) NOT NULL,
  `image` longtext NOT NULL,
  `logo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sac`
--

CREATE TABLE `sac` (
  `id` int(11) NOT NULL,
  `id_vendeur` int(11) NOT NULL,
  `marque` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `couleur` varchar(50) NOT NULL,
  `image` longtext NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `logo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tendance`
--

CREATE TABLE `tendance` (
  `id` int(11) NOT NULL,
  `id_vendeur` int(11) DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `image1` longtext DEFAULT NULL,
  `image2` longtext DEFAULT NULL,
  `image3` longtext DEFAULT NULL,
  `image4` longtext DEFAULT NULL,
  `image5` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tendance`
--

INSERT INTO `tendance` (`id`, `id_vendeur`, `image`, `image1`, `image2`, `image3`, `image4`, `image5`) VALUES
(236595997, 236595976, 'KAMA_IMAGE/pub_68373b9f51f07.png', NULL, NULL, NULL, NULL, NULL),
(236595999, 236595976, 'KAMA_IMAGE/pub_68373c31414fd.png', NULL, NULL, NULL, NULL, NULL),
(236596000, 236595976, 'KAMA_IMAGE/pub_68373c441dd2d.jpg', NULL, NULL, NULL, NULL, NULL),
(236596001, 236595976, 'KAMA_IMAGE/pub_68373c608f331.jpg', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vendeur`
--

CREATE TABLE `vendeur` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `postnom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `image` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vendeur`
--

INSERT INTO `vendeur` (`id`, `nom`, `postnom`, `prenom`, `email`, `mdp`, `numero`, `image`) VALUES
(236595976, 'Tshibamba', 'Mbaya', 'Sylvain', 'Mbayasylvain4@gmail.com', '$2y$10$bwqMwkX/znvtXsDL3p2I..8r7byl5rLuXPZgjBTNuRigibFswNBFC', '971 236 595', 'KAMA_IMAGE/profil_6837395e33e37.jpg'),
(236595977, 'Ngombabu', 'Mutala', 'Chris', 'chrisngombabu2@gmail.com', '$2y$10$2NBulnzD1n5tRAD1kYlR3u.zhwTsZ3wKxg.g/Olc1UAYTA57nRxda', '974437984', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chaussure`
--
ALTER TABLE `chaussure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendeur` (`id_vendeur`);

--
-- Index pour la table `sac`
--
ALTER TABLE `sac`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendeur` (`id_vendeur`);

--
-- Index pour la table `tendance`
--
ALTER TABLE `tendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendeur` (`id_vendeur`);

--
-- Index pour la table `vendeur`
--
ALTER TABLE `vendeur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236595972;

--
-- AUTO_INCREMENT pour la table `chaussure`
--
ALTER TABLE `chaussure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236596005;

--
-- AUTO_INCREMENT pour la table `sac`
--
ALTER TABLE `sac`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236595985;

--
-- AUTO_INCREMENT pour la table `tendance`
--
ALTER TABLE `tendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236596002;

--
-- AUTO_INCREMENT pour la table `vendeur`
--
ALTER TABLE `vendeur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236595978;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chaussure`
--
ALTER TABLE `chaussure`
  ADD CONSTRAINT `chaussure_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sac`
--
ALTER TABLE `sac`
  ADD CONSTRAINT `sac_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tendance`
--
ALTER TABLE `tendance`
  ADD CONSTRAINT `tendance_ibfk_1` FOREIGN KEY (`id_vendeur`) REFERENCES `vendeur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
