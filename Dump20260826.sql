-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ticketpro
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `avisos`
--

DROP TABLE IF EXISTS `avisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avisos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('mantenimiento','incidente','informativo','general') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `importancia` enum('critica','alta','media','normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `aplica_a` enum('todos','departamento','usuarios','oficinas') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `afecta_a` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mostrar_notificaciones` tinyint(1) NOT NULL DEFAULT '1',
  `fijado` tinyint(1) NOT NULL DEFAULT '0',
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publicado_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  KEY `idx_avisos_publicado_por` (`publicado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avisos`
--

LOCK TABLES `avisos` WRITE;
/*!40000 ALTER TABLE `avisos` DISABLE KEYS */;
INSERT INTO `avisos` VALUES (1,'Aviso de prueba','mantenimiento','critica','2026-08-20 20:05:00',NULL,'todos','Aviso de prueba','{\"tipo\":\"todos\",\"empresa_id\":1}',1,0,'avisos/XafEl8qgymf2zKHQz8KCrkGwZaHT8bDvRnj2husg.png','jhinojosa','2026-08-21 01:07:48','2026-08-21 01:07:48','activo'),(2,'Aviso de prueba 3','mantenimiento','media','2026-08-22 00:13:00',NULL,'departamento','Aviso de prueba','{\"tipo\":\"departamentos\",\"ids\":[2]}',1,0,'avisos/U0w90psDHuPWhJSha5aH4Bg0yqn2ZEMVMZmefMfz.jpg','jhinojosa','2026-08-21 14:14:12','2026-08-25 14:54:43','inactivo'),(3,'Aviso de prueba 4','mantenimiento','media','2026-08-24 08:29:00',NULL,'todos','Aviso de prueba','{\"tipo\":\"todos\",\"empresa_id\":1}',1,0,'avisos/sop5I6RC4k0qO9psZtIrd3AR6CbzAqa5HoKotwLY.png','jhinojosa','2026-08-24 13:30:07','2026-08-24 13:30:07','activo'),(4,'Aviso de prueba 12','informativo','normal','2026-08-25 09:05:00',NULL,'departamento','Contenido de prueba sin notificacion','{\"tipo\":\"departamentos\",\"ids\":[2]}',0,1,'avisos/0TnW9YCU8PJZwW9Zz46wsfrWA1ZZJObBXK5n8Yl0.png','jhinojosa','2026-08-25 14:07:00','2026-08-25 14:07:00','activo'),(5,'Aviso de prueba 13','general','normal','2026-08-25 09:07:00',NULL,'departamento','Aviso de prueba con notificacion','{\"tipo\":\"departamentos\",\"ids\":[2]}',1,0,'avisos/Xd2yoSnsaWYsemaarP6bsfnmHGKJw6kwsTPhnPL2.png','jhinojosa','2026-08-25 14:08:53','2026-08-25 14:08:53','activo');
/*!40000 ALTER TABLE `avisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('manual','automatico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `es_configuracion` tinyint(1) NOT NULL DEFAULT '0',
  `frecuencia` enum('diario','semanal','mensual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `hora` time DEFAULT NULL,
  `dia_semana` tinyint unsigned DEFAULT NULL,
  `dia_mes` tinyint unsigned DEFAULT NULL,
  `estado` enum('pendiente','ejecutando','completado','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `tamaño` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_finalizacion` datetime DEFAULT NULL,
  `ultima_ejecucion` datetime DEFAULT NULL,
  `proxima_ejecucion` datetime DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_backups_login` (`login`),
  KEY `idx_backups_tipo` (`tipo`),
  KEY `idx_backups_estado` (`estado`),
  KEY `idx_backups_created_at` (`created_at`),
  CONSTRAINT `fk_backups_usuario` FOREIGN KEY (`login`) REFERENCES `users` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
INSERT INTO `backups` VALUES (20,'jhinojosa','Configuración de backups',NULL,'automatico',1,'diario',1,'10:39:00',NULL,NULL,'pendiente',NULL,NULL,'2026-08-26 10:59:01','2026-08-26 10:56:01','2026-08-27 10:39:00','Backups automáticos activados.','2026-08-25 22:32:15','2026-08-26 16:02:30');
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `oficina_id` int NOT NULL,
  `usuario_departamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_departamentos_oficina` (`oficina_id`),
  KEY `fk_departamentos_users` (`usuario_departamento`),
  CONSTRAINT `fk_departamentos_oficina` FOREIGN KEY (`oficina_id`) REFERENCES `oficinas` (`id`),
  CONSTRAINT `fk_departamentos_users` FOREIGN KEY (`usuario_departamento`) REFERENCES `users` (`login`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'Tecnologias',1,'jhinojosa'),(2,'Administracion',1,'freyes'),(3,'Tecnologias',1,'anavarro'),(4,'Recursos Humanos',1,'avargas'),(5,'Ventas',1,'dsilva');
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispositivos`
--

DROP TABLE IF EXISTS `dispositivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispositivos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_equipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_equipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` enum('Vinculado','Desvinculado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Vinculado',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dispositivos_id_equipo` (`id_equipo`),
  KEY `idx_dispositivos_login` (`login`),
  CONSTRAINT `fk_dispositivos_users_login` FOREIGN KEY (`login`) REFERENCES `users` (`login`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispositivos`
--

LOCK TABLES `dispositivos` WRITE;
/*!40000 ALTER TABLE `dispositivos` DISABLE KEYS */;
INSERT INTO `dispositivos` VALUES (1,'jhinojosa','PC-TI-3','DESKTOP-15FER9','2026-08-25 19:43:41','2026-08-25 20:49:27','Vinculado'),(2,'jhinojosa','PHONE-TI-1','CELLPHONE-201547','2026-08-25 19:50:46','2026-08-25 20:49:43','Vinculado'),(3,'jhinojosa','IMPRESORA-TI-1','PRINTER-DF784','2026-08-25 19:51:16','2026-08-25 19:51:16','Vinculado'),(4,'freyes','LAP-ADM-2','LENOVO-15970','2026-08-25 20:42:52','2026-08-25 20:42:52','Vinculado'),(5,'freyes','PHONE-ADM-3','CELLPHONE-C9756A','2026-08-25 20:43:54','2026-08-25 20:43:54','Vinculado'),(6,'freyes','IMPRESORA-ADM-1','HP-975S2','2026-08-25 20:45:03','2026-08-25 20:50:10','Vinculado');
/*!40000 ALTER TABLE `dispositivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Cymez');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_08_22_210042_create_numeros_empleado_table',1),(2,'2019_12_14_000001_create_personal_access_tokens_table',2),(3,'2026_08_26_161235_change_tokenable_id_to_string_in_personal_access_tokens_table',3),(4,'2026_08_26_161600_change_tokenable_id_to_string_again_in_personal_access_tokens_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `leida` tinyint(1) NOT NULL DEFAULT '0',
  `referencia_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notificaciones_login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (1,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00004: Pc no enciende','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=4',1,NULL,'2026-08-21 02:42:36','2026-08-24 14:07:13'),(2,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00001: Impresora no imprime','ticket','green','http://127.0.0.1:8000/dashboard/tickets/1',1,NULL,'2026-08-21 02:50:55','2026-08-21 04:55:45'),(3,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00001 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/1',1,NULL,'2026-08-21 04:52:04','2026-08-21 04:55:45'),(4,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00001: Hey','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:11:04','2026-08-24 14:07:13'),(5,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00003: Holaaa?','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:14:11','2026-08-24 14:07:13'),(6,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: p','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:16:27','2026-08-24 14:07:13'),(7,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: pi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:19:06','2026-08-24 14:07:13'),(8,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: ´pipi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:19:11','2026-08-24 14:07:13'),(9,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: p','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:27:18','2026-08-24 14:07:13'),(10,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: kiki','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:31:08','2026-08-24 14:07:13'),(11,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:38:51','2026-08-24 14:07:13'),(12,'jhinojosa','comentario','Nuevo comentario','freyes comentó en el ticket TKT-2026-00004: hey','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:45:28','2026-08-24 14:07:13'),(13,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Oye','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:47:09','2026-08-24 14:07:13'),(15,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00004: Pc no enciende','ticket','green','http://127.0.0.1:8000/dashboard/tickets/4',1,NULL,'2026-08-21 17:51:58','2026-08-21 19:55:13'),(16,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:21','2026-08-21 21:07:29'),(17,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:21','2026-08-24 14:07:13'),(18,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:32','2026-08-21 21:07:29'),(19,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:32','2026-08-24 14:07:13'),(20,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00004: No','message-circle','blue','http://127.0.0.1:8000/tickets/4/comentarios',1,NULL,'2026-08-21 20:05:25','2026-08-21 20:16:24'),(21,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Sisisi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:10:02','2026-08-21 21:07:29'),(22,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Sisisi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:10:02','2026-08-24 14:07:13'),(23,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00003: Pc no enciende','ticket','green','http://127.0.0.1:8000/dashboard/tickets/3',1,NULL,'2026-08-21 20:11:03','2026-08-21 20:16:24'),(24,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00007: Ticket de prueba 3','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=7',1,NULL,'2026-08-21 20:17:15','2026-08-24 14:07:13'),(25,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00007: Ticket de prueba 3','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=7',1,NULL,'2026-08-21 20:17:15','2026-08-21 21:07:29'),(26,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00007: Ticket de prueba 3','ticket','green','http://127.0.0.1:8000/dashboard/tickets/7',1,NULL,'2026-08-21 20:18:33','2026-08-21 20:19:51'),(27,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00007 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/7',1,NULL,'2026-08-21 20:19:05','2026-08-21 20:19:51'),(28,'anavarro','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/1/aprobar',1,NULL,'2026-08-21 20:52:56','2026-08-21 21:07:29'),(29,'jhinojosa','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/1/aprobar',1,NULL,'2026-08-21 20:52:56','2026-08-24 14:07:13'),(30,'anavarro','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/2/aprobar',1,NULL,'2026-08-21 20:54:19','2026-08-21 21:07:29'),(31,'jhinojosa','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/2/aprobar',1,NULL,'2026-08-21 20:54:19','2026-08-24 14:07:13'),(32,'anavarro','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios?solicitud=3',1,NULL,'2026-08-21 21:06:51','2026-08-21 21:07:29'),(33,'jhinojosa','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios?solicitud=3',1,NULL,'2026-08-21 21:06:51','2026-08-24 14:07:13'),(34,'freyes','solicitud_cambio','Solicitud de cambio aprobada','Tu solicitud de cambio fue aprobada por el área de Tecnologías.','check-circle','green',NULL,1,NULL,'2026-08-21 21:15:59','2026-08-24 14:34:43'),(35,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00007: Si','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/7',1,NULL,'2026-08-24 13:25:45','2026-08-24 14:34:43'),(36,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00007: Se adjuntó un archivo al comentario.','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/7',1,NULL,'2026-08-24 13:25:56','2026-08-24 14:34:43'),(37,'freyes','aviso','Aviso de prueba 4','Aviso de prueba',NULL,NULL,'http://127.0.0.1:8000/dashboard/avisos?3',1,NULL,'2026-08-24 13:30:07','2026-08-24 14:34:43'),(38,'avargas','aviso','Aviso de prueba 4','Aviso de prueba',NULL,NULL,'http://127.0.0.1:8000/dashboard/avisos?3',0,NULL,'2026-08-24 13:30:07','2026-08-24 13:30:07'),(39,'dsilva','aviso','Aviso de prueba 4','Aviso de prueba',NULL,NULL,'http://127.0.0.1:8000/dashboard/avisos?3',0,NULL,'2026-08-24 13:30:07','2026-08-24 13:30:07'),(40,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00008: Impresora de administracion sin conexion','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=8',1,NULL,'2026-08-24 14:36:04','2026-08-24 16:42:59'),(41,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00008: Impresora de administracion sin conexion','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=8',1,NULL,'2026-08-24 14:36:04','2026-08-26 16:24:19'),(42,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00009: Ticket de prueba 5','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=9',1,NULL,'2026-08-24 15:29:23','2026-08-24 16:42:59'),(43,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00009: Ticket de prueba 5','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=9',1,NULL,'2026-08-24 15:29:23','2026-08-26 16:24:19'),(44,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00009: Ticket de prueba 5','ticket','green','http://127.0.0.1:8000/dashboard/tickets/9',1,NULL,'2026-08-24 15:45:44','2026-08-24 16:53:11'),(45,'freyes','ticket_cancelado','Ticket cancelado','Tu ticket #TKT-2026-00009 fue cancelado.','check-circle','red','http://127.0.0.1:8000/dashboard/tickets/9',1,NULL,'2026-08-24 15:46:17','2026-08-24 16:53:11'),(46,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00010: Ticket de prueba 6','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=10',1,NULL,'2026-08-24 15:51:21','2026-08-24 16:42:59'),(47,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00010: Ticket de prueba 6','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=10',1,NULL,'2026-08-24 15:51:21','2026-08-26 16:24:19'),(48,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00010: Ticket de prueba 6','ticket','green','http://127.0.0.1:8000/dashboard/tickets/10',1,NULL,'2026-08-24 15:52:56','2026-08-24 16:53:11'),(49,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00010 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/10',1,NULL,'2026-08-24 15:53:36','2026-08-24 16:53:11'),(50,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00004 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/4',1,NULL,'2026-08-24 16:10:37','2026-08-24 16:53:11'),(51,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00003 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/3',1,NULL,'2026-08-24 16:14:32','2026-08-24 16:53:11'),(52,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00008: Impresora de administracion sin conexion','ticket','green','http://127.0.0.1:8000/dashboard/tickets/8',1,NULL,'2026-08-24 16:20:25','2026-08-24 16:53:11'),(53,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00006: Ticket de prueba 2','ticket','green','http://127.0.0.1:8000/dashboard/tickets/6',1,NULL,'2026-08-24 16:20:39','2026-08-24 16:53:11'),(54,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00006 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/6',1,NULL,'2026-08-24 16:20:58','2026-08-24 16:53:11'),(55,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00008 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/8',1,NULL,'2026-08-24 16:26:18','2026-08-24 16:53:11'),(56,'avargas','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00005: Ticket de prueba','ticket','green','http://127.0.0.1:8000/dashboard/tickets/5',0,NULL,'2026-08-24 16:32:46','2026-08-24 16:32:46'),(57,'avargas','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00005 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/5',0,NULL,'2026-08-24 16:33:07','2026-08-24 16:33:07'),(58,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00002: Computadora no enciende','ticket','green','http://127.0.0.1:8000/dashboard/tickets/2',1,NULL,'2026-08-24 16:34:52','2026-08-24 16:53:11'),(59,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00002 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/2',1,NULL,'2026-08-24 16:35:17','2026-08-24 16:53:11'),(60,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00011: Ticket de prueba numero','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=11',1,NULL,'2026-08-24 16:39:06','2026-08-24 16:42:59'),(61,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00011: Ticket de prueba numero','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=11',1,NULL,'2026-08-24 16:39:06','2026-08-26 16:24:19'),(62,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00011: Ticket de prueba numero','ticket','green','http://127.0.0.1:8000/dashboard/tickets/11',1,NULL,'2026-08-24 16:39:22','2026-08-24 16:53:11'),(63,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00011 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/11',1,NULL,'2026-08-24 16:39:49','2026-08-24 16:53:11'),(64,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00012: Ticket de prueba numero 2','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=12',1,NULL,'2026-08-24 16:40:17','2026-08-24 16:42:59'),(65,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00012: Ticket de prueba numero 2','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=12',1,NULL,'2026-08-24 16:40:17','2026-08-26 16:24:19'),(66,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00012: Ticket de prueba numero 2','ticket','green','http://127.0.0.1:8000/dashboard/tickets/12',1,NULL,'2026-08-24 16:40:25','2026-08-24 16:53:11'),(67,'freyes','ticket_cancelado','Ticket cancelado','Tu ticket #TKT-2026-00012 fue cancelado.','x-circle','red','http://127.0.0.1:8000/dashboard/tickets/12',1,NULL,'2026-08-24 16:40:42','2026-08-24 16:53:11'),(68,'freyes','solicitud_cambio','Solicitud de cambio rechazada','Tu solicitud de cambio fue rechazada por el área de Tecnologías. Motivo: No','x-circle','red',NULL,1,NULL,'2026-08-24 16:41:03','2026-08-24 16:53:11'),(69,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00012: a','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/12',1,NULL,'2026-08-24 16:48:19','2026-08-24 16:53:11'),(70,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00012: a','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/12',1,NULL,'2026-08-24 16:50:18','2026-08-24 16:53:11'),(71,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00012: a','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/12',1,NULL,'2026-08-24 16:50:19','2026-08-24 16:53:11'),(72,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00012: e','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-24 16:50:27','2026-08-26 16:24:19'),(73,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00012: e','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-24 16:50:27','2026-08-24 16:54:03'),(74,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00012: e','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-24 16:50:30','2026-08-26 16:24:19'),(75,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00012: e','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-24 16:50:30','2026-08-24 16:54:03'),(76,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00010: a','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/10',1,NULL,'2026-08-24 16:57:32','2026-08-24 20:01:03'),(77,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00010: a','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/10',1,NULL,'2026-08-24 16:57:35','2026-08-24 20:01:03'),(78,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00013: Ticket de prueba numero 3','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=13',1,NULL,'2026-08-24 20:03:11','2026-08-25 12:51:57'),(79,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00013: Ticket de prueba numero 3','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=13',1,NULL,'2026-08-24 20:03:11','2026-08-26 16:24:19'),(80,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00013: Ticket de prueba numero 3','ticket','green','http://127.0.0.1:8000/dashboard/tickets/13',1,NULL,'2026-08-24 20:03:28','2026-08-25 13:14:13'),(81,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00013 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/13',1,NULL,'2026-08-24 20:23:51','2026-08-25 13:14:13'),(82,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00014: Ticket de prueba numero 5','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=14',1,NULL,'2026-08-24 21:10:17','2026-08-25 12:51:57'),(83,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00014: Ticket de prueba numero 5','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=14',1,NULL,'2026-08-24 21:10:17','2026-08-26 16:24:19'),(84,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00014: Ticket de prueba numero 5','ticket','green','http://127.0.0.1:8000/dashboard/tickets/14',1,NULL,'2026-08-24 21:10:27','2026-08-25 13:14:13'),(85,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00014 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/14',1,NULL,'2026-08-24 21:17:34','2026-08-25 13:14:13'),(86,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00015: Ticket de prueba numero 6','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=15',1,NULL,'2026-08-24 21:58:15','2026-08-25 12:51:57'),(87,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00015: Ticket de prueba numero 6','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=15',1,NULL,'2026-08-24 21:58:15','2026-08-26 16:24:19'),(88,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00015: Ticket de prueba numero 6','ticket','green','http://127.0.0.1:8000/dashboard/tickets/15',1,NULL,'2026-08-24 22:01:43','2026-08-25 13:14:13'),(89,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00015 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/15',1,NULL,'2026-08-24 22:30:23','2026-08-25 13:14:13'),(90,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00016: Ticket de prueba numero 15','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=16',1,NULL,'2026-08-25 13:15:23','2026-08-25 14:35:36'),(91,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00016: Ticket de prueba numero 15','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=16',1,NULL,'2026-08-25 13:15:23','2026-08-26 16:24:19'),(92,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00016: Ticket de prueba numero 15','ticket','green','http://127.0.0.1:8000/dashboard/tickets/16',1,NULL,'2026-08-25 13:15:32','2026-08-25 13:23:20'),(93,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00016 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/16',1,NULL,'2026-08-25 13:16:14','2026-08-25 13:23:20'),(94,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00016: A','message-circle','blue','http://127.0.0.1:8000/dashboard/tickets/16',1,NULL,'2026-08-25 13:24:01','2026-08-25 13:51:44'),(95,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00016: a','message-circle','blue','misticketusuario',1,NULL,'2026-08-25 13:29:32','2026-08-25 13:51:44'),(96,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00016: a','message-circle','blue','misticketusuario',1,NULL,'2026-08-25 13:29:34','2026-08-25 13:51:44'),(97,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00016: a','message-circle','blue','/dashboard/mistickets',1,NULL,'2026-08-25 13:31:06','2026-08-25 13:51:44'),(98,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00016: Se adjuntó un archivo al comentario.','message-circle','blue','/dashboard/mistickets',1,NULL,'2026-08-25 13:31:14','2026-08-25 13:51:44'),(99,'freyes','aviso','Aviso de prueba 13','Aviso de prueba con notificacion','megaphone','blue','http://127.0.0.1:8000/dashboard/avisos?5',1,NULL,'2026-08-25 14:08:53','2026-08-25 14:14:27'),(100,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00017: Ticket de prueba numero 20','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=17',1,NULL,'2026-08-25 14:32:02','2026-08-25 14:35:36'),(101,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00017: Ticket de prueba numero 20','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=17',1,NULL,'2026-08-25 14:32:02','2026-08-26 16:24:19'),(102,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00017: Ticket de prueba numero 20','ticket','green','http://127.0.0.1:8000/dashboard/tickets/17',1,NULL,'2026-08-25 14:32:13','2026-08-26 16:28:58'),(103,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00017 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/17',1,NULL,'2026-08-25 14:32:59','2026-08-26 16:28:58'),(106,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00017: o','message-circle','blue','/tecnologias/tickets/',1,NULL,'2026-08-25 18:41:16','2026-08-26 16:24:19'),(107,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00017: o','message-circle','blue','/tecnologias/tickets/',1,NULL,'2026-08-25 18:41:16','2026-08-25 18:50:19'),(108,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00017: o','message-circle','blue','/tecnologias/tickets/',1,NULL,'2026-08-25 18:41:18','2026-08-26 16:24:19'),(109,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00017: o','message-circle','blue','/tecnologias/tickets/',1,NULL,'2026-08-25 18:41:18','2026-08-25 18:50:19'),(110,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00017: Hola','message-circle','blue','/dashboard/mistickets',1,NULL,'2026-08-25 18:59:42','2026-08-26 16:28:58'),(111,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00017: Se adjuntó un archivo al comentario.','message-circle','blue','/dashboard/mistickets',1,NULL,'2026-08-25 18:59:53','2026-08-26 16:28:58'),(112,'jhinojosa','backup','Error en backup automático','No se pudo realizar el backup automático. Motivo: No se encontró mysqldump.exe en: C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.ex','alert-triangle','red','http://localhost/tecnologias/backups',1,NULL,'2026-08-26 15:59:01','2026-08-26 16:21:15'),(113,'jhinojosa','backup','Error en backup automático','No se pudo realizar el backup automático. Motivo: No se encontró mysqldump.exe en: C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.ex','alert-triangle','red','http://localhost/tecnologias/backups',1,NULL,'2026-08-26 15:59:01','2026-08-26 16:21:15'),(114,'freyes','comentario','Nuevo comentario','Andrea Navarro comentó en el ticket TKT-2026-00017: Si','message-circle','blue','/dashboard/mistickets',1,NULL,'2026-08-26 16:24:43','2026-08-26 16:28:58');
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `numeros_empleado`
--

DROP TABLE IF EXISTS `numeros_empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `numeros_empleado` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_empleado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numeros_empleado_numero_empleado_unique` (`numero_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `numeros_empleado`
--

LOCK TABLES `numeros_empleado` WRITE;
/*!40000 ALTER TABLE `numeros_empleado` DISABLE KEYS */;
INSERT INTO `numeros_empleado` VALUES (1,'dsilva','789546','2026-08-24 13:12:39','2026-08-24 13:12:39'),(2,'jhinojosa','256070',NULL,NULL),(3,'freyes','201478','2026-08-25 13:13:34','2026-08-25 13:13:48'),(4,'avargas','134658','2026-08-25 19:01:44','2026-08-25 19:01:44');
/*!40000 ALTER TABLE `numeros_empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oficinas`
--

DROP TABLE IF EXISTS `oficinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oficinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_oficinas_empresa` (`empresa_id`),
  CONSTRAINT `fk_oficinas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oficinas`
--

LOCK TABLES `oficinas` WRITE;
/*!40000 ALTER TABLE `oficinas` DISABLE KEYS */;
INSERT INTO `oficinas` VALUES (1,'Reynosa',1),(2,'Matamoros',1);
/*!40000 ALTER TABLE `oficinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User','jhinojosa','flutter','e871f7e5dfe3063a24911fe604ed916af795186e4c39ee3b99454d1fe7923c84','[\"*\"]','2026-08-26 21:20:43',NULL,'2026-08-26 21:17:03','2026-08-26 21:20:43'),(2,'App\\Models\\User','jhinojosa','flutter','e8339a56a67f4c15f2002b47dc2c6ce256e351cbc1f8ccb1aabd0edc6c7454ff','[\"*\"]',NULL,NULL,'2026-08-26 21:38:52','2026-08-26 21:38:52'),(3,'App\\Models\\User','agarcia','flutter','cfc2c10d37d8863b05019b309d5f2eaced5dc1d789cf75a72d4a524f2ad1f602','[\"*\"]',NULL,NULL,'2026-08-26 21:50:45','2026-08-26 21:50:45'),(4,'App\\Models\\User','jhinojosa','flutter','837035b1ae9bf1d13ba6e82b3be978cd5e773d5fd93b1fddd26119fbb1867d1d','[\"*\"]',NULL,NULL,'2026-08-26 21:51:24','2026-08-26 21:51:24'),(5,'App\\Models\\User','jhinojosa','flutter','a8092052aaf37b06256ce94aa67b2c02055b591bd749d245ca2f79202c0c5339','[\"*\"]',NULL,NULL,'2026-08-26 22:12:13','2026-08-26 22:12:13'),(6,'App\\Models\\User','jhinojosa','flutter','44c21dd7bfc9970094c10a11f21edeff93b8e9ccc066130e16c07cdca9b28ccf','[\"*\"]',NULL,NULL,'2026-08-26 22:12:33','2026-08-26 22:12:33'),(7,'App\\Models\\User','dflores','flutter','61260ee1a313e425c873fdbd52d6601641987653f4b73eac0e61cd7d93d50120','[\"*\"]',NULL,NULL,'2026-08-26 22:13:27','2026-08-26 22:13:27'),(8,'App\\Models\\User','dflores','flutter','badae34103865a86a94ef010b5e39d52a41b8caf7ea8c8d301a9abd96ae8a83d','[\"*\"]',NULL,NULL,'2026-08-26 22:31:18','2026-08-26 22:31:18');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes_cambio`
--

DROP TABLE IF EXISTS `solicitudes_cambio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_cambio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `campo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_actual` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nuevo_valor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `comentario_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revisado_por` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revisado_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_solicitudes_cambio_login` (`login`),
  KEY `idx_solicitudes_cambio_revisado_por` (`revisado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_cambio`
--

LOCK TABLES `solicitudes_cambio` WRITE;
/*!40000 ALTER TABLE `solicitudes_cambio` DISABLE KEYS */;
INSERT INTO `solicitudes_cambio` VALUES (1,'freyes','telefono',NULL,'8741203040','Asignar numero de telefono','pendiente',NULL,NULL,NULL,'2026-08-21 20:52:56','2026-08-21 20:52:56'),(2,'freyes','telefono',NULL,'8795631014','Asignar numero de telefono','rechazada','No','jhinojosa','2026-08-24 16:41:03','2026-08-21 20:54:19','2026-08-24 16:41:03'),(3,'freyes','telefono',NULL,'45789630','Asignar','aprobada','Si','jhinojosa','2026-08-21 21:15:59','2026-08-21 21:06:51','2026-08-21 21:15:59');
/*!40000 ALTER TABLE `solicitudes_cambio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soluciones`
--

DROP TABLE IF EXISTS `soluciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soluciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `problema_solucionado` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `solucion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firma` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_solucion` datetime DEFAULT NULL,
  `nombre_firmante` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_firma` datetime DEFAULT NULL,
  `evidencia` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `solucionado_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_soluciones_ticket_ticket_id` (`ticket_id`),
  KEY `idx_soluciones_ticket_login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soluciones`
--

LOCK TABLES `soluciones` WRITE;
/*!40000 ALTER TABLE `soluciones` DISABLE KEYS */;
INSERT INTO `soluciones` VALUES (1,1,'jhinojosa','1','Se formateo la computadora','firmas/firma_1_6a87d974e4b81.png','2026-08-20 23:51:00','Fernando Reyes','2026-08-20 23:51:00','[]','jhinojosa','2026-08-21 04:52:04','2026-08-21 04:52:04'),(2,7,'jhinojosa','1','Solucion de prueba','firmas/firma_7_6a88b2b9ed442.png','2026-08-21 15:18:00','Fernando Reyes','2026-08-21 15:19:00','[]','jhinojosa','2026-08-21 20:19:05','2026-08-21 20:19:05'),(3,9,'jhinojosa','0','Solucion prueba','firmas/firma_9_6a8c674914855.png','2026-08-24 10:45:00','Fernando Reyes','2026-08-24 10:46:00','[]','jhinojosa','2026-08-24 15:46:17','2026-08-24 15:46:17'),(4,10,'jhinojosa','1','Solucion de prueba 2','firmas/firma_10_6a8c6900191d7.png','2026-08-24 10:53:00','Fernando Reyes','2026-08-24 10:53:00','[]','jhinojosa','2026-08-24 15:53:36','2026-08-24 15:53:36'),(5,4,'jhinojosa','1','Solucion','firmas/firma_4_6a8c6cfdba8ae.png','2026-08-24 11:10:00','Fernando Reyes','2026-08-24 11:10:00','[]','jhinojosa','2026-08-24 16:10:37','2026-08-24 16:10:37'),(6,3,'jhinojosa','1','Solucion','firmas/firma_3_6a8c6de844e81.png','2026-08-24 11:14:00','Fernando Reyes','2026-08-24 11:14:00','[]','jhinojosa','2026-08-24 16:14:32','2026-08-24 16:14:32'),(7,6,'jhinojosa','1','Solucionado','firmas/firma_6_6a8c6f6aaf0ef.png','2026-08-24 11:20:00','Fernando Reyes','2026-08-24 11:20:00','[]','jhinojosa','2026-08-24 16:20:58','2026-08-24 16:20:58'),(8,8,'jhinojosa','1','Solucion','firmas/firma_8_6a8c70aa101a0.png','2026-08-24 11:26:00','Fernando Reyes','2026-08-24 11:26:00','[]','jhinojosa','2026-08-24 16:26:18','2026-08-24 16:26:18'),(9,5,'jhinojosa','1','Solucion','firmas/firma_5_6a8c724330e48.png','2026-08-24 11:32:00','Alejandro Vargas','2026-08-24 11:33:00','[]','jhinojosa','2026-08-24 16:33:07','2026-08-24 16:33:07'),(10,2,'jhinojosa','1','Solucion','firmas/firma_2_6a8c72c591625.png','2026-08-24 11:35:00','Fernando Reyes','2026-08-24 11:35:00','[]','jhinojosa','2026-08-24 16:35:17','2026-08-24 16:35:17'),(11,11,'jhinojosa','1','Solucion','firmas/firma_11_6a8c73d5268f3.png','2026-08-24 11:39:00','Fernando Reyes','2026-08-24 11:39:00','[]','jhinojosa','2026-08-24 16:39:49','2026-08-24 16:39:49'),(12,12,'jhinojosa','0','A','firmas/firma_12_6a8c740a7c459.png','2026-08-24 11:40:00','Fernando Reyes','2026-08-24 11:40:00','[]','jhinojosa','2026-08-24 16:40:42','2026-08-24 16:40:42'),(13,13,'jhinojosa','1','Solucion','firmas/firma_13_6a8ca85747dc5.png','2026-08-24 15:23:00','Fernando Reyes','2026-08-24 15:23:00','[]','jhinojosa','2026-08-24 20:23:51','2026-08-24 20:23:51'),(14,14,'jhinojosa','1','a','firmas/firma_14_6a8cb4eec8ea8.png','2026-08-24 16:17:00','Fernando Reyes','2026-08-24 16:17:00','[]','jhinojosa','2026-08-24 21:17:34','2026-08-24 21:17:34'),(15,15,'jhinojosa','1','A','firmas/firma_15_6a8cc5ff38938.png','2026-08-24 17:30:00','Fernando Reyes','2026-08-24 17:30:00','[]','jhinojosa','2026-08-24 22:30:23','2026-08-24 22:30:23'),(16,16,'jhinojosa','1','Solucion de prueba','firmas/firma_16_6a8d959ebea2e.png','2026-08-25 08:15:00','Fernando Reyes','2026-08-25 08:16:00','[\"evidencia_tickets\\/NCBOZvqJph9JQmM6w69gNxCtIbRXCf4SNPdzwsR2.png\",\"evidencia_tickets\\/oTqHTF9SLk5FlTas7rPW0dtYRluT0J8XQYXMl1mU.png\",\"evidencia_tickets\\/TRMUkw0hwF2qdLGldqgLuWfZteBvPeYMO0fuqf0C.png\",\"evidencia_tickets\\/SEfZQrV501ZkN9tCTpeyKfkzXo1cZOms5waYwRkO.png\"]','jhinojosa','2026-08-25 13:16:14','2026-08-25 13:16:14'),(17,17,'jhinojosa','1','Solucion de prueba','firmas/firma_17_6a8da79b77058.png','2026-08-25 09:32:00','Fernando Reyes','2026-08-25 09:32:00','[\"evidencia_tickets\\/s17w3op6FIpXj0sZlBzg6eA3G13jQTOHnlJChEB4.png\",\"evidencia_tickets\\/2jWfpI8z2ajcfomPFXY1z81fO0rX4DbNCRshGDx2.png\",\"evidencia_tickets\\/fakrPZmXxfC2APkdlfFZdEVz36a2AxFqoE5wefi2.png\",\"evidencia_tickets\\/zvIiO2JrXdO4vBU2hQqaWaatPRwfBhqikjuBt7Dt.png\"]','jhinojosa','2026-08-25 14:32:59','2026-08-25 14:32:59');
/*!40000 ALTER TABLE `soluciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_comentarios`
--

DROP TABLE IF EXISTS `ticket_comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_comentarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `archivo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_comentario_ticket_id_index` (`ticket_id`),
  KEY `ticket_comentario_login_index` (`login`),
  CONSTRAINT `ticket_comentario_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `ticket_u_s` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_comentarios`
--

LOCK TABLES `ticket_comentarios` WRITE;
/*!40000 ALTER TABLE `ticket_comentarios` DISABLE KEYS */;
INSERT INTO `ticket_comentarios` VALUES (1,1,'freyes','a',NULL,'2026-08-21 02:11:09','2026-08-21 02:11:09'),(2,4,'freyes','Hey',NULL,'2026-08-21 05:02:01','2026-08-21 05:02:01'),(3,4,'freyes','hey',NULL,'2026-08-21 05:03:42','2026-08-21 05:03:42'),(4,3,'freyes','Me puedes ayudar',NULL,'2026-08-21 05:06:00','2026-08-21 05:06:00'),(5,4,'freyes','Oye?','comentarios_tickets/7Vi0gI82KmYi1x47ROlNVa6ulNLmqauARVy2ibJI.png','2026-08-21 05:06:27','2026-08-21 05:06:27'),(6,4,'freyes','Si?',NULL,'2026-08-21 05:07:46','2026-08-21 05:07:46'),(7,4,'freyes','Me puedes ayudar?',NULL,'2026-08-21 05:09:23','2026-08-21 05:09:23'),(8,4,'freyes','Si',NULL,'2026-08-21 05:10:17','2026-08-21 05:10:17'),(9,1,'freyes','Hey',NULL,'2026-08-21 05:11:04','2026-08-21 05:11:04'),(10,2,'freyes','Hola, mi computadora no enciende y me urge',NULL,'2026-08-21 05:12:31','2026-08-21 05:12:31'),(11,3,'freyes','Hola, me urge no puedo avanzar',NULL,'2026-08-21 05:13:11','2026-08-21 05:13:11'),(12,1,'freyes','Hola, me urge no puedo avanzar',NULL,'2026-08-21 05:13:36','2026-08-21 05:13:36'),(13,3,'freyes','Holaaa?',NULL,'2026-08-21 05:14:11','2026-08-21 05:14:11'),(14,4,'freyes','p',NULL,'2026-08-21 05:16:27','2026-08-21 05:16:27'),(15,4,'freyes','pi',NULL,'2026-08-21 05:19:06','2026-08-21 05:19:06'),(16,4,'freyes','´pipi',NULL,'2026-08-21 05:19:11','2026-08-21 05:19:11'),(17,4,'freyes','p',NULL,'2026-08-21 05:27:18','2026-08-21 05:27:18'),(18,4,'freyes','kiki',NULL,'2026-08-21 05:31:08','2026-08-21 05:31:08'),(19,4,'freyes','Si',NULL,'2026-08-21 05:38:51','2026-08-21 05:38:51'),(20,4,'freyes','hey',NULL,'2026-08-21 05:45:28','2026-08-21 05:45:28'),(21,4,'freyes','Oye',NULL,'2026-08-21 05:47:09','2026-08-21 05:47:09'),(22,4,'jhinojosa','Si',NULL,'2026-08-21 17:51:50','2026-08-21 17:51:50'),(23,4,'jhinojosa','p',NULL,'2026-08-21 19:38:06','2026-08-21 19:38:06'),(24,4,'jhinojosa','o',NULL,'2026-08-21 19:38:17','2026-08-21 19:38:17'),(25,4,'jhinojosa','a',NULL,'2026-08-21 19:44:31','2026-08-21 19:44:31'),(26,4,'jhinojosa','Okey',NULL,'2026-08-21 19:44:38','2026-08-21 19:44:38'),(27,5,'avargas','Si',NULL,'2026-08-21 19:53:40','2026-08-21 19:53:40'),(28,3,'freyes','Si',NULL,'2026-08-21 19:55:34','2026-08-21 19:55:34'),(29,4,'freyes','Si',NULL,'2026-08-21 20:01:21','2026-08-21 20:01:21'),(30,4,'freyes','Si',NULL,'2026-08-21 20:01:32','2026-08-21 20:01:32'),(31,4,'jhinojosa','No',NULL,'2026-08-21 20:05:25','2026-08-21 20:05:25'),(32,4,'freyes','Sisisi',NULL,'2026-08-21 20:10:02','2026-08-21 20:10:02'),(33,7,'jhinojosa','Si',NULL,'2026-08-24 13:25:45','2026-08-24 13:25:45'),(34,7,'jhinojosa',NULL,'comentarios_tickets/NLMs5n7q2bnJ4GpJjU3oBaBux2rdjEHs23teEAAL.png','2026-08-24 13:25:56','2026-08-24 13:25:56'),(35,12,'jhinojosa','a',NULL,'2026-08-24 16:48:19','2026-08-24 16:48:19'),(36,12,'jhinojosa','a',NULL,'2026-08-24 16:50:18','2026-08-24 16:50:18'),(37,12,'jhinojosa','a',NULL,'2026-08-24 16:50:19','2026-08-24 16:50:19'),(38,12,'freyes','e',NULL,'2026-08-24 16:50:27','2026-08-24 16:50:27'),(39,12,'freyes','e',NULL,'2026-08-24 16:50:30','2026-08-24 16:50:30'),(40,10,'jhinojosa','a',NULL,'2026-08-24 16:57:32','2026-08-24 16:57:32'),(41,10,'jhinojosa','a',NULL,'2026-08-24 16:57:35','2026-08-24 16:57:35'),(42,16,'jhinojosa','A',NULL,'2026-08-25 13:24:01','2026-08-25 13:24:01'),(43,16,'jhinojosa','a',NULL,'2026-08-25 13:29:32','2026-08-25 13:29:32'),(44,16,'jhinojosa','a',NULL,'2026-08-25 13:29:34','2026-08-25 13:29:34'),(45,16,'jhinojosa','a',NULL,'2026-08-25 13:31:06','2026-08-25 13:31:06'),(46,16,'jhinojosa',NULL,'comentarios_tickets/HIVq3eEvSnuodzkmiSSL4csuP6HYXRprjpLVvvaX.png','2026-08-25 13:31:14','2026-08-25 13:31:14'),(47,17,'freyes','o',NULL,'2026-08-25 18:41:16','2026-08-25 18:41:16'),(48,17,'freyes','o',NULL,'2026-08-25 18:41:18','2026-08-25 18:41:18'),(49,17,'jhinojosa','Hola',NULL,'2026-08-25 18:59:42','2026-08-25 18:59:42'),(50,17,'jhinojosa',NULL,'comentarios_tickets/Jfj3kYR5EALeIfRflKalSsmhkXWv3G5Grga6jKFd.png','2026-08-25 18:59:53','2026-08-25 18:59:53'),(51,17,'anavarro','Si',NULL,'2026-08-26 16:24:43','2026-08-26 16:24:43');
/*!40000 ALTER TABLE `ticket_comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_u_s`
--

DROP TABLE IF EXISTS `ticket_u_s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_u_s` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `folio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_falla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prioridad` enum('Critica','Alta','Media','Normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `afecta_otros` tinyint(1) NOT NULL DEFAULT '0',
  `es_recurrente` tinyint(1) NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `evidencia` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `solucion_id` bigint unsigned DEFAULT NULL,
  `tomado_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `informacion_adicional` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fecha_tomado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ticket_us_login` (`login`),
  KEY `idx_ticket_us_solucion_id` (`solucion_id`),
  KEY `idx_ticket_us_tomado_por` (`tomado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_u_s`
--

LOCK TABLES `ticket_u_s` WRITE;
/*!40000 ALTER TABLE `ticket_u_s` DISABLE KEYS */;
INSERT INTO `ticket_u_s` VALUES (1,'TKT-2026-00001','freyes','Impresora no imprime','Equipo','Impresora','Critica','Impresora no imprime',1,0,'a','[\"evidencia_tickets\\/IDxdn41pX4nLais2PLnuO2RpBenEGqzuSmGFRO35.png\"]','2026-08-21 02:04:04','2026-08-21 04:52:04','solucionado',1,'jhinojosa',NULL,'2026-08-20 21:50:55'),(2,'TKT-2026-00002','freyes','Computadora no enciende','Equipo','Laptop - lenovo','Critica','lenovo',0,0,'No','[\"evidencia_tickets\\/43suHNzXmbRVfxfpIB3RfhcsKQxrQWStLX2FhrDr.png\"]','2026-08-21 02:36:14','2026-08-24 16:35:17','solucionado',10,'jhinojosa',NULL,'2026-08-24 11:34:52'),(3,'TKT-2026-00003','freyes','Pc no enciende','Equipo','Pc - Hp','Critica','a',1,0,NULL,'[\"evidencia_tickets\\/Z3RIHVS74kd0RfXAI4L2HiWYzu4k1Yo3ELDjRgDX.png\"]','2026-08-21 02:42:23','2026-08-24 16:14:32','solucionado',6,'jhinojosa',NULL,'2026-08-21 15:11:03'),(4,'TKT-2026-00004','freyes','Pc no enciende','Equipo','Pc - Hp','Critica','a',1,0,NULL,'[\"evidencia_tickets\\/o9ii29YcGJBlMhlVunQeYu4JxWL6w2eyezhk2nYW.png\"]','2026-08-21 02:42:36','2026-08-24 16:10:37','solucionado',5,'jhinojosa',NULL,'2026-08-21 12:51:58'),(5,'TKT-2026-00005','avargas','Ticket de prueba','Equipo','Laptop - Lenovo','Critica','Descripcion',1,1,'a','[\"evidencia_tickets\\/Dxnj2Se3FsZ6PZzCkrdE1dvkip48xgxzD2wn3KQY.png\"]','2026-08-21 19:18:52','2026-08-24 16:33:07','solucionado',9,'jhinojosa',NULL,'2026-08-24 11:32:46'),(6,'TKT-2026-00006','freyes','Ticket de prueba 2','Equipo','Laptop - Lenovo','Critica','Descripcion prueba',1,1,'Si','[\"evidencia_tickets\\/pP9OQendW3SJmDxzFrug8lWv8pHpmjxZvXutNQLi.png\"]','2026-08-21 20:12:10','2026-08-24 16:20:58','solucionado',7,'jhinojosa',NULL,'2026-08-24 11:20:39'),(7,'TKT-2026-00007','freyes','Ticket de prueba 3','Equipo','Laptop - Lenovo','Critica','Descripcion de prueba',1,1,'A','[\"evidencia_tickets\\/CnAzvRDIs4DbdODPmz9MWb0DlNenyHTUtLeqj5jf.png\"]','2026-08-21 20:17:15','2026-08-21 20:19:05','solucionado',2,'jhinojosa',NULL,'2026-08-21 15:18:33'),(8,'TKT-2026-00008','freyes','Impresora de administracion sin conexion','Equipo','Impresora','Critica','Descripcion de prueba',1,0,'Comentario de prueba','[\"evidencia_tickets\\/RaHMby4svqcp7QfmSDPwR6DTCx6g6dNHgekWQkSt.png\"]','2026-08-24 14:36:04','2026-08-24 16:26:18','solucionado',8,'jhinojosa',NULL,'2026-08-24 11:20:25'),(9,'TKT-2026-00009','freyes','Ticket de prueba 5','Equipo','Laptop - Lenovo','Critica','Prueba',1,1,'Prueba','[\"evidencia_tickets\\/CBR6WNWoAfzHLbrkbYpgsxv4R7TOfKZlpiVCJPE1.png\"]','2026-08-24 15:29:23','2026-08-24 15:46:17','cancelado',3,'jhinojosa',NULL,'2026-08-24 10:45:44'),(10,'TKT-2026-00010','freyes','Ticket de prueba 6','Equipo','Laptop - Lenovo','Critica','Descripcion de prueba',1,1,'Comentario de prueba','[\"evidencia_tickets\\/j3zeEo2PwhwB3ss24HVTcmucQhFvmXg2ebCOY6Vw.png\"]','2026-08-24 15:51:21','2026-08-24 15:53:36','solucionado',4,'jhinojosa',NULL,'2026-08-24 10:52:56'),(11,'TKT-2026-00011','freyes','Ticket de prueba numero','Equipo','Laptop - lenovo','Critica','A',1,1,'A','[]','2026-08-24 16:39:06','2026-08-24 16:39:49','solucionado',11,'jhinojosa',NULL,'2026-08-24 11:39:22'),(12,'TKT-2026-00012','freyes','Ticket de prueba numero 2','Equipo','Laptop - lenovo','Critica','a',1,1,'a','[]','2026-08-24 16:40:17','2026-08-24 16:40:42','cancelado',12,'jhinojosa',NULL,'2026-08-24 11:40:25'),(13,'TKT-2026-00013','freyes','Ticket de prueba numero 3','Equipo','Laptop - lenovo','Critica','Descripcion de prueba',1,1,'Prueba','[\"evidencia_tickets\\/vwKyeLzHxScqG8ZHQtqeC7ngtRbHGaPTp2NO73qQ.png\"]','2026-08-24 20:03:11','2026-08-24 20:23:51','solucionado',13,'jhinojosa',NULL,'2026-08-24 15:03:28'),(14,'TKT-2026-00014','freyes','Ticket de prueba numero 5','Equipo','Laptop - lenovo','Critica','a',1,1,'a','[\"evidencia_tickets\\/dKLOhcMiZtVHokGbVMhglCnIki7tQAFh6Eqng4aQ.png\"]','2026-08-24 21:10:17','2026-08-24 21:17:34','solucionado',14,'jhinojosa',NULL,'2026-08-24 16:10:27'),(15,'TKT-2026-00015','freyes','Ticket de prueba numero 6','Redes',NULL,'Critica','A',1,1,'A','[\"evidencia_tickets\\/t5qLMffmiu511CIekYcymUMlZkjXWCKoIn7L7oHU.png\"]','2026-08-24 21:58:15','2026-08-24 22:30:23','solucionado',15,'jhinojosa',NULL,'2026-08-24 17:01:43'),(16,'TKT-2026-00016','freyes','Ticket de prueba numero 15','Equipo','Laptop - lenovo','Critica','Descripcion de prueba',0,0,NULL,'[\"evidencia_tickets\\/tqDVmAg8Gyfc2jXEHbVJo3ZCR9LMDmuffsXWxRgf.png\"]','2026-08-25 13:15:23','2026-08-25 13:16:14','solucionado',16,'jhinojosa',NULL,'2026-08-25 08:15:32'),(17,'TKT-2026-00017','freyes','Ticket de prueba numero 20','Servidor',NULL,'Media','Descripcion',1,0,'Comentario','[\"evidencia_tickets\\/rEa5NVc6pnu72ZonXLHbRhW0nMj1M4Y1SuhSxcaq.png\",\"evidencia_tickets\\/OvhqdKm572UlbnC4NTTzeTfAyJZrwg72PN9DK90w.png\"]','2026-08-25 14:32:02','2026-08-25 14:32:59','solucionado',17,'jhinojosa',NULL,'2026-08-25 09:32:13');
/*!40000 ALTER TABLE `ticket_u_s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pswd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activation_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priv_admin` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mfa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` longblob,
  `role` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pswd_last_updated` timestamp NULL DEFAULT NULL,
  `mfa_last_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('agarcia','$2y$12$YszgEgHrMrl.ApjRjLsHOuleURLMKsgwBFz.B6URih/4CcLB5uwEK','Ana Garcia','ana.garcia@gmail.com','Y',NULL,'N',NULL,NULL,'Soporte Tecnico',NULL,'2026-08-20 23:34:09',NULL),('anavarro','$2y$12$LEHskVxZtulwjEcxsMMiuuOfuzEiZ9MNJcsMzdFVP8oxnOHlqxyHq','Andrea Navarro','andrea.navarro@gmail.com','Y',NULL,'Y',NULL,NULL,'Soporte Tecnico',NULL,'2026-08-21 18:49:14',NULL),('avargas','$2y$12$.wjntjP45Rmhxi3mDpI4HOfcyfn4LxG1lroGioRpnfvK58RqpmgOC','Alejandro Vargas','alejandro.vargas@gmail.com','N',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:17',NULL),('dflores','$2y$12$3r5Ru1mFcq5sFfeeOWboYOlVENFlUJ.cqgge0sfNMtGLdqoQG9Nwq','Diego Flores','diego.flores@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:14',NULL),('dsilva','$2y$12$c1wTj/.keBC4Q9Oud09z2OMqQ9A.QG3Hh1oS6ZMpIQKgrTUP0PBRe','Daniela Silva','daniela.silva@gmail.com','N',NULL,'N',NULL,NULL,'Recursos Humanos',NULL,'2026-08-20 23:34:19',NULL),('freyes','$2y$12$WfXVMfu2XtLB8U29pfSpP.WR9.VHsYVutMoZVwrCsKLLVtwYAG4cu','Fernando Reyes','fernando.reyes@gmail.com','Y',NULL,'N','N',_binary 'profile-photos/ecpEebL6rvi4cV6tVGWmS4oH5hVnAIp5lMUDbRVg.jpg','usuario','6570203054','2026-08-24 15:11:53','2026-08-24 15:11:37'),('gmendoza','$2y$12$qMZD8AZsdNeqcl1WWvvqzOPGszVoG6aMXQ4WFWlDklxOYnv6KtCNi','Gabriela Mendoza','gabriela.mendoza@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:18',NULL),('jhinojosa','$2y$12$ZPn5nk51ye.D6RHZlB1iK.8j5neZQ7TAOC8/Osw3I4UcxmsIOdh72','Jesus Hinojosa','jefehi13@gmail.com','Y',NULL,'Y','N',_binary 'profile-photos/cxBPJiP0viG5yDklfXlMq1QaWXvKnxEyUltJSDvt.jpg','Gerente Ti','8951235410','2026-08-21 14:51:31','2026-08-25 15:29:18'),('jperez','$2y$12$sVg8Gl8YvnIKMDz/AaWuveEbYSRJ4/8ynvXci7dQ439nBZsrHJRyW','Juan Perez','juan.perez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:07',NULL),('lhernandez','$2y$12$0e.mvjCKAjdFude5nVI04Os8F7TEa8RiWHXU.MQQsjpjsHPTcVK3S','Laura Hernandez','laura.hernandez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:14',NULL),('lrodriguez','$2y$12$2O6ncRHmF/JaI/CHDDgpLuuxMVS4lzzdretcvCVD9wL85kEGy.E/O','Luis Rodriguez','luis.rodriguez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:10',NULL),('mgonzalez','$2y$12$8mzxOvlcnf77F9gXK1TSW.ua1FDBQ2Ul0Y7R.Yb6Z3WgedC9MEiaa','Maria Gonzalez','maria.gonzalez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:11',NULL),('mramirez','$2y$12$8YpHwZgzXFsRuaL39cR49.Op0g1vo4PbG35AaMmirkCKn0TE0.u7G','Miguel Ramirez','miguel.ramirez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:13',NULL),('psanchez','$2y$12$oe.dNOBGCNWQIYkoHw3ZmuVVae9xh1.AR/d4A74Qn/yIDDyxntwX2','Pedro Sanchez','pedro.sanchez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:11',NULL),('rmorales','$2y$12$PxhDkZIdGmsLNeYADKZlruDelwDkt0a1lM9cnioiG9EaqHoaUBFiy','Roberto Morales','roberto.morales@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:16',NULL),('rortega','$2y$12$zAq3WsGZFSXqzsH4XbBsKOMsk7s0nP63Dqi.uqhpNQ5/uinu1RnuC','Ricardo Ortega','ricardo.ortega@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:18',NULL),('storres','$2y$12$WhzfJFXsAma4AOsQpKiVCeoTxfpHlkTSJ9cN8872d97cU3/KYtPfO','Sofia Torres','sofia.torres@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:12',NULL),('vcruz','$2y$12$WoSuC95QcADMtjZDdd7TCOljvkliqsHuft2WScH9WSQSC1yBBGEyK','Valeria Cruz','valeria.cruz@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:15',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-26 17:35:17
