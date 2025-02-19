-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 11 fév. 2025 à 17:55
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `restaurant_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `employe` varchar(255) NOT NULL,
  `plat_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(10) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `employe`, `plat_id`, `date`, `status`) VALUES
(1, '1', 1, '2025-02-04', 'active'),
(2, '2', 1, '2025-02-04', 'active'),
(3, '2', 1, '2025-02-04', 'active'),
(4, '1', 3, '2025-02-04', 'active'),
(5, '1', 4, '2025-02-05', 'active'),
(6, '1', 4, '2025-02-05', 'active'),
(7, '5', 4, '2025-02-05', 'active'),
(8, '2', 4, '2025-02-05', 'active'),
(9, '2', 7, '2025-02-05', 'active'),
(10, '5', 7, '2025-02-05', 'active'),
(11, '2', 7, '2025-02-05', 'active'),
(12, '1', 8, '2025-02-07', 'active'),
(13, '1', 9, '2025-02-07', 'active'),
(14, '1', 8, '2025-02-07', 'active'),
(15, '1', 9, '2025-02-07', 'active'),
(16, '1', 10, '2025-02-10', 'active'),
(25, '2', 10, '2025-02-10', 'active'),
(28, '1', 12, '2025-02-10', 'active'),
(29, '1', 13, '2025-02-10', 'active'),
(30, '1', 11, '2025-02-10', 'active'),
(32, '2', 12, '2025-02-10', 'active'),
(33, '2', 13, '2025-02-10', 'active'),
(35, '1', 7, '2025-02-11', 'deleted'),
(36, '1', 7, '2025-02-11', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `plat` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prix` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `plat`, `description`, `image`, `prix`, `date`) VALUES
(1, 'teste111', 'teste111', 'images2.jpeg', 500, '2025-02-04'),
(3, 'gateau', 'une delice', '1734465139367.jpg', 550, '2025-02-04'),
(4, 'demain', 'demain', 'fg1.jpeg', 100, '2025-02-05'),
(6, 'apres demain', 'apres demain', 'font.jpeg', 1000, '2025-02-06'),
(7, 'boisons', 'glaceeeee', 'IMG-20240120-WA0010.jpg', 255, '2025-02-11'),
(8, 'fevr', 'fevr', '1733672227150.jpg', 2025, '2025-02-07'),
(9, 'soir', 'soir', 'IMG-20241102-WA0004.jpg', 1000, '2025-02-07'),
(10, 'gateau', 'un delice', 'IMG-20240120-WA0022.jpg', 500, '2025-02-10'),
(11, 'jus orange', 'vraiment', 'jus.jpeg', 500, '2025-02-10'),
(12, 'jus ananas', 'tres cool', 'jusAnna.jpeg', 250, '2025-02-10'),
(13, 'dsjhflsfkjgp', 'hjshpzkngk;xnzc', 'jus3.jpeg', 300, '2025-02-10');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('employe','super_utilisateur') DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `role`, `password`) VALUES
(1, 'mbai', 'mbai@gmail.com', 'super_utilisateur', '$2y$10$zUXTuYPovuI/SdyExO2SjeQpUP.kHJ.oP8.W46jFqz.L9n3XZpcV2'),
(2, 'aaa', 'aa@gmail.com', 'employe', '$2y$10$C51bOXM3zGE8.8i6oRGHlOEEm4CKompm2C62Aed5aKbLVUnWx9Pum'),
(3, 'admin', 'admin@gmail.om', 'super_utilisateur', '$2y$10$.WWIL8HUjGeVO.DussUlNOFu0qrYpdOSBRRAxOamsnODEx/EuO5w6'),
(4, 'jojo', 'jojo@gmail.com', 'employe', '$2y$10$6Wxlkf8S9rfOSzBUzku48ueCskfv5G4oq.1ypkWokxHKGKvRQ8pfq'),
(5, 'adolphe', 'adolphe@gmail.com', 'super_utilisateur', '$2y$10$1RYeOkPzeZP9UuwtlwIkOO3o8H70Dj9/hbi5UMWlDkLmrn6S.VmJy');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plat_id` (`plat_id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`plat_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
