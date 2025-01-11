SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


ALTER TABLE `batch` CHANGE `date_disposed` `date_disposed` DATE NULL; 

ALTER TABLE `batch` ADD `supplier_price` DOUBLE NOT NULL; 

ALTER TABLE `batch` CHANGE `quantity` `received_quantity` INT(11) NOT NULL; 

ALTER TABLE `batch` ADD `disposed_quantity` INT(11) NOT NULL; 

COMMIT;