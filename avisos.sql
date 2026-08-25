-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 25-08-2026 a las 05:52:28
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ticketpro2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avisos`
--

CREATE TABLE `avisos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('mantenimiento','incidente','informativo','general') COLLATE utf8mb4_unicode_ci NOT NULL,
  `importancia` enum('critica','alta','media','normal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `aplica_a` enum('todos','oficina','departamento','usuarios') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `afecta_a` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mostrar_notificaciones` tinyint(1) NOT NULL DEFAULT 1,
  `fijado` tinyint(1) NOT NULL DEFAULT 0,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publicado_por` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `avisos`
--

INSERT INTO `avisos` (`id`, `titulo`, `tipo`, `importancia`, `fecha_inicio`, `fecha_fin`, `aplica_a`, `descripcion`, `afecta_a`, `mostrar_notificaciones`, `fijado`, `archivo`, `publicado_por`, `created_at`, `updated_at`) VALUES
(1, 'Aviso de prueba', 'mantenimiento', 'critica', '2026-08-20 20:05:00', NULL, 'todos', 'Aviso de prueba', '{\"tipo\":\"todos\",\"empresa_id\":1}', 1, 0, 'avisos/XafEl8qgymf2zKHQz8KCrkGwZaHT8bDvRnj2husg.png', 'jhinojosa', '2026-08-21 01:07:48', '2026-08-21 01:07:48'),
(2, 'Aviso de prueba 3', 'mantenimiento', 'media', '2026-08-21 09:13:00', NULL, 'departamento', 'Aviso de prueba', '{\"tipo\":\"departamentos\",\"ids\":[2]}', 1, 0, 'avisos/U0w90psDHuPWhJSha5aH4Bg0yqn2ZEMVMZmefMfz.jpg', 'jhinojosa', '2026-08-21 14:14:12', '2026-08-21 14:14:12'),
(3, 'Aviso de prueba', 'incidente', 'alta', '2026-08-24 21:49:00', NULL, 'todos', 'Contenido de prueba', '{\"tipo\":\"todos\",\"empresa_id\":1}', 1, 0, 'avisos/KhwLQ7QTZR2XVDAvRMYeZ67kVvrW7HiHKe3EH8fK.png', 'jhinojosa', '2026-08-25 02:50:00', '2026-08-25 02:50:00'),
(4, 'Aviso de prueba 18', 'mantenimiento', 'critica', '2026-08-24 22:41:00', NULL, 'oficina', 'Ccontenido del aviso', '{\"tipo\":\"oficina\",\"oficina_id\":2}', 1, 0, 'avisos/F7BVNIRX75psspmpt4LTXwdEcKUAhlWMiwCnUpl5.png', 'jhinojosa', '2026-08-25 03:42:58', '2026-08-25 03:42:58'),
(5, 'Aviso de prueba oficina', 'mantenimiento', 'media', '2026-08-24 22:47:00', NULL, 'oficina', 'Aviso', '{\"tipo\":\"oficinas\",\"ids\":[2]}', 1, 0, 'avisos/hyBjF1K26Mvhk0JlWCx8YfhCa8mTTdS5H4Lr1BYM.png', 'jhinojosa', '2026-08-25 03:48:20', '2026-08-25 03:48:20'),
(6, 'Aviso de prueba oficina reynosa', 'mantenimiento', 'critica', '2026-08-24 22:48:00', NULL, 'oficina', 'aviso', '{\"tipo\":\"oficinas\",\"ids\":[1]}', 1, 0, 'avisos/WbA52WxkFheAlBn79JrX10vOasy099LYnEdmWlqt.png', 'jhinojosa', '2026-08-25 03:49:18', '2026-08-25 03:49:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_avisos_publicado_por` (`publicado_por`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
