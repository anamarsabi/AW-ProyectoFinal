-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: vm09.db.swarm.test
-- Tiempo de generación: 12-05-2022 a las 16:07:10
-- Versión del servidor: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versión de PHP: 8.0.15

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
(21, 7),
(48, 1),
(48, 2),
(48, 3),
(48, 4),
(48, 5),
(48, 7);

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `id_roomie`, `id_piso`, `cama_cm`, `banio_propio`, `precio`, `gastos_incluidos`, `descripcion`, `disponibilidad`) VALUES
(5, 23, 13, 90, 0, 350, 1, 'Habitaci&oacute;n de 30m2, muy luminosa y bien aclimatada', '2022-05-14'),
(6, NULL, 13, 90, 1, 450, 1, 'Habitaci&oacute;n de 25m2, muy luminosa y bien aclimatada, cuenta con ba&ntilde;o propio. Ideal para estudiantes o j&oacute;venes trabajadores', '2022-05-14'),
(7, 25, 13, 120, 1, 400, 1, 'Habitación muy luminosa, con baño propio.', '2022-05-05'),
(8, 0, 16, 90, 1, 550, 1, 'Esta habitacion est&aacute; orientada al este, con lo que dispone de gran cantidad de luz natural. Adem&aacute;s cuenta con un ba&ntilde;o privado con plato de ducha. Dispone de armario, escritorio, silla de oficina, cama y ropa de cama.', '2022-05-14'),
(11, NULL, 18, 150, 1, 550, 0, 'Se ofrece habitaci&oacute;n con un gran ventanal de orientaci&oacute;n este. Dispone de armario, mesa de estudio y c&oacute;moda cama doble.', '2022-05-19'),
(12, 36, 17, 90, 1, 300, 0, 'Habitaci&oacute;n ideal para estudiantes o j&oacute;venes trabajadores. Gran escritorio e iluminaci&oacute;n natural', '2022-05-27'),
(19, NULL, 14, 90, 1, 450, 0, 'Habitaci&oacute;n en el coraz&oacute;n de Barcelona. Ideal para estancias cortas de medio curso.', '2022-05-14'),
(20, NULL, 15, 135, 1, 300, 1, 'Habitaci&oacute;n con cama doble y mucha luz natural. No dudes en visitarla.', '2022-05-14'),
(21, NULL, 15, 90, 1, 550, 0, 'Habitaci&oacute;n m&aacute;s amplia del piso, con ventanas Climatit a una calle tranquila. Dispone de una mesa de estudio y armario.', '2022-05-14'),
(22, NULL, 14, 90, 1, 350, 0, 'Habitaci&oacute;n peque&ntilde;a pero confortable. Cuenta con un ba&ntilde;o privado.', '2022-05-14'),
(23, 24, 15, 150, 1, 375, 0, 'Habitaci&oacute;n con mucha luz natural y amplia mesa de trabajo. En las im&aacute;genes est&aacute;n las pertenencias del actual inquilino, no est&aacute;n incluidas.', '2022-05-21'),
(27, NULL, 17, 140, 0, 550, 1, 'Habitaci&oacute;n disponible durante este verano. Muy acogedora y fresca', '2022-05-17'),
(28, NULL, 19, 90, 0, 330, 1, 'Habitaci&oacute;n con todo lo que puede necesitar un estudiante: cama, mesa de estudio y un armario. La ventana da a un patio de manzanas muy amplio y bien ventilado.', '2022-05-15');

--
-- Volcado de datos para la tabla `imagenes_habitaciones`
--

INSERT INTO `imagenes_habitaciones` (`id`, `ruta`, `nombre`, `mimeType`, `id_habitacion`) VALUES
(28, 'habitaciones/28.jpg', 'habitacion.jpg', 'image/jpeg', 5),
(29, 'habitaciones/29.jpg', 'habitacion2.jpg', 'image/jpeg', 6),
(30, 'habitaciones/30.jpg', 'habitacion.jpg', 'image/jpeg', 19),
(31, 'habitaciones/31.jpg', 'habitacion2.jpg', 'image/jpeg', 20),
(32, 'habitaciones/32.jpg', 'habitacion3.jpg', 'image/jpeg', 21),
(33, 'habitaciones/33.jpg', 'baño.jpg', 'image/jpeg', 22),
(35, 'habitaciones/35.jpg', 'habitacion4.jpg', 'image/jpeg', 22),
(36, 'habitaciones/36.jpg', 'armario.jpg', 'image/jpeg', 23),
(37, 'habitaciones/37.jpg', 'habitacion5.jpg', 'image/jpeg', 23),
(38, 'habitaciones/38.jpg', 'habitacion5.jpg', 'image/jpeg', 8),
(39, 'habitaciones/39.jpg', 'habitacion4.jpg', 'image/jpeg', 12),
(40, 'habitaciones/40.jpg', 'habitacion3.jpg', 'image/jpeg', 27);

--
-- Volcado de datos para la tabla `imagenes_pisos`
--

INSERT INTO `imagenes_pisos` (`id`, `ruta`, `nombre`, `mimeType`, `id_piso`) VALUES
(55, 'pisos/55.jpg', 'piso-3.jpg', 'image/jpeg', 13),
(57, 'pisos/57.jpg', 'piso-compartido-hogar-1024x682.jpg', 'image/jpeg', 13),
(58, 'pisos/58.jpg', 'piscina.jpg', 'image/jpeg', 13),
(59, 'pisos/59.jpg', 'baño.jpg', 'image/jpeg', 14),
(60, 'pisos/60.jpg', 'habitacion.jpg', 'image/jpeg', 14),
(61, 'pisos/61.jpg', 'piso-barna.jpg', 'image/jpeg', 15),
(62, 'pisos/62.jpg', 'balcon.jpg', 'image/jpeg', 16),
(63, 'pisos/63.jpg', 'piscina.jpg', 'image/jpeg', 17),
(64, 'pisos/64.jpg', 'piso-compartido-deco.jpg', 'image/jpeg', 17),
(65, 'pisos/65.jpg', 'piso-compartido-3.jpg', 'image/jpeg', 19);

--
-- Volcado de datos para la tabla `pisos`
--

INSERT INTO `pisos` (`id_piso`, `id_host`, `calle`, `barrio`, `ciudad`, `imagen_fachada`, `permite_mascota`, `descripcion_piso`, `precio_max`, `precio_min`, `num_banios`) VALUES
(13, 30, 'Ciudad de Barcelona n 145', 'Pacifico', 'Madrid', NULL, 1, 'Este piso de 150m2, cuenta con amplias zonas comunes y adem&aacute;s de lo ya especificado, la finca cuenta con piscina y pista de padel.', 0, 0, 2),
(14, 30, 'Plaza catalu&ntilde;a n 12', 'Gracia', 'Barcelona', NULL, 1, 'Este piso esta situado en una c&eacute;ntrica de Barcelona, fue reformado hace 3 a&ntilde;os.', 0, 0, 1),
(15, 30, 'Plaza Espa&ntilde;a n 4', 'Parque Gwell', 'Barcelona', NULL, 1, 'Piso con 3 habitaciones en zona cercana al Parque G&uuml;ell. Muy bien comunicado con buses y trenes.', 0, 0, 3),
(16, 27, 'Hacienda de Pavones n 145', 'Vicalvaro', 'Madrid', NULL, 1, 'Piso en zona muy cercana al centro, muy bien conectado mediante metro y autobus.', 0, 0, 2),
(17, 27, 'Cochabamba n 2', 'Colombia', 'Madrid', NULL, 1, 'El piso se encuentra en una lujosa zona, muy tranquila y muy bien conectado con el centro.', 0, 0, 1),
(18, 27, 'Evergreenterrace n 365', 'Las palomas', 'Valencia', NULL, 1, 'Este piso fue reformado hace 3 a&ntilde;os, esta en primera l&iacute;nea de playa y muy bien conectado con el centro.', 0, 0, 2),
(19, 28, 'Fernandez Shaw n 12', 'Pacifico', 'Madrid', NULL, 1, 'Este piso cuenta con amplias zonas comunes y una amplia finca', 0, 0, 2),
(20, 28, 'General Casado n 14', 'Moncloa', 'Madrid', NULL, 1, 'Este piso fue reformado hace 2 a&ntilde;os por lo tanto cuenta con los &uacute;ltimos dise&ntilde;os.', 0, 0, 2),
(21, 28, 'Menendez Pelayo n 1', 'Pacifico', 'Madrid', NULL, 1, 'Este piso esta a 5 minutos de atocha de andando, cuenta con ascensor y unas buenas instalaciones.', 0, 0, 3),
(48, 27, 'Calle Serrano', 'Salamanca', 'Madrid', NULL, 1, 'Piso en el coraz&oacute;n del barrio de Salamanca, rodeado de comercios. Muy cerca de la parada de metro de la Linea 4.', 0, 0, 3);

--
-- Volcado de datos para la tabla `rolesusuario`
--

INSERT INTO `rolesusuario` (`id_usuario`, `rol`) VALUES
(34, 0),
(23, 1),
(24, 1),
(25, 1),
(36, 1),
(27, 2),
(28, 2),
(30, 2);

--
-- Volcado de datos para la tabla `roomies`
--

INSERT INTO `roomies` (`id_usuario`, `tiene_mascota`, `descripcion`) VALUES
(23, 1, 'Soy una persona agradable, que se dedica a la música y amo los animales'),
(24, 0, 'Soy una persona deportista a la que le encanta la lectura'),
(25, 0, 'Soy un apasionado de las series y las películas, además de una persona tranquila.'),
(36, 1, 'Vivo en Madrid por mis estudios. Me encanta cocinar y solemos cenar todos juntos en el piso. Soy una persona tranquila y apasionada por la naturaleza.');

--
-- Volcado de datos para la tabla `roomie_aficiones`
--

INSERT INTO `roomie_aficiones` (`id_aficion`, `id_usuario`) VALUES
(8, 23),
(4, 24),
(6, 25),
(5, 36);

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
(34, 'admin@roomie.es', 'Administrador', 'De Roomie', '', '$2y$10$40q5j0mjL562Pbd9LrcD2OH0tfx1xNA9rtaC7yl2gQrIEmPGbxcbS', '1999-02-28'),
(36, 'violeta@gmail.com', 'Violeta', 'Vega', '', '$2y$10$fpq0O/hPA31Yv9QI6f7fcOkEKRugnMwW/CudTwWmVQaH82H3psMgS', '2005-01-14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
