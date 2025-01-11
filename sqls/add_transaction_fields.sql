SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


ALTER TABLE `transaction` ADD `selected_discount` VARCHAR(128) NOT NULL;

ALTER TABLE `transaction` ADD `reference_number` VARCHAR(128) NOT NULL; 

ALTER TABLE `transaction`
  DROP `sub_total`,
  DROP `total`,
  DROP `discount`; 

COMMIT;