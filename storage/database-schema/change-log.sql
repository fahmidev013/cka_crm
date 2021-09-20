### Add new table task_activity_logs ###

set foreign_key_checks=0;

CREATE  TABLE `task_activity_logs` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `task_id` INT(10) UNSIGNED NULL ,
  `project_id` INT(10) UNSIGNED NULL ,
  `user_id` INT(10) UNSIGNED NULL ,
  `status` VARCHAR(45) NULL ,
  `comment` VARCHAR(255) NULL DEFAULT NULL ,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_task_activity_log_user_id_idx` (`user_id` ASC) ,
  INDEX `fk_task_activity_log_task_id_idx` (`task_id` ASC) ,
  INDEX `fk_task_activity_log_project_id_idx` (`project_id` ASC) ,
  INDEX `idx_task_activity_log_status` (`status` ASC) ,
  CONSTRAINT `fk_task_activity_log_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_task_activity_log_task_id`
    FOREIGN KEY (`task_id` )
    REFERENCES `tasks` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_task_activity_log_project_id`
    FOREIGN KEY (`project_id` )
    REFERENCES `projects` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE=InnoDB DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_general_ci;

### Add new table checklist_items ###

CREATE  TABLE `checklist_items` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL,
  `task_id` INT(10) UNSIGNED NULL ,
  `project_id` INT(10) UNSIGNED NULL ,
  `user_id` INT(10) UNSIGNED NULL ,
  `status` VARCHAR(45) NULL ,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `checked_at` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_checklist_item_user_id_idx` (`user_id` ASC) ,
  INDEX `fk_checklist_item_task_id_idx` (`task_id` ASC) ,
  INDEX `fk_checklist_item_project_id_idx` (`project_id` ASC) ,
  INDEX `idx_checklist_item_status` (`status` ASC) ,
  CONSTRAINT `fk_checklist_item_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_checklist_item_task_id`
    FOREIGN KEY (`task_id` )
    REFERENCES `tasks` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_checklist_item_project_id`
    FOREIGN KEY (`project_id` )
    REFERENCES `projects` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE=InnoDB DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_general_ci;



-- Database change for Point of sale
ALTER TABLE `sales_orders` ADD `pos_order_title` VARCHAR(50) NULL AFTER `pos_discount`;

ALTER TABLE `sales_orders` ADD `pos_tax_on_order` DOUBLE NOT NULL AFTER `pos_order_title`;

CREATE TABLE IF NOT EXISTS `pos_order_shipping` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `pos_order_shipping` ADD `shipping_cost` DOUBLE NULL AFTER `name`;

CREATE TRIGGER `delete_sales_order_details` AFTER DELETE ON `sales_orders` FOR EACH ROW DELETE FROM sales_order_details WHERE sales_order_details.sales_order_id = old.id;

CREATE TRIGGER `delete_pos_shipping` AFTER DELETE ON `sales_orders` FOR EACH ROW Delete FROM pos_order_shipping WHERE pos_order_shipping.order_id = old.id;

INSERT INTO `task_status` (`id`, `name`, `status_order`, `color`) VALUES (NULL, 'Re-open', '7', '#FFA500');

UPDATE `tasks` SET `status` = 5 WHERE `tasks`.`status` = 4;

DELETE FROM task_status WHERE id = 4;

ALTER TABLE `password_resets` CHANGE `token` `token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `purch_orders` ADD `payment_id` VARCHAR(50) NULL AFTER `into_stock_location`;

ALTER TABLE `purch_orders` ADD `payment_term` INT NOT NULL AFTER `paid_amount`;

ALTER TABLE `location` CHANGE `email` `email` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `phone` `phone` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `fax` `fax` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `contact` `contact` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `currency` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `exchange_from`, ADD INDEX `idx_currency_deleted` (`deleted`);

CREATE TABLE `transaction_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `file` varchar(100) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users` ADD `first_name` VARCHAR(32) NOT NULL AFTER `password`, ADD `last_name` VARCHAR(32) NOT NULL AFTER `first_name`;
ALTER TABLE `users` ADD `full_name` VARCHAR(64) NOT NULL AFTER `last_name`;
ALTER TABLE `users` RENAME COLUMN `full_name` TO `full_name`;
ALTER TABLE `item_unit` ADD UNIQUE( `abbr`);

ALTER TABLE `receive_orders` CHANGE `order_receive_no` `order_receive_no` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `sales_orders` ADD `pos_inv_status` ENUM('clear','hold') NOT NULL DEFAULT 'clear' AFTER `payment_term`, ADD `pos_discount` DOUBLE NOT NULL DEFAULT '0' AFTER `pos_inv_status`, ADD `pos_order_title` VARCHAR(50) NULL DEFAULT NULL AFTER `pos_discount`, ADD `pos_tax_on_order` DOUBLE NOT NULL AFTER `pos_order_title`;


ALTER TABLE `sales_orders` ADD `pos_shipping` TEXT NULL AFTER `pos_tax_on_order`;

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'manage_account_type', 'Manage Account Type', 'Manage Account Type', 'account type', NULL, NULL), (NULL, 'add_account_type', 'Add Account Type', 'Add Account Type ', 'account type', NULL, NULL), (NULL, 'edit_account_type', 'Edit Account Type', 'Edit Account Type', 'account type', NULL, NULL), (NULL, 'delete_account_type', 'Delete Account Type', 'Delete Account Type', 'account type', NULL, NULL);

-- here permission_id should be matched with last inseted id of permissions table
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('211', '1'), ('212', '1'), ('213', '1'), ('214', '1');

-- Ticket status color
UPDATE `ticket_status` SET `statuscolor` = '#848484' WHERE `ticket_status`.`id` = 3;

ALTER TABLE `tickets` CHANGE `customer_id` `customer_id` INT(10) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `expenses` CHANGE `type` `income_expense_category_id` INT(11) NOT NULL;

RENAME TABLE 'bank_trans' TO 'bank_transaction';

ALTER TABLE `backup`
ADD INDEX `backups_name_index` (`name` ASC);
, RENAME TO  `backups` ;

ALTER TABLE `bank_account_type`
RENAME TO `account_types`;

ALTER TABLE `currency`
RENAME TO  `currencies` ;

ALTER TABLE `account_types`
ADD INDEX `account_types_name_index` (`name` ASC);

ALTER TABLE `bank_accounts`
RENAME TO  `accounts` ;


ALTER TABLE `accounts`
CHANGE COLUMN `name` `name` VARCHAR(30) NOT NULL ,
CHANGE COLUMN `gl_account_id` `income_expense_category_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `branch_name` `branch_name` VARCHAR(50) NULL DEFAULT NULL ,
CHANGE COLUMN `branch_city` `branch_city` VARCHAR(50) NULL DEFAULT NULL ,
CHANGE COLUMN `swift_code` `swift_code` VARCHAR(100) NULL DEFAULT NULL ,
ADD INDEX `accounts_income_expense_category_id_foreign_idx` (`income_expense_category_id` ASC) ;
ALTER TABLE `accounts`
ADD CONSTRAINT `accounts_income_expense_category_id_foreign`
  FOREIGN KEY (`income_expense_category_id`)
  REFERENCES `income_expense_categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `backups`
DROP COLUMN `updated_at`;
ALTER TABLE `backups` ALTER INDEX `backups_name_index`;

ALTER TABLE `bank_transaction`
RENAME TO  `transactions` ;


ALTER TABLE `accounts`
CHANGE COLUMN `account_name` `name` VARCHAR(150) NOT NULL ,
CHANGE COLUMN `account_no` `account_number` VARCHAR(30) NOT NULL ,
CHANGE COLUMN `gl_account_id` `gl_account_id` INT(10) UNSIGNED NULL ,
CHANGE COLUMN `default_account` `is_default` TINYINT(1) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `deleted` `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 ;

ALTER TABLE `accounts`
ADD INDEX `accounts_currency_id_foreign_idx` (`currency_id` ASC),
ADD INDEX `accounts_name_index` (`name` ASC),
ADD INDEX `accounts_account_number_index` (`account_number` ASC),
ADD INDEX `accounts_bank_name_index` (`bank_name` ASC),
ADD INDEX `accounts_is_default_index` (`is_default` ASC);
ALTER TABLE `accounts`
ADD CONSTRAINT `accounts_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `bank_deposits`
RENAME TO  `deposits`;

ALTER TABLE `deposits`
CHANGE COLUMN `bank_account_id` `account_id` INT(10) UNSIGNED NOT NULL,
CHANGE COLUMN `exchange_rate` `exchange_rate` DOUBLE NOT NULL;

ALTER TABLE `deposits`
ADD CONSTRAINT `deposits_account_id_foreign`
  FOREIGN KEY (`account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `deposits`
ADD INDEX `deposits_account_id_foreign_idx` (`account_id` ASC);
ALTER TABLE `deposits`
ADD CONSTRAINT `deposits_account_id_foreign`
  FOREIGN KEY (`account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `payment_terms`
RENAME TO  `payment_methods`;

ALTER TABLE `payment_methods`
ADD INDEX `payment_methods_name_index` (`name` ASC);

-- Sumon
ALTER TABLE `languages`
CHANGE COLUMN `status` `status` VARCHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'Active' COMMENT '\'Active\', \'Inactive'  , CHANGE COLUMN `default` `is_default` TINYINT(1) NOT NULL  , CHANGE COLUMN `deletable` `is_deletable` TINYINT(1) NULL DEFAULT 1 COMMENT '1, 0 \n1 = deletable\n0 = not deletable'  , CHANGE COLUMN `direction` `direction` VARCHAR(8) NOT NULL DEFAULT 'ltr' COMMENT '\'ltr\', \'rtl\'\nltr = left-to-right direction\nrtl = right-to-left direction'
, ADD INDEX `languages_name_index` (`name` ASC);

--Sumon
ALTER TABLE `lead_sources` COLLATE = utf8_general_ci , CHANGE COLUMN `name` `name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  , CHANGE COLUMN `status` `status` VARCHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'active' COMMENT '(\'active\',\'inactive\')'
, ADD UNIQUE INDEX `lead_sources_name_unique` (`name` ASC) ;

--Sumon
ALTER TABLE `lead_status` COLLATE = utf8_general_ci , CHANGE COLUMN `color` `color` VARCHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL  , CHANGE COLUMN `status` `status` VARCHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT '(\'active\',\'inactive\')'
, ADD UNIQUE INDEX `lead_status_name_unique` (`name` ASC) ;


--Sumon
ALTER TABLE `leads` CHARACTER SET = utf8 , CHANGE COLUMN `status_id` `lead_status_id` INT(11) NOT NULL  , CHANGE COLUMN `source_id` `lead_source_id` INT(11) NOT NULL  , CHANGE COLUMN `assignee_id` `assignee_id` INT(11) NOT NULL  , CHANGE COLUMN `is_lost` `is_lost` TINYINT(1) NOT NULL DEFAULT 0  , CHANGE COLUMN `is_public` `is_public` TINYINT(1) NOT NULL DEFAULT 0  ,
  ADD CONSTRAINT `leads_lead_status_id_foreign`
  FOREIGN KEY (`lead_status_id` )
  REFERENCES `lead_status` (`id` )
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_lead_source_id_foreign`
  FOREIGN KEY (`lead_source_id` )
  REFERENCES `lead_sources` (`id` )
  ON DELETE NO ACTION
  ON UPDATE CASCADE
, ADD INDEX `leads_lead_status_id_foreign_idx` (`lead_status_id` ASC)
, ADD INDEX `leads_lead_source_id_foreign_idx` (`lead_source_id` ASC) ;

ALTER TABLE `leads` CHANGE COLUMN `country_id` `country_id` INT(11) NULL  ;

--Sumon
ALTER TABLE `location` COLLATE = utf8_general_ci , RENAME TO  `locations` ;

ALTER TABLE `locations` CHANGE COLUMN `loc_code` `code` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  , CHANGE COLUMN `location_name` `name` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  , CHANGE COLUMN `phone` `phone` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL  , CHANGE COLUMN `inactive` `inactive` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0 = \'Active\'\n1 = \'Inactive\''
, ADD UNIQUE INDEX `locations_code_unique` (`code` ASC)
, ADD INDEX `locations_name_index` (`name` ASC) ;


-- Sumon
ALTER TABLE `milestones` COLLATE = utf8_general_ci , CHANGE COLUMN `milestone_name` `name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  , CHANGE COLUMN `description` `description` LONGTEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL  , CHANGE COLUMN `visible_to_customer` `is_visible_to_customer` TINYINT(1) NULL DEFAULT 0 COMMENT '1=yes, 0=no'  , CHANGE COLUMN `milestone_order` `order` INT(11) NULL DEFAULT NULL
, ADD INDEX `milestones_name_index` (`name` ASC) ;

-- Sumon
ALTER TABLE `months` COLLATE = utf8_general_ci
, ADD INDEX `months_name_index` (`name` ASC) ;


ALTER TABLE `payment_gateway`
RENAME TO  `payment_gateways`;

ALTER TABLE `payment_gateways`
ADD UNIQUE INDEX `payment_gateways_name_unique` (`name` ASC)


ALTER TABLE `payment_history`
RENAME TO  `payment_histories`;

DROP TABLE `payment_histories`;

ALTER TABLE `permission_role`
RENAME TO  `permission_roles`;

ALTER TABLE `preference`
RENAME TO  `preferences`;

ALTER TABLE `priorities`
ADD INDEX `priorities_name_index` (`name` ASC);

ALTER TABLE `project_settings`
CHANGE COLUMN `setting_value` `setting_value` VARCHAR(16) NULL DEFAULT NULL COMMENT '(\'on\', \'off\')';

ALTER TABLE `project_status`
ADD UNIQUE INDEX `project_statuses_name_unique` (`name` ASC);
, RENAME TO  `project_statuses` ;

ALTER TABLE `projects`
CHANGE COLUMN `project_detail` `detail` LONGTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `project_name` `name` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `projects`
CHANGE COLUMN `position` `status` INT(3) UNSIGNED NOT NULL ,
CHANGE COLUMN `charge_type` `charge_type` INT(3) UNSIGNED NOT NULL COMMENT '1 = Fixed Rate;\n2 = Project Hour;\n3 = Rate Per Hour;' ,
CHANGE COLUMN `improvement` `improvement` INT(3) NOT NULL ,
CHANGE COLUMN `improvement_from_tasks` `improvement_from_task` TINYINT(1) NULL COMMENT '1=yes, 0=no';


ALTER TABLE `projects`
ADD INDEX `projects_customer_id_foreign_idx` (`customer_id` ASC);
ALTER TABLE `projects`
ADD CONSTRAINT `projects_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `purch_orders`
CHANGE COLUMN `inv_type` `inv_type` VARCHAR(16) NOT NULL COMMENT '\'quantity\', \n\'hours\', \n\'amount\'' ,
CHANGE COLUMN `discount_on` `discount_on` VARCHAR(16) NOT NULL COMMENT '\'before\', \n\'after\'' ,
CHANGE COLUMN `tax_type` `tax_type` VARCHAR(16) NOT NULL COMMENT '\'exclusive\', \n\'inclusive\'' ;

ALTER TABLE `purch_orders`
RENAME TO  `purchase_orders`;

ALTER TABLE `item_tax_types`
RENAME TO  `tax_types` ;

ALTER TABLE `tax_types`
ADD INDEX `tax_types_name_index` (`name` ASC);

ALTER TABLE `purch_order_details`
RENAME TO  `purchase_order_details`;

ALTER TABLE `purchase_tax`
RENAME TO  `purchase_taxes`;

ALTER TABLE `purchase_taxes`
CHANGE COLUMN `purch_id` `purchase_order_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `purch_details_id` `purchase_order_details_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `tax_id` `tax_type_id` INT(3) NOT NULL ;

ALTER TABLE `purchase_taxes`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
DROP INDEX `purch_details_id`,
DROP INDEX `purch_id`;

ALTER TABLE `purchase_taxes`
ADD INDEX `purchase_taxes_purchase_order_id_foreign_idx` (`purchase_order_id` ASC),
ADD INDEX `purchase_taxes_purchase_order_details_id_foreign_idx` (`purchase_order_details_id` ASC);
ALTER TABLE `purchase_taxes`
ADD CONSTRAINT `purchase_taxes_purchase_order_id_foreign`
  FOREIGN KEY (`purchase_order_id`)
  REFERENCES `purchase_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `purchase_taxes_purchase_order_details_id_foreign`
  FOREIGN KEY (`purchase_order_details_id`)
  REFERENCES `purchase_order_details` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_taxes`
CHANGE COLUMN `tax_type_id` `tax_type_id` INT(11) NOT NULL ;

ALTER TABLE `tax_types` CHANGE `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `purchase_taxes`
CHANGE COLUMN `tax_type_id` `tax_type_id` INT(11) UNSIGNED NOT NULL ;

ALTER TABLE `purchase_taxes`
ADD INDEX `purchase_taxes_tax_type_id_foreign_idx` (`tax_type_id` ASC);
ALTER TABLE `purchase_taxes`
ADD CONSTRAINT `purchase_taxes_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_type`
RENAME TO  `purchase_types`;

ALTER TABLE `purchase_types`
CHANGE COLUMN `type` `mode` VARCHAR(50) NOT NULL COMMENT 'mode is used instead of type.\nSo, it means what is the type of this purchase.';

ALTER TABLE `leads`
CHANGE COLUMN `assignee_id` `assignee_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `user_id` `user_id` INT(11) UNSIGNED NOT NULL ;

ALTER TABLE `leads`
CHANGE COLUMN `assignee_id` `assignee_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `leads`
ADD INDEX `leads_assignee_id_foreign_idx` (`assignee_id` ASC) ;
ALTER TABLE `leads`
ADD CONSTRAINT `leads_assignee_id_foreign`
  FOREIGN KEY (`assignee_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `leads`
ADD INDEX `leads_user_id_foreign_idx` (`user_id` ASC);
ALTER TABLE `leads`
ADD CONSTRAINT `leads_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `leads`
CHANGE COLUMN `country_id` `country_id` INT(10) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `leads`
ADD INDEX `leads_country_id_foreign_idx` (`country_id` ASC);
ALTER TABLE `leads`
ADD CONSTRAINT `leads_country_id_foreign`
  FOREIGN KEY (`country_id`)
  REFERENCES `countries` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `leads`
DROP FOREIGN KEY `leads_country_id_foreign`;
ALTER TABLE `leads`
ADD CONSTRAINT `leads_country_id_foreign`
  FOREIGN KEY (`country_id`)
  REFERENCES `countries` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `receive_order_details`
COLLATE = utf8_general_ci , RENAME TO  `received_orders_details` ;

ALTER TABLE `received_orders_details`
DROP FOREIGN KEY `receive_order_details_purch_order_id_foreign`;
ALTER TABLE `received_orders_details`
CHANGE COLUMN `purch_order_id` `purchase_order_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `po_details_id` `purchase_order_details_id` INT(10) UNSIGNED NOT NULL ;
ALTER TABLE `received_orders_details`
ADD CONSTRAINT `receive_order_details_purch_order_id_foreign`
  FOREIGN KEY (`purchase_order_id`)
  REFERENCES `purchase_orders` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `receive_orders`
COLLATE = utf8_general_ci , RENAME TO  `received_orders` ;


ALTER TABLE `received_orders`
DROP FOREIGN KEY `receive_orders_purch_order_id_foreign`;

ALTER TABLE `received_orders`
CHANGE COLUMN `purch_order_id` `purchase_order_id` INT(10) UNSIGNED NOT NULL,
DROP INDEX `receive_orders_purch_order_id_foreign` ,
ADD INDEX `receive_orders_purchase_order_id_foreign` (`purchase_order_id` ASC);
ALTER TABLE `received_orders`
ADD CONSTRAINT `receive_orders_purchase_order_id_foreign`
  FOREIGN KEY (`purchase_order_id`)
  REFERENCES `purchase_orders` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `references`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `type` `reference_type` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `reference` `code` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
RENAME TO  `transaction_references`;

ALTER TABLE `bank_transfers`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci , RENAME TO  `transfers`;

ALTER TABLE `received_orders_details`
RENAME TO  `received_order_details`;

ALTER TABLE `transfers`
DROP COLUMN `reference`,
CHANGE COLUMN `memo` `description` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL ,
CHANGE COLUMN `bank_charge` `fee` DOUBLE NOT NULL ;

ALTER TABLE `purchase_types`
CHANGE COLUMN `mode` `purchase_type` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `defaults` `is_default` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 for default.\n0 for otherwise.' ;

ALTER TABLE `transfers`
CHANGE COLUMN `from_currency_id` `from_currency_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `to_currency_id` `to_currency_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `transfered_currency_id` `home_currency_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `transfers_from_account_id_foreign_idx` (`from_account_id` ASC),
ADD INDEX `transfers_to_account_id_foreign_idx` (`to_account_id` ASC);
ALTER TABLE `transfers`
ADD CONSTRAINT `transfers_from_account_id_foreign`
  FOREIGN KEY (`from_account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `transfers_to_account_id_foreign`
  FOREIGN KEY (`to_account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `transfers`
DROP FOREIGN KEY `transfers_from_account_id_foreign`,
DROP FOREIGN KEY `transfers_to_account_id_foreign`;
ALTER TABLE `transfers`
ADD INDEX `transfers_from_currency_id_foreign_idx` (`from_currency_id` ASC) ,
ADD INDEX `transfers_to_currency_id_foreign_idx` (`to_currency_id` ASC) ,
ADD INDEX `transfers_user_id_foreign_idx` (`user_id` ASC) ,
ADD INDEX `transfers_reference_id_foreign_idx` (`reference_id` ASC) ,
ADD INDEX `transfers_home_currency_id_foreign_idx` (`home_currency_id` ASC) ,
ADD INDEX `transfers_payment_method_id_foreign_idx` (`payment_method_id` ASC) ;
ALTER TABLE `transfers`
ADD CONSTRAINT `transfers_from_account_id_foreign`
  FOREIGN KEY (`from_account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_to_account_id_foreign`
  FOREIGN KEY (`to_account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_from_currency_id_foreign`
  FOREIGN KEY (`from_currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_to_currency_id_foreign`
  FOREIGN KEY (`to_currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_reference_id_foreign`
  FOREIGN KEY (`reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_home_currency_id_foreign`
  FOREIGN KEY (`home_currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transfers_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

  ALTER TABLE `transfers`
CHANGE COLUMN `type` `transfer_type` INT(11) NOT NULL ;


ALTER TABLE `activities`
CHANGE COLUMN `visible_to_customer` `visible_to_customer` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 for visible to customer;\n0 for hide;' ,
ADD INDEX `activities_customer_id_foreign_idx` (`customer_id` ASC),
DROP INDEX `activities_customer_id_foreign`;
ALTER TABLE `activities`
ADD CONSTRAINT `activities_project_id_foreign`
  FOREIGN KEY (`project_id`)
  REFERENCES `projects` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `activities_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `activities_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `calendar_events`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `description` `description` TEXT NULL DEFAULT NULL ,
CHANGE COLUMN `noti_time` `notification_start` INT(11) NULL DEFAULT NULL COMMENT 'notification_start, it means how many days/hour/minutes/weeks before it will remind user about the event.' ,
CHANGE COLUMN `noti_every` `notification_repeat_every` TINYINT(4) NULL DEFAULT NULL COMMENT 'notification_repeat_every, it means what is the unit of \'notification_start\' field.\ni.e. : minutes, hours, days, weeks' ;

ALTER TABLE `calendar_events`
CHANGE COLUMN `created_by` `created_by` INT(10) UNSIGNED NOT NULL COMMENT 'created by refers the users table id;\nit is the id who was logged in at the creation time of the event.' ,
ADD INDEX `calendar_events_created_by_foreign_idx` (`created_by` ASC);
ALTER TABLE `calendar_events`
ADD CONSTRAINT `calendar_events_created_by_foreign`
  FOREIGN KEY (`created_by`)
  REFERENCES `users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


ALTER TABLE `checklist_items`
DROP FOREIGN KEY `fk_checklist_item_user_id`,
DROP FOREIGN KEY `fk_checklist_item_task_id`,
DROP FOREIGN KEY `fk_checklist_item_project_id`;
ALTER TABLE `checklist_items`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `status` `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1, 0 and 0 as default;\n0 for unchecked;\n1 for checked;' ,
DROP INDEX `idx_checklist_item_status` ,
DROP INDEX `fk_checklist_item_project_id_idx` ,
DROP INDEX `fk_checklist_item_task_id_idx` ,
DROP INDEX `fk_checklist_item_user_id_idx`;

ALTER TABLE `checklist_items`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `task_id` `task_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'task_id refers the task table\'s id column.' ,
CHANGE COLUMN `project_id` `project_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'project_id refers the projects table\'s id column.' ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'user_id refers the user table\'s id column.' ,
ADD INDEX `checklist_items_project_id_foreign_idx` (`project_id` ASC),
ADD INDEX `checklist_items_task_id_foreign_idx` (`task_id` ASC),
ADD INDEX `checklist_items_user_id_foreign_idx` (`user_id` ASC);
ALTER TABLE `checklist_items`
ADD CONSTRAINT `checklist_items_project_id_foreign`
  FOREIGN KEY (`project_id`)
  REFERENCES `projects` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `checklist_items_task_id_foreign`
  FOREIGN KEY (`task_id`)
  REFERENCES `tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `checklist_items_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `currencies`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `deleted` `is_deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 for active, 1 for inactive(deleted).' ;

ALTER TABLE `cust_branch`
RENAME TO  `customer_branches` ;

ALTER TABLE `customer_branches`
DROP FOREIGN KEY `cust_branch_customer_id_foreign`;
ALTER TABLE `customer_branches`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `br_name` `name` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `br_address` `address` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `br_contact` `contact` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `billing_country_id` `billing_country_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `shipping_country_id` `shipping_country_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
ADD INDEX `customer_branches_customer_id_foreign_idx` (`customer_id` ASC),
ADD INDEX `customer_branches_billing_country_id_foreign_idx` (`billing_country_id` ASC),
ADD INDEX `customer_branches_shipping_country_id_foreign_idx` (`shipping_country_id` ASC),
DROP INDEX `cust_branch_billing_country_id_foreign` ,
DROP INDEX `cust_branch_shipping_country_id_foreign` ,
DROP INDEX `cust_branch_customer_id_foreign` ;
ALTER TABLE `customer_branches`
ADD CONSTRAINT `customer_branches_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `customer_branches_billing_country_id_foreign`
  FOREIGN KEY (`billing_country_id`)
  REFERENCES `countries` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `customer_branches_shipping_country_id_foreign`
  FOREIGN KEY (`shipping_country_id`)
  REFERENCES `countries` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `customer_activations`
ADD INDEX `customer_activations_customer_id_foreign_idx` (`customer_id` ASC);
ALTER TABLE `customer_activations`
ADD CONSTRAINT `customer_activations_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


ALTER TABLE `customer_transactions`
DROP FOREIGN KEY `customer_transactions_payment_type_id_foreign`;
ALTER TABLE `customer_transactions`
COLLATE = utf8_general_ci ,
DROP COLUMN `reference`,
DROP COLUMN `invoice_reference`,
DROP COLUMN `order_reference`,
CHANGE COLUMN `payment_type_id` `payment_method_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `status` `status` VARCHAR(16) NOT NULL DEFAULT 'Approved' COMMENT '\'Pending\', \'Approved\', \'Declined\'' ,
DROP INDEX `customer_transactions_payment_type_id_foreign` ,
ADD INDEX `customer_transactions_payment_method_id_foreign_idx` (`payment_method_id` ASC),
ADD INDEX `customer_transactions_invoice_id_foreign_idx` (`invoice_id` ASC) ,
ADD INDEX `customer_transactions_currency_id_foreign_idx` (`currency_id` ASC);
ALTER TABLE `customer_transactions`
ADD CONSTRAINT `customer_transactions_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `customer_transactions_invoice_id_foreign`
  FOREIGN KEY (`invoice_id`)
  REFERENCES `sales_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `customer_transactions_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transactions` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `customer_transactions_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `customers`
DROP COLUMN `customer_type`,
CHANGE COLUMN `inactive` `is_inactive` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 for active;\n1 for inactive.' ,
CHANGE COLUMN `is_activated` `is_not_verified` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 for vairified;\n1 for not varified.' ;

ALTER TABLE `customers`
COLLATE = utf8_general_ci ,
DROP COLUMN `sales_type`,
CHANGE COLUMN `currency_id` `currency_id` INT(10) UNSIGNED NOT NULL COMMENT 'currency_id refers to currencies tbale id.' ,
ADD INDEX `customers_currency_id_foreign_idx` (`currency_id` ASC);
ALTER TABLE `customers`
ADD CONSTRAINT `customers_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `customers`
DROP FOREIGN KEY `customers_currency_id_foreign`;
ALTER TABLE `customers`
ADD CONSTRAINT `customers_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `deposits`
DROP COLUMN `reference`,
DROP COLUMN `type`,
CHANGE COLUMN `from_gl_account_id` `income_expense_category_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `total_amount` `amount` DOUBLE NOT NULL ,
ADD INDEX `deposits_user_id_foreign_idx` (`user_id` ASC),
ADD INDEX `deposits_income_expense_category_id_foreign_idx` (`income_expense_category_id` ASC),
ADD INDEX `deposits_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC),
ADD INDEX `deposits_payment_method_id_foreign_idx` (`payment_method_id` ASC);
ALTER TABLE `deposits`
ADD CONSTRAINT `deposits_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `deposits_income_expense_category_id_foreign`
  FOREIGN KEY (`income_expense_category_id`)
  REFERENCES `income_expense_categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `deposits_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `deposits_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `email_temp_details`
COLLATE = utf8_general_ci , RENAME TO  `email_templates` ;

ALTER TABLE `email_templates`
CHANGE COLUMN `temp_id` `template_id` INT(11) NOT NULL ,
CHANGE COLUMN `lang` `language_short_name` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
CHANGE COLUMN `type` `template_type` VARCHAR(8) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT '\'email\', \'sms\'' ,
CHANGE COLUMN `lang_id` `language_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `email_templates_language_id_foreign_idx` (`language_id` ASC);
ALTER TABLE `email_templates`
ADD CONSTRAINT `email_templates_language_id_foreign`
  FOREIGN KEY (`language_id`)
  REFERENCES `languages` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `exchange_rates`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
ADD INDEX `exchange_rates_currency_id_foreign_idx` (`currency_id` ASC);
ALTER TABLE `exchange_rates`
ADD CONSTRAINT `exchange_rates_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `expenses`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
DROP COLUMN `reference`,
CHANGE COLUMN `bank_transaction_id` `transaction_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `income_expense_category_id` `income_expense_category_id` INT(11) UNSIGNED NOT NULL ;


ALTER TABLE `expenses`
CHANGE COLUMN `transaction_id` `transaction_id` INT(10) UNSIGNED NOT NULL COMMENT 'transaction_id refers transactions tables\' id;' ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL COMMENT 'user_id refers users tables\' id;' ,
CHANGE COLUMN `transaction_reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL COMMENT 'transaction_reference_id refers transaction_references tables\'  id;' ,
CHANGE COLUMN `income_expense_category_id` `income_expense_category_id` INT(11) UNSIGNED NOT NULL COMMENT 'income_expense_category_id refers income_expense_categories tables\' id;' ,
CHANGE COLUMN `currency_id` `currency_id` INT(10) UNSIGNED NOT NULL COMMENT 'currency_id refers currencies tables\' id;' ,
CHANGE COLUMN `payment_method_id` `payment_method_id` INT(10) UNSIGNED NOT NULL COMMENT 'payment_method_id refers payment_methods tables\' id;' ,
ADD INDEX `expenses_transaction_id_foreign_idx` (`transaction_id` ASC),
ADD INDEX `expenses_user_id_foreign_idx` (`user_id` ASC),
ADD INDEX `expenses_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC),
ADD INDEX `expenses_income_expense_category_id_foreign_idx` (`income_expense_category_id` ASC),
ADD INDEX `expenses_currency_id_foreign_idx` (`currency_id` ASC),
ADD INDEX `expenses_payment_method_id_foreign_idx` (`payment_method_id` ASC);
ALTER TABLE `expenses`
ADD CONSTRAINT `expenses_transaction_id_foreign`
  FOREIGN KEY (`transaction_id`)
  REFERENCES `transactions` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `expenses_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `expenses_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `expenses_income_expense_category_id_foreign`
  FOREIGN KEY (`income_expense_category_id`)
  REFERENCES `income_expense_categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `expenses_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `expenses_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `invoice_payment_terms`
COLLATE = utf8_general_ci , RENAME TO  `payment_terms` ;

ALTER TABLE `payment_terms`
CHANGE COLUMN `terms` `name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
CHANGE COLUMN `defaults` `is_default` TINYINT(1) NOT NULL DEFAULT '1' ;

ALTER TABLE `payment_terms`
CHANGE COLUMN `is_default` `is_default` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 for default;\n0 for otherwise;' ;


ALTER TABLE `items`
COLLATE = utf8_general_ci ,
DROP COLUMN `deleted_status`,
CHANGE COLUMN `stock_id` `stock_id` VARCHAR(30) NOT NULL COMMENT 'every product/service has unique individual stock_id' ,
CHANGE COLUMN `category_id` `stock_category_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `item_type` `item_type` VARCHAR(30) NOT NULL COMMENT '\'product\', \'service\'' ,
CHANGE COLUMN `parent_id` `parent_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `weight_unit_id` `item_unit_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `manage_stock_level` `manage_stock_level` TINYINT(1) NOT NULL COMMENT 'it means the product/service stock management \n1 for on\n0 for off' ,
CHANGE COLUMN `description` `description` VARCHAR(100) NULL ,
CHANGE COLUMN `inactive` `is_inactive` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for inactive \n0 for active' ,
CHANGE COLUMN `alert_item_quantity` `alert_quantity` INT(11) NULL DEFAULT NULL ;

ALTER TABLE `items`
CHANGE COLUMN `item_name` `name` VARCHAR(199) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;

ALTER TABLE `item_unit`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `abbr` `abbreviation` VARCHAR(20) NOT NULL ,
CHANGE COLUMN `name` `name` VARCHAR(32) NOT NULL ,
CHANGE COLUMN `inactive` `is_inactive` TINYINT(1) UNSIGNED NULL DEFAULT '0' COMMENT '1 for inactive; \n0 for active' , RENAME TO  `item_units`;

ALTER TABLE `stock_category`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `description` `name` VARCHAR(50) NOT NULL ,
CHANGE COLUMN `dflt_units` `default_item_unit_id` INT(11) NOT NULL ,
CHANGE COLUMN `inactive` `is_inactive` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for inactive;\n0 for otherwise' , RENAME TO  `stock_categories` ;

ALTER TABLE `item_custom_variants`
CHANGE COLUMN `item_id` `item_id` INT(10) UNSIGNED NOT NULL COMMENT 'item_id refers the items table id column' ;

ALTER TABLE `items`
CHANGE COLUMN `parent_id` `parent_id` INT(10) UNSIGNED NOT NULL COMMENT 'If the product/service  is under a product/service as sub-product/service then it will refer an id of this table, if the product/service is main product/service then the parent id will 0.' ,
ADD INDEX `items_item_unit_id_foreign_idx` (`item_unit_id` ASC),
ADD INDEX `items_stock_category_id_foreign_idx` (`stock_category_id` ASC),
DROP INDEX `weight_unit_id` ,
DROP INDEX `category_id` ;
ALTER TABLE `items`
ADD CONSTRAINT `items_stock_category_id_foreign`
  FOREIGN KEY (`stock_category_id`)
  REFERENCES `stock_categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `items_item_unit_id_foreign`
  FOREIGN KEY (`item_unit_id`)
  REFERENCES `item_units` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `items`
ADD UNIQUE INDEX `items_stock_id_unique` (`stock_id` ASC);

ALTER TABLE `item_custom_variants`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;
ALTER TABLE `item_custom_variants`
ADD CONSTRAINT `item_custom_variants_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `item_custom_variants`
DROP FOREIGN KEY `item_custom_variants_item_id_foreign`;
ALTER TABLE `item_custom_variants`
ADD CONSTRAINT `item_custom_variants_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `items`
DROP FOREIGN KEY `items_item_unit_id_foreign`,
DROP FOREIGN KEY `items_stock_category_id_foreign`;
ALTER TABLE `items`
ADD CONSTRAINT `items_item_unit_id_foreign`
  FOREIGN KEY (`item_unit_id`)
  REFERENCES `item_units` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `items_stock_category_id_foreign`
  FOREIGN KEY (`stock_category_id`)
  REFERENCES `stock_categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `role_user`
COLLATE = utf8_general_ci , RENAME TO  `role_users` ;

ALTER TABLE `sales_order_details`
RENAME TO  `sale_order_details` ;

ALTER TABLE `sales_orders`
RENAME TO  `sale_orders` ;

ALTER TABLE `sales_prices`
RENAME TO  `sale_prices` ;

ALTER TABLE `sales_types`
COLLATE = utf8_general_ci , RENAME TO  `sale_types` ;

ALTER TABLE `sale_types`
DROP COLUMN `factor`;

ALTER TABLE `sale_types`
CHANGE COLUMN `sales_type` `sale_type` VARCHAR(25) NOT NULL ,
CHANGE COLUMN `tax_included` `is_tax_included` TINYINT(1) NOT NULL ,
CHANGE COLUMN `defaults` `is_default` TINYINT(1) NOT NULL ,
ADD INDEX `sale_types_sale_type_index` (`sale_type` ASC) ,
ADD INDEX `sale_types_is_default_index` (`is_default` ASC) ;

ALTER TABLE `sales_tax`
RENAME TO  `sale_taxes` ;

ALTER TABLE `sale_taxes`
DROP INDEX `sales_details_id` ,
DROP INDEX `sales_id` ;

ALTER TABLE `sales_order_details`
RENAME TO `sale_order_details` ;

ALTER TABLE `sale_order_details`
DROP FOREIGN KEY `sales_order_details_sales_order_id_foreign`;
ALTER TABLE `sale_order_details`
DROP INDEX `sales_order_details_sales_order_id_foreign`;



ALTER TABLE `sale_order_details`
DROP COLUMN `is_inventory`,
DROP COLUMN `trans_type`,
CHANGE COLUMN `sales_order_id` `sale_order_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `item_id` `item_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `tax_type_id` `tax_type_id` INT(10) UNSIGNED NULL ,
CHANGE COLUMN `qty_sent` `quantity_sent` DOUBLE NOT NULL DEFAULT 0 ,
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(1) NOT NULL DEFAULT '%' COMMENT '% or $';

ALTER TABLE `sale_order_details`
ADD INDEX `sale_order_details_sale_order_id_foreign_idx` (`sale_order_id` ASC) ,
ADD INDEX `sale_order_details_item_id_foreign_idx` (`item_id` ASC) ,
ADD INDEX `sale_order_details_tax_type_id_foreign_idx` (`tax_type_id` ASC) ;

ALTER TABLE `sale_order_details`
ADD CONSTRAINT `sale_order_details_sale_order_id_foreign`
  FOREIGN KEY (`sale_order_id`)
  REFERENCES `sale_orders` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_order_details_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_order_details_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `sale_order_details`
ADD INDEX `sale_order_details_item_name_index` (`item_name` ASC) ;

ALTER TABLE `sale_taxes`
ADD CONSTRAINT `sale_taxes_sale_order_detail_id_foreign`
  FOREIGN KEY (`sale_order_detail_id`)
  REFERENCES `sale_order_details` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_taxes_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `sale_prices`
DROP FOREIGN KEY `sale_prices_sales_type_id_foreign`;
ALTER TABLE `sale_prices`
CHANGE COLUMN `sales_type_id` `sale_type_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `curr_abrev` `currency_id` INT(10) NOT NULL ;
ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_sales_type_id_foreign`
  FOREIGN KEY (`sale_type_id`)
  REFERENCES `sale_types` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `gobilling_latest`.`sale_types`
DROP INDEX `sale_types_is_default_index` ,
DROP INDEX `sale_types_sales_type_index` ;


ALTER TABLE `sale_prices`
ADD INDEX `sale_prices_sale_type_id_foreign_idx` (`sale_type_id` ASC) ;

ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_sale_type_id_foreign`
  FOREIGN KEY (`sale_type_id`)
  REFERENCES `sale_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `sale_prices`
ADD INDEX `sale_prices_currency_id_foreign_idx` (`currency_id` ASC) VISIBLE;
ALTER TABLE `sale_prices` ALTER INDEX `sale_prices_sale_type_id_foreign_idx` INVISIBLE;
ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_currency_id_foreign`
  FOREIGN KEY ()
  REFERENCES `currencies` ()
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `shipments`
ADD INDEX `shipments_sale_order_id_foreign_idx` (`sale_order_id` ASC) ;

ALTER TABLE `shipments`
ADD CONSTRAINT `shipments_sale_order_id_foreign`
  FOREIGN KEY (`sale_order_id`)
  REFERENCES `sale_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `shipments`
ADD INDEX `shipments_status_index` (`status` ASC) ;

ALTER TABLE `shipment_details`
ADD INDEX `shipments_quantity_index` (`quantity` ASC) ,
ADD INDEX `shipments_unit_price_index` (`unit_price` ASC) ;

ALTER TABLE `shipment_details`
DROP COLUMN `order_no`;

ALTER TABLE `stock_adjustment`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `location` `location_code` VARCHAR(10) NOT NULL ,
CHANGE COLUMN `trans_type` `adjustment_transaction_type` INT(11) NOT NULL , RENAME TO  `stock_adjustments` ;

ALTER TABLE `stock_adjustments`
CHANGE COLUMN `location_code` `location_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `stock_adjustments_user_id_foreign_idx` (`user_id` ASC),
ADD INDEX `stock_adjustments_location_id_foreign_idx` (`location_id` ASC);
ALTER TABLE `stock_adjustments`
ADD CONSTRAINT `stock_adjustments_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `stock_adjustments_location_id_foreign`
  FOREIGN KEY (`location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `stock_adjustment_details`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `adjustment_id` `stock_adjustment_id` INT(10) UNSIGNED NOT NULL COMMENT 'stock_adjustment_id refers to stock_adjustments table id' ,
CHANGE COLUMN `item_id` `item_id` INT(10) UNSIGNED NOT NULL COMMENT 'item_id referes to items table id' ,
CHANGE COLUMN `quantity` `quantity` INT(11) NOT NULL ,
ADD INDEX `stock_adjustment_details_stock_adjustment_id_foreign_idx` (`stock_adjustment_id` ASC),
ADD INDEX `stock_adjustment_details_item_id_foreign_idx` (`item_id` ASC);
ALTER TABLE `stock_adjustment_details`
ADD CONSTRAINT `stock_adjustment_details_stock_adjustment_id_foreign`
  FOREIGN KEY (`stock_adjustment_id`)
  REFERENCES `stock_adjustments` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_adjustment_details_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

DROP TABLE `stock_master`;

ALTER TABLE `stock_moves`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `item_id` `item_id` INT(10) UNSIGNED NOT NULL COMMENT 'item_id refers items table id' ,
CHANGE COLUMN `order_no` `sale_order_id` INT(10) UNSIGNED NOT NULL COMMENT 'sale_order_id refers sale_orders table id' ,
CHANGE COLUMN `trans_type` `transaction_type` INT(11) NOT NULL DEFAULT '0' ,
CHANGE COLUMN `loc_code` `location_id` INT(10) UNSIGNED NOT NULL COMMENT 'location_id refers locations table id' ,
CHANGE COLUMN `tran_date` `transaction_date` DATE NOT NULL ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'user_id refers users table id' ,
CHANGE COLUMN `transaction_reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL COMMENT 'transaction_reference_id refers to transaction_references table id' ,
CHANGE COLUMN `transfer_id` `stock_transfer_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'stock_transfer_id refers stock_transfers table id' ,
CHANGE COLUMN `receive_id` `receive_order_id` INT(10) UNSIGNED NOT NULL COMMENT 'receive_order_id refers receive_orders table id' ,
ADD INDEX `stock_moves_sale_order_id_foreign_idx` (`sale_order_id` ASC),
ADD INDEX `stock_moves_location_id_foreign_idx` (`location_id` ASC),
ADD INDEX `stock_moves_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC),
ADD INDEX `stock_moves_stock_transfer_id_foreign_idx` (`stock_transfer_id` ASC) ,
ADD INDEX `stock_moves_receive_order_id_foreign_idx` (`receive_order_id` ASC) ;
ALTER TABLE `stock_moves`
ADD CONSTRAINT `stock_moves_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_moves_sale_order_id_foreign`
  FOREIGN KEY (`sale_order_id`)
  REFERENCES `sale_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_moves_location_id_foreign`
  FOREIGN KEY (`location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_moves_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_moves_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_moves_stock_transfer_id_foreign`
  FOREIGN KEY (`stock_transfer_id`)
  REFERENCES `stock_transfer` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_moves_receive_order_id_foreign`
  FOREIGN KEY (`receive_order_id`)
  REFERENCES `received_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `stock_transfer`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `source` `source_location_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `destination` `destination_location_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `qty` `quantity` INT(11) NOT NULL , RENAME TO  `stock_transfers` ;

ALTER TABLE `stock_transfers`
ADD INDEX `stock_transfers_user_id_foreign_idx` (`user_id` ASC) ,
ADD INDEX `stock_transfers_source_location_id_foreign_idx` (`source_location_id` ASC) ,
ADD INDEX `stock_transfers_destination_location_id_foreign_idx` (`destination_location_id` ASC) ,
DROP INDEX `stock_transfer_user_id_foreign` ;
ALTER TABLE `stock_transfers`
ADD CONSTRAINT `stock_transfers_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_transfers_source_location_id_foreign`
  FOREIGN KEY (`source_location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `stock_transfers_destination_location_id_foreign`
  FOREIGN KEY (`destination_location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `suppliers`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `supp_name` `name` VARCHAR(150) NOT NULL ,
CHANGE COLUMN `currency_id` `currency_id` INT(10) UNSIGNED NOT NULL COMMENT 'currency_id refers currencies table id' ,
CHANGE COLUMN `country` `country_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `inactive` `is_inactive` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for inactive; \n0 for active.' ,
ADD INDEX `suppliers_currency_id_foreign_idx` (`currency_id` ASC),
ADD INDEX `suppliers_country_id_foreign_idx` (`country_id` ASC);
ALTER TABLE `suppliers`
ADD CONSTRAINT `suppliers_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `suppliers_country_id_foreign`
  FOREIGN KEY (`country_id`)
  REFERENCES `countries` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tags_in`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `reff_id` `reference_id` INT(10) UNSIGNED NOT NULL , RENAME TO  `tags_assigns` ;

ALTER TABLE `users`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `user_id` `added_by` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `role_id` `role_id` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
CHANGE COLUMN `inactive` `is_inactive` TINYINT(1) NOT NULL DEFAULT '0' ,
ADD INDEX `users_role_id_foreign_idx` (`role_id` ASC);
ALTER TABLE `users`
ADD CONSTRAINT `users_role_id_foreign`
  FOREIGN KEY (`role_id`)
  REFERENCES `roles` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `user_departments`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL COMMENT 'user_id refers users table id' ,
CHANGE COLUMN `department_id` `department_id` INT(10) UNSIGNED NOT NULL COMMENT 'department_id refers departments table id' ;
ALTER TABLE `user_departments`
ADD CONSTRAINT `user_departments_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `user_departments_department_id_foreign`
  FOREIGN KEY (`department_id`)
  REFERENCES `departments` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `user_departments`
DROP FOREIGN KEY `user_departments_user_id_foreign`;
ALTER TABLE `user_departments`
ADD CONSTRAINT `user_departments_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `transactions`
COLLATE = utf8_general_ci ,
DROP COLUMN `reference`,
CHANGE COLUMN `trans_type` `transaction_type_name` VARCHAR(100) NOT NULL ,
CHANGE COLUMN `account_no` `account_no` INT(10) UNSIGNED NOT NULL COMMENT 'account_no refers accounts table id' ,
CHANGE COLUMN `trans_date` `transaction_date` DATE NOT NULL ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL COMMENT 'user_id refers users table id' ,
CHANGE COLUMN `reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL COMMENT 'transaction_reference_id referes transaction_references table id' ,
CHANGE COLUMN `type` `transaction_type` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `payment_method` `payment_method_id` INT(10) UNSIGNED NOT NULL COMMENT 'payment_method_id references payment_methods table id' ,
ADD INDEX `transactions_ account_no_foreign_idx` (`account_no` ASC),
ADD INDEX `transactions_user_id_foreign _idx` (`user_id` ASC),
ADD INDEX `transactions_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC),
ADD INDEX `transactions_payment_method_id_foreign_idx` (`payment_method_id` ASC);
ALTER TABLE `transactions`
ADD CONSTRAINT `transactions_ account_no_foreign`
  FOREIGN KEY (`account_no`)
  REFERENCES `accounts` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transactions_user_id_foreign `
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transactions_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `transactions_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `ticket_status`
COLLATE = utf8_general_ci ,
DROP COLUMN `statusorder`,
CHANGE COLUMN `isdefault` `is_default` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for default;\n0 otherwise' ,
CHANGE COLUMN `statuscolor` `statuscolor` VARCHAR(8) NOT NULL , RENAME TO  `ticket_statuses` ;


ALTER TABLE `tickets`
COLLATE = utf8_general_ci ,
DROP COLUMN `admin_replying`,
CHANGE COLUMN `customer_id` `customer_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'customer_id refers customers table id' ,
CHANGE COLUMN `department_id` `department_id` INT(10) UNSIGNED NOT NULL COMMENT 'department_id refers departments table id' ,
CHANGE COLUMN `priority_id` `priority_id` INT(10) UNSIGNED NOT NULL COMMENT 'priority_id refers priorities table id' ,
CHANGE COLUMN `status_id` `status_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'status_id refers ticket_statuses  table id' ,
CHANGE COLUMN `subject` `subject` VARCHAR(255) NOT NULL ,
CHANGE COLUMN `admin` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'user_id refers users table id' ,
CHANGE COLUMN `project_id` `project_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'project_id refers projects table id' ,
CHANGE COLUMN `customer_read` `customer_read` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 for not read yet;\n1 for otherwise' ,
CHANGE COLUMN `user_read` `user_read` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 for not read yet;\n1 for otherwise' ,
CHANGE COLUMN `assigned_member_id` `assigned_member_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'assigned_member_id refers users table id' ,
ADD INDEX `tickets_assigned_member_id_foreign_idx` (`assigned_member_id` ASC),
ADD INDEX `tickets_user_id_foreign_idx` (`user_id` ASC);
ALTER TABLE `tickets`
ADD CONSTRAINT `tickets_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `tickets_department_id_foreign`
  FOREIGN KEY (`department_id`)
  REFERENCES `departments` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `tickets_priority_id_foreign`
  FOREIGN KEY (`priority_id`)
  REFERENCES `priorities` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `tickets_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `tickets_project_id_foreign`
  FOREIGN KEY (`project_id`)
  REFERENCES `projects` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `tickets_assigned_member_id_foreign`
  FOREIGN KEY (`assigned_member_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tickets`
ADD CONSTRAINT `tickets_status_id_foreign`
  FOREIGN KEY (`status_id`)
  REFERENCES `ticket_statuses` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `ticket_replies`
COLLATE = utf8_general_ci ,
DROP COLUMN `admin`,
DROP COLUMN `email`,
DROP COLUMN `name`,
CHANGE COLUMN `ticket_id` `ticket_id` INT(10) UNSIGNED NOT NULL COMMENT 'ticket_id refers tickets table id' ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL COMMENT 'user_id refers users table id in other word (admin id who replied the current one)' ,
CHANGE COLUMN `customer_id` `customer_id` INT(10) UNSIGNED NOT NULL COMMENT 'customer_id refers customers table id' ,
CHANGE COLUMN `attachment` `has_attachment` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 for no attachment;\n1 means this reply has attachment' ,
ADD INDEX `ticket_replies_user_id_foreign_idx` (`user_id` ASC),
ADD INDEX `ticket_replies_customer_id_foreign_idx` (`customer_id` ASC);
ALTER TABLE `ticket_replies`
ADD CONSTRAINT `ticket_replies_ticket_id_foreign`
  FOREIGN KEY (`ticket_id`)
  REFERENCES `tickets` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `ticket_replies_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `ticket_replies_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tax_types`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `defaults` `is_defaults` TINYINT(1) NOT NULL COMMENT '1 for defaults;\n0 for otherwise';

-- Database Schema change for table languages
ALTER TABLE `languages` CHANGE `status` `status` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active', CHANGE `default` `is_default` TINYINT(1) NULL DEFAULT '0', CHANGE `deletable` `deletable` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'Yes', CHANGE `direction` `direction` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ltr';
UPDATE languages SET is_default = 1 WHERE is_default = 2;


ALTER TABLE `task_timer`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci , RENAME TO  `task_timers` ;

ALTER TABLE `task_timers`
ADD INDEX `task_timers_task_id_foreign_idx` (`task_id` ASC),
ADD INDEX `task_timers_user_id_foreign_idx` (`user_id` ASC);
ALTER TABLE `task_timers`
ADD CONSTRAINT `task_timers_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `task_timers_task_id_foreign`
  FOREIGN KEY (`task_id`)
  REFERENCES `tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `task_timers`
DROP FOREIGN KEY `task_timers_task_id_foreign`;
ALTER TABLE `task_timers`
ADD CONSTRAINT `task_timers_task_id_foreign`
  FOREIGN KEY (`task_id`)
  REFERENCES `tasks` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `task_comments`
COLLATE = utf8_general_ci ,
ADD INDEX `task_comments_customer_id_foreign_idx` (`customer_id` ASC),
ADD INDEX `task_comments_user_id_foreign_idx` (`user_id` ASC);
ALTER TABLE `task_comments`
ADD CONSTRAINT `task_comments_task_id_foreign`
  FOREIGN KEY (`task_id`)
  REFERENCES `tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `task_comments_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `task_comments_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `task_assigns`
COLLATE = utf8_general_ci ,
CHANGE COLUMN `assigned_from` `assigned_by` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `is_assigned_from_customer` `is_assigned_by_customer` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 for not;\n1 for yes;' ,
ADD INDEX `task_assigns_assigned_by_foreign_idx` (`assigned_by` ASC);
ALTER TABLE `task_assigns`
ADD CONSTRAINT `task_assigns_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `task_assigns_task_id_foreign`
  FOREIGN KEY (`task_id`)
  REFERENCES `tasks` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `task_assigns_assigned_by_foreign`
  FOREIGN KEY (`assigned_by`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

DROP TABLE `transaction_files`;

ALTER TABLE `files`
COLLATE = utf8_general_ci ,
DROP COLUMN `payment_id`,
DROP COLUMN `invoice_id`,
DROP COLUMN `task_id`,
DROP COLUMN `thumnail_link`,
DROP COLUMN `external_link`,
DROP COLUMN `external`,
DROP COLUMN `visible_to_customer`,
DROP COLUMN `last_activity`,
DROP COLUMN `file_type`,
DROP COLUMN `ticket_reply_id`,
DROP COLUMN `ticket_id`,
DROP COLUMN `project_id`,
DROP COLUMN `customer_id`,
DROP COLUMN `user_id`,
ADD COLUMN `object_type` VARCHAR(32) NULL COMMENT 'The type of file. \ne.g.: task, ticket, customer, user etc. ' AFTER `id`,
ADD COLUMN `object_id` INT(10) NULL COMMENT 'object id refers\ne.g: user_id, customer_id, ticket_id etc.' AFTER `object_type`,
CHANGE COLUMN `file_name` `file_name` VARCHAR(128) NULL DEFAULT NULL ,
CHANGE COLUMN `original_file_name` `original_file_name` VARCHAR(255) NULL DEFAULT NULL ,
DROP INDEX `files_user_id_foreign` ;

ALTER TABLE `files`
ADD INDEX `files_object_type_index` (`object_type` ASC),
ADD INDEX `files_object_id_index` (`object_id` ASC);

ALTER TABLE `activities`
ADD INDEX `activities_full_name_index` (`full_name` ASC);

ALTER TABLE `calendar_events`
ADD INDEX `calendar_events_title_index` (`title` ASC),
ADD INDEX `calendar_events_start_date_index` (`start_date` ASC),
ADD INDEX `calendar_events_end_date_index` (`end_date` ASC),
ADD INDEX `calendar_events_is_public_index` (`is_public` ASC) ;

ALTER TABLE `checklist_items`
ADD INDEX `checklist_items_title_index` (`title` ASC) ,
ADD INDEX `checklist_items_status_index` (`status` ASC) ,
ADD INDEX `checklist_items_checked_at_index` (`checked_at` ASC) ;

ALTER TABLE `countries`
ADD INDEX `countries_country_index` (`country` ASC) ,
ADD INDEX `countries_code_index` (`code` ASC) ;

ALTER TABLE `currencies`
ADD INDEX `currencies_name_index` (`name` ASC) ,
ADD INDEX `currencies_is_deleted_index` (`is_deleted` ASC) ;

ALTER TABLE `custom_item_orders`
ADD INDEX `custom_item_orders_order_no_index` (`order_no` ASC) ,
ADD INDEX `custom_item_orders_name_index` (`name` ASC) ;

ALTER TABLE `customer_branches`
ADD INDEX `customer_branches_name_index` (`name` ASC) ,
ADD INDEX `customer_branches_address_index` (`address` ASC) ,
ADD INDEX `customer_branches_contact_index` (`contact` ASC) ,
ADD INDEX `customer_branches_billing_state_index` (`billing_state` ASC) ,
ADD INDEX `customer_branches_shipping_state_index` (`shipping_state` ASC) ;

ALTER TABLE `customer_transactions`
ADD INDEX `customer_transactions_transaction_date_index` (`transaction_date` ASC) ,
ADD INDEX `customer_transactions_amount_index` (`amount` ASC) ,
ADD INDEX `customer_transactions_incoming_amount_index` (`incoming_amount` ASC) ,
ADD INDEX `customer_transactions_status_index` (`status` ASC) ;

ALTER TABLE `customers`
ADD INDEX `customers_name_index` (`name` ASC) ,
ADD INDEX `customers_first_name_index` (`first_name` ASC) ,
ADD INDEX `customers_last_name_index` (`last_name` ASC) ,
ADD INDEX `customers_email_index` (`email` ASC) ,
ADD INDEX `customers_phone_index` (`phone` ASC) ,
ADD INDEX `customers_is_inactive_index` (`is_inactive` ASC) ,
ADD INDEX `customers_is_not_verified_index` (`is_not_verified` ASC) ;

ALTER TABLE `departments`
ADD INDEX `departments_name_index` (`name` ASC) ;

ALTER TABLE `deposits`
ADD INDEX `deposits_transaction_date_index` (`transaction_date` ASC) ,
ADD INDEX `deposits_amount_index` (`amount` ASC) ;

ALTER TABLE `email_templates`
ADD INDEX `email_templates_language_short_name_index` (`language_short_name` ASC) ;

ALTER TABLE `exchange_rates`
ADD INDEX `exchange_rates_date_index` (`date` ASC);

ALTER TABLE `expenses`
ADD INDEX `expenses_amount_index` (`amount` ASC),
ADD INDEX `expenses_transaction_date_index` (`transaction_date` ASC);

ALTER TABLE `income_expense_categories`
CHANGE COLUMN `type` `category_type` VARCHAR(15) NOT NULL ,
ADD INDEX `income_expense_categories_name_index` (`name` ASC) ,
ADD INDEX `income_expense_categories_category_type_index` (`category_type` ASC);

ALTER TABLE `item_custom_variants`
ADD INDEX `item_custom_variants_variant_title_index` (`variant_title` ASC) ;

ALTER TABLE `item_units`
ADD INDEX `item_units_abbreviation_index` (`abbreviation` ASC) ,
ADD INDEX `item_units_name_index` (`name` ASC) ,
ADD INDEX `item_units_is_inactive_index` (`is_inactive` ASC) ;
ALTER TABLE `item_units` RENAME INDEX `abbr` TO `item_units_abbreviation`;

ALTER TABLE `items`
ADD INDEX `items_item_type_index` (`item_type` ASC) ,
ADD INDEX `items_name_index` (`name` ASC) ,
ADD INDEX `items_item_unit_id_index` (`item_unit_id` ASC) ,
ADD INDEX `items_manage_stock_level_index` (`manage_stock_level` ASC) ,
ADD INDEX `items_is_inactive_index` (`is_inactive` ASC) ;

ALTER TABLE `languages`
ADD INDEX `languages_short_name_index` (`short_name` ASC) ,
ADD INDEX `languages_is_default_index` (`is_default` ASC) ,
ADD INDEX `languages_status_index` (`status` ASC) ;

ALTER TABLE `lead_sources`
ADD INDEX `lead_sources_status_index` (`status` ASC) ;

ALTER TABLE `lead_status`
ADD INDEX `lead_status_status_index` (`status` ASC) ;

ALTER TABLE `leads`
ADD INDEX `leads_first_name_index` (`first_name` ASC) ,
ADD INDEX `leads_last_name_index` (`last_name` ASC) ,
ADD INDEX `leads_email_index` (`email` ASC) ,
ADD INDEX `leads_state_index` (`state` ASC) ,
ADD INDEX `leads_phone_index` (`phone` ASC) ,
ADD INDEX `leads_last_contact_index` (`last_contact` ASC) ,
ADD INDEX `leads_is_lost_index` (`is_lost` ASC) ,
ADD INDEX `leads_is_public_index` (`is_public` ASC);

ALTER TABLE `locations`
ADD INDEX `locations_phone_index` (`phone` ASC) ,
ADD INDEX `locations_email_index` (`email` ASC) ,
ADD INDEX `locations_inactive_index` (`inactive` ASC) ;

ALTER TABLE `milestones`
ADD INDEX `milestones_due_date_index` (`due_date` ASC) ;

ALTER TABLE `notes`
ADD INDEX `notes_subject_index` (`subject` ASC) ;

ALTER TABLE `payment_methods`
ADD INDEX `payment_methods_defaults_index` (`defaults` ASC);

ALTER TABLE `payment_terms`
ADD INDEX `payment_terms_name_index` (`name` ASC) ,
ADD INDEX `payment_terms_is_default_index` (`is_default` ASC) ;

ALTER TABLE `permissions`
ADD INDEX `permissions_display_name_index` (`display_name` ASC) ,
ADD INDEX `permissions_permission_group_index` (`permission_group` ASC) ;

ALTER TABLE `preferences`
ADD INDEX `preferences_category_index` (`category` ASC) ,
ADD INDEX `preferences_field_index` (`field` ASC) ,
ADD INDEX `preferences_value_index` (`value` ASC) ;

ALTER TABLE `project_settings`
ADD INDEX `project_settings_setting_label_index` (`setting_label` ASC) ,
ADD INDEX `project_settings_setting_value_index` (`setting_value` ASC) ;

ALTER TABLE `projects`
ADD INDEX `projects_name_index` (`name` ASC) ,
ADD INDEX `projects_status_index` (`status` ASC) ,
ADD INDEX `projects_project_begin_date_index` (`project_begin_date` ASC) ,
ADD INDEX `projects_project_due_date_index` (`project_due_date` ASC) ,
ADD INDEX `projects_project_type_index` (`project_type` ASC) ;

ALTER TABLE `purchase_order_details`
DROP FOREIGN KEY `purch_order_details_item_id_foreign`;
ALTER TABLE `purchase_order_details`
DROP INDEX `purch_order_details_item_id_foreign` ,
DROP INDEX `purch_order_details_purch_order_id_foreign` ;

ALTER TABLE `purchase_order_details`
CHANGE COLUMN `purch_order_id` `purchase_order_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `description` `description` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(12) NOT NULL COMMENT 'types are :\n\'%\',    \'$\'' ,
CHANGE COLUMN `sorting_no` `sorting_no` TINYINT(1) NOT NULL ,
CHANGE COLUMN `qty_invoiced` `is_qty_invoiced` TINYINT(1) NOT NULL DEFAULT '0' ,
CHANGE COLUMN `is_inventory` `is_inventory` TINYINT(1) NOT NULL ;

ALTER TABLE `purchase_prices`
ADD INDEX `purchase_prices_price_index` (`price` ASC);

ALTER TABLE `purchase_types`
ADD INDEX `purchase_types_purchase_type_index` (`purchase_type` ASC) ,
ADD INDEX `purchase_types_is_default_index` (`is_default` ASC) ;

ALTER TABLE `received_order_details`
ADD INDEX `received_order_details_purchase_order_details_id_index` (`purchase_order_details_id` ASC) ,
ADD INDEX `received_order_details_item_name_index` (`item_name` ASC) ;

ALTER TABLE `roles`
ADD INDEX `roles_display_name_index` (`display_name` ASC) ;


ALTER TABLE `stock_categories`
ADD INDEX `stock_categories_name_index` (`name` ASC) ,
ADD INDEX `stock_categories_is_inactive_index` (`is_inactive` ASC) ;

ALTER TABLE `stock_moves`
ADD INDEX `stock_moves_transaction_type_index` (`transaction_type` ASC) ,
ADD INDEX `stock_moves_transaction_date_index` (`transaction_date` ASC) ,
ADD INDEX `stock_moves_price_index` (`price` ASC) ;

ALTER TABLE `stock_transfers`
ADD INDEX `stock_transfers_transfer_date_index` (`transfer_date` ASC);

ALTER TABLE `supplier_transactions`
ADD INDEX `supplier_transactions_transaction_date_index` (`transaction_date` ASC) ,
ADD INDEX `supplier_transactions_incoming_amount_index` (`incoming_amount` ASC) ,
ADD INDEX `supplier_transactions_amount_index` (`amount` ASC) ,
ADD INDEX `supplier_transactions_purchase_order_id_foreign_idx` (`purchase_order_id` ASC) ,
ADD INDEX `supplier_transactions_payment_method_id_foreign_idx` (`payment_method_id` ASC) ;
ALTER TABLE `supplier_transactions`



ALTER TABLE `sale_orders`
DROP COLUMN `order_type`,
DROP COLUMN `order_reference`,
DROP COLUMN `customer_ref`,
CHANGE COLUMN `trans_type` `transaction_type` VARCHAR(15) NOT NULL ,
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(10) NOT NULL COMMENT 'percent or flat' ,
CHANGE COLUMN `tax_type` `tax_type` VARCHAR(10) NOT NULL COMMENT 'exclusive or inclusive' ,
CHANGE COLUMN `reference` `transaction_reference_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `comments` `comment` TEXT NULL DEFAULT NULL ,
CHANGE COLUMN `comment_check` `has_comment` TINYINT(1) NOT NULL ,
CHANGE COLUMN `ord_date` `order_date` DATE NOT NULL ,
CHANGE COLUMN `from_stk_loc` `location_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `payment_id` `payment_method_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `inv_type` `unit` VARCHAR(15) NOT NULL COMMENT 'quantity, hours, amount' ,
CHANGE COLUMN `discount_on` `discount_on` VARCHAR(15) NOT NULL ,
CHANGE COLUMN `currency_id` `currency_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `m_tax` `has_tax` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_detail_description` `has_description` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_item_discount` `has_item_discount` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_shn` `has_shn` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_sub_discount` `has_sub_discount` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_sub_shipping` `has_shipping_charge` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_sub_custom_amount` `has_custom_charge` TINYINT(1) NOT NULL ,
CHANGE COLUMN `s_other_discount` `other_discount_amount` DOUBLE NULL DEFAULT NULL ,
CHANGE COLUMN `s_other_discount_type` `other_discount_type` VARCHAR(1) NOT NULL COMMENT '% or $' ,
CHANGE COLUMN `s_shipping` `shipping_charge` DOUBLE NULL DEFAULT NULL ,
CHANGE COLUMN `s_custom_amount_title` `custom_charge_title` VARCHAR(199) NULL DEFAULT NULL ,
CHANGE COLUMN `s_custom_amount` `custom_charge_amount` DOUBLE NOT NULL ,
CHANGE COLUMN `paid_amount` `paid` DOUBLE NOT NULL DEFAULT 0 ,
CHANGE COLUMN `payment_term` `invoice_payment_term_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `pos_inv_status` `pos_invoice_status` VARCHAR(10) NOT NULL DEFAULT 'clear' COMMENT 'clear or hold' ,
CHANGE COLUMN `pos_discount` `pos_discount_amount` DOUBLE NOT NULL DEFAULT 0 ;



ALTER TABLE `sale_orders`
CHANGE COLUMN `branch_id` `customer_branch_id` INT(10) UNSIGNED NOT NULL ; #changed by jalal uddin

ALTER TABLE `sale_orders`
ADD INDEX `sale_orders_project_id_foreign_idx` (`project_id` ASC),
ADD INDEX `sale_orders_customer_id_foreign_idx` (`customer_id` ASC),
ADD INDEX `sale_orders_customer_branch_id_foreign_idx` (`customer_branch_id` ASC),
ADD INDEX `sale_orders_user_id_foreign_idx` (`user_id` ASC),
ADD INDEX `sale_orders_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC),
ADD INDEX `sale_orders_lcoation_id_foreign_idx` (`location_id` ASC),
ADD INDEX `sale_orders_payment_method_id_foreign_idx` (`payment_method_id` ASC),
ADD INDEX `sale_orders_currency_id_foreign_idx` (`currency_id` ASC),
ADD INDEX `sale_orders_invoice_payment_term_id_foreign_idx` (`invoice_payment_term_id` ASC);

ALTER TABLE `sale_orders`
ADD CONSTRAINT `sale_orders_project_id_foreign`
  FOREIGN KEY (`project_id`)
  REFERENCES `projects` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_customer_branch_id_foreign`
  FOREIGN KEY (`customer_branch_id`)
  REFERENCES `customer_branches` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_lcoation_id_foreign`
  FOREIGN KEY (`location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_invoice_payment_term_id_foreign`
  FOREIGN KEY (`invoice_payment_term_id`)
  REFERENCES `invoice_payment_terms` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;  #changed by jalal uddin


ALTER TABLE `sale_order_details`
DROP COLUMN `is_inventory`,
DROP COLUMN `trans_type`,
CHANGE COLUMN `sales_order_id` `sale_order_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `item_id` `item_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `tax_type_id` `tax_type_id` INT(10) UNSIGNED NULL ,
CHANGE COLUMN `qty_sent` `quantity_sent` DOUBLE NOT NULL DEFAULT 0 ,
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(1) NOT NULL DEFAULT '%' COMMENT '% or $' ;  #changed by jalal uddin

ALTER TABLE `sale_order_details`
ADD INDEX `sale_order_details_sale_order_id_foreign_idx` (`sale_order_id` ASC) ,
ADD INDEX `sale_order_details_item_id_foreign_idx` (`item_id` ASC) ,
ADD INDEX `sale_order_details_tax_type_id_foreign_idx` (`tax_type_id` ASC) ;
;
ALTER TABLE `sale_order_details`
ADD CONSTRAINT `sale_order_details_sale_order_id_foreign`
  FOREIGN KEY (`sale_order_id`)
  REFERENCES `sale_orders` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_order_details_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_order_details_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `sale_order_details`
ADD INDEX `sale_order_details_item_name_index` (`item_name` ASC) ; #changed by jalal uddin

ALTER TABLE `sale_taxes`
ADD INDEX `sale_taxes_sale_order_detail_id_foreign_idx` (`sale_order_detail_id` ASC) ,
ADD INDEX `sale_taxes_tax_type_id_foreign_idx` (`tax_type_id` ASC) ;

ALTER TABLE `sale_taxes`
ADD CONSTRAINT `sale_taxes_sale_order_detail_id_foreign`
  FOREIGN KEY (`sale_order_detail_id`)
  REFERENCES `sale_order_details` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_taxes_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `sale_prices`
DROP FOREIGN KEY `sale_prices_sales_type_id_foreign`;
ALTER TABLE `sale_prices`
CHANGE COLUMN `sales_type_id` `sale_type_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `curr_abrev` `currency_id` INT(10) NOT NULL ;
ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_sales_type_id_foreign`
  FOREIGN KEY (`sale_type_id`)
  REFERENCES `sale_types` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `sale_prices`
DROP FOREIGN KEY `sale_prices_sales_type_id_foreign`;

ALTER TABLE `sale_prices`
ADD INDEX `sale_prices_sale_type_id_foreign_idx` (`sale_type_id` ASC) ;

ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_sale_type_id_foreign`
  FOREIGN KEY (`sale_type_id`)
  REFERENCES `sale_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;  #changed by jalal uddin

ALTER TABLE `sale_prices`
ADD INDEX `sale_prices_currency_id_foreign_idx` (`currency_id` ASC) ;

ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `sale_prices`
ADD INDEX `sale_prices_currency_id_foreign_idx` (`currency_id` ASC) VISIBLE;
ALTER TABLE `sale_prices` ALTER INDEX `sale_prices_sale_type_id_foreign_idx` INVISIBLE;
ALTER TABLE `sale_prices`
ADD CONSTRAINT `sale_prices_currency_id_foreign`
  FOREIGN KEY ()
  REFERENCES `currencies` ()
  ON DELETE NO ACTION
  ON UPDATE NO ACTION; # changed by jalal uddin

ALTER TABLE `shipments`
DROP COLUMN `trans_type`,
CHANGE COLUMN `order_no` `sale_order_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `comments` `comment` TEXT NOT NULL ,
CHANGE COLUMN `status` `status` VARCHAR(10) NOT NULL DEFAULT 0 ; # changed by jalal uddin


ALTER TABLE `supplier_transactions`
DROP FOREIGN KEY `supplier_transactions_payment_type_id_foreign`;
ALTER TABLE `supplier_transactions`
DROP COLUMN `purch_order_reference`,
DROP COLUMN `reference`,
CHANGE COLUMN `reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `purchase_id` `purchase_order_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `payment_type_id` `payment_method_id` INT(10) UNSIGNED NOT NULL ;
ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_payment_type_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `supplier_transactions`
DROP FOREIGN KEY `supplier_transactions_supplier_id_foreign`,
DROP FOREIGN KEY `supplier_transactions_user_id_foreign`,
DROP FOREIGN KEY `supplier_transactions_payment_type_id_foreign`;
ALTER TABLE `supplier_transactions`
DROP INDEX `supplier_transactions_payment_type_id_foreign` ;
;
ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_supplier_id_foreign`
  FOREIGN KEY (`supplier_id`)
  REFERENCES `suppliers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `supplier_transactions_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;  #changed by jalal uddin

ALTER TABLE `supplier_transactions`
ADD INDEX `supplier_transactions_currency_id_foreign_idx` (`currency_id` ASC) ;
;
ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `supplier_transactions`
ADD INDEX `supplier_transactions_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC) ,
ADD INDEX `supplier_transactions_purchase_order_id_foreign_idx` (`purchase_order_id` ASC) ,
ADD INDEX `supplier_transactions_payment_method_id_foreign_idx` (`payment_method_id` ASC) ;
;
ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `supplier_transactions_purchase_order_id_foreign`
  FOREIGN KEY (`purchase_order_id`)
  REFERENCES `purchase_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `supplier_transactions_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `suppliers`
ADD INDEX `suppliers_name_index` (`name` ASC) ,
ADD INDEX `suppliers_email_index` (`email` ASC) ,
ADD INDEX `suppliers_contact_index` (`contact` ASC) ,
ADD INDEX `suppliers_state_index` (`state` ASC) ,
ADD INDEX `suppliers_is_inactive_index` (`is_inactive` ASC) ;

ALTER TABLE `tags`
ADD INDEX `tags_name_index` (`name` ASC);

ALTER TABLE `tags_assigns`
CHANGE COLUMN `type` `tag_type` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
ADD INDEX `tags_assigns_tag_type_index` (`tag_type` ASC) ;

ALTER TABLE `ticket_statuses`
ADD INDEX `ticket_statuses_name_index` (`name` ASC) ,
ADD INDEX `ticket_statuses_is_default_index` (`is_default` ASC) ;

ALTER TABLE `ticket_replies`
ADD INDEX `ticket_replies_date_index` (`date` ASC) ,
ADD INDEX `ticket_replies_has_attachment_index` (`has_attachment` ASC) ;

ALTER TABLE `tickets`
ADD INDEX `tickets_email_index` (`email` ASC) ,
ADD INDEX `tickets_name_index` (`name` ASC) ,
ADD INDEX `tickets_subject_index` (`subject` ASC) ,
ADD INDEX `tickets_date_index` (`date` ASC) ,
ADD INDEX `tickets_last_reply_index` (`last_reply` ASC) ;

ALTER TABLE `transactions`
ADD INDEX `transactions_amount_index` (`amount` ASC) ,
ADD INDEX `transactions_transaction_type_name_index` (`transaction_type_name` ASC) ,
ADD INDEX `transactions_transaction_date_index` (`transaction_date` ASC) ,
ADD INDEX `transactions_transaction_type_index` (`transaction_type` ASC) ;

ALTER TABLE `transfers`
ADD INDEX `transfers_transaction_date_index` (`transaction_date` ASC) ,
ADD INDEX `transfers_transfer_type_index` (`transfer_type` ASC) ,
ADD INDEX `transfers_amount_index` (`amount` ASC) ,
ADD INDEX `transfers_incoming_amount_index` (`incoming_amount` ASC) ;

ALTER TABLE `users`
ADD INDEX `users_first_name_index` (`first_name` ASC) ,
ADD INDEX `users_last_name_index` (`last_name` ASC) ,
ADD INDEX `users_full_name_index` (`full_name` ASC) ,
ADD INDEX `users_email_index` (`email` ASC) ,
ADD INDEX `users_is_inactive_index` (`is_inactive` ASC) ;

ALTER TABLE `task_statuses`
CHARACTER SET = utf8 , COLLATE = utf8_general_ci ,
CHANGE COLUMN `id` `id` INT(10) NOT NULL ;

ALTER TABLE `task_status`
RENAME TO  `task_statuses`;

ALTER TABLE `task_statuses`
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `tasks`
ADD INDEX `tasks_task_status_id_foreign_idx` (`task_status_id` ASC) ;
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `tasks`
DROP COLUMN `deadline_notified`,
DROP COLUMN `invoice_id`,
DROP COLUMN `billed`,
CHANGE COLUMN `priority` `priority_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ,
CHANGE COLUMN `added_from` `user_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `status` `task_status_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `recurring` `is_recurring` TINYINT(1) UNSIGNED NOT NULL ,
CHANGE COLUMN `retated_to_id` `retated_to_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `related_to_type` `related_to_type` VARCHAR(10) NOT NULL COMMENT 'project, customer, ticket' ,
CHANGE COLUMN `custom_recurring` `is_custom_recurring` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `billable` `is_billable` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `visible_to_customer` `is_visible_to_customer` TINYINT(1) UNSIGNED NULL DEFAULT 0 ;  #changed by jalal uddin

ALTER TABLE `tasks`
CHANGE COLUMN `task_status_id` `task_status_id` INT(11) UNSIGNED NOT NULL ; #changed by jalal uddin

ALTER TABLE `tasks`
ADD INDEX `tasks_priority_id_foreign_idx` (`priority_id` ASC) ;

ALTER TABLE `tasks`
ADD CONSTRAINT `tasks_priority_id_foreign`
  FOREIGN KEY (`priority_id`)
  REFERENCES `priorities` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tasks`
ADD INDEX `tasks_user_id_foreign_idx` (`user_id` ASC) ;

ALTER TABLE `tasks`
ADD CONSTRAINT `tasks_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tasks`
ADD CONSTRAINT `tasks_task_status_id_foreign`
  FOREIGN KEY (`task_status_id`)
  REFERENCES `task_statuses` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE; #changed by jalal uddin

ALTER TABLE `tasks`
ADD INDEX `tasks_milestone_id_foreign_idx` (`milestone_id` ASC) ;

ALTER TABLE `tasks`
ADD CONSTRAINT `tasks_milestone_id_foreign`
  FOREIGN KEY (`milestone_id`)
  REFERENCES `milestones` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tasks`
ADD CONSTRAINT `tasks_task_status_id_foreign`
  FOREIGN KEY (`task_status_id`)
  REFERENCES `task_statuses`(`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;  # changed by jalal uddin

ALTER TABLE `tasks`
ADD INDEX `tasks_name_index` (`name` ASC) ; # changed by jalal

ALTER TABLE `tax_types`
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `items`
ADD COLUMN `tax_type_id` INT(10) UNSIGNED NULL AFTER `name`,
ADD INDEX `items_tax_type_id_foreign_idx` (`tax_type_id` ASC) ;

ALTER TABLE `items`
ADD CONSTRAINT `items_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_order_details`
CHANGE COLUMN `is_qty_invoiced` `quantity_invoiced` DOUBLE NOT NULL ;

ALTER TABLE `stock_categories`
CHANGE COLUMN `default_item_unit_id` `default_item_unit_id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `stock_categories`
ADD INDEX `stock_categories_default_item_unit_id_foreign_idx` (`default_item_unit_id` ASC);

ALTER TABLE `stock_categories`
ADD CONSTRAINT `stock_categories_default_item_unit_id_foreign`
  FOREIGN KEY (`default_item_unit_id`)
  REFERENCES `stock_categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `stock_moves` CHANGE `sale_order_id` `sale_order_id` INT(10) UNSIGNED NULL COMMENT 'sale_order_id refers sale_orders table id';

ALTER TABLE `stock_moves` CHANGE `transaction_reference_id` `transaction_reference_id` INT(10) UNSIGNED NULL COMMENT 'transaction_reference_id refers to transaction_references table id';

ALTER TABLE `stock_moves` CHANGE `receive_order_id` `receive_order_id` INT(10) UNSIGNED NULL COMMENT 'receive_order_id refers receive_orders table id';

ALTER TABLE `items`
ADD COLUMN `weight_unit_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `weight`,
CHANGE COLUMN `item_unit_id` `item_unit_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `name`,
ADD INDEX `items_weight_unit_id_foreign_idx` (`weight_unit_id` ASC);

ALTER TABLE `items`
ADD CONSTRAINT `items_weight_unit_id_foreign`
  FOREIGN KEY (`weight_unit_id`)
  REFERENCES `item_units` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `items` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `items` CHANGE `weight` `weight` DOUBLE NULL DEFAULT NULL;

ALTER TABLE `transactions` CHANGE `account_no` `account_id` INT(10) UNSIGNED NOT NULL COMMENT 'account_id refers accounts table id'

INSERT INTO `preferences` (`id`, `category`, `field`, `value`) VALUES (NULL, 'company', 'company_icon', 'fav.1573734567.ico');

ALTER TABLE `customer_branches` DROP `address`

ALTER TABLE `customers`
CHANGE `is_not_verified` `is_verified` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for vairified;0 for not varified.';

ALTER TABLE `customers`
ADD `customer_type` VARCHAR(16) NULL DEFAULT NULL AFTER `currency_id`;

ALTER TABLE `users` CHANGE `role_id` `role_id` INT(10) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `users`
CHANGE `added_by` `added_by` INT(10) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `users`
ADD INDEX `users_added_by_foreign_idx` (`added_by` ASC);

ALTER TABLE `users`
ADD CONSTRAINT `users_added_by_foreign`
  FOREIGN KEY (`added_by`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `stock_categories`
DROP FOREIGN KEY `stock_categories_default_item_unit_id_foreign`;
ALTER TABLE `stock_categories`
CHANGE COLUMN `default_item_unit_id` `item_unit_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `stock_categories_item_unit_id_foreign_idx` (`item_unit_id` ASC),
DROP INDEX `stock_categories_default_item_unit_id_foreign_idx`;

ALTER TABLE `stock_categories`
ADD CONSTRAINT `stock_categories_item_unit_id_foreign`
  FOREIGN KEY (`item_unit_id`)
  REFERENCES `item_units` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `users`
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ;

ALTER TABLE `projects`
CHANGE COLUMN `status` `project_status_id` INT(3) UNSIGNED NOT NULL ;
ALTER TABLE `projects`
ADD CONSTRAINT `projects_project_status_id_foreign`
  FOREIGN KEY (`project_status_id`)
  REFERENCES `project_statuses` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `projects`
DROP FOREIGN KEY `projects_customer_id_foreign`,
DROP FOREIGN KEY `projects_project_status_id_foreign`;
ALTER TABLE `projects`
ADD CONSTRAINT `projects_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `projects_project_status_id_foreign`
  FOREIGN KEY (`project_status_id`)
  REFERENCES `project_statuses` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tickets`
DROP FOREIGN KEY `tickets_status_id_foreign`;
ALTER TABLE `tickets`
CHANGE COLUMN `status_id` `task_status_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'status_id refers ticket_statuses  table id' ,
DROP INDEX `tickets_status_id_foreign` ,
ADD INDEX `tickets_task_status_id_foreign` (`task_status_id` ASC);

ALTER TABLE `tickets`
ADD CONSTRAINT `tickets_task_status_id_foreign`
  FOREIGN KEY (`task_status_id`)
  REFERENCES `ticket_statuses` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `tickets`
DROP FOREIGN KEY `tickets_task_status_id_foreign`;
ALTER TABLE `tickets`
CHANGE COLUMN `task_status_id` `ticket_status_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'status_id refers ticket_statuses  table id' ,
DROP INDEX `tickets_task_status_id_foreign` ,
ADD INDEX `tickets_ticket_status_id_foreign` (`ticket_status_id` ASC);

ALTER TABLE `tickets`
ADD CONSTRAINT `tickets_ticket_status_id_foreign`
  FOREIGN KEY (`ticket_status_id`)
  REFERENCES `ticket_statuses` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `sale_orders`
DROP FOREIGN KEY `sale_orders_transaction_reference_id_foreign`;
ALTER TABLE `sale_orders`
CHANGE COLUMN `transaction_reference_id` `reference` VARCHAR(32) NOT NULL ,
DROP INDEX `sale_orders_transaction_reference_id_foreign_idx`;

ALTER TABLE `lead_status`
RENAME TO  `lead_statuses` ;

ALTER TABLE `sale_orders`
DROP FOREIGN KEY `sale_orders_invoice_payment_term_id_foreign`;
ALTER TABLE `sale_orders`
CHANGE COLUMN `invoice_payment_term_id` `payment_term_id` INT(11) UNSIGNED NOT NULL ,
ADD INDEX `sale_orders_payment_term_id_foreign_idx` (`payment_term_id` ASC),
DROP INDEX `sale_orders_invoice_payment_term_id_foreign_idx` ;

ALTER TABLE `sale_orders`
ADD CONSTRAINT `sale_orders_payment_term_id_foreign`
  FOREIGN KEY (`payment_term_id`)
  REFERENCES `payment_terms` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

set foreign_key_checks=0;
ALTER TABLE `sale_orders`
DROP FOREIGN KEY `sale_orders_payment_term_id_foreign`;

ALTER TABLE `sale_orders`
CHANGE COLUMN `payment_term_id` `payment_term_id` VARCHAR(50) NOT NULL,
DROP INDEX `sale_orders_payment_term_id_foreign_idx`;

ALTER TABLE `projects`
CHANGE COLUMN `name` `name` VARCHAR(50) NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `users`
CHANGE COLUMN `full_name` `full_name` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL AFTER `id`,
CHANGE COLUMN `first_name` `first_name` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL AFTER `full_name`,
CHANGE COLUMN `last_name` `last_name` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL AFTER `first_name`;

set foreign_key_checks=0;
ALTER TABLE `customer_transactions`
DROP FOREIGN KEY `customer_transactions_currency_id_foreign`,
DROP FOREIGN KEY `customer_transactions_invoice_id_foreign`,
DROP FOREIGN KEY `customer_transactions_payment_method_id_foreign`,
DROP FOREIGN KEY `customer_transactions_transaction_reference_id_foreign`,
DROP FOREIGN KEY `customer_transactions_user_id_foreign`;
ALTER TABLE `customer_transactions`
CHANGE COLUMN `invoice_id` `sale_order_id` INT(11) UNSIGNED NOT NULL ;
ALTER TABLE `customer_transactions`
ADD CONSTRAINT `customer_transactions_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `customer_transactions_sale_order_id_foreign`
  FOREIGN KEY (`sale_order_id`)
  REFERENCES `sale_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `customer_transactions_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `customer_transactions_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transactions` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `customer_transactions_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `ticket_replies`
DROP FOREIGN KEY `ticket_replies_customer_id_foreign`,
DROP FOREIGN KEY `ticket_replies_user_id_foreign`;
ALTER TABLE `ticket_replies`
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'user_id refers users table id in other word (admin id who replied the current one)' ,
CHANGE COLUMN `customer_id` `customer_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'customer_id refers customers table id' ;
ALTER TABLE `ticket_replies`
ADD CONSTRAINT `ticket_replies_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `ticket_replies_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_orders`
CHANGE COLUMN `inv_type` `invoice_type` VARCHAR(15) NOT NULL COMMENT '\'quantity\', \\n\'hours\', \\n\'amount\'' ,
CHANGE COLUMN `discount_on` `discount_on` VARCHAR(15) NOT NULL COMMENT '\'before\', \\n\'after\'' ,
CHANGE COLUMN `m_tax` `has_tax` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_detail_description` `has_description` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_item_discount` `has_item_discount` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_shn` `has_shn` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_sub_discount` `has_sub_discount` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_sub_shipping` `has_shipping_charge` TINYINT(1) NOT NULL ,
CHANGE COLUMN `m_sub_custom_amount` `has_custom_charge` TINYINT(1) NOT NULL ,
CHANGE COLUMN `s_other_discount` `other_discount_amount` DOUBLE NULL DEFAULT NULL ,
CHANGE COLUMN `s_other_discount_type` `other_discount_type` VARCHAR(1) NOT NULL COMMENT '% or $' ,
CHANGE COLUMN `s_shipping` `shipping_charge` DOUBLE NULL DEFAULT NULL ,
CHANGE COLUMN `s_custom_amount_title` `custom_charge_title` VARCHAR(199) NULL DEFAULT NULL ,
CHANGE COLUMN `s_custom_amount` `custom_charge_amount` DOUBLE NOT NULL ,
CHANGE COLUMN `currency_id` `currency_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(10) NOT NULL COMMENT 'percent or flat' ,
CHANGE COLUMN `purchase_receive_type` `purchase_receive_type` VARCHAR(10) NOT NULL ,
CHANGE COLUMN `comment_check` `has_comment` TINYINT(1) NOT NULL DEFAULT 0 ,
CHANGE COLUMN `ord_date` `order_date` DATE NOT NULL ,
CHANGE COLUMN `into_stock_location` `location_id` INT(11) UNSIGNED NOT NULL ,
CHANGE COLUMN `payment_id` `payment_method` VARCHAR(50) NULL DEFAULT NULL ,
CHANGE COLUMN `paid_amount` `paid` DOUBLE NOT NULL DEFAULT 0 ;

ALTER TABLE `purchase_orders`
DROP FOREIGN KEY `purch_orders_user_id_foreign`,
DROP FOREIGN KEY `purch_orders_supplier_id_foreign`;
ALTER TABLE `purchase_orders`
DROP INDEX `purch_orders_user_id_foreign` ,
DROP INDEX `purch_orders_supplier_id_foreign` ;


ALTER TABLE `purchase_orders`
CHANGE COLUMN `payment_term` `payment_term_id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `gobilling_latest`.`purchase_orders`
ADD INDEX `purchase_orders_supplier_id_foreign_idx` (`supplier_id` ASC) ,
ADD INDEX `purchase_orders_user_id_foreign_idx` (`user_id` ASC) ;

ALTER TABLE `gobilling_latest`.`purchase_orders`
ADD CONSTRAINT `purchase_orders_supplier_id_foreign`
  FOREIGN KEY (`supplier_id`)
  REFERENCES `gobilling_latest`.`suppliers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `purchase_orders_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `gobilling_latest`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_orders`
ADD INDEX `purchase_orders_currency_id_foreign_idx` (`currency_id` ASC) ,
ADD INDEX `purchase_orders_location_id_foreign_idx` (`location_id` ASC) ;

ALTER TABLE `purchase_orders`
ADD CONSTRAINT `purchase_orders_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `gobilling_latest`.`currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `purchase_orders_location_id_foreign`
  FOREIGN KEY (`location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_orders`
ADD INDEX `purchase_orders_payment_term_id_foreign_idx` (`payment_term_id` ASC) VISIBLE;

ALTER TABLE `purchase_orders`
ADD CONSTRAINT `purchase_orders_payment_term_id_foreign`
  FOREIGN KEY (`payment_term_id`)
  REFERENCES `payment_terms` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `tax_types` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `sale_orders`
DROP FOREIGN KEY `sale_orders_payment_method_id_foreign`;
ALTER TABLE `sale_orders`
DROP INDEX `sale_orders_payment_method_id_foreign_idx` ;

ALTER TABLE `sale_orders`
CHANGE COLUMN `payment_method_id` `payment_method_id` VARCHAR(15) NULL DEFAULT NULL ;

ALTER TABLE `sale_orders`
CHANGE COLUMN `payment_term_id` `payment_term_id` INT(11) UNSIGNED NOT NULL ;

ALTER TABLE `sale_orders`
ADD INDEX `sale_orders_payment_term_id_foreign_idx` (`payment_term_id` ASC) ;

ALTER TABLE `sale_orders`
ADD CONSTRAINT `sale_orders_payment_term_id_foreign`
  FOREIGN KEY (`payment_term_id`)
  REFERENCES `payment_terms` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `customers`
CHANGE COLUMN `is_inactive` `is_active` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 for active;\\n0 for inactive.' ;

ALTER TABLE `general_ledger_transactions`
CHANGE COLUMN `is_reversed` `is_reversed` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 for yes, 0 for no' ;

UPDATE `general_ledger_transactions` SET `is_reversed`= 0 WHERE is_reversed = 'no'

UPDATE `general_ledger_transactions` SET `is_reversed`= 1 WHERE is_reversed = 'yes'


ALTER TABLE `item_units`
CHANGE COLUMN `is_inactive` `is_active` TINYINT(1) UNSIGNED NULL DEFAULT 1 COMMENT '1 for active; \\n0 for inactive' ;


ALTER TABLE `items`
CHANGE COLUMN `is_inactive` `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0 for inactive \\n1 for active' ;


ALTER TABLE `security_role`
RENAME TO  `security_roles` ;

ALTER TABLE `security_roles`
CHANGE COLUMN `inactive` `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1  for active, 0 for inactive' ;


ALTER TABLE `item_units`
DROP INDEX `item_units_is_inactive_index` ;

ALTER TABLE `item_units`
ADD INDEX `item_units_is_active_index` (`is_active` ASC) ;


set foreign_key_checks=0;
ALTER TABLE `items`
DROP INDEX `items_is_inactive_index` ;

set foreign_key_checks = 0;
ALTER TABLE `items`
ADD INDEX `items_is_active_index` (`is_active` ASC);


ALTER TABLE `purchase_orders`
CHANGE COLUMN `payment_method` `payment_method_id` VARCHAR(50) NULL DEFAULT NULL ;


ALTER TABLE `payment_gateways`
DROP INDEX `payment_gateways_name_unique` ;


ALTER TABLE `payment_gateways`
ADD INDEX `payment_gateways_name_index` (`name` ASC);

ALTER TABLE `stock_moves`
ADD CONSTRAINT `stock_moves_stock_transfer_id_foreign`
  FOREIGN KEY (`stock_transfer_id`)
  REFERENCES `stock_transfers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `stock_moves`
CHANGE COLUMN `qty` `quantity` DOUBLE NOT NULL DEFAULT 0 ;

ALTER TABLE `locations`
CHANGE COLUMN `inactive` `is_active` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '1 = \'Active\'\\n 0 = \'Inactive\'' ;

ALTER TABLE `payment_methods`
CHANGE COLUMN `defaults` `is_default` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 for not default;\n1 for default' ;

ALTER TABLE `purchase_order_details`
CHANGE COLUMN `is_inventory` `has_inventory` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for it has inventory;\n0 otherwise' ;

ALTER TABLE `stock_categories`
CHANGE COLUMN `is_inactive` `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 for active;\\n 0 for otherwise' ;

ALTER TABLE `suppliers`
CHANGE COLUMN `is_inactive` `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 for active; \\n 0 for inactive.' ;

ALTER TABLE `tax_types`
CHANGE COLUMN `is_defaults` `is_default` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for defaults;\\n0 for otherwise' ;

ALTER TABLE `users`
CHANGE COLUMN `remember_token` `remember_token` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL AFTER `picture`,
CHANGE COLUMN `is_inactive` `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 for active; \n0 otherwise' ;

ALTER TABLE `countries`
CHANGE COLUMN `country` `name` VARCHAR(50) NOT NULL ;

ALTER TABLE `countries`
DROP INDEX `countries_country_index` ;

ALTER TABLE `countries`
ADD INDEX `countries_name_index` (`name` ASC) ;

ALTER TABLE `locations`
ADD INDEX `locations_is_active_index` (`is_active` ASC)

ALTER TABLE `payment_methods`
DROP INDEX `payment_methods_defaults_index` ;

ALTER TABLE `payment_methods`
ADD INDEX `payment_methods_is_default_index` (`is_default` ASC) ;

ALTER TABLE `purchase_order_details`
DROP INDEX `purchase_order_details_is_inventory_index` ;

ALTER TABLE `purchase_order_details`
ADD INDEX `purchase_order_details_has_inventory_index` (`has_inventory` ASC) ;

ALTER TABLE `stock_categories`
DROP INDEX `stock_categories_is_inactive_index` ;

ALTER TABLE `stock_categories`
ADD INDEX `stock_categories_is_active_index` (`is_active` ASC);

ALTER TABLE `suppliers`
DROP INDEX `suppliers_is_inactive_index` ;

ALTER TABLE `suppliers`
ADD INDEX `suppliers_is_active_index` (`is_active` ASC) ;

ALTER TABLE `users`
DROP INDEX `users_is_inactive_index` ;

ALTER TABLE `users`
ADD INDEX `users_is_active_index` (`is_active` ASC) ;

ALTER TABLE `sale_taxes`
DROP COLUMN `sales_id`;

ALTER TABLE `ticket_statuses`
CHANGE COLUMN `statuscolor` `color` VARCHAR(8) NOT NULL ;

ALTER TABLE `tasks`
CHANGE COLUMN `retated_to_id` `related_to_id` INT(10) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `sale_orders`
CHANGE COLUMN `has_shn` `has_hsn` TINYINT(1) NOT NULL ;

ALTER TABLE `sale_order_details`
CHANGE COLUMN `shn` `hsn` VARCHAR(250) NOT NULL ;

ALTER TABLE `locations`
ADD `is_default` TINYINT(4) NOT NULL COMMENT '1 = \'Yes\'\\n 0 = \'No\'' AFTER `is_active`;

ALTER TABLE `locations`
CHANGE `is_default` `is_default` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1 = \'Yes\'\\n 0 = \'No\'';


ALTER TABLE `stock_moves`
DROP FOREIGN KEY `stock_moves_stock_transfer_id_foreign`;
ALTER TABLE `stock_moves`
DROP INDEX `stock_moves_stock_transfer_id_foreign_idx` ;

ALTER TABLE `stock_moves`
ADD INDEX `stock_moves_stock_transfer_id_foreign_idx` (`stock_transfer_id` ASC) ;

ALTER TABLE `stock_moves`
ADD CONSTRAINT `stock_moves_stock_transfer_id_foreign`
  FOREIGN KEY (`stock_transfer_id`)
  REFERENCES `stock_transfers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `stock_moves`
CHANGE COLUMN `order_reference` `sale_order_detail_id` INT(10) UNSIGNED NULL ;

ALTER TABLE `stock_moves`
ADD INDEX `stock_moves_sale_order_detail_id_foreign_idx` (`sale_order_detail_id` ASC) ;

ALTER TABLE `stock_moves`
ADD CONSTRAINT `stock_moves_sale_order_detail_id_foreign`
  FOREIGN KEY (`sale_order_detail_id`)
  REFERENCES `sale_order_details` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `currencies`
DROP COLUMN `is_deleted`,
DROP INDEX `currencies_is_deleted_index`;

ALTER TABLE `sale_types`
CHANGE COLUMN `sales_type` `sale_type` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;

ALTER TABLE `sale_types`
DROP INDEX `sales_types_sales_type_index` ,
DROP INDEX `sales_types_is_default_index` ;

ALTER TABLE `sale_types`
ADD INDEX `sale_types_sale_type_index` (`sale_type` ASC) ,
ADD INDEX `sale_types_is_default_index` (`is_default` ASC) ;

ALTER TABLE `tags_assigns`
RENAME TO  `tag_assigns` ;

ALTER TABLE `purchase_orders`
CHANGE COLUMN `has_shn` `has_hsn` TINYINT(1) NOT NULL ;

ALTER TABLE `purchase_order_details`
CHANGE COLUMN `shn` `hsn` VARCHAR(250) NULL DEFAULT NULL ;

ALTER TABLE `purchase_order_details`
ADD CONSTRAINT `purchase_order_details_tax_type_id_foreign`
  FOREIGN KEY (`tax_type_id`)
  REFERENCES `tax_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_orders`
CHANGE COLUMN `payment_method` `payment_method_id` VARCHAR(50) NULL DEFAULT NULL ;

ALTER TABLE `purchase_orders`
ADD INDEX `purchase_orders_payment_method_id_index` (`payment_method_id` ASC) ;

ALTER TABLE `purchase_orders`
CHANGE COLUMN `purchase_receive_type` `purchase_type_id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `purchase_orders`
ADD INDEX `purchase_orders_purchase_type_id_foreign_idx` (`purchase_type_id` ASC) ;

ALTER TABLE `purchase_orders`
ADD CONSTRAINT `purchase_orders_purchase_type_id_foreign`
  FOREIGN KEY (`purchase_type_id`)
  REFERENCES `purchase_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


set foreign_key_checks=0;

ALTER TABLE `purchase_types`
RENAME TO  `purchase_receive_types` ;

ALTER TABLE `purchase_receive_types`
DROP INDEX `purchase_types_is_default_index` ,
DROP INDEX `purchase_types_purchase_type_index` ;

ALTER TABLE `purchase_receive_types`
CHANGE COLUMN `purchase_type` `receive_type` VARCHAR(50) NOT NULL ;

ALTER TABLE `purchase_receive_types`
ADD INDEX `purchase_receive_types_receive_type_index` (`receive_type` ASC) ;

ALTER TABLE `purchase_receive_types`
ADD INDEX `purchase_receive_types_is_default_index` (`is_default` ASC) ;

ALTER TABLE `purchase_orders`
DROP FOREIGN KEY `purchase_orders_purchase_type_id_foreign`;
ALTER TABLE `purchase_orders`
DROP INDEX `purchase_orders_purchase_type_id_foreign_idx` ;


ALTER TABLE `purchase_orders`
CHANGE COLUMN `purchase_type_id` `purchase_receive_type_id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `purchase_orders`
ADD INDEX `purchase_orders_purchase_receive_type_id_foreign_idx` (`purchase_receive_type_id` ASC) ;

ALTER TABLE `purchase_orders`
ADD CONSTRAINT `purchase_orders_purchase_receive_type_id_foreign`
  FOREIGN KEY (`purchase_receive_type_id`)
  REFERENCES `purchase_receive_types` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `purchase_orders`
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(10) NOT NULL COMMENT '% or $' ;

ALTER TABLE `purchase_order_details`
DROP FOREIGN KEY `purchase_order_details_tax_type_id_foreign`;
ALTER TABLE `purchase_order_details`
DROP COLUMN `has_inventory`,
DROP COLUMN `tax_type_id`,
DROP COLUMN `quantity_invoiced`,
CHANGE COLUMN `discount` `discount` DOUBLE UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(1) NOT NULL COMMENT '% or $' ,
CHANGE COLUMN `sorting_no` `sorting_no` TINYINT(2) NOT NULL ,
CHANGE COLUMN `discount_percent` `discount_amount` DOUBLE UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `quantity_ordered` `quantity_ordered` DOUBLE UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `quantity_received` `quantity_received` DOUBLE UNSIGNED NOT NULL DEFAULT 0 ,
DROP INDEX `purchase_order_details_tax_type_id_foreign_idx` ,
DROP INDEX `purchase_order_details_has_inventory_index` ,
DROP INDEX `purchase_order_details_is_qty_invoiced_index` ;

ALTER TABLE `purchase_taxes`
DROP FOREIGN KEY `purchase_taxes_purchase_order_id_foreign`;
ALTER TABLE `purchase_taxes`
DROP COLUMN `purchase_order_id`,
DROP INDEX `purchase_taxes_purchase_order_id_foreign_idx` ;

ALTER TABLE `purchase_taxes`
CHANGE COLUMN `tax_amount` `tax_amount` DOUBLE UNSIGNED NOT NULL ;

ALTER TABLE `sale_orders`
CHANGE COLUMN `invoice_type` `order_type` VARCHAR(15) NOT NULL ;

ALTER TABLE `sale_orders`
ADD `invoice_type` VARCHAR(10) NOT NULL
COMMENT 'quantity, hourse or amount' AFTER `transaction_type`, ADD INDEX `sale_orders_invoice_type_index` (`invoice_type`);

ALTER TABLE `sale_orders`
CHANGE COLUMN `discount_type` `discount_type` VARCHAR(10) NOT NULL COMMENT '% or $' ;

ALTER TABLE `sale_order_details`
DROP FOREIGN KEY `sale_order_details_tax_type_id_foreign`;
ALTER TABLE `sale_order_details`
DROP COLUMN `tax_type_id`,
CHANGE COLUMN `discount_percent` `discount_amount` DOUBLE UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `discount` `discount` DOUBLE UNSIGNED NOT NULL DEFAULT 0 ,
CHANGE COLUMN `sorting_no` `sorting_no` TINYINT(4) UNSIGNED NOT NULL ,
DROP INDEX `sale_order_details_tax_type_id_foreign_idx` ;

ALTER TABLE `purchase_prices`
ADD COLUMN `currency_id` INT(10) UNSIGNED NOT NULL AFTER `item_id`;


ALTER TABLE `purchase_prices`
ADD INDEX `purchase_prices_currency_id_foreign_idx` (`currency_id` ASC) ;

ALTER TABLE `purchase_prices`
ADD CONSTRAINT `purchase_prices_currency_id_foreign`
  FOREIGN KEY (`currency_id`)
  REFERENCES `currencies` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `purchase_taxes`
DROP FOREIGN KEY `purchase_taxes_purchase_order_details_id_foreign`;
ALTER TABLE `purchase_taxes`
DROP INDEX `purchase_taxes_purchase_order_details_id_foreign_idx` ;

ALTER TABLE `purchase_taxes`
CHANGE COLUMN `purchase_order_details_id` `purchase_order_detail_id` INT(11) UNSIGNED NOT NULL ;

ALTER TABLE `purchase_taxes`
ADD INDEX `purchase_taxes_purchase_order_detail_id_foreign_idx` (`purchase_order_detail_id` ASC) ;
ALTER TABLE `purchase_taxes`
ADD CONSTRAINT `purchase_taxes_purchase_order_detail_id_foreign`
  FOREIGN KEY (`purchase_order_detail_id`)
  REFERENCES `purchase_order_details` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `transactions`
DROP INDEX `transactions_transaction_type_index` ,
DROP INDEX `transactions_transaction_type_name_index` ;

ALTER TABLE `transactions`
CHANGE COLUMN `transaction_type_name` `transaction_type` VARCHAR(100) NOT NULL ,
CHANGE COLUMN `transaction_type` `transaction_method` VARCHAR(50) NOT NULL ;

ALTER TABLE `transactions`
ADD INDEX `transactions_transaction_type_index` (`transaction_type` ASC) ,
ADD INDEX `transactions_transaction_method_index` (`transaction_method` ASC) ;

ALTER TABLE `transaction_references`
CHANGE COLUMN `reference_type` `reference_type` VARCHAR(50) NOT NULL ;
UPDATE `transaction_references` SET `reference_type` = 'EXPENSE' WHERE reference_type = 12;
UPDATE `transaction_references` SET `reference_type` = 'PURCHASE_PAYMENT' WHERE reference_type = 16;
UPDATE `transaction_references` SET `reference_type` = 'INVOICE_PAYMENT' WHERE reference_type = 15;
UPDATE `transaction_references` SET `reference_type` = 'TRANSFER' WHERE reference_type = 13;
UPDATE `transaction_references` SET `reference_type` = 'DEPOSIT' WHERE reference_type = 11;

ALTER TABLE `transaction_references`
ADD INDEX `transaction_references_reference_type_index` (`reference_type` ASC) ;
UPDATE `transactions` SET `transaction_method` = 'OPENING_BALANCE' WHERE transaction_method = 10;
UPDATE `transactions` SET `transaction_method` = 'DEPOSIT' WHERE transaction_method = 11;
UPDATE `transactions` SET `transaction_method` = 'TRANSFER' WHERE transaction_method = 13;
UPDATE `transactions` SET `transaction_method` = 'INVOICE_PAYMENT' WHERE transaction_method = 15;
UPDATE `transactions` SET `transaction_method` = 'PURCHASE_PAYMENT' WHERE transaction_method = 16;
UPDATE `transactions` SET `transaction_method` = 'POS_PAYMENT' WHERE transaction_method = 17;


ALTER TABLE `tasks`
CHANGE COLUMN `is_public` `is_public` TINYINT(1) UNSIGNED NULL DEFAULT '0' ,
CHANGE COLUMN `billable` `is_billable` TINYINT(1) UNSIGNED NULL DEFAULT '0' ,
CHANGE COLUMN `billed` `is_billed` TINYINT(1) UNSIGNED NOT NULL ,
CHANGE COLUMN `visible_to_customer` `is_visible_to_customer` TINYINT(1) UNSIGNED NULL DEFAULT '0' ,
ADD INDEX `tasks_is_billable_index` (`is_billable` ASC),
ADD INDEX `tasks_is_billed_foreign` (`is_billed` ASC);



set foreign_key_checks = 0;
ALTER TABLE `transfers`
DROP FOREIGN KEY `transfers_reference_id_foreign`;
ALTER TABLE `transfers`
CHANGE COLUMN `reference_id` `transaction_reference_id` INT(10) UNSIGNED NOT NULL ,
DROP INDEX `transfers_transaction_reference_id_foreign_idx` ;


ALTER TABLE `transfers`
DROP COLUMN `transfer_type`,
DROP INDEX `transfers_transfer_type_index` ;


ALTER TABLE `transfers`
ADD CONSTRAINT `transfers_transaction_reference_id`
  FOREIGN KEY (`id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `transfers`
ADD INDEX `transfers_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC) ;


ALTER TABLE `received_order_details`
DROP FOREIGN KEY `receive_order_details_receive_id_foreign`,
DROP FOREIGN KEY `receive_order_details_purch_order_id_foreign`;
ALTER TABLE `received_order_details`
DROP INDEX `receive_order_details_receive_id_foreign` ,
DROP INDEX `receive_order_details_purch_order_id_foreign` ;

ALTER TABLE `received_order_details`
CHANGE COLUMN `receive_id` `received_order_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `received_order_details_received_order_id_foreign_idx` (`received_order_id` ASC) ;

ALTER TABLE `received_order_details`
ADD CONSTRAINT `received_order_details_received_order_id_foreign`
  FOREIGN KEY (`received_order_id`)
  REFERENCES `received_orders` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `received_order_details`
CHANGE COLUMN `purchase_order_details_id` `purchase_order_detail_id` INT(10) UNSIGNED NOT NULL ;

set foreign_key_checks=0;
ALTER TABLE `received_order_details`
ADD CONSTRAINT `received_order_details_purchase_order_detail_id_foreign`
  FOREIGN KEY (`purchase_order_detail_id`)
  REFERENCES `purchase_order_details` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

set foreign_key_checks=0;
ALTER TABLE `received_order_details`
ADD INDEX `received_order_details_purchase_order_id_foreign_idx` (`purchase_order_id` ASC) ;

ALTER TABLE `received_order_details`
ADD CONSTRAINT `received_order_details_purchase_order_id_foreign`
  FOREIGN KEY (`purchase_order_id`)
  REFERENCES `received_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `transfers`
CHANGE COLUMN `from_exchange_rate` `from_exchange_rate` DOUBLE NULL ,
CHANGE COLUMN `to_exchange_rate` `to_exchange_rate` DOUBLE NULL ;

ALTER TABLE `transfers`
DROP FOREIGN KEY `transfers_payment_method_id_foreign`;
ALTER TABLE `transfers`
DROP COLUMN `payment_method_id`,
DROP INDEX `transfers_payment_method_id_foreign_idx` ;

ALTER TABLE `purchase_orders`
DROP COLUMN `discount_type`,
CHANGE COLUMN `has_sub_discount` `has_other_discount` TINYINT(1) NOT NULL ;

ALTER TABLE `sale_orders`
CHANGE COLUMN `has_sub_discount` `has_other_discount` TINYINT(1) NOT NULL ;


ALTER TABLE `projects`
CHANGE COLUMN `project_begin_date` `begin_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `project_due_date` `due_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `project_completed_date` `completed_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `project_cost` `cost` DECIMAL(8,2) NULL DEFAULT NULL ;


ALTER TABLE `projects`
DROP INDEX `projects_project_due_date_index` ,
DROP INDEX `projects_project_begin_date_index` ;

ALTER TABLE `projects`
ADD INDEX `projects_begin_date_index` (`begin_date` ASC) ,
ADD INDEX `projects_due_date_index` (`due_date` ASC) ;

ALTER TABLE `email_config`
COLLATE = utf8_general_ci , RENAME TO  `email_configurations`;

ALTER TABLE `email_configurations`
CHANGE COLUMN `email_protocol` `protocol` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL ,
CHANGE COLUMN `email_encryption` `encryption` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL ,
CHANGE COLUMN `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1= varified, 0= unverified' ;

ALTER TABLE `supplier_transactions`
CHANGE `exchange_rate` `exchange_rate` DOUBLE NULL DEFAULT NULL;

ALTER TABLE `supplier_transactions`
CHANGE `purchase_exchange_rate` `purchase_exchange_rate` DECIMAL(10,0) NULL DEFAULT NULL;

ALTER TABLE `supplier_transactions`
DROP FOREIGN KEY `supplier_transactions_payment_method_id_foreign`;
ALTER TABLE `supplier_transactions`
CHANGE COLUMN `payment_method_id` `payment_term_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `supplier_transactions_payment_term_id_foreign_idx` (`payment_term_id` ASC),
DROP INDEX `supplier_transactions_payment_method_id_foreign_idx` ;

ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_payment_term_id_foreign`
  FOREIGN KEY (`payment_term_id`)
  REFERENCES `payment_terms` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `supplier_transactions`
DROP FOREIGN KEY `supplier_transactions_payment_term_id_foreign`;
ALTER TABLE `supplier_transactions`
CHANGE COLUMN `payment_term_id` `payment_method_id` INT(10) UNSIGNED NOT NULL ,
ADD INDEX `supplier_transactions_payment_method_id_foreign_idx` (`payment_method_id` ASC),
DROP INDEX `supplier_transactions_payment_term_id_foreign_idx` ;

ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `stock_moves`
DROP FOREIGN KEY `stock_moves_receive_order_id_foreign`;

ALTER TABLE `stock_moves`
DROP INDEX `stock_moves_receive_order_id_foreign_idx` ;

ALTER TABLE `stock_moves`
CHANGE COLUMN `receive_order_id` `received_order_id` INT(10) UNSIGNED NULL DEFAULT NULL
COMMENT 'receive_order_id refers receive_orders table id' ;

set foreign_key_checks=0;
ALTER TABLE `stock_moves`
ADD INDEX `stock_moves_received_order_id_foreign_idx` (`received_order_id` ASC) ;

ALTER TABLE `stock_moves` ADD CONSTRAINT `stock_moves_received_order_id_foreign`
FOREIGN KEY (`received_order_id`) REFERENCES `received_orders` (`id`)
ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `stock_moves`
CHANGE COLUMN `transaction_type` `transaction_type` VARCHAR(25) NOT NULL ;

ALTER TABLE `items`
DROP COLUMN `item_image`;

ALTER TABLE `received_order_details`
DROP FOREIGN KEY `received_order_details_purchase_order_id_foreign`;
ALTER TABLE `received_order_details`
ADD INDEX `received_order_details_purchase_order_id_foreign_idx` (`purchase_order_id` ASC) ,
DROP INDEX `received_order_details_purchase_order_id_foreign_idx` ;

ALTER TABLE `received_order_details`
ADD CONSTRAINT `received_order_details_purchase_order_id_foreign`
  FOREIGN KEY (`purchase_order_id`)
  REFERENCES `purchase_orders` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `transactions` CHANGE `attachment` `attachment` VARCHAR(50) NULL DEFAULT NULL

ALTER TABLE `stock_moves`
DROP FOREIGN KEY `stock_moves_transaction_reference_id_foreign`;

ALTER TABLE `stock_moves`
DROP INDEX `stock_moves_transaction_reference_id_foreign_idx` ;

ALTER TABLE `stock_adjustments`
CHANGE `adjustment_transaction_type` `adjustment_transaction_type` VARCHAR(20) NOT NULL;

ALTER TABLE `stock_adjustments`
CHANGE `total` `total_quantity` DOUBLE NOT NULL;

ALTER TABLE `stock_adjustments`
CHANGE `adjustment_transaction_type` `transaction_type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `stock_adjustments` CHANGE `date` `transaction_date` DATE NULL DEFAULT NULL;

ALTER TABLE `stock_transfers` CHANGE `quantity` `quantity` DOUBLE NOT NULL;

ALTER TABLE `stock_adjustment_details` CHANGE `quantity` `quantity` DOUBLE NOT NULL;

ALTER TABLE `expenses`
DROP COLUMN `exchange_rate`;

ALTER TABLE `expenses`
DROP FOREIGN KEY `expenses_transaction_id_foreign`;
ALTER TABLE `expenses`
CHANGE COLUMN `transaction_id` `transaction_id` INT(10) UNSIGNED NULL COMMENT 'transaction_id refers transactions tables\' id;' ;
ALTER TABLE `expenses`
ADD CONSTRAINT `expenses_transaction_id_foreign`
  FOREIGN KEY (`transaction_id`)
  REFERENCES `transactions` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `sale_order_details`
CHANGE COLUMN `hsn` `hsn` VARCHAR(250) NULL ;

ALTER TABLE `sale_order_details`
DROP FOREIGN KEY `sale_order_details_item_id_foreign`;
ALTER TABLE `sale_order_details`
CHANGE COLUMN `item_id` `item_id` INT(10) UNSIGNED NULL ;
ALTER TABLE `sale_order_details`
ADD CONSTRAINT `sale_order_details_item_id_foreign`
  FOREIGN KEY (`item_id`)
  REFERENCES `items` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `expenses`
DROP FOREIGN KEY `expenses_payment_method_id_foreign`;
ALTER TABLE `expenses`
CHANGE COLUMN `payment_method_id` `payment_method_id` INT(10) UNSIGNED NULL COMMENT 'payment_method_id refers payment_methods' ;
ALTER TABLE `expenses`
ADD CONSTRAINT `expenses_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `expenses`
DROP FOREIGN KEY `expenses_payment_method_id_foreign`;
ALTER TABLE `expenses`
ADD CONSTRAINT `expenses_payment_method_id_foreign`
  FOREIGN KEY (`payment_method_id`)
  REFERENCES `payment_methods` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;


ALTER TABLE `customers`
CHANGE `is_active` `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 for active;\\n0 for inactive.';

ALTER TABLE `items`
CHANGE `is_active` `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '0 for inactive \\n1 for active';

ALTER TABLE `locations`
CHANGE `is_active` `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = \'Active\'\\n 0 = \'Inactive\'',
CHANGE `is_default` `is_default` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = \'Yes\'\\n 0 = \'No\'';

ALTER TABLE `payment_terms`
CHANGE `is_default` `is_default` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 for default;0 for otherwise;';

ALTER TABLE `purchase_orders`
COLLATE = utf8_general_ci ,
ADD INDEX `purchase_orders_supplier_id_foreign_idx` (`supplier_id` ASC) ,
ADD INDEX `purchase_orders_user_id_foreign_idx` (`user_id` ASC) ;
;
ALTER TABLE `purchase_orders`
ADD CONSTRAINT `purchase_orders_supplier_id_foreign`
  FOREIGN KEY (`supplier_id`)
  REFERENCES `suppliers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `purchase_orders_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `stock_moves`
DROP FOREIGN KEY `stock_moves_transaction_reference_id_foreign`,
DROP FOREIGN KEY `stock_moves_stock_transfer_id_foreign`,
DROP FOREIGN KEY `stock_moves_sale_order_id_foreign`,
DROP FOREIGN KEY `stock_moves_sale_order_detail_id_foreign`,
DROP FOREIGN KEY `stock_moves_received_order_id_foreign`;

ALTER TABLE `stock_moves`
DROP INDEX `stock_moves_received_order_id_foreign_idx` ,
DROP INDEX `stock_moves_sale_order_detail_id_foreign_idx` ,
DROP INDEX `stock_moves_stock_transfer_id_foreign_idx` ,
DROP INDEX `stock_moves_transaction_reference_id_foreign_idx` ,
DROP INDEX `stock_moves_sale_order_id_foreign_idx` ;

ALTER TABLE `stock_moves`
DROP COLUMN `received_order_id`,
DROP COLUMN `stock_transfer_id`,
DROP COLUMN `transaction_reference_id`;
ALTER TABLE `stock_moves` ALTER INDEX `stock_moves_price_index`;

ALTER TABLE `stock_moves`
CHANGE COLUMN `sale_order_id` `transaction_type_id` INT(10) UNSIGNED NULL DEFAULT NULL,
CHANGE COLUMN `sale_order_detail_id` `transaction_type_detail_id` INT(10) UNSIGNED NULL DEFAULT NULL ;

set foreign_key_checks=0;
ALTER TABLE `stock_moves`
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL COMMENT 'user_id refers users table id' ;

set foreign_key_checks=0;
ALTER TABLE `stock_moves`
ADD CONSTRAINT `stock_moves_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


ALTER TABLE `stock_moves`
CHANGE COLUMN `transaction_type_id` `transaction_type_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Id of sale_orders, purchase_orders, stock_adjustments, stock_transfers, items (initial stock)' ,
CHANGE COLUMN `transaction_type_detail_id` `transaction_type_detail_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Id of sale_order_details, purchase_order_details, stock_adjustment_details, stock_transfers, items (initial stock)' ,
ADD INDEX `stock_moves_transaction_type_id_index` (`transaction_type_id` ASC) ,
ADD INDEX `stock_moves_transaction_type_detail_id_index` (`transaction_type_detail_id` ASC) ;


ALTER TABLE `supplier_transactions`
DROP COLUMN `exchange_rate`,
DROP COLUMN `purchase_exchange_rate`;

ALTER TABLE `items` CHANGE ` is_stock_managed` `is_stock_managed` TINYINT(1) NOT NULL
COMMENT 'it means the product/service stock management 1 for on0 for off';

ALTER TABLE `task_comments` DROP `file_id`;

set foreign_key_checks=0;
ALTER TABLE `customer_transactions`
DROP FOREIGN KEY `customer_transactions_transaction_reference_id_foreign`;
ALTER TABLE `customer_transactions`
ADD INDEX `customer_transactions_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC) ,
DROP INDEX `customer_transaction_reference_id_idx` ;

ALTER TABLE `customer_transactions`
ADD CONSTRAINT `customer_transactions_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `activities` CHANGE `project_id` `project_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `activities` CHANGE `full_name` `full_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `tasks` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `task_comments` CHANGE `customer_id` `customer_id` INT(10) UNSIGNED NULL DEFAULT NULL
ALTER TABLE `task_comments` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `users` DROP `picture`

ALTER TABLE `customer_transactions`
DROP COLUMN `incoming_amount`,
DROP COLUMN `invoice_exchange_rate`,
DROP INDEX `customer_transactions_incoming_amount_index` ;

ALTER TABLE `supplier_transactions`
DROP INDEX `supplier_transactions_incoming_amount_index` ;

ALTER TABLE `supplier_transactions`
CHANGE COLUMN `incoming_amount` `exchange_rate` DOUBLE UNSIGNED NOT NULL ,
ADD INDEX `supplier_transactions_exchange_rate_index` (`exchange_rate` ASC) ;

ALTER TABLE `transactions`
DROP FOREIGN KEY `transactions_user_id_foreign `;
ALTER TABLE `transactions`
DROP INDEX `transactions_user_id_foreign _idx` ;

set foreign_key_checks=0;
ALTER TABLE `transactions`
DROP FOREIGN KEY `transactions_account_id_foreign`;
ALTER TABLE `transactions`
CHANGE COLUMN `account_id` `account_id` INT(10) UNSIGNED NULL COMMENT 'account_id refers accounts table id' ;
ALTER TABLE `transactions`
ADD CONSTRAINT `transactions_account_id_foreign`
  FOREIGN KEY (`account_id`)
  REFERENCES `accounts` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `transactions`
CHANGE `payment_method_id` `payment_method_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'payment_method_id references payment_methods table id';

ALTER TABLE `bank_trans` DROP `attachment`;

set foreign_key_checks=0;
ALTER TABLE `supplier_transactions`
ADD INDEX `supplier_transactions_transaction_reference_id_foreign_idx` (`transaction_reference_id` ASC) ;

ALTER TABLE `supplier_transactions`
ADD CONSTRAINT `supplier_transactions_transaction_reference_id_foreign`
  FOREIGN KEY (`transaction_reference_id`)
  REFERENCES `transaction_references` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `notes`
DROP FOREIGN KEY `notes_project_id_foreign`;
ALTER TABLE `notes`
DROP COLUMN `project_id`,
DROP INDEX `notes_project_id_foreign` ;

ALTER TABLE `checklist_items`
DROP FOREIGN KEY `checklist_items_user_id_foreign`,
DROP FOREIGN KEY `checklist_items_project_id_foreign`;
ALTER TABLE `checklist_items`
DROP COLUMN `user_id`,
DROP COLUMN `project_id`,
DROP INDEX `checklist_items_user_id_foreign_idx` ,
DROP INDEX `checklist_items_project_id_foreign_idx` ;

ALTER TABLE `checklist_items`
CHANGE COLUMN `status` `is_checked` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1, 0 and 0 as default;\\n0 for unchecked;\\n1 for checked;' ,
ADD INDEX `checklist_items_is_checked_index` (`is_checked` ASC) ;

ALTER TABLE `checklist_items` DROP INDEX `checklist_items_status_index`;

ALTER TABLE `files`
ADD COLUMN `uploaded_by` INT(10) NULL DEFAULT NULL AFTER `object_id`;

ALTER TABLE `sale_orders`
DROP FOREIGN KEY `sale_orders_customer_branch_id_foreign`,
DROP FOREIGN KEY `sale_orders_customer_id_foreign`;
ALTER TABLE `sale_orders`
CHANGE COLUMN `customer_id` `customer_id` INT(10) UNSIGNED NULL ,
CHANGE COLUMN `customer_branch_id` `customer_branch_id` INT(10) UNSIGNED NULL ;
ALTER TABLE `sale_orders`
ADD CONSTRAINT `sale_orders_customer_branch_id_foreign`
  FOREIGN KEY (`customer_branch_id`)
  REFERENCES `customer_branches` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE,
ADD CONSTRAINT `sale_orders_customer_id_foreign`
  FOREIGN KEY (`customer_id`)
  REFERENCES `customers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `items`
CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `transactions` CHANGE `attachment` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `payment_methods`
ADD COLUMN `method_type` VARCHAR(10) NOT NULL AFTER `name`;

ALTER TABLE `sale_orders`
DROP FOREIGN KEY `sale_orders_payment_term_id_foreign`;
ALTER TABLE `sale_orders`
CHANGE COLUMN `payment_term_id` `payment_term_id` INT(11) UNSIGNED NULL ;
ALTER TABLE `sale_orders`
ADD CONSTRAINT `sale_orders_payment_term_id_foreign`
  FOREIGN KEY (`payment_term_id`)
  REFERENCES `payment_terms` (`id`)
  ON DELETE NO ACTION
  ON UPDATE CASCADE;

ALTER TABLE `tickets` DROP COLUMN `message`;

ALTER TABLE `transactions`
ADD COLUMN `currency_id` INT(10) UNSIGNED NOT NULL AFTER `id`,
ADD INDEX `transactions_currency_id_foreign_idx` (`currency_id` ASC);
;
ALTER TABLE `transactions`
ADD CONSTRAINT `transactions_currency_id_foreign`
FOREIGN KEY (`currency_id`)
REFERENCES `currencies` (`id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE `transaction_references`
ADD COLUMN `object_id` INT(10) UNSIGNED NULL AFTER `id`;

CREATE TABLE `url_shortner_config` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
`status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
`key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`secretkey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`default` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

ALTER TABLE `deposits` CHANGE `exchange_rate` `exchange_rate` DOUBLE NULL;

ALTER TABLE `transfers`
CHANGE COLUMN `to_exchange_rate` `exchange_rate` DOUBLE NOT NULL ;

ALTER TABLE `transfers`
DROP FOREIGN KEY `transfers_home_currency_id_foreign`;
ALTER TABLE `transfers`
DROP COLUMN `home_currency_id`,
DROP INDEX `transfers_home_currency_id_foreign_idx` ;

ALTER TABLE `transfers` DROP COLUMN `from_exchange_rate`

ALTER TABLE `transfers` CHANGE `exchange_rate` `exchange_rate` DOUBLE NULL;

ALTER TABLE `sale_orders` ADD `amount_received` DOUBLE NULL AFTER `paid`;

set foreign_key_checks=0;
ALTER TABLE `received_orders`
DROP FOREIGN KEY `received_orders_location_id_foreign`;
ALTER TABLE `received_orders`
ADD INDEX `received_orders_location_id_foreign_idx` (`location_id` ASC),
DROP INDEX `received_orders_location_id_foreign_idx` ;
;
ALTER TABLE `received_orders`
ADD CONSTRAINT `received_orders_location_id_foreign`
  FOREIGN KEY (`location_id`)
  REFERENCES `locations` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `tax_types` CHANGE `tax_rate` `tax_rate` DECIMAL(10,8) NOT NULL;

ALTER TABLE `item_custom_variants`
CHANGE COLUMN `variant_value` `variant_value` VARCHAR(240) CHARACTER SET 'utf8' NULL DEFAULT NULL;

ALTER TABLE `calendar_events` CHANGE `is_public` `is_public` TINYINT(4) NULL DEFAULT NULL;

ALTER TABLE `customers`
DROP COLUMN `address`;

ALTER TABLE `deposits`
DROP COLUMN `exchange_rate`;

ALTER TABLE `tickets`
DROP COLUMN `user_read`,
DROP COLUMN `customer_read`;

ALTER TABLE `transactions` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL COMMENT 'user_id refers users table id';

ALTER TABLE `customer_transactions` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL;

ALTER TABLE `payment_methods`
ADD COLUMN `client_id` VARCHAR(255) NOT NULL AFTER `name`,
ADD COLUMN `secret_key` VARCHAR(255) NOT NULL AFTER `is_default`,
ADD COLUMN `is_active` TINYINT(1) NOT NULL COMMENT '0 for inactive;\\n1 for active' AFTER `secret_key`;

ALTER TABLE `payment_methods`
ADD COLUMN `mode` VARCHAR(45) NOT NULL AFTER `name`,
CHANGE COLUMN `secret_key` `secret_key` VARCHAR(255) NOT NULL AFTER `client_id`;

ALTER TABLE `payment_methods` CHANGE `mode` `mode` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `payment_methods` CHANGE `client_id` `client_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `payment_methods` CHANGE `secret_key` `consumer_key` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `payment_methods` ADD `consumer_secret` VARCHAR(255) NULL DEFAULT NULL AFTER `consumer_key`;


INSERT INTO `email_templates` (`template_id`, `subject`, `body`, `language_short_name`, `template_type`, `language_id`) VALUES
(19, 'Your Invoice # {invoice_reference_no} from {company_name} has been created.', '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no} is for Quotation #{order_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email.</p><p><strong>Billing address</strong></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br>&nbsp;</p><p><br>&nbsp;</p><p><strong>Quotation summary</strong><br>&nbsp;</p><p>{invoice_summery}<br>&nbsp;</p><p>Regards,</p><p>{company_name}<br>&nbsp;</p>', 'en', 'email', 1),
(19, 'Subject', '<p>Body</p>', 'ar', 'email', 2),
(19, 'Subject', '<p>Body</p>', 'pt', 'email', 4),
(19, 'Subject', '<p>Body</p>', 'fr', 'email', 12),
(19, 'Subject', '<p>Body</p>', 'tr', 'email', 14),
(19, 'Subject', '<p>Body</p>', 'rs', 'email', 15),
(19, 'Subject', '<p>Body</p>', 'hn', 'email', 17),
(19, 'Subject', '<p>Body</p>', 'ur', 'email', 18),
(19, 'Subject', '<p>Body</p>', 'bn', 'email', 19),
(19, 'Subject', '<p>Body</p>', 'aa', 'email', 20),
(19, 'Subject', '<p>Body</p>', 'ab', 'email', 22),
(19, 'Subject', '<p>Body</p>', 'af', 'email', 23),
(19, 'Subject', '<p>Body</p>', 'az', 'email', 25),
(19, 'Subject', '<p>Body</p>', 'br', 'email', 28),
(19, 'Subject', '<p>Body</p>', 'co', 'email', 29),
(19, 'Subject', '<p>Body</p>', 'cy', 'email', 30),
(19, 'Subject', '<p>Body</p>', 'de', 'email', 31),
(19, 'Subject', '<p>Body</p>', 'dz', 'email', 32),
(19, 'Subject', '<p>Body</p>', 'es', 'email', 34),
(19, 'Subject', '<p>Body</p>', 'et', 'email', 35),
(19, 'Subject', '<p>Body</p>', 'eu', 'email', 36),
(19, 'Subject', '<p>Body</p>', 'fa', 'email', 37),
(19, 'Subject', '<p>Body</p>', 'fi', 'email', 38),
(19, 'Subject', '<p>Body</p>', 'fo', 'email', 39),
(19, 'Subject', '<p>Body</p>', 'fy', 'email', 40),
(19, 'Subject', '<p>Body</p>', 'ga', 'email', 41),
(19, 'Subject', '<p>Body</p>', 'gd', 'email', 42),
(19, 'Subject', '<p>Body</p>', 'ay', 'email', 44),
(19, 'Subject', '<p>Body</p>', 'bi', 'email', 45),
(19, 'Subject', '<p>Body</p>', 'bg', 'email', 46),
(19, 'Subject', '<p>Body</p>', 'zu', 'email', 47),
(19, 'Subject', '<p>Body</p>', 'am', 'email', 48),
(19, 'Your Invoice # {invoice_reference_no} from {company_name} has been created.', '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no} is for Quotation #{order_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email.</p><p><strong>Billing address</strong></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br>&nbsp;</p><p><br>&nbsp;</p><p><strong>Quotation summary</strong><br>&nbsp;</p><p>{invoice_summery}<br>&nbsp;</p><p>Regards,</p><p>{company_name}<br>&nbsp;</p>', 'en', 'sms', 1),
(19, 'Subject', '<p>Body</p>', 'ar', 'sms', 2),
(19, 'Subject', '<p>Body</p>', 'pt', 'sms', 4),
(19, 'Subject', '<p>Body</p>', 'fr', 'sms', 12),
(19, 'Subject', '<p>Body</p>', 'tr', 'sms', 14),
(19, 'Subject', '<p>Body</p>', 'rs', 'sms', 15),
(19, 'Subject', '<p>Body</p>', 'hn', 'sms', 17),
(19, 'Subject', '<p>Body</p>', 'ur', 'sms', 18),
(19, 'Subject', '<p>Body</p>', 'bn', 'sms', 19),
(19, 'Subject', '<p>Body</p>', 'aa', 'sms', 20),
(19, 'Subject', '<p>Body</p>', 'ab', 'sms', 22),
(19, 'Subject', '<p>Body</p>', 'af', 'sms', 23),
(19, 'Subject', '<p>Body</p>', 'az', 'sms', 25),
(19, 'Subject', '<p>Body</p>', 'br', 'sms', 28),
(19, 'Subject', '<p>Body</p>', 'co', 'sms', 29),
(19, 'Subject', '<p>Body</p>', 'cy', 'sms', 30),
(19, 'Subject', '<p>Body</p>', 'de', 'sms', 31),
(19, 'Subject', '<p>Body</p>', 'dz', 'sms', 32),
(19, 'Subject', '<p>Body</p>', 'es', 'sms', 34),
(19, 'Subject', '<p>Body</p>', 'et', 'sms', 35),
(19, 'Subject', '<p>Body</p>', 'eu', 'sms', 36),
(19, 'Subject', '<p>Body</p>', 'fa', 'sms', 37),
(19, 'Subject', '<p>Body</p>', 'fi', 'sms', 38),
(19, 'Subject', '<p>Body</p>', 'fo', 'sms', 39),
(19, 'Subject', '<p>Body</p>', 'fy', 'sms', 40),
(19, 'Subject', '<p>Body</p>', 'ga', 'sms', 41),
(19, 'Subject', '<p>Body</p>', 'gd', 'sms', 42),
(19, 'Subject', '<p>Body</p>', 'ay', 'sms', 44),
(19, 'Subject', '<p>Body</p>', 'bi', 'sms', 45),
(19, 'Subject', '<p>Body</p>', 'bg', 'sms', 46),
(19, 'Subject', '<p>Body</p>', 'zu', 'sms', 47),
(19, 'Subject', '<p>Body</p>', 'am', 'sms', 48);

DELETE FROM `permissions` WHERE `permissions`.`name` = 'manage_relationship';
DELETE FROM `permissions` WHERE `permissions`.`name` = 'manage_invoice_email_template';
DELETE FROM `permissions` WHERE `permissions`.`name` = 'manage_payment_email_template';
DELETE FROM `permissions` WHERE `permissions`.`name` = 'manage_settings';


INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES
('manage_pos', 'Manage POs', 'Manage Point of Sale', 'pos', NULL, NULL),
('url_shortner', 'URL Shortner', 'Manage url shortner', 'url_shortner', NULL, NULL),
('manage_sms_template', 'Manage SMS Template', 'manage sms template', 'manage_sms_template', NULL, NULL);

INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('215', '1')

ALTER TABLE `payment_methods` ADD `approve` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `consumer_secret`;

ALTER TABLE `customer_transactions` ADD `account_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT ' account_id refers accounts table id' AFTER `user_id`;

INSERT INTO `task_statuses` (`id`, `name`, `status_order`, `color`) VALUES ('7', 'Re-open', '7', '#F22012');

/* Update object_type column in sale_orders and files table*/
UPDATE `sale_orders` SET `order_type` = "Direct Order" WHERE `order_type` = "directOrder";
UPDATE `sale_orders` SET `order_type` = "Direct Order" WHERE `order_type` = "indirectOrder";
UPDATE `files` SET `object_type`= "Direct Order" WHERE `object_type`="Indirect Order";
ALTER TABLE `sale_orders` CHANGE `order_type` `order_type` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
UPDATE `sale_orders` SET `order_type` = "Indirect Invoice" WHERE `order_type` = "indirectInvoice";
UPDATE `sale_orders` SET `order_type` = "Direct Invoice" WHERE `order_type` = "directInvoice";
/* Update object_type column in sale_orders and files table*/

-- 01-04-2021
-- Update token column default value null in password_resets table
ALTER TABLE `password_resets` CHANGE `token` `token` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
--
-- Added some language
INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `status`, `is_default`, `direction`) VALUES (NULL, 'Spanish', 'es', NULL, 'Active', '0', 'ltr');
INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `status`, `is_default`, `direction`) VALUES (NULL, 'Russian', 'ru', NULL, 'Active', '0', 'ltr');
INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `status`, `is_default`, `direction`) VALUES (NULL, 'Turkish', 'tr', NULL, 'Active', '0', 'ltr');
INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `status`, `is_default`, `direction`) VALUES (NULL, 'Chinese', 'zh', NULL, 'Active', '0', 'ltr');
INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `status`, `is_default`, `direction`) VALUES (NULL, 'Portuguese', 'pt', NULL, 'Active', '0', 'ltr');
-- Update email template
UPDATE `email_templates` SET `body` = '<!DOCTYPE html>\r\n <html>\r\n <head>\r\n <meta charset=\"utf-8\">\r\n <meta http-equiv=\"x-ua-compatible\" content=\"ie=edge\">\r\n <title>Activation Link</title>\r\n <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n <style type=\"text/css\">\r\n /**\r\n * Google webfonts. Recommended to include the .woff version for cross-client compatibility.\r\n */\r\n @media screen {\r\n @font-face {\r\n font-family: Source Sans Pro;\r\n font-style: normal;\r\n font-weight: 400;\r\n src: local(\"Source Sans Pro Regular\"), local(\"SourceSansPro-Regular\"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format(\"woff\");\r\n }\r\n @font-face {\r\n font-family: Source Sans Pro;\r\n font-style: normal;\r\n font-weight: 700;\r\n src: local(\"Source Sans Pro Bold\"), local(\"SourceSansPro-Bold\"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format(\"woff\");\r\n }\r\n }\r\n /**\r\n * Avoid browser level font resizing.\r\n * 1. Windows Mobile\r\n * 2. iOS / OSX\r\n */\r\n body,\r\n table,\r\n td,\r\n a {\r\n -ms-text-size-adjust: 100%; /* 1 */\r\n -webkit-text-size-adjust: 100%; /* 2 */\r\n }\r\n /**\r\n * Remove extra space added to tables and cells in Outlook.\r\n */\r\n table,\r\n td {\r\n mso-table-rspace: 0pt;\r\n mso-table-lspace: 0pt;\r\n }\r\n /**\r\n * Better fluid images in Internet Explorer.\r\n */\r\n img {\r\n -ms-interpolation-mode: bicubic;\r\n }\r\n /**\r\n * Remove blue links for iOS devices.\r\n */\r\n a[x-apple-data-detectors] {\r\n font-family: inherit !important;\r\n font-size: inherit !important;\r\n font-weight: inherit !important;\r\n line-height: inherit !important;\r\n color: inherit !important;\r\n text-decoration: none !important;\r\n }\r\n /**\r\n * Fix centering issues in Android 4.4.\r\n */\r\n div[style*=\"margin: 16px 0;\"] {\r\n margin: 0 !important;\r\n }\r\n body {\r\n width: 100% !important;\r\n height: 100% !important;\r\n padding: 0 !important;\r\n margin: 0 !important;\r\n }\r\n /**\r\n * Collapse table borders to avoid space between cells.\r\n */\r\n table {\r\n border-collapse: collapse !important;\r\n }\r\n a {\r\n color: #1a82e2;\r\n }\r\n img {\r\n height: auto;\r\n line-height: 100%;\r\n text-decoration: none;\r\n border: 0;\r\n outline: none;\r\n }\r\n </style>\r\n </head>\r\n <body style=\"background-color: #e9ecef;\">\r\n <div class=\"preheader\" style=\"display: none; color:black; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;\">\r\n A preheader is the short summary text that follows the subject line when an email is viewed in the inbox.\r\n </div>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"center\" valign=\"top\" style=\"padding: 36px 24px;\">\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 36px 24px 0; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;\">\r\n <h1 style=\"margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; text-align: center; color: cornflowerblue;\">Activate Your Account</h1>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;\">\r\n <p style=\"margin: 0; color:black;\">Dear {customer_name},</p>\r\n <p style=\" color:black;\">To activate your account, go to the following the button: </p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#ffffff\" style=\"padding: 12px;\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#1a82e2\" style=\"border-radius: 6px;\">\r\n <a href=\"{activation_link}\" target=\"_blank\" style=\"display: inline-block; padding: 16px 36px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;\">Click here</a>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;\">\r\n <p style=\"margin: 0; color:black;\">If that does not work, click on the following link in your browser:</p>\r\n <p style=\"margin: 0;\"><a href=\"{activation_link}\" target=\"_blank\">{activation_link}</a></p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf\">\r\n <p style=\"margin: 0; color:black;\">Thanks & Regards,<br> {company_name}</p>\r\n <p style=\" color:black;\">\r\n \r\n </p>\r\n <br />\r\n <hr>\r\n <p style=\"text-align: center;font-size:12px\">©{company_name}, all rights reserved</p>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n </table>\r\n </body>\r\n </html>' WHERE `email_templates`.`id` = 31
UPDATE `email_templates` SET `body` = '<!DOCTYPE html>\r\n <html>\r\n <head>\r\n <meta charset=\"utf-8\">\r\n <meta http-equiv=\"x-ua-compatible\" content=\"ie=edge\">\r\n <title>Password Reset</title>\r\n <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n <style type=\"text/css\">\r\n /**\r\n * Google webfonts. Recommended to include the .woff version for cross-client compatibility.\r\n */\r\n @media screen {\r\n @font-face {\r\n font-family: Source Sans Pro;\r\n font-style: normal;\r\n font-weight: 400;\r\n src: local(\"Source Sans Pro Regular\"), local(\"SourceSansPro-Regular\"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format(\"woff\");\r\n }\r\n @font-face {\r\n font-family: Source Sans Pro;\r\n font-style: normal;\r\n font-weight: 700;\r\n src: local(\"Source Sans Pro Bold\"), local(\"SourceSansPro-Bold\"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format(\"woff\");\r\n }\r\n }\r\n /**\r\n * Avoid browser level font resizing.\r\n * 1. Windows Mobile\r\n * 2. iOS / OSX\r\n */\r\n body,\r\n table,\r\n td,\r\n a {\r\n -ms-text-size-adjust: 100%; /* 1 */\r\n -webkit-text-size-adjust: 100%; /* 2 */\r\n }\r\n /**\r\n * Remove extra space added to tables and cells in Outlook.\r\n */\r\n table,\r\n td {\r\n mso-table-rspace: 0pt;\r\n mso-table-lspace: 0pt;\r\n }\r\n /**\r\n * Better fluid images in Internet Explorer.\r\n */\r\n img {\r\n -ms-interpolation-mode: bicubic;\r\n }\r\n /**\r\n * Remove blue links for iOS devices.\r\n */\r\n a[x-apple-data-detectors] {\r\n font-family: inherit !important;\r\n font-size: inherit !important;\r\n font-weight: inherit !important;\r\n line-height: inherit !important;\r\n color: inherit !important;\r\n text-decoration: none !important;\r\n }\r\n /**\r\n * Fix centering issues in Android 4.4.\r\n */\r\n div[style*=\"margin: 16px 0;\"] {\r\n margin: 0 !important;\r\n }\r\n body {\r\n width: 100% !important;\r\n height: 100% !important;\r\n padding: 0 !important;\r\n margin: 0 !important;\r\n }\r\n /**\r\n * Collapse table borders to avoid space between cells.\r\n */\r\n table {\r\n border-collapse: collapse !important;\r\n }\r\n a {\r\n color: #1a82e2;\r\n }\r\n img {\r\n height: auto;\r\n line-height: 100%;\r\n text-decoration: none;\r\n border: 0;\r\n outline: none;\r\n }\r\n </style>\r\n\r\n </head>\r\n <body style=\"background-color: #e9ecef;\">\r\n <div class=\"preheader\" style=\"display: none; color:black; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;\">\r\n A preheader is the short summary text that follows the subject line when an email is viewed in the inbox.\r\n </div>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"center\" valign=\"top\" style=\"padding: 36px 24px;\">\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 36px 24px 0; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;\">\r\n <h1 style=\"margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; text-align: center; color: cornflowerblue;\">Reset Your Password</h1>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;\">\r\n <p style=\"margin: 0; color:black;\">Dear {user_name},</p>\r\n <p style=\" color:black;\">Someone has asked to reset the password of your {company_name} account. If you did not request a password reset, you can disregard this email. No changes have been made to your account.</p>\r\n <p style=\" color:black;\">To reset your password, go to the following the button: </p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#ffffff\" style=\"padding: 12px;\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#1a82e2\" style=\"border-radius: 6px;\">\r\n <a href=\"{password_reset_url}\" target=\"_blank\" style=\"display: inline-block; padding: 16px 36px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;\">Click here</a>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;\">\r\n <p style=\"margin: 0; color:black;\">If that does not work, click on the following link in your browser:</p>\r\n <p style=\"margin: 0;\"><a href=\"{password_reset_url}\" target=\"_blank\">{password_reset_url}</a></p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf\">\r\n <p style=\"margin: 0; color:black;\">Thanks & Regards,<br> {company_name}</p>\r\n <br />\r\n <hr>\r\n <p style=\"text-align: center;font-size:12px\">©{company_name}, all rights reserved</p>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n </table>\r\n </body>\r\n </html>' WHERE `email_templates`.`id` = 21
UPDATE `email_templates` SET `body` = '<!DOCTYPE html>\r\n <html>\r\n <head>\r\n <meta charset=\"utf-8\">\r\n <meta http-equiv=\"x-ua-compatible\" content=\"ie=edge\">\r\n <title>Password Reset</title>\r\n <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n <style type=\"text/css\">\r\n /**\r\n * Google webfonts. Recommended to include the .woff version for cross-client compatibility.\r\n */\r\n @media screen {\r\n @font-face {\r\n font-family: Source Sans Pro;\r\n font-style: normal;\r\n font-weight: 400;\r\n src: local(\"Source Sans Pro Regular\"), local(\"SourceSansPro-Regular\"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format(\"woff\");\r\n }\r\n @font-face {\r\n font-family: Source Sans Pro;\r\n font-style: normal;\r\n font-weight: 700;\r\n src: local(\"Source Sans Pro Bold\"), local(\"SourceSansPro-Bold\"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format(\"woff\");\r\n }\r\n }\r\n /**\r\n * Avoid browser level font resizing.\r\n * 1. Windows Mobile\r\n * 2. iOS / OSX\r\n */\r\n body,\r\n table,\r\n td,\r\n a {\r\n -ms-text-size-adjust: 100%; /* 1 */\r\n -webkit-text-size-adjust: 100%; /* 2 */\r\n }\r\n /**\r\n * Remove extra space added to tables and cells in Outlook.\r\n */\r\n table,\r\n td {\r\n mso-table-rspace: 0pt;\r\n mso-table-lspace: 0pt;\r\n }\r\n /**\r\n * Better fluid images in Internet Explorer.\r\n */\r\n img {\r\n -ms-interpolation-mode: bicubic;\r\n }\r\n /**\r\n * Remove blue links for iOS devices.\r\n */\r\n a[x-apple-data-detectors] {\r\n font-family: inherit !important;\r\n font-size: inherit !important;\r\n font-weight: inherit !important;\r\n line-height: inherit !important;\r\n color: inherit !important;\r\n text-decoration: none !important;\r\n }\r\n /**\r\n * Fix centering issues in Android 4.4.\r\n */\r\n div[style*=\"margin: 16px 0;\"] {\r\n margin: 0 !important;\r\n }\r\n body {\r\n width: 100% !important;\r\n height: 100% !important;\r\n padding: 0 !important;\r\n margin: 0 !important;\r\n }\r\n /**\r\n * Collapse table borders to avoid space between cells.\r\n */\r\n table {\r\n border-collapse: collapse !important;\r\n }\r\n a {\r\n color: #1a82e2;\r\n }\r\n img {\r\n height: auto;\r\n line-height: 100%;\r\n text-decoration: none;\r\n border: 0;\r\n outline: none;\r\n }\r\n </style>\r\n\r\n </head>\r\n <body style=\"background-color: #e9ecef;\">\r\n <div class=\"preheader\" style=\"display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;\">\r\n </div>\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"center\" valign=\"top\" style=\"padding: 36px 24px;\">\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 36px 24px 0; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;\">\r\n <h1 style=\"margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; text-align: center; color: cornflowerblue;\">Updated Your Password</h1>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"center\" bgcolor=\"#e9ecef\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width: 600px;\">\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;\">\r\n <p style=\"margin: 0;\">Hello {user_name},</p>\r\n <p>You requested to update your password. Your new password has been set. You can check the update going through the <a href=\"{company_url}\">portal</a>.</p>\r\n <h5 style=\"margin-top:10px; margin-bottom:0px; \"> <u>Credentials</u>: </h5>\r\n <div style=\"line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;\">\r\n <p style=\"font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;\"><span style=\"font-size: 15px;\">User ID: <span style=\"color: #555555;\"><strong> {user_id}</strong></span></span></p>\r\n <p style=\"font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;\"><span style=\"font-size: 15px;\">Password: <span style=\"color: #555555;\"><strong> {user_pass}</strong></span></span></p>\r\n </div>\r\n <p style=\"margin-top:10px;\">Was it you or someone else? If it was not you, please inform us promptly.</p>\r\n </td>\r\n </tr>\r\n <tr>\r\n <td align=\"left\" bgcolor=\"#ffffff\" style=\"padding: 0 24px 24px 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf\">\r\n <p style=\"margin: 0;\">Thanks & Regards,<br> {company_name}</p>\r\n <br/>\r\n <hr>\r\n <p style=\"text-align: center;\">©{company_name}, all rights reserved</p>\r\n </td>\r\n </tr>\r\n </table>\r\n </td>\r\n </tr>\r\n </table>\r\n </body>\r\n </html>' WHERE `email_templates`.`id` = 20

--create group table
CREATE TABLE `groups`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`        varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci,
    `status`      varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY           `groups_name_index` (`name`),
    KEY           `groups_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--Group
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('manage_group', 'Manage Group', 'Manage Group', 'Manage Group', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('add_group', 'Add Group', 'Add Group', 'Add Group', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('edit_group', 'Edit Group', 'Edit Group', 'Edit Group', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('delete_group', 'Delete Group', 'Delete Group', 'Delete Group', '2021-06-05 16:10:21');

--create knowledge base table
CREATE TABLE `knowledge_bases`
(
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `group_id`     int(10) unsigned NOT NULL,
    `subject`      varchar(290) COLLATE utf8mb4_unicode_ci NOT NULL,
    `slug`         varchar(290) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description`  text COLLATE utf8mb4_unicode_ci,
    `status`       varchar(16) COLLATE utf8mb4_unicode_ci  NOT NULL,
    `comments`     varchar(8) COLLATE utf8mb4_unicode_ci  NOT NULL,
    `publish_date` date DEFAULT NULL,
    `created_at`   timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `knowledge_bases_slug_unique` (`slug`),
    KEY            `knowledge_bases_group_id_foreign_idx` (`group_id`),
    KEY            `knowledge_bases_subject_index` (`subject`),
    KEY            `knowledge_bases_status_index` (`status`),
    KEY            `knowledge_bases_comments_index` (`comments`),
    KEY            `knowledge_bases_publish_date_index` (`publish_date`),
    CONSTRAINT `knowledge_bases_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--knowledge base
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('manage_knowledge_base', 'Manage Knowledge Base', 'Manage Knowledge Base', 'Manage Knowledge Base', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('add_knowledge_base', 'Add Knowledge Base', 'Add Knowledge Base', 'Add Knowledge Base', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('edit_knowledge_base', 'Edit Knowledge Base', 'Edit Knowledge Base', 'Edit Knowledge Base', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('delete_knowledge_base', 'Delete Knowledge Base', 'Delete Knowledge Base', 'Delete Knowledge Base', '2021-06-05 16:10:21');

--preference
INSERT INTO `preferences` (`category`, `field`, `value`) VALUES ('preference', 'facebook_comments', 'enable');

/*Supplier Column name change*/
ALTER TABLE `suppliers` CHANGE `address` `street` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `suppliers` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `suppliers` CHANGE `city` `city` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
/*End Supplier Column name change*/


-- Permission Role
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('221', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('222', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('250', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('249', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('220', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('219', '1');
-- End Permission Role

-- Stock Moves
DELETE FROM `stock_moves` WHERE `item_id` not in (1,2);
-- End Stock Moves

-- Permission
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'manage_theme_preference', 'Manage Theme Preference', 'Manage Theme Preference', 'Theme Preferencee', '2021-02-27 09:32:11', '2021-02-27 09:32:11');
-- End Permission

-- Permission Role
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('252', '1');
-- End Permission Role

-- Permission
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'own_timesheet', 'View Own Timesheet', 'View Own Timesheet', 'Timesheet', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'delete_timesheet', 'Delete Timesheet', 'Delete Timesheet', 'Timesheet', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'edit_timesheet', 'Edit Timesheet', 'Edit Timesheet', 'Timesheet', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'add_timesheet', 'Add Timesheet', 'Add Timesheet', 'Timesheet', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'manage_timesheet', 'Manage Timesheet', 'Manage Timesheet', 'Timesheet', NULL, NULL);
-- End Permission

-- Permission Role
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('253', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('254', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('255', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('256', '1');
INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('257', '1');
-- End Permission Role


-- Captcha
CREATE TABLE IF NOT EXISTS `captcha_configurations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_verify_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plugin_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `captcha_configurations` (`id`, `site_key`, `secret_key`, `site_verify_url`, `plugin_url`) VALUES (NULL, '6Lf7szsbAAAAAHo_pm1VCCnt6MxI9EVF02EnVaSE', '6Lf7szsbAAAAAPF7JewA_hxRffSQmL0AXGUS2vzi', 'https://www.google.com/recaptcha/api/siteverify', 'https://www.google.com/recaptcha/api.js');
-- End Captcha

-- Captcha Permission
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'manage_captcha_setup', 'Manage Captcha Setup', 'Manage Captcha Setup', 'captcha setup', '2021-06-01 15:16:42', '2021-06-09 15:16:42');

INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('253', '1');
-- End Captcha Permission

-- Currency Converter Configuration
CREATE TABLE IF NOT EXISTS `currency_converter_configurations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Currency Converter Permission
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `permission_group`, `created_at`, `updated_at`) VALUES (NULL, 'manage_currency_converter_setup', 'Manage Currency Converter Setup', 'Manage Currency Converter Setup', 'currency converter setup', '2021-06-01 15:16:42', '2021-06-09 15:16:42')

INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES ('239', '1');
-- End Currency Converter Permission

--create canned_links
CREATE TABLE `canned_links`
(
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title`        varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `link`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_by`   int(10) unsigned NOT NULL,
    `created_type` varchar(12) COLLATE utf8mb4_unicode_ci  NOT NULL,
    `created_at`   timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY            `canned_links_link_index` (`link`),
    KEY            `canned_links_created_by_foreign_idx` (`created_by`),
    KEY            `canned_links_created_type_index` (`created_type`),
    KEY            `canned_links_title_index` (`title`),
    CONSTRAINT `canned_links_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--create canned_messages
CREATE TABLE `canned_messages`
(
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title`        varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `message`      text COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_by`   int(10) unsigned NOT NULL,
    `created_type` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at`   timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY            `canned_messages_created_by_foreign_idx` (`created_by`),
    KEY            `canned_messages_created_type_index` (`created_type`),
    KEY            `canned_messages_title_index` (`title`),
    CONSTRAINT `canned_messages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--canned message permission
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`,`created_at`) VALUES ('manage_canned_message', 'Manage Canned Message', 'Manage Canned Message', 'Canned Message','2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('delete_canned_message', 'Delete Canned Message', 'Delete Canned Message', 'Canned Message', '2021-06-05 16:10:21');
 --canned link
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('manage_canned_link', 'Manage Canned Link', 'Manage Canned Link', 'Canned Link', '2021-06-05 16:10:21');
INSERT INTO `permissions` (`name`, `display_name`, `description`, `permission_group`, `created_at`) VALUES ('delete_canned_link', 'Delete Canned Link', 'Delete Canned Link', 'Canned Link', '2021-06-05 16:10:21');

-- Currency Converter Api
ALTER TABLE `currency_converter_configurations` CHANGE `api_key` `api_key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;