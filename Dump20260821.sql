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
  `aplica_a` enum('todos','departamento','usuarios') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'todos',
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `afecta_a` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mostrar_notificaciones` tinyint(1) NOT NULL DEFAULT '1',
  `fijado` tinyint(1) NOT NULL DEFAULT '0',
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publicado_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_avisos_publicado_por` (`publicado_por`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avisos`
--

LOCK TABLES `avisos` WRITE;
/*!40000 ALTER TABLE `avisos` DISABLE KEYS */;
INSERT INTO `avisos` VALUES (1,'Aviso de prueba','mantenimiento','critica','2026-08-20 20:05:00',NULL,'todos','Aviso de prueba','{\"tipo\":\"todos\",\"empresa_id\":1}',1,0,'avisos/XafEl8qgymf2zKHQz8KCrkGwZaHT8bDvRnj2husg.png','jhinojosa','2026-08-21 01:07:48','2026-08-21 01:07:48'),(2,'Aviso de prueba 3','mantenimiento','media','2026-08-21 09:13:00',NULL,'departamento','Aviso de prueba','{\"tipo\":\"departamentos\",\"ids\":[2]}',1,0,'avisos/U0w90psDHuPWhJSha5aH4Bg0yqn2ZEMVMZmefMfz.jpg','jhinojosa','2026-08-21 14:14:12','2026-08-21 14:14:12');
/*!40000 ALTER TABLE `avisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `oficina_id` int NOT NULL,
  `usuario_departamento` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_departamentos_oficina` (`oficina_id`),
  KEY `fk_departamentos_users` (`usuario_departamento`),
  CONSTRAINT `fk_departamentos_oficina` FOREIGN KEY (`oficina_id`) REFERENCES `oficinas` (`id`),
  CONSTRAINT `fk_departamentos_users` FOREIGN KEY (`usuario_departamento`) REFERENCES `users` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'Tecnologias',1,'jhinojosa'),(2,'Administracion',1,'freyes'),(3,'Tecnologias',1,'anavarro'),(4,'Recursos Humanos',1,'avargas');
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (1,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00004: Pc no enciende','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=4',1,NULL,'2026-08-21 02:42:36','2026-08-21 02:47:36'),(2,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00001: Impresora no imprime','ticket','green','http://127.0.0.1:8000/dashboard/tickets/1',1,NULL,'2026-08-21 02:50:55','2026-08-21 04:55:45'),(3,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00001 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/1',1,NULL,'2026-08-21 04:52:04','2026-08-21 04:55:45'),(4,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00001: Hey','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:11:04','2026-08-21 17:52:13'),(5,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00003: Holaaa?','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:14:11','2026-08-21 17:52:13'),(6,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: p','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:16:27','2026-08-21 17:52:13'),(7,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: pi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:19:06','2026-08-21 17:52:13'),(8,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: ´pipi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:19:11','2026-08-21 17:52:13'),(9,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: p','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:27:18','2026-08-21 17:52:13'),(10,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: kiki','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:31:08','2026-08-21 17:52:13'),(11,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:38:51','2026-08-21 17:52:13'),(12,'jhinojosa','comentario','Nuevo comentario','freyes comentó en el ticket TKT-2026-00004: hey','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:45:28','2026-08-21 17:52:13'),(13,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Oye','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 05:47:09','2026-08-21 17:52:13'),(14,'freyes','aviso','Aviso de prueba 3','Aviso de prueba',NULL,NULL,'http://127.0.0.1:8000/dashboard/avisos?2',1,NULL,'2026-08-21 14:14:13','2026-08-21 19:55:13'),(15,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00004: Pc no enciende','ticket','green','http://127.0.0.1:8000/dashboard/tickets/4',1,NULL,'2026-08-21 17:51:58','2026-08-21 19:55:13'),(16,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:21','2026-08-21 21:07:29'),(17,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:21','2026-08-21 20:19:26'),(18,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:32','2026-08-21 21:07:29'),(19,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Si','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:01:32','2026-08-21 20:19:26'),(20,'freyes','comentario','Nuevo comentario','Jesus Hinojosa comentó en el ticket TKT-2026-00004: No','message-circle','blue','http://127.0.0.1:8000/tickets/4/comentarios',1,NULL,'2026-08-21 20:05:25','2026-08-21 20:16:24'),(21,'anavarro','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Sisisi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:10:02','2026-08-21 21:07:29'),(22,'jhinojosa','comentario','Nuevo comentario','Fernando Reyes comentó en el ticket TKT-2026-00004: Sisisi','message-circle','blue','http://127.0.0.1:8000/tecnologias/tickets',1,NULL,'2026-08-21 20:10:02','2026-08-21 20:19:26'),(23,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00003: Pc no enciende','ticket','green','http://127.0.0.1:8000/dashboard/tickets/3',1,NULL,'2026-08-21 20:11:03','2026-08-21 20:16:24'),(24,'jhinojosa','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00007: Ticket de prueba 3','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=7',1,NULL,'2026-08-21 20:17:15','2026-08-21 20:19:26'),(25,'anavarro','ticket_nuevo','Nuevo ticket recibido','El usuario Fernando Reyes creó el ticket TKT-2026-00007: Ticket de prueba 3','ticket','blue','http://127.0.0.1:8000/tecnologias/tickets?ticket=7',1,NULL,'2026-08-21 20:17:15','2026-08-21 21:07:29'),(26,'freyes','ticket','Ticket tomado','El técnico Jesus Hinojosa ha tomado tu ticket #TKT-2026-00007: Ticket de prueba 3','ticket','green','http://127.0.0.1:8000/dashboard/tickets/7',1,NULL,'2026-08-21 20:18:33','2026-08-21 20:19:51'),(27,'freyes','ticket_solucionado','Ticket solucionado','Tu ticket #TKT-2026-00007 fue solucionado correctamente.','check-circle','green','http://127.0.0.1:8000/dashboard/tickets/7',1,NULL,'2026-08-21 20:19:05','2026-08-21 20:19:51'),(28,'anavarro','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/1/aprobar',1,NULL,'2026-08-21 20:52:56','2026-08-21 21:07:29'),(29,'jhinojosa','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/1/aprobar',1,NULL,'2026-08-21 20:52:56','2026-08-21 21:13:45'),(30,'anavarro','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/2/aprobar',1,NULL,'2026-08-21 20:54:19','2026-08-21 21:07:29'),(31,'jhinojosa','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios/2/aprobar',1,NULL,'2026-08-21 20:54:19','2026-08-21 21:13:45'),(32,'anavarro','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios?solicitud=3',1,NULL,'2026-08-21 21:06:51','2026-08-21 21:07:29'),(33,'jhinojosa','solicitud_cambio','Nueva solicitud de cambio','Fernando Reyes solicitó cambiar su teléfono.','user-cog','blue','http://127.0.0.1:8000/tecnologias/cambios?solicitud=3',1,NULL,'2026-08-21 21:06:51','2026-08-21 21:13:45'),(34,'freyes','solicitud_cambio','Solicitud de cambio aprobada','Tu solicitud de cambio fue aprobada por el área de Tecnologías.','check-circle','green',NULL,0,NULL,'2026-08-21 21:15:59','2026-08-21 21:15:59');
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oficinas`
--

DROP TABLE IF EXISTS `oficinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oficinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `empresa_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_oficinas_empresa` (`empresa_id`),
  CONSTRAINT `fk_oficinas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oficinas`
--

LOCK TABLES `oficinas` WRITE;
/*!40000 ALTER TABLE `oficinas` DISABLE KEYS */;
INSERT INTO `oficinas` VALUES (1,'Tecnologias',1);
/*!40000 ALTER TABLE `oficinas` ENABLE KEYS */;
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
INSERT INTO `solicitudes_cambio` VALUES (1,'freyes','telefono',NULL,'8741203040','Asignar numero de telefono','pendiente',NULL,NULL,NULL,'2026-08-21 20:52:56','2026-08-21 20:52:56'),(2,'freyes','telefono',NULL,'8795631014','Asignar numero de telefono','pendiente',NULL,NULL,NULL,'2026-08-21 20:54:19','2026-08-21 20:54:19'),(3,'freyes','telefono',NULL,'45789630','Asignar','aprobada','Si','jhinojosa','2026-08-21 21:15:59','2026-08-21 21:06:51','2026-08-21 21:15:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soluciones`
--

LOCK TABLES `soluciones` WRITE;
/*!40000 ALTER TABLE `soluciones` DISABLE KEYS */;
INSERT INTO `soluciones` VALUES (1,1,'jhinojosa','1','Se formateo la computadora','firmas/firma_1_6a87d974e4b81.png','2026-08-20 23:51:00','Fernando Reyes','2026-08-20 23:51:00','[]','jhinojosa','2026-08-21 04:52:04','2026-08-21 04:52:04'),(2,7,'jhinojosa','1','Solucion de prueba','firmas/firma_7_6a88b2b9ed442.png','2026-08-21 15:18:00','Fernando Reyes','2026-08-21 15:19:00','[]','jhinojosa','2026-08-21 20:19:05','2026-08-21 20:19:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_comentarios`
--

LOCK TABLES `ticket_comentarios` WRITE;
/*!40000 ALTER TABLE `ticket_comentarios` DISABLE KEYS */;
INSERT INTO `ticket_comentarios` VALUES (1,1,'freyes','a',NULL,'2026-08-21 02:11:09','2026-08-21 02:11:09'),(2,4,'freyes','Hey',NULL,'2026-08-21 05:02:01','2026-08-21 05:02:01'),(3,4,'freyes','hey',NULL,'2026-08-21 05:03:42','2026-08-21 05:03:42'),(4,3,'freyes','Me puedes ayudar',NULL,'2026-08-21 05:06:00','2026-08-21 05:06:00'),(5,4,'freyes','Oye?','comentarios_tickets/7Vi0gI82KmYi1x47ROlNVa6ulNLmqauARVy2ibJI.png','2026-08-21 05:06:27','2026-08-21 05:06:27'),(6,4,'freyes','Si?',NULL,'2026-08-21 05:07:46','2026-08-21 05:07:46'),(7,4,'freyes','Me puedes ayudar?',NULL,'2026-08-21 05:09:23','2026-08-21 05:09:23'),(8,4,'freyes','Si',NULL,'2026-08-21 05:10:17','2026-08-21 05:10:17'),(9,1,'freyes','Hey',NULL,'2026-08-21 05:11:04','2026-08-21 05:11:04'),(10,2,'freyes','Hola, mi computadora no enciende y me urge',NULL,'2026-08-21 05:12:31','2026-08-21 05:12:31'),(11,3,'freyes','Hola, me urge no puedo avanzar',NULL,'2026-08-21 05:13:11','2026-08-21 05:13:11'),(12,1,'freyes','Hola, me urge no puedo avanzar',NULL,'2026-08-21 05:13:36','2026-08-21 05:13:36'),(13,3,'freyes','Holaaa?',NULL,'2026-08-21 05:14:11','2026-08-21 05:14:11'),(14,4,'freyes','p',NULL,'2026-08-21 05:16:27','2026-08-21 05:16:27'),(15,4,'freyes','pi',NULL,'2026-08-21 05:19:06','2026-08-21 05:19:06'),(16,4,'freyes','´pipi',NULL,'2026-08-21 05:19:11','2026-08-21 05:19:11'),(17,4,'freyes','p',NULL,'2026-08-21 05:27:18','2026-08-21 05:27:18'),(18,4,'freyes','kiki',NULL,'2026-08-21 05:31:08','2026-08-21 05:31:08'),(19,4,'freyes','Si',NULL,'2026-08-21 05:38:51','2026-08-21 05:38:51'),(20,4,'freyes','hey',NULL,'2026-08-21 05:45:28','2026-08-21 05:45:28'),(21,4,'freyes','Oye',NULL,'2026-08-21 05:47:09','2026-08-21 05:47:09'),(22,4,'jhinojosa','Si',NULL,'2026-08-21 17:51:50','2026-08-21 17:51:50'),(23,4,'jhinojosa','p',NULL,'2026-08-21 19:38:06','2026-08-21 19:38:06'),(24,4,'jhinojosa','o',NULL,'2026-08-21 19:38:17','2026-08-21 19:38:17'),(25,4,'jhinojosa','a',NULL,'2026-08-21 19:44:31','2026-08-21 19:44:31'),(26,4,'jhinojosa','Okey',NULL,'2026-08-21 19:44:38','2026-08-21 19:44:38'),(27,5,'avargas','Si',NULL,'2026-08-21 19:53:40','2026-08-21 19:53:40'),(28,3,'freyes','Si',NULL,'2026-08-21 19:55:34','2026-08-21 19:55:34'),(29,4,'freyes','Si',NULL,'2026-08-21 20:01:21','2026-08-21 20:01:21'),(30,4,'freyes','Si',NULL,'2026-08-21 20:01:32','2026-08-21 20:01:32'),(31,4,'jhinojosa','No',NULL,'2026-08-21 20:05:25','2026-08-21 20:05:25'),(32,4,'freyes','Sisisi',NULL,'2026-08-21 20:10:02','2026-08-21 20:10:02');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_u_s`
--

LOCK TABLES `ticket_u_s` WRITE;
/*!40000 ALTER TABLE `ticket_u_s` DISABLE KEYS */;
INSERT INTO `ticket_u_s` VALUES (1,'TKT-2026-00001','freyes','Impresora no imprime','Equipo','Impresora','Critica','Impresora no imprime',1,0,'a','[\"evidencia_tickets\\/IDxdn41pX4nLais2PLnuO2RpBenEGqzuSmGFRO35.png\"]','2026-08-21 02:04:04','2026-08-21 04:52:04','solucionado',1,'jhinojosa',NULL,'2026-08-20 21:50:55'),(2,'TKT-2026-00002','freyes','Computadora no enciende','Equipo','Laptop - lenovo','Critica','lenovo',0,0,'No','[\"evidencia_tickets\\/43suHNzXmbRVfxfpIB3RfhcsKQxrQWStLX2FhrDr.png\"]','2026-08-21 02:36:14','2026-08-21 02:36:14','pendiente',NULL,NULL,NULL,NULL),(3,'TKT-2026-00003','freyes','Pc no enciende','Equipo','Pc - Hp','Critica','a',1,0,NULL,'[\"evidencia_tickets\\/Z3RIHVS74kd0RfXAI4L2HiWYzu4k1Yo3ELDjRgDX.png\"]','2026-08-21 02:42:23','2026-08-21 20:11:03','en proceso',NULL,'jhinojosa',NULL,'2026-08-21 15:11:03'),(4,'TKT-2026-00004','freyes','Pc no enciende','Equipo','Pc - Hp','Critica','a',1,0,NULL,'[\"evidencia_tickets\\/o9ii29YcGJBlMhlVunQeYu4JxWL6w2eyezhk2nYW.png\"]','2026-08-21 02:42:36','2026-08-21 17:51:58','en proceso',NULL,'jhinojosa',NULL,'2026-08-21 12:51:58'),(5,'TKT-2026-00005','avargas','Ticket de prueba','Equipo','Laptop - Lenovo','Critica','Descripcion',1,1,'a','[\"evidencia_tickets\\/Dxnj2Se3FsZ6PZzCkrdE1dvkip48xgxzD2wn3KQY.png\"]','2026-08-21 19:18:52','2026-08-21 19:18:52','pendiente',NULL,NULL,NULL,NULL),(6,'TKT-2026-00006','freyes','Ticket de prueba 2','Equipo','Laptop - Lenovo','Critica','Descripcion prueba',1,1,'Si','[\"evidencia_tickets\\/pP9OQendW3SJmDxzFrug8lWv8pHpmjxZvXutNQLi.png\"]','2026-08-21 20:12:10','2026-08-21 20:12:10','pendiente',NULL,NULL,NULL,NULL),(7,'TKT-2026-00007','freyes','Ticket de prueba 3','Equipo','Laptop - Lenovo','Critica','Descripcion de prueba',1,1,'A','[\"evidencia_tickets\\/CnAzvRDIs4DbdODPmz9MWb0DlNenyHTUtLeqj5jf.png\"]','2026-08-21 20:17:15','2026-08-21 20:19:05','solucionado',2,'jhinojosa',NULL,'2026-08-21 15:18:33');
/*!40000 ALTER TABLE `ticket_u_s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `login` varchar(255) NOT NULL,
  `pswd` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL,
  `activation_code` varchar(32) DEFAULT NULL,
  `priv_admin` varchar(1) DEFAULT NULL,
  `mfa` varchar(255) DEFAULT NULL,
  `picture` longblob,
  `role` varchar(128) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `pswd_last_updated` timestamp NULL DEFAULT NULL,
  `mfa_last_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('agarcia','$2y$12$YszgEgHrMrl.ApjRjLsHOuleURLMKsgwBFz.B6URih/4CcLB5uwEK','Ana Garcia','ana.garcia@gmail.com','Y',NULL,'N',NULL,NULL,'Soporte Tecnico',NULL,'2026-08-20 23:34:09',NULL),('anavarro','$2y$12$LEHskVxZtulwjEcxsMMiuuOfuzEiZ9MNJcsMzdFVP8oxnOHlqxyHq','Andrea Navarro','andrea.navarro@gmail.com','Y',NULL,'Y',NULL,NULL,'Soporte Tecnico',NULL,'2026-08-21 18:49:14',NULL),('avargas','$2y$12$.wjntjP45Rmhxi3mDpI4HOfcyfn4LxG1lroGioRpnfvK58RqpmgOC','Alejandro Vargas','alejandro.vargas@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:17',NULL),('cmartinez','$2y$12$nou2VK7563O7EjsJXHd3X.HcrvmC5XJmvUwPeP4Co8FMhhjEg3UcO','Carlos Martinez','carlos.martinez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:08',NULL),('dflores','$2y$12$3r5Ru1mFcq5sFfeeOWboYOlVENFlUJ.cqgge0sfNMtGLdqoQG9Nwq','Diego Flores','diego.flores@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:14',NULL),('dsilva','$2y$12$c1wTj/.keBC4Q9Oud09z2OMqQ9A.QG3Hh1oS6ZMpIQKgrTUP0PBRe','Daniela Silva','daniela.silva@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:19',NULL),('fcastillo','$2y$12$xWcwxdi0Vq/H.UOIzGhJwOSRivl4kOK1l978SUXmDwljBTkEgEaRG','Fernanda Castillo','fernanda.castillo@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:17',NULL),('freyes','$2y$12$VEcT0A7EMF7TABbbylBSluExugqDWWGcsdR9G.4f3skle087FPGEi','Fernando Reyes','fernando.reyes@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:20',NULL),('gmendoza','$2y$12$qMZD8AZsdNeqcl1WWvvqzOPGszVoG6aMXQ4WFWlDklxOYnv6KtCNi','Gabriela Mendoza','gabriela.mendoza@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:18',NULL),('jhinojosa','$2y$12$ZPn5nk51ye.D6RHZlB1iK.8j5neZQ7TAOC8/Osw3I4UcxmsIOdh72','Jesus Hinojosa','jefehi13@gmail.com','Y','W2ISXIPPFGGB5JVD','Y','Y',_binary 'profile-photos/agM4pvk9EOGrn9mnli6f2Cj6H5HZqPHyBxw4btXf.jpg','Gerente Ti','1416589014','2026-08-21 14:51:31','2026-08-21 16:57:15'),('jperez','$2y$12$sVg8Gl8YvnIKMDz/AaWuveEbYSRJ4/8ynvXci7dQ439nBZsrHJRyW','Juan Perez','juan.perez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:07',NULL),('lhernandez','$2y$12$0e.mvjCKAjdFude5nVI04Os8F7TEa8RiWHXU.MQQsjpjsHPTcVK3S','Laura Hernandez','laura.hernandez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:14',NULL),('lrodriguez','$2y$12$2O6ncRHmF/JaI/CHDDgpLuuxMVS4lzzdretcvCVD9wL85kEGy.E/O','Luis Rodriguez','luis.rodriguez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:10',NULL),('mgonzalez','$2y$12$8mzxOvlcnf77F9gXK1TSW.ua1FDBQ2Ul0Y7R.Yb6Z3WgedC9MEiaa','Maria Gonzalez','maria.gonzalez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:11',NULL),('mramirez','$2y$12$8YpHwZgzXFsRuaL39cR49.Op0g1vo4PbG35AaMmirkCKn0TE0.u7G','Miguel Ramirez','miguel.ramirez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:13',NULL),('psanchez','$2y$12$oe.dNOBGCNWQIYkoHw3ZmuVVae9xh1.AR/d4A74Qn/yIDDyxntwX2','Pedro Sanchez','pedro.sanchez@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:11',NULL),('rmorales','$2y$12$PxhDkZIdGmsLNeYADKZlruDelwDkt0a1lM9cnioiG9EaqHoaUBFiy','Roberto Morales','roberto.morales@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:16',NULL),('rortega','$2y$12$zAq3WsGZFSXqzsH4XbBsKOMsk7s0nP63Dqi.uqhpNQ5/uinu1RnuC','Ricardo Ortega','ricardo.ortega@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:18',NULL),('storres','$2y$12$WhzfJFXsAma4AOsQpKiVCeoTxfpHlkTSJ9cN8872d97cU3/KYtPfO','Sofia Torres','sofia.torres@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:12',NULL),('vcruz','$2y$12$WoSuC95QcADMtjZDdd7TCOljvkliqsHuft2WScH9WSQSC1yBBGEyK','Valeria Cruz','valeria.cruz@gmail.com','Y',NULL,'N',NULL,NULL,'usuario',NULL,'2026-08-20 23:34:15',NULL);
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

-- Dump completed on 2026-08-21 17:03:31
