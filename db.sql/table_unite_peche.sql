-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           10.1.13-MariaDB - mariadb.org binary distribution
-- SE du serveur:                Win32
-- HeidiSQL Version:             10.0.0.5460
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Listage de la structure de la table peche_db. unite_peche
CREATE TABLE IF NOT EXISTS `unite_peche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type_canoe` int(11) NOT NULL DEFAULT '0',
  `id_type_engin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_unite_peche_type_canoe` (`id_type_canoe`),
  KEY `FK_unite_peche_type_engin` (`id_type_engin`),
  CONSTRAINT `FK_unite_peche_type_canoe` FOREIGN KEY (`id_type_canoe`) REFERENCES `type_canoe` (`id`),
  CONSTRAINT `FK_unite_peche_type_engin` FOREIGN KEY (`id_type_engin`) REFERENCES `type_engin` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Les données exportées n'étaient pas sélectionnées.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
