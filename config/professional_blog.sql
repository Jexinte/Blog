-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 25 juin 2023 à 20:38
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
-- Structure de la table `administrators_notifications`
--

CREATE TABLE `administrators_notifications` (
  `idUser` int(11) NOT NULL,
  `total_temporary_comments` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
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
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `image`, `title`, `chapô`, `content`, `tags`, `author`, `date_creation`) VALUES
(1, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_1.jpg', 'Les bases de PHP', 'Découvrez les fondamentaux de PHP pour développer des sites web dynamiques.', 'PHP, acronyme de Hypertext Preprocessor, est un langage de script largement utilisé pour le développement web. Avec PHP, vous pouvez créer des pages web interactives et dynamiques en générant du contenu HTML en temps réel. Que vous souhaitiez créer un site web simple ou une application web complexe, les connaissances de base de PHP sont essentielles. Ce langage polyvalent offre de nombreuses fonctionnalités et facilite l\'interaction avec les bases de données, la manipulation de fichiers, la gestion des formulaires et bien plus encore. Plongez dans l\'univers de PHP et explorez ses possibilités infinies !', '#PHP #DéveloppementWeb #LangageDeScript', 'John007', '2023-06-12'),
(2, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_2.jpg', 'Les avantages de PHP pour le développement web', 'Découvrez pourquoi PHP est un choix populaire parmi les développeurs web.', 'PHP offre de nombreux avantages pour le développement web. Sa syntaxe simple et intuitive permet aux développeurs de créer rapidement des applications web. PHP possède également une grande communauté de développeurs actifs, ce qui signifie qu\'il existe une pléthore de ressources et de bibliothèques disponibles. De plus, PHP s\'intègre facilement aux bases de données, offrant ainsi une flexibilité pour la gestion des données. Découvrez comment PHP peut vous aider à développer des sites web puissants et dynamiques.', '#PHP #DéveloppementWeb #Avantages', 'JaneD', '2023-06-15'),
(3, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_3.jpg', 'Les bonnes pratiques de programmation en PHP', 'Découvrez les meilleures pratiques pour écrire un code PHP propre et maintenable.', 'Pour développer des applications PHP de qualité, il est essentiel de suivre les bonnes pratiques de programmation. Cela inclut l\'utilisation de la POO (Programmation Orientée Objet), l\'organisation du code en classes et en fonctions, la validation des entrées utilisateur, la sécurisation des requêtes SQL, etc. En suivant ces bonnes pratiques, vous pouvez améliorer la lisibilité, la maintenabilité et la sécurité de votre code PHP. Découvrez comment écrire un code PHP de qualité supérieure.', '#PHP #DéveloppementWeb #BonnesPratiques', 'John Smith', '2023-06-18'),
(4, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_4.png', 'Les frameworks PHP les plus populaires', 'Découvrez les frameworks PHP qui facilitent le développement d\'applications web.', 'Les frameworks PHP offrent une structure et des fonctionnalités prêtes à l\'emploi pour accélérer le développement d\'applications web. Parmi les frameworks les plus populaires, citons Laravel, Symfony, CodeIgniter et CakePHP. Chacun de ces frameworks possède ses propres caractéristiques et avantages. Découvrez comment choisir le framework PHP adapté à votre projet et comment il peut simplifier votre processus de développement.', '#PHP #DéveloppementWeb #Frameworks', 'Juni Sarko', '2023-06-20'),
(5, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_5.png', 'La sécurisation des applications PHP', 'Apprenez les meilleures pratiques pour sécuriser vos applications PHP.', 'La sécurité est un aspect crucial du développement d\'applications web. En PHP, il est important de prendre des mesures pour protéger vos applications contre les attaques telles que les injections SQL, les attaques par cross-site scripting (XSS) et les failles de sécurité. Apprenez à mettre en œuvre des techniques telles que le filtrage des entrées utilisateur, les requêtes préparées, l\'échappement des données de sortie et la gestion des sessions pour améliorer la sécurité de vos applications PHP.', '#PHP #Sécurité #DéveloppementWeb', 'YagerD', '2023-06-22'),
(6, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_6.png', 'Les meilleures pratiques pour optimiser les performances de PHP', 'Découvrez comment améliorer les performances de vos applications PHP.', 'L\'optimisation des performances est essentielle pour offrir une expérience utilisateur fluide et réactive. En PHP, il existe plusieurs techniques pour améliorer les performances de vos applications, telles que le caching, l\'utilisation efficace des requêtes SQL, la minimisation des appels de fonction et l\'utilisation de techniques de mise en cache côté serveur. Apprenez les meilleures pratiques pour optimiser les performances de vos applications PHP.', '#PHP #Optimisation #DéveloppementWeb', 'ShuffleX', '2023-06-24'),
(7, 'http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/author_7.png', 'Les bases de données et PHP', 'Découvrez comment interagir avec les bases de données en utilisant PHP.', 'Les bases de données sont essentielles pour stocker et récupérer des données dans les applications web. En PHP, vous pouvez interagir avec les bases de données en utilisant des extensions telles que MySQLi ou PDO. Apprenez comment établir une connexion avec une base de données, exécuter des requêtes SQL, récupérer des résultats et gérer les transactions. Découvrez les bonnes pratiques pour interagir avec les bases de données en PHP.', '#PHP #BasesDeDonnées #DéveloppementWeb', 'Lovelace42', '2023-06-26');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `form_messages`
--

CREATE TABLE `form_messages` (
  `id` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `firstname` int(11) NOT NULL,
  `lastname` int(11) NOT NULL,
  `email` int(11) NOT NULL,
  `message` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `temporary_comments`
--

CREATE TABLE `temporary_comments` (
  `id` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `rejected` tinyint(1) NOT NULL,
  `feedback_administrator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `visitors_notifications`
--

CREATE TABLE `visitors_notifications` (
  `idUser` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `rejected` tinyint(1) NOT NULL,
  `feedback_administrator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrators_notifications`
--
ALTER TABLE `administrators_notifications`
  ADD KEY `fk_administrators_notifications_temporary_comments` (`idUser`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_articles` (`idArticle`),
  ADD KEY `fk_comments_users` (`idUser`);

--
-- Index pour la table `form_messages`
--
ALTER TABLE `form_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `temporary_comments`
--
ALTER TABLE `temporary_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_temporary_comments_articles` (`idArticle`),
  ADD KEY `fk_temporary_comments_users` (`idUser`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `visitors_notifications`
--
ALTER TABLE `visitors_notifications`
  ADD KEY `idUser` (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `form_messages`
--
ALTER TABLE `form_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `temporary_comments`
--
ALTER TABLE `temporary_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrators_notifications`
--
ALTER TABLE `administrators_notifications`
  ADD CONSTRAINT `fk_administrators_notifications_temporary_comments` FOREIGN KEY (`idUser`) REFERENCES `temporary_comments` (`id`);

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_articles` FOREIGN KEY (`idArticle`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `fk_comments_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `form_messages`
--
ALTER TABLE `form_messages`
  ADD CONSTRAINT `fk_form_messages_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `temporary_comments`
--
ALTER TABLE `temporary_comments`
  ADD CONSTRAINT `fk_temporary_comments_articles` FOREIGN KEY (`idArticle`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `fk_temporary_comments_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `visitors_notifications`
--
ALTER TABLE `visitors_notifications`
  ADD CONSTRAINT `fk_visitors_notifications_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
