-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           10.1.13-MariaDB - mariadb.org binary distribution
-- SE du serveur:                Win32
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Listage de la structure de la vue peche_db. req_1
DROP VIEW IF EXISTS `req_1`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_1` (
	`code_unique` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`date` DATE NULL,
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`capture` DOUBLE NULL,
	`cpue` DOUBLE NULL,
	`id_region` INT(11) NOT NULL,
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`id_espece` INT(11) NOT NULL,
	`id_site_embarquement` INT(11) NOT NULL,
	`id_district` INT(11) NOT NULL,
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_1_stocke
DROP PROCEDURE IF EXISTS `req_1_stocke`;
DELIMITER //
CREATE PROCEDURE `req_1_stocke`(
	IN `Paramidregion` INT,
	IN `Paramannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
    DETERMINISTIC
BEGIN
	IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	
		SELECT code_unique AS 'Code unique',
				 nom_region AS 'Nom region',
				 nom_site_embarquement AS 'Site embarquement',
				 date AS date,
				 libelle_unite_peche AS 'Unite peche',
				 capture AS 'Capture',
				 cpue AS 'cpue'
			 
			 FROM req_1
			 WHERE id_region=Paramidregion AND 
			 			annee= Paramannee AND 
						id_district=Paramiddistrict AND 
						id_site_embarquement=Paramidsiteembarquement;
	ELSE IF(Paramiddistrict!=0) THEN
	
			SELECT code_unique AS 'Code unique',
						nom_region AS 'Nom region',
						 nom_site_embarquement AS 'Site embarquement',
						date AS date,
						libelle_unite_peche AS 'Unite peche',
						capture AS 'Capture',
						cpue AS 'cpue'
					 
					 FROM req_1
					 WHERE id_region=Paramidregion AND 
					 			annee= Paramannee AND 
								id_district=Paramiddistrict;					
		
				ELSE SELECT code_unique AS 'Code unique',
						nom_region AS 'Nom region',
						 nom_site_embarquement AS 'Site embarquement',
						date AS date,
						libelle_unite_peche AS 'Unite peche',
						capture AS 'Capture',
						cpue AS 'cpue'
					 
					 FROM req_1
					 WHERE id_region=Paramidregion AND 
					 			annee= Paramannee;
			END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_2
DROP VIEW IF EXISTS `req_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_2` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`average_cpue` DOUBLE NULL,
	`stddev_cpue` DOUBLE NULL,
	`nbr_cpue` BIGINT(21) NOT NULL,
	`sqrt_cpue` DOUBLE NULL,
	`degree_liberte` BIGINT(22) NOT NULL,
	`id_site_embarquement` INT(11) NOT NULL,
	`id_district` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_2_stocke
DROP PROCEDURE IF EXISTS `req_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_2_stocke`(
	IN `Paramsidregion` INT,
	IN `Paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	req2.annee AS 'Annee',
				req2.mois AS 'Mois',
				req2.nom_region AS 'Nom region',
				req2.libelle_unite_peche AS 'Unite peche',
				req2.average_cpue AS 'Average cpue',
				req2.stddev_cpue AS 'Stddev cpue',
				req2.nbr_cpue AS 'Nombre cpue',
				req2.sqrt_cpue AS 'Sqrt cpue',
				req2.degree_liberte AS  'Degree liberte'
				
				FROM 
				(
					SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
							DATE_FORMAT(req1.date,"%c") AS mois,
							req1.nom_region AS nom_region,
							req1.libelle_unite_peche AS libelle_unite_peche,
							AVG(req1.cpue) AS average_cpue,
							STDDEV_SAMP(req1.cpue) AS stddev_cpue,
							COUNT(req1.cpue) AS nbr_cpue,
							SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
							COUNT(req1.cpue)-1 AS degree_liberte
							
							FROM 
								(
									SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											fiche_echantillonnage_capture.date AS date,
											unite_peche.libelle AS libelle_unite_peche,
											SUM(espece_capture.capture) AS capture,
											SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
												
												FROM unite_peche
													INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
													INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
													INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
													INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
													INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
													INNER JOIN district ON district.id = fiche_echantillonnage_capture.id_district
													
													WHERE fiche_echantillonnage_capture.validation=1 
															AND region.id=Paramsidregion 
															AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=Paramsannee
															AND district.id=Paramiddistrict
															AND site_embarquement.id=Paramidsiteembarquement
													
													GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
													site_embarquement.id,echantillon.id
							) AS req1
							GROUP BY annee,mois,nom_region,libelle_unite_peche
				) AS req2
				GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche  ;
	ELSE IF(Paramiddistrict!=0) THEN
				SELECT 	req2.annee AS 'Annee',
				req2.mois AS 'Mois',
				req2.nom_region AS 'Nom region',
				req2.libelle_unite_peche AS 'Unite peche',
				req2.average_cpue AS 'Average cpue',
				req2.stddev_cpue AS 'Stddev cpue',
				req2.nbr_cpue AS 'Nombre cpue',
				req2.sqrt_cpue AS 'Sqrt cpue',
				req2.degree_liberte AS  'Degree liberte'
				
				FROM 
				(
					SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
							DATE_FORMAT(req1.date,"%c") AS mois,
							req1.nom_region AS nom_region,
							req1.libelle_unite_peche AS libelle_unite_peche,
							AVG(req1.cpue) AS average_cpue,
							STDDEV_SAMP(req1.cpue) AS stddev_cpue,
							COUNT(req1.cpue) AS nbr_cpue,
							SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
							COUNT(req1.cpue)-1 AS degree_liberte
							
							FROM 
								(
									SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											fiche_echantillonnage_capture.date AS date,
											unite_peche.libelle AS libelle_unite_peche,
											SUM(espece_capture.capture) AS capture,
											SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
												
												FROM unite_peche
													INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
													INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
													INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
													INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
													INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
													INNER JOIN district ON district.id = fiche_echantillonnage_capture.id_district
													
													WHERE fiche_echantillonnage_capture.validation=1 
															AND region.id=Paramsidregion 
															AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=Paramsannee
															AND district.id=Paramiddistrict
													
													GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
													site_embarquement.id,echantillon.id
							) AS req1
							GROUP BY annee,mois,nom_region,libelle_unite_peche
				) AS req2
				GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche  ;
				
			ELSE
			
			SELECT 	req2.annee AS 'Annee',
				req2.mois AS 'Mois',
				req2.nom_region AS 'Nom region',
				req2.libelle_unite_peche AS 'Unite peche',
				req2.average_cpue AS 'Average cpue',
				req2.stddev_cpue AS 'Stddev cpue',
				req2.nbr_cpue AS 'Nombre cpue',
				req2.sqrt_cpue AS 'Sqrt cpue',
				req2.degree_liberte AS  'Degree liberte'
				
				FROM 
				(
					SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
							DATE_FORMAT(req1.date,"%c") AS mois,
							req1.nom_region AS nom_region,
							req1.libelle_unite_peche AS libelle_unite_peche,
							AVG(req1.cpue) AS average_cpue,
							STDDEV_SAMP(req1.cpue) AS stddev_cpue,
							COUNT(req1.cpue) AS nbr_cpue,
							SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
							COUNT(req1.cpue)-1 AS degree_liberte
							
							FROM 
								(
									SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											fiche_echantillonnage_capture.date AS date,
											unite_peche.libelle AS libelle_unite_peche,
											SUM(espece_capture.capture) AS capture,
											SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
												
												FROM unite_peche
													INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
													INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
													INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
													INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
													INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
													INNER JOIN district ON district.id = fiche_echantillonnage_capture.id_district
													
													WHERE fiche_echantillonnage_capture.validation=1 
															AND region.id=Paramsidregion 
															AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=Paramsannee
													
													GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
													site_embarquement.id,echantillon.id
							) AS req1
							GROUP BY annee,mois,nom_region,libelle_unite_peche
				) AS req2
				GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche  ;
			END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_3
DROP VIEW IF EXISTS `req_3`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_3` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`average_cpue` DOUBLE NULL,
	`stdev_cpue` DOUBLE NULL,
	`sqrt_cpue` DOUBLE NULL,
	`percentFractile90` FLOAT NULL,
	`clcpue` DOUBLE NULL,
	`relErreurCPUE90` DOUBLE NULL,
	`nbr_cpue` BIGINT(21) NOT NULL,
	`max_cpue` DOUBLE NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_3_stocke
DROP PROCEDURE IF EXISTS `req_3_stocke`;
DELIMITER //
CREATE PROCEDURE `req_3_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	req2stoke.annee AS 'Annee',
			req2stoke.mois AS 'Mois',
			req2stoke.nom_region AS 'Nom region',
			req2stoke.libelle_unite_peche AS 'Unite peche',
			req2stoke.average_cpue AS 'Average cpue',
			req2stoke.stddev_cpue AS 'Stdev cpue',
			req2stoke.sqrt_cpue AS 'Sqrt cpue',
			distribution_fractile.PercentFractile90 AS 'Percent Fractile 90',
			((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS 'Clcpue',
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS 'relErreurCPUE90',
			req2stoke.nbr_cpue AS nbr_cpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS 'Max cpue'
			FROM distribution_fractile
			
			INNER JOIN (SELECT 	req2.annee AS annee,
			req2.mois AS mois,
			req2.nom_region AS nom_region,
			req2.libelle_unite_peche AS libelle_unite_peche,
			req2.average_cpue AS average_cpue,
			req2.stddev_cpue AS stddev_cpue,
			req2.nbr_cpue AS nbr_cpue,
			req2.sqrt_cpue AS sqrt_cpue,
			req2.degree_liberte AS  degree_liberte
			
			FROM 
			(
				SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
						DATE_FORMAT(req1.date,"%c") AS mois,
						req1.nom_region AS nom_region,
						req1.libelle_unite_peche AS libelle_unite_peche,
						AVG(req1.cpue) AS average_cpue,
						STDDEV_SAMP(req1.cpue) AS stddev_cpue,
						COUNT(req1.cpue) AS nbr_cpue,
						SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
						COUNT(req1.cpue)-1 AS degree_liberte
						
						FROM 
							(
								SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										fiche_echantillonnage_capture.date AS date,
										unite_peche.libelle AS libelle_unite_peche,
										SUM(espece_capture.capture) AS capture,
										SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
											
											FROM unite_peche
												INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
												INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
												INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
												INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
												INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
												INNER JOIN district ON district.id = fiche_echantillonnage_capture.id_district
												
												WHERE fiche_echantillonnage_capture.validation=1 
														AND region.id=paramsidregion 
														AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee 
														AND district.id=Paramiddistrict 
														AND site_embarquement.id=Paramidsiteembarquement
												
												GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
												site_embarquement.id,echantillon.id
						) AS req1
						GROUP BY annee,mois,nom_region,libelle_unite_peche
			) AS req2
			GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom;

ELSE IF(Paramiddistrict!=0) THEN
			SELECT 	req2stoke.annee AS 'Annee',
						req2stoke.mois AS 'Mois',
						req2stoke.nom_region AS 'Nom region',
						req2stoke.libelle_unite_peche AS 'Unite peche',
						req2stoke.average_cpue AS 'Average cpue',
						req2stoke.stddev_cpue AS 'Stdev cpue',
						req2stoke.sqrt_cpue AS 'Sqrt cpue',
						distribution_fractile.PercentFractile90 AS 'Percent Fractile 90',
						((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS 'Clcpue',
						(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS 'relErreurCPUE90',
						req2stoke.nbr_cpue AS nbr_cpue,
						(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS 'Max cpue'
						FROM distribution_fractile
						
						INNER JOIN (SELECT 	req2.annee AS annee,
						req2.mois AS mois,
						req2.nom_region AS nom_region,
						req2.libelle_unite_peche AS libelle_unite_peche,
						req2.average_cpue AS average_cpue,
						req2.stddev_cpue AS stddev_cpue,
						req2.nbr_cpue AS nbr_cpue,
						req2.sqrt_cpue AS sqrt_cpue,
						req2.degree_liberte AS  degree_liberte
						
						FROM 
						(
							SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
									DATE_FORMAT(req1.date,"%c") AS mois,
									req1.nom_region AS nom_region,
									req1.libelle_unite_peche AS libelle_unite_peche,
									AVG(req1.cpue) AS average_cpue,
									STDDEV_SAMP(req1.cpue) AS stddev_cpue,
									COUNT(req1.cpue) AS nbr_cpue,
									SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
									COUNT(req1.cpue)-1 AS degree_liberte
									
									FROM 
										(
											SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
													region.nom AS nom_region,
													site_embarquement.libelle AS nom_site_embarquement,
													fiche_echantillonnage_capture.date AS date,
													unite_peche.libelle AS libelle_unite_peche,
													SUM(espece_capture.capture) AS capture,
													SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
														
														FROM unite_peche
															INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
															INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
															INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
															INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
															INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
															INNER JOIN district ON district.id = fiche_echantillonnage_capture.id_district
															
															WHERE fiche_echantillonnage_capture.validation=1 
																	AND region.id=paramsidregion 
																	AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee 
																	AND district.id=Paramiddistrict 
															
															GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
															site_embarquement.id,echantillon.id
									) AS req1
									GROUP BY annee,mois,nom_region,libelle_unite_peche
						) AS req2
						GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom;
			ELSE
				SELECT 	req2stoke.annee AS 'Annee',
							req2stoke.mois AS 'Mois',
							req2stoke.nom_region AS 'Nom region',
							req2stoke.libelle_unite_peche AS 'Unite peche',
							req2stoke.average_cpue AS 'Average cpue',
							req2stoke.stddev_cpue AS 'Stdev cpue',
							req2stoke.sqrt_cpue AS 'Sqrt cpue',
							distribution_fractile.PercentFractile90 AS 'Percent Fractile 90',
							((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS 'Clcpue',
							(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS 'relErreurCPUE90',
							req2stoke.nbr_cpue AS nbr_cpue,
							(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS 'Max cpue'
							FROM distribution_fractile
							
							INNER JOIN (SELECT 	req2.annee AS annee,
							req2.mois AS mois,
							req2.nom_region AS nom_region,
							req2.libelle_unite_peche AS libelle_unite_peche,
							req2.average_cpue AS average_cpue,
							req2.stddev_cpue AS stddev_cpue,
							req2.nbr_cpue AS nbr_cpue,
							req2.sqrt_cpue AS sqrt_cpue,
							req2.degree_liberte AS  degree_liberte
							
							FROM 
							(
								SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
										DATE_FORMAT(req1.date,"%c") AS mois,
										req1.nom_region AS nom_region,
										req1.libelle_unite_peche AS libelle_unite_peche,
										AVG(req1.cpue) AS average_cpue,
										STDDEV_SAMP(req1.cpue) AS stddev_cpue,
										COUNT(req1.cpue) AS nbr_cpue,
										SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
										COUNT(req1.cpue)-1 AS degree_liberte
										
										FROM 
											(
												SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
														region.nom AS nom_region,
														site_embarquement.libelle AS nom_site_embarquement,
														fiche_echantillonnage_capture.date AS date,
														unite_peche.libelle AS libelle_unite_peche,
														SUM(espece_capture.capture) AS capture,
														SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
															
															FROM unite_peche
																INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
																INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
																INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
																INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN district ON district.id = fiche_echantillonnage_capture.id_district
																
																WHERE fiche_echantillonnage_capture.validation=1 
																		AND region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee 
																
																GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
																site_embarquement.id,echantillon.id
										) AS req1
										GROUP BY annee,mois,nom_region,libelle_unite_peche
							) AS req2
							GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_4
DROP VIEW IF EXISTS `req_4`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_4` (
	`annee` INT(4) NULL,
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`nbr_unite_peche` DECIMAL(32,0) NULL,
	`id_region` INT(11) NOT NULL,
	`id_district` INT(11) NULL,
	`id_site_embarquement` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la vue peche_db. req_4_2
DROP VIEW IF EXISTS `req_4_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_4_2` (
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`nbr_unite_peche` DECIMAL(32,0) NULL,
	`annee` INT(4) NULL,
	`id_region` INT(11) NOT NULL,
	`id_site_embarquement` INT(11) NOT NULL,
	`id_district` INT(11) NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_4_2_stocke
DROP PROCEDURE IF EXISTS `req_4_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_4_2_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	nom_region AS 'Nom region',
				libelle_unite_peche AS 'Unite peche',
				sum(nbr_unite_peche) AS 'Nombre unite peche',
				annee AS 'Annee'
	 
		FROM req_4_2
		WHERE id_region=paramsidregion 
				AND annee=paramsannee
				AND id_district=Paramiddistrict
				AND id_site_embarquement=Paramidsiteembarquement
		
		GROUP BY req_4_2.annee ,req_4_2.nom_region,req_4_2.libelle_unite_peche ;
		
ELSE IF(Paramiddistrict!=0) THEN
			SELECT 	nom_region AS 'Nom region',
						libelle_unite_peche AS 'Unite peche',
						sum(nbr_unite_peche) AS 'Nombre unite peche',
						annee AS 'Annee'
			 
				FROM req_4_2
				WHERE id_region=paramsidregion 
						AND annee=paramsannee
						AND id_district=Paramiddistrict
				
				GROUP BY req_4_2.annee ,req_4_2.nom_region,req_4_2.libelle_unite_peche ;
		ELSE
		
			SELECT 	nom_region AS 'Nom region',
						libelle_unite_peche AS 'Unite peche',
						sum(nbr_unite_peche) AS 'Nombre unite peche',
						annee AS 'Annee'
			 
				FROM req_4_2
				WHERE id_region=paramsidregion 
						AND annee=paramsannee
				
				GROUP BY req_4_2.annee ,req_4_2.nom_region,req_4_2.libelle_unite_peche ;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure peche_db. req_4_stocke
DROP PROCEDURE IF EXISTS `req_4_stocke`;
DELIMITER //
CREATE PROCEDURE `req_4_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
	IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
		SELECT annee AS 'Annee',
				nom_region AS 'Nom region',
				nom_site_embarquement AS 'Site embarquement',
				libelle_unite_peche AS 'Unite peche',
				nbr_unite_peche AS 'Nombre unite peche'
		 
			FROM req_4
			WHERE id_region=paramsidregion 
					AND annee=paramsannee
					AND id_district=Paramiddistrict
					AND id_site_embarquement=Paramidsiteembarquement;
		ELSE IF(Paramiddistrict!=0) THEN
				SELECT annee AS 'Annee',
							nom_region AS 'Nom region',
							nom_site_embarquement AS 'Site embarquement',
							libelle_unite_peche AS 'Unite peche',
							nbr_unite_peche AS 'Nombre unite peche'
					 
						FROM req_4
						WHERE id_region=paramsidregion 
								AND annee=paramsannee
								AND id_district=Paramiddistrict;
								
				ELSE
				
					SELECT annee AS 'Annee',
								nom_region AS 'Nom region',
								nom_site_embarquement AS 'Site embarquement',
								libelle_unite_peche AS 'Unite peche',
								nbr_unite_peche AS 'Nombre unite peche'
						 
							FROM req_4
							WHERE id_region=paramsidregion 
									AND annee=paramsannee;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_5_1
DROP VIEW IF EXISTS `req_5_1`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_5_1` (
	`code_unique` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`date` DATE NULL,
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`peche_hier` INT(1) NULL,
	`peche_avant_hier` INT(1) NULL,
	`nbr_jrs_peche_dernier_sem` INT(3) NOT NULL,
	`pab` DECIMAL(17,4) NULL,
	`id_region` INT(11) NOT NULL,
	`id_site_embarquement` INT(11) NOT NULL,
	`id_district` INT(11) NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_5_1_stocke
DROP PROCEDURE IF EXISTS `req_5_1_stocke`;
DELIMITER //
CREATE PROCEDURE `req_5_1_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
	IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
		SELECT code_unique AS 'Code unique',
				date AS date,
				nom_region AS 'Region',
				nom_site_embarquement AS 'Site embarquement',
				libelle_unite_peche AS 'unite peche',
				peche_hier AS 'Peche hier',
				peche_avant_hier AS 'Peche avant hier',
				nbr_jrs_peche_dernier_sem AS 'Nombre jrs peche derniere semaine',
				pab AS 'PAB'
				FROM req_5_1
				WHERE id_region=paramsidregion 
						AND DATE_FORMAT(DATE,"%Y")=paramsannee
						AND id_district=Paramiddistrict
						AND id_site_embarquement=Paramidsiteembarquement;
	ELSE IF(Paramiddistrict!=0) THEN
				SELECT code_unique AS 'Code unique',
							date AS date,
							nom_region AS 'Region',
							nom_site_embarquement AS 'Site embarquement',
							libelle_unite_peche AS 'unite peche',
							peche_hier AS 'Peche hier',
							peche_avant_hier AS 'Peche avant hier',
							nbr_jrs_peche_dernier_sem AS 'Nombre jrs peche derniere semaine',
							pab AS 'PAB'
							FROM req_5_1
							WHERE id_region=paramsidregion 
									AND DATE_FORMAT(DATE,"%Y")=paramsannee
									AND id_district=Paramiddistrict;
			ELSE
			SELECT code_unique AS 'Code unique',
							date AS date,
							nom_region AS 'Region',
							nom_site_embarquement AS 'Site embarquement',
							libelle_unite_peche AS 'unite peche',
							peche_hier AS 'Peche hier',
							peche_avant_hier AS 'Peche avant hier',
							nbr_jrs_peche_dernier_sem AS 'Nombre jrs peche derniere semaine',
							pab AS 'PAB'
							FROM req_5_1
							WHERE id_region=paramsidregion 
									AND DATE_FORMAT(DATE,"%Y")=paramsannee;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_5_2
DROP VIEW IF EXISTS `req_5_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_5_2` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`average_pab` DECIMAL(21,8) NULL,
	`stddev_pab` DOUBLE(40,8) NULL,
	`nbr_pab` BIGINT(21) NOT NULL,
	`NbMonthlyFishingDaysFAC` DECIMAL(24,9) NULL,
	`degree_liberte` BIGINT(22) NOT NULL,
	`sqrt_pab` DOUBLE NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_5_2_stocke
DROP PROCEDURE IF EXISTS `req_5_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_5_2_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
	IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
		SELECT 	DATE_FORMAT(req51.date,"%Y") AS 'Annee',
					DATE_FORMAT(req51.date,"%c") AS 'Mois',
					req51.nom_region AS 'Nom region',
					req51.libelle_unite_peche AS 'Unite peche',
					AVG(req51.pab) AS 'Average pab',
					STDDEV_SAMP(req51.pab) AS 'stddev pab',
					COUNT(req51.pab) AS 'Nombre pab',
					(AVG(req51.pab)*30.5) AS 'NbMonthlyFishingDaysFAC',
					(COUNT(req51.pab)-1) AS 'Degree liberte',
					SQRT(COUNT(req51.pab)) AS 'sqrt pab'
					
					FROM 
						(
							SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
										fiche_echantillonnage_capture.date AS date,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										unite_peche.libelle AS libelle_unite_peche,
										echantillon.peche_hier AS peche_hier,
										echantillon.peche_avant_hier AS peche_avant_hier,
										echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
										((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
									
									FROM fiche_echantillonnage_capture
										INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
										INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
										INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
										INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
										WHERE	region.id=paramsidregion 
												AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
												AND site_embarquement.id=Paramidsiteembarquement 
												AND site_embarquement.id_district=Paramiddistrict
						) as req51 
					GROUP BY annee,mois,nom_region,libelle_unite_peche; 
	ELSE IF(Paramiddistrict!=0) THEN
		SELECT 	DATE_FORMAT(req51.date,"%Y") AS 'Annee',
					DATE_FORMAT(req51.date,"%c") AS 'Mois',
					req51.nom_region AS 'Nom region',
					req51.libelle_unite_peche AS 'Unite peche',
					AVG(req51.pab) AS 'Average pab',
					STDDEV_SAMP(req51.pab) AS 'stddev pab',
					COUNT(req51.pab) AS 'Nombre pab',
					(AVG(req51.pab)*30.5) AS 'NbMonthlyFishingDaysFAC',
					(COUNT(req51.pab)-1) AS 'Degree liberte',
					SQRT(COUNT(req51.pab)) AS 'sqrt pab'
					
					FROM 
						(
							SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
										fiche_echantillonnage_capture.date AS date,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										unite_peche.libelle AS libelle_unite_peche,
										echantillon.peche_hier AS peche_hier,
										echantillon.peche_avant_hier AS peche_avant_hier,
										echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
										((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
									
									FROM fiche_echantillonnage_capture
										INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
										INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
										INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
										INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
										WHERE	region.id=paramsidregion 
												AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee 
												AND site_embarquement.id_district=Paramiddistrict
						) as req51 
					GROUP BY annee,mois,nom_region,libelle_unite_peche;
				ELSE
					SELECT 	DATE_FORMAT(req51.date,"%Y") AS 'Annee',
						DATE_FORMAT(req51.date,"%c") AS 'Mois',
						req51.nom_region AS 'Nom region',
						req51.libelle_unite_peche AS 'Unite peche',
						AVG(req51.pab) AS 'Average pab',
						STDDEV_SAMP(req51.pab) AS 'stddev pab',
						COUNT(req51.pab) AS 'Nombre pab',
						(AVG(req51.pab)*30.5) AS 'NbMonthlyFishingDaysFAC',
						(COUNT(req51.pab)-1) AS 'Degree liberte',
						SQRT(COUNT(req51.pab)) AS 'sqrt pab'
						
						FROM 
							(
								SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
											fiche_echantillonnage_capture.date AS date,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											unite_peche.libelle AS libelle_unite_peche,
											echantillon.peche_hier AS peche_hier,
											echantillon.peche_avant_hier AS peche_avant_hier,
											echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
											((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
										
										FROM fiche_echantillonnage_capture
											INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
											INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
											INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
											INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
											WHERE	region.id=paramsidregion 
													AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
							) as req51 
						GROUP BY annee,mois,nom_region,libelle_unite_peche;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_5_6
DROP VIEW IF EXISTS `req_5_6`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_5_6` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`average_pab` DECIMAL(21,8) NULL,
	`maxpab` DOUBLE NULL,
	`stddev_pab` DOUBLE(40,8) NULL,
	`sqrt_pab` DOUBLE NULL,
	`percentFractile90` FLOAT NULL,
	`relError90PAB` DOUBLE NULL,
	`clpab` DOUBLE NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_5_6_stocke
DROP PROCEDURE IF EXISTS `req_5_6_stocke`;
DELIMITER //
CREATE PROCEDURE `req_5_6_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	req52.annee AS 'Annee',
				req52.mois AS 'Mois',
				req52.nom_region AS 'Nom region',
				req52.libelle_unite_peche AS 'Unite peche',
				ROUND(req52.average_pab,3) AS 'Average pab',
				ROUND(((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab),3) AS maxpab,
				ROUND(req52.stddev_pab,3) AS 'stddev pab',
				ROUND(req52.sqrt_pab,3) AS 'sqrt pab',
				ROUND(distribution_fractile.PercentFractile90,3) AS 'PercentFractile90',
				ROUND(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)),3) AS 'RelError90PAB',
				ROUND((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab ),3)AS 'clpab'
				FROM 
					(
						SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
						DATE_FORMAT(req51.date,"%c") AS mois,
						req51.nom_region AS nom_region,
						req51.libelle_unite_peche AS libelle_unite_peche,
						AVG(req51.pab) AS average_pab,
						STDDEV_SAMP(req51.pab) AS stddev_pab,
						COUNT(req51.pab) AS nbr_pab,
						(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
						(COUNT(req51.pab)-1) AS degree_liberte,
						SQRT(COUNT(req51.pab)) AS sqrt_pab,
						req51.id_region AS id_region
						
						FROM 
							(
								SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
											fiche_echantillonnage_capture.date AS date,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											unite_peche.libelle AS libelle_unite_peche,
											echantillon.peche_hier AS peche_hier,
											echantillon.peche_avant_hier AS peche_avant_hier,
											echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
											((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab,
											region.id AS id_region
										
										FROM fiche_echantillonnage_capture
											INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
											INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
											INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
											INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche 
											WHERE	region.id=paramsidregion 
													AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
													AND site_embarquement.id=Paramidsiteembarquement
													AND site_embarquement.id_district=Paramiddistrict 
							) as req51 
						GROUP BY annee,mois,nom_region,libelle_unite_peche
					) AS req52
				
				INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
				 
				GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche;
	ELSE IF(Paramiddistrict!=0) THEN
			SELECT 	req52.annee AS 'Annee',
				req52.mois AS 'Mois',
				req52.nom_region AS 'Nom region',
				req52.libelle_unite_peche AS 'Unite peche',
				ROUND(req52.average_pab,3) AS 'Average pab',
				ROUND(((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab),3) AS maxpab,
				ROUND(req52.stddev_pab,3) AS 'stddev pab',
				ROUND(req52.sqrt_pab,3) AS 'sqrt pab',
				ROUND(distribution_fractile.PercentFractile90,3) AS 'PercentFractile90',
				ROUND(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)),3) AS 'RelError90PAB',
				ROUND((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab ),3)AS 'clpab'
				FROM 
					(
						SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
						DATE_FORMAT(req51.date,"%c") AS mois,
						req51.nom_region AS nom_region,
						req51.libelle_unite_peche AS libelle_unite_peche,
						AVG(req51.pab) AS average_pab,
						STDDEV_SAMP(req51.pab) AS stddev_pab,
						COUNT(req51.pab) AS nbr_pab,
						(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
						(COUNT(req51.pab)-1) AS degree_liberte,
						SQRT(COUNT(req51.pab)) AS sqrt_pab,
						req51.id_region AS id_region
						
						FROM 
							(
								SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
											fiche_echantillonnage_capture.date AS date,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											unite_peche.libelle AS libelle_unite_peche,
											echantillon.peche_hier AS peche_hier,
											echantillon.peche_avant_hier AS peche_avant_hier,
											echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
											((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab,
											region.id AS id_region
										
										FROM fiche_echantillonnage_capture
											INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
											INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
											INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
											INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche 
											WHERE	region.id=paramsidregion 
													AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
													AND site_embarquement.id_district=Paramiddistrict 
							) as req51 
						GROUP BY annee,mois,nom_region,libelle_unite_peche
					) AS req52
				
				INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
				 
				GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche;
		ELSE
		
			SELECT 	req52.annee AS 'Annee',
				req52.mois AS 'Mois',
				req52.nom_region AS 'Nom region',
				req52.libelle_unite_peche AS 'Unite peche',
				ROUND(req52.average_pab,3) AS 'Average pab',
				ROUND(((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab),3) AS maxpab,
				ROUND(req52.stddev_pab,3) AS 'stddev pab',
				ROUND(req52.sqrt_pab,3) AS 'sqrt pab',
				ROUND(distribution_fractile.PercentFractile90,3) AS 'PercentFractile90',
				ROUND(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)),3) AS 'RelError90PAB',
				ROUND((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab ),3)AS 'clpab'
				FROM 
					(
						SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
						DATE_FORMAT(req51.date,"%c") AS mois,
						req51.nom_region AS nom_region,
						req51.libelle_unite_peche AS libelle_unite_peche,
						AVG(req51.pab) AS average_pab,
						STDDEV_SAMP(req51.pab) AS stddev_pab,
						COUNT(req51.pab) AS nbr_pab,
						(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
						(COUNT(req51.pab)-1) AS degree_liberte,
						SQRT(COUNT(req51.pab)) AS sqrt_pab,
						req51.id_region AS id_region
						
						FROM 
							(
								SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
											fiche_echantillonnage_capture.date AS date,
											region.nom AS nom_region,
											site_embarquement.libelle AS nom_site_embarquement,
											unite_peche.libelle AS libelle_unite_peche,
											echantillon.peche_hier AS peche_hier,
											echantillon.peche_avant_hier AS peche_avant_hier,
											echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
											((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab,
											region.id AS id_region
										
										FROM fiche_echantillonnage_capture
											INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
											INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
											INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
											INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche 
											WHERE	region.id=paramsidregion 
													AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
							) as req51 
						GROUP BY annee,mois,nom_region,libelle_unite_peche
					) AS req52
				
				INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
				 
				GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_5_7
DROP VIEW IF EXISTS `req_5_7`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_5_7` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`monthlyday` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`average_pab` DECIMAL(25,12) NULL,
	`stddev_pab` DOUBLE(40,8) NULL,
	`NbMonthlyFishingDaysPAB` DOUBLE(29,12) NULL,
	`avgmaxpab` DOUBLE NULL,
	`AvgMaxPABCorrected` DOUBLE NULL,
	`MaxNbMonthlyFishingDaysPAB` DOUBLE NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_5_7_stocke
DROP PROCEDURE IF EXISTS `req_5_7_stocke`;
DELIMITER //
CREATE PROCEDURE `req_5_7_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	req56.annee AS 'Annee',
				req56.mois AS 'Mois',
				req56.nom_region AS 'Nom region',
				req56.libelle_unite_peche AS 'Unite peche',
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS 'Monthlyday',
				ROUND(AVG(req56.average_pab),3) AS 'Average pab',
				ROUND((AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))),3) AS 'NbMonthlyFishingDaysPAB',
				ROUND(AVG(req56.maxpab),3) AS 'avgmaxpab',
				IF(AVG(req56.maxpab)>1,1,ROUND(AVG(req56.maxpab),3)) AS 'AvgMaxPABCorrected',
				ROUND(((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))),3) AS 'MaxNbMonthlyFishingDaysPAB'
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
																		AND site_embarquement.id_district=Paramiddistrict 
																		AND site_embarquement.id=Paramidsiteembarquement
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche;
			
		ELSE IF(Paramiddistrict!=0) THEN
		
	SELECT 	req56.annee AS 'Annee',
				req56.mois AS 'Mois',
				req56.nom_region AS 'Nom region',
				req56.libelle_unite_peche AS 'Unite peche',
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS 'Monthlyday',
				ROUND(AVG(req56.average_pab),3) AS 'Average pab',
				ROUND((AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))),3) AS 'NbMonthlyFishingDaysPAB',
				ROUND(AVG(req56.maxpab),3) AS 'avgmaxpab',
				IF(AVG(req56.maxpab)>1,1,ROUND(AVG(req56.maxpab),3)) AS 'AvgMaxPABCorrected',
				ROUND(((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))),3) AS 'MaxNbMonthlyFishingDaysPAB'
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
																		AND site_embarquement.id_district=Paramiddistrict 
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche;
		ELSE
		
	SELECT 	req56.annee AS 'Annee',
				req56.mois AS 'Mois',
				req56.nom_region AS 'Nom region',
				req56.libelle_unite_peche AS 'Unite peche',
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS 'Monthlyday',
				ROUND(AVG(req56.average_pab),3) AS 'Average pab',
				ROUND((AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))),3) AS 'NbMonthlyFishingDaysPAB',
				ROUND(AVG(req56.maxpab),3) AS 'avgmaxpab',
				IF(AVG(req56.maxpab)>1,1,ROUND(AVG(req56.maxpab),3)) AS 'AvgMaxPABCorrected',
				ROUND(((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))),3) AS 'MaxNbMonthlyFishingDaysPAB'
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_6_2
DROP VIEW IF EXISTS `req_6_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_6_2` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`nbr_unite_peche` DECIMAL(32,0) NULL,
	`NbMonthlyFishingDaysPAB` DOUBLE(29,12) NULL,
	`NbrTotalMonthlyFishingDays` DOUBLE(29,12) NULL,
	`average_cpue` DOUBLE NULL,
	`CapturesTotalest` DOUBLE NULL,
	`relErreurCPUE90` DOUBLE NULL,
	`nbr_cpue` BIGINT(21) NOT NULL,
	`avgmaxpab` DOUBLE NULL,
	`max_cpue` DOUBLE NULL,
	`monthlyday` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`MaxCapturesTotale` DOUBLE NULL,
	`CLCaptureTotales` DOUBLE NULL,
	`RelErrorCapuresTotales90` DOUBLE NULL,
	`id_region` INT(11) NOT NULL,
	`id_district` INT(11) NULL,
	`id_site_embarquement` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la vue peche_db. req_6_2_a
DROP VIEW IF EXISTS `req_6_2_a`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_6_2_a` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`TotalAnnualFishingDays` DOUBLE(29,12) NULL,
	`id_region` INT(11) NOT NULL,
	`id_district` INT(11) NULL,
	`id_site_embarquement` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_6_2_a_stocke
DROP PROCEDURE IF EXISTS `req_6_2_a_stocke`;
DELIMITER //
CREATE PROCEDURE `req_6_2_a_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT annee AS 'Annee',
				libelle_unite_peche AS 'Unite peche',
		 ROUND(sum(NbrTotalMonthlyFishingDays),3) AS TotalAnnualFishingDays	 
		FROM req_6_2
		WHERE	id_region=paramsidregion 
				AND annee=paramsannee
				AND id_district=Paramiddistrict
				AND id_site_embarquement=Paramidsiteembarquement
		GROUP BY annee,libelle_unite_peche;
ELSE IF(Paramiddistrict!=0) THEN
		SELECT annee AS 'Annee',
						libelle_unite_peche AS 'Unite peche',
				 ROUND(sum(NbrTotalMonthlyFishingDays),3) AS TotalAnnualFishingDays	 
				FROM req_6_2
				WHERE	id_region=paramsidregion 
						AND annee=paramsannee
						AND id_district=Paramiddistrict
				GROUP BY annee,libelle_unite_peche;
		ELSE
		SELECT annee AS 'Annee',
				libelle_unite_peche AS 'Unite peche',
		 ROUND(sum(NbrTotalMonthlyFishingDays),3) AS TotalAnnualFishingDays	 
		FROM req_6_2
		WHERE	id_region=paramsidregion 
				AND annee=paramsannee
		GROUP BY annee,libelle_unite_peche;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la procédure peche_db. req_6_2_stocke
DROP PROCEDURE IF EXISTS `req_6_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_6_2_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
		SELECT 	req3.annee AS 'Annee',
			req3.mois AS 'Mois',
			req57.nom_region AS 'Region',
			req41.nom_site_embarquement AS 'Site embarquement',
			req57.libelle_unite_peche AS 'Unite peche',
			req41.nbr_unite_peche AS 'Nombre Unite peche',
			ROUND(req57.NbMonthlyFishingDaysPAB,3) AS NbMonthlyFishingDaysPAB,
			ROUND((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB),3) AS NbrTotalMonthlyFishingDays,
			ROUND(req3.average_cpue,3) AS average_cpue,
			ROUND(((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000),3) AS CapturesTotalest,
			ROUND(req3.relErreurCPUE90,3) AS relErreurCPUE90,
			req3.nbr_cpue AS 'Nombre cpue',
			ROUND(req57.avgmaxpab,3) AS avgmaxpab,
			ROUND(req3.max_cpue,3) AS max_cpue,
			req57.monthlyday AS monthlyday,
			ROUND(((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000),3) AS MaxCapturesTotale,
			ROUND((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)),3) AS CLCaptureTotales,
			ROUND(((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000))/((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)),3) AS RelErrorCapuresTotales90 
			
 
 FROM (SELECT 	enquete_cadre.annee AS annee,
			region.nom AS nom_region,
			site_embarquement.libelle AS nom_site_embarquement,
			unite_peche.libelle AS libelle_unite_peche,
			SUM(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche
			
			FROM enquete_cadre
				INNER JOIN region ON region.id= enquete_cadre.id_region
				INNER JOIN site_embarquement ON site_embarquement.id=enquete_cadre.id_site_embarquement
				INNER JOIN unite_peche ON unite_peche.id=enquete_cadre.id_unite_peche
			WHERE	region.id=paramsidregion 
					AND enquete_cadre.annee= paramsannee
					AND site_embarquement.id=Paramidsiteembarquement
					AND site_embarquement.id_district=Paramiddistrict
			GROUP BY annee,nom_region,nom_site_embarquement,libelle_unite_peche) AS req41 
 	INNER JOIN (SELECT 	req56.annee AS annee,
				req56.mois AS mois,
				req56.nom_region AS nom_region,
				req56.libelle_unite_peche AS libelle_unite_peche,
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS monthlyday,
				AVG(req56.average_pab) AS average_pab,
				(AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS NbMonthlyFishingDaysPAB,
				AVG(req56.maxpab) AS avgmaxpab,
				IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)) AS AvgMaxPABCorrected,
				((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS MaxNbMonthlyFishingDaysPAB
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche 
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
																		AND site_embarquement.id=Paramidsiteembarquement
																		AND site_embarquement.id_district=Paramiddistrict
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche) AS req57 ON req41.annee=req57.annee 
	 		AND req41.libelle_unite_peche=req57.libelle_unite_peche 
			AND req57.nom_region=req41.nom_region 
 	INNER JOIN (SELECT 	req2stoke.annee AS annee,
			req2stoke.mois AS mois,
			req2stoke.nom_region AS nom_region,
			req2stoke.libelle_unite_peche AS libelle_unite_peche,
			req2stoke.average_cpue AS average_cpue,
			req2stoke.stddev_cpue AS stddev_cpue,
			req2stoke.sqrt_cpue AS sqrt_cpue,
			distribution_fractile.PercentFractile90 AS PercentFractile90,
			((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS clcpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS relErreurCPUE90,
			req2stoke.nbr_cpue AS nbr_cpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS max_cpue
			FROM distribution_fractile
			
			INNER JOIN (SELECT 	req2.annee AS annee,
			req2.mois AS mois,
			req2.nom_region AS nom_region,
			req2.libelle_unite_peche AS libelle_unite_peche,
			req2.average_cpue AS average_cpue,
			req2.stddev_cpue AS stddev_cpue,
			req2.nbr_cpue AS nbr_cpue,
			req2.sqrt_cpue AS sqrt_cpue,
			req2.degree_liberte AS  degree_liberte
			
			FROM 
			(
				SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
						DATE_FORMAT(req1.date,"%c") AS mois,
						req1.nom_region AS nom_region,
						req1.libelle_unite_peche AS libelle_unite_peche,
						AVG(req1.cpue) AS average_cpue,
						STDDEV_SAMP(req1.cpue) AS stddev_cpue,
						COUNT(req1.cpue) AS nbr_cpue,
						SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
						COUNT(req1.cpue)-1 AS degree_liberte
						
						FROM 
							(
								SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										fiche_echantillonnage_capture.date AS date,
										unite_peche.libelle AS libelle_unite_peche,
										SUM(espece_capture.capture) AS capture,
										SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
											
											FROM unite_peche
												INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
												INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
												INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
												INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
												INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
												
												WHERE fiche_echantillonnage_capture.validation=1 
														AND region.id=paramsidregion 
														AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee	
														AND site_embarquement.id=Paramidsiteembarquement
														AND site_embarquement.id_district=Paramiddistrict
												
												GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
												site_embarquement.id,echantillon.id
						) AS req1
						GROUP BY annee,mois,nom_region,libelle_unite_peche
			) AS req2
			GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom) AS req3 ON req57.libelle_unite_peche=req3.libelle_unite_peche 
		 AND req3.nom_region=req57.nom_region 
		 AND req3.mois=req57.mois
		 AND req3.annee=req57.annee;
		 
ELSE IF(Paramiddistrict!=0) THEN
		SELECT 	req3.annee AS 'Annee',
			req3.mois AS 'Mois',
			req57.nom_region AS 'Region',
			req41.nom_site_embarquement AS 'Site embarquement',
			req57.libelle_unite_peche AS 'Unite peche',
			req41.nbr_unite_peche AS 'Nombre Unite peche',
			ROUND(req57.NbMonthlyFishingDaysPAB,3) AS NbMonthlyFishingDaysPAB,
			ROUND((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB),3) AS NbrTotalMonthlyFishingDays,
			ROUND(req3.average_cpue,3) AS average_cpue,
			ROUND(((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000),3) AS CapturesTotalest,
			ROUND(req3.relErreurCPUE90,3) AS relErreurCPUE90,
			req3.nbr_cpue AS 'Nombre cpue',
			ROUND(req57.avgmaxpab,3) AS avgmaxpab,
			ROUND(req3.max_cpue,3) AS max_cpue,
			req57.monthlyday AS monthlyday,
			ROUND(((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000),3) AS MaxCapturesTotale,
			ROUND((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)),3) AS CLCaptureTotales,
			ROUND(((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000))/((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)),3) AS RelErrorCapuresTotales90 
			
 
 FROM (SELECT 	enquete_cadre.annee AS annee,
			region.nom AS nom_region,
			site_embarquement.libelle AS nom_site_embarquement,
			unite_peche.libelle AS libelle_unite_peche,
			SUM(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche
			
			FROM enquete_cadre
				INNER JOIN region ON region.id= enquete_cadre.id_region
				INNER JOIN site_embarquement ON site_embarquement.id=enquete_cadre.id_site_embarquement
				INNER JOIN unite_peche ON unite_peche.id=enquete_cadre.id_unite_peche
			WHERE	region.id=paramsidregion 
					AND enquete_cadre.annee= paramsannee
					AND site_embarquement.id_district=Paramiddistrict
			GROUP BY annee,nom_region,nom_site_embarquement,libelle_unite_peche) AS req41 
 	INNER JOIN (SELECT 	req56.annee AS annee,
				req56.mois AS mois,
				req56.nom_region AS nom_region,
				req56.libelle_unite_peche AS libelle_unite_peche,
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS monthlyday,
				AVG(req56.average_pab) AS average_pab,
				(AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS NbMonthlyFishingDaysPAB,
				AVG(req56.maxpab) AS avgmaxpab,
				IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)) AS AvgMaxPABCorrected,
				((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS MaxNbMonthlyFishingDaysPAB
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche 
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
																		AND site_embarquement.id_district=Paramiddistrict
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche) AS req57 ON req41.annee=req57.annee 
	 		AND req41.libelle_unite_peche=req57.libelle_unite_peche 
			AND req57.nom_region=req41.nom_region 
 	INNER JOIN (SELECT 	req2stoke.annee AS annee,
			req2stoke.mois AS mois,
			req2stoke.nom_region AS nom_region,
			req2stoke.libelle_unite_peche AS libelle_unite_peche,
			req2stoke.average_cpue AS average_cpue,
			req2stoke.stddev_cpue AS stddev_cpue,
			req2stoke.sqrt_cpue AS sqrt_cpue,
			distribution_fractile.PercentFractile90 AS PercentFractile90,
			((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS clcpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS relErreurCPUE90,
			req2stoke.nbr_cpue AS nbr_cpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS max_cpue
			FROM distribution_fractile
			
			INNER JOIN (SELECT 	req2.annee AS annee,
			req2.mois AS mois,
			req2.nom_region AS nom_region,
			req2.libelle_unite_peche AS libelle_unite_peche,
			req2.average_cpue AS average_cpue,
			req2.stddev_cpue AS stddev_cpue,
			req2.nbr_cpue AS nbr_cpue,
			req2.sqrt_cpue AS sqrt_cpue,
			req2.degree_liberte AS  degree_liberte
			
			FROM 
			(
				SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
						DATE_FORMAT(req1.date,"%c") AS mois,
						req1.nom_region AS nom_region,
						req1.libelle_unite_peche AS libelle_unite_peche,
						AVG(req1.cpue) AS average_cpue,
						STDDEV_SAMP(req1.cpue) AS stddev_cpue,
						COUNT(req1.cpue) AS nbr_cpue,
						SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
						COUNT(req1.cpue)-1 AS degree_liberte
						
						FROM 
							(
								SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										fiche_echantillonnage_capture.date AS date,
										unite_peche.libelle AS libelle_unite_peche,
										SUM(espece_capture.capture) AS capture,
										SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
											
											FROM unite_peche
												INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
												INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
												INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
												INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
												INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
												
												WHERE fiche_echantillonnage_capture.validation=1 
														AND region.id=paramsidregion 
														AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee	
														AND site_embarquement.id_district=Paramiddistrict
												
												GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
												site_embarquement.id,echantillon.id
						) AS req1
						GROUP BY annee,mois,nom_region,libelle_unite_peche
			) AS req2
			GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom) AS req3 ON req57.libelle_unite_peche=req3.libelle_unite_peche 
		 AND req3.nom_region=req57.nom_region 
		 AND req3.mois=req57.mois
		 AND req3.annee=req57.annee;
	ELSE
	
			SELECT 	req3.annee AS 'Annee',
			req3.mois AS 'Mois',
			req57.nom_region AS 'Region',
			req41.nom_site_embarquement AS 'Site embarquement',
			req57.libelle_unite_peche AS 'Unite peche',
			req41.nbr_unite_peche AS 'Nombre Unite peche',
			ROUND(req57.NbMonthlyFishingDaysPAB,3) AS NbMonthlyFishingDaysPAB,
			ROUND((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB),3) AS NbrTotalMonthlyFishingDays,
			ROUND(req3.average_cpue,3) AS average_cpue,
			ROUND(((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000),3) AS CapturesTotalest,
			ROUND(req3.relErreurCPUE90,3) AS relErreurCPUE90,
			req3.nbr_cpue AS 'Nombre cpue',
			ROUND(req57.avgmaxpab,3) AS avgmaxpab,
			ROUND(req3.max_cpue,3) AS max_cpue,
			req57.monthlyday AS monthlyday,
			ROUND(((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000),3) AS MaxCapturesTotale,
			ROUND((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)),3) AS CLCaptureTotales,
			ROUND(((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000))/((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)),3) AS RelErrorCapuresTotales90 
			
 
 FROM (SELECT 	enquete_cadre.annee AS annee,
			region.nom AS nom_region,
			site_embarquement.libelle AS nom_site_embarquement,
			unite_peche.libelle AS libelle_unite_peche,
			SUM(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche
			
			FROM enquete_cadre
				INNER JOIN region ON region.id= enquete_cadre.id_region
				INNER JOIN site_embarquement ON site_embarquement.id=enquete_cadre.id_site_embarquement
				INNER JOIN unite_peche ON unite_peche.id=enquete_cadre.id_unite_peche
			WHERE	region.id=paramsidregion 
					AND enquete_cadre.annee= paramsannee
			GROUP BY annee,nom_region,nom_site_embarquement,libelle_unite_peche) AS req41 
 	INNER JOIN (SELECT 	req56.annee AS annee,
				req56.mois AS mois,
				req56.nom_region AS nom_region,
				req56.libelle_unite_peche AS libelle_unite_peche,
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS monthlyday,
				AVG(req56.average_pab) AS average_pab,
				(AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS NbMonthlyFishingDaysPAB,
				AVG(req56.maxpab) AS avgmaxpab,
				IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)) AS AvgMaxPABCorrected,
				((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS MaxNbMonthlyFishingDaysPAB
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche 
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche) AS req57 ON req41.annee=req57.annee 
	 		AND req41.libelle_unite_peche=req57.libelle_unite_peche 
			AND req57.nom_region=req41.nom_region 
 	INNER JOIN (SELECT 	req2stoke.annee AS annee,
			req2stoke.mois AS mois,
			req2stoke.nom_region AS nom_region,
			req2stoke.libelle_unite_peche AS libelle_unite_peche,
			req2stoke.average_cpue AS average_cpue,
			req2stoke.stddev_cpue AS stddev_cpue,
			req2stoke.sqrt_cpue AS sqrt_cpue,
			distribution_fractile.PercentFractile90 AS PercentFractile90,
			((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS clcpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS relErreurCPUE90,
			req2stoke.nbr_cpue AS nbr_cpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS max_cpue
			FROM distribution_fractile
			
			INNER JOIN (SELECT 	req2.annee AS annee,
			req2.mois AS mois,
			req2.nom_region AS nom_region,
			req2.libelle_unite_peche AS libelle_unite_peche,
			req2.average_cpue AS average_cpue,
			req2.stddev_cpue AS stddev_cpue,
			req2.nbr_cpue AS nbr_cpue,
			req2.sqrt_cpue AS sqrt_cpue,
			req2.degree_liberte AS  degree_liberte
			
			FROM 
			(
				SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
						DATE_FORMAT(req1.date,"%c") AS mois,
						req1.nom_region AS nom_region,
						req1.libelle_unite_peche AS libelle_unite_peche,
						AVG(req1.cpue) AS average_cpue,
						STDDEV_SAMP(req1.cpue) AS stddev_cpue,
						COUNT(req1.cpue) AS nbr_cpue,
						SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
						COUNT(req1.cpue)-1 AS degree_liberte
						
						FROM 
							(
								SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										fiche_echantillonnage_capture.date AS date,
										unite_peche.libelle AS libelle_unite_peche,
										SUM(espece_capture.capture) AS capture,
										SUM(espece_capture.capture/echantillon.duree_mare) AS cpue
											
											FROM unite_peche
												INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
												INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
												INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
												INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
												INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
												
												WHERE fiche_echantillonnage_capture.validation=1 
														AND region.id=paramsidregion 
														AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee	
												
												GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
												site_embarquement.id,echantillon.id
						) AS req1
						GROUP BY annee,mois,nom_region,libelle_unite_peche
			) AS req2
			GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom) AS req3 ON req57.libelle_unite_peche=req3.libelle_unite_peche 
		 AND req3.nom_region=req57.nom_region 
		 AND req3.mois=req57.mois
		 AND req3.annee=req57.annee;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_7_1
DROP VIEW IF EXISTS `req_7_1`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_7_1` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`Code3alpha` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`capture` DOUBLE NULL,
	`average_prix` DECIMAL(14,4) NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_7_1_stocke
DROP PROCEDURE IF EXISTS `req_7_1_stocke`;
DELIMITER //
CREATE PROCEDURE `req_7_1_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	select date_format(fiche_echantillonnage_capture.date,'%Y') AS `Annee`,
		date_format(fiche_echantillonnage_capture.date,'%c') AS `Mois`,
		region.nom AS `Region`,
		unite_peche.libelle AS `Unite peche`,
		espece.code AS `Code3alpha`,
		sum(espece_capture.capture) AS `capture`,
		ROUND(avg(espece_capture.prix),3) AS `average_prix` 
		from (((((unite_peche 
				join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
				join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
				join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
				join espece on(espece.id = espece_capture.id_espece)) 
				join region on(region.id = fiche_echantillonnage_capture.id_region))
		WHERE	region.id=paramsidregion 
				AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
				AND fiche_echantillonnage_capture.id_district=Paramiddistrict
				AND fiche_echantillonnage_capture.id_site_embarquement=Paramidsiteembarquement 
		group by date_format(fiche_echantillonnage_capture.date,'%Y'),
					date_format(fiche_echantillonnage_capture.date,'%c'),
					region.nom,
					unite_peche.libelle,
					espece.code;
ELSE IF(Paramiddistrict!=0) THEN
		select date_format(fiche_echantillonnage_capture.date,'%Y') AS `Annee`,
				date_format(fiche_echantillonnage_capture.date,'%c') AS `Mois`,
				region.nom AS `Region`,
				unite_peche.libelle AS `Unite peche`,
				espece.code AS `Code3alpha`,
				sum(espece_capture.capture) AS `capture`,
				ROUND(avg(espece_capture.prix),3) AS `average_prix` 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region))
				WHERE	region.id=paramsidregion 
						AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
						AND fiche_echantillonnage_capture.id_district=Paramiddistrict 
				group by date_format(fiche_echantillonnage_capture.date,'%Y'),
							date_format(fiche_echantillonnage_capture.date,'%c'),
							region.nom,
							unite_peche.libelle,
							espece.code;
	ELSE
		select date_format(fiche_echantillonnage_capture.date,'%Y') AS `Annee`,
				date_format(fiche_echantillonnage_capture.date,'%c') AS `Mois`,
				region.nom AS `Region`,
				unite_peche.libelle AS `Unite peche`,
				espece.code AS `Code3alpha`,
				sum(espece_capture.capture) AS `capture`,
				ROUND(avg(espece_capture.prix),3) AS `average_prix` 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region))
				WHERE	region.id=paramsidregion 
						AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
				group by date_format(fiche_echantillonnage_capture.date,'%Y'),
							date_format(fiche_echantillonnage_capture.date,'%c'),
							region.nom,
							unite_peche.libelle,
							espece.code;
		END IF;
	END IF; 
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_7_2
DROP VIEW IF EXISTS `req_7_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_7_2` (
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`capture` DOUBLE NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_7_2_stocke
DROP PROCEDURE IF EXISTS `req_7_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_7_2_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	select 
			date_format(fiche_echantillonnage_capture.date,'%Y') AS `Annee`,
			date_format(fiche_echantillonnage_capture.date,'%c') AS `Mois`,
			region.nom AS `Region`,
			unite_peche.libelle AS `Unite peche`,
			ROUND(sum(espece_capture.capture),3) AS `capture` 
		from (((((unite_peche 
				join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
				join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
				join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
				join espece on(espece.id = espece_capture.id_espece)) 
				join region on(region.id = fiche_echantillonnage_capture.id_region)) 
			WHERE	id_region=paramsidregion 
					AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
					AND id_district=Paramiddistrict
					AND id_site_embarquement=Paramidsiteembarquement
			group by date_format(fiche_echantillonnage_capture.date,'%Y'),
						date_format(fiche_echantillonnage_capture.date,'%c'),
						region.nom,
						unite_peche.libelle;
ELSE IF(Paramiddistrict!=0) THEN
		select 
					date_format(fiche_echantillonnage_capture.date,'%Y') AS `Annee`,
					date_format(fiche_echantillonnage_capture.date,'%c') AS `Mois`,
					region.nom AS `Region`,
					unite_peche.libelle AS `Unite peche`,
					ROUND(sum(espece_capture.capture),3) AS `capture` 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region)) 
					WHERE	id_region=paramsidregion 
							AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
							AND id_district=Paramiddistrict
					group by date_format(fiche_echantillonnage_capture.date,'%Y'),
								date_format(fiche_echantillonnage_capture.date,'%c'),
								region.nom,
								unite_peche.libelle;
			ELSE
			
				select 
				date_format(fiche_echantillonnage_capture.date,'%Y') AS `Annee`,
				date_format(fiche_echantillonnage_capture.date,'%c') AS `Mois`,
				region.nom AS `Region`,
				unite_peche.libelle AS `Unite peche`,
				ROUND(sum(espece_capture.capture),3) AS `capture` 
			from (((((unite_peche 
					join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
					join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
					join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
					join espece on(espece.id = espece_capture.id_espece)) 
					join region on(region.id = fiche_echantillonnage_capture.id_region)) 
				WHERE	id_region=paramsidregion 
						AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
				group by date_format(fiche_echantillonnage_capture.date,'%Y'),
							date_format(fiche_echantillonnage_capture.date,'%c'),
							region.nom,
							unite_peche.libelle;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_7_3
DROP VIEW IF EXISTS `req_7_3`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_7_3` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`Code3alpha` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`capture` DOUBLE NULL,
	`totalcapture` DOUBLE NULL,
	`CompEspece` DOUBLE NULL,
	`average_prix` DECIMAL(14,4) NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_7_3_stocke
DROP PROCEDURE IF EXISTS `req_7_3_stocke`;
DELIMITER //
CREATE PROCEDURE `req_7_3_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	req73.annee AS 'Annee',
			req73.mois AS 'Mois',
			req73.nom_region AS 'Region',
			req73.libelle_unite_peche AS 'Unite peche',
			req73.Code3alpha AS Code3alpha,
			req73.capture AS capture,
			req73.totalcapture AS 'Totalcapture',
			req73.CompEspece AS	CompEspece,
			req73.average_prix AS 'Average prix'			
			
			FROM (select req71.annee AS annee,
			req71.mois AS mois,
			req71.nom_region AS nom_region,
			req71.libelle_unite_peche AS libelle_unite_peche,
			req71.Code3alpha AS Code3alpha,
			req71.capture AS capture,
			req72.capture AS totalcapture,
			req71.capture / req72.capture AS CompEspece,
			req71.average_prix AS average_prix,
			req71.id_region AS id_region 
			
			FROM (select date_format(fiche_echantillonnage_capture.date,'%Y') AS annee,
		date_format(fiche_echantillonnage_capture.date,'%c') AS mois,
		region.nom AS nom_region,
		unite_peche.libelle AS libelle_unite_peche,
		espece.code AS Code3alpha,sum(espece_capture.capture) AS capture,
		avg(espece_capture.prix) AS average_prix,
		region.id AS id_region 
		from (((((unite_peche 
				join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
				join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
				join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
				join espece on(espece.id = espece_capture.id_espece)) 
				join region on(region.id = fiche_echantillonnage_capture.id_region))
			WHERE	fiche_echantillonnage_capture.id_region=paramsidregion 
					AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
					AND fiche_echantillonnage_capture.id_district=Paramiddistrict
					AND fiche_echantillonnage_capture.id_site_embarquement=Paramidsiteembarquement 
			group by date_format(fiche_echantillonnage_capture.date,'%Y'),
						date_format(fiche_echantillonnage_capture.date,'%c'),
						region.nom,
						unite_peche.libelle,espece.code ) AS req71 
		JOIN (select region.nom AS nom_region,
			date_format(fiche_echantillonnage_capture.date,'%Y') AS annee,
			date_format(fiche_echantillonnage_capture.date,'%c') AS mois,
			unite_peche.libelle AS libelle_unite_peche,
			sum(espece_capture.capture) AS capture,
			region.id AS id_region 
		from (((((unite_peche 
				join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
				join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
				join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
				join espece on(espece.id = espece_capture.id_espece)) 
				join region on(region.id = fiche_echantillonnage_capture.id_region))
				
			WHERE	fiche_echantillonnage_capture.id_region=paramsidregion 
					AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
					AND fiche_echantillonnage_capture.id_district=Paramiddistrict
					AND fiche_echantillonnage_capture.id_site_embarquement=Paramidsiteembarquement 
			group by date_format(fiche_echantillonnage_capture.date,'%Y'),
						date_format(fiche_echantillonnage_capture.date,'%c'),
						region.nom,
						unite_peche.libelle) AS req72 on(req71.libelle_unite_peche = req72.libelle_unite_peche and req71.nom_region = req72.nom_region and req71.mois = req72.mois and req71.annee = req72.annee) 
					where req72.capture > 0) AS req73;
ELSE IF(Paramiddistrict!=0) THEN
		SELECT 	req73.annee AS 'Annee',
					req73.mois AS 'Mois',
					req73.nom_region AS 'Region',
					req73.libelle_unite_peche AS 'Unite peche',
					req73.Code3alpha AS Code3alpha,
					req73.capture AS capture,
					req73.totalcapture AS 'Totalcapture',
					req73.CompEspece AS	CompEspece,
					req73.average_prix AS 'Average prix'			
					
					FROM (select req71.annee AS annee,
					req71.mois AS mois,
					req71.nom_region AS nom_region,
					req71.libelle_unite_peche AS libelle_unite_peche,
					req71.Code3alpha AS Code3alpha,
					req71.capture AS capture,
					req72.capture AS totalcapture,
					req71.capture / req72.capture AS CompEspece,
					req71.average_prix AS average_prix,
					req71.id_region AS id_region 
					
					FROM (select date_format(fiche_echantillonnage_capture.date,'%Y') AS annee,
				date_format(fiche_echantillonnage_capture.date,'%c') AS mois,
				region.nom AS nom_region,
				unite_peche.libelle AS libelle_unite_peche,
				espece.code AS Code3alpha,sum(espece_capture.capture) AS capture,
				avg(espece_capture.prix) AS average_prix,
				region.id AS id_region 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region))
					WHERE	fiche_echantillonnage_capture.id_region=paramsidregion 
							AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
							AND fiche_echantillonnage_capture.id_district=Paramiddistrict 
					group by date_format(fiche_echantillonnage_capture.date,'%Y'),
								date_format(fiche_echantillonnage_capture.date,'%c'),
								region.nom,
								unite_peche.libelle,espece.code ) AS req71 
				JOIN (select region.nom AS nom_region,
					date_format(fiche_echantillonnage_capture.date,'%Y') AS annee,
					date_format(fiche_echantillonnage_capture.date,'%c') AS mois,
					unite_peche.libelle AS libelle_unite_peche,
					sum(espece_capture.capture) AS capture,
					region.id AS id_region 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region))
						
					WHERE	fiche_echantillonnage_capture.id_region=paramsidregion 
							AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
							AND fiche_echantillonnage_capture.id_district=Paramiddistrict
					group by date_format(fiche_echantillonnage_capture.date,'%Y'),
								date_format(fiche_echantillonnage_capture.date,'%c'),
								region.nom,
								unite_peche.libelle) AS req72 on(req71.libelle_unite_peche = req72.libelle_unite_peche and req71.nom_region = req72.nom_region and req71.mois = req72.mois and req71.annee = req72.annee) 
							where req72.capture > 0) AS req73;
		ELSE
		
			SELECT 	req73.annee AS 'Annee',
					req73.mois AS 'Mois',
					req73.nom_region AS 'Region',
					req73.libelle_unite_peche AS 'Unite peche',
					req73.Code3alpha AS Code3alpha,
					req73.capture AS capture,
					req73.totalcapture AS 'Totalcapture',
					req73.CompEspece AS	CompEspece,
					req73.average_prix AS 'Average prix'			
					
					FROM (select req71.annee AS annee,
					req71.mois AS mois,
					req71.nom_region AS nom_region,
					req71.libelle_unite_peche AS libelle_unite_peche,
					req71.Code3alpha AS Code3alpha,
					req71.capture AS capture,
					req72.capture AS totalcapture,
					req71.capture / req72.capture AS CompEspece,
					req71.average_prix AS average_prix,
					req71.id_region AS id_region 
					
					FROM (select date_format(fiche_echantillonnage_capture.date,'%Y') AS annee,
				date_format(fiche_echantillonnage_capture.date,'%c') AS mois,
				region.nom AS nom_region,
				unite_peche.libelle AS libelle_unite_peche,
				espece.code AS Code3alpha,sum(espece_capture.capture) AS capture,
				avg(espece_capture.prix) AS average_prix,
				region.id AS id_region 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region))
					WHERE	fiche_echantillonnage_capture.id_region=paramsidregion 
							AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
					group by date_format(fiche_echantillonnage_capture.date,'%Y'),
								date_format(fiche_echantillonnage_capture.date,'%c'),
								region.nom,
								unite_peche.libelle,espece.code ) AS req71 
				JOIN (select region.nom AS nom_region,
					date_format(fiche_echantillonnage_capture.date,'%Y') AS annee,
					date_format(fiche_echantillonnage_capture.date,'%c') AS mois,
					unite_peche.libelle AS libelle_unite_peche,
					sum(espece_capture.capture) AS capture,
					region.id AS id_region 
				from (((((unite_peche 
						join echantillon on(echantillon.id_unite_peche = unite_peche.id)) 
						join espece_capture on(espece_capture.id_echantillon = echantillon.id)) 
						join fiche_echantillonnage_capture on(fiche_echantillonnage_capture.id = echantillon.id_fiche_echantillonnage_capture)) 
						join espece on(espece.id = espece_capture.id_espece)) 
						join region on(region.id = fiche_echantillonnage_capture.id_region))
						
					WHERE	fiche_echantillonnage_capture.id_region=paramsidregion 
							AND date_format(fiche_echantillonnage_capture.date,'%Y')=paramsannee
					group by date_format(fiche_echantillonnage_capture.date,'%Y'),
								date_format(fiche_echantillonnage_capture.date,'%c'),
								region.nom,
								unite_peche.libelle) AS req72 on(req71.libelle_unite_peche = req72.libelle_unite_peche and req71.nom_region = req72.nom_region and req71.mois = req72.mois and req71.annee = req72.annee) 
							where req72.capture > 0) AS req73;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_8_2
DROP VIEW IF EXISTS `req_8_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_8_2` (
	`annee` VARCHAR(4) NULL COLLATE 'utf8mb4_general_ci',
	`mois` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`NbMonthlyFishingDaysPAB` DOUBLE(29,12) NULL,
	`NbrTotalMonthlyFishingDays` DOUBLE(29,12) NULL,
	`average_cpue` DOUBLE NULL,
	`avgmaxpab` DOUBLE NULL,
	`max_cpue` DOUBLE NULL,
	`monthlyday` VARCHAR(2) NULL COLLATE 'utf8mb4_general_ci',
	`MaxCapturesTotale` DOUBLE NULL,
	`CLCaptureTotales` DOUBLE NULL,
	`Code3alpha` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`relErreurCPUE90` DOUBLE NULL,
	`RelErrorCapuresTotales90` DOUBLE NULL,
	`nbr_cpue` BIGINT(21) NOT NULL,
	`CapturesTotalest` DOUBLE NULL,
	`CompEspece` DOUBLE NULL,
	`Total_catch_specie` DOUBLE NULL,
	`average_prix` DECIMAL(14,4) NULL,
	`Value_specie` DOUBLE NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_8_2_stocke
DROP PROCEDURE IF EXISTS `req_8_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_8_2_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT 	req62.annee AS 'Annee',
			req62.mois AS 'Mois',
			req62.nom_region AS 'Region',
			req62.nom_site_embarquement AS 'Site embarquement',
			req62.libelle_unite_peche AS 'Unite peche',
			req62.NbMonthlyFishingDaysPAB AS	NbMonthlyFishingDaysPAB,
			req62.NbrTotalMonthlyFishingDays AS NbrTotalMonthlyFishingDays,
			req73.Code3alpha AS Code3alpha,			
			req62.relErreurCPUE90 AS relErreurCPUE90,
			req62.RelErrorCapuresTotales90 AS RelErrorCapuresTotales90,
			req62.nbr_cpue AS 'Nombre cpue',
			req62.CapturesTotalest AS CapturesTotalest, 
			req73.CompEspece AS	CompEspece,
			(req62.CapturesTotalest*req73.CompEspece) AS Total_catch_specie,
			req73.average_prix AS average_prix,
			(req62.CapturesTotalest*req73.CompEspece)*req73.average_prix AS Value_specie

 
 	FROM (SELECT 	req3.annee AS annee,
			req3.mois AS mois,
			req57.nom_region AS nom_region,
			req41.nom_site_embarquement AS nom_site_embarquement,
			req57.libelle_unite_peche AS libelle_unite_peche,
			req41.nbr_unite_peche,
			req57.NbMonthlyFishingDaysPAB,
			(req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB) AS NbrTotalMonthlyFishingDays,
			req3.average_cpue AS average_cpue,
			((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000) AS CapturesTotalest,
			req3.relErreurCPUE90 AS relErreurCPUE90,
			req3.nbr_cpue AS nbr_cpue,
			req57.avgmaxpab AS avgmaxpab,
			req3.max_cpue AS max_cpue,
			req57.monthlyday AS monthlyday,
			((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000) AS MaxCapturesTotale,
			(((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)) AS CLCaptureTotales,
			((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000))/((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)) AS RelErrorCapuresTotales90 
			
 
 FROM (SELECT 	enquete_cadre.annee AS annee,
			region.nom AS nom_region,
			site_embarquement.libelle AS nom_site_embarquement,
			unite_peche.libelle AS libelle_unite_peche,
			SUM(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche
			
			FROM enquete_cadre
				INNER JOIN region ON region.id= enquete_cadre.id_region
				INNER JOIN site_embarquement ON site_embarquement.id=enquete_cadre.id_site_embarquement
				INNER JOIN unite_peche ON unite_peche.id=enquete_cadre.id_unite_peche
				WHERE	region.id=paramsidregion 
						AND enquete_cadre.annee = paramsannee
						AND site_embarquement.id_district = Paramiddistrict
						AND site_embarquement.id = Paramidsiteembarquement
			
			GROUP BY annee,nom_region,nom_site_embarquement,libelle_unite_peche) AS req41 
 	INNER JOIN (SELECT 	req56.annee AS annee,
				req56.mois AS mois,
				req56.nom_region AS nom_region,
				req56.libelle_unite_peche AS libelle_unite_peche,
				(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS monthlyday,
				AVG(req56.average_pab) AS average_pab,
				(AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS NbMonthlyFishingDaysPAB,
				AVG(req56.maxpab) AS avgmaxpab,
				IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)) AS AvgMaxPABCorrected,
				((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS MaxNbMonthlyFishingDaysPAB
				
				FROM 
					(
						SELECT 	req52.annee AS annee,
									req52.mois AS mois,
									req52.nom_region AS nom_region,
									req52.libelle_unite_peche AS libelle_unite_peche,
									req52.average_pab AS average_pab,
									((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
									req52.stddev_pab AS stddev_pab,
									req52.sqrt_pab AS sqrt_pab,
									distribution_fractile.PercentFractile90 AS PercentFractile90,
									((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
									(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
									FROM 
										(
											SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
											DATE_FORMAT(req51.date,"%c") AS mois,
											req51.nom_region AS nom_region,
											req51.libelle_unite_peche AS libelle_unite_peche,
											AVG(req51.pab) AS average_pab,
											STDDEV_SAMP(req51.pab) AS stddev_pab,
											COUNT(req51.pab) AS nbr_pab,
											(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
											(COUNT(req51.pab)-1) AS degree_liberte,
											SQRT(COUNT(req51.pab)) AS sqrt_pab
											
											FROM 
												(
													SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																fiche_echantillonnage_capture.date AS date,
																region.nom AS nom_region,
																site_embarquement.libelle AS nom_site_embarquement,
																unite_peche.libelle AS libelle_unite_peche,
																echantillon.peche_hier AS peche_hier,
																echantillon.peche_avant_hier AS peche_avant_hier,
																echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab,
																DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS	annee
															
															FROM fiche_echantillonnage_capture
																INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
																WHERE	region.id=paramsidregion 
																		AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee																		
																		AND fiche_echantillonnage_capture.id_district = Paramiddistrict
																		AND fiche_echantillonnage_capture.id_site_embarquement = Paramidsiteembarquement
												) as req51 
											GROUP BY annee,mois,nom_region,libelle_unite_peche
										) AS req52
									
									INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
									 
									GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
					) AS req56
			
			 
			GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche) AS req57 ON req41.annee=req57.annee 
	 		AND req41.libelle_unite_peche=req57.libelle_unite_peche 
			AND req57.nom_region=req41.nom_region 
 	INNER JOIN (SELECT 	req2stoke.annee AS annee,
			req2stoke.mois AS mois,
			req2stoke.nom_region AS nom_region,
			req2stoke.libelle_unite_peche AS libelle_unite_peche,
			req2stoke.average_cpue AS average_cpue,
			req2stoke.stddev_cpue AS stddev_cpue,
			req2stoke.sqrt_cpue AS sqrt_cpue,
			distribution_fractile.PercentFractile90 AS PercentFractile90,
			((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS clcpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS relErreurCPUE90,
			req2stoke.nbr_cpue AS nbr_cpue,
			(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS max_cpue
			FROM distribution_fractile
			
			INNER JOIN (SELECT 	req2.annee AS annee,
			req2.mois AS mois,
			req2.nom_region AS nom_region,
			req2.libelle_unite_peche AS libelle_unite_peche,
			req2.average_cpue AS average_cpue,
			req2.stddev_cpue AS stddev_cpue,
			req2.nbr_cpue AS nbr_cpue,
			req2.sqrt_cpue AS sqrt_cpue,
			req2.degree_liberte AS  degree_liberte
			
			FROM 
			(
				SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
						DATE_FORMAT(req1.date,"%c") AS mois,
						req1.nom_region AS nom_region,
						req1.libelle_unite_peche AS libelle_unite_peche,
						AVG(req1.cpue) AS average_cpue,
						STDDEV_SAMP(req1.cpue) AS stddev_cpue,
						COUNT(req1.cpue) AS nbr_cpue,
						SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
						COUNT(req1.cpue)-1 AS degree_liberte
						
						FROM 
							(
								SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
										region.nom AS nom_region,
										site_embarquement.libelle AS nom_site_embarquement,
										fiche_echantillonnage_capture.date AS date,
										unite_peche.libelle AS libelle_unite_peche,
										SUM(espece_capture.capture) AS capture,
										SUM(espece_capture.capture/echantillon.duree_mare) AS cpue,
										DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee
											
											FROM unite_peche
												INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
												INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
												INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
												INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
												INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
												
												WHERE fiche_echantillonnage_capture.validation=1 
														AND	region.id=paramsidregion 
														AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee																	
														AND fiche_echantillonnage_capture.id_district = Paramiddistrict
														AND fiche_echantillonnage_capture.id_site_embarquement = Paramidsiteembarquement
														
												GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
												site_embarquement.id,echantillon.id
						) AS req1
						GROUP BY annee,mois,nom_region,libelle_unite_peche
			) AS req2
			GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom) AS req3 ON req57.libelle_unite_peche=req3.libelle_unite_peche 
		 AND req3.nom_region=req57.nom_region 
		 AND req3.mois=req57.mois
		 AND req3.annee=req57.annee) AS	req62 
 		INNER JOIN (SELECT 	req71.annee AS annee,
			req71.mois AS mois,
			req71.nom_region AS nom_region,
			req71.libelle_unite_peche AS libelle_unite_peche,
			req71.Code3alpha AS Code3alpha,
			req71.capture AS capture,
			req72.capture AS totalcapture,
			(req71.capture/req72.capture) AS	CompEspece,
			req71.average_prix AS average_prix			
			
			FROM (SELECT 	DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee,
			DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") AS mois,
			region.nom AS nom_region,
			unite_peche.libelle AS libelle_unite_peche,
			espece.code AS Code3alpha,
			SUM(espece_capture.capture) AS capture,
			AVG(espece_capture.prix) AS average_prix
			
			FROM unite_peche
				INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
				INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
				INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
				INNER JOIN espece ON espece.id = espece_capture.id_espece
				INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
				WHERE	region.id=paramsidregion 
						AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
						AND fiche_echantillonnage_capture.id_district = Paramiddistrict
						AND fiche_echantillonnage_capture.id_site_embarquement = Paramidsiteembarquement
				GROUP BY annee,mois,nom_region,libelle_unite_peche,Code3alpha) AS req71
					INNER JOIN (SELECT 	region.nom AS nom_region,
			DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee,
			DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") AS mois,			
			unite_peche.libelle AS libelle_unite_peche,
			SUM(espece_capture.capture) AS capture
			
			FROM unite_peche
				INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
				INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
				INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
				INNER JOIN espece ON espece.id = espece_capture.id_espece
				INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
				WHERE	region.id=paramsidregion 
						AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee																							
						AND fiche_echantillonnage_capture.id_district = Paramiddistrict
						AND fiche_echantillonnage_capture.id_site_embarquement = Paramidsiteembarquement
				GROUP BY annee,mois,nom_region,libelle_unite_peche) as req72 ON req71.libelle_unite_peche=req72.libelle_unite_peche 
					AND req71.nom_region = req72.nom_region
					AND req71.mois=req72.mois
					AND req71.annee = req72.annee
					WHERE	req72.capture>0) AS req73	ON  req62.annee=req73.annee
 		AND	req62.mois=req73.mois
 		AND	req62.nom_region=req73.nom_region
 		AND	req62.libelle_unite_peche=req73.libelle_unite_peche;

ELSE IF(Paramiddistrict!=0) THEN

			SELECT 	req62.annee AS 'Annee',
						req62.mois AS 'Mois',
						req62.nom_region AS 'Region',
						req62.nom_site_embarquement AS 'Site embarquement',
						req62.libelle_unite_peche AS 'Unite peche',
						req62.NbMonthlyFishingDaysPAB AS	NbMonthlyFishingDaysPAB,
						req62.NbrTotalMonthlyFishingDays AS NbrTotalMonthlyFishingDays,
						req73.Code3alpha AS Code3alpha,			
						req62.relErreurCPUE90 AS relErreurCPUE90,
						req62.RelErrorCapuresTotales90 AS RelErrorCapuresTotales90,
						req62.nbr_cpue AS 'Nombre cpue',
						req62.CapturesTotalest AS CapturesTotalest, 
						req73.CompEspece AS	CompEspece,
						(req62.CapturesTotalest*req73.CompEspece) AS Total_catch_specie,
						req73.average_prix AS average_prix,
						(req62.CapturesTotalest*req73.CompEspece)*req73.average_prix AS Value_specie
			
			 
			 	FROM (SELECT 	req3.annee AS annee,
						req3.mois AS mois,
						req57.nom_region AS nom_region,
						req41.nom_site_embarquement AS nom_site_embarquement,
						req57.libelle_unite_peche AS libelle_unite_peche,
						req41.nbr_unite_peche,
						req57.NbMonthlyFishingDaysPAB,
						(req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB) AS NbrTotalMonthlyFishingDays,
						req3.average_cpue AS average_cpue,
						((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000) AS CapturesTotalest,
						req3.relErreurCPUE90 AS relErreurCPUE90,
						req3.nbr_cpue AS nbr_cpue,
						req57.avgmaxpab AS avgmaxpab,
						req3.max_cpue AS max_cpue,
						req57.monthlyday AS monthlyday,
						((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000) AS MaxCapturesTotale,
						(((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)) AS CLCaptureTotales,
						((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000))/((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)) AS RelErrorCapuresTotales90 
						
			 
			 FROM (SELECT 	enquete_cadre.annee AS annee,
						region.nom AS nom_region,
						site_embarquement.libelle AS nom_site_embarquement,
						unite_peche.libelle AS libelle_unite_peche,
						SUM(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche
						
						FROM enquete_cadre
							INNER JOIN region ON region.id= enquete_cadre.id_region
							INNER JOIN site_embarquement ON site_embarquement.id=enquete_cadre.id_site_embarquement
							INNER JOIN unite_peche ON unite_peche.id=enquete_cadre.id_unite_peche
							WHERE	region.id=paramsidregion 
									AND enquete_cadre.annee = paramsannee
									AND site_embarquement.id_district = Paramiddistrict
						
						GROUP BY annee,nom_region,nom_site_embarquement,libelle_unite_peche) AS req41 
			 	INNER JOIN (SELECT 	req56.annee AS annee,
							req56.mois AS mois,
							req56.nom_region AS nom_region,
							req56.libelle_unite_peche AS libelle_unite_peche,
							(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS monthlyday,
							AVG(req56.average_pab) AS average_pab,
							(AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS NbMonthlyFishingDaysPAB,
							AVG(req56.maxpab) AS avgmaxpab,
							IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)) AS AvgMaxPABCorrected,
							((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS MaxNbMonthlyFishingDaysPAB
							
							FROM 
								(
									SELECT 	req52.annee AS annee,
												req52.mois AS mois,
												req52.nom_region AS nom_region,
												req52.libelle_unite_peche AS libelle_unite_peche,
												req52.average_pab AS average_pab,
												((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
												req52.stddev_pab AS stddev_pab,
												req52.sqrt_pab AS sqrt_pab,
												distribution_fractile.PercentFractile90 AS PercentFractile90,
												((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
												(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
												FROM 
													(
														SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
														DATE_FORMAT(req51.date,"%c") AS mois,
														req51.nom_region AS nom_region,
														req51.libelle_unite_peche AS libelle_unite_peche,
														AVG(req51.pab) AS average_pab,
														STDDEV_SAMP(req51.pab) AS stddev_pab,
														COUNT(req51.pab) AS nbr_pab,
														(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
														(COUNT(req51.pab)-1) AS degree_liberte,
														SQRT(COUNT(req51.pab)) AS sqrt_pab
														
														FROM 
															(
																SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																			fiche_echantillonnage_capture.date AS date,
																			region.nom AS nom_region,
																			site_embarquement.libelle AS nom_site_embarquement,
																			unite_peche.libelle AS libelle_unite_peche,
																			echantillon.peche_hier AS peche_hier,
																			echantillon.peche_avant_hier AS peche_avant_hier,
																			echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																			((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab,
																			DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS	annee
																		
																		FROM fiche_echantillonnage_capture
																			INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																			INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																			INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																			INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
																			WHERE	region.id=paramsidregion 
																					AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee																		
																					AND fiche_echantillonnage_capture.id_district = Paramiddistrict
															) as req51 
														GROUP BY annee,mois,nom_region,libelle_unite_peche
													) AS req52
												
												INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
												 
												GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
								) AS req56
						
						 
						GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche) AS req57 ON req41.annee=req57.annee 
				 		AND req41.libelle_unite_peche=req57.libelle_unite_peche 
						AND req57.nom_region=req41.nom_region 
			 	INNER JOIN (SELECT 	req2stoke.annee AS annee,
						req2stoke.mois AS mois,
						req2stoke.nom_region AS nom_region,
						req2stoke.libelle_unite_peche AS libelle_unite_peche,
						req2stoke.average_cpue AS average_cpue,
						req2stoke.stddev_cpue AS stddev_cpue,
						req2stoke.sqrt_cpue AS sqrt_cpue,
						distribution_fractile.PercentFractile90 AS PercentFractile90,
						((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS clcpue,
						(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS relErreurCPUE90,
						req2stoke.nbr_cpue AS nbr_cpue,
						(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS max_cpue
						FROM distribution_fractile
						
						INNER JOIN (SELECT 	req2.annee AS annee,
						req2.mois AS mois,
						req2.nom_region AS nom_region,
						req2.libelle_unite_peche AS libelle_unite_peche,
						req2.average_cpue AS average_cpue,
						req2.stddev_cpue AS stddev_cpue,
						req2.nbr_cpue AS nbr_cpue,
						req2.sqrt_cpue AS sqrt_cpue,
						req2.degree_liberte AS  degree_liberte
						
						FROM 
						(
							SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
									DATE_FORMAT(req1.date,"%c") AS mois,
									req1.nom_region AS nom_region,
									req1.libelle_unite_peche AS libelle_unite_peche,
									AVG(req1.cpue) AS average_cpue,
									STDDEV_SAMP(req1.cpue) AS stddev_cpue,
									COUNT(req1.cpue) AS nbr_cpue,
									SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
									COUNT(req1.cpue)-1 AS degree_liberte
									
									FROM 
										(
											SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
													region.nom AS nom_region,
													site_embarquement.libelle AS nom_site_embarquement,
													fiche_echantillonnage_capture.date AS date,
													unite_peche.libelle AS libelle_unite_peche,
													SUM(espece_capture.capture) AS capture,
													SUM(espece_capture.capture/echantillon.duree_mare) AS cpue,
													DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee
														
														FROM unite_peche
															INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
															INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
															INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
															INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
															INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
															
															WHERE fiche_echantillonnage_capture.validation=1 
																	AND	region.id=paramsidregion 
																	AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee																	
																	AND fiche_echantillonnage_capture.id_district = Paramiddistrict
																	
															GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
															site_embarquement.id,echantillon.id
									) AS req1
									GROUP BY annee,mois,nom_region,libelle_unite_peche
						) AS req2
						GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom) AS req3 ON req57.libelle_unite_peche=req3.libelle_unite_peche 
					 AND req3.nom_region=req57.nom_region 
					 AND req3.mois=req57.mois
					 AND req3.annee=req57.annee) AS	req62 
			 		INNER JOIN (SELECT 	req71.annee AS annee,
						req71.mois AS mois,
						req71.nom_region AS nom_region,
						req71.libelle_unite_peche AS libelle_unite_peche,
						req71.Code3alpha AS Code3alpha,
						req71.capture AS capture,
						req72.capture AS totalcapture,
						(req71.capture/req72.capture) AS	CompEspece,
						req71.average_prix AS average_prix			
						
						FROM (SELECT 	DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee,
						DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") AS mois,
						region.nom AS nom_region,
						unite_peche.libelle AS libelle_unite_peche,
						espece.code AS Code3alpha,
						SUM(espece_capture.capture) AS capture,
						AVG(espece_capture.prix) AS average_prix
						
						FROM unite_peche
							INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
							INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
							INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
							INNER JOIN espece ON espece.id = espece_capture.id_espece
							INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
							WHERE	region.id=paramsidregion 
									AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
									AND fiche_echantillonnage_capture.id_district = Paramiddistrict
							GROUP BY annee,mois,nom_region,libelle_unite_peche,Code3alpha) AS req71
								INNER JOIN (SELECT 	region.nom AS nom_region,
						DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee,
						DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") AS mois,			
						unite_peche.libelle AS libelle_unite_peche,
						SUM(espece_capture.capture) AS capture
						
						FROM unite_peche
							INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
							INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
							INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
							INNER JOIN espece ON espece.id = espece_capture.id_espece
							INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
							WHERE	region.id=paramsidregion 
									AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee																							
									AND fiche_echantillonnage_capture.id_district = Paramiddistrict
							GROUP BY annee,mois,nom_region,libelle_unite_peche) as req72 ON req71.libelle_unite_peche=req72.libelle_unite_peche 
								AND req71.nom_region = req72.nom_region
								AND req71.mois=req72.mois
								AND req71.annee = req72.annee
								WHERE	req72.capture>0) AS req73	ON  req62.annee=req73.annee
			 		AND	req62.mois=req73.mois
			 		AND	req62.nom_region=req73.nom_region
			 		AND	req62.libelle_unite_peche=req73.libelle_unite_peche;
		ELSE
		
			
			SELECT 	req62.annee AS 'Annee',
						req62.mois AS 'Mois',
						req62.nom_region AS 'Region',
						req62.nom_site_embarquement AS 'Site embarquement',
						req62.libelle_unite_peche AS 'Unite peche',
						req62.NbMonthlyFishingDaysPAB AS	NbMonthlyFishingDaysPAB,
						req62.NbrTotalMonthlyFishingDays AS NbrTotalMonthlyFishingDays,
						req73.Code3alpha AS Code3alpha,			
						req62.relErreurCPUE90 AS relErreurCPUE90,
						req62.RelErrorCapuresTotales90 AS RelErrorCapuresTotales90,
						req62.nbr_cpue AS 'Nombre cpue',
						req62.CapturesTotalest AS CapturesTotalest, 
						req73.CompEspece AS	CompEspece,
						(req62.CapturesTotalest*req73.CompEspece) AS Total_catch_specie,
						req73.average_prix AS average_prix,
						(req62.CapturesTotalest*req73.CompEspece)*req73.average_prix AS Value_specie
			
			 
			 	FROM (SELECT 	req3.annee AS annee,
						req3.mois AS mois,
						req57.nom_region AS nom_region,
						req41.nom_site_embarquement AS nom_site_embarquement,
						req57.libelle_unite_peche AS libelle_unite_peche,
						req41.nbr_unite_peche,
						req57.NbMonthlyFishingDaysPAB,
						(req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB) AS NbrTotalMonthlyFishingDays,
						req3.average_cpue AS average_cpue,
						((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000) AS CapturesTotalest,
						req3.relErreurCPUE90 AS relErreurCPUE90,
						req3.nbr_cpue AS nbr_cpue,
						req57.avgmaxpab AS avgmaxpab,
						req3.max_cpue AS max_cpue,
						req57.monthlyday AS monthlyday,
						((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000) AS MaxCapturesTotale,
						(((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)) AS CLCaptureTotales,
						((((req41.nbr_unite_peche*req57.avgmaxpab*req57.monthlyday*req3.max_cpue)/1000)-((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000))/((req41.nbr_unite_peche*req57.NbMonthlyFishingDaysPAB*req3.average_cpue)/1000)) AS RelErrorCapuresTotales90 
						
			 
			 FROM (SELECT 	enquete_cadre.annee AS annee,
						region.nom AS nom_region,
						site_embarquement.libelle AS nom_site_embarquement,
						unite_peche.libelle AS libelle_unite_peche,
						SUM(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche
						
						FROM enquete_cadre
							INNER JOIN region ON region.id= enquete_cadre.id_region
							INNER JOIN site_embarquement ON site_embarquement.id=enquete_cadre.id_site_embarquement
							INNER JOIN unite_peche ON unite_peche.id=enquete_cadre.id_unite_peche
							WHERE	region.id=paramsidregion 
									AND enquete_cadre.annee = paramsannee
						
						GROUP BY annee,nom_region,nom_site_embarquement,libelle_unite_peche) AS req41 
			 	INNER JOIN (SELECT 	req56.annee AS annee,
							req56.mois AS mois,
							req56.nom_region AS nom_region,
							req56.libelle_unite_peche AS libelle_unite_peche,
							(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e")) AS monthlyday,
							AVG(req56.average_pab) AS average_pab,
							(AVG(req56.average_pab)*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS NbMonthlyFishingDaysPAB,
							AVG(req56.maxpab) AS avgmaxpab,
							IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)) AS AvgMaxPABCorrected,
							((IF(AVG(req56.maxpab)>1,1,AVG(req56.maxpab)))*(DATE_FORMAT(LAST_DAY(CONCAT(req56.annee,'/',mois,'/01')),"%e"))) AS MaxNbMonthlyFishingDaysPAB
							
							FROM 
								(
									SELECT 	req52.annee AS annee,
												req52.mois AS mois,
												req52.nom_region AS nom_region,
												req52.libelle_unite_peche AS libelle_unite_peche,
												req52.average_pab AS average_pab,
												((((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )+req52.average_pab) AS maxpab,
												req52.stddev_pab AS stddev_pab,
												req52.sqrt_pab AS sqrt_pab,
												distribution_fractile.PercentFractile90 AS PercentFractile90,
												((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab)) AS RelError90PAB,
												(((req52.stddev_pab*distribution_fractile.PercentFractile90)/(req52.average_pab*req52.sqrt_pab))*req52.average_pab )AS clpab
												FROM 
													(
														SELECT 	DATE_FORMAT(req51.date,"%Y") AS annee,
														DATE_FORMAT(req51.date,"%c") AS mois,
														req51.nom_region AS nom_region,
														req51.libelle_unite_peche AS libelle_unite_peche,
														AVG(req51.pab) AS average_pab,
														STDDEV_SAMP(req51.pab) AS stddev_pab,
														COUNT(req51.pab) AS nbr_pab,
														(AVG(req51.pab)*30.5) AS NbMonthlyFishingDaysFAC,
														(COUNT(req51.pab)-1) AS degree_liberte,
														SQRT(COUNT(req51.pab)) AS sqrt_pab
														
														FROM 
															(
																SELECT 	fiche_echantillonnage_capture.code_unique AS code_unique,
																			fiche_echantillonnage_capture.date AS date,
																			region.nom AS nom_region,
																			site_embarquement.libelle AS nom_site_embarquement,
																			unite_peche.libelle AS libelle_unite_peche,
																			echantillon.peche_hier AS peche_hier,
																			echantillon.peche_avant_hier AS peche_avant_hier,
																			echantillon.nbr_jrs_peche_dernier_sem AS nbr_jrs_peche_dernier_sem,
																			((1+COALESCE(echantillon.peche_hier,0)+COALESCE(echantillon.peche_avant_hier,0)+COALESCE(echantillon.nbr_jrs_peche_dernier_sem,0))/10) AS pab,
																			DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS	annee
																		
																		FROM fiche_echantillonnage_capture
																			INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
																			INNER JOIN site_embarquement ON site_embarquement.id=fiche_echantillonnage_capture.id_site_embarquement
																			INNER JOIN echantillon ON echantillon.id_fiche_echantillonnage_capture=fiche_echantillonnage_capture.id
																			INNER JOIN unite_peche ON unite_peche.id=echantillon.id_unite_peche
																			WHERE	region.id=paramsidregion 
																					AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee	
															) as req51 
														GROUP BY annee,mois,nom_region,libelle_unite_peche
													) AS req52
												
												INNER JOIN distribution_fractile ON req52.degree_liberte=distribution_fractile.DegreesofFreedom
												 
												GROUP BY req52.annee,req52.mois,req52.nom_region,req52.libelle_unite_peche
								) AS req56
						
						 
						GROUP BY req56.annee,req56.mois,req56.nom_region,req56.libelle_unite_peche) AS req57 ON req41.annee=req57.annee 
				 		AND req41.libelle_unite_peche=req57.libelle_unite_peche 
						AND req57.nom_region=req41.nom_region 
			 	INNER JOIN (SELECT 	req2stoke.annee AS annee,
						req2stoke.mois AS mois,
						req2stoke.nom_region AS nom_region,
						req2stoke.libelle_unite_peche AS libelle_unite_peche,
						req2stoke.average_cpue AS average_cpue,
						req2stoke.stddev_cpue AS stddev_cpue,
						req2stoke.sqrt_cpue AS sqrt_cpue,
						distribution_fractile.PercentFractile90 AS PercentFractile90,
						((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue) AS clcpue,
						(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)/req2stoke.average_cpue) AS relErreurCPUE90,
						req2stoke.nbr_cpue AS nbr_cpue,
						(((distribution_fractile.PercentFractile90 * req2stoke.stddev_cpue)/req2stoke.sqrt_cpue)+req2stoke.average_cpue) AS max_cpue
						FROM distribution_fractile
						
						INNER JOIN (SELECT 	req2.annee AS annee,
						req2.mois AS mois,
						req2.nom_region AS nom_region,
						req2.libelle_unite_peche AS libelle_unite_peche,
						req2.average_cpue AS average_cpue,
						req2.stddev_cpue AS stddev_cpue,
						req2.nbr_cpue AS nbr_cpue,
						req2.sqrt_cpue AS sqrt_cpue,
						req2.degree_liberte AS  degree_liberte
						
						FROM 
						(
							SELECT 	DATE_FORMAT(req1.date,"%Y") AS annee,
									DATE_FORMAT(req1.date,"%c") AS mois,
									req1.nom_region AS nom_region,
									req1.libelle_unite_peche AS libelle_unite_peche,
									AVG(req1.cpue) AS average_cpue,
									STDDEV_SAMP(req1.cpue) AS stddev_cpue,
									COUNT(req1.cpue) AS nbr_cpue,
									SQRT(COUNT(req1.cpue)) AS sqrt_cpue,
									COUNT(req1.cpue)-1 AS degree_liberte
									
									FROM 
										(
											SELECT fiche_echantillonnage_capture.code_unique AS code_unique,
													region.nom AS nom_region,
													site_embarquement.libelle AS nom_site_embarquement,
													fiche_echantillonnage_capture.date AS date,
													unite_peche.libelle AS libelle_unite_peche,
													SUM(espece_capture.capture) AS capture,
													SUM(espece_capture.capture/echantillon.duree_mare) AS cpue,
													DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee
														
														FROM unite_peche
															INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
															INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
															INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
															INNER JOIN site_embarquement ON site_embarquement.id = fiche_echantillonnage_capture.id_site_embarquement
															INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
															
															WHERE fiche_echantillonnage_capture.validation=1 
																	AND	region.id=paramsidregion 
																	AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee	
																	
															GROUP BY fiche_echantillonnage_capture.code_unique,fiche_echantillonnage_capture.date,fiche_echantillonnage_capture.id_region,
															site_embarquement.id,echantillon.id
									) AS req1
									GROUP BY annee,mois,nom_region,libelle_unite_peche
						) AS req2
						GROUP BY req2.annee,req2.mois,req2.nom_region,req2.libelle_unite_peche)AS req2stoke ON req2stoke.degree_liberte=distribution_fractile.DegreesofFreedom) AS req3 ON req57.libelle_unite_peche=req3.libelle_unite_peche 
					 AND req3.nom_region=req57.nom_region 
					 AND req3.mois=req57.mois
					 AND req3.annee=req57.annee) AS	req62 
			 		INNER JOIN (SELECT 	req71.annee AS annee,
						req71.mois AS mois,
						req71.nom_region AS nom_region,
						req71.libelle_unite_peche AS libelle_unite_peche,
						req71.Code3alpha AS Code3alpha,
						req71.capture AS capture,
						req72.capture AS totalcapture,
						(req71.capture/req72.capture) AS	CompEspece,
						req71.average_prix AS average_prix			
						
						FROM (SELECT 	DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee,
						DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") AS mois,
						region.nom AS nom_region,
						unite_peche.libelle AS libelle_unite_peche,
						espece.code AS Code3alpha,
						SUM(espece_capture.capture) AS capture,
						AVG(espece_capture.prix) AS average_prix
						
						FROM unite_peche
							INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
							INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
							INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
							INNER JOIN espece ON espece.id = espece_capture.id_espece
							INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
							WHERE	region.id=paramsidregion 
									AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
							GROUP BY annee,mois,nom_region,libelle_unite_peche,Code3alpha) AS req71
								INNER JOIN (SELECT 	region.nom AS nom_region,
						DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y") AS annee,
						DATE_FORMAT(fiche_echantillonnage_capture.date,"%c") AS mois,			
						unite_peche.libelle AS libelle_unite_peche,
						SUM(espece_capture.capture) AS capture
						
						FROM unite_peche
							INNER JOIN echantillon ON echantillon.id_unite_peche=unite_peche.id
							INNER JOIN espece_capture ON espece_capture.id_echantillon=echantillon.id
							INNER JOIN fiche_echantillonnage_capture ON fiche_echantillonnage_capture.id=echantillon.id_fiche_echantillonnage_capture
							INNER JOIN espece ON espece.id = espece_capture.id_espece
							INNER JOIN region ON region.id=fiche_echantillonnage_capture.id_region
							WHERE	region.id=paramsidregion 
									AND DATE_FORMAT(fiche_echantillonnage_capture.date,"%Y")=paramsannee
							GROUP BY annee,mois,nom_region,libelle_unite_peche) as req72 ON req71.libelle_unite_peche=req72.libelle_unite_peche 
								AND req71.nom_region = req72.nom_region
								AND req71.mois=req72.mois
								AND req71.annee = req72.annee
								WHERE	req72.capture>0) AS req73	ON  req62.annee=req73.annee
			 		AND	req62.mois=req73.mois
			 		AND	req62.nom_region=req73.nom_region
			 		AND	req62.libelle_unite_peche=req73.libelle_unite_peche;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_9_2
DROP VIEW IF EXISTS `req_9_2`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_9_2` (
	`annee` INT(4) NULL,
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`nbr_unite_peche` DECIMAL(32,0) NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_9_2_stocke
DROP PROCEDURE IF EXISTS `req_9_2_stocke`;
DELIMITER //
CREATE PROCEDURE `req_9_2_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT req92.annee AS 'Annee',
			req92.nom_site_embarquement AS 'Site embarquement',
			req92.libelle_unite_peche AS 'Unite peche',
			req92.nbr_unite_peche AS 'Nombre unite peche'
 	FROM (select enquete_cadre.annee AS annee,
		site_embarquement.libelle AS nom_site_embarquement,
		unite_peche.libelle AS libelle_unite_peche,
		sum(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche,
		region.id AS id_region 
	
		from (((enquete_cadre 
				join region on(region.id = enquete_cadre.id_region)) 
				join site_embarquement on(site_embarquement.id = enquete_cadre.id_site_embarquement)) 
				join unite_peche on(unite_peche.id = enquete_cadre.id_unite_peche)) 
				WHERE region.id=paramsidregion		  
		 				AND annee=paramsannee
		 				AND site_embarquement.id_district=Paramiddistrict
		 				AND site_embarquement.id=Paramidsiteembarquement
				group by enquete_cadre.annee,site_embarquement.libelle,unite_peche.libelle ) AS req92;
ELSE IF(Paramiddistrict!=0) THEN
			SELECT req92.annee AS 'Annee',
						req92.nom_site_embarquement AS 'Site embarquement',
						req92.libelle_unite_peche AS 'Unite peche',
						req92.nbr_unite_peche AS 'Nombre unite peche'
			 	FROM (select enquete_cadre.annee AS annee,
					site_embarquement.libelle AS nom_site_embarquement,
					unite_peche.libelle AS libelle_unite_peche,
					sum(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche,
					region.id AS id_region 
				
					from (((enquete_cadre 
							join region on(region.id = enquete_cadre.id_region)) 
							join site_embarquement on(site_embarquement.id = enquete_cadre.id_site_embarquement)) 
							join unite_peche on(unite_peche.id = enquete_cadre.id_unite_peche)) 
							WHERE region.id=paramsidregion		  
					 				AND annee=paramsannee
					 				AND site_embarquement.id_district=Paramiddistrict
							group by enquete_cadre.annee,site_embarquement.libelle,unite_peche.libelle ) AS req92;
		ELSE
			SELECT req92.annee AS 'Annee',
				req92.nom_site_embarquement AS 'Site embarquement',
				req92.libelle_unite_peche AS 'Unite peche',
				req92.nbr_unite_peche AS 'Nombre unite peche'
	 	FROM (select enquete_cadre.annee AS annee,
			site_embarquement.libelle AS nom_site_embarquement,
			unite_peche.libelle AS libelle_unite_peche,
			sum(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche,
			region.id AS id_region 
		
			from (((enquete_cadre 
					join region on(region.id = enquete_cadre.id_region)) 
					join site_embarquement on(site_embarquement.id = enquete_cadre.id_site_embarquement)) 
					join unite_peche on(unite_peche.id = enquete_cadre.id_unite_peche)) 
					WHERE region.id=paramsidregion		  
			 				AND annee=paramsannee
					group by enquete_cadre.annee,site_embarquement.libelle,unite_peche.libelle ) AS req92;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_9_3
DROP VIEW IF EXISTS `req_9_3`;
-- Création d'une table temporaire pour palier aux erreurs de dépendances de VIEW
CREATE TABLE `req_9_3` (
	`annee` INT(4) NULL,
	`nom_region` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`nom_site_embarquement` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`libelle_unite_peche` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`nbr_unite_peche` DECIMAL(32,0) NULL,
	`id_region` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Listage de la structure de la procédure peche_db. req_9_3_stocke
DROP PROCEDURE IF EXISTS `req_9_3_stocke`;
DELIMITER //
CREATE PROCEDURE `req_9_3_stocke`(
	IN `paramsidregion` INT,
	IN `paramsannee` INT,
	IN `Paramiddistrict` INT,
	IN `Paramidsiteembarquement` INT
)
BEGIN
IF(Paramiddistrict!=0 AND Paramidsiteembarquement!=0) THEN
	SELECT req93.annee AS 'Annee',
			req93.nom_region AS 'Nom region',
			req93.libelle_unite_peche AS 'Unite peche',
			req93.nbr_unite_peche AS 'Nombre unite peche'
	 
		FROM (select enquete_cadre.annee AS annee,
			region.nom AS nom_region,
			site_embarquement.libelle AS nom_site_embarquement,
			unite_peche.libelle AS libelle_unite_peche,
			sum(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche,
			region.id AS id_region 
			
		from (((enquete_cadre 
				join region on(region.id = enquete_cadre.id_region)) 
				join site_embarquement on(site_embarquement.id = enquete_cadre.id_site_embarquement)) 
				join unite_peche on(unite_peche.id = enquete_cadre.id_unite_peche))
			
				WHERE region.id=paramsidregion 
						AND annee=paramsannee
						AND site_embarquement.id_district=Paramiddistrict 
						AND site_embarquement.id=Paramidsiteembarquement
			group by enquete_cadre.annee,region.nom,unite_peche.libelle ) AS req93;
	
	ELSE IF(Paramiddistrict!=0) THEN
	
			SELECT req93.annee AS 'Annee',
					req93.nom_region AS 'Nom region',
					req93.libelle_unite_peche AS 'Unite peche',
					req93.nbr_unite_peche AS 'Nombre unite peche'
			 
				FROM (select enquete_cadre.annee AS annee,
					region.nom AS nom_region,
					site_embarquement.libelle AS nom_site_embarquement,
					unite_peche.libelle AS libelle_unite_peche,
					sum(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche,
					region.id AS id_region 
					
				from (((enquete_cadre 
						join region on(region.id = enquete_cadre.id_region)) 
						join site_embarquement on(site_embarquement.id = enquete_cadre.id_site_embarquement)) 
						join unite_peche on(unite_peche.id = enquete_cadre.id_unite_peche))
					
						WHERE region.id=paramsidregion 
								AND annee=paramsannee
								AND site_embarquement.id_district=Paramiddistrict
					group by enquete_cadre.annee,region.nom,unite_peche.libelle ) AS req93;
		
		ELSE
			SELECT req93.annee AS 'Annee',
				req93.nom_region AS 'Nom region',
				req93.libelle_unite_peche AS 'Unite peche',
				req93.nbr_unite_peche AS 'Nombre unite peche'
		 
			FROM (select enquete_cadre.annee AS annee,
				region.nom AS nom_region,
				site_embarquement.libelle AS nom_site_embarquement,
				unite_peche.libelle AS libelle_unite_peche,
				sum(enquete_cadre.nbr_unite_peche) AS nbr_unite_peche,
				region.id AS id_region 
				
			from (((enquete_cadre 
					join region on(region.id = enquete_cadre.id_region)) 
					join site_embarquement on(site_embarquement.id = enquete_cadre.id_site_embarquement)) 
					join unite_peche on(unite_peche.id = enquete_cadre.id_unite_peche))
				
					WHERE region.id=paramsidregion 
							AND annee=paramsannee
				group by enquete_cadre.annee,region.nom,unite_peche.libelle ) AS req93;
		END IF;
	END IF;
END//
DELIMITER ;

-- Listage de la structure de la vue peche_db. req_1
DROP VIEW IF EXISTS `req_1`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_1`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_1` AS SELECT 
			`fiche_echantillonnage_capture`.`code_unique` AS `code_unique`,
			`region`.`nom` AS `nom_region`,
			`site_embarquement`.`libelle` AS `nom_site_embarquement`,
			`fiche_echantillonnage_capture`.`date` AS `date`,
			`unite_peche`.`libelle` AS `libelle_unite_peche`,
			sum(`espece_capture`.`capture`) AS `capture`,
			sum(`espece_capture`.`capture` / `echantillon`.`duree_mare`) AS `cpue`,
			`region`.`id` AS `id_region`,
			date_format(`fiche_echantillonnage_capture`.`date`,'%Y') AS `annee`,
			`espece`.`id` AS `id_espece`,
			`site_embarquement`.`id` AS `id_site_embarquement`,
			`district`.`id` AS `id_district`,
			date_format(`fiche_echantillonnage_capture`.`date`,'%c') AS `mois`
			
			from 
					(
						(
							(	
								(
									(
										(
											(`unite_peche` join `echantillon` on(`echantillon`.`id_unite_peche` = `unite_peche`.`id`)) 
											join `espece_capture` on(`espece_capture`.`id_echantillon` = `echantillon`.`id`)
										) 
										join `fiche_echantillonnage_capture` on(`fiche_echantillonnage_capture`.`id` = `echantillon`.`id_fiche_echantillonnage_capture`)
									) 
									join `site_embarquement` on(`site_embarquement`.`id` = `fiche_echantillonnage_capture`.`id_site_embarquement`)
								) 
								join `region` on(`region`.`id` = `fiche_echantillonnage_capture`.`id_region`)
							)
							join `district` on(`district`.`id` = `fiche_echantillonnage_capture`.`id_district`)
						)
						join `espece` on(`espece`.`id` = `espece_capture`.`id_espece`)
					) 
					where `fiche_echantillonnage_capture`.`validation` = 1 
					group by `fiche_echantillonnage_capture`.`code_unique`,`fiche_echantillonnage_capture`.`date`,`fiche_echantillonnage_capture`.`id_region`,`site_embarquement`.`id`,`echantillon`.`id` ;

-- Listage de la structure de la vue peche_db. req_2
DROP VIEW IF EXISTS `req_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_2` AS SELECT 
			date_format(`req_1`.`date`,'%Y') AS `annee`,
			date_format(`req_1`.`date`,'%c') AS `mois`,
			`req_1`.`nom_region` AS `nom_region`,
			`req_1`.`libelle_unite_peche` AS `libelle_unite_peche`,
			avg(`req_1`.`cpue`) AS `average_cpue`,
			std(`req_1`.`cpue`) AS `stddev_cpue`,
			count(`req_1`.`cpue`) AS `nbr_cpue`,
			sqrt(count(`req_1`.`cpue`)) AS `sqrt_cpue`,
			count(`req_1`.`cpue`) - 1 AS `degree_liberte`,
			`req_1`.`id_site_embarquement` AS `id_site_embarquement`,
			`req_1`.`id_district` AS `id_district`
			from `req_1` 
					group by `req_1`.`annee`,
								date_format(`req_1`.`date`,'%c'),
								`req_1`.`nom_region`,
								`req_1`.`libelle_unite_peche` ;

-- Listage de la structure de la vue peche_db. req_3
DROP VIEW IF EXISTS `req_3`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_3`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_3` AS select `req_2`.`annee` AS `annee`,`req_2`.`mois` AS `mois`,`req_2`.`nom_region` AS `nom_region`,`req_2`.`libelle_unite_peche` AS `libelle_unite_peche`,`req_2`.`average_cpue` AS `average_cpue`,`req_2`.`stddev_cpue` AS `stdev_cpue`,`req_2`.`sqrt_cpue` AS `sqrt_cpue`,`distribution_fractile`.`PercentFractile90` AS `percentFractile90`,`distribution_fractile`.`PercentFractile90` * `req_2`.`stddev_cpue` / `req_2`.`sqrt_cpue` AS `clcpue`,`distribution_fractile`.`PercentFractile90` * `req_2`.`stddev_cpue` / `req_2`.`sqrt_cpue` / `req_2`.`average_cpue` AS `relErreurCPUE90`,`req_2`.`nbr_cpue` AS `nbr_cpue`,`distribution_fractile`.`PercentFractile90` * `req_2`.`stddev_cpue` / `req_2`.`sqrt_cpue` + `req_2`.`average_cpue` AS `max_cpue` from (`distribution_fractile` join `req_2` on(`req_2`.`degree_liberte` = `distribution_fractile`.`DegreesofFreedom`)) ;

-- Listage de la structure de la vue peche_db. req_4
DROP VIEW IF EXISTS `req_4`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_4`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_4` AS select `enquete_cadre`.`annee` AS `annee`,
			`region`.`nom` AS `nom_region`,
			`site_embarquement`.`libelle` AS `nom_site_embarquement`,
			`unite_peche`.`libelle` AS `libelle_unite_peche`,
			sum(`enquete_cadre`.`nbr_unite_peche`) AS `nbr_unite_peche`,
			`region`.`id` AS `id_region`,
			`site_embarquement`.`id_district` AS `id_district`,
			`site_embarquement`.`id` AS `id_site_embarquement`
			FROM (((`enquete_cadre` 
						join `region` on(`region`.`id` = `enquete_cadre`.`id_region`)) 
						join `site_embarquement` on(`site_embarquement`.`id` = `enquete_cadre`.`id_site_embarquement`)) 
						join `unite_peche` on(`unite_peche`.`id` = `enquete_cadre`.`id_unite_peche`))
						group by `enquete_cadre`.`annee`,`region`.`nom`,`site_embarquement`.`libelle`,`unite_peche`.`libelle` ;

-- Listage de la structure de la vue peche_db. req_4_2
DROP VIEW IF EXISTS `req_4_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_4_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_4_2` AS select `region`.`nom` AS `nom_region`,
			`unite_peche`.`libelle` AS `libelle_unite_peche`,
			sum(`enquete_cadre`.`nbr_unite_peche`) AS `nbr_unite_peche`,
			`enquete_cadre`.`annee` AS `annee`,
			`region`.`id` AS `id_region` ,
			`site_embarquement`.`id` AS `id_site_embarquement`,
			`site_embarquement`.`id_district` AS `id_district`
			
			FROM (((`enquete_cadre` 
						join `region` on(`region`.`id` = `enquete_cadre`.`id_region`)) 
						join `unite_peche` on(`unite_peche`.`id` = `enquete_cadre`.`id_unite_peche`)) 
						join `site_embarquement` on(`site_embarquement`.`id` = `enquete_cadre`.`id_site_embarquement`))
						
						group by `enquete_cadre`.`annee`,`region`.`nom`,`site_embarquement`.`id_district`,`site_embarquement`.`id`,`unite_peche`.`libelle` ;

-- Listage de la structure de la vue peche_db. req_5_1
DROP VIEW IF EXISTS `req_5_1`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_5_1`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_5_1` AS select `fiche_echantillonnage_capture`.`code_unique` AS `code_unique`,
			`fiche_echantillonnage_capture`.`date` AS `date`,
			`region`.`nom` AS `nom_region`,
			`site_embarquement`.`libelle` AS `nom_site_embarquement`,
			`unite_peche`.`libelle` AS `libelle_unite_peche`,
			`echantillon`.`peche_hier` AS `peche_hier`,
			`echantillon`.`peche_avant_hier` AS `peche_avant_hier`,
			`echantillon`.`nbr_jrs_peche_dernier_sem` AS `nbr_jrs_peche_dernier_sem`,
			(1 + coalesce(`echantillon`.`peche_hier`,0) + coalesce(`echantillon`.`peche_avant_hier`,0) + coalesce(`echantillon`.`nbr_jrs_peche_dernier_sem`,0)) / 10 AS `pab`,
			`region`.`id` AS `id_region`,
			`site_embarquement`.`id` AS `id_site_embarquement`,
			`site_embarquement`.`id_district` AS `id_district` 
			
			from ((((`fiche_echantillonnage_capture` 
					join `region` on(`region`.`id` = `fiche_echantillonnage_capture`.`id_region`)) 
					join `site_embarquement` on(`site_embarquement`.`id` = `fiche_echantillonnage_capture`.`id_site_embarquement`)) 
					join `echantillon` on(`echantillon`.`id_fiche_echantillonnage_capture` = `fiche_echantillonnage_capture`.`id`)) 
					join `unite_peche` on(`unite_peche`.`id` = `echantillon`.`id_unite_peche`)) ;

-- Listage de la structure de la vue peche_db. req_5_2
DROP VIEW IF EXISTS `req_5_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_5_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_5_2` AS select date_format(`req_5_1`.`date`,'%Y') AS `annee`,
		date_format(`req_5_1`.`date`,'%c') AS `mois`,
		`req_5_1`.`nom_region` AS `nom_region`,
		`req_5_1`.`libelle_unite_peche` AS `libelle_unite_peche`,
		avg(`req_5_1`.`pab`) AS `average_pab`,
		std(`req_5_1`.`pab`) AS `stddev_pab`,
		count(`req_5_1`.`pab`) AS `nbr_pab`,
		avg(`req_5_1`.`pab`) * 30.5 AS `NbMonthlyFishingDaysFAC`,
		count(`req_5_1`.`pab`) - 1 AS `degree_liberte`,
		sqrt(count(`req_5_1`.`pab`)) AS `sqrt_pab`,
		`req_5_1`.`id_region` AS `id_region` 
		
		from `req_5_1` 
		group by date_format(`req_5_1`.`date`,'%Y'),
					date_format(`req_5_1`.`date`,'%c'),
					`req_5_1`.`nom_region`,
					`req_5_1`.`libelle_unite_peche` ;

-- Listage de la structure de la vue peche_db. req_5_6
DROP VIEW IF EXISTS `req_5_6`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_5_6`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_5_6` AS select `req52`.`annee` AS `annee`,
			`req52`.`mois` AS `mois`,
			`req52`.`nom_region` AS `nom_region`,
			`req52`.`libelle_unite_peche` AS `libelle_unite_peche`,
			`req52`.`average_pab` AS `average_pab`,
			`req52`.`stddev_pab` * `distribution_fractile`.`PercentFractile90` / (`req52`.`average_pab` * `req52`.`sqrt_pab`) * `req52`.`average_pab` + `req52`.`average_pab` AS `maxpab`,
			`req52`.`stddev_pab` AS `stddev_pab`,
			`req52`.`sqrt_pab` AS `sqrt_pab`,
			`distribution_fractile`.`PercentFractile90` AS `percentFractile90`,
			`req52`.`stddev_pab` * `distribution_fractile`.`PercentFractile90` / (`req52`.`average_pab` * `req52`.`sqrt_pab`) AS `relError90PAB`,
			`req52`.`stddev_pab` * `distribution_fractile`.`PercentFractile90` / (`req52`.`average_pab` * `req52`.`sqrt_pab`) * `req52`.`average_pab` AS `clpab`,
			`req52`.`id_region` AS `id_region` 
		
		from (`req_5_2` `req52` 
				join `distribution_fractile` on(`req52`.`degree_liberte` = `distribution_fractile`.`DegreesofFreedom`)) 
				group by `req52`.`annee`,`req52`.`mois`,`req52`.`nom_region`,`req52`.`libelle_unite_peche` ;

-- Listage de la structure de la vue peche_db. req_5_7
DROP VIEW IF EXISTS `req_5_7`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_5_7`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_5_7` AS select `req56`.`annee` AS `annee`,
		`req56`.`mois` AS `mois`,
		`req56`.`nom_region` AS `nom_region`,
		`req56`.`libelle_unite_peche` AS `libelle_unite_peche`,
		date_format(last_day(concat(`req56`.`annee`,'/',`req56`.`mois`,'/01')),'%e') AS `monthlyday`,
		avg(`req56`.`average_pab`) AS `average_pab`,
		`req56`.`stddev_pab` AS `stddev_pab`,
		avg(`req56`.`average_pab`) * date_format(last_day(concat(`req56`.`annee`,'/',`req56`.`mois`,'/01')),'%e') AS `NbMonthlyFishingDaysPAB`,
		avg(`req56`.`maxpab`) AS `avgmaxpab`,
		if(avg(`req56`.`maxpab`) > 1,1,avg(`req56`.`maxpab`)) AS `AvgMaxPABCorrected`,
		if(avg(`req56`.`maxpab`) > 1,1,avg(`req56`.`maxpab`)) * date_format(last_day(concat(`req56`.`annee`,'/',`req56`.`mois`,'/01')),'%e') AS `MaxNbMonthlyFishingDaysPAB`,
		`req56`.`id_region` AS `id_region` 
from `req_5_6` AS `req56` 
group by `req56`.`annee`,`req56`.`mois`,`req56`.`nom_region`,`req56`.`libelle_unite_peche` ;

-- Listage de la structure de la vue peche_db. req_6_2
DROP VIEW IF EXISTS `req_6_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_6_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_6_2` AS select `req3`.`annee` AS `annee`,
			`req3`.`mois` AS `mois`,
			`req57`.`nom_region` AS `nom_region`,
			`req41`.`nom_site_embarquement` AS `nom_site_embarquement`,
			`req57`.`libelle_unite_peche` AS `libelle_unite_peche`,
			`req41`.`nbr_unite_peche` AS `nbr_unite_peche`,
			`req57`.`NbMonthlyFishingDaysPAB` AS `NbMonthlyFishingDaysPAB`,
			`req41`.`nbr_unite_peche` * `req57`.`NbMonthlyFishingDaysPAB` AS `NbrTotalMonthlyFishingDays`,
			`req3`.`average_cpue` AS `average_cpue`,
			`req41`.`nbr_unite_peche` * `req57`.`NbMonthlyFishingDaysPAB` * `req3`.`average_cpue` / 1000 AS `CapturesTotalest`,
			`req3`.`relErreurCPUE90` AS `relErreurCPUE90`,
			`req3`.`nbr_cpue` AS `nbr_cpue`,
			`req57`.`avgmaxpab` AS `avgmaxpab`,
			`req3`.`max_cpue` AS `max_cpue`,
			`req57`.`monthlyday` AS `monthlyday`,
			`req41`.`nbr_unite_peche` * `req57`.`avgmaxpab` * `req57`.`monthlyday` * `req3`.`max_cpue` / 1000 AS `MaxCapturesTotale`,
			`req41`.`nbr_unite_peche` * `req57`.`avgmaxpab` * `req57`.`monthlyday` * `req3`.`max_cpue` / 1000 - `req41`.`nbr_unite_peche` * `req57`.`NbMonthlyFishingDaysPAB` * `req3`.`average_cpue` / 1000 AS `CLCaptureTotales`,
			(`req41`.`nbr_unite_peche` * `req57`.`avgmaxpab` * `req57`.`monthlyday` * `req3`.`max_cpue` / 1000 - `req41`.`nbr_unite_peche` * `req57`.`NbMonthlyFishingDaysPAB` * `req3`.`average_cpue` / 1000) / (`req41`.`nbr_unite_peche` * `req57`.`NbMonthlyFishingDaysPAB` * `req3`.`average_cpue` / 1000) AS `RelErrorCapuresTotales90`,
			`req41`.`id_region` AS `id_region`,			
			`req41`.`id_district` AS `id_district`,
			`req41`.`id_site_embarquement` AS `id_site_embarquement`
			from ((`req_4` AS `req41` 
						join `req_5_7` `req57` on(`req41`.`annee` = `req57`.`annee` 
								and `req41`.`libelle_unite_peche` = `req57`.`libelle_unite_peche` 
								and `req57`.`nom_region` = `req41`.`nom_region`)) 
						join `req_3` `req3` on(`req57`.`libelle_unite_peche` = `req3`.`libelle_unite_peche` 
								and `req3`.`nom_region` = `req57`.`nom_region` 
								and `req3`.`mois` = `req57`.`mois` 
								and `req3`.`annee` = `req57`.`annee`)) ;

-- Listage de la structure de la vue peche_db. req_6_2_a
DROP VIEW IF EXISTS `req_6_2_a`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_6_2_a`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_6_2_a` AS select `req62`.`annee` AS `annee`,
			`req62`.`libelle_unite_peche` AS `libelle_unite_peche`,
			sum(`req62`.`NbrTotalMonthlyFishingDays`) AS `TotalAnnualFishingDays`,
			`req62`.`id_region` AS `id_region` ,			
			`req62`.`id_district` AS `id_district`,
			`req62`.`id_site_embarquement` AS `id_site_embarquement`
			
			from `req_6_2` `req62` 
			GROUP by req62.annee,req62.libelle_unite_peche ;

-- Listage de la structure de la vue peche_db. req_7_1
DROP VIEW IF EXISTS `req_7_1`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_7_1`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_7_1` AS select date_format(`fiche_echantillonnage_capture`.`date`,'%Y') AS `annee`,
		date_format(`fiche_echantillonnage_capture`.`date`,'%c') AS `mois`,
		`region`.`nom` AS `nom_region`,
		`unite_peche`.`libelle` AS `libelle_unite_peche`,
		`espece`.`code` AS `Code3alpha`,sum(`espece_capture`.`capture`) AS `capture`,
		avg(`espece_capture`.`prix`) AS `average_prix`,
		`region`.`id` AS `id_region` 
		from (((((`unite_peche` 
				join `echantillon` on(`echantillon`.`id_unite_peche` = `unite_peche`.`id`)) 
				join `espece_capture` on(`espece_capture`.`id_echantillon` = `echantillon`.`id`)) 
				join `fiche_echantillonnage_capture` on(`fiche_echantillonnage_capture`.`id` = `echantillon`.`id_fiche_echantillonnage_capture`)) 
				join `espece` on(`espece`.`id` = `espece_capture`.`id_espece`)) 
				join `region` on(`region`.`id` = `fiche_echantillonnage_capture`.`id_region`)) 
		group by date_format(`fiche_echantillonnage_capture`.`date`,'%Y'),date_format(`fiche_echantillonnage_capture`.`date`,'%c'),`region`.`nom`,`unite_peche`.`libelle`,`espece`.`code` ;

-- Listage de la structure de la vue peche_db. req_7_2
DROP VIEW IF EXISTS `req_7_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_7_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_7_2` AS select `region`.`nom` AS `nom_region`,
			date_format(`fiche_echantillonnage_capture`.`date`,'%Y') AS `annee`,
			date_format(`fiche_echantillonnage_capture`.`date`,'%c') AS `mois`,
			`unite_peche`.`libelle` AS `libelle_unite_peche`,
			sum(`espece_capture`.`capture`) AS `capture`,
			`region`.`id` AS `id_region` 
		from (((((`unite_peche` 
				join `echantillon` on(`echantillon`.`id_unite_peche` = `unite_peche`.`id`)) 
				join `espece_capture` on(`espece_capture`.`id_echantillon` = `echantillon`.`id`)) 
				join `fiche_echantillonnage_capture` on(`fiche_echantillonnage_capture`.`id` = `echantillon`.`id_fiche_echantillonnage_capture`)) 
				join `espece` on(`espece`.`id` = `espece_capture`.`id_espece`)) 
				join `region` on(`region`.`id` = `fiche_echantillonnage_capture`.`id_region`)) 
			group by date_format(`fiche_echantillonnage_capture`.`date`,'%Y'),
						date_format(`fiche_echantillonnage_capture`.`date`,'%c'),
						`region`.`nom`,
						`unite_peche`.`libelle` ;

-- Listage de la structure de la vue peche_db. req_7_3
DROP VIEW IF EXISTS `req_7_3`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_7_3`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_7_3` AS select req71.annee AS annee,
			req71.mois AS mois,
			req71.nom_region AS nom_region,
			req71.libelle_unite_peche AS libelle_unite_peche,
			req71.Code3alpha AS Code3alpha,
			req71.capture AS capture,
			req72.capture AS totalcapture,
			req71.capture / req72.capture AS CompEspece,
			req71.average_prix AS average_prix,
			req71.id_region AS id_region 
			
			from req_7_1 AS req71 
					join req_7_2 req72 on(req71.libelle_unite_peche = req72.libelle_unite_peche and req71.nom_region = req72.nom_region and req71.mois = req72.mois and req71.annee = req72.annee) 
					where req72.capture > 0 ;

-- Listage de la structure de la vue peche_db. req_8_2
DROP VIEW IF EXISTS `req_8_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_8_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_8_2` AS select `req62`.`annee` AS `annee`,`req62`.`mois` AS `mois`,`req62`.`nom_region` AS `nom_region`,`req62`.`nom_site_embarquement` AS `nom_site_embarquement`,`req62`.`libelle_unite_peche` AS `libelle_unite_peche`,`req62`.`NbMonthlyFishingDaysPAB` AS `NbMonthlyFishingDaysPAB`,`req62`.`NbrTotalMonthlyFishingDays` AS `NbrTotalMonthlyFishingDays`,`req62`.`average_cpue` AS `average_cpue`,`req62`.`avgmaxpab` AS `avgmaxpab`,`req62`.`max_cpue` AS `max_cpue`,`req62`.`monthlyday` AS `monthlyday`,`req62`.`MaxCapturesTotale` AS `MaxCapturesTotale`,`req62`.`CLCaptureTotales` AS `CLCaptureTotales`,`req73`.`Code3alpha` AS `Code3alpha`,`req62`.`relErreurCPUE90` AS `relErreurCPUE90`,`req62`.`RelErrorCapuresTotales90` AS `RelErrorCapuresTotales90`,`req62`.`nbr_cpue` AS `nbr_cpue`,`req62`.`CapturesTotalest` AS `CapturesTotalest`,`req73`.`CompEspece` AS `CompEspece`,`req62`.`CapturesTotalest` * `req73`.`CompEspece` AS `Total_catch_specie`,`req73`.`average_prix` AS `average_prix`,`req62`.`CapturesTotalest` * `req73`.`CompEspece` * `req73`.`average_prix` AS `Value_specie` from (`req_6_2` `req62` join `req_7_3` `req73` on(`req62`.`annee` = `req73`.`annee` and `req62`.`mois` = `req73`.`mois` and `req62`.`nom_region` = `req73`.`nom_region` and `req62`.`libelle_unite_peche` = `req73`.`libelle_unite_peche`)) ;

-- Listage de la structure de la vue peche_db. req_9_2
DROP VIEW IF EXISTS `req_9_2`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_9_2`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_9_2` AS select `enquete_cadre`.`annee` AS `annee`,
		`site_embarquement`.`libelle` AS `nom_site_embarquement`,
		`unite_peche`.`libelle` AS `libelle_unite_peche`,
		sum(`enquete_cadre`.`nbr_unite_peche`) AS `nbr_unite_peche`,
		`region`.`id` AS `id_region` 
	
	from (((`enquete_cadre` 
			join `region` on(`region`.`id` = `enquete_cadre`.`id_region`)) 
			join `site_embarquement` on(`site_embarquement`.`id` = `enquete_cadre`.`id_site_embarquement`)) 
			join `unite_peche` on(`unite_peche`.`id` = `enquete_cadre`.`id_unite_peche`)) 
			group by `enquete_cadre`.`annee`,`site_embarquement`.`libelle`,`unite_peche`.`libelle` ;

-- Listage de la structure de la vue peche_db. req_9_3
DROP VIEW IF EXISTS `req_9_3`;
-- Suppression de la table temporaire et création finale de la structure d'une vue
DROP TABLE IF EXISTS `req_9_3`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `req_9_3` AS select `enquete_cadre`.`annee` AS `annee`,
			`region`.`nom` AS `nom_region`,
			`site_embarquement`.`libelle` AS `nom_site_embarquement`,
			`unite_peche`.`libelle` AS `libelle_unite_peche`,
			sum(`enquete_cadre`.`nbr_unite_peche`) AS `nbr_unite_peche`,
			`region`.`id` AS `id_region` 
			
		from (((`enquete_cadre` 
				join `region` on(`region`.`id` = `enquete_cadre`.`id_region`)) 
				join `site_embarquement` on(`site_embarquement`.`id` = `enquete_cadre`.`id_site_embarquement`)) 
				join `unite_peche` on(`unite_peche`.`id` = `enquete_cadre`.`id_unite_peche`)) 
			group by `enquete_cadre`.`annee`,`region`.`nom`,`unite_peche`.`libelle` ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
