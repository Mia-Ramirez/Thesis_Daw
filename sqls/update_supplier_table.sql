SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


ALTER TABLE `supplier`
  DROP `contact_number`,
  DROP `email`,
  DROP `address`; 

COMMIT;