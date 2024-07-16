-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3305
-- Tiempo de generación: 16-07-2024 a las 20:58:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `facturacion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarVentaSimple` (IN `p_idusuario` INT, IN `p_codcliente` INT, IN `p_total` DECIMAL(10,2), IN `p_codproducto` INT, IN `p_cantidad` INT)   BEGIN
    DECLARE last_id INT;
    
    -- Insertar en la tabla venta
    INSERT INTO venta (fecha_venta, idusuario, codcliente, total) 
    VALUES (NOW(), p_idusuario, p_codcliente, p_total);
    
    -- Obtener el ID de la venta recién insertada
    SET last_id = LAST_INSERT_ID();
    
    -- Insertar en la tabla detalle_venta
    INSERT INTO detalle_venta (idventa, codproducto, cantidad) 
    VALUES (last_id, p_codproducto, p_cantidad);
    
    -- Actualizar la existencia del producto
    UPDATE producto 
    SET existencia = existencia - p_cantidad 
    WHERE codproducto = p_codproducto;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `cuit` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nombre`, `telefono`, `direccion`, `cuit`) VALUES
(1, 'Pepe SA', 123456, 'calle uno altura uno', 888),
(2, 'Alejandra SA', 47362374, 'calle uno altura dos', 999),
(3, 'JC SA', 23242324, 'calle uno altura tres', 9224234),
(4, 'UCH SA', 333435342, 'calle uno altura cuatro', 2349273242);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `idcompra` int(11) NOT NULL,
  `fecha_compra` date NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `codproveedor` int(11) NOT NULL,
  `idusuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`idcompra`, `fecha_compra`, `codproducto`, `cantidad`, `precio_unitario`, `total`, `codproveedor`, `idusuario`) VALUES
(1, '2024-05-30', 1, 500, 500.00, 250000.00, 1, NULL),
(2, '2024-07-03', 1, 10, 5000.00, 50000.00, 1, NULL),
(3, '2024-07-15', 10, 22, 12.00, 264.00, 2, NULL),
(4, '2024-07-15', 10, 10, 12.00, 120.00, 2, NULL),
(5, '2024-07-16', 10, 20, 12.00, 240.00, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `iddetalle` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`iddetalle`, `idventa`, `codproducto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 3, 0.00),
(2, 2, 2, 2, 0.00),
(5, 5, 1, 1, 0.00),
(6, 6, 2, 2, 0.00),
(7, 7, 2, 5, 0.00),
(8, 8, 2, 5, 0.00),
(9, 9, 1, 1, 0.00),
(11, 11, 1, 1, 0.00),
(12, 12, 1, 2, 0.00),
(13, 13, 1, 1, 0.00),
(14, 14, 1, 1, 0.00),
(15, 15, 1, 1, 0.00),
(16, 16, 1, 1, 0.00),
(18, 18, 2, 12, 0.00),
(19, 19, 2, 5, 0.00),
(20, 20, 1, 2, 0.00),
(22, 22, 1, 10, 0.00),
(23, 23, 1, 1, 0.00),
(26, 26, 1, 10, 0.00),
(27, 27, 2, 5, 0.00),
(28, 28, 2, 1, 0.00),
(30, 30, 2, 2, 0.00),
(31, 31, 8, 2, 0.00),
(32, 32, 2, 1, 0.00),
(34, 38, 2, 1, 0.00),
(35, 44, 9, 1, 0.00),
(36, 49, 9, 1, 0.00),
(37, 50, 10, 2, 0.00),
(38, 51, 2, 1, 0.00),
(39, 52, 10, 1, 0.00),
(40, 53, 1, 1, 0.00),
(41, 54, 10, 28, 0.00),
(42, 55, 1, 1, 0.00);

--
-- Disparadores `detalle_venta`
--
DELIMITER $$
CREATE TRIGGER `actualizar_stock` AFTER INSERT ON `detalle_venta` FOR EACH ROW BEGIN
    UPDATE producto
    SET existencia = existencia - NEW.cantidad
    WHERE codproducto = NEW.codproducto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `nombre_producto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `proveedor`, `precio`, `existencia`, `nombre_producto`) VALUES
(1, 1, 500.00, 470, 'Iphone 10'),
(2, 2, 500.00, 147, 'Smart TV 50 pulgadas'),
(6, 2, 25.00, 150, 'Mouse'),
(7, 3, 300.00, 50, 'Teclado'),
(8, 1, 15.00, 96, 'Apple TV'),
(9, 2, 450.00, 71, 'Tablet 14 pulgadas'),
(10, 2, 12.00, -8, 'Microfono');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `contacto`, `telefono`, `direccion`) VALUES
(1, 'Apple', 'Applencio andres', 789456, 'calle dos altura uno'),
(2, 'Samsung', 'Samsungniano ', 1334323, 'calle dos altura dos '),
(3, 'Sony', 'Sonyenso', 23432452, 'calle dos altura tres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`) VALUES
(1, 'Juan Cruz', 'j@gmail.com', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 1),
(2, 'Martina Gimenez', 'm@gmail.com', 'MartinaG', '4a7d1ed414474e4033ac29ccb8653d9b', 3),
(3, 'Martin Gonzales', 'mgonzales@gmail.com', 'Mgonzales', 'b59c67bf196a4758191e42f76670ceba', 3),
(5, 'conbgo', 'lqui@h.com', 'congo', '443fd8c93d17446bad49472af0e22dc3', 2),
(6, 'Daniel', 'daniel@gmail.com', 'dani', '4a7d1ed414474e4033ac29ccb8653d9b', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL,
  `fecha_venta` date NOT NULL,
  `idusuario` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `codcliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idventa`, `fecha_venta`, `idusuario`, `total`, `codcliente`) VALUES
(1, '2024-05-05', 1, 1500.00, 1),
(2, '2024-05-06', 2, 1000.00, 2),
(3, '2024-05-07', 3, 550.00, 3),
(4, '2024-05-16', 2, 1320.00, 3),
(5, '2024-05-16', 1, 500.00, 1),
(6, '2024-05-16', 3, 1000.00, 4),
(7, '2024-05-20', 2, 550.00, 4),
(8, '2024-05-20', 2, 550.00, 4),
(9, '2024-05-21', 2, 500.00, 4),
(10, '2024-05-30', 2, 220.00, 4),
(11, '2024-05-30', 2, 500.00, 1),
(12, '2024-05-30', 2, 1000.00, 3),
(13, '2024-05-30', 3, 500.00, 2),
(14, '2024-05-30', 3, 500.00, 2),
(15, '2024-05-30', 1, 500.00, 3),
(16, '2024-06-06', 2, 500.00, 1),
(17, '2024-06-06', 3, 3800.00, 1),
(18, '2024-07-01', 5, 6000.00, 1),
(19, '2024-07-03', 1, 2500.00, 2),
(20, '2024-07-03', 5, 1000.00, 1),
(21, '2024-07-03', 6, 1000.00, 2),
(22, '2024-07-04', 3, 5000.00, 3),
(23, '2024-07-04', 1, 500.00, 2),
(24, '2024-07-07', 1, 500.00, 2),
(25, '2024-07-10', 1, 1100.00, 2),
(26, '2024-07-15', 1, 5000.00, 3),
(27, '2024-07-15', 1, 2500.00, 3),
(28, '2024-07-15', 1, 500.00, 2),
(29, '2024-07-15', 1, 1100.00, 3),
(30, '2024-07-15', 1, 1000.00, 2),
(31, '2024-07-15', 1, 30.00, 2),
(32, '2024-07-15', 1, 500.00, 3),
(33, '2024-07-15', 1, 19000.00, 2),
(38, '2024-07-15', 1, 500.00, 2),
(44, '2024-07-15', 1, 450.00, 1),
(49, '2024-07-15', 1, 450.00, 2),
(50, '2024-07-15', 1, 24.00, 2),
(51, '2024-07-15', 1, 500.00, 3),
(52, '2024-07-16', 5, 12.00, 3),
(53, '2024-07-16', 2, 500.00, 2),
(54, '2024-07-16', 5, 336.00, 2),
(55, '2024-07-16', 2, 500.00, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`idcompra`),
  ADD KEY `idx_idusuario` (`idusuario`),
  ADD KEY `idx_codproveedor` (`codproveedor`),
  ADD KEY `idx_codproducto` (`codproducto`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `idx_idventa` (`idventa`),
  ADD KEY `idx_codproducto` (`codproducto`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `idx_proveedor` (`proveedor`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codproveedor`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`),
  ADD KEY `idx_rol` (`rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `idx_idusuario` (`idusuario`),
  ADD KEY `idx_codcliente` (`codcliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `idcompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`codproveedor`) REFERENCES `proveedor` (`codproveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_ibfk_3` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`codproveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`codcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
