USE `portfolio`;

DROP TABLE IF EXISTS `source_image`;
CREATE TABLE `source_image` (
  `id` INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `width` INT( 12 ) NOT NULL ,
  `height` INT( 12 ) NOT NULL ,
  `name` VARCHAR( 255 ) NULL ,
  `url` VARCHAR( 255 ) NOT NULL
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `thumb_image`;
CREATE TABLE `thumb_image` (
  `id` INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `source_image_id` INT( 12 ) NOT NULL ,
  `page_width` INT( 12 ) NOT NULL ,
  `width` INT( 12 ) NOT NULL ,
  `height` INT( 12 ) NOT NULL ,
  `url` VARCHAR( 255 ) NOT NULL ,
  `row_number` INT NOT NULL ,
  INDEX ( `source_image_id` , `row_number` )
) ENGINE = InnoDB;


DROP TABLE IF EXISTS `process`;
CREATE TABLE `process` (
  `name` varchar(255) NOT NULL,
  `percent` int(12) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;