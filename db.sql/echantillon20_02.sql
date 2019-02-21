-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.7.21 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de la structure de la table peche_db. echantillon
DROP TABLE IF EXISTS `echantillon`;
CREATE TABLE IF NOT EXISTS `echantillon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_fiche_echantillonnage_capture` int(11) NOT NULL,
  `id_type_canoe` int(11) NOT NULL,
  `id_type_engin` int(11) NOT NULL,
  `peche_hier` int(1) DEFAULT NULL,
  `peche_avant_hier` int(1) DEFAULT NULL,
  `nbr_jrs_peche_dernier_sem` int(3) NOT NULL DEFAULT '0',
  `total_capture` double DEFAULT NULL,
  `unique_code` varchar(50) NOT NULL,
  `id_data_collect` int(11) NOT NULL,
  `nbr_bateau_actif` int(11) NOT NULL DEFAULT '0',
  `total_bateau_ecn` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_modification` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 7` (`unique_code`),
  KEY `FK_echantillon_type_canoe` (`id_type_canoe`),
  KEY `FK_echantillon_type_engin` (`id_type_engin`),
  KEY `FK_echantillon_fiche_echantillonnage_capture` (`id_fiche_echantillonnage_capture`),
  KEY `FK_echantillon_data_collect` (`id_data_collect`),
  KEY `FK_echantillon_utilisateur` (`id_user`),
  CONSTRAINT `FK_echantillon_data_collect` FOREIGN KEY (`id_data_collect`) REFERENCES `data_collect` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_echantillon_fiche_echantillonnage_capture` FOREIGN KEY (`id_fiche_echantillonnage_capture`) REFERENCES `fiche_echantillonnage_capture` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_echantillon_type_canoe` FOREIGN KEY (`id_type_canoe`) REFERENCES `type_canoe` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_echantillon_type_engin` FOREIGN KEY (`id_type_engin`) REFERENCES `type_engin` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_echantillon_utilisateur` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Export de données de la table peche_db.echantillon : ~4 rows (environ)
/*!40000 ALTER TABLE `echantillon` DISABLE KEYS */;
INSERT INTO `echantillon` (`id`, `id_fiche_echantillonnage_capture`, `id_type_canoe`, `id_type_engin`, `peche_hier`, `peche_avant_hier`, `nbr_jrs_peche_dernier_sem`, `total_capture`, `unique_code`, `id_data_collect`, `nbr_bateau_actif`, `total_bateau_ecn`, `id_user`, `date_creation`, `date_modification`) VALUES
	(1, 1, 1, 1, 0, 0, 0, 3, '1', 3, 1, 1, 66, '2019-02-13 10:46:50', '2019-02-19 14:26:49'),
	(2, 1, 2, 2, 1, 3, 4, 4, '2', 1, 0, 0, 66, '2019-02-13 10:47:34', '2019-02-19 10:20:59'),
	(13, 1, 3, 4, 1, 1, 2, 0, '3', 1, 0, 0, 66, '2019-02-19 10:48:57', '2019-02-19 10:48:57'),
	(14, 1, 1, 4, 0, 0, 0, 0, '6', 3, 1, 1, 66, '2019-02-19 14:11:14', '2019-02-19 15:04:39');
/*!40000 ALTER TABLE `echantillon` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
