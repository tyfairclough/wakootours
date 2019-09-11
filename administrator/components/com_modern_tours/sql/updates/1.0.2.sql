ALTER TABLE `#__modern_tours_reservations` ADD `paid_deposit` DECIMAL (10,2) UNSIGNED NOT NULL DEFAULT '0' AFTER `price`;
ALTER TABLE `#__modern_tours_reservations` ADD `deposit` INT(1);
ALTER TABLE `#__modern_tours_reservations` ADD `unique_id` VARCHAR (15);