-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-05-2013 a las 15:03:41
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `libreria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE IF NOT EXISTS `articulos` (
  `articuloid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `articulodes` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `articulofecha` date NOT NULL,
  PRIMARY KEY (`articuloid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`articuloid`, `articulodes`, `articulofecha`) VALUES
(1, 'La lectura es el proceso de la recuperación y comprensión de algún tipo de información o ideas almacenadas en un soporte y transmitidas mediante algún tipo de código, usualmente un lenguaje, que puede ser visual o táctil (por ejemplo, el sistema Braille). Otros tipos de lectura pueden no estar basados en el lenguaje tales como la notación o los pictogramas.\r\nLa lectura no es una actividad neutra: pone en juego al lector y una serie de relaciones complejas con el texto. Mas, cuando el libro está cerrado, ¿en qué se convierte el lector? ¿En un simple glotón capaz de digerir letras? ¿Un leñador cuya única labor es desbrozar el paisaje literario?', '2012-01-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `author_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombres` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `ape_paterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `ape_materno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `nacionalidad` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `authors`
--

INSERT INTO `authors` (`author_id`, `nombres`, `ape_paterno`, `ape_materno`, `nacionalidad`) VALUES
(1, 'Luke', 'Welling', 'Smith', 'Estados Unidos'),
(2, 'Laura', 'Thomson', 'Smith', 'Estados Unidos'),
(3, 'Matt', 'Zandstra', 'Smith', 'Estados Unidos'),
(4, 'Sterling', 'Hughes', 'Smith', 'Estados Unidos'),
(5, 'Andrei', 'Zmi', 'Smith', 'Estados Unidos'),
(6, 'José', 'Pérez', 'Zárate', 'Perú');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `isbn` char(13) CHARACTER SET utf8 NOT NULL,
  `title` varchar(60) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `bookdes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `date_publication` date NOT NULL,
  `publisher_id` int(10) unsigned NOT NULL,
  `price` float(6,2) NOT NULL,
  `catid` int(10) unsigned NOT NULL,
  `recomendado` tinyint(1) NOT NULL,
  PRIMARY KEY (`isbn`),
  KEY `publisher_id` (`publisher_id`,`catid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books_by_author`
--

CREATE TABLE IF NOT EXISTS `books_by_author` (
  `author_id` int(11) unsigned NOT NULL,
  `isbn` char(13) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`author_id`,`isbn`),
  KEY `isbn` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`catid`, `catname`) VALUES
(1, 'Biografías'),
(2, 'Arte'),
(4, 'Biografías'),
(5, 'Filosofía'),
(6, 'Best Sellers'),
(7, 'Economía'),
(8, 'Médico Biológicas'),
(9, 'Sexualidad'),
(10, 'Ciencias Exactas'),
(11, 'Ciencias de la Tierra'),
(12, 'Ciencias Sociales'),
(13, 'Ingenierías'),
(14, 'Computación'),
(15, 'Derecho'),
(16, 'Tiempo Libre'),
(17, 'Turismo y Gastronomía'),
(18, 'Historia'),
(19, 'Infantil'),
(21, 'Lenguas y Lingüística');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PAI_ISONUM` smallint(6) DEFAULT NULL,
  `PAI_ISO2` char(2) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `PAI_ISO3` char(3) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `PAI_NOMBRE` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241 ;

--
-- Volcado de datos para la tabla `countries`
--

INSERT INTO `countries` (`country_id`, `PAI_ISONUM`, `PAI_ISO2`, `PAI_ISO3`, `PAI_NOMBRE`) VALUES
(1, 4, 'AF', 'AFG', 'Afganistán'),
(2, 248, 'AX', 'ALA', 'Islas Gland'),
(3, 8, 'AL', 'ALB', 'Albania'),
(4, 276, 'DE', 'DEU', 'Alemania'),
(5, 20, 'AD', 'AND', 'Andorra'),
(6, 24, 'AO', 'AGO', 'Angola'),
(7, 660, 'AI', 'AIA', 'Anguilla'),
(8, 10, 'AQ', 'ATA', 'Antártida'),
(9, 28, 'AG', 'ATG', 'Antigua y Barbuda'),
(10, 530, 'AN', 'ANT', 'Antillas Holandesas'),
(11, 682, 'SA', 'SAU', 'Arabia Saudí'),
(12, 12, 'DZ', 'DZA', 'Argelia'),
(13, 32, 'AR', 'ARG', 'Argentina'),
(14, 51, 'AM', 'ARM', 'Armenia'),
(15, 533, 'AW', 'ABW', 'Aruba'),
(16, 36, 'AU', 'AUS', 'Australia'),
(17, 40, 'AT', 'AUT', 'Austria'),
(18, 31, 'AZ', 'AZE', 'Azerbaiyán'),
(19, 44, 'BS', 'BHS', 'Bahamas'),
(20, 48, 'BH', 'BHR', 'Bahréin'),
(21, 50, 'BD', 'BGD', 'Bangladesh'),
(22, 52, 'BB', 'BRB', 'Barbados'),
(23, 112, 'BY', 'BLR', 'Bielorrusia'),
(24, 56, 'BE', 'BEL', 'Bélgica'),
(25, 84, 'BZ', 'BLZ', 'Belice'),
(26, 204, 'BJ', 'BEN', 'Benin'),
(27, 60, 'BM', 'BMU', 'Bermudas'),
(28, 64, 'BT', 'BTN', 'Bhután'),
(29, 68, 'BO', 'BOL', 'Bolivia'),
(30, 70, 'BA', 'BIH', 'Bosnia y Herzegovina'),
(31, 72, 'BW', 'BWA', 'Botsuana'),
(32, 74, 'BV', 'BVT', 'Isla Bouvet'),
(33, 76, 'BR', 'BRA', 'Brasil'),
(34, 96, 'BN', 'BRN', 'Brunéi'),
(35, 100, 'BG', 'BGR', 'Bulgaria'),
(36, 854, 'BF', 'BFA', 'Burkina Faso'),
(37, 108, 'BI', 'BDI', 'Burundi'),
(38, 132, 'CV', 'CPV', 'Cabo Verde'),
(39, 136, 'KY', 'CYM', 'Islas Caimán'),
(40, 116, 'KH', 'KHM', 'Camboya'),
(41, 120, 'CM', 'CMR', 'Camerún'),
(42, 124, 'CA', 'CAN', 'Canadá'),
(43, 140, 'CF', 'CAF', 'República Centroafricana'),
(44, 148, 'TD', 'TCD', 'Chad'),
(45, 203, 'CZ', 'CZE', 'República Checa'),
(46, 152, 'CL', 'CHL', 'Chile'),
(47, 156, 'CN', 'CHN', 'China'),
(48, 196, 'CY', 'CYP', 'Chipre'),
(49, 162, 'CX', 'CXR', 'Isla de Navidad'),
(50, 336, 'VA', 'VAT', 'Ciudad del Vaticano'),
(51, 166, 'CC', 'CCK', 'Islas Cocos'),
(52, 170, 'CO', 'COL', 'Colombia'),
(53, 174, 'KM', 'COM', 'Comoras'),
(54, 180, 'CD', 'COD', 'República Democrática del Congo'),
(55, 178, 'CG', 'COG', 'Congo'),
(56, 184, 'CK', 'COK', 'Islas Cook'),
(57, 408, 'KP', 'PRK', 'Corea del Norte'),
(58, 410, 'KR', 'KOR', 'Corea del Sur'),
(59, 384, 'CI', 'CIV', 'Costa de Marfil'),
(60, 188, 'CR', 'CRI', 'Costa Rica'),
(61, 191, 'HR', 'HRV', 'Croacia'),
(62, 192, 'CU', 'CUB', 'Cuba'),
(63, 208, 'DK', 'DNK', 'Dinamarca'),
(64, 212, 'DM', 'DMA', 'Dominica'),
(65, 214, 'DO', 'DOM', 'República Dominicana'),
(66, 218, 'EC', 'ECU', 'Ecuador'),
(67, 818, 'EG', 'EGY', 'Egipto'),
(68, 222, 'SV', 'SLV', 'El Salvador'),
(69, 784, 'AE', 'ARE', 'Emiratos Árabes Unidos'),
(70, 232, 'ER', 'ERI', 'Eritrea'),
(71, 703, 'SK', 'SVK', 'Eslovaquia'),
(72, 705, 'SI', 'SVN', 'Eslovenia'),
(73, 724, 'ES', 'ESP', 'España'),
(74, 581, 'UM', 'UMI', 'Islas ultramarinas de Estados Unidos'),
(75, 840, 'US', 'USA', 'Estados Unidos'),
(76, 233, 'EE', 'EST', 'Estonia'),
(77, 231, 'ET', 'ETH', 'Etiopía'),
(78, 234, 'FO', 'FRO', 'Islas Feroe'),
(79, 608, 'PH', 'PHL', 'Filipinas'),
(80, 246, 'FI', 'FIN', 'Finlandia'),
(81, 242, 'FJ', 'FJI', 'Fiyi'),
(82, 250, 'FR', 'FRA', 'Francia'),
(83, 266, 'GA', 'GAB', 'Gabón'),
(84, 270, 'GM', 'GMB', 'Gambia'),
(85, 268, 'GE', 'GEO', 'Georgia'),
(86, 239, 'GS', 'SGS', 'Islas Georgias del Sur y Sandwich del Sur'),
(87, 288, 'GH', 'GHA', 'Ghana'),
(88, 292, 'GI', 'GIB', 'Gibraltar'),
(89, 308, 'GD', 'GRD', 'Granada'),
(90, 300, 'GR', 'GRC', 'Grecia'),
(91, 304, 'GL', 'GRL', 'Groenlandia'),
(92, 312, 'GP', 'GLP', 'Guadalupe'),
(93, 316, 'GU', 'GUM', 'Guam'),
(94, 320, 'GT', 'GTM', 'Guatemala'),
(95, 254, 'GF', 'GUF', 'Guayana Francesa'),
(96, 324, 'GN', 'GIN', 'Guinea'),
(97, 226, 'GQ', 'GNQ', 'Guinea Ecuatorial'),
(98, 624, 'GW', 'GNB', 'Guinea-Bissau'),
(99, 328, 'GY', 'GUY', 'Guyana'),
(100, 332, 'HT', 'HTI', 'Haití'),
(101, 334, 'HM', 'HMD', 'Islas Heard y McDonald'),
(102, 340, 'HN', 'HND', 'Honduras'),
(103, 344, 'HK', 'HKG', 'Hong Kong'),
(104, 348, 'HU', 'HUN', 'Hungría'),
(105, 356, 'IN', 'IND', 'India'),
(106, 360, 'ID', 'IDN', 'Indonesia'),
(107, 364, 'IR', 'IRN', 'Irán'),
(108, 368, 'IQ', 'IRQ', 'Iraq'),
(109, 372, 'IE', 'IRL', 'Irlanda'),
(110, 352, 'IS', 'ISL', 'Islandia'),
(111, 376, 'IL', 'ISR', 'Israel'),
(112, 380, 'IT', 'ITA', 'Italia'),
(113, 388, 'JM', 'JAM', 'Jamaica'),
(114, 392, 'JP', 'JPN', 'Japón'),
(115, 400, 'JO', 'JOR', 'Jordania'),
(116, 398, 'KZ', 'KAZ', 'Kazajstán'),
(117, 404, 'KE', 'KEN', 'Kenia'),
(118, 417, 'KG', 'KGZ', 'Kirguistán'),
(119, 296, 'KI', 'KIR', 'Kiribati'),
(120, 414, 'KW', 'KWT', 'Kuwait'),
(121, 418, 'LA', 'LAO', 'Laos'),
(122, 426, 'LS', 'LSO', 'Lesotho'),
(123, 428, 'LV', 'LVA', 'Letonia'),
(124, 422, 'LB', 'LBN', 'Líbano'),
(125, 430, 'LR', 'LBR', 'Liberia'),
(126, 434, 'LY', 'LBY', 'Libia'),
(127, 438, 'LI', 'LIE', 'Liechtenstein'),
(128, 440, 'LT', 'LTU', 'Lituania'),
(129, 442, 'LU', 'LUX', 'Luxemburgo'),
(130, 446, 'MO', 'MAC', 'Macao'),
(131, 807, 'MK', 'MKD', 'ARY Macedonia'),
(132, 450, 'MG', 'MDG', 'Madagascar'),
(133, 458, 'MY', 'MYS', 'Malasia'),
(134, 454, 'MW', 'MWI', 'Malawi'),
(135, 462, 'MV', 'MDV', 'Maldivas'),
(136, 466, 'ML', 'MLI', 'Malí'),
(137, 470, 'MT', 'MLT', 'Malta'),
(138, 238, 'FK', 'FLK', 'Islas Malvinas'),
(139, 580, 'MP', 'MNP', 'Islas Marianas del Norte'),
(140, 504, 'MA', 'MAR', 'Marruecos'),
(141, 584, 'MH', 'MHL', 'Islas Marshall'),
(142, 474, 'MQ', 'MTQ', 'Martinica'),
(143, 480, 'MU', 'MUS', 'Mauricio'),
(144, 478, 'MR', 'MRT', 'Mauritania'),
(145, 175, 'YT', 'MYT', 'Mayotte'),
(146, 484, 'MX', 'MEX', 'México'),
(147, 583, 'FM', 'FSM', 'Micronesia'),
(148, 498, 'MD', 'MDA', 'Moldavia'),
(149, 492, 'MC', 'MCO', 'Mónaco'),
(150, 496, 'MN', 'MNG', 'Mongolia'),
(151, 500, 'MS', 'MSR', 'Montserrat'),
(152, 508, 'MZ', 'MOZ', 'Mozambique'),
(153, 104, 'MM', 'MMR', 'Myanmar'),
(154, 516, 'NA', 'NAM', 'Namibia'),
(155, 520, 'NR', 'NRU', 'Nauru'),
(156, 524, 'NP', 'NPL', 'Nepal'),
(157, 558, 'NI', 'NIC', 'Nicaragua'),
(158, 562, 'NE', 'NER', 'Níger'),
(159, 566, 'NG', 'NGA', 'Nigeria'),
(160, 570, 'NU', 'NIU', 'Niue'),
(161, 574, 'NF', 'NFK', 'Isla Norfolk'),
(162, 578, 'NO', 'NOR', 'Noruega'),
(163, 540, 'NC', 'NCL', 'Nueva Caledonia'),
(164, 554, 'NZ', 'NZL', 'Nueva Zelanda'),
(165, 512, 'OM', 'OMN', 'Omán'),
(166, 528, 'NL', 'NLD', 'Países Bajos'),
(167, 586, 'PK', 'PAK', 'Pakistán'),
(168, 585, 'PW', 'PLW', 'Palau'),
(169, 275, 'PS', 'PSE', 'Palestina'),
(170, 591, 'PA', 'PAN', 'Panamá'),
(171, 598, 'PG', 'PNG', 'Papúa Nueva Guinea'),
(172, 600, 'PY', 'PRY', 'Paraguay'),
(173, 604, 'PE', 'PER', 'Perú'),
(174, 612, 'PN', 'PCN', 'Islas Pitcairn'),
(175, 258, 'PF', 'PYF', 'Polinesia Francesa'),
(176, 616, 'PL', 'POL', 'Polonia'),
(177, 620, 'PT', 'PRT', 'Portugal'),
(178, 630, 'PR', 'PRI', 'Puerto Rico'),
(179, 634, 'QA', 'QAT', 'Qatar'),
(180, 826, 'GB', 'GBR', 'Reino Unido'),
(181, 638, 'RE', 'REU', 'Reunión'),
(182, 646, 'RW', 'RWA', 'Ruanda'),
(183, 642, 'RO', 'ROU', 'Rumania'),
(184, 643, 'RU', 'RUS', 'Rusia'),
(185, 732, 'EH', 'ESH', 'Sahara Occidental'),
(186, 90, 'SB', 'SLB', 'Islas Salomón'),
(187, 882, 'WS', 'WSM', 'Samoa'),
(188, 16, 'AS', 'ASM', 'Samoa Americana'),
(189, 659, 'KN', 'KNA', 'San Cristóbal y Nevis'),
(190, 674, 'SM', 'SMR', 'San Marino'),
(191, 666, 'PM', 'SPM', 'San Pedro y Miquelón'),
(192, 670, 'VC', 'VCT', 'San Vicente y las Granadinas'),
(193, 654, 'SH', 'SHN', 'Santa Helena'),
(194, 662, 'LC', 'LCA', 'Santa Lucía'),
(195, 678, 'ST', 'STP', 'Santo Tomé y Príncipe'),
(196, 686, 'SN', 'SEN', 'Senegal'),
(197, 891, 'CS', 'SCG', 'Serbia y Montenegro'),
(198, 690, 'SC', 'SYC', 'Seychelles'),
(199, 694, 'SL', 'SLE', 'Sierra Leona'),
(200, 702, 'SG', 'SGP', 'Singapur'),
(201, 760, 'SY', 'SYR', 'Siria'),
(202, 706, 'SO', 'SOM', 'Somalia'),
(203, 144, 'LK', 'LKA', 'Sri Lanka'),
(204, 748, 'SZ', 'SWZ', 'Suazilandia'),
(205, 710, 'ZA', 'ZAF', 'Sudáfrica'),
(206, 736, 'SD', 'SDN', 'Sudán'),
(207, 752, 'SE', 'SWE', 'Suecia'),
(208, 756, 'CH', 'CHE', 'Suiza'),
(209, 740, 'SR', 'SUR', 'Surinam'),
(210, 744, 'SJ', 'SJM', 'Svalbard y Jan Mayen'),
(211, 764, 'TH', 'THA', 'Tailandia'),
(212, 158, 'TW', 'TWN', 'Taiwán'),
(213, 834, 'TZ', 'TZA', 'Tanzania'),
(214, 762, 'TJ', 'TJK', 'Tayikistán'),
(215, 86, 'IO', 'IOT', 'Territorio Británico del Océano Índico'),
(216, 260, 'TF', 'ATF', 'Territorios Australes Franceses'),
(217, 626, 'TL', 'TLS', 'Timor Oriental'),
(218, 768, 'TG', 'TGO', 'Togo'),
(219, 772, 'TK', 'TKL', 'Tokelau'),
(220, 776, 'TO', 'TON', 'Tonga'),
(221, 780, 'TT', 'TTO', 'Trinidad y Tobago'),
(222, 788, 'TN', 'TUN', 'Túnez'),
(223, 796, 'TC', 'TCA', 'Islas Turcas y Caicos'),
(224, 795, 'TM', 'TKM', 'Turkmenistán'),
(225, 792, 'TR', 'TUR', 'Turquía'),
(226, 798, 'TV', 'TUV', 'Tuvalu'),
(227, 804, 'UA', 'UKR', 'Ucrania'),
(228, 800, 'UG', 'UGA', 'Uganda'),
(229, 858, 'UY', 'URY', 'Uruguay'),
(230, 860, 'UZ', 'UZB', 'Uzbekistán'),
(231, 548, 'VU', 'VUT', 'Vanuatu'),
(232, 862, 'VE', 'VEN', 'Venezuela'),
(233, 704, 'VN', 'VNM', 'Vietnam'),
(234, 92, 'VG', 'VGB', 'Islas Vírgenes Británicas'),
(235, 850, 'VI', 'VIR', 'Islas Vírgenes de los Estados Unidos'),
(236, 876, 'WF', 'WLF', 'Wallis y Futuna'),
(237, 887, 'YE', 'YEM', 'Yemen'),
(238, 262, 'DJ', 'DJI', 'Yibuti'),
(239, 894, 'ZM', 'ZMB', 'Zambia'),
(240, 716, 'ZW', 'ZWE', 'Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customerid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ap_paterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ap_materno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `address` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `city` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `zip` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`customerid`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`customerid`, `name`, `ap_paterno`, `ap_materno`, `address`, `city`, `zip`, `country_id`) VALUES
(1, 'José', 'Pérez', 'Zárate', 'Av. Los Insurgentes 743 B34', 'Lima', '51', 173),
(2, 'Rosa', 'Maturrano', 'Toledo', 'Psje. Quiche s/n', 'Huacho', '51', 173),
(3, 'Carmen', 'Zárate', 'Carbajal', 'Av. Los Insurgentes 743 B34', 'Lima', '51', 173),
(4, 'Luis', 'Pérez', 'Zárate', 'Av. Los Insurgentes 743 B34', 'Lima', '51', 173);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE IF NOT EXISTS `eventos` (
  `id_evento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo_evento` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `des_evento` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha_evento` date NOT NULL,
  `img_evento` varchar(50) NOT NULL,
  `statuts` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_evento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `titulo_evento`, `des_evento`, `fecha_evento`, `img_evento`, `statuts`) VALUES
(1, 'Aprendiendo PHP y Mysql', 'Primer evento en el cual mostraremos las nociones básicas en el uso de estas potentes herramientas para el desarrollo web.\r\nLorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.', '2012-01-30', 'php.png', 1),
(2, 'Taller de Postgres', 'En julio empezamos el taller de Postgres en el cual mostraremos las características más importantes de esta herramienta.\r\nLorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.', '2012-02-03', 'postgres.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menuID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) NOT NULL,
  `enlace` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`menuID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`menuID`, `menu`, `enlace`) VALUES
(1, 'Dashboard', 'index'),
(2, 'Mantenimiento', 'index');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customerid` int(10) unsigned NOT NULL,
  `amount` float(6,2) DEFAULT NULL,
  `date` date NOT NULL,
  `order_status` int(1) unsigned DEFAULT NULL,
  `ship_name` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ship_apaterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ship_amaterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ship_address` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ship_city` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ship_zip` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ship_country` int(10) unsigned NOT NULL,
  PRIMARY KEY (`orderid`),
  KEY `ship_country` (`ship_country`),
  KEY `customerid` (`customerid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`orderid`, `customerid`, `amount`, `date`, `order_status`, `ship_name`, `ship_apaterno`, `ship_amaterno`, `ship_address`, `ship_city`, `ship_zip`, `ship_country`) VALUES
(7, 1, 28.85, '2012-01-26', 1, 'José', 'Pérez', 'Zárate', 'Av. Los Insurgentes 743 B34', 'Lima', '51', 173),
(8, 2, 82.86, '2012-01-26', 1, 'Rosa', 'Maturrano', 'Toledo', 'Psje. Quiche s/n', 'Huacho', '51', 173),
(10, 3, 30.50, '2012-01-26', 1, 'Carmen', 'Zárate', 'Carbajal', 'Av. Los Insurgentes 743 B34', 'Lima', '51', 173),
(13, 4, 91.51, '2012-01-26', 1, 'Luis', 'Pérez', 'Zárate', 'Av. Los Insurgentes 743 B34', 'Lima', '51', 173);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE IF NOT EXISTS `order_items` (
  `orderid` int(10) unsigned NOT NULL,
  `isbn` char(13) CHARACTER SET utf8 NOT NULL,
  `item_price` float(4,2) NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`orderid`,`isbn`),
  KEY `isbn` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `permiso` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_permiso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `permiso`, `key`) VALUES
(1, 'Tareas de administración', 'admin_access'),
(2, 'Agregar Usuario', 'add_user'),
(3, 'Eliminar Usuario', 'del_user'),
(4, 'Editar Usuario', 'edit_user'),
(5, 'Agregar Editorial', 'add_pub'),
(6, 'Editar Editorial', 'edit_pub'),
(7, 'Eliminar Editorial', 'del_pub'),
(8, 'Agregar Categoría', 'add_cat'),
(9, 'Editar Categoría', 'edit_cat'),
(10, 'Eliminar Categoría', 'del_cat'),
(11, 'Agregar Evento', 'add_event'),
(12, 'Editar Evento', 'edit_event'),
(13, 'Eliminar Evento', 'del_event'),
(14, 'Agregar Cliente', 'add_cust'),
(15, 'Editar Cliente', 'edit_cust'),
(16, 'Eliminar Cliente', 'del_cust');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_role`
--

CREATE TABLE IF NOT EXISTS `permisos_role` (
  `roleID` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `valor` tinyint(4) NOT NULL,
  UNIQUE KEY `role` (`roleID`,`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permisos_role`
--

INSERT INTO `permisos_role` (`roleID`, `id_permiso`, `valor`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(1, 4, 1),
(1, 5, 1),
(1, 6, 1),
(1, 7, 1),
(1, 8, 1),
(1, 9, 1),
(1, 10, 1),
(1, 11, 1),
(1, 12, 1),
(1, 13, 1),
(3, 5, 1),
(3, 6, 1),
(3, 7, 1),
(3, 8, 1),
(3, 9, 1),
(3, 10, 1),
(3, 11, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_usuario`
--

CREATE TABLE IF NOT EXISTS `permisos_usuario` (
  `userID` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `valor` tinyint(4) DEFAULT NULL,
  UNIQUE KEY `usuario` (`userID`,`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publisher`
--

CREATE TABLE IF NOT EXISTS `publisher` (
  `publisher_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publisher` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`publisher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `publisher`
--

INSERT INTO `publisher` (`publisher_id`, `publisher`, `description`) VALUES
(11, 'Limusa S.A. de C.V.', ''),
(12, 'Alfaomega GPO EDR', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`roleID`, `role`) VALUES
(1, 'Administrador'),
(2, 'Ventas'),
(3, 'Almacén'),
(6, 'Contabilidad'),
(9, 'Sistemas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submenu`
--

CREATE TABLE IF NOT EXISTS `submenu` (
  `submenuID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `submenu` varchar(100) NOT NULL,
  `enlace` varchar(100) DEFAULT NULL,
  `menuID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`submenuID`),
  KEY `menuID` (`menuID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `submenu`
--

INSERT INTO `submenu` (`submenuID`, `submenu`, `enlace`, `menuID`) VALUES
(1, 'Usuarios', 'usuarios', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(100) NOT NULL,
  `nombres` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apaterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `amaterno` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `avatar` varchar(80) DEFAULT NULL,
  `role` int(11) NOT NULL,
  `Comentario` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  PRIMARY KEY (`userID`),
  KEY `level` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`userID`, `login`, `pass`, `nombres`, `apaterno`, `amaterno`, `email`, `telefono`, `avatar`, `role`, `Comentario`) VALUES
(1, 'admin', '26b33773c3417698f1a1303cd771c29a18e36031', 'José Luis', 'Pérez', 'Zárate', 'jolupeza@hotmail.com', '993301435', 'upl_4fc4086cbacec.jpg', 1, 'El super Administrador'),
(19, 'rossy', '26b33773c3417698f1a1303cd771c29a18e36031', 'Rosa', 'Maturrano', 'Toledo', 'rossy_18_02@hotmail.com', '991856220', 'upl_4ffed5d59b44c.gif', 2, 'La contadora '),
(20, 'carozaca', '26b33773c3417698f1a1303cd771c29a18e36031', 'Carmen', 'Zárate', 'Carbajal', 'carozaca@hotmail.com', '4206939', 'upl_4ffed75202e55.jpg', 3, 'Mi madre');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`publisher_id`) REFERENCES `publisher` (`publisher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`catid`) REFERENCES `categories` (`catid`) ON DELETE CASCADE;

--
-- Filtros para la tabla `books_by_author`
--
ALTER TABLE `books_by_author`
  ADD CONSTRAINT `books_by_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `books_by_author_ibfk_3` FOREIGN KEY (`isbn`) REFERENCES `books` (`isbn`) ON DELETE CASCADE;

--
-- Filtros para la tabla `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customers` (`customerid`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`ship_country`) REFERENCES `countries` (`country_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`isbn`) REFERENCES `books` (`isbn`) ON DELETE CASCADE;

--
-- Filtros para la tabla `submenu`
--
ALTER TABLE `submenu`
  ADD CONSTRAINT `submenu_ibfk_1` FOREIGN KEY (`menuID`) REFERENCES `menu` (`menuID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`roleID`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
