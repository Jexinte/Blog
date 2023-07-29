-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 29 juil. 2023 à 22:08
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
(16, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/C9UaAnU+kAcW.jpg', 'Tyacf', 'As', 'As', '#As #As #As', 'Admin', '2023-07-29');

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
(10, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(11, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(12, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(13, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(14, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(15, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(16, 'John', 'Doe', 'johndoe@live.fr', 'Collaborationeaxaera', 'CollaborationeaxaeraCollaborationeaxaera'),
(17, 'Lo', 'Do', 'fs@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(18, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(19, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(20, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(21, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(22, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(23, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(24, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(25, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(26, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(27, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(28, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(29, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(30, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(31, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(32, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(33, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(34, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf'),
(35, 'Jo', 'La', 'd@live.fr', 'Asfgsfsfsdfsfsfsdfsf', 'Asfgsfsfsdfsfsfsdfsf');

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
(38, '2tY9xnZAK7+N5Rj3GfOq3LmsVemJR6VwGL3cYvoHKFaz2Eg8aT3rP0rTy5SgCQNppE=', 'Admin', 'admin');

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
(1, 'Admin', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/admin.jpg', 'mdembelepro@gmail.com', '$2y$10$OBndb21bE3fLFo1aTfn36.FlVkaHDPtLNsmiBS10eB9xxwK.be.oy', 'admin'),
(2, 'Testuser', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/v48RMUXQD901.jpg', 'tes2@gmail.com', '$2y$10$Ky/qmzf/IcHowKKct7emeOft.xnwS5rHO/A5JpV4t6LgEn2d6gZVq', 'user'),
(3, 'Testv', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/', 'va@live.fr', '$2y$10$2STAbphVuJ6gHd6GTISepucvlAiyuz6dE0vZyAYSeve9bLQaeHhW2', 'user'),
(5, 'Test', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/K6Naz2PzP+kG.png', 'test@gmail.com', '$2y$10$OshLZmZApr5IZF63B0nNQuxql0g8bsUpv6wOVOEf1HJ0ZWCDWIPuq', 'user'),
(6, 'Admina', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/mcRtGoB2rO8L.jpg', 'sdfsf@live.fr', '$2y$10$0txL5PjD7812z933y4oDr.o5QWSc3e6E13mt94nFLmDQUbDBdPbn.', 'user'),
(7, 'Vax', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/JVchqWmeBju.jpg', 'vax@gmail.com', '$2y$10$HYS5cLB5RTdZqQ3XLfFHru..luMQWTlED4WkNzlxuJKOUdQ7qqVQS', 'user'),
(8, 'Adxa', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/NC+63oK4K5Ud.jpg', 'dsfsff@live.fr', '$2y$10$VPU4H4na3z4IaUhva9oUZepBAXPkeon24MWCtOmoD3RXI3BSoHjUW', 'user'),
(9, 'Afdsf', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/wLOmC8FidBwf.jpg', 'sdfsff45@live.fr', '$2y$10$M.7rltDWx8YXT7yOTngr3..oVGG.UkuummNTEkHx1aaVsDd9h4Xp.', 'user'),
(10, 'Axa', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/dzJeQSHNR83a.jpg', 'x@live.fr', '$2y$10$4thcbCB.AFtBAKcxCz7q4ezm79Xd.tVFIorEZ6A6oFdRQuFiNU026', 'user'),
(11, 'Valhalla', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/3jbwHJppT3Qi.jpg', 'valh@live.fr', '$2y$10$JzGz863uRTebmlk4d4KXgOcWJJ1b0N3Cz5bhf1ohNebU9wEYlQN3i', 'user'),
(12, 'Valbala', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/2ALwwEYDySy.jpg', 'asdf@live.fr', '$2y$10$kbJE2.GVtZhur7s7qydJteu7z1XDik/Srz5tuSZxmML5cGqattLA2', 'user'),
(13, 'Test3', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/v2mCPwoW4j7O.jpg', 'test3@gmail.com', '$2y$10$oc3Lwo/SO/dTJnos1jSUdeumezVpEA/JgOXVhdYLY.HxohNPljw7e', 'user'),
(14, 'Xar', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/H5B5N3nfjft.jpg', 'xar@gmail.com', '$2y$10$MkiImpGL5pD4FCI7CIJAUepctg.trqIRT7h0meg6kzDkJqvElbugC', 'user'),
(15, 'Cratos', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/rqmir+CUF72q.png', 'cratos@gmail.com', '$2y$10$Nsfa1SKUFPqxnhrURup8Quf.rLrVqsTgr9Hdyq6lbsg/YocJG/Tmu', 'user'),
(16, 'Adlo', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/0rOqgt5p0iac.png', 'adlo@live.fr', '$2y$10$pemdXsdpFXfYx7WkQwhZ5uR2GqHXvfuGlDR2SnXfri.Djbb5O/dY2', 'user'),
(17, 'Oeuf', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/DvT4FGDOem4j.png', 'oeuf@gmail.com', '$2y$10$FeigVPrrmlGRCi3w5Pz4lOvcVeM10rsF8FxzP0ohrO...8izv1sVK', 'user'),
(18, 'Kratak', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/Re1tTzSIloWI.png', 'l@live.fr', '$2y$10$sJ2LIwAj2KjeWaaOgMsPSu1M/C1qvtuFnpaM1x4qxh/FAIF2vvMjO', 'user'),
(19, 'Marsx', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/Dc8kgCMmzdc.png', 'sdfsfd@live.fr', '$2y$10$sewT/xF5n2U0MvgRlVkK9OEexf7ADXyyUd70jEaPmUOqRnseCCKWe', 'user');

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
(98, 13, 1, 1, NULL, ''),
(99, 13, 1, 1, NULL, ''),
(100, 13, 1, 1, NULL, ''),
(101, 13, 1, 1, NULL, ''),
(102, 13, 1, 1, NULL, ''),
(103, 15, 1, NULL, 1, '');

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
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `temporary_comment`
--
ALTER TABLE `temporary_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idArticle` (`idArticle`),
  ADD KEY `idUser` (`idUser`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `form_message`
--
ALTER TABLE `form_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `temporary_comment`
--
ALTER TABLE `temporary_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

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
-- Contraintes pour la table `temporary_comment`
--
ALTER TABLE `temporary_comment`
  ADD CONSTRAINT `temporary_comment_ibfk_1` FOREIGN KEY (`idArticle`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `temporary_comment_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_notification`
--
ALTER TABLE `user_notification`
  ADD CONSTRAINT `user_notification_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
