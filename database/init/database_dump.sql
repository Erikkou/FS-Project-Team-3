/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mydatabase
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `home_team` int(11) NOT NULL,
  `starting_at` date NOT NULL,
  `away_team` int(11) NOT NULL,
  `stadium_id` int(11) NOT NULL,
  `round_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar`
--

LOCK TABLES `calendar` WRITE;
/*!40000 ALTER TABLE `calendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250126205755','2025-01-26 21:58:37',414),
('DoctrineMigrations\\Version20250126210235','2025-01-26 22:03:08',313);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `starting_at` varchar(255) NOT NULL,
  `end_result_info` varchar(255) NOT NULL,
  `stage_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `jersey_number` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `detailed_position_id` int(11) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_98197A65296CD8AE` (`team_id`),
  CONSTRAINT `FK_98197A65296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player`
--

LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` VALUES
(323,629,'Jordan Brian Henderson','Jordan Henderson',6,26,153,''),
(712,919,'Patrick van Aanholt','Patrick van Aanholt',5,25,155,''),
(871,2345,'Leandro Bacuna','Leandro Bacuna',7,25,NULL,''),
(975,593,'Ricky van Wolfswinkel','R. van Wolfswinkel',9,27,151,''),
(986,919,'Jonathan de Guzmán','Jonathan de Guzmán',NULL,26,149,''),
(1646,750,'Mike van der Hoorn','Mike van der Hoorn',3,25,148,''),
(1764,61,'Jordy Clasie','Jordy Clasie',8,26,149,''),
(1913,629,'Chuba Akpom','Chuba Akpom',10,27,151,''),
(2946,1128,'Lasse Vigen Christensen','Lasse Vigen Christensen',21,26,153,''),
(3073,1459,'Ivo Daniel Ferreira Mendonca Pinto','Ivo Pinto',12,25,154,''),
(3383,1435,'Maikel Kieftenbeld','Maikel Kieftenbeld',8,26,149,''),
(4095,94,'Alexander Büttner','Alexander Büttner',28,25,155,''),
(4230,629,'Steven Berghuis','Steven Berghuis',23,27,156,''),
(4254,629,'Bertrand Isidore Traoré','Bertrand Traoré',20,27,156,''),
(4459,682,'Luuk de Jong','Luuk de Jong',9,27,151,''),
(4813,61,'Bruno Martins Indi','Bruno Martins Indi',4,25,148,''),
(8039,1459,'Mitchell Dijks','Mitchell Dijks',35,25,155,''),
(9084,1459,'Kristoffer Peterson','Kristoffer Peterson',7,27,152,''),
(9282,2345,'Kevin van Veen','Kevin van Veen',NULL,27,151,''),
(23006,593,'Przemysław Tytoń','P. Tytoń',22,24,24,''),
(23056,750,'Jens Toornstra','Jens Toornstra',18,26,150,''),
(23079,1652,'Mike van Duinen','Mike van Duinen',29,27,151,''),
(23232,1403,'Kelvin Leerdam','Kelvin Leerdam',18,25,154,''),
(23416,494,'Lasse Schöne','Lasse Schöne ',20,26,149,''),
(23431,629,'Remko Pasveer','Remko Pasveer',22,24,24,''),
(23563,1403,'Thomas Bruns','Thomas Bruns',17,26,150,''),
(23634,2344,'Nick Marsman','Nick Marsman',90,24,NULL,''),
(23657,494,'Bryan Linssen','Bryan Linssen',30,27,151,''),
(23671,494,'Bram Nuytinck','Bram Nuytinck',17,25,148,''),
(23811,814,'Aaron Meijers','Aaron Meijers ',28,25,155,''),
(24032,750,'Nick Viergever','Nick Viergever',24,25,148,''),
(24050,1016,'Kenneth Vermeer','K. Vermeer',25,24,24,''),
(24082,61,'Hobie Verhulst','Hobie Verhulst',12,24,24,''),
(24147,61,'Jeroen Zoet','Jeroen Zoet',41,24,24,''),
(24200,2345,'Joey Pelupessy','Joey Pelupessy',4,26,149,''),
(24241,1435,'Michael de Leeuw','Michael de Leeuw',19,27,151,''),
(24258,2344,'Nils Röseler','Nils Röseler',13,25,148,''),
(24328,637,'Kostas Lamprou','Kostas Lamprou',31,24,24,''),
(24347,1128,'Alex Schalk','Alex Schalk',10,27,151,''),
(24373,1128,'Tim Coremans','Tim Coremans',28,24,24,''),
(24508,637,'Terence Kongolo','Terence Kongolo',23,25,148,''),
(24594,629,'Davy Klaassen','Davy Klaassen',18,26,153,''),
(24602,1459,'Luuk Koopmans','Luuk Koopmans',1,24,24,''),
(24676,1652,'Jurgen Mattheij','Jurgen Matheij',NULL,25,148,''),
(24695,1459,'Mattijs Branderhorst','Mattijs Branderhorst',31,24,24,''),
(24705,1128,'Daryl van Mieghem','Daryl van Mieghem',7,27,156,''),
(24714,1433,'Thom Haye','Thom Haye',19,26,149,''),
(24761,2345,'Mats Seuntjens','Mats Seuntjens',20,26,NULL,''),
(24825,814,'Michiel Kramer','Michiel Kramer',29,27,151,''),
(24836,1073,'Lion Kaak','Lion Kaak',6,26,149,''),
(24891,1053,'Mickey van der Hart','Mickey van der Hart',13,24,24,''),
(24925,1073,'Joshua Smits','Joshua Smits',16,24,24,''),
(25123,1016,'Younes Namli','Y. Namli',7,26,156,''),
(25144,682,'Rick Karsdorp','Rick Karsdorp',2,25,154,''),
(25147,1053,'Alireza Jahanbakhsh','Alireza Jahanbakhsh',30,27,156,''),
(25161,1016,'Ryan Thomas','R. Thomas',30,26,153,''),
(25201,1435,'Mark Diemers','Mark Diemers',12,26,150,''),
(25240,1073,'Jeffry Fortes','Jeffrey Fortes',22,25,154,''),
(25241,2345,'Marvin Peersman','Marvin Peersman',43,25,148,''),
(25317,629,'Wout Weghorst','Wout Weghorst',25,27,NULL,''),
(25414,593,'Bas Edo Kuipers','B. Kuipers',5,25,155,''),
(25421,814,'Jeroen Houwen','Jeroen Houwen',1,24,24,''),
(25437,1073,'Mimoun Mahi','Mimoun Mahi',10,27,152,''),
(25473,1433,'Tim Receveur','Tim Receveur',28,26,149,''),
(25482,919,'Nick Olij','N. Olij',NULL,24,24,''),
(25490,1403,'Justin Hoogma','Justin Hoogma',21,25,148,''),
(25499,750,'Sébastien Haller','Sébastien Haller',91,27,151,''),
(25501,669,'Patrick Joosten','Patrick Joosten',17,27,152,''),
(25520,750,'Michael Brouwer','Michael Brouwer',25,24,24,''),
(25555,1016,'Mike Hauptmeijer','M. Hauptmeijer',40,24,24,''),
(25582,637,'Clint Leemans','Clint Leemans',8,26,153,''),
(25602,814,'Julian Lelieveld','Julian Lelieveld',2,25,154,''),
(25609,629,'Branco van den Boomen','Branco van den Boomen',21,26,153,''),
(25622,1073,'Youssef El Jebli','Youssef El Jebli',47,26,150,''),
(25629,2345,'Hidde Jurjus','Hidde Jurjus',21,24,24,''),
(25662,682,'Joël Drommel','Joël Drommel',16,24,24,''),
(25663,1459,'Alessio da Cruz','Alessio Da Cruz',23,27,151,''),
(25683,1053,'Jordy de Wijs','Jordy de Wijs',31,25,148,''),
(25734,494,'Calvin Verdonk','Calvin Verdonk',24,25,155,''),
(25739,494,'Philippe Sandler','Philippe Sandler ',3,25,148,''),
(25772,2344,'Orhan Džepar','Orhan Dzepar',17,26,153,''),
(25826,593,'Mitchell van Bergen','M. van Bergen',7,27,156,''),
(25878,822,'Jari Schuurman','Jari Schuurman',10,26,150,''),
(25945,73,'Justin Bijlow','Justin Bijlow',1,24,24,''),
(25947,73,'Bart Nieuwkoop','Bart Nieuwkoop',2,25,154,''),
(26015,682,'Guus Til','G. Til',20,26,NULL,''),
(26022,593,'Michel Vlap','M. Vlap',18,26,150,''),
(26034,637,'Roy Kortsmit','Roy Kortsmit',1,24,24,''),
(26073,1016,'Sherel Floranus','S. Floranus',2,25,154,''),
(26082,664,'Finn Stokkers','Finn Stokkers',27,27,151,''),
(26090,1435,'Ilias Alhaft','Ilias Alhaft',11,27,156,''),
(26208,814,'Reuven Niemeijer','Reuven Niemeijer',10,26,150,''),
(26256,669,'Jeremy Loteteka Bokila','Jeremy Bokila',18,27,151,''),
(26370,494,'Thomas Ouwejan','Thomas Ouwejan',5,25,155,''),
(26415,1053,'Andries Noppert','Andries Noppert',44,24,24,''),
(26433,1016,'Jamiro Gregory Monteiro Alvarenga','Jamiro Monteiro',35,26,153,''),
(26517,1053,'Jan Bekkema','Jan Bekkema',23,24,24,''),
(26550,1652,'Kik Pierie','Kik Pierie',3,25,148,''),
(26575,1652,'Django Warmerdam','Django Warmerdam',4,25,148,''),
(26579,593,'Sam Lammers','S. Lammers',10,27,151,''),
(26620,629,'Owen Wijndal','Owen Wijndal',5,25,155,''),
(26635,73,'Calvin Stengs','Calvin Stengs',10,26,150,''),
(26658,814,'Yanick van Osch','Yanick van Osch ',21,24,24,''),
(26747,919,'Pelle Clement','Pelle Clement',NULL,26,153,''),
(26786,682,'Jerdy Schouten','Jerdy Schouten',22,26,149,''),
(26801,2344,'Keziah Veendorp','Keziah Veendorp',34,26,149,''),
(26815,1073,'Ralf Seuntjens','Ralf Seuntjens',26,27,151,''),
(26865,814,'Oskar Zawada','Oskar Zawada ',9,27,151,''),
(26877,664,'Joris Kramer','Joris Kramer',4,25,148,''),
(26944,593,'Carel Eiting','C. Eiting',6,26,153,''),
(26970,1016,'Odysseus Velanas','O. Velanas',18,26,150,''),
(26991,1016,'Dylan Vente','D. Vente',9,27,NULL,''),
(27291,2344,'Thomas Oude Kotte','Thomas Oude Kotte',3,25,148,''),
(27750,814,'Alexander Jakobsen','Alexander Jakobsen',11,27,152,''),
(27757,669,'Erik Schouten','Erik Schouten',4,25,148,''),
(27889,1128,'Kürşad Sürmeli','Kürşad Sürmeli',6,26,149,''),
(28009,1403,'Damon Mirani','Damon Mirani',4,25,148,''),
(28049,1433,'James Lawrence','James Lawrence',15,25,148,''),
(28113,1433,'Damil Dankerlui','Damil Dankerlui',2,25,154,''),
(28199,494,'Stijn van Gassel','Stijn van Gassel',1,24,24,''),
(28212,494,'Vito van Crooij','Vito van Crooij',32,27,156,''),
(28354,814,'Dario Van den Buijs','Dario Van den Buijs',3,25,148,''),
(28418,814,'Richard van der Venne','Richard van der Venne',23,26,150,''),
(28448,1403,'Nikolai Laursen','Nikolai Laursen',11,27,156,''),
(28544,2345,'Etienne Vaessen','Etienne Vaessen',1,24,24,''),
(28674,814,'Silvester van der Water','Silvester van der Water',18,27,156,''),
(28731,1053,'Luuk Brouwers','Luuk Brouwers',8,26,150,''),
(28918,637,'Boyd Lucassen','Boyd Lucassen',2,25,154,''),
(29106,1053,'Sam Kersten','Sam Kersten',3,25,148,''),
(29156,94,'Justin Bakker','Justin Bakker',5,25,148,''),
(29182,2344,'Koen Bucker','Koen Bucker',1,24,24,''),
(29205,669,'Nick Doodeman','Nick Doodeman',7,27,156,''),
(29209,919,'Arno Verschueren','Arno Verschueren',NULL,26,150,''),
(29282,1016,'Thomas Buitink','T. Buitink',29,27,151,''),
(29285,814,'Patrick Vroegh','Patrick Vroegh',8,26,153,''),
(29394,682,'Joey Veerman','Joey Veerman',23,26,153,''),
(29401,1403,'Jordy Bruijn','Jordy Bruijn',5,26,150,''),
(29444,682,'Armando Obispo','Armando Obispo',4,25,148,''),
(29460,1073,'Anass Najah','Anass Najah',27,26,149,''),
(29484,494,'Mees Hoedemakers','Mees Hoedemakers',6,26,149,''),
(30066,593,'Michal Sadílek','M. Sadílek',23,26,153,''),
(30107,664,'Mats Deijl','Mats Deijl',2,25,154,''),
(30291,1459,'Kaj Sierhuis','Kaj Sierhuis',9,27,151,''),
(30618,1053,'Che Nunnely','Ché Nunnely',7,27,156,''),
(30782,814,'Godfried Roemeratoe','Godfried Roemeratoe',24,26,149,''),
(31002,682,'Ivan Perišić','I. Perišić',5,27,152,''),
(31161,593,'Lars Unnerstall','L. Unnerstall',1,24,24,''),
(31439,682,'Noa Lang','Noa Lang',10,27,NULL,''),
(31511,1403,'Fabian de Keijzer','Fabian de Keijzer',1,24,24,''),
(32493,1459,'Alen Halilović','Alen Halilović',10,26,150,''),
(32811,73,'Timon Wellenreuther','Timon Wellenreuther',22,24,24,''),
(34621,1403,'Mario Engels','Mario Engels',8,27,152,''),
(35971,2344,'Brian Koglin','Brian Koglin',4,25,148,''),
(38203,664,'Gerrit Nauber','Gerrit Nauber',3,25,148,''),
(41219,1016,'Braydon Marvin Manu','B. Manu',77,27,156,''),
(41288,1053,'Amara Condé','Amara Condé',6,26,153,''),
(42862,1053,'Mats Köhlert','Mats Köhlert',11,25,155,''),
(52302,73,'Gernot Trauner','Gernot Trauner',18,25,148,''),
(62179,1433,'Anas Tahiri','Anas Tahiri',8,26,153,''),
(62648,669,'Mickaël Tirpan','Mickael Tirpan',25,25,154,''),
(62821,1459,'Samuel Bastien','Samuel Bastien',22,26,153,''),
(63074,750,'Siebe Horemans','Siebe Horemans',2,25,154,''),
(65145,637,'Jan Van den Bergh','Jan Van den Bergh',5,25,148,''),
(65152,669,'Thomas Didillon','Thomas Didillon',1,24,24,''),
(65655,822,'Liam Bossin','Liam Bossin',1,24,24,''),
(74186,73,'Luka Ivanušec','Luka Ivanusec',17,27,152,''),
(92767,1128,'Diogo Tomas','Diogo Tomas',45,25,148,''),
(96288,1433,'Baptiste Guillaume','Baptiste Guillaume',21,27,151,''),
(96513,682,'Olivier Boscagli','Olivier Boscagli',18,NULL,148,''),
(97383,682,'Walter Daniel Benítez','Walter Benítez',1,24,24,''),
(98885,1433,'Thomas Robinet','Thomas Robinet',9,27,151,''),
(108928,750,'Vasileios Barkas','Vasilis Barkas',1,24,24,''),
(118490,637,'Elías Már Ómarsson','Elías Már Ómarsson',10,27,151,''),
(118811,750,'Kolbeinn Birgir Finnsson','K. Finnsson',5,25,155,''),
(129624,629,'Daniele Rugani','Daniele Rugani',24,25,148,''),
(132636,1053,'Paweł Bochniewicz','Paweł Bochniewicz',5,25,148,''),
(140824,1433,'Marvin Martins Santos Da Graca','Marvin Martins',34,25,148,''),
(140973,637,'Enes Mahmutovic','Enes Mahmutovic',15,25,148,''),
(151003,637,'Fredrik Oldrup Jensen','Fredrik Jensen',20,26,149,''),
(151749,2345,'Johan Hove','Johan Hove',8,26,153,''),
(181086,637,'Martin Koscelník','Martin Koscelník',3,25,154,''),
(181449,73,'Dávid Hancko','Dávid Hancko',33,25,148,''),
(187162,494,'Iván Márquez Álvarez','Iván Márquez',4,25,148,''),
(188096,1433,'Alejandro Carbonell Vallés','Àlex Carbonell',6,26,153,''),
(192278,1053,'Simon Olsson','S. Olsson',19,26,NULL,''),
(193908,664,'Victor Edvardsen','Victor Kaj Edvardsen',16,27,151,''),
(197007,1433,'Adi Nalic','Adi Nalić',16,26,150,''),
(198116,73,'Jordan Lotomba','Jordan Lotomba ',30,25,154,''),
(253463,682,'Hirving Rodrigo Lozano Bahena','Hirving Lozano',27,27,NULL,''),
(312118,494,'Koki Ogawa','Koki Ogawa',18,27,151,''),
(321093,73,'In-Beom Hwang','In-beom Hwang',4,26,153,''),
(348090,669,'Raffael Behounek','Raffael Behounek',30,25,148,''),
(418424,2345,'Marco Rente','Marco Rente',5,25,148,''),
(445664,637,'Manel Royo Castell','Manel Royo',21,25,155,''),
(455798,494,'Lars Olden Larsen','Lars Olden Larsen ',NULL,27,156,''),
(529555,1459,'Loreintz Rosier','Rosier Loreintz',32,26,149,''),
(537292,2344,'Rodney Kongolo','Rodney Kongolo',21,26,153,''),
(537313,637,'Boy Kemper','Boy Kemper',4,25,155,''),
(537691,61,'Mees De Wit','Mees de Wit',34,25,155,''),
(537916,750,'Victor Jensen','Victor Jensen',7,26,150,''),
(537935,750,'Niclas Vesterlund','Niklas Vesterlund',23,25,154,''),
(538122,494,'Eleutherios Lyratzis','Lefteris Lyratzis',19,25,154,''),
(540711,629,'Josip Šutalo','J. Šutalo',37,25,148,''),
(581049,1435,'Jonathan Afolabi','Jonathan Afolabi',9,27,151,''),
(608110,664,'Jari De Busser','Jari De Busser',22,24,24,''),
(766685,637,'Kacper Kostorz','Kacper Kostorz',9,27,151,''),
(1452363,682,'Mauro Jaqueson Júnior Ferreira dos Santos','Mauro Júnior',17,25,155,''),
(1478149,1435,'Nicky Souren','Nicky Souren',28,26,NULL,''),
(1478336,664,'Sven Jansen','Sven Jansen',30,24,24,''),
(1478876,1016,'Jasper Schendelaar','J. Schendelaar',1,24,24,''),
(1483521,814,'Nourdin El Harmazi','Nouri El Harmazi',15,26,150,''),
(1491254,1459,'Darijo Grujcic','Darijo Grujčić',5,25,148,''),
(1494368,2344,'Nathangelo Markelo','Nathan Markelo',24,25,154,''),
(1525384,1053,'Ion Nicolăescu','I. Nicolăescu',18,27,151,''),
(1873079,669,'Rúnar Thór Sigurgeirsson','Rúnar Þór Sigurgeirsson',5,25,NULL,''),
(2178571,637,'Dominik Janosek','Dominik Janošek',39,26,150,''),
(2503729,73,'Santiago Tomás Giménez','Santiago Gimenez',29,27,151,''),
(2510401,822,'Rene Kriwak','Rene Kriwak',29,27,151,''),
(2875857,814,'Juan Familio-Castillo','Juan Familia-Castillo ',5,25,155,''),
(3180873,919,'Saïd Bakari','Saïd Bakari',NULL,25,154,''),
(3187727,664,'Luca Plogmann','Luca Plogmann',1,24,24,''),
(3510293,494,'Roberto González Bayón','Rober González',7,27,156,''),
(3526194,73,'Julián Carranza','Julián Carranza ',19,27,151,''),
(3533334,593,'Gustaf Lagerbielke','G. Lagerbielke',3,25,NULL,''),
(3568482,1652,'Ilias Bronkhorst','Ilias Bronkhorst',2,25,154,''),
(3568504,814,'Kevin Felida','Kevin Felida',35,26,149,''),
(3861889,2345,'Rui-Jorge Monteiro Mendes','Rui Mendes',27,27,156,''),
(3861893,2344,'Joey Paul Müller','Joey Muller',8,25,155,''),
(3872676,1403,'Robin Mantel','Robin Mantel',30,24,24,''),
(4187845,61,'Kristijan Belic','Kristijan Belić',14,26,149,''),
(4536485,1459,'Umaro Embaló','Úmaro Embaló',85,27,156,''),
(4536591,1652,'Arthur Zagre','Arthur Zagre',12,25,155,''),
(4545371,919,'Boyd Reith','Boyd Reith',NULL,25,154,''),
(4545373,1435,'Jeremy van Mullem','Jeremy Van Mullem',6,26,149,''),
(4598845,2344,'Issam El Maach','I. El Maach',52,24,24,''),
(4926032,1403,'Jannes Luca Wieckhoff','Jannes Wieckhoff',3,25,154,''),
(4977500,94,'Tomislav Gudelj','Tomislav Gudelj',98,27,151,''),
(4977576,73,'Gijs Smal','Gijs Smal',5,25,155,''),
(5270464,1435,'Daan Reiziger','Daan Reiziger',22,24,24,''),
(5270467,814,'Liam van Gelderen','Liam van Gelderen',4,25,154,''),
(5283769,2345,'Brynjolfur Darri Willumsson','B. Willumsson',9,27,NULL,''),
(5629269,1433,'Kornelius Normann Hansen','Kornelius Hansen',17,27,151,''),
(5640853,61,'Ibrahim Sadiq','Ibrahim Sadiq',11,27,156,''),
(5640878,682,'Sergino Dest','Sergiño Dest',8,25,154,''),
(5640882,750,'Taylor Booth','Taylor Booth',10,27,156,''),
(5641040,1459,'Rodrigo Guth','Rodrigo Guth',14,25,148,''),
(5967747,1433,'Théo Barbet','Theo Barbet',22,25,148,''),
(6306066,814,'Richonell Margaret','Richonell Margaret',19,27,151,''),
(6306068,73,'Quinten Maduro','Quinten Timber ',8,26,153,''),
(6599943,1403,'Suf Podgoreanu','Suf Podgoreanu',29,27,152,''),
(6599976,1016,'Thierry Lutonda','T. Lutonda',5,25,155,''),
(6599977,664,'Mathis Suray','Mathis Suray',17,26,150,''),
(6972292,919,'Tobias Lauritsen','Tobias Lauritsen',NULL,27,151,''),
(7330507,629,'Brian Brobbey','B. Brobbey',9,27,151,''),
(7345851,669,'Tommy St. Jago','Tommy St. Jago',33,25,148,''),
(8055883,669,'Valentino Vermeulen','Valentino Vermeulen',20,25,154,''),
(8056316,919,'Joshua Gaston Kitolano','Joshua Kitolano',NULL,26,153,''),
(8403174,2344,'Enrique Manuel Peña Zauner','Enrique Peña Zauner',7,27,152,''),
(8409938,822,'Celton Aussumone Biai','Celton Biai',63,24,24,''),
(8449358,1433,'Jonas Wendlinger','Jonas Wendlinger',29,24,NULL,''),
(9307980,1403,'Jizz Hornkamp','Jizz Hornkamp',9,27,151,''),
(9939028,1403,'Ivan Mesík','Ivan Mesík',24,25,NULL,''),
(9939195,94,'Nordin Musampa','Nordin Musampa',NULL,25,148,''),
(9939199,94,'Mees Kreekels','Mees Kreekels',2,25,154,''),
(9939202,664,'Enric Llansana','Enric Llansana',21,26,149,''),
(9939204,1073,'Arjen van der Heide','Arjen Van Der Heide',28,27,156,''),
(10294211,1053,'Danilo Al-Saed','Danilo Al-Saed',24,27,152,''),
(10966288,61,'Peer Koopmeiners','Peer Koopmeiners',6,26,149,''),
(12433117,1016,'Simon Graves Jensen','S. Graves',28,25,148,''),
(12433124,1433,'Charles-Andreas Brym','Charles-Andreas Brym',32,27,151,''),
(12789336,1016,'Kaj de Rooij','K. de Rooij',22,27,156,''),
(12790692,814,'Roshon van Eijma','Roshon van Eijma',17,25,148,''),
(13992050,750,'Matisse Didden','Matisse Didden',40,25,148,''),
(14685017,2345,'Thijs Oosting','Thijs Oosting',25,26,150,''),
(14685018,1433,'Joey Jacobs','Joey Jacobs',3,25,148,''),
(14709213,1128,'Sekou Sylla','Sekou Sylla',5,25,155,''),
(15061065,1433,'Christopher Mamengi','Christopher Mamengi',25,25,NULL,''),
(15076414,1073,'Seth Saarinen','Seth Saarinen',NULL,25,154,''),
(15169845,664,'Oliver Valaker Edvardsen','Oliver Valaker Edvardsen',23,27,152,''),
(15746438,822,'Léo Seydoux','Léo Seydoux',16,25,154,''),
(15756157,664,'Evert Linthorst','Evert Linthorst',8,26,153,''),
(15763847,1128,'Jari Vlak','Jari Vlak',8,26,153,''),
(16136386,664,'Oliver Antman','O. Antman',19,27,156,''),
(16136410,1128,'Taneli Hämäläinen','Taneli Hämäläinen',NULL,25,148,''),
(16475806,814,'Mohammed Amine Ihattaren','Mohamed Ihattaren',52,26,150,''),
(16496670,1433,'Faiz Mattoir','Faiz Mattoir',24,27,152,''),
(16817566,682,'Richard Ledezma','Richard Ledezma',37,25,154,''),
(16838270,1016,'Filip Yavorov Krastev','F. Krastev',50,26,153,''),
(17158410,1073,'Simon Colyn','Simon Colyn',11,26,150,''),
(17187713,669,'Lambert Boris','Boris Lambert',6,26,149,''),
(17867667,919,'Mike Eerdhuijzen','Mike Eerdhuijzen',NULL,25,148,''),
(19970146,1459,'Josip Mitrović','Josip Mitrović',28,27,156,''),
(19970205,637,'Daniel Bielica','Daniel Bielica',99,24,24,''),
(19980285,637,'Sydney van Hooijdonk','Sydney van Hooijdonk',29,27,151,''),
(20357597,593,'Bart van Rooij','B. van Rooij',28,25,154,''),
(20357599,1652,'Lance Duijvestijn','Lance Duijvestijn',10,26,150,''),
(21036703,637,'Leo Greiml','Leo Greiml',12,25,148,''),
(21054226,637,'Maxime Busi','Maxime Busi',44,25,154,''),
(21394902,593,'Sem Steijn','S. Steijn',14,26,150,''),
(21762190,750,'Can Bozdogan','Can Bozdoğan',8,26,153,''),
(21762228,1403,'Sava-Arangel Cestic','Sava Arangel Čestić',6,25,148,''),
(21772823,1433,'Nordin Bakker','Nordin Bakker',1,24,24,''),
(21773355,73,'Ayase Ueda','Ayase Ueda ',9,27,151,''),
(22126605,919,'Teo Quintero','Teo Quintero',NULL,25,148,''),
(22136572,94,'Angelos Tsigaras','Angelos Tsingaras',37,26,149,''),
(22136638,61,'Sven Mijnans','Sven Mijnans',10,26,150,''),
(22136781,1433,'Stijn Keller','Stijn Keller',26,24,NULL,''),
(23269665,1433,'Ruben Providence','Ruben Providence',7,27,152,''),
(23277869,682,'Malik Tillman','Malik Tillman',7,26,150,''),
(23277907,61,'Troy Parrott','T. Parrott',9,27,151,''),
(23278580,494,'Sontje Hansen','Sontje Hansen',10,27,NULL,''),
(23278611,61,'Alexandre Manuel Penetra Correia','Alexandre Penetra',5,25,148,''),
(23287280,822,'Rocco Robert Shein','R. Shein',21,26,153,''),
(23651573,1403,'Daniël van Kaam','Daniel van Kaam',26,26,149,''),
(23661344,1652,'Calvin Raatsie','Calvin Raatsie',40,24,24,''),
(23661347,593,'Anass Salah-Eddine','A. Salah-Eddine',34,25,NULL,''),
(23661349,1128,'Steven van der Sloot','Steven Van Der Sloot',2,25,154,''),
(23661350,1459,'Syb van Ottele','Syb van Ottele',6,25,148,''),
(23661356,494,'Dirk Proper','Dirk Proper ',71,26,149,''),
(23697482,2344,'Thibo Baeten','Thibo Baeten',97,27,NULL,''),
(23697484,1652,'Xander Blomme','Xander Blomme',6,26,149,''),
(23697508,1016,'Dave van den Berg','D. van den Berg',10,26,153,''),
(23697509,593,'Mathias Ullereng Kjølø','M. Kjølø',4,26,149,''),
(23715953,1053,'Hussein Ali','Hussein Ali',15,25,154,''),
(24092545,629,'Kenneth Taylor','Kenneth Taylor',8,26,NULL,''),
(24446234,1459,'Ezequiel Eduardo Bullaude','Ezequiel Bullaude',33,26,150,''),
(24446405,1016,'Eliano Reijnders','E. Reijnders',23,26,154,''),
(24468972,750,'Miguel Rodríguez Vidal','Miguel Rodríguez',22,27,156,''),
(24469059,637,'Matthew Garbett','Matthew Garbett',7,26,153,''),
(24816995,682,'Fredrik Oppegard','Fredrik Oppegård',35,25,NULL,''),
(24817074,1128,'Juho Kilo','Juho Kilo',25,26,153,''),
(24817135,669,'Rob Nizet','Rob Nizet',22,25,155,''),
(24818025,1053,'Hristiyan Petrov','H. Petrov',28,25,148,''),
(26336992,94,'Dillion Hoogerwerf','Dillon Hoogerwerf',11,27,156,''),
(27065896,919,'Djevencio van der Kust','Djevencio van der Kust',NULL,25,155,''),
(27065898,750,'David Min','David Min',9,27,151,''),
(27066535,2344,'Tim Köther','Tim Kother',18,25,155,''),
(27067136,629,'Diant Ramaj','Diant Ramaj',40,24,24,''),
(28543549,1433,'Junior Morau Kadile','Junior Kadile',11,27,156,''),
(28571312,1403,'Jan Žambůrek','Jan Žambůrek',13,26,153,''),
(28912766,750,'Noah Ohio','Noah Ohio',11,27,151,''),
(28931566,1073,'Rio Hillen','Rio Hillen',20,25,148,''),
(28931567,1435,'Fedde De Jong','Fedde de Jong',10,26,153,''),
(28931568,629,'Kian Fitz-Jim','Kian Fitz-Jim',28,26,NULL,''),
(28952172,814,'Joey Kesting','Joey Kesting',13,24,24,''),
(28952175,1016,'Anouar El Azzouzi','A. El Azzouzi',6,25,149,''),
(29313813,1459,'Jasper Dahlhaus','Jasper Dahlhaus',8,26,157,''),
(29328428,73,'Igor Guilherme Barbosa da Paixão','Igor Paixão',14,27,152,''),
(29674834,593,'Alec Van Hoorenbeeck','A. Van Hoorenbeeck',17,25,148,''),
(30082121,814,'Yassin Oukili','Yassin Oukili',6,26,153,''),
(30082198,629,'Gastón Ávila','Gastón Ávila',30,25,148,''),
(31255539,822,'Reda Akmum','Reda Akmum',17,25,148,''),
(32013727,1016,'Dylan Mbayo','D. Mbayo',11,27,156,''),
(32490407,629,'Sivert Heggheim Mannsverk','Sivert Mannsverk',16,26,149,''),
(32777738,629,'Devyne Rensch','Devyne Rensch',2,25,NULL,''),
(33166012,61,'David Møller Wolfe','D. Møller Wolfe',18,25,155,''),
(33187441,682,'Ricardo Pepi','Ricardo Pepi',14,27,151,''),
(33587428,1403,'Brian De Keersmaecker','Brian De Keersmaecker',14,26,149,''),
(33991810,1073,'Wanya Marcal-Madivadua','Wanya Marcal Madivadua',37,27,156,''),
(33992579,2345,'Kristian Strømland Lien','Kristian Stromland Lien',NULL,27,151,''),
(34366947,1435,'Sturla Ottesen','Sturla Ottesen',15,25,154,''),
(34390950,1435,'Brett Minnema','Bret Minnema',23,24,24,''),
(34408234,629,'Jay Gorter','Jay Gorter',12,24,24,''),
(34454700,1459,'Makan Aiko','Makan Aïko',11,27,152,''),
(34873442,1016,'Anselmo García McNulty','A. MacNulty',4,25,148,''),
(34907343,669,'Emilio Kehrer','Emilio Kehrer',11,27,151,''),
(37252509,664,'Søren Tengstedt','Søren Tengstedt',10,26,150,''),
(37252524,1459,'Shawn Adewoye','Shawn Adewoye',4,25,148,''),
(37253373,1433,'Vasilios Zagaritis','Vassilis Zagaritis',14,25,155,''),
(37253640,669,'Kyan Vaesen','Kyan Vaesen',9,27,151,''),
(37255814,1433,'Hamdi Akujobi','Hamdi Akujobi',20,25,154,''),
(37255817,669,'Jesse Bosch','Jesse Bosch',8,26,153,''),
(37255818,73,'Ramiz Larbi Zerrouki','Ramiz Zerrouki',6,26,149,''),
(37255819,669,'Connor van den Berg','Connor van den Berg',24,24,NULL,''),
(37255824,1652,'Jacky Donkor','Jacky Donkor',21,27,152,''),
(37255839,919,'Youri Schoonderwalt','Youri Schoonderwaldt',NULL,24,NULL,''),
(37257612,750,'Souffian Elkarouani','Souffian El Karouani',16,25,155,''),
(37262101,1053,'Denzel Hall','Denzel Hall',2,25,154,''),
(37266217,2344,'Joshua Schwirten','Joshua Schwirten',10,26,150,''),
(37286738,73,'Hugo Bueno','Hugo Bueno ',16,25,155,''),
(37286793,2345,'Romano Postema','Romano Postema',29,27,151,''),
(37286795,669,'Ringo Meerveld','Ringo Meerveld',16,26,150,''),
(37287745,1128,'Lee Bonis','Lee Bonis',9,27,NULL,''),
(37296250,2344,'Jay Kruiver','Jay Kruiver',22,25,154,''),
(37296251,664,'Dean Ruben James','Dean James',5,25,155,''),
(37296256,1073,'Ibrahim El Kadiri','Ibrahim El Kadiri',30,27,152,''),
(37296338,1433,'Álex Balboa Bandeira','Alex Balboa',23,26,153,''),
(37303736,1053,'Daniel Seland Karlsbakk','Daniel Karlsbakk',9,27,151,''),
(37316061,822,'Joshua Pynadath','Joshua Pynadath',11,27,152,''),
(37316876,1403,'Luka Kulenović','Luka Kulenović',19,27,NULL,''),
(37317326,593,'Youri Regeer','Y. Regeer',8,26,153,''),
(37317327,1073,'Donny Warmerdam','Donny Warmerdam',8,26,149,''),
(37317328,629,'Christian Theodor Kjelder Rasmussen','Christian Kjelder Rasmussen',29,27,156,''),
(37317434,682,'Ismael Saibari Ben El Basra','Ismael Saibari',34,26,153,''),
(37317437,814,'Ilias Takidine','Ilias Takidine',20,27,152,''),
(37317439,1403,'Bryan Limbombe','Bryan Limbombe',7,27,152,''),
(37325022,1652,'Casper Widell','Casper Widell',5,25,148,''),
(37328101,2344,'Lucas Beerten','Lucas Beerten',15,25,148,''),
(37336190,1128,'Kilian Nikiema','Kilian Nikièma',23,24,NULL,''),
(37336764,494,'Argyrios Darelas','Argyris Darelas',8,26,NULL,''),
(37336803,750,'Oscar Fraulo','Oscar Fraulo',6,26,NULL,''),
(37336952,1459,'Ryan Fosso','Ryan Fosso',80,26,NULL,''),
(37336976,664,'Julius Dirksen','Julius Dirksen',26,25,148,''),
(37337035,1459,'Edouard Michut','Édouard Michut',20,26,153,''),
(37341724,629,'Kristian Nökkvi Hlynsson','Kristian Hlynsson',38,26,NULL,''),
(37342613,494,'Brayann Pereira','Brayann Pereira',2,25,154,''),
(37342706,919,'Shunsuke Mito','Shunsuke Mito',NULL,27,156,''),
(37344111,1435,'Thijs Jansen','Thijs Jansen',1,24,24,''),
(37344112,73,'Quilindschy Hartman','Quilindschy Hartman  ',11,25,155,''),
(37344113,1652,'Noah Naujoks','Noah Naujoks',15,26,150,''),
(37344114,822,'Sem Valk','Sem Valk',3,25,148,''),
(37344222,629,'Ahmetcan Kaplan','Ahmetcan Kaplan',13,25,NULL,''),
(37345947,2344,'Iman Griffith','Iman Griffith',11,27,156,''),
(37351238,814,'Denilho Cleonise','Denilho Cleonise',7,27,156,''),
(37351304,1433,'Ricardo Visus Contreras','Ricardo Visus',4,25,148,''),
(37352749,669,'Cisse Sandra','Cisse Sandra',14,26,150,''),
(37356277,494,'Rijk Janse','Rijk Janse',31,24,24,''),
(37357850,1435,'Tony Rölke','Tony Rölke',18,26,150,''),
(37359754,637,'Maximilien Balard','Maximilien Balard',16,26,149,''),
(37363315,629,'Youri Baas','Youri Baas',15,25,148,''),
(37365874,750,'Zidane Iqbal','Zidane Iqbal',14,26,153,''),
(37366611,61,'Denso Kasius','Denso Kasius',30,25,NULL,''),
(37369432,750,'Anthony Descotte','Anthony Descotte',19,27,151,''),
(37392966,2344,'Patriot Sejdiu','Patriot Sejdiu',77,27,156,''),
(37395418,822,'John Hilton','John Hilton',5,25,155,''),
(37397144,494,'Robin Roefs','Robin Roefs',22,24,24,''),
(37400390,2345,'Tika de Jonge','Tika de Jonge',18,26,153,''),
(37400396,682,'Niek Schiks','Niek Schiks',24,24,NULL,''),
(37400399,494,'D\'Leanu Arts','D\'Leanu Arts',21,25,154,''),
(37400407,682,'Johan Bakayoko','J. Bakayoko',11,27,156,''),
(37400410,669,'Dani Mathieu','Dani Mathieu',27,26,150,''),
(37400412,94,'Mathijs Tielemans','Mathijs Tielemans',21,26,153,''),
(37400419,94,'Simon van Duivenbooden','Simon van Duivenbooden',9,27,NULL,''),
(37404861,1435,'Thomas Poll','Thomas Poll',5,25,155,''),
(37404863,1435,'Gabi Caschili','Gabi Caschili',2,25,154,''),
(37414312,822,'Yannis M\'Bemba','Yannis M\'Bemba',15,25,148,''),
(37429216,61,'Seiya Maikuma','Seiya Maikuma',16,25,154,''),
(37430362,1403,'Mimeirhel Benita','Mimeirhel Benita',2,25,154,''),
(37430897,2345,'Paulos Abraham','Paulos Abraham',NULL,27,152,''),
(37434647,1435,'Remco Balk','Remco Balk',7,27,156,''),
(37434651,94,'Enzo Cornelisse','Enzo Cornelisse',8,26,149,''),
(37440597,61,'Sem Westerveld','Sem Westerveld',13,24,24,''),
(37440603,61,'Zico Buurmeester','Zico Buurmeester',27,26,153,''),
(37440607,2344,'Tiago Cukur','Tiago Çukur',9,27,151,''),
(37445162,629,'Amourrichio Van Axel Dongen','Amourricho van Axel-Dongen',27,27,152,''),
(37445187,94,'Theodosis Macheras','T. Macheras',17,NULL,152,''),
(37452952,682,'Ryan Flamingo','Ryan Flamingo',6,25,148,''),
(37459026,1435,'Arnau Casas Arcas','Arnau Casas',14,25,148,''),
(37459062,750,'Alonzo Engwanda','Alonzo Engwanda',27,26,149,''),
(37459086,750,'Silas Andersen','Silas Andersen',46,26,149,''),
(37490223,1435,'Marcel Schaapman','Marcel Schaapman',21,26,149,''),
(37503644,664,'Jakob Breum','Jakob Breum',7,27,NULL,''),
(37514124,629,'Benjamin Tahirovic','B. Tahirović',33,26,149,''),
(37516978,593,'Sayfallah Ltaief','Sayfallah Ltaief',30,27,152,''),
(37525731,750,'Paxten Aaronson','Paxten Aaronson',21,26,150,''),
(37526217,814,'Luuk  Wouters','Luuk Wouters',34,25,155,''),
(37526249,750,'Yoann Cathline','Yoann Cathline',20,27,152,''),
(37526344,61,'Mayckel Lahdo','Mayckel Lahdo',23,27,NULL,''),
(37527355,919,'Rick Meissen','Rick Meissen',NULL,25,148,''),
(37527863,682,'Couhaib Driouech','Couhaib Driouech',21,27,152,''),
(37528802,822,'Ben Scholte','Ben Scholte',18,26,150,''),
(37529636,637,'Roy Kuijpers','Roy Kuijpers',17,27,156,''),
(37529707,919,'Julian Baas','Julian Baas',NULL,26,153,''),
(37529708,1652,'Derensili Sanches Fernandes','Derensili Sanches Fernandes',30,27,156,''),
(37529951,593,'Daan Rots','D. Rots',11,27,156,''),
(37529953,593,'Mees Hilgers','M. Hilgers',2,25,148,''),
(37529955,637,'Casper Staring','Casper Staring',6,26,149,''),
(37530626,1128,'Joel Ideho','Joel Ideho',11,27,152,''),
(37531079,822,'Jop van den Avert','Jop van den Avert',4,25,148,''),
(37531080,2344,'Wesley Spieringhs','Wesley Spieringhs',6,26,149,''),
(37531158,1016,'Olivier Aertssen','O. Aertssen',3,25,148,''),
(37532463,822,'Dean Zandbergen','Dean Zandbergen',19,27,151,''),
(37533138,1403,'Lorenzo Milani','Lorenzo Milani',22,25,155,''),
(37533856,1128,'Matteo Waem','Matteo Waem',4,25,148,''),
(37536719,822,'Joep van der Sluijs','Joep van der Sluijs',20,26,150,''),
(37536867,664,'Calvin Twigt','Calvin Twigt',6,26,149,''),
(37536873,94,'Mikki van Sas','Mikki van Sas',23,24,24,''),
(37536974,664,'Jamal Amofa','Jamal Amofa',25,NULL,148,''),
(37537522,61,'Daniel Deen','Daniel Deen',31,24,24,''),
(37537863,2345,'Noam Emeran','Noam Emeran',11,27,152,''),
(37538431,2344,'Justin Treichel','Justin Treichel',16,24,24,''),
(37538825,1652,'Pascal Kuiper','Pascal Kuiper',38,24,24,''),
(37539494,2344,'Mamadou Saydou Bangura','Saydou Bangura',27,27,151,''),
(37539574,1053,'Jacob Trenskow','Jacob Trenskow',20,27,156,''),
(37543638,73,'Facundo González Molino','Facundo González',15,25,148,''),
(37543978,1016,'Samir Lagsir','S. Lagsir',21,27,150,''),
(37544442,919,'Kaylen Jermaine Danny Reitmaier','Kaylen Reitmaier',NULL,24,NULL,''),
(37546750,637,'Aron Van Lare','Aron van Lare',37,24,24,''),
(37546752,919,'Mohamed Nassoh','Mohamed Nassoh',NULL,26,150,''),
(37546792,919,'Camiel Neghli','Camiel Neghli',NULL,27,156,''),
(37546925,669,'Maarten Schut','Maarten Schut',41,24,24,''),
(37547377,1652,'Joshua Eijgenraam','Joshua Eijgenraam',24,26,149,''),
(37547506,2345,'Stije Resink','Stije Resink',6,26,153,''),
(37547946,1652,'Serano Seymor','Serano Seymor',34,25,148,''),
(37548114,593,'Max Bruns','M. Bruns',38,25,148,''),
(37548730,1128,'Hugo Wentges','Hugo Wentges',1,24,NULL,''),
(37549513,73,'Ibrahim Osman','Ibrahim Osman ',38,27,156,''),
(37553259,73,'Chris Kevin Nadje','Chris-Kévin Nadje ',34,26,150,''),
(37553517,669,'Youssuf Sylla','Youssuf Sylla',19,27,151,''),
(37553559,73,'Jeyland Mitchell','Jeyland Mitchell ',20,25,148,''),
(37554541,750,'Joshua Mukeh','Joshua Mukeh',44,25,148,''),
(37554542,750,'Kevin Gadellaa','Kevin Gadellaa',33,24,24,''),
(37554726,2344,'Cain Seedorf','Cain Seedorf',47,27,156,''),
(37558993,637,'Raul Paula','Raul Paula',11,26,150,''),
(37559578,1403,'Timo Jansink','Timo Jansink',16,24,24,''),
(37559579,593,'Sam Luca Karssies','S. Karssies',21,24,24,''),
(37559585,1403,'Sem Scheperman','Sem Scheperman',32,26,149,''),
(37559586,664,'Luca Everink','Luca Everink',24,NULL,154,''),
(37562173,919,'Kayky da Silva Chagas','Kayky',NULL,27,NULL,''),
(37562176,919,'Abemly Meto Silu','Metinho',NULL,26,153,''),
(37562363,822,'Devin Haen','Devin Haen',9,27,151,''),
(37563059,682,'Adamo Nagalo','Adamo Nagalo',39,25,148,''),
(37564574,1435,'Tomas Galvez','T. Galvez',NULL,25,155,''),
(37565352,2344,'Koen Jansen','Koen Jansen',5,25,155,''),
(37565511,1128,'Silvinho Esajas','Silvinho Esajas',18,26,NULL,''),
(37565641,1053,'Marcus Linday','Marcus Linday',NULL,26,153,''),
(37566650,750,'Adrian Blake','Adrian Blake',15,27,152,''),
(37568010,61,'Lequincio Zeefuik','Lequincio Zeefuik',28,27,151,''),
(37568511,61,'Ernest Poku','Ernest Poku',21,27,156,''),
(37568513,61,'Maxim Dekker','Maxim Dekker',22,25,NULL,''),
(37568557,94,'Giovanni van Zwam','Giovanni van Zwam',3,25,148,''),
(37568748,682,'Isaac Babadi','Isaac Babadi',26,26,150,''),
(37569270,637,'Adam Kaied','Adam Kaied',14,27,152,''),
(37569524,2344,'Ryan Yang Leijten','Ryan Yang Leijten',20,26,150,''),
(37569676,629,'Anton Gaaei','Anton Gaaei',3,25,NULL,''),
(37569703,1053,'Nikolai Søyset Hopland','Nikolai Soyset Hopland',17,25,148,''),
(37570057,1403,'Juho Talvitie','Juho Talvitie',23,27,NULL,''),
(37570131,2345,'Thijmen Blokzijl','Thijmen Blokzijl',3,25,148,''),
(37570133,2345,'Luciano Valente','Luciano Valente',10,27,152,''),
(37574238,73,'Plamen Andreev','Plamen Andreev ',21,24,24,''),
(37575227,1652,'Lennard Hartjes','Lennard Hartjes',20,26,149,''),
(37575228,73,'Antoni-Djibu Milambo','Antoni Milambo',27,26,150,''),
(37575935,629,'Julian Rijkhoff','Julian Rijkhoff',19,27,NULL,''),
(37576708,669,'Miodrag Pivas','Miodrag Pivas',15,25,148,''),
(37577576,2345,'Finn Stam','Finn Stam',22,25,148,''),
(37579662,1073,'Maas Willemsen','Maas Willemsen',4,25,148,''),
(37579663,1073,'Philip Brittijn','Philip Brittijn',23,26,153,''),
(37579885,1053,'Levi Smans','Levi Smans',14,26,150,''),
(37579972,73,'Anis  Hadj Moussa','Anis Hadj Moussa',23,27,156,''),
(37582530,73,'Thomas Beelen','Thomas Beelen',3,25,148,''),
(37583516,1016,'Duke Verduin','D. Verduin',41,24,24,''),
(37583535,593,'Younes Taha El Idrissi','Y. Taha',19,26,150,''),
(37583696,682,'Esmir Bajraktarevic','E. Bajraktarević',19,27,156,''),
(37583848,1652,'Richie Omorowa','Richie Omorowa',17,27,NULL,''),
(37589628,1433,'Joël van der Wilt','Joel Van der Wilt',31,24,NULL,''),
(37593157,1053,'Mateja Milovanovic','Mateja Milovanović',27,25,NULL,''),
(37593166,1016,'Tristan Gooijer','T. Gooijer',47,25,154,''),
(37593170,1073,'Sten Kremers','Sten Kremers',12,24,24,''),
(37593238,814,'Daouda Weidmann','Daouda Weidmann',30,26,153,''),
(37593344,822,'Vladislavs Razumejevs','Vladislavs Razumejevs',31,24,24,''),
(37594935,1073,'Ties Wieggers','Ties Wieggers',1,24,24,''),
(37595644,94,'Gyan de Regt','Gyan de Regt',7,27,152,''),
(37595807,750,'Tom Gerard de Graaff','Tom de Graaff',32,24,24,''),
(37595899,1128,'Elias Mohammed','Elias Mohammed',NULL,27,151,''),
(37597231,637,'Leo Sauer','L. Sauer',77,27,NULL,''),
(37597888,61,'Wouter Goes','Wouter Goes',3,25,148,''),
(37597889,61,'Ro-Zangelo Daal','Ro-Zangelo Daal',37,27,152,''),
(37597898,61,'Dave Kwakman','Dave Kwakman',33,26,153,''),
(37597900,61,'Rome-Jayden Owusu-Oduro','Rome Jayden Owusu-Oduro',1,24,NULL,''),
(37597901,94,'Loek Postma','Loek Postma',6,25,148,''),
(37597902,61,'Lewis Schouten','Lewis Schouten',24,26,153,''),
(37598261,822,'Daniël van Vianen','Daniel van Vianen',6,26,149,''),
(37599085,629,'Mika Marcel Godts','Mika Godts',11,27,152,''),
(37599456,1053,'Oliver Johansen Braude','Oliver Braude',45,26,NULL,''),
(37600243,629,'Jorrel Hato','J. Hato',4,25,155,''),
(37600255,61,'Mexx Meerdink','Mexx Meerdink',35,27,151,''),
(37601122,1053,'Ilias Sebaoui','Ilias Sebaoui',10,27,152,''),
(37603833,1433,'Jochem Ritmeester van de Kamp','Jochem Ritmeester van de Kamp',5,26,NULL,''),
(37603838,669,'Amar Abdirahman Ahmed','Amar Ahmed',21,27,NULL,''),
(37606984,1053,'Miloš Luković','Miloš Luković',NULL,27,151,''),
(37607140,682,'Matteo Dams','Matteo Dams',32,25,155,''),
(37608596,1652,'Seydou Fini','Seydou Fini',7,27,NULL,''),
(37609278,94,'Sep van der Heijden','Sep van der Heijden',30,24,24,''),
(37609470,822,'Joseph Amuzu','Joseph Amuzu',22,27,152,''),
(37611921,822,'Jayson Ezeb','Jayson Ezeb',27,27,151,''),
(37611941,814,'Faissal Al Mazyani','Faissal Al Mazyani',33,25,148,''),
(37612033,1016,'Teun Gijselhart','T. Gijselhart',38,26,153,''),
(37612035,61,'Kees Smit','Kees Smit',26,26,153,''),
(37612037,61,'Jayden Addai','Jayden Addai',17,27,156,''),
(37612540,2345,'Jasper Meijster','Jasper Meijster',31,24,24,''),
(37612544,919,'Mike Kleijn','Mike Kleijn',NULL,25,NULL,''),
(37612547,822,'Jaden Fernando Slory','Jaden Slory',28,27,156,''),
(37614103,822,'Lorenzo Codutti','Lorenzo Codutti',2,25,154,''),
(37614429,1016,'Damian van der Haar','D. van der Haar',33,25,148,''),
(37614967,1128,'David van de Riet','David van de Riet',29,24,NULL,''),
(37615099,664,'Nando Verdoni','Nando Verdoni',33,24,24,''),
(37615103,814,'Tim van de Loo','Tim van de Loo',22,26,153,''),
(37619473,494,'Kodai Sano','Kodai Sano',23,26,153,''),
(37620783,1652,'Seb Loeffen','Seb Loeffen',18,25,148,''),
(37621234,919,'Hamza el Dahri','Hamza El Dahri',NULL,26,NULL,''),
(37622856,73,'Givairo Read','Givairo Read ',26,25,154,''),
(37622868,2345,'Dirk Baron','Dirk Baron',24,24,24,''),
(37622927,822,'Oluwakorede David Osundina','Oluwakorede Osundina',7,27,156,''),
(37622962,94,'Marcus Steffen','Marcus Steffen',55,25,148,''),
(37622964,1652,'Rayvien Rosario','Rayvien Rosario',14,27,152,''),
(37623398,494,'Basar Onal','Başar Önal',11,27,152,''),
(37623985,1403,'Shiloh ’t Zand','Shiloh \'t Zand',10,26,153,''),
(37623986,1128,'Milan Hokke','Milan Hokke',15,25,148,''),
(37624329,1053,'Bernt Klaverboer','Bernt Klaverboer',22,24,NULL,''),
(37624877,1652,'Siem de Moes','Siem de Moes',32,25,155,''),
(37624914,1073,'Kaya Symons','Kaya Symons',21,25,155,''),
(37625391,664,'Milan Smit','Milan Smit',9,27,151,''),
(37630140,750,'Miliano Jonathans','Miliano Jonathans',NULL,27,156,''),
(37630418,61,'Ruben van Bommel','Ruben van Bommel',7,27,152,''),
(37630829,1073,'Tristan van Gilst','Tristan van Gilst',7,27,152,''),
(37634566,73,'Zepiqueno Redmond','Zepiqueno Redmond',49,27,151,''),
(37634597,94,'Irakli Yegoian','Irakli Yegoian',20,26,NULL,''),
(37634633,822,'Chiel Olde Keizer','Chiel Olde Keizer',12,25,155,''),
(37637488,814,'Chris Lokesa','Chris Lokesa',14,26,150,''),
(37640044,1652,'Nesto Groen','Nesto Groen',19,27,151,''),
(37640734,1073,'Joran Hardeman','Joran Hardeman',14,25,148,''),
(37640735,1073,'Levi Schoppema','Levi Schoppema',5,25,155,''),
(37640737,1073,'Anis Yadir','Anis Yadir',34,26,150,''),
(37643111,73,'Mannou Berger','Mannou Berger',41,24,24,''),
(37645156,1128,'Dano Lourens','Dano Lourens',22,27,151,''),
(37645259,1459,'Luka Tunjic','Luka Tunjic',77,26,150,''),
(37645868,1073,'Rowan Besselink','Rowan Besselink',3,25,148,''),
(37646319,2344,'Jordy Steins','Jordy Steins',23,24,24,''),
(37646946,822,'Tijn Baltussen','Tijn Baltussen',13,24,24,''),
(37646968,1435,'Floris Smand','Floris Smand',3,25,148,''),
(37646970,1435,'Bryant Nieling','Bryant Nieling',20,25,148,''),
(37648056,494,'Sami Ouaissa','Sami Ouaissa',25,26,150,''),
(37648267,494,'Kas de Wit','Kas de Wit',29,26,150,''),
(37648278,664,'Pim Saathof','Pim Saathof',28,25,154,''),
(37648279,664,'Robbin Weijenberg','Robbin Weijenberg',15,26,153,''),
(37648345,664,'Aske Adelgaard','Aske Adelgaard',29,25,NULL,''),
(37648810,814,'Luuk Vogels','Luuk Vogels',16,24,24,''),
(37662891,822,'Igor Daniel da Silva','Igor',24,26,150,''),
(37663130,73,'Gjivai Zechiël','Gjivai Zechiël',24,26,149,''),
(37663441,1652,'Chadwick Zachary Booth','Zach Booth',11,26,150,''),
(37663893,2345,'Thom van Bergen','Thom van Bergen',26,27,NULL,''),
(37666626,669,'Niels van Berkel','Niels van Berkel',44,25,NULL,''),
(37666627,669,'Amine Lachkar','Amine Lachkar',34,26,149,''),
(37667281,1016,'Mohamed Oukhattou','M. Oukhattou',37,26,150,''),
(37667301,2345,'Jorg Schreuders','Jorg Schreuders',14,27,156,''),
(37673074,669,'Khaled Razak','Khaled Razak',35,27,156,''),
(37676359,1073,'Jesse van de Haar','Jesse van de Haar',15,27,151,''),
(37676562,682,'Tygo Land','Tygo Land',28,26,NULL,''),
(37677894,2345,'Maxim Mariani','Maxim Mariani',36,25,148,''),
(37680394,919,'Marvin Young','Marvin Young',NULL,25,148,''),
(37680622,1435,'Benjamin Pauwels','Benjamin Pauwels',29,27,152,''),
(37682186,1403,'Giandro Alejandro Sambo','Giandro Sambo',28,27,152,''),
(37684845,593,'Mats Rots','M. Rots',39,25,155,''),
(37684846,593,'Gijs Besselink','G. Besselink',41,26,153,''),
(37685374,94,'Roan van der Plaat','Roan van der Plaat',24,25,155,''),
(37688142,629,'Dies Janse','Dies Janse',36,25,NULL,''),
(37688200,593,'Juliën Mesbahi','J. Mesbahi',24,25,148,''),
(37689817,1403,'Diego van Oorschot','Diego van Oorschot',20,27,151,''),
(37691111,1073,'Stan Wevers','Stan Wevers',42,26,149,''),
(37691885,1403,'Stijn Bultman','Stijn Bultman',35,25,148,''),
(37694720,94,'Michael Folabi Dokunmu','Michael Dokunmu',29,26,150,''),
(37698735,2345,'Fofin Turay','Fofin Turay',23,27,152,''),
(37699370,494,'Kento Shiogai','Kento Shiogai',9,27,151,''),
(37702367,822,'Gabriele Parlanti','Gabriele Parlanti',8,26,153,''),
(37702527,1459,'Onur Demir','Onur Demir',17,27,151,''),
(37702533,1435,'Wiebe Kooistra','Wiebe Kooistra',27,27,156,''),
(37702637,1016,'Nick Fichtinger','N. Fichtinger',34,26,149,''),
(37704239,1053,'Melle Joop Witteveen','Melle Witteveen',28,26,150,''),
(37704458,2344,'Reda El Meliani','Reda El Meliani',26,25,154,''),
(37705747,593,'Harrie Kuster','H. Kuster',29,26,153,''),
(37709747,94,'Mats Egbring','Mats Egbring',22,25,154,''),
(37710045,1053,'Espen van Ee','Espen Van Ee',21,26,153,''),
(37711542,637,'Lars Mol','Lars Mol',28,26,149,''),
(37714313,637,'Saná Fernandes','Saná Fernandes',19,27,152,''),
(37715762,1435,'Yorem van der Veen','Yorem van der Veen',30,27,152,''),
(37716129,94,'Tom Bramel','Tom Bramel',16,24,24,''),
(37717571,637,'Aimane Jaddi','Aimane Jaddi',30,26,153,''),
(37718670,2345,'Wouter Prins','Wouter Prins',2,25,155,''),
(37721211,94,'Jim Koller','Jim Koller',18,26,150,''),
(37724507,919,'Ayoub Oufkir','Ayoub Oufkir',NULL,27,156,''),
(37725958,494,'Luc Nieuwenhuijs','Luc Nieuwenhuijs',26,27,152,''),
(37727819,1435,'Tyrique Mercera','Tyrique Mercera',26,25,154,''),
(37731803,94,'Andy Visser','Andy Visser',19,27,151,''),
(37734530,1435,'Toni Jonker','Toni Jonker',24,25,148,''),
(37734607,1128,'Finn de Bruin','Finn de Bruin',16,26,150,''),
(37736027,1435,'Matthias Nartey','Matthias Nartey',17,26,150,''),
(37736095,637,'Cherrion Valerius','Cherrion Valerius',25,25,NULL,''),
(37736117,1016,'Dylan Ruward','D. Ruward',36,25,NULL,''),
(37736118,1652,'Cedric Hatenboer','Cedric Hatenboer',23,26,153,''),
(37736807,2345,'Sven Bouland','Sven Bouland',67,25,NULL,''),
(37737126,1435,'Bram Marsman','Bram Marsman',25,25,155,''),
(37738957,94,'Anass Zarrouk','Anass Zarrouk',34,26,150,''),
(37747409,1652,'Jose De Almeida Reis','Jose De Almeida Reis',22,25,148,''),
(37748891,494,'Thomas Reinders','Thomas Reinders',15,25,148,''),
(37752311,1459,'Ramazan Bayram','Ramazan Bayram',71,24,24,''),
(37752565,1128,'Ronald Boakye','Ronny Boakye',36,25,155,''),
(37754581,822,'Kwame Tabiri','Kwame Tabiri',12,26,149,''),
(37756561,1403,'Jop Tijink','Jop Tijink',27,25,148,''),
(37756562,1459,'Tristan Schenkhuizen','Tristan Schenkhuizen',38,26,149,''),
(37759675,2345,'David van der Werff','David van der Werff',46,26,150,''),
(37760518,1016,'Gabriël Reiziger','G. Reiziger',32,26,NULL,''),
(37762048,494,'Omar Mohamedhoesein','Omar Jamil',27,26,153,''),
(37767516,637,'Daan van Reeuwijk','Daan Van Reeuwijk',18,25,NULL,''),
(37767705,1652,'Jerolldino Armantrading','Jerolldino Armantrading',33,27,151,''),
(37767711,94,'Adam Tahaui','Adam Tahaui',25,26,153,''),
(37767713,94,'Sil Milder','Sil Milder',12,24,24,''),
(37767870,669,'Jens Mathijsen','Jens Mathijsen',48,25,148,''),
(37767871,669,'Per van Loon','Per van Loon',50,27,156,''),
(37767873,1128,'Lorenzo Maasland','Lorenzo Maasland',35,27,152,''),
(37768056,1053,'Isaiah Ahmed','Isaiah Ahmed',39,26,153,''),
(37768057,1053,'Ties Oostra','Ties Oostra',35,26,153,''),
(37768058,1053,'Dimitris Rallis','Dimitris Rallis',26,27,151,''),
(37783266,94,'Bas Huisman','Bas Huisman',35,26,150,''),
(37787740,1128,'Illaijh de Ruijter','Illaijh de Ruijter',26,25,155,''),
(37912264,1016,'Nick Dobben','N. Dobben',42,24,NULL,'');
/*!40000 ALTER TABLE `player` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rounds`
--

DROP TABLE IF EXISTS `rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rounds` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rounds`
--

LOCK TABLES `rounds` WRITE;
/*!40000 ALTER TABLE `rounds` DISABLE KEYS */;
/*!40000 ALTER TABLE `rounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scores`
--

DROP TABLE IF EXISTS `scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wedstrijd_name` varchar(255) NOT NULL,
  `starting_at` datetime NOT NULL,
  `end_result_info` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scores`
--

LOCK TABLES `scores` WRITE;
/*!40000 ALTER TABLE `scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stadium`
--

DROP TABLE IF EXISTS `stadium`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stadium` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stadium`
--

LOCK TABLES `stadium` WRITE;
/*!40000 ALTER TABLE `stadium` DISABLE KEYS */;
/*!40000 ALTER TABLE `stadium` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2346 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES
(61,'AZ'),
(73,'Feyenoord'),
(94,'Vitesse'),
(494,'NEC'),
(593,'FC Twente'),
(629,'Ajax'),
(637,'NAC Breda'),
(664,'Go Ahead Eagles'),
(669,'Willem II'),
(682,'PSV'),
(750,'FC Utrecht'),
(814,'RKC Waalwijk'),
(822,'FC Dordrecht'),
(919,'Sparta Rotterdam'),
(1016,'PEC Zwolle'),
(1053,'SC Heerenveen'),
(1073,'De Graafschap'),
(1128,'ADO Den Haag'),
(1403,'Heracles Almelo'),
(1433,'Almere City'),
(1435,'SC Cambuur'),
(1459,'Fortuna Sittard'),
(1652,'Excelsior'),
(2344,'Roda JC Kerkrade'),
(2345,'FC Groningen');
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `avatar` varchar(255) DEFAULT NULL,
  `scores` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(3,'testuser','test@test','$2y$13$LK5Nt17SSy3cq.6nuBecaOaQ5hv59sPna1bbBoZhxttV2uZXhNR7y','[\"ROLE_USER\"]','/uploads/avatars/67bc9ade6b962.jpg',0),
(5,'Nver','nver.am@live.nl','$2y$13$M5fFHID/FjBn2D9ArhS3Bu7iH2UtppD7rLYa/0jC3ZwH/aS1DnmOW','[\"ROLE_USER\"]','/uploads/avatars/67c4a4e53ce36.jpg',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-03-08 16:48:37
