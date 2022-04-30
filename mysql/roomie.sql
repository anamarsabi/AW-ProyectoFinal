-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-04-2022 a las 20:28:40
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `roomie`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aficiones`
--

CREATE TABLE `aficiones` (
  `id_aficion` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `aficiones`
--

INSERT INTO `aficiones` (`id_aficion`, `nombre`) VALUES
(1, 'Leer'),
(2, 'Musica'),
(3, 'Deportes'),
(4, 'Aire Libre'),
(5, 'Naturaleza'),
(6, 'Historia'),
(7, 'Fiesta'),
(8, 'Animales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_host` int(11) NOT NULL,
  `mensaje` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallespiso`
--

CREATE TABLE `detallespiso` (
  `id_piso` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detallespiso`
--

INSERT INTO `detallespiso` (`id_piso`, `id_servicio`) VALUES
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(13, 6),
(14, 1),
(14, 4),
(14, 5),
(14, 6),
(15, 2),
(15, 4),
(15, 5),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(16, 5),
(16, 6),
(16, 7),
(17, 3),
(17, 4),
(17, 5),
(17, 6),
(18, 3),
(18, 4),
(18, 5),
(18, 6),
(19, 1),
(19, 4),
(19, 5),
(20, 2),
(20, 3),
(20, 4),
(20, 5),
(20, 6),
(21, 1),
(21, 2),
(21, 3),
(21, 4),
(21, 5),
(21, 6),
(21, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(11) NOT NULL,
  `id_roomie` int(11) DEFAULT NULL,
  `id_piso` int(11) DEFAULT NULL,
  `cama_cm` int(3) NOT NULL,
  `banio_propio` tinyint(1) NOT NULL,
  `precio` int(11) NOT NULL,
  `gastos_incluidos` tinyint(1) DEFAULT NULL,
  `descripcion` varchar(2048) DEFAULT NULL,
  `disponibilidad` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `id_roomie`, `id_piso`, `cama_cm`, `banio_propio`, `precio`, `gastos_incluidos`, `descripcion`, `disponibilidad`) VALUES
(5, 23, 13, 90, 0, 350, 1, 'Habitación de 30m2, muy luminosa y bien aclimatada', '2022-04-19'),
(6, 24, 13, 90, 1, 450, 1, 'Habitación de 50m2, muy luminosa y bien aclimatada, cuenta con baño propio.', '2022-04-20'),
(7, 25, 13, 120, 1, 400, 1, 'Habitación muy luminosa, con baño propio.', '2022-05-05'),
(8, 0, 16, 90, 1, 550, 1, 'Esta habitacion est&aacute; orientada al este, con lo que dispone de gran cantidad de luz natural. Adem&aacute;s cuenta con un ba&ntilde;o privado con plato de ducha. Dispone de armario, escritorio, silla de oficina, cama y ropa de cama.', '2022-04-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_pisos`
--

CREATE TABLE `imagenes_pisos` (
  `id` int(11) NOT NULL,
  `ruta` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `mimeType` varchar(30) NOT NULL,
  `id_piso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `imagenes_pisos`
--

INSERT INTO `imagenes_pisos` (`id`, `ruta`, `nombre`, `mimeType`, `id_piso`) VALUES
(9, '9.jpg', 'piso-3.jpg', 'image/jpeg', 16),
(10, '10.jpg', 'piso-compartido-deco', 'image/jpeg', 16),
(11, '11.jpg', 'piso-compartido-hoga', 'image/jpeg', 16),
(14, '14.jpg', 'habitacion.jpg', 'image/jpeg', 16),
(15, '15.jpg', 'habitacion2.jpg', 'image/jpeg', 16),
(16, '16.jpg', 'baño.jpg', 'image/jpeg', 17),
(17, '17.jpg', 'baño.jpg', 'image/jpeg', 16),
(18, '18.jpg', 'piso-compartido-3.jp', 'image/jpeg', 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pisos`
--

CREATE TABLE `pisos` (
  `id_piso` int(11) NOT NULL,
  `id_host` int(11) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `barrio` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `imagen_fachada` varchar(255) DEFAULT NULL,
  `permite_mascota` tinyint(1) DEFAULT NULL,
  `descripcion_piso` varchar(1024) DEFAULT NULL,
  `precio_max` int(11) DEFAULT NULL,
  `precio_min` int(11) DEFAULT NULL,
  `num_banios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pisos`
--

INSERT INTO `pisos` (`id_piso`, `id_host`, `calle`, `barrio`, `ciudad`, `imagen_fachada`, `permite_mascota`, `descripcion_piso`, `precio_max`, `precio_min`, `num_banios`) VALUES
(13, 30, 'Ciudad de Barcelona n 145', 'Pacifico', 'Madrid', NULL, 1, 'Este piso de 150m2, cuenta con amplias zonas comunes y adem&aacute;s de lo ya especificado, la finca cuenta con piscina y pista de padel.', 0, 0, 2),
(14, 30, 'Plaza catalu&ntilde;a n 12', 'Gracia', 'Barcelona', NULL, 1, 'Este piso esta situado en una c&eacute;ntrica de Barcelona, fue reformado hace 3 a&ntilde;os.', 0, 0, 1),
(15, 30, 'Plaza Espa&ntilde;a n 4', 'Parque Gwell', 'Barcelona', NULL, 1, 'Piso con 3 habitaciones en zona cercana al Parque Gwell. Muy bien comunicado con buses y trenes.', 0, 0, 3),
(16, 27, 'Hacienda de Pavones n 145', 'Vicalvaro', 'Madrid', NULL, 1, 'Piso en zona muy cercana al centro, muy bien conectado mediante metro y autobus.', 0, 0, 2),
(17, 27, 'Cochabamba n 2', 'Colombia', 'Madrid', NULL, 1, 'El piso se encuentra en una lujosa zona, muy tranquila y muy bien conectado con el centro.', 0, 0, 1),
(18, 27, 'Evergreenterrace n 365', 'Las palomas', 'Valencia', NULL, 1, 'Este piso fue reformado hace 3 a&ntilde;os, esta en primera l&iacute;nea de playa y muy bien conectado con el centro.', 0, 0, 2),
(19, 28, 'Fernandez Shaw n 12', 'Pacifico', 'Madrid', NULL, 1, 'Este piso cuenta con amplias zonas comunes y una amplia finca', 0, 0, 2),
(20, 28, 'General Casado n 14', 'Moncloa', 'Madrid', NULL, 1, 'Este piso fue reformado hace 2 a&ntilde;os por lo tanto cuenta con los &uacute;ltimos dise&ntilde;os.', 0, 0, 2),
(21, 28, 'Menendez Pelayo n 1', 'Pacifico', 'Madrid', NULL, 1, 'Este piso esta a 5 minutos de atocha de andando, cuenta con ascensor y unas buenas instalaciones.', 0, 0, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolesusuario`
--

CREATE TABLE `rolesusuario` (
  `id_usuario` int(11) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rolesusuario`
--

INSERT INTO `rolesusuario` (`id_usuario`, `rol`) VALUES
(23, 1),
(24, 1),
(25, 1),
(31, 1),
(27, 2),
(28, 2),
(30, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roomies`
--

CREATE TABLE `roomies` (
  `id_usuario` int(11) NOT NULL,
  `tiene_mascota` tinyint(1) NOT NULL,
  `descripcion` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roomies`
--

INSERT INTO `roomies` (`id_usuario`, `tiene_mascota`, `descripcion`) VALUES
(23, 1, 'Soy una persona agradable, que se dedica a la música y amo los animales'),
(24, 0, 'Soy una persona deportista a la que le encanta la lectura'),
(25, 0, 'Soy un apasionado de las series y las películas, además de una persona tranquila.'),
(31, 0, 'Estoy buscando piso en Madrid para el proximo curso, ya que me mudaré allí para cursar un Máster en la Universidad Complutense de Madrid');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roomie_aficiones`
--

CREATE TABLE `roomie_aficiones` (
  `id_aficion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roomie_aficiones`
--

INSERT INTO `roomie_aficiones` (`id_aficion`, `id_usuario`) VALUES
(8, 23),
(4, 24),
(6, 25),
(2, 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre`) VALUES
(1, 'Basura'),
(2, 'Aire Acondicionado'),
(3, 'Conserje'),
(4, 'Luz'),
(5, 'Agua'),
(6, 'Gas'),
(7, 'Calefacción');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido1` varchar(255) NOT NULL,
  `apellido2` varchar(255) DEFAULT NULL,
  `contrasenia` varchar(2048) NOT NULL,
  `fecha_nacimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `correo`, `nombre`, `apellido1`, `apellido2`, `contrasenia`, `fecha_nacimiento`) VALUES
(23, 'julim123@gmail.com', 'Julian', 'Mu&ntilde;oz', '', '$2y$10$ha9nUWnhH7iMdqzJXZ1K6ezBjjiRaFXfIWoKZYmdhjyiiU6Iai1.m', '2002-02-06'),
(24, 'juanjimenez@gmail.com', 'Juan', 'Jimenez', 'Lopez', '$2y$10$sqNvNjejG4Q4xv43tuOAaet7snAokzPah8s57HPq0PGIsiNhlaacu', '2000-02-25'),
(25, 'dibidi@gmail.com', 'David', 'Herrero', 'Montiel', '$2y$10$Gl7866AXfR65AxcDfyUH4uGuhLNO818ZsJblMu2h3M2/TCW/FG7b.', '2005-07-14'),
(27, 'jluso@gmail.com', 'Jose Luis', 'Casado', 'Herrero', '$2y$10$.WZKZEr4dAMwXbXn482JS.3Tx.u0eiLD.LZmqLSv6uGPAdM/mEK12', '2002-06-07'),
(28, 'gacas@gmail.com', 'Gabriel', 'Hernandez', '', '$2y$10$LjF.aYZ5foykmkmKr7M/8.rOSdF4hKiUHyf2HZhM32LsO9.3uWutq', '1988-07-09'),
(30, 'bmar123@gmail.com', 'Benito', 'Martinez', '', '$2y$10$hVluCkzvsIUCvvnC4DXNw.ouxRc9.g3p9iawon1PE2/vQEPlQhKma', '1993-10-20'),
(31, 'violeta@gmail.com', 'Violeta', 'Mart&iacute;nez', '', '$2y$10$0ejmp2zVE/135HjLw13dXeA5xC7N3wLB5R26GL2PuyPiNwPCP6skm', '2006-03-25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aficiones`
--
ALTER TABLE `aficiones`
  ADD PRIMARY KEY (`id_aficion`);

--
-- Indices de la tabla `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_roomie` (`id_usuario`),
  ADD KEY `id_host` (`id_host`);

--
-- Indices de la tabla `detallespiso`
--
ALTER TABLE `detallespiso`
  ADD KEY `id_piso` (`id_piso`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD UNIQUE KEY `id_usuario` (`id_roomie`) USING BTREE,
  ADD UNIQUE KEY `id_roomie` (`id_roomie`),
  ADD KEY `id_piso` (`id_piso`);

--
-- Indices de la tabla `imagenes_pisos`
--
ALTER TABLE `imagenes_pisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pisos`
--
ALTER TABLE `pisos`
  ADD PRIMARY KEY (`id_piso`),
  ADD KEY `correo_host` (`id_host`);

--
-- Indices de la tabla `rolesusuario`
--
ALTER TABLE `rolesusuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `roomies`
--
ALTER TABLE `roomies`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `roomie_aficiones`
--
ALTER TABLE `roomie_aficiones`
  ADD KEY `id_aficion` (`id_aficion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aficiones`
--
ALTER TABLE `aficiones`
  MODIFY `id_aficion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `imagenes_pisos`
--
ALTER TABLE `imagenes_pisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pisos`
--
ALTER TABLE `pisos`
  MODIFY `id_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `rolesusuario`
--
ALTER TABLE `rolesusuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `roomies` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`id_host`) REFERENCES `pisos` (`id_host`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallespiso`
--
ALTER TABLE `detallespiso`
  ADD CONSTRAINT `detallespiso_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `pisos` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallespiso_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `pisos` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pisos`
--
ALTER TABLE `pisos`
  ADD CONSTRAINT `pisos_ibfk_1` FOREIGN KEY (`id_host`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rolesusuario`
--
ALTER TABLE `rolesusuario`
  ADD CONSTRAINT `rolesusuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `roomies`
--
ALTER TABLE `roomies`
  ADD CONSTRAINT `roomies_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `roomie_aficiones`
--
ALTER TABLE `roomie_aficiones`
  ADD CONSTRAINT `roomie_aficiones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roomie_aficiones_ibfk_3` FOREIGN KEY (`id_aficion`) REFERENCES `aficiones` (`id_aficion`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
