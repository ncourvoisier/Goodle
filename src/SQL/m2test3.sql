-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mar. 29 sep. 2020 à 16:40
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
-- Structure de la table `Date`
--

CREATE TABLE `Date` (
  `ID` int(11) NOT NULL,
  `Jour` int(11) NOT NULL,
  `Mois` int(11) NOT NULL,
  `Annee` int(11) NOT NULL,
  `Heure` int(11) NOT NULL,
  `Minute` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `DateEvenement`
--

CREATE TABLE `DateEvenement` (
  `ID` int(11) NOT NULL,
  `IDEvent` int(11) NOT NULL,
  `IDDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Evenement`
--

CREATE TABLE `Evenement` (
  `ID` int(11) NOT NULL,
  `Nom` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Lieu` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Referent` int(11) NOT NULL,
  `DateCloture` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Invite`
--

CREATE TABLE `Invite` (
  `ID` int(11) NOT NULL,
  `IDEvent` int(11) NOT NULL,
  `IDPersonne` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

CREATE TABLE `Personne` (
  `ID` int(11) NOT NULL,
  `Nom` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Prenom` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Admin` tinyint(1) NOT NULL,
  `Username` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Email` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `EmailVerifie` tinyint(1) NOT NULL,
  `motDePasse` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DateNaissance` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Index pour la table `Date`
--
ALTER TABLE `Date`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `DateEvenement`
--
ALTER TABLE `DateEvenement`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `DateEvenement_Date` (`IDDate`),
  ADD KEY `DateEvenement_Evenement` (`IDEvent`);

--
-- Index pour la table `Evenement`
--
ALTER TABLE `Evenement`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Evenement_Personne` (`Referent`);

--
-- Index pour la table `Invite`
--
ALTER TABLE `Invite`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Invite_Personne` (`IDPersonne`),
  ADD KEY `Invite_Evenement` (`IDEvent`);

--
-- Index pour la table `Personne`
--
ALTER TABLE `Personne`
  ADD PRIMARY KEY (`ID`);

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
-- AUTO_INCREMENT pour la table `Date`
--
ALTER TABLE `Date`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `DateEvenement`
--
ALTER TABLE `DateEvenement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Evenement`
--
ALTER TABLE `Evenement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Invite`
--
ALTER TABLE `Invite`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Personne`
--
ALTER TABLE `Personne`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Reponse`
--
ALTER TABLE `Reponse`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `DateEvenement`
--
ALTER TABLE `DateEvenement`
  ADD CONSTRAINT `DateEvenement_Date` FOREIGN KEY (`IDDate`) REFERENCES `Date` (`ID`),
  ADD CONSTRAINT `DateEvenement_Evenement` FOREIGN KEY (`IDEvent`) REFERENCES `Evenement` (`ID`);

--
-- Contraintes pour la table `Evenement`
--
ALTER TABLE `Evenement`
  ADD CONSTRAINT `Evenement_Personne` FOREIGN KEY (`Referent`) REFERENCES `Personne` (`ID`);

--
-- Contraintes pour la table `Invite`
--
ALTER TABLE `Invite`
  ADD CONSTRAINT `Invite_Evenement` FOREIGN KEY (`IDEvent`) REFERENCES `Evenement` (`ID`),
  ADD CONSTRAINT `Invite_Personne` FOREIGN KEY (`IDPersonne`) REFERENCES `Personne` (`ID`);

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
