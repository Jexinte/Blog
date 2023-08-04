-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 04 août 2023 à 10:33
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
-- Base de données : `professional_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `chapô` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `date_creation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `image`, `title`, `chapô`, `content`, `tags`, `author`, `date_creation`) VALUES
(18, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/banner_article/article_1.jpg', 'PHP : Introduction au langage de programmation web', 'Découvrez les bases de PHP', 'PHP est un langage de programmation largement utilisé pour développer des sites web dynamiques. Dans cet article, nous allons explorer les concepts de base de PHP, y compris les variables, les boucles et les fonctions.', '#PHP #WebDevelopment #Programming', 'Admin', '2023-07-24'),
(19, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/banner_article/article_2.jpg', 'Les meilleures pratiques de sécurité en PHP', 'Protégez vos applications web avec PHP', 'La sécurité est essentielle lors du développement d\'applications web avec PHP. Découvrez les meilleures pratiques pour protéger votre code contre les failles courantes telles que les injections SQL et les attaques par cross-site scripting (XSS).', '#PHP #Security #WebDevelopment', 'Admin', '2023-07-24'),
(20, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/q11QTtwFyDA.jpg', 'PHP : Gérer les formulaires et les données utilisateur', 'Apprenez à manipuler les données des utilisateurs', 'Les formulaires sont couramment utilisés pour collecter des données auprès des utilisateurs. Dans cet article, nous allons explorer comment traiter et valider les données envoyées via des formulaires en utilisant PHP.\r\nLes formulaires sont couramment utilisés pour collecter des données auprès des utilisateurs. Dans cet article, nous allons explorer comment traiter et valider les données envoyées via des formulaires en utilisant PHP.', '#PHP #GérerFormulaire #ValidationDeDonnées', 'Admin', '2023-08-03');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_creation` date NOT NULL,
  `feedback_administrator` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `idArticle`, `idUser`, `content`, `date_creation`, `feedback_administrator`, `status`) VALUES
(183, 20, 1, 'Ax', '2023-08-04', NULL, 1),
(184, 19, 1, 'Agdgffdg', '2023-08-04', NULL, 1),
(185, 18, 1, 'Vax', '2023-08-04', NULL, 1),
(187, 20, 5, 'Va', '2023-08-04', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `form_message`
--

CREATE TABLE `form_message` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `profile_image`, `email`, `password`, `type`) VALUES
(1, 'Admin', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/admin.jpg', 'mdembelepro@gmail.com', '$2y$10$OBndb21bE3fLFo1aTfn36.FlVkaHDPtLNsmiBS10eB9xxwK.be.oy', 'admin'),
(5, 'Test', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/K6Naz2PzP+kG.png', 'test@gmail.com', '$2y$10$OshLZmZApr5IZF63B0nNQuxql0g8bsUpv6wOVOEf1HJ0ZWCDWIPuq', 'user'),
(26, 'Floki', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/71m1WGesaqAJ.jpg', 'floki@gmail.com', '$2y$10$Nf8vr8eQ9OQ5nitXtMHoB.QRcKSqoM9D6d/TyFLUqrXo0Dpi1OMny', 'user');

-- --------------------------------------------------------

--
-- Structure de la table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `feedback_administrator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idArticle` (`idArticle`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `form_message`
--
ALTER TABLE `form_message`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idArticle` (`idArticle`),
  ADD KEY `idUser` (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT pour la table `form_message`
--
ALTER TABLE `form_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`idArticle`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_notification`
--
ALTER TABLE `user_notification`
  ADD CONSTRAINT `user_notification_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_notification_ibfk_3` FOREIGN KEY (`idArticle`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
