-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mar. 29 sep. 2020 à 16:32
-- Version du serveur :  10.3.23-MariaDB-0+deb10u1
-- Version de PHP :  7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `m2test3`
--

-- --------------------------------------------------------

--
-- Structure de la table `Reponse`
--

CREATE TABLE `Reponse` (
  `ID` int(11) NOT NULL,
  `IDDateEvent` int(11) NOT NULL,
  `IDInvite` int(11) NOT NULL,
  `Response` enum('Oui','Non','Peutetre','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Reponse`
--
ALTER TABLE `Reponse`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Response_Invite` (`IDInvite`),
  ADD KEY `Response_DateEvenement` (`IDDateEvent`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Reponse`
--
ALTER TABLE `Reponse`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Reponse`
--
ALTER TABLE `Reponse`
  ADD CONSTRAINT `Response_DateEvenement` FOREIGN KEY (`IDDateEvent`) REFERENCES `DateEvenement` (`ID`),
  ADD CONSTRAINT `Response_Invite` FOREIGN KEY (`IDInvite`) REFERENCES `Invite` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
