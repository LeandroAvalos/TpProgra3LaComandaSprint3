-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2022 a las 15:31:23
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
-- Base de datos: `tplacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `codigoMesa` int(11) NOT NULL,
  `codigoComanda` varchar(50) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `pathFoto` varchar(100) DEFAULT NULL,
  `estadoComanda` varchar(50) NOT NULL,
  `precioFinalComanda` int(11) DEFAULT NULL,
  `tiempoEstimado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`id`, `codigoMesa`, `codigoComanda`, `nombreCliente`, `pathFoto`, `estadoComanda`, `precioFinalComanda`, `tiempoEstimado`) VALUES
(1, 46533, 'VKHFY', 'Carlos', NULL, 'Entregado', 1500, 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombreEmpleado` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `cargoEmpleado` varchar(50) NOT NULL,
  `fechaAlta` date NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombreEmpleado`, `usuario`, `clave`, `cargoEmpleado`, `fechaAlta`, `fechaBaja`) VALUES
(1, 'Oscar', 'osky111', '$2y$10$Ve1XikD0D3vvKqeXjbosduowTd5UBf99ZFSGV5k9WVdAQexaxtn3K', 'socio', '2022-11-26', NULL),
(2, 'Roberto', 'roby112', '$2y$10$yStEVXJL4SXsFyvckiRBq.qScF77TGwzbwXqylbFnhaRzxCaUZjWO', 'socio', '2022-11-26', NULL),
(3, 'Mauricio', 'mau113', '$2y$10$aMcpHGfX0HJYAEjUwPrMfeILqVVewl.NMQmN95judJTiNSjM1jgE2', 'socio', '2022-11-26', NULL),
(4, 'Omar', 'omi211', '$2y$10$iM5wHRJmQqEF3bbAzY/C0u2U736b91hdkNiSt9i1gaaOIqsTecRUm', 'mozo', '2022-11-26', NULL),
(5, 'Leonardo', 'leo212', '$2y$10$JwJMnZY1Iel0UGadV/PvVeUdUjwN87qdPN6q2NgjM/pvNpMMh3QT6', 'mozo', '2022-11-26', NULL),
(6, 'Ramon', 'ram213', '$2y$10$gTaXkgJEWKWrejr.myOoDO5xXB267yAmRDg6jZpYTfPgDKEPGs14W', 'mozo', '2022-11-26', NULL),
(7, 'Jesus', 'jes311', '$2y$10$E.y4QKMwGE1EZFH5xBpBX.hjVSxTj/VfJUGKo4jvShNe6udsAsOcC', 'cocina', '2022-11-26', NULL),
(8, 'Ismael', 'isma312', '$2y$10$qUzbMdqkIN5bxZ1Pqn.HXuS.2fL4SbvfXoVS31t1Pro50ZR8.TgNe', 'cocina', '2022-11-26', NULL),
(9, 'Luciano', 'luc411', '$2y$10$bnRB2s3enaCrsW7tFMPpBOu9GonJoEonqicP6G1fuZein0BRQE2GO', 'bartender', '2022-11-26', NULL),
(10, 'Marcos', 'mar412', '$2y$10$T.JMiUznt2HkHHlulyGLMevS5XVj/.W7gTLqCKerdwyZGalIw3Mye', 'bartender', '2022-11-26', NULL),
(11, 'Gabriel', 'gab511', '$2y$10$bbCTBLuLqNn9RCeU8jHe5OpV8.dcaJPCew0kcwBJ70Zt.s0BO.w0i', 'cervecero', '2022-11-26', NULL),
(12, 'Agustin', 'agu512', '$2y$10$Jx894asuhtfWNS0yV.mIKem2AwYcZmrsGv9uIczhqElr3HsjuE2aO', 'cervecero', '2022-11-26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `codigoMesa` int(11) NOT NULL,
  `codigoComanda` varchar(50) NOT NULL,
  `puntajeMesa` int(11) NOT NULL,
  `puntajeRestaurante` int(11) NOT NULL,
  `puntajeMozo` int(11) NOT NULL,
  `puntajeCocinero` int(11) NOT NULL,
  `puntajePromedio` float NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `codigoMesa`, `codigoComanda`, `puntajeMesa`, `puntajeRestaurante`, `puntajeMozo`, `puntajeCocinero`, `puntajePromedio`, `descripcion`) VALUES
(1, 46533, 'VKHFY', 8, 8, 8, 8, 8, 'Muy buena atencion, la comida estuvo rica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `nombreProducto` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL,
  `sectorProducto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `nombreProducto`, `precio`, `sectorProducto`) VALUES
(1, 'Milanesa a Caballo', 1000, 'cocina\r\n'),
(2, 'Hamburguesas de Garbanzo', 700, 'cocina\r\n'),
(3, 'Pollo Frito', 800, 'cocina\r\n'),
(4, 'Cerveza Bicentenario', 500, 'cervecero\r\n'),
(5, 'Cerveza Mundial', 550, 'cervecero\r\n'),
(6, 'Cerveza Quad', 600, 'cervecero\r\n'),
(7, 'Daikiri', 800, 'bartender\r\n'),
(8, 'Whisky', 850, 'bartender\r\n'),
(9, 'Mojito', 750, 'bartender');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `codigoMesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `estado`, `codigoMesa`) VALUES
(1, 'Cerrado', 46533),
(2, 'Cerrado', 96691),
(3, 'Cerrado', 66029),
(4, 'Cerrado', 59527),
(5, 'Cerrado', 59938);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `codigoComanda` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tiempoEstimadoProducto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `idMenu`, `codigoComanda`, `estado`, `tiempoEstimadoProducto`) VALUES
(1, 1, 'VKHFY', 'Entregado', 36),
(2, 4, 'VKHFY', 'Entregado', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
