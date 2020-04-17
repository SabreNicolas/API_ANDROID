-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  ven. 17 avr. 2020 à 11:04
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `api-mobile`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `id` bigint(20) NOT NULL,
  `valeur` varchar(100) NOT NULL,
  `idEspace` bigint(20) NOT NULL,
  `idIndicateur` bigint(20) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `activite`
--

INSERT INTO `activite` (`id`, `valeur`, `idEspace`, `idIndicateur`, `date`) VALUES
(18, '500', 3, 13, '2020-04-15'),
(19, '0', 3, 15, '2020-04-15'),
(20, '500', 6, 13, '2020-04-15'),
(24, '500', 3, 13, '2020-04-14'),
(25, '0', 3, 15, '2020-04-14'),
(39, '500', 3, 13, '2020-04-01'),
(40, '1', 3, 15, '2020-04-01'),
(41, '750', 3, 13, '2020-04-02'),
(42, '0', 3, 15, '2020-04-02'),
(46, '1', 7, 15, '2020-04-16');

-- --------------------------------------------------------

--
-- Structure de la table `espace`
--

CREATE TABLE `espace` (
  `id` bigint(20) NOT NULL,
  `nomEspace` varchar(100) DEFAULT NULL,
  `idUser` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `espace`
--

INSERT INTO `espace` (`id`, `nomEspace`, `idUser`) VALUES
(1, 'Vélo', 1),
(2, 'Régime', 1),
(3, 'Tabac', 2),
(6, 'bus', 2),
(7, 'train', 2),
(8, 'course à pied', 2),
(9, 'course', 2),
(11, 'test maj espace 11', 25),
(16, 'espace 2 de test', 25),
(17, 'espace 3 de test', 25),
(18, 'espace 4 de test', 25);

--
-- Déclencheurs `espace`
--
DELIMITER $$
CREATE TRIGGER `espace_BEFORE_DELETE` BEFORE DELETE ON `espace` FOR EACH ROW BEGIN
DELETE FROM activite WHERE idEspace = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `indicateur`
--

CREATE TABLE `indicateur` (
  `id` bigint(20) NOT NULL,
  `nomIndicateur` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `valeurInit` varchar(100) DEFAULT NULL,
  `idUser` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `indicateur`
--

INSERT INTO `indicateur` (`id`, `nomIndicateur`, `type`, `valeurInit`, `idUser`) VALUES
(1, 'Nombre de kilomètres', 'number', '0', 1),
(2, 'Durée en minutes', 'number', '0', 1),
(3, 'Nombre de fruits et légumes', 'number', '0', 1),
(4, 'Grammes de protéines', 'number', '0', 1),
(13, 'Grammes de protéine', 'Menu déroulant', '500;750;800;1000;', 2),
(14, 'Nombre de pas', 'Champ de saisie', '0', 2),
(15, '10 000 pas', 'Oui ou Non', '0', 2),
(20, 'distance parcourue', 'Curseur', '0', 2),
(21, 'nb moyen de transport', 'Menu déroulant', 'a;b;c;d;e;', 2);

--
-- Déclencheurs `indicateur`
--
DELIMITER $$
CREATE TRIGGER `indicateur_BEFORE_DELETE` BEFORE DELETE ON `indicateur` FOR EACH ROW BEGIN
DELETE FROM activite WHERE idIndicateur = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `myview`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `myview` (
`idIndicateur` bigint(20)
,`idEspace` bigint(20)
,`idUser` bigint(20)
,`date` date
,`valeur` varchar(100)
,`nomIndicateur` varchar(100)
,`type` varchar(100)
,`valeurInit` varchar(100)
,`nomEspace` varchar(100)
,`login` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `login` varchar(100) NOT NULL,
  `passwd` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `login`, `passwd`) VALUES
(1, 'CHRETIEN', 'Maxence', 'max', 'max'),
(2, 'SABRE', 'Nicolas', 'nico', 'nico'),
(3, 'SZTUREMSKI', 'jean', 'jean', 'jean'),
(24, 'salut', 'salut', 'salut', 'salut'),
(25, 'test', 'test', 'test', 'test'),
(27, 't', 't', 't', 't'),
(28, 'a', 'a', 'a', 'a');

--
-- Déclencheurs `user`
--
DELIMITER $$
CREATE TRIGGER `user_BEFORE_DELETE` BEFORE DELETE ON `user` FOR EACH ROW BEGIN

	DELETE FROM espace WHERE idUser = OLD.id;
    DELETE FROM indicateur WHERE idUser = OLD.id;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la vue `myview`
--
DROP TABLE IF EXISTS `myview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `myview`  AS  select `i`.`id` AS `idIndicateur`,`e`.`id` AS `idEspace`,`u`.`id` AS `idUser`,`a`.`date` AS `date`,`a`.`valeur` AS `valeur`,`i`.`nomIndicateur` AS `nomIndicateur`,`i`.`type` AS `type`,`i`.`valeurInit` AS `valeurInit`,`e`.`nomEspace` AS `nomEspace`,`u`.`login` AS `login` from (((`user` `u` join `espace` `e`) join `indicateur` `i`) join `activite` `a`) where ((`u`.`id` = `e`.`idUser`) and (`e`.`id` = `a`.`idEspace`) and (`i`.`id` = `a`.`idIndicateur`)) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activite_id_uindex` (`id`),
  ADD UNIQUE KEY `un_espace_par_jour_unique_espace_date` (`idEspace`,`idIndicateur`,`date`),
  ADD KEY `activite_idindicateur_id_fk` (`idIndicateur`);

--
-- Index pour la table `espace`
--
ALTER TABLE `espace`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `espace_id_uindex` (`id`),
  ADD KEY `espace_iduser_id_fk` (`idUser`);

--
-- Index pour la table `indicateur`
--
ALTER TABLE `indicateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `indicateur_id_uindex` (`id`),
  ADD KEY `userIndicateur` (`idUser`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_uindex` (`id`),
  ADD UNIQUE KEY `user_login_uindex` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activite`
--
ALTER TABLE `activite`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `espace`
--
ALTER TABLE `espace`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `indicateur`
--
ALTER TABLE `indicateur`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `activite_idespace_id_fk` FOREIGN KEY (`idEspace`) REFERENCES `espace` (`id`),
  ADD CONSTRAINT `activite_idindicateur_id_fk` FOREIGN KEY (`idIndicateur`) REFERENCES `indicateur` (`id`);

--
-- Contraintes pour la table `espace`
--
ALTER TABLE `espace`
  ADD CONSTRAINT `espace_iduser_id_fk` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `indicateur`
--
ALTER TABLE `indicateur`
  ADD CONSTRAINT `userIndicateur` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`);
