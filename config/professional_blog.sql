-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 08 août 2023 à 19:16
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
  `dateCreation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `image`, `title`, `chapô`, `content`, `tags`, `author`, `dateCreation`) VALUES
(18, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/banner_article/article_1.jpg', 'PHP : Introduction au langage de programmation web', 'Découvrez les bases de PHP', 'PHP est un langage de programmation largement utilisé pour développer des sites web dynamiques. Dans cet article, nous allons explorer les concepts de base de PHP, y compris les variables, les boucles et les fonctions.', '#PHP #WebDevelopment #Programming', 'Admin', '2023-07-24'),
(19, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/banner_article/article_2.jpg', 'Les meilleures pratiques de sécurité en PHP', 'Protégez vos applications web avec PHP', 'La sécurité est essentielle lors du développement d\'applications web avec PHP. Découvrez les meilleures pratiques pour protéger votre code contre les failles courantes telles que les injections SQL et les attaques par cross-site scripting (XSS).', '#PHP #Security #WebDevelopment', 'Admin', '2023-07-24'),
(20, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/banner_article/article_3.jpg', 'PHP : Gérer les formulaires et les données utilisateur', 'Apprenez à manipuler les données des utilisateurs', 'Les formulaires sont couramment utilisés pour collecter des données auprès des utilisateurs. Dans cet article, nous allons explorer comment traiter et valider les données envoyées via des formulaires en utilisant PHP.\r\nLes formulaires sont couramment utilisés pour collecter des données auprès des utilisateurs. Dans cet article, nous allons explorer comment traiter et valider les données envoyées via des formulaires en utilisant PHP.', '#PHP #GérerFormulaire #ValidationDeDonnées', 'Admin', '2023-08-03'),
(46, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/b040dmH+B6y.jpg', 'Test', 'A', 'B', '#C #C #C', 'Admin', '2023-08-08');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `content` text NOT NULL,
  `dateCreation` date NOT NULL,
  `feedbackAdministrator` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `idArticle`, `idUser`, `content`, `dateCreation`, `feedbackAdministrator`, `status`) VALUES
(204, 20, 1, 'Test', '2023-08-07', NULL, 1),
(206, 20, 1, 'Vax', '2023-08-08', NULL, 1),
(207, 20, 1, 'Test3', '2023-08-08', NULL, 1);

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
(100, 'John', 'Doe', 'johndoe@gmail.com', 'Un partenariat d\'exception à l\'avenir !', 'Un partenariat d\'exception à l\'avenir !'),
(101, 'John', 'Doe', 'john@gmail.com', 'EEEEEEEEEEEEEEEEEEEEEEEEEEEEE', 'EEEEEEEEEEEEEEEEEEEEEEEEEEEEE'),
(102, 'John', 'Doe', 'john@gmail.com', 'EEEEEEEEEEEEEEEEEEEEEEEEEEEEE', 'EEEEEEEEEEEEEEEEEEEEEEEEEEEEE'),
(103, 'John', 'Doe', 'x@live.fr', 'Asdfsfdfsfsfsdfsfsdfsfddsfsfsfsfd', 'Asdfsfdfsfsfsdfsfsdfsfddsfsfsfsfd'),
(104, 'John', 'Doe', 'johndoe@live.fr', 'Un partenariat d\'exception à l\'avenir!', 'Un partenariat d\'exception à l\'avenir!'),
(105, 'John', 'Doe', 'johndoe@live.fr', 'Un partenariat d\'exception à l\'avenir!', 'Un partenariat d\'exception à l\'avenir!'),
(106, 'John', 'Doe', 'johndoe@live.fr', 'Un partenariat d\'exception à l\'avenir!', 'Un partenariat d\'exception à l\'avenir!'),
(107, 'John', 'Doe', 'johndoe@live.fr', 'Un partenariat d\'exception à l\'avenir!', 'Un partenariat d\'exception à l\'avenir!'),
(108, 'Vax', 'Doe', 'johnd@live.fr', 'MyretiaxMyretiaxMyretiax', 'MyretiaxMyretiaxMyretiaxMyretiax'),
(109, 'Floki', 'Franel', 'franel@gmail.com', 'Adssdfsfsffsfdsfdsdfsd', 'Adssdfsfsffsfdsfdsdfsd'),
(110, 'Floki', 'Franel', 'franel@gmail.com', 'Adssdfsfsffsfdsfdsdfsd', 'Adssdfsfsffsfdsfdsdfsd'),
(111, 'Floki', 'Franel', 'franel@gmail.com', 'Adssdfsfsffsfdsfdsdfsd', 'Adssdfsfsffsfdsfdsdfsd'),
(112, 'Floki', 'Franel', 'franel@gmail.com', 'Adssdfsfsffsfdsfdsdfsd', 'Adssdfsfsffsfdsfdsdfsd'),
(113, 'Floki', 'Franel', 'franel@gmail.com', 'Adssdfsfsffsfdsfdsdfsd', 'Adssdfsfsffsfdsfdsdfsd'),
(114, 'Floki', 'Franel', 'franel@gmail.com', 'Adssdfsfsffsfdsfdsdfsd', 'Adssdfsfsffsfdsfdsdfsd'),
(115, 'Kratos', 'Doe', 'xdsf@live.fr', 'A', 'B');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `profileImage` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `profileImage`, `email`, `password`, `type`) VALUES
(1, 'Admin', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/admin.jpg', 'mdembelepro@gmail.com', '$2y$10$OBndb21bE3fLFo1aTfn36.FlVkaHDPtLNsmiBS10eB9xxwK.be.oy', 'admin'),
(5, 'Test', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/test.png', 'test@gmail.com', '$2y$10$OshLZmZApr5IZF63B0nNQuxql0g8bsUpv6wOVOEf1HJ0ZWCDWIPuq', 'user'),
(29, 'Johnt', 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/+ChFybElfZzN.jpg', 'johnt@gmail.com', '$2y$10$uZch82fB9fQFsaq5cH3nSeVdAu4/QRd5r9nTcuH.f8oP6rvdSY7dO', 'user');

-- --------------------------------------------------------

--
-- Structure de la table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `feedbackAdministrator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_notification`
--

INSERT INTO `user_notification` (`id`, `idArticle`, `idUser`, `status`, `feedbackAdministrator`) VALUES
(185, 20, 1, 0, NULL),
(186, 20, 1, 0, NULL),
(187, 18, 1, 0, NULL),
(188, 20, 1, 1, NULL),
(189, 20, 1, 1, NULL),
(190, 20, 1, 0, NULL),
(191, 20, 1, 1, NULL),
(192, 20, 1, 1, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT pour la table `form_message`
--
ALTER TABLE `form_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

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
