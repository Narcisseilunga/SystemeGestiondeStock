-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 01 jan. 2025 à 20:22
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `php_chat_app01`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id_msg` int(11) NOT NULL,
  `msg` varchar(250) DEFAULT NULL,
  `date_msg` datetime DEFAULT current_timestamp(),
  `user_msg_send` int(11) DEFAULT NULL,
  `user_msg_recep` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_msg`, `msg`, `date_msg`, `user_msg_send`, `user_msg_recep`) VALUES
(1, 'bla bla bla  1', '2024-12-31 15:44:55', 2, 1),
(2, 'bla bla bla  5', '2024-12-31 15:44:55', 2, 1),
(3, 'bla bla bla 2', '2024-12-31 15:44:55', 2, 1),
(4, 'bla bla bla  3', '2024-12-31 15:44:55', 2, 1),
(5, 'bla bla bla  4', '2024-12-31 15:44:55', 2, 8),
(6, 'bla bla bla  4', '2024-12-31 15:44:55', 2, 6),
(7, 'bla bla bla  4', '2024-12-31 15:44:55', 2, 1),
(8, 'bla bla bla  4', '2024-12-31 15:44:55', 8, 2),
(9, 'bla bla bla  4', '2024-12-31 15:44:55', 8, 2);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `etat` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'en ligne of ligne'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `img`, `password`, `etat`) VALUES
(9, 'nom1 nom 2 nom 3', '0000', '67706da58b52c_regle conduit.jpg', '$2y$10$Sn7T7pKA91vmE92Dwh7EJu5qGqsLQ3ycNhbdDJmjqwhcFVRjgfekq', 1),
(10, 'rabi3 2', '1111', '67706da58b52c_regle conduit.jpg', '$2y$10$Eh6.uIU.SnGbgBkVs/tWXuQMpPBvo5AZ8rEUee.eeO4fkLjEIM59u', 0),
(18, 'yassin', 'sqdsq@gmail.com', '67706da58b52c_regle conduit.jpg', '$2y$10$DuUw8WMpgzwyep2lDVVdIOdvb9/Q.FmFedxkCSjFV/qWonEmoHWLq', 1),
(19, 'medox', 'sdqsd@gmail.com', '67706db6867aa_40 km.JPG', '$2y$10$3SH6eDgvwG0aNbv8UMfMk.xfGjgTBBAis8.jn//w8qISjW7hlCj/e', 0),
(20, 'Youssef moden jama3', 'naaameone@gmail.com', '6770706b7e27b_regle conduit 3.jpg', '$2y$10$mhpujpz5i.ikcpAD4xjYEOef2t2Lz29pd/Zj0mtS4fIa5g//kgVCS', 1),
(21, 'mmm eee', 'email1@gmail.com', '6772ef22dbf7a_regle conduit 4.jpg', '$2y$10$2xXIIxnQtA37sy5hq5G5XOXZFMC4M.VQiFl/bbgGV5R8Jqt/23y3y', 0),
(22, 'hamid play', 'hamido@gmail.com', '677557fa57dc5_web.JPG', '$2y$10$viOK4SiF830HhDxRxkEUtebbI7qIy2uYP3WQawn2vOIDld3ET24zW', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_msg`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_msg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
