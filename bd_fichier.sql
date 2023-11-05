CREATE DATABASE IF NOT EXISTS `bd_fichier` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `bd_fichier`;

DROP TABLE IF EXISTS `fichiers`;
CREATE TABLE `fichiers` (
    `id` int NOT NULL AUTO_INCREMENT,
    `nom` nvarchar(255) NOT NULL,
    `extension` nvarchar(8) NOT NULL,
    `description` nvarchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
    `id` int NOT NULL AUTO_INCREMENT,
    `nom` nvarchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `permissions_fichiers_utilisateurs`;
CREATE TABLE `permissions_fichiers_utilisateurs` (
    `id` int NOT NULL AUTO_INCREMENT,
    `utilisateur` int NOT NULL,
    `fichier` int NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_permissions_fichiers_utilisateurs_utilisateurs` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`id`),
    CONSTRAINT `fk_permissions_fichiers_utilisateurs_fichiers` FOREIGN KEY (`fichier`) REFERENCES `fichiers` (`id`)
) ENGINE=InnoDB;

INSERT INTO `fichiers` (`id`, `nom`, `extension`, `description`) VALUES
(1, 'pikachu', 'png', 'Pikachu'),
(2, 'raichu', 'png', 'Raichu');

INSERT INTO `utilisateurs` (`id`, `nom`) VALUES
(1, 'admin'),
(2, 'user');

INSERT INTO `permissions_fichiers_utilisateurs` (`id`, `utilisateur`, `fichier`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1);