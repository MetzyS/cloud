-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 05 juin 2023 à 11:49
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `plateforme_partage`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL,
  `category_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id_category`, `category_name`) VALUES
(1, 'images'),
(2, 'pdf'),
(3, 'word'),
(4, 'excel');

-- --------------------------------------------------------

--
-- Structure de la table `file`
--

CREATE TABLE `file` (
  `id_file` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `category_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déclencheurs `file`
--
DELIMITER $$
CREATE TRIGGER `delete_file` AFTER DELETE ON `file` FOR EACH ROW BEGIN
INSERT INTO file_log (action, action_time, path, updated_by)
VALUES('delete', NOW(), OLD.path, OLD.updated_by);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_file` AFTER INSERT ON `file` FOR EACH ROW BEGIN
INSERT INTO file_log (action, action_time, path, new_path, updated_by)
VALUES ('upload', NOW(), path, NEW.path, NEW.updated_by);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_file` AFTER UPDATE ON `file` FOR EACH ROW BEGIN
INSERT INTO file_log (action, action_time, path, updated_by, new_path)
VALUES('update', NOW(), OLD.path, NEW.updated_by, NEW.path);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `file_log`
--

CREATE TABLE `file_log` (
  `id` int(11) NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `new_path` varchar(255) DEFAULT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `folder`
--

CREATE TABLE `folder` (
  `id_folder` int(11) NOT NULL,
  `folder_name` varchar(45) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `folder_path` varchar(255) NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déclencheurs `folder`
--
DELIMITER $$
CREATE TRIGGER `delete_folder` AFTER DELETE ON `folder` FOR EACH ROW BEGIN
INSERT INTO folder_log (action, action_time, path, updated_by)
VALUES('delete', NOW(), OLD.folder_path, OLD.updated_by);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_folder` AFTER INSERT ON `folder` FOR EACH ROW BEGIN
INSERT INTO folder_log (action, action_time, new_path, updated_by)
VALUES('create', NOW(), NEW.folder_path, NEW.updated_by);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_folder` AFTER UPDATE ON `folder` FOR EACH ROW BEGIN
INSERT INTO folder_log (action, action_time, path, new_path, updated_by)
VALUES('update', NOW(), OLD.folder_path, NEW.folder_path, NEW.updated_by);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `folder_log`
--

CREATE TABLE `folder_log` (
  `id` int(11) NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `new_path` varchar(255) DEFAULT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `serv_infos`
--

CREATE TABLE `serv_infos` (
  `id_server` int(11) NOT NULL,
  `server_capacity` bigint(20) DEFAULT NULL,
  `current_storage` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `serv_infos`
--

INSERT INTO `serv_infos` (`id_server`, `server_capacity`, `current_storage`) VALUES
(1, 1073741824, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_surname` varchar(100) NOT NULL,
  `email` varchar(145) NOT NULL,
  `password` varchar(90) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL,
  `superadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `user_name`, `user_surname`, `email`, `password`, `created_at`, `role`, `superadmin`) VALUES
(7, 'Administrateur', 'Administrateur', 'admin@mail.com', '$2y$10$mwy1cUsiA3Y2PyiLaEjZ0ube6tHjvPRs2JBHPj09K9Rc3HHXFBD1m', '2023-04-25 10:22:59', 'admin', 0),
(9, 'user', 'user', 'user@mail.com', '$2y$10$liF3Xhxg1MCOzPSu2x9O5eSSAPEGKVYT60dz6mbCqrWyhn2n6QN5y', '2023-05-07 10:01:41', 'user', 0),
(10, 'Test', 'Compte', 'test@mail.com', 'Test123@***', '2023-06-05 11:45:11', 'user', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Index pour la table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id_file`),
  ADD UNIQUE KEY `path` (`path`),
  ADD KEY `fk_file_user1_idx` (`uploaded_by`),
  ADD KEY `fk_file_categorie_idx` (`category_id`) USING BTREE,
  ADD KEY `fk_file_folderid` (`folder_id`);

--
-- Index pour la table `file_log`
--
ALTER TABLE `file_log`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id_folder`),
  ADD UNIQUE KEY `folder_path` (`folder_path`),
  ADD KEY `fk_folder_folder1_idx` (`parent_id`),
  ADD KEY `fk_folder_user1_idx` (`created_by`);

--
-- Index pour la table `folder_log`
--
ALTER TABLE `folder_log`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `serv_infos`
--
ALTER TABLE `serv_infos`
  ADD PRIMARY KEY (`id_server`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `file`
--
ALTER TABLE `file`
  MODIFY `id_file` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT pour la table `file_log`
--
ALTER TABLE `file_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT pour la table `folder`
--
ALTER TABLE `folder`
  MODIFY `id_folder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `folder_log`
--
ALTER TABLE `folder_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `serv_infos`
--
ALTER TABLE `serv_infos`
  MODIFY `id_server` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`),
  ADD CONSTRAINT `fk_file_folderid` FOREIGN KEY (`folder_id`) REFERENCES `folder` (`id_folder`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_user1` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `folder`
--
ALTER TABLE `folder`
  ADD CONSTRAINT `fk_folder_folder1` FOREIGN KEY (`parent_id`) REFERENCES `folder` (`id_folder`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_folder_user1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
