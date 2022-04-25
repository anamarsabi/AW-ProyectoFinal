-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2022 a las 23:03:03
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
(1, 2),
(1, 3),
(1, 5),
(11, 3),
(11, 5),
(11, 6),
(11, 7),
(12, 1),
(12, 2),
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
  `id_usuario` int(11) DEFAULT NULL,
  `id_piso` int(11) DEFAULT NULL,
  `cama_cm` int(3) NOT NULL,
  `banio_propio` tinyint(1) NOT NULL,
  `precio` int(11) NOT NULL,
  `gastos_incluidos` tinyint(1) DEFAULT NULL,
  `descripcion` varchar(1024) DEFAULT NULL,
  `disponibilidad` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `id_usuario`, `id_piso`, `cama_cm`, `banio_propio`, `precio`, `gastos_incluidos`, `descripcion`, `disponibilidad`) VALUES
(1, 1, 11, 85, 1, 450, 0, 'Habitación de 50 metros cuadrados, cuenta con una amplia ventana al exterior y con baño propio', '2022-04-21'),
(2, 15, 12, 90, 1, 550, 0, 'Habitación de 45 metros cuadrados, formada por un balcón, baño propio, una gran cama y un armario empotrado.', '2022-05-22'),
(3, 16, 1, 90, 1, 550, 1, 'Habitación de un gran tamaño que cuenta con un balcón que da a un patio interior, además de contar con un baño propio.', '2022-05-05'),
(4, 17, 1, 120, 0, 450, 1, 'Habitación muy luminosa, que cuenta con una gran ventana que da a un gran patio interior, no dispone de baño propio pero si que cuenta con una gran cama y un armario empotrado.', '2022-04-20'),
(5, 27, 13, 90, 0, 350, 1, 'Habitación de 30m2, muy luminosa y bien aclimatada', '2022-04-19'),
(6, 29, 13, 90, 1, 450, 1, 'Habitación de 50m2, muy luminosa y bien aclimatada, cuenta con baño propio.', '2022-04-20'),
(7, 30, 13, 120, 1, 400, 1, 'Habitación muy luminosa, con baño propio.', '2022-05-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE `imagen` (
  `ruta` varchar(255) NOT NULL,
  `id_piso` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `permite_mascota` tinyint(1) DEFAULT NULL,
  `fotos` varchar(255) DEFAULT NULL,
  `descripcion_piso` varchar(1024) DEFAULT NULL,
  `precio_max` int(11) DEFAULT NULL,
  `precio_min` int(11) DEFAULT NULL,
  `plazas_libres` int(11) DEFAULT NULL,
  `num_banios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pisos`
--

INSERT INTO `pisos` (`id_piso`, `id_host`, `calle`, `barrio`, `ciudad`, `permite_mascota`, `fotos`, `descripcion_piso`, `precio_max`, `precio_min`, `plazas_libres`, `num_banios`) VALUES
(1, 2, 'Calle pajaros N 15', 'Chamberi', 'Madrid', 1, NULL, 'Piso totalmente reformado situado en la 3 planta del edificio, que cuenta con un total de 2 habitaciones, de la cual 1 tiene baño propio pero la otra no. Cuenta con un salón formado por dos grandes balcones que dan a un patio interior y una grán cocina/comedor.', 550, 450, 2, 3),
(11, 6, 'Calle lucero n 13', 'Moncloa', 'Madrid', 0, NULL, 'Piso reformado hace 10 años, cuenta con 1 habitación, 1 salón, 2 baños, 1 cocina. El piso es un bajo que cuenta con una gran iluminación que viene de un patio interior de la comunidad.', 450, 450, 1, 2),
(12, 5, 'Calle Alonso Martinez N 15', 'Principe Pio', 'Valencia', 1, NULL, 'Piso situado en la calle de Alonso Martinez, es un piso situado en una 4 planta, formado por 1 habitación, 1 salon/comedor, ! cocina y 2 baños, de los cuales 1 pertenece a la habitación.', 550, 550, 1, 2),
(13, 30, 'Ciudad de Barcelona n 145', 'Pacifico', 'Madrid', 1, NULL, 'Este piso de 150m2, cuenta con amplias zonas comunes y adem&aacute;s de lo ya especificado, la finca cuenta con piscina y pista de padel.', 0, 0, NULL, 2),
(14, 30, 'Plaza catalu&ntilde;a n 12', 'Gracia', 'Barcelona', 1, NULL, 'Este piso esta situado en una c&eacute;ntrica de Barcelona, fue reformado hace 3 a&ntilde;os.', 0, 0, NULL, 1),
(15, 30, 'Plaza Espa&ntilde;a n 4', 'Parque Gwell', 'Barcelona', 1, NULL, 'Piso con 3 habitaciones en zona cercana al Parque Gwell. Muy bien comunicado con buses y trenes.', 0, 0, NULL, 3),
(16, 27, 'Hacienda de Pavones n 145', 'Vicalvaro', 'Madrid', 1, NULL, 'Piso en zona muy cercana al centro, muy bien conectado mediante metro y autobus.', 0, 0, NULL, 2),
(17, 27, 'Cochabamba n 2', 'Colombia', 'Madrid', 1, NULL, 'El piso se encuentra en una lujosa zona, muy tranquila y muy bien conectado con el centro.', 0, 0, NULL, 1),
(18, 27, 'Evergreenterrace n 365', 'Las palomas', 'Valencia', 1, NULL, 'Este piso fue reformado hace 3 a&ntilde;os, esta en primera l&iacute;nea de playa y muy bien conectado con el centro.', 0, 0, NULL, 2),
(19, 28, 'Fernandez Shaw n 12', 'Pacifico', 'Madrid', 1, NULL, 'Este piso cuenta con amplias zonas comunes y una amplia finca', 0, 0, NULL, 2),
(20, 28, 'General Casado n 14', 'Moncloa', 'Madrid', 1, NULL, 'Este piso fue reformado hace 2 a&ntilde;os por lo tanto cuenta con los &uacute;ltimos dise&ntilde;os.', 0, 0, NULL, 2),
(21, 28, 'Menendez Pelayo n 1', 'Pacifico', 'Madrid', 1, NULL, 'Este piso esta a 5 minutos de atocha de andando, cuenta con ascensor y unas buenas instalaciones.', 0, 0, NULL, 3);

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
(2, 0),
(5, 0),
(6, 0),
(1, 1),
(3, 1),
(4, 1),
(23, 1),
(24, 1),
(25, 1),
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
(1, 0, 'Hola, mi nombre es Pedro, soy de Madrid y actualmente me encuentro estudiando la carrera de ingeniería del Software en la facultad de Informática de la UCM, no soy muy fan de las mascotas, por ello no tengo ninguna, mis dos principales aficiones son la naturaleza y pasar grán parte de mi tiempo libre al aire libre, ya sea en el campo, dando un paseo o en algún parque.'),
(3, 1, 'Hola, mi nombre es David, soy de Valencia, pero actualmente me encuentro estudiando en la periodismo en la universidad Complutense de Madrid. Desde pequeño siempre me han gustado los animales, por lo que cuento con 2 mascotas, siendo estas dos perros. Mi principal afición es la lectura. Soy un chico al que le gusta tener la habitación y el piso colocado, por lo que soy una persona bastante ordenada'),
(4, 0, 'Hola, mi nombre es Pepa, estoy trabajando de dependienta en una cafetería y también soy estudiante de medicina. Soy una persona bastante sociable y ordenada, no me gustan los animales, por lo que no cuento con ninguna mascota.'),
(23, 1, 'Soy una persona agradable, que se dedica a la música y amo los animales'),
(24, 0, 'Soy una persona deportista a la que le encanta la lectura'),
(25, 0, 'Soy un apasionado de las series y las películas, además de una persona tranquila.');

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
(1, 2),
(2, 2),
(4, 1),
(5, 1),
(6, 4),
(1, 3),
(8, 23),
(4, 24),
(6, 25);

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
(1, 'basura'),
(2, 'aire acondicionado'),
(3, 'Conserje'),
(4, 'Luz'),
(5, 'Agua'),
(6, 'Gas'),
(7, 'calefacion');

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
(1, 'Pedro_garcia@gmail.com', 'Pedro', 'Garcia', 'Sanz', 'PedroSanz1', '1986-04-02'),
(2, 'Carlaperez21@gmail.com', 'Carla', 'Perez', 'Roman', 'RomanPerez21', '1965-09-21'),
(3, 'DavidGutierrez@gmail.com', 'David', 'Sanz', 'Gutierrez', 'GutierrezDavid', '1995-09-23'),
(4, 'PepaEncinas@gmail.com', 'Pepa', 'Sanz', 'Encinas', 'Encinas-sanz21', '1965-06-23'),
(5, 'Rodriguez_Madrid@gmail.com', 'Carlos', 'Rodriguez', NULL, 'informaticoRodriguez', '1986-04-02'),
(6, 'esteff_valencia@gmail.com', 'Estefania', 'Roman', NULL, 'valencianaEsteff', '1966-04-05'),
(23, 'julim123@gmail.com', 'Julian', 'Mu&ntilde;oz', '', '$2y$10$ha9nUWnhH7iMdqzJXZ1K6ezBjjiRaFXfIWoKZYmdhjyiiU6Iai1.m', '2002-02-06'),
(24, 'juanjimenez@gmail.com', 'Juan', 'Jimenez', 'Lopez', '$2y$10$sqNvNjejG4Q4xv43tuOAaet7snAokzPah8s57HPq0PGIsiNhlaacu', '2000-02-25'),
(25, 'dibidi@gmail.com', 'David', 'Herrero', 'Montiel', '$2y$10$Gl7866AXfR65AxcDfyUH4uGuhLNO818ZsJblMu2h3M2/TCW/FG7b.', '2005-07-14'),
(27, 'jluso@gmail.com', 'Jose Luis', 'Casado', 'Herrero', '$2y$10$.WZKZEr4dAMwXbXn482JS.3Tx.u0eiLD.LZmqLSv6uGPAdM/mEK12', '2002-06-07'),
(28, 'gacas@gmail.com', 'Gabriel', 'Hernandez', '', '$2y$10$LjF.aYZ5foykmkmKr7M/8.rOSdF4hKiUHyf2HZhM32LsO9.3uWutq', '1988-07-09'),
(30, 'bmar123@gmail.com', 'Benito', 'Martinez', '', '$2y$10$hVluCkzvsIUCvvnC4DXNw.ouxRc9.g3p9iawon1PE2/vQEPlQhKma', '1993-10-20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aficiones`
--
ALTER TABLE `aficiones`
  ADD PRIMARY KEY (`id_aficion`);

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
  ADD UNIQUE KEY `id_usuario` (`id_usuario`) USING BTREE,
  ADD KEY `id_piso` (`id_piso`);

--
-- Indices de la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD PRIMARY KEY (`ruta`),
  ADD KEY `id_piso` (`id_piso`,`id_habitacion`),
  ADD KEY `id_habitacion` (`id_habitacion`);

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
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pisos`
--
ALTER TABLE `pisos`
  MODIFY `id_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `rolesusuario`
--
ALTER TABLE `rolesusuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

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
-- Filtros para la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD CONSTRAINT `imagen_ibfk_1` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `imagen_ibfk_2` FOREIGN KEY (`id_piso`) REFERENCES `pisos` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE;

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
