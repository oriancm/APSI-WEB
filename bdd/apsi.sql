-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 03 avr. 2024 à 11:45
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `apsi`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
                         `login` varchar(50) NOT NULL,
                         `pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`login`, `pass`) VALUES
    ('admin', '$2y$10$R.1dlwWWa055qCBl7CZN3u5ir/EKHZ9qVqt.6pqNGYJ3OUDMfeKtW');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
                         `id` int(11) NOT NULL,
                         `titre` varchar(100) NOT NULL,
                         `dir` varchar(200) NOT NULL,
                         `idR` int(11) DEFAULT NULL,
                         `orderPic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id`, `titre`, `dir`, `idR`, `orderPic`) VALUES
                                                                  (1, 'fiche_mobile_1636.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/fiche_mobile_1636.jpg', 1, 1),
                                                                  (2, '9474.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/9474.jpg', 1, 2),
                                                                  (3, '9475.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/9475.jpg', 1, 3),
                                                                  (4, 'Cabanel-L\'ange_déchu.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/Cabanel-L\'ange_déchu.jpg', 2, 1),
                                                                  (5, '39595-20210106-125831-constructie-4db856e7.png', 'D:\\dev\\apsi\\APSI-WEB/pic/39595-20210106-125831-constructie-4db856e7.png', 3, 1),
                                                                  (6, '41bf6928-aded-43ba-bdc3-7bce8349f749.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/41bf6928-aded-43ba-bdc3-7bce8349f749.jpg', 3, 2),
                                                                  (7, '41bf6928-aded-43ba-bdc3-7bce8349f749.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/41bf6928-aded-43ba-bdc3-7bce8349f749.jpg', 4, 1),
                                                                  (8, '41bf6928-aded-43ba-bdc3-7bce8349f749.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/41bf6928-aded-43ba-bdc3-7bce8349f749.jpg', 5, 2),
                                                                  (9, '9474.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/9474.jpg', 6, 1),
                                                                  (10, '9475.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/9475.jpg', 6, 2),
                                                                  (11, '580b585b2edbce24c47b262a-removebg-preview.png', 'D:\\dev\\apsi\\APSI-WEB/pic/580b585b2edbce24c47b262a-removebg-preview.png', 7, 1),
                                                                  (12, '580b585b2edbce24c47b262a.png', 'D:\\dev\\apsi\\APSI-WEB/pic/580b585b2edbce24c47b262a.png', 7, 2),
                                                                  (13, '4103306-branche-couronne-de-laurier-gratuit-vectoriel-removebg-preview.png', 'D:\\dev\\apsi\\APSI-WEB/pic/4103306-branche-couronne-de-laurier-gratuit-vectoriel-removebg-preview.png', 7, 3);

-- --------------------------------------------------------

--
-- Structure de la table `reference`
--

CREATE TABLE `reference` (
                             `id` int(11) NOT NULL,
                             `titre` varchar(100) NOT NULL,
                             `commune` varchar(100) DEFAULT NULL,
                             `description` text DEFAULT NULL,
                             `domaine` int(11) DEFAULT NULL,
                             `anneeD` year(4) DEFAULT NULL,
                             `anneeF` year(4) DEFAULT NULL,
                             `statut` int(11) DEFAULT NULL,
                             `moa` varchar(100) DEFAULT NULL,
                             `archi` varchar(100) DEFAULT NULL,
                             `eMoe` varchar(100) DEFAULT NULL,
                             `nbPhase` int(11) DEFAULT NULL,
                             `montant` decimal(10,2) DEFAULT NULL,
                             `nbE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `reference`
--

INSERT INTO `reference` (`id`, `titre`, `commune`, `description`, `domaine`, `anneeD`, `anneeF`, `statut`, `moa`, `archi`, `eMoe`, `nbPhase`, `montant`, `nbE`) VALUES
                                                                                                                                                                    (1, 'Chantier 1', 'Apt', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                                                                                                                                                                    (2, 'Chantier 2', 'Auribeau', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas id mollis nibh, vitae maximus ligula. Nam dapibus sagittis lacus, eu interdum diam. Vivamus auctor a libero eget placerat. Suspendisse quis scelerisque ipsum. Mauris eget dolor id erat imperdiet pretium. Maecenas non ultrices metus. Quisque tristique magna eros, et congue massa mollis id. Ut imperdiet diam at eleifend iaculis. Vestibulum rhoncus suscipit rutrum.', 7, '1990', '2000', 1, 'Mr  maitre douvrage', 'Mr l\'archi', 'ekip', 12, 12000.00, 5),
(3, 'Chantier 3', 'Apt', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Chantier 1', 'Claret', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'chantier 5', 'Lyon', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Chantier 2', NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'chantier 6', 'Apt', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idR` (`idR`);

--
-- Index pour la table `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `reference`
--
ALTER TABLE `reference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `reference` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
