SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

UPDATE `medicine` SET `current_quantity` = '100' WHERE `medicine`.`id` = 1; 
UPDATE `medicine` SET `current_quantity` = '50' WHERE `medicine`.`id` = 2; 

COMMIT;