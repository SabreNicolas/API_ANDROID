drop database IF EXISTS `api-mobile`;

CREATE DATABASE `api-mobile`;
USE `api-mobile`;

DROP table IF EXISTS activite;
DROP table IF EXISTS indicateur;
DROP table IF EXISTS espace;
DROP table IF EXISTS user;

CREATE TABLE `user` (
  `id`           BIGINT(20) NOT NULL AUTO_INCREMENT,
  `nom`        VARCHAR(100),
  `prenom`  VARCHAR(100),
  `login`  VARCHAR(100)  NOT NULL,
  `passwd`  VARCHAR(100),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_uindex` (`id`),
  UNIQUE KEY `user_login_uindex` (`login`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `espace` (
  `id`  BIGINT(20) NOT NULL AUTO_INCREMENT,
  `nomEspace`  VARCHAR(100),
  `idUser` BIGINT(20),
  PRIMARY KEY (`id`),
  UNIQUE KEY `espace_id_uindex` (`id`),
  CONSTRAINT `espace_iduser_id_fk` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
  
  CREATE TABLE `indicateur` (
  `id`  BIGINT(20) NOT NULL AUTO_INCREMENT,
  `nomIndicateur`  VARCHAR(100)  NOT NULL,
  `type`  VARCHAR(100)  NOT NULL,
  `valeurInit`  VARCHAR(100),
  `idUser` BIGINT(20),
  PRIMARY KEY (`id`),
  UNIQUE KEY `indicateur_id_uindex` (`id`),
  CONSTRAINT `userIndicateur` FOREIGN KEY (`idUser`) REFERENCES `user`(`id`)
 )
 ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
  
    CREATE TABLE `activite` (
  `id`  BIGINT(20) NOT NULL AUTO_INCREMENT,
  `valeur`  VARCHAR(100)  NOT NULL,
  `idEspace`  BIGINT(20)  NOT NULL,
  `idIndicateur`  BIGINT(20) NOT NULL,
  `date` DATE NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activite_id_uindex` (`id`),
  CONSTRAINT `activite_idespace_id_fk` FOREIGN KEY (`idEspace`) REFERENCES `espace` (`id`),
  CONSTRAINT `activite_idindicateur_id_fk` FOREIGN KEY (`idIndicateur`) REFERENCES `indicateur` (`id`),
  CONSTRAINT `un_espace_par_jour_unique_espace_date` UNIQUE (`idEspace`,`idIndicateur`,`date`)
 )
 ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


INSERT INTO user(id,nom,prenom,login,passwd) VALUES 
(1,'CHRETIEN','Maxence','max','max'),
(2,'SABRE','Nicolas','nico','nico');

INSERT INTO espace (id,nomEspace,idUser) VALUES 
(1,'Vélo',1),
(2,'Régime',1),
(3,'Tabac',2),
(4,'Ecole',2);

INSERT INTO indicateur (id,nomIndicateur,type,valeurInit,idUser) VALUES 
(1,'Nombre de kilomètres','number','0',1),
(2,'Durée en minutes','number','0',1),
(3,'Nombre de fruits et légumes','number','0',1),
(4,'Grammes de protéines','number','0',1),
(5,'Nombre de cigarettes fumées','number','0',2),
(6,'Paquet acheté','boolean','0',2),
(7,'Temps de révision en minutes','number','0',2),
(8,'Temps d exercices en minutes','number','0',2);

INSERT INTO activite (id,valeur,idEspace,idIndicateur,date) VALUES 
(1,'60',1,1,'2020-02-01'),
(2,'30',1,2,'2020-02-01'),
(3,'75',1,1,'2020-02-07'),
(4,'40',1,2,'2020-02-07'),
(5,'4',2,3,'2020-02-01'),
(6,'200',2,4,'2020-02-01'),
(7,'6',2,3,'2020-02-07'),
(8,'300',2,4,'2020-02-07'),
(9,'5',3,5,'2020-02-01'),
(10,'true',3,6,'2020-02-01'),
(11,'2',3,5,'2020-02-07'),
(12,'false',3,6,'2020-02-07'),
(13,'60',4,7,'2020-02-01'),
(14,'40',4,8,'2020-02-01'),
(15,'80',4,7,'2020-02-07'),
(16,'40',4,8,'2020-02-07');

DELIMITER //
CREATE VIEW myview (idIndicateur,idEspace,idUser,date,valeur,nomIndicateur,type,valeurInit,nomEspace,login)
AS SELECT i.id,e.id,u.id,date,valeur,nomIndicateur,type,valeurInit,nomEspace,login
FROM user u,espace e,indicateur i , activite a
WHERE u.id=e.idUser
AND e.id=a.idEspace
AND i.id=a.idIndicateur
//

DELIMITER //
CREATE DEFINER=`root`@`localhost` TRIGGER `api-mobile`.`espace_BEFORE_DELETE` BEFORE DELETE ON `espace` FOR EACH ROW
BEGIN
DELETE FROM activite WHERE idEspace = OLD.id;
END
//

DELIMITER //
CREATE DEFINER = CURRENT_USER TRIGGER `api-mobile`.`indicateur_BEFORE_DELETE` BEFORE DELETE ON `indicateur` FOR EACH ROW
BEGIN
DELETE FROM activite WHERE idIndicateur = OLD.id;
END
//


