-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.37 - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных utip
CREATE DATABASE IF NOT EXISTS `utip` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `utip`;

-- Дамп структуры для таблица utip.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `descr` text,
  `hash` varchar(32) DEFAULT NULL,
  `ext` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы utip.files: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Дамп структуры для таблица utip.migration
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы utip.migration: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` (`version`, `apply_time`) VALUES
	('m000000_000000_base', 1563160122),
	('m190714_225738_create_users_table', 1563160125),
	('m190714_231003_create_files_table', 1563160125),
	('m190714_231813_create_junction_table_for_users_and_files_tables', 1563160126);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;

-- Дамп структуры для таблица utip.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы utip.users: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`) VALUES
	(1, 'admin', '$2y$13$tpJkifJ67rfbbE2sDaO0eOtX5Wu/t//AD9q7EHGKneRpTMx0RFwNS');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для таблица utip.users_files
CREATE TABLE IF NOT EXISTS `users_files` (
  `users_id` int(11) NOT NULL DEFAULT '0',
  `files_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`users_id`,`files_id`),
  KEY `idx-users_files-users_id` (`users_id`),
  KEY `idx-users_files-files_id` (`files_id`),
  CONSTRAINT `fk-users_files-files_id` FOREIGN KEY (`files_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-users_files-users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы utip.users_files: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_files` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
