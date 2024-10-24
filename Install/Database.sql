DROP TABLE IF EXISTS `users`
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrative', 'worker') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
DROP TABLE IF EXISTS `task`
CREATE TABLE task (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    nif VARCHAR(15) NOT NULL, -- NIF o CIF de la persona sobre la que se hará la factura
    contact_name VARCHAR(100) NOT NULL, -- Nombre y apellidos de la persona de contacto
    contact_phone VARCHAR(50) NOT NULL, -- Teléfonos de contacto (separados por comas si hay varios)
    description TEXT NOT NULL, -- Descripción de la tarea
    contact_email VARCHAR(100), -- Correo electrónico de la persona de contacto
    address TEXT NOT NULL, -- Dirección donde se realizará la tarea
    city VARCHAR(100) NOT NULL, -- Población
    postal_code VARCHAR(5) NOT NULL, -- Código postal de la tarea
    province_code INT NOT NULL, -- Código de provincia según INE (dos primeros dígitos del CP)
    status ENUM('B', 'P', 'R', 'C') DEFAULT 'B', -- Estado de la tarea (B: Esperando aprobación, P: Pendiente, R: Realizada, C: Cancelada)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de creación de la tarea
    finished_at DATE, -- Fecha en la que se realizará la tarea
    assigned_worker VARCHAR(100) NOT NULL, -- Nombre o ID del operario encargado
    pre_notes TEXT, -- Anotaciones antes de comenzar el trabajo
    post_notes TEXT, -- Anotaciones realizadas después del trabajo
    sumary_file VARCHAR(255) -- Ruta al fichero resumen del trabajo
    img_file VARCHAR(255) NOT NULL, -- Ruta de la foto almacenada en el servidor
);


DROP TABLE IF EXISTS `autonomous_communities`;
CREATE TABLE IF NOT EXISTS `autonomous_communities` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Autonomous Communities of Spain';

-- Insert data into the table `tbl_comunidadesautonomas`
INSERT INTO `autonomous_communities` (`id`, `name`) VALUES
(1, 'Andalusia'),
(2, 'Aragon'),
(3, 'Asturias (Principality of)'),
(4, 'Balears (Illes)'),
(5, 'Canary Islands'),
(6, 'Cantabria'),
(7, 'Castilla-La Mancha'),
(8, 'Castilla y León'),
(9, 'Catalonia'),
(10, 'Valencian Community'),
(11, 'Extremadura'),
(12, 'Galicia'),
(13, 'Madrid (Community of)'),
(14, 'Murcia (Region of)'),
(15, 'Navarra (Foral Community of)'),
(16, 'Basque Country'),
(17, 'Rioja (La)'),
(18, 'Ceuta'),
(19, 'Melilla');


DROP TABLE IF EXISTS `provinces`;
CREATE TABLE IF NOT EXISTS `provinces` (
  `code` char(2) NOT NULL COMMENT 'Two-digit province code',
  `name` varchar(50) NOT NULL COMMENT 'Name of the province',
  `community_id` tinyint(4) NOT NULL COMMENT 'Code of the community to which it belongs',
  PRIMARY KEY (`code`),
  KEY `name` (`name`),
  KEY `FK_AutonomousCommunityProv` (`community_id`),
  CONSTRAINT `FK_AutonomousCommunityProv` FOREIGN KEY (`community_id`) REFERENCES `autonomous_communities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Provinces of Spain; 99 for selecting National';


INSERT INTO `provinces` (`code`, `name`, `community_id`) VALUES
('01', 'Alava', 16),
('02', 'Albacete', 7),
('03', 'Alicante', 10),
('04', 'Almería', 1),
('05', 'Ávila', 8),
('06', 'Badajoz', 11),
('07', 'Balears (Illes)', 4),
('08', 'Barcelona', 9),
('09', 'Burgos', 8),
('10', 'Cáceres', 11),
('11', 'Cádiz', 1),
('12', 'Castellón', 10),
('13', 'Ciudad Real', 7),
('14', 'Córdoba', 1),
('15', 'A Coruña', 12),
('16', 'Cuenca', 7),
('17', 'Girona', 9),
('18', 'Granada', 1),
('19', 'Guadalajara', 7),
('20', 'Gipuzkoa', 16),
('21', 'Huelva', 1),
('22', 'Huesca', 2),
('23', 'Jaén', 1),
('24', 'León', 8),
('25', 'Lleida', 9),
('26', 'La Rioja', 17),
('27', 'Lugo', 12),
('28', 'Madrid', 13),
('29', 'Málaga', 1),
('30', 'Murcia', 14),
('31', 'Navarra', 15),
('32', 'Ourense', 12),
('33', 'Asturias', 3),
('34', 'Palencia', 8),
('35', 'Las Palmas', 5),
('36', 'Pontevedra', 12),
('37', 'Salamanca', 8),
('38', 'Santa Cruz de Tenerife', 5),
('39', 'Cantabria', 6),
('40', 'Segovia', 8),
('41', 'Seville', 1),
('42', 'Soria', 8),
('43', 'Tarragona', 9),
('44', 'Teruel', 2),
('45', 'Toledo', 7),
('46', 'Valencia', 10),
('47', 'Valladolid', 8),
('48', 'Biscay', 16),
('49', 'Zamora', 8),
('50', 'Zaragoza', 2),
('51', 'Ceuta', 18),
('52', 'Melilla', 19);