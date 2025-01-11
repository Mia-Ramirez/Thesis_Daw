SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


ALTER TABLE `batch` CHANGE `date_disposed` `date_disposed` DATE NULL; 

COMMIT;