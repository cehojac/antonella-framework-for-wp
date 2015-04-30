-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-04-2015 a las 19:03:33
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `destinia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hospedajes`
--

CREATE TABLE IF NOT EXISTS `hospedajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `tipo` set('hotel','apartamento') NOT NULL,
  `ciudad` text NOT NULL,
  `provincia` text NOT NULL,
  `estrellas` int(1) NOT NULL,
  `apartamentos` int(3) NOT NULL DEFAULT '0',
  `tipo_habitacion` text NOT NULL,
  `capacidad_apartamento` int(2) NOT NULL DEFAULT '0',
  `image` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `hospedajes`
--

INSERT INTO `hospedajes` (`id`, `nombre`, `tipo`, `ciudad`, `provincia`, `estrellas`, `apartamentos`, `tipo_habitacion`, `capacidad_apartamento`, `image`) VALUES
(1, 'prueba', 'hotel', 'Santander', 'Cantabria', 4, 0, 'con vista a la playa', 0, ''),
(2, 'prueba2', 'apartamento', 'Santander', 'Cantabria', 0, 5, '', 3, ''),
(3, 'prueba3', 'hotel', 'Santander', 'Cantabria', 5, 0, 'presidencial', 0, ''),
(4, 'prueba3', 'hotel', 'Santander', 'Cantabria', 5, 0, 'presidencial', 0, ''),
(5, 'prueba4', 'hotel', 'Santander', 'Cantabria', 5, 0, 'presidencial', 0, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
