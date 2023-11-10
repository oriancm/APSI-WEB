-- Create a new database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `apsi`;

-- Use the `apsi` database
USE `apsi`;

-- Drop the existing tables if they exist
DROP TABLE IF EXISTS `admin`;
DROP TABLE IF EXISTS `photo`;
DROP TABLE IF EXISTS `reference`;

-- Create the `admin` table
CREATE TABLE `admin` (
                         `login` VARCHAR(50) NOT NULL,
                         `pass` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Create the `reference` table
CREATE TABLE `reference` (
                             `id` INT NOT NULL AUTO_INCREMENT,
                             `titre` VARCHAR(100) NOT NULL,
                             `commune` VARCHAR(100),
                             `description` TEXT,
                             `domaine` INT,
                             `anneeD` YEAR,
                             `anneeF` YEAR,
                             `statut` INT,
                             `moa` VARCHAR(100),
                             `archi` VARCHAR(100),
                             `eMoe` VARCHAR(100),
                             `nbPhase` INT,
                             `montant` DECIMAL(10, 2),
                             `nbE` INT,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Create the `photo` table
CREATE TABLE `photo` (
                         `id` INT NOT NULL AUTO_INCREMENT,
                         `titre` VARCHAR(100) NOT NULL,
                         `dir` VARCHAR(200) NOT NULL,
                         `idR` INT,
                         PRIMARY KEY (`id`),
                         FOREIGN KEY (`idR`) REFERENCES `reference`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Insert the admin data
INSERT INTO `admin` (`login`, `pass`) VALUES
    ('admin', '$2y$10$R.1dlwWWa055qCBl7CZN3u5ir/EKHZ9qVqt.6pqNGYJ3OUDMfeKtW');

-- Indexes, AUTO_INCREMENT, and other database operations
-- ...

-- Add your PHP code here
-- ...

-- Finally, commit the changes
COMMIT;
