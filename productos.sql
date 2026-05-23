-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2026 a las 21:39:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario_seguro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `estado` varchar(20) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `codigo`, `nombre_producto`, `descripcion`, `id_proveedor`, `precio_compra`, `precio_venta`, `stock`, `fecha_registro`, `estado`) VALUES
(10, '7506306213906', 'Desodorante', '', 1, 20.00, 40.00, 41, '2026-05-19 18:57:39', 'activo'),
(11, '7791293036045', 'Desodorante mujer', '', 1, 25.00, 45.00, 10, '2026-05-19 19:04:20', 'activo'),
(12, '7872571592160', 'Libreta', '', 1, 20.00, 35.00, 45, '2026-05-19 19:06:36', 'activo'),
(13, '7506129430924', 'Libreta cuadro chico', '', 1, 20.00, 30.00, 30, '2026-05-19 19:07:48', 'activo'),
(14, '7503024481358', 'Mochila', '', 1, 300.00, 650.00, 25, '2026-05-19 19:10:13', 'activo'),
(15, '7501073839861', 'Peñafiel naranjada', '', 1, 20.00, 35.00, 14, '2026-05-19 21:03:02', 'activo'),
(16, '7501058617866', 'Nescafé Clásico', '', 1, 30.00, 80.00, 15, '2026-05-19 21:04:40', 'activo'),
(17, '7501073830509', 'Peñafiel', '', 1, 15.00, 25.00, 29, '2026-05-19 21:06:14', 'activo'),
(18, '097339000054', 'Valentina', '', 1, 15.00, 25.00, 27, '2026-05-19 21:06:58', 'activo'),
(20, '7501059315570', 'Crema', '', 1, 50.00, 80.00, 6, '2026-05-20 00:22:15', 'activo'),
(21, '17501033958929', 'PediaSure', '', 1, 200.00, 400.00, 46, '2026-05-20 00:28:31', 'activo'),
(23, '7501052472195', 'Catsup', '', 1, 20.00, 30.00, 29, '2026-05-20 12:03:02', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
