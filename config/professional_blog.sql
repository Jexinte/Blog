-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 25 juil. 2023 à 16:21
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
(8, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/Hl2eMWYKn3Oy.png', 'Vsdfsfsfsdf', 'Asdfsfsdfsfsdfuse Repository\\SessionManagerRepository;', 'Asdfsfsdfsfsdfuse Repository\\SessionManagerRepository;\r\nuse Repository\\SessionManagerRepository;\r\nuse Repository\\SessionManagerRepository;\r\nuse Repository\\SessionManagerRepository;\r\n', '#Lsdfsfdf #Ldfdfdf #Lafdf', 'Admin', '2023-07-25');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_creation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `idArticle`, `idUser`, `content`, `date_creation`) VALUES
(1, 8, 1, 'Ares', '2023-07-25'),
(2, 8, 4, 'Tu es un crack !', '2023-07-25');

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

--
-- Déchargement des données de la table `form_message`
--

INSERT INTO `form_message` (`id`, `firstname`, `lastname`, `email`, `subject`, `message`) VALUES
(8, 'John', 'Doe', 'trains78@live.fr', 'Collaborationfffffff', 'CollaborationfffffffCollaborationfffffffCollaborationfffffff'),
(9, 'John', 'Doe', 'john@live.fr', 'Collaborationfffffff', 'CollaborationfffffffCollaborationfffffff');

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_type` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `session`
--

INSERT INTO `session` (`id`, `id_session`, `username`, `user_type`) VALUES
(11, 'u4qRtwKHYyYFeDxSW3V4MU+vKWnPR7VANWBQ8RxWKM7DigPX8JdbNvtUy2uuWqT8KyY=', 'Admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `temporary_comment`
--

CREATE TABLE `temporary_comment` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `rejected` tinyint(4) DEFAULT NULL,
  `feedback_administrator` varchar(255) DEFAULT NULL
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
(1, 'Admin', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/dxabo0CfiTpn.jpg', 'mdembelepro@gmail.com', '$2y$10$OBndb21bE3fLFo1aTfn36.FlVkaHDPtLNsmiBS10eB9xxwK.be.oy', 'admin'),
(2, 'Testuser', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/v48RMUXQD901.jpg', 'tes2@gmail.com', '$2y$10$Ky/qmzf/IcHowKKct7emeOft.xnwS5rHO/A5JpV4t6LgEn2d6gZVq', 'user'),
(3, 'Testv', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/', 'va@live.fr', '$2y$10$2STAbphVuJ6gHd6GTISepucvlAiyuz6dE0vZyAYSeve9bLQaeHhW2', 'user'),
(5, 'Test', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/K6Naz2PzP+kG.png', 'test@gmail.com', '$2y$10$OshLZmZApr5IZF63B0nNQuxql0g8bsUpv6wOVOEf1HJ0ZWCDWIPuq', 'user');

-- --------------------------------------------------------

--
-- Structure de la table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `rejected` tinyint(4) DEFAULT NULL,
  `feedback_administrator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_notification`
--

INSERT INTO `user_notification` (`id`, `idArticle`, `idUser`, `approved`, `rejected`, `feedback_administrator`) VALUES
(4, 8, 1, NULL, 1, NULL);

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
  ADD KEY `idArticle` (`idArticle`);

--
-- Index pour la table `form_message`
--
ALTER TABLE `form_message`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `temporary_comment`
--
ALTER TABLE `temporary_comment`
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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `form_message`
--
ALTER TABLE `form_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `temporary_comment`
--
ALTER TABLE `temporary_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`idArticle`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
