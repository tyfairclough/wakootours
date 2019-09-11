ALTER TABLE `#__modern_tours_reservations` ADD `children` INT(4) UNSIGNED NOT NULL DEFAULT '0' AFTER `people`;
ALTER TABLE `#__modern_tours_reservations` ADD `adults` INT(4) UNSIGNED NOT NULL DEFAULT '0' AFTER `people`;
ALTER TABLE `#__modern_tours_assets` ADD `discounts` MEDIUMTEXT;
