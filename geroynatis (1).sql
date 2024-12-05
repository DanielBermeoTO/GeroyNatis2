-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 13:44:43
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
-- Base de datos: `geroynatis`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `agregarFactura` (IN `p_fechaventa` DATE, IN `p_id_estadof` INT, IN `p_productos` JSON)   BEGIN
    DECLARE v_idFactura INT;
    DECLARE v_subtotal INT DEFAULT 0;
    DECLARE v_total DOUBLE DEFAULT 0;
    
    -- Calcular subtotal y total
    DECLARE v_producto JSON;
    DECLARE v_precio INT;
    DECLARE v_iva INT;
    DECLARE v_cantidad INT;

    -- Inserta la factura
    INSERT INTO factura (fechaventa, id_estadof)
    VALUES (p_fechaventa, p_id_estadof);

    -- Obtener el último ID de factura insertado
    SET v_idFactura = LAST_INSERT_ID();

    -- Iterar sobre el array de productos
    SET @i = 0;
    WHILE @i < JSON_LENGTH(p_productos) DO
        SET v_producto = JSON_EXTRACT(p_productos, CONCAT('$[', @i, ']'));
        SET v_cantidad = JSON_UNQUOTE(JSON_EXTRACT(v_producto, '$.cantidad'));
        SET v_precio = (SELECT precio FROM producto WHERE idProducto = JSON_UNQUOTE(JSON_EXTRACT(v_producto, '$.idProducto')));
        SET v_iva = (SELECT iva FROM producto WHERE idProducto = JSON_UNQUOTE(JSON_EXTRACT(v_producto, '$.idProducto')));

        -- Calcular subtotal y total
        SET v_subtotal = v_subtotal + (v_precio * v_cantidad);
        SET v_total = v_total + ((v_precio * v_cantidad) * (1 + (v_iva / 100)));

        -- Insertar en detalle_factura
        INSERT INTO detalle_factura (FacturaidFactura, ProductoidProducto, valorunitario, cantidad)
        VALUES (v_idFactura, JSON_UNQUOTE(JSON_EXTRACT(v_producto, '$.idProducto')), v_precio, v_cantidad);

        SET @i = @i + 1;
    END WHILE;

    -- Actualizar la factura con subtotal y total
    UPDATE factura SET subtotal = v_subtotal, total = v_total WHERE idFactura = v_idFactura;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_contrasena_descifrada` (IN `clave` VARCHAR(20))   BEGIN
    SELECT documento, AES_DECRYPT(`contrasena`, clave) AS contrasena_descifrada 
    FROM sesion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `productos_talla` (IN `letra` CHAR(1))   SELECT * FROM producto WHERE Talla LIKE concat('%',letra,'%')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_usuario_sesion` (IN `p_documento` INT, IN `p_tipoDocumento` VARCHAR(50), IN `p_nombre` VARCHAR(255), IN `p_apellido` VARCHAR(255), IN `p_direccion` VARCHAR(255), IN `p_localidad` VARCHAR(100), IN `p_telefono` VARCHAR(20), IN `p_correo` VARCHAR(255), IN `p_id_estado` INT, IN `p_id_rol` INT, IN `p_contrasena` VARCHAR(255))   BEGIN
    -- Verificar si el documento ya existe en la tabla usuario
    IF NOT EXISTS (SELECT 1 FROM usuario WHERE documento = p_documento) THEN
        -- Insertar en la tabla usuario
        INSERT INTO usuario (
            documento, correo, tipoDocumento, nombre, apellido, direccion, localidad, id_estado,idrol, telefono
        ) 
        VALUES (
            p_documento, p_correo, p_tipoDocumento, p_nombre, p_apellido, p_direccion, p_localidad, p_id_estado,p_id_rol, p_telefono
        );
    END IF;

    -- Verificar si el documento ya existe en la tabla sesion
    IF NOT EXISTS (SELECT 1 FROM sesion WHERE documento = p_documento) THEN
        -- Insertar en la tabla sesion
        INSERT INTO sesion (
            documento, contrasena
        ) 
        VALUES (
            p_documento, AES_ENCRYPT(p_contrasena, 'empanadaslomejor4')
        );
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tipo_documento` (IN `letra` CHAR(2))   SELECT * FROM usuario WHERE tipoDocumento LIKE concat('%',letra,'%')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `validar_usuario` (IN `p_correo` VARCHAR(255), IN `p_contrasena` VARCHAR(255))   BEGIN
    DECLARE usuario_existente INT DEFAULT 0;
    DECLARE contrasena_almacenada VARBINARY(255);

    -- Verificar si el usuario existe y obtener la contraseña almacenada
    SELECT COUNT(*), 
           (SELECT contrasena 
            FROM sesion 
            WHERE documento = (SELECT documento FROM usuario WHERE correo = p_correo))
    INTO usuario_existente, contrasena_almacenada
    FROM usuario
    WHERE correo = p_correo;

    IF usuario_existente = 0 THEN
        SELECT 'Usuario no encontrado' AS mensaje;
    END IF;

    -- Comparar la contraseña
    IF contrasena_almacenada IS NULL THEN
        SELECT 'Contraseña incorrecta' AS mensaje;
    ELSEIF contrasena_almacenada = AES_ENCRYPT(p_contrasena, 'empanadaslomejor4') THEN
        SELECT 'Login exitoso' AS mensaje;
    ELSE
        SELECT 'Contraseña incorrecta' AS mensaje;
    END IF;

END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `descifrar_contrasena` (`id_sesion` INT, `clave` VARCHAR(11)) RETURNS VARCHAR(255) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    DECLARE resultado VARCHAR(255);
    
    SELECT AES_DECRYPT(`contrasena`, clave) INTO resultado 
    FROM sesion
    WHERE documento = documento;
    
    RETURN resultado;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_bit` int(11) NOT NULL,
  `accion` varchar(200) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_bit`, `accion`, `fecha`) VALUES
(1, 'Se creo un nuevo usuario', NULL),
(2, 'Se creo un nuevo usuario', NULL),
(3, 'Se creo un nuevo usuario', NULL),
(4, 'Se creo un nuevo usuario', '2024-08-22 06:59:16'),
(5, 'Se actuaizo un dato del usuario', '2024-08-22 07:02:46'),
(6, 'Se actuaizo un dato del usuario', '2024-08-22 07:05:09'),
(7, 'Se actuaizo un dato del usuario que documento es:1578623046', '2024-08-22 07:18:00'),
(8, 'se eliminno un usuario con el documento:1028483367', '2024-08-22 07:27:08'),
(9, 'Se actuaizo un dato del usuario que documento es:13122972', '2024-08-22 07:57:39'),
(10, 'Se creo un nuevo usuario', '2024-09-05 15:39:37'),
(11, 'se eliminno un usuario con el documento:1003094893', '2024-09-05 15:50:14'),
(12, 'Se creo un nuevo usuario', '2024-09-05 15:53:09'),
(13, 'Se creo un nuevo usuario', '2024-09-05 16:14:40'),
(14, 'Se creo un nuevo usuario', '2024-09-18 06:30:59'),
(15, 'Se creo un nuevo usuario', '2024-09-18 06:32:44'),
(16, 'Se creo un nuevo usuario', '2024-09-18 06:37:45'),
(17, 'Se creo un nuevo usuario', '2024-09-18 06:52:52'),
(18, 'Se creo un nuevo usuario', '2024-09-18 07:04:03'),
(19, 'se eliminno un usuario con el documento:321654984', '2024-09-18 07:04:43'),
(20, 'se eliminno un usuario con el documento:456987855', '2024-09-18 07:04:45'),
(21, 'se eliminno un usuario con el documento:145698225', '2024-09-18 07:04:47'),
(22, 'se eliminno un usuario con el documento:79456135', '2024-09-18 07:04:50'),
(23, 'Se creo un nuevo usuario', '2024-09-18 07:10:24'),
(24, 'Se creo un nuevo usuario', '2024-09-18 07:16:29'),
(25, 'Se creo un nuevo usuario', '2024-09-18 07:19:18'),
(26, 'Se creo un nuevo usuario', '2024-09-18 07:21:30'),
(27, 'Se creo un nuevo usuario', '2024-09-18 07:37:23'),
(28, 'Se creo un nuevo usuario', '2024-09-18 07:45:44'),
(29, 'Se creo un nuevo usuario', '2024-09-18 07:52:24'),
(30, 'se eliminno un usuario con el documento:5454', '2024-09-18 08:07:20'),
(31, 'se eliminno un usuario con el documento:1515518', '2024-09-18 08:07:29'),
(32, 'se eliminno un usuario con el documento:21542', '2024-09-18 08:07:33'),
(33, 'se eliminno un usuario con el documento:454564864', '2024-09-18 08:08:08'),
(34, 'Se actuaizo un dato del usuario que documento es:1022847832', '2024-09-18 09:00:39'),
(35, 'Se actuaizo un dato del usuario que documento es:1022847832', '2024-09-18 09:02:31'),
(36, 'Se creo un nuevo usuario', '2024-09-18 09:54:13'),
(37, 'Se creo un nuevo usuario', '2024-10-02 09:37:59'),
(38, 'Se creo un nuevo usuario', '2024-10-06 18:14:42'),
(39, 'Se actuaizo un dato del usuario que documento es:1140914077', '2024-10-06 18:32:58'),
(40, 'Se creo un nuevo usuario', '2024-10-06 19:12:33'),
(41, 'Se creo un nuevo usuario', '2024-10-06 19:18:04'),
(42, 'Se creo un nuevo usuario', '2024-10-06 19:23:01'),
(43, 'Se actuaizo un dato del usuario que documento es:2147483647', '2024-10-15 14:03:26'),
(44, 'Se actuaizo un dato del usuario que documento es:7890', '2024-10-15 14:03:49'),
(45, 'Se actuaizo un dato del usuario que documento es:7890', '2024-10-15 16:35:13'),
(46, 'Se actuaizo un dato del usuario que documento es:1003094895', '2024-10-15 17:13:59'),
(47, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 19:47:26'),
(48, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:20:58'),
(49, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:21:52'),
(50, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:22:20'),
(51, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:22:35'),
(52, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:22:52'),
(53, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:23:24'),
(54, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:23:37'),
(55, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:24:23'),
(56, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:24:47'),
(57, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:25:06'),
(58, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:39:22'),
(59, 'Se actuaizo un dato del usuario que documento es:2147483647', '2024-10-17 20:53:10'),
(60, 'Se actuaizo un dato del usuario que documento es:7890', '2024-10-17 20:53:19'),
(61, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:53:25'),
(62, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:53:27'),
(63, 'Se actuaizo un dato del usuario que documento es:213234', '2024-10-17 20:53:32'),
(64, 'Se actuaizo un dato del usuario que documento es:7890', '2024-10-17 20:53:34'),
(65, 'Se creo un nuevo usuario', '2024-10-17 21:06:58'),
(66, 'Se creo un nuevo usuario', '2024-10-17 21:17:22'),
(67, 'Se creo un nuevo usuario', '2024-10-17 21:25:00'),
(68, 'Se creo un nuevo usuario', '2024-10-17 21:39:05'),
(69, 'Se actuaizo un dato del usuario que documento es:1140914079', '2024-11-19 07:07:04'),
(70, 'Se actuaizo un dato del usuario que documento es:1140914077', '2024-11-19 07:07:12'),
(71, 'Se actuaizo un dato del usuario que documento es:1140914079', '2024-11-19 07:07:21'),
(72, 'Se actuaizo un dato del usuario que documento es:213234', '2024-11-19 07:13:38'),
(73, 'Se actuaizo un dato del usuario que documento es:213234', '2024-11-19 07:13:41'),
(74, 'Se actuaizo un dato del usuario que documento es:213234', '2024-11-19 07:13:47'),
(75, 'Se creo un nuevo usuario', '2024-11-19 07:14:19'),
(76, 'Se creo un nuevo usuario', '2024-11-19 07:59:25'),
(77, 'Se actuaizo un dato del usuario que documento es:1080180837', '2024-11-19 07:59:39'),
(78, 'Se actuaizo un dato del usuario que documento es:1080180837', '2024-11-19 07:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(10) NOT NULL COMMENT 'Número que identifica una categoria',
  `categoria` varchar(15) NOT NULL COMMENT 'Nombre o pequeña descripción de la categoria'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `categoria`) VALUES
(1, 'Hombre'),
(2, 'Niño'),
(3, 'Mujer'),
(4, 'Niña'),
(5, 'Disfraz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `FacturaidFactura` int(10) NOT NULL COMMENT 'Llave foranea que relaciona el id del producto con el detalle de la factura',
  `ProductoidProducto` int(10) NOT NULL COMMENT 'Llave foranea que relaciona el id del producto con el detalle de la factura',
  `valorunitario` int(15) NOT NULL COMMENT 'Valor unitario de la prenda',
  `cantidad` int(10) NOT NULL COMMENT 'Cantidad de productos del cliente',
  `cliente` int(11) NOT NULL COMMENT 'Número de documento del cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`FacturaidFactura`, `ProductoidProducto`, `valorunitario`, `cantidad`, `cliente`) VALUES
(1, 14, 25000, 2, 0),
(2, 15, 25000, 1, 0),
(3, 17, 55000, 1, 0),
(4, 16, 35000, 3, 0),
(5, 14, 25000, 1, 0),
(5, 15, 25000, 2, 0),
(9, 15, 25000, 7, 4527),
(10, 15, 25000, 7, 4527),
(11, 14, 25000, 1, 8747),
(11, 24, 9000, 2, 8747),
(12, 14, 25000, 1, 8747),
(12, 24, 9000, 2, 8747),
(13, 14, 25000, 1, 8747),
(13, 24, 9000, 2, 8747),
(14, 16, 55000, 4, 5257),
(14, 25, 20000, 8, 5257),
(15, 14, 25000, 2, 152354254),
(16, 15, 25000, 1, 2054254),
(16, 25, 20000, 2, 2054254),
(17, 14, 25000, 2, 45272),
(18, 16, 55000, 2, 65415),
(18, 24, 9000, 3, 65415),
(19, 19, 45000, 1, 9654984),
(20, 19, 45000, 1, 9654984),
(21, 14, 25000, 10, 123),
(22, 16, 55000, 2, 145546),
(22, 18, 45000, 4, 145546),
(23, 16, 55000, 23, 23456),
(23, 24, 9000, 5, 23456),
(23, 29, 100000, 8, 23456),
(24, 30, 9000, 3, 232432);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `idestado` int(11) NOT NULL,
  `tiposestados` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`idestado`, `tiposestados`) VALUES
(1, 'PAGO'),
(2, 'NOPAGO'),
(3, 'ACTIVO'),
(4, 'INACTIVO'),
(5, 'AÑADIDO'),
(6, 'MOVIMIENTO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idFactura` int(10) NOT NULL COMMENT 'Número identificador de una factura',
  `fechaventa` date NOT NULL COMMENT 'Fecha en que se hizo la venta',
  `subtotal` int(20) DEFAULT NULL COMMENT 'Subtotal de la venta (suma del valor el precio unitario de los productos)',
  `total` double DEFAULT NULL COMMENT 'Total de la venta (suma del valor el precio unitario de los productos + iva)',
  `id_estadof` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`idFactura`, `fechaventa`, `subtotal`, `total`, `id_estadof`, `usuario`) VALUES
(1, '2024-08-12', 50000, 60000, 1, 321564),
(2, '2024-08-12', 25000, 30000, 1, 1140914077),
(3, '2024-08-13', 55000, 60500, 1, 1140914077),
(4, '2024-08-14', 105000, 123900, 1, 723459876),
(5, '2024-08-15', 75000, 90000, 1, 213234),
(6, '2024-08-15', 49500, 99000, 2, NULL),
(8, '2024-08-12', NULL, NULL, 1, NULL),
(9, '2024-09-28', NULL, NULL, 1, NULL),
(10, '2024-09-28', NULL, NULL, 1, NULL),
(11, '2024-09-28', NULL, NULL, 1, NULL),
(12, '2024-09-28', NULL, NULL, 1, NULL),
(13, '2024-09-28', NULL, NULL, 1, NULL),
(14, '2024-09-28', NULL, NULL, 1, NULL),
(15, '2024-09-28', 50000, NULL, 1, NULL),
(16, '2024-09-28', 65000, NULL, 1, NULL),
(17, '2024-09-28', 50000, 60000, 1, NULL),
(18, '2024-09-28', 137000, 158420, 1, NULL),
(19, '2024-09-29', 45000, 49500, 1, NULL),
(20, '2024-09-29', 45000, 49500, 1, NULL),
(21, '2024-10-03', 250000, 300000, 1, NULL),
(22, '2024-10-03', 290000, 327800, 2, NULL),
(23, '2024-11-19', 2110000, 2372400, 1, NULL),
(24, '2024-11-20', 27000, 33480, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proceso`
--

CREATE TABLE `proceso` (
  `idProceso` int(10) NOT NULL COMMENT 'Número identificador de un proceso',
  `entradaproducto` int(10) NOT NULL COMMENT 'Cantidad de productos que llegan a la microempresa',
  `fecha_entrada` date NOT NULL COMMENT 'Fecha de la entrada de un producto',
  `ProductoidProducto` int(10) NOT NULL COMMENT 'Llave foranea que relaciona el id de un producto con un proceso',
  `ProveedoridProveedor` int(10) NOT NULL COMMENT 'Llave foranea que relaciona el id de un proveedor con un proceso',
  `total` int(11) DEFAULT NULL,
  `anadido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proceso`
--

INSERT INTO `proceso` (`idProceso`, `entradaproducto`, `fecha_entrada`, `ProductoidProducto`, `ProveedoridProveedor`, `total`, `anadido`) VALUES
(1, 12, '2024-01-23', 14, 2, 300000, 5),
(2, 23, '2024-02-02', 17, 1, 1265000, 6),
(3, 12, '2024-01-23', 16, 4, 1155000, 6),
(4, 30, '2024-06-15', 15, 3, 750000, 6),
(5, 12, '2024-04-28', 18, 6, 540000, 6),
(6, 12, '2024-01-28', 19, 6, 540000, 6),
(7, 1, '2024-09-01', 14, 1, 25000, 6),
(8, 10, '2024-10-05', 14, 1, 250000, 6),
(9, 10, '2024-10-04', 27, 2, 20000, 6),
(10, 3, '2024-10-05', 26, 2, 13695, 6),
(11, 14, '2024-10-05', 29, 3, 1400000, 5),
(12, 3, '2024-11-19', 30, 6, 27000, 5),
(13, 44, '2024-11-19', 31, 5, 28782864, 5),
(14, 1, '2024-11-19', 29, 9, 100000, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idProducto` int(10) NOT NULL COMMENT 'Identificador unico de cada producto',
  `nombreproducto` varchar(15) NOT NULL COMMENT 'Nombre del producto',
  `cantidadp` int(10) NOT NULL COMMENT 'Cantidad de los productos disponibles',
  `precio` int(20) NOT NULL COMMENT 'Precio del producto',
  `color` varchar(10) NOT NULL COMMENT 'Color de la prenda',
  `iva` int(10) NOT NULL COMMENT 'Porcentaje iva',
  `imagen` varchar(30) DEFAULT NULL,
  `CategoriaidCategoria` int(10) NOT NULL COMMENT 'Llave foranea que relaciona el id de una categoria con un producto',
  `id_estado` int(11) DEFAULT NULL,
  `talla` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `nombreproducto`, `cantidadp`, `precio`, `color`, `iva`, `imagen`, `CategoriaidCategoria`, `id_estado`, `talla`) VALUES
(14, 'kmisa', 15, 25000, 'Morado', 23, '../Imagenes/puño.gif', 1, 4, 1),
(15, 'Camisa', 9, 25000, 'Azul', 20, '../Imagenes/puño.gif', 3, 4, 3),
(16, 'Lamisa ', -5, 55000, 'Negro', 18, '../Imagenes/sonic.jpg', 1, 3, 5),
(17, 'Camiseta ', 15, 55000, 'Blanco', 10, '', 1, 4, 3),
(18, 'Camiseta', 26, 45000, 'Mosado', 10, '', 1, 3, 1),
(19, 'Camisa', 29, 45000, 'Ezul', 10, '', 2, 4, 5),
(20, 'Pirata', 2, 50000, 'Negro', 10, '', 1, 3, 4),
(23, 'Deisy', 3, 40000, 'negraaaa', 3, '', 1, 3, 3),
(24, 'Jonny', -4, 9000, 'blanco', 6, '', 1, 3, 4),
(25, 'Harold', 1, 20000, 'Blanco', 4, '', 1, 4, 5),
(26, 'Felipe', 6, 4565, 'Blanco', 3, '../Imagenes/sonic.png', 1, 4, 5),
(27, 'Lilley', 18, 2000, 'Blanca', 3, '../Imagenes/gofit.png', 4, 3, 1),
(29, 'Silver', 7, 100000, 'Dorado', 4, '../Imagenes/silver.jpg', 1, 3, 5),
(30, 'Unico', 0, 9000, 'dfgds', 24, '../Imagenes/puño.gif', 1, 3, 2),
(31, 'Pirobos', 44, 654156, 'wert', 43, NULL, 3, 3, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `idProveedor` int(10) NOT NULL COMMENT 'Número dentifiicador de cada proveedor',
  `nombreproveedor` varchar(20) NOT NULL COMMENT 'Nombre del proveedor',
  `Telefono` varchar(10) NOT NULL COMMENT 'Número de teléfono del proveedor',
  `productos` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`idProveedor`, `nombreproveedor`, `Telefono`, `productos`) VALUES
(1, 'Julian Camacha', '3015558965', 24),
(2, 'Andres Capera', '3215556699', 23),
(3, 'Camila Espitia', '2147483647', 14),
(4, 'Andrea Jimenez', '2147483647', 15),
(5, 'Julian Basto', '2147483647', 18),
(6, 'Jose Buitrago', '2147483647', 16),
(9, 'Deisy Buena', '3025415277', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(5) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesion`
--

CREATE TABLE `sesion` (
  `documento` int(13) NOT NULL,
  `contrasena` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `token` char(32) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sesion`
--

INSERT INTO `sesion` (`documento`, `contrasena`, `token`, `token_expiry`) VALUES
(7890, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(213234, 'i', NULL, NULL),
(321564, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(3255654, '÷šPa&¼§û	¥œZL.', NULL, NULL),
(52433567, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(53359485, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(67345870, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(67345878, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(115789632, '‰}Yd+9‘zG4£÷ÜóÚ', NULL, NULL),
(321654984, '8X0Þïëãvø Ë‘ÜÓ³ŽŒË	)g;ú~ÔŠZ', NULL, NULL),
(369856985, '¶KpeÉmiV° <=\0', NULL, NULL),
(1003094893, 'sldjf', NULL, NULL),
(1003094895, '©P]ÙóŒaÝØƒ BÚÈÎ,#ùH\\„Y2V¡ƒ`', NULL, NULL),
(1022847832, '»G‚,˜\n6î^~_iÏQÏêèe²:ˆù†§ËKî¼²', NULL, NULL),
(1033698803, 'žMµ\"^©ÆéUžü$àr', NULL, NULL),
(1080180837, 'ÿöxã°DÐc@ZTL—', NULL, NULL),
(1140914077, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(1140914079, 'Úºÿ!½iÄ\\t×ì®XõÚ', NULL, NULL),
(1234567899, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL),
(2147483647, 'r¬çc¨v˜ÑÓ$«§ðHF', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla`
--

CREATE TABLE `talla` (
  `idtalla` int(11) NOT NULL,
  `talla` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talla`
--

INSERT INTO `talla` (`idtalla`, `talla`) VALUES
(1, 'XS'),
(2, 'S'),
(3, 'M'),
(4, 'L'),
(5, 'XL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `documento` int(12) NOT NULL COMMENT 'Número identificador de un rol',
  `tipoDocumento` char(2) NOT NULL COMMENT 'Tipo de documento del usuario (CC,TI,CE,etc..)',
  `nombre` varchar(15) NOT NULL COMMENT 'Nombre del usuario',
  `apellido` varchar(15) NOT NULL COMMENT 'Apellido del usuario',
  `direccion` varchar(20) DEFAULT NULL COMMENT 'Dirección del usuario',
  `localidad` varchar(30) NOT NULL COMMENT 'Localidad del usuario',
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(30) NOT NULL COMMENT 'Correo con el que se registrará el usuario',
  `id_estado` int(11) DEFAULT NULL,
  `idrol` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`documento`, `tipoDocumento`, `nombre`, `apellido`, `direccion`, `localidad`, `telefono`, `correo`, `id_estado`, `idrol`) VALUES
(7890, 'CC', 'sONIC', 'fdgfd', 'DSF', 'LaCandelaria', '3149990908', 'Danielbermi@gmail.com', 4, 2),
(213234, 'TI', 'Martha', 'Delgada', 'calle 91 b sur', 'Antonio Nariño', '3224935678', 'marthica123@gmail.com', 3, 2),
(321564, 'CC', 'Carlos', 'Pera', 'qwere', 'Suba', '6574', 'lu@gmail.com', 3, NULL),
(3255654, 'TI', 'kevin', 'meza', 'clae3 4', 'Kennedy', '2631231231', 'lu@gmail.com', 3, NULL),
(13122972, 'CC', 'Contanza', 'Ramires', 'Cr 5 #98-7', 'Molinos', NULL, 'constara2@gmail', 3, NULL),
(52433567, 'CC', 'Yanett', 'fdg', 'ref', 'PuenteAranda', '3149990908', 'er@shad', 3, NULL),
(53359485, 'CC', 'Rufo', 'Rolo', 'Cra 4 #57-98 sur', 'Engativa', '3145678998', 'ortizelizabeth39@gmail.com', 3, 2),
(67345870, 'CC', 'Nicolas', 'Astu', 'Cll 100 #8-27', 'Teusaquillo', '3044448448', 'nicoasti4@gmail.com', 3, 1),
(67345878, 'TI', 'Nicolas', 'Astu', 'Cll 100 #8-27', 'Teusaquillo', '3044448448', 'nicoasti4@gmail.com', 3, 1),
(115789632, 'CC', 'daniel', 'torres', 'sdare', 'Bosa', '2147483647', 'SJKHDF@YHAS', 3, NULL),
(321654984, 'Va', 'Mayonesa', 'kdfj', 'Teusaquillo', '3214569632', '0', 'CC', 3, NULL),
(369856985, 'CC', 'Deisy', 'Gomez', 'ewtre', 'LaCandelaria', '798456', 'gdftjhedts@gmail.com', 3, 2),
(524556789, 'CC', 'Rufo', 'Rolo', 'Cra 4 #57-98 sur', 'Engativa', '3145678998', 'ortizelizabeth39@gmail.com', 3, NULL),
(723459876, 'CC', 'Daniela', 'Gi', 'Cll 97 #25', 'Usme', NULL, 'Danig45@gmail.c', 3, NULL),
(1003094893, 'CC', 'Mille', 'Gonzalez', 'fdsfsd', 'Usme', NULL, 'miller@gmal.com', 3, NULL),
(1003094895, 'CC', 'Eduard', 'Gonzalez', 'fdsfsd', 'Usme', NULL, 'Holl@gmal.com', 3, 2),
(1022847832, 'CC', 'Nicolas', 'Torres', 'Cr 4B bis ', 'Molinos', '3214449944', 'nico6t9@gmail.com', 3, 1),
(1033698803, 'TI', 'jonny', 'escobar', 'tv 2v este', 'Usme', '2147483647', 'aalee@gmail', 3, NULL),
(1080180837, 'CC', 'Lilley', 'Caicedo ', 'Cra 3 #89-39 sur', 'Usme', '3046530380', 'nietolily4@gmail.com', 3, 1),
(1140914077, 'CC', 'Daniel', 'Bermeo', 'Cr 4 b #54-87 sur', 'Usme', '3014449971', 'felipedanieltorres32@', 3, 1),
(1140914079, 'TI', 'Daniel', 'Bermeo', 'Cr 4 3b bis sur 27', 'Usme', NULL, 'felipedanieltorres32@gmail.com', 3, 1),
(1234567897, 'CC', 'Vanessa', 'Mateus', 'Cr 4 #87-6', 'Usme', NULL, 'vanesamateus66@', 3, NULL),
(1234567899, 'CC', 'Miguel', 'Mateus', 'dsfgdf', 'Kennedy', '3216544563', 'efelk@sad', 3, NULL),
(2147483647, 'TI', 'Deisy', 'Bueno', 'Cll 100 #5', 'Suba', NULL, 'deisybubu23@gma', 3, NULL);

--
-- Disparadores `usuario`
--
DELIMITER $$
CREATE TRIGGER `bitacora-usu` AFTER UPDATE ON `usuario` FOR EACH ROW INSERT INTO bitacora (accion) VALUES (concat('Se actuaizo un dato del usuario que documento es:',old.documento))
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `bitacora-usuario` AFTER INSERT ON `usuario` FOR EACH ROW INSERT INTO bitacora (accion) VALUES ('Se creo un nuevo usuario')
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `usu-bitacora` AFTER DELETE ON `usuario` FOR EACH ROW INSERT INTO bitacora (accion) VALUES (concat('se eliminno un usuario con el documento:',old.documento))
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_bit`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`FacturaidFactura`,`ProductoidProducto`),
  ADD KEY `FKFactura_Pr310483` (`ProductoidProducto`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idestado`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFactura`),
  ADD KEY `fk_idestadofac` (`id_estadof`),
  ADD KEY `fk_usuario` (`usuario`);

--
-- Indices de la tabla `proceso`
--
ALTER TABLE `proceso`
  ADD PRIMARY KEY (`idProceso`),
  ADD KEY `FKProceso664029` (`ProductoidProducto`),
  ADD KEY `FKProceso739263` (`ProveedoridProveedor`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idProducto`),
  ADD KEY `FKProducto488865` (`CategoriaidCategoria`),
  ADD KEY `fk_estadop` (`id_estado`),
  ADD KEY `fk_tallas` (`talla`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`idProveedor`),
  ADD KEY `Fk_product` (`productos`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `sesion`
--
ALTER TABLE `sesion`
  ADD PRIMARY KEY (`documento`);

--
-- Indices de la tabla `talla`
--
ALTER TABLE `talla`
  ADD PRIMARY KEY (`idtalla`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`documento`,`tipoDocumento`),
  ADD KEY `fk_estado` (`id_estado`),
  ADD KEY `fk_rol` (`idrol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Número que identifica una categoria', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `idestado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idFactura` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Número identificador de una factura', AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `proceso`
--
ALTER TABLE `proceso`
  MODIFY `idProceso` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Número identificador de un proceso', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Identificador unico de cada producto', AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `idProveedor` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Número dentifiicador de cada proveedor', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `talla`
--
ALTER TABLE `talla`
  MODIFY `idtalla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `FKFactura_Pr310483` FOREIGN KEY (`ProductoidProducto`) REFERENCES `producto` (`idProducto`),
  ADD CONSTRAINT `FKFactura_Pr337929` FOREIGN KEY (`FacturaidFactura`) REFERENCES `factura` (`idFactura`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_idestadofac` FOREIGN KEY (`id_estadof`) REFERENCES `estados` (`idestado`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`documento`);

--
-- Filtros para la tabla `proceso`
--
ALTER TABLE `proceso`
  ADD CONSTRAINT `FKProceso664029` FOREIGN KEY (`ProductoidProducto`) REFERENCES `producto` (`idProducto`),
  ADD CONSTRAINT `FKProceso739263` FOREIGN KEY (`ProveedoridProveedor`) REFERENCES `proveedor` (`idProveedor`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `FKProducto488865` FOREIGN KEY (`CategoriaidCategoria`) REFERENCES `categoria` (`idCategoria`),
  ADD CONSTRAINT `fk_estadop` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`idestado`),
  ADD CONSTRAINT `fk_tallas` FOREIGN KEY (`talla`) REFERENCES `talla` (`idtalla`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `Fk_product` FOREIGN KEY (`productos`) REFERENCES `producto` (`idProducto`);

--
-- Filtros para la tabla `sesion`
--
ALTER TABLE `sesion`
  ADD CONSTRAINT `fk_documento` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_estado` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`idestado`),
  ADD CONSTRAINT `fk_rol` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
