CREATE DATABASE `piamonte`;
CREATE USER 'piamonte'@'localhost' IDENTIFIED BY 'piamonte';
GRANT ALL PRIVILEGES ON `piamonte` . * TO 'piamonte'@'localhost' IDENTIFIED BY 'piamonte' WITH GRANT OPTION ;
CREATE TABLE `piamonte`.`users` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),usuario varchar(20) NOT NULL,clave varchar(200) NOT NULL,nombre varchar(100) NOT NULL,UNIQUE KEY usuario (usuario));
INSERT INTO `piamonte`.`users` (`id`, `usuario`, `clave`, `nombre`) VALUES (NULL, 'admin', '53bf23ae4ab78054f4331809504de38a23fc2aa0456d296683248ec34d2c056b72d80bcfcaaa6b86f5ed02484773562fee8734fc6878ab124eb304c37e645694b103c7ba', 'Administrador');
CREATE TABLE `piamonte`.`images` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `path` VARCHAR(255) NULL,`titulo` VARCHAR(255) NULL,`home` INT(1) NULL,`updated_on` DATETIME NULL, `created_on` DATETIME NULL);
CREATE TABLE `piamonte`.`promotions` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `titulo` VARCHAR(255) NOT NULL,`foto` VARCHAR(255) NULL,`descripcion` TEXT NULL,`updated_on` DATETIME NULL, `created_on` DATETIME NULL);
