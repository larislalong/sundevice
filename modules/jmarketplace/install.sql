CREATE TABLE IF NOT EXISTS `PREFIX_seller` (
    `id_seller` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_customer` int(10) unsigned NOT NULL,
    `id_shop` int(10) unsigned NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `name` varchar(128) character set utf8 NOT NULL,
    `email` varchar(128) NOT NULL,
    `link_rewrite` varchar(128) character set utf8 NOT NULL,
	`meta_description` VARCHAR(255) NULL DEFAULT NULL,
	`meta_keywords` VARCHAR(255) NULL DEFAULT NULL,
	`meta_title` VARCHAR(128) NULL DEFAULT NULL,
    `shop` varchar(128) character set utf8 NOT NULL,
    `cif` varchar(32) DEFAULT NULL,
    `phone` varchar(32) DEFAULT NULL,
    `fax` varchar(32) DEFAULT NULL,
    `address` text,  
    `country` varchar(75) DEFAULT NULL,
    `state` varchar(75) DEFAULT NULL,
    `city` varchar(75) DEFAULT NULL,
    `postcode` varchar(12) DEFAULT NULL,
    `description` text,
    `active` int(2) unsigned NOT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id_seller`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_product` (
    `id_seller_product` INT(10) UNSIGNED NOT NULL,
    `id_product` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_seller_product`, `id_product`),
    INDEX `id_product` (`id_product`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_incidence` (
    `id_seller_incidence` int(10) NOT NULL AUTO_INCREMENT,
    `reference` VARCHAR(8) NOT NULL,
    `id_order` int(10) NOT NULL,
    `id_product` int(10) NOT NULL,
    `id_customer` int(10) NOT NULL,
    `id_seller` int(10) NOT NULL,
	`id_employee` int(10) NOT NULL DEFAULT '0',
    `id_shop` int(10) NOT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id_seller_incidence`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_incidence_message` (
    `id_seller_incidence_message` int(10) NOT NULL AUTO_INCREMENT,
    `id_seller_incidence` int(10) NOT NULL,
    `id_customer` int(10) NOT NULL,
    `id_seller` int(10) NOT NULL,
	`id_employee` int(10) NOT NULL DEFAULT '0',
    `description` text,
	`attachment` VARCHAR(128) NULL DEFAULT NULL,
    `readed` int(2) NOT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id_seller_incidence_message`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_payment` (
    `id_seller_payment` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_seller` INT(10) UNSIGNED NOT NULL,
    `payment` varchar(128) character set utf8 NOT NULL,
    `account` varchar(128) character set utf8 NOT NULL,
    `active` int(2) unsigned NOT NULL,
    PRIMARY KEY (`id_seller_payment`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_commission` (
    `id_seller_commission` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_seller` int(10) NOT NULL,
    `id_shop` int(10) NOT NULL,
    `commission` float(10) NOT NULL,
     PRIMARY KEY  (`id_seller_commission`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_commission_history` (
    `id_seller_commission_history` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_order` int(10) NOT NULL,
    `id_product` int(10) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `id_seller` int(10) NOT NULL,
    `id_shop` int(10) NOT NULL,
    `id_currency` int(10) NOT NULL,
    `conversion_rate` DECIMAL(13,6) NOT NULL DEFAULT '1.000000',
    `price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `quantity` int(10) NOT NULL,
    `unit_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `unit_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `id_seller_commission_history_state` int(10) NOT NULL,
    `date_add` DATETIME NOT NULL,
    `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     PRIMARY KEY  (`id_seller_commission_history`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_commission_history_state` (
    `id_seller_commission_history_state` INT(10) NOT NULL AUTO_INCREMENT,
    `reference` varchar(32) character set utf8 NOT NULL,
    `active` int(2) NOT NULL,
    PRIMARY KEY (`id_seller_commission_history_state`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_commission_history_state_lang` (
    `id_seller_commission_history_state` INT(10) NOT NULL,
    `id_lang` INT(10) NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`id_seller_commission_history_state`, `id_lang`),
    KEY `id_lang` (`id_lang`),
    KEY `name` (`name`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_favorite` (
    `id_customer` INT(10) UNSIGNED NOT NULL,
    `id_seller` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_customer`, `id_seller`),
    INDEX `id_seller` (`id_seller`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_category` (
    `id_seller_category` INT(10) NOT NULL AUTO_INCREMENT,
    `id_category` INT(10) UNSIGNED NOT NULL,
    `id_shop` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_seller_category`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_carrier` (
    `id_seller_carrier` int( 10 ) NOT NULL AUTO_INCREMENT ,
    `id_seller` int( 10 ) NOT NULL ,
    `id_carrier` int( 10 ) NOT NULL ,
    PRIMARY KEY ( `id_seller_carrier` )
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_email` (
    `id_seller_email` INT(10) NOT NULL AUTO_INCREMENT,
    `reference` varchar(45) character set utf8 NOT NULL,
    PRIMARY KEY (`id_seller_email`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_email_lang` (
    `id_seller_email` INT(10) NOT NULL,
    `id_lang` INT(10) NOT NULL,
    `subject` VARCHAR(155) NOT NULL,
    `content` text NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY (`id_seller_email`, `id_lang`),
    KEY `id_lang` (`id_lang`),
    KEY `subject` (`subject`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_comment` (
    `id_seller_comment` int(10) unsigned NOT NULL auto_increment,
    `id_seller` int(10) unsigned NOT NULL,
    `id_customer` int(10) unsigned NOT NULL,
    `id_guest` int(10) unsigned NULL,
    `id_product` int(10) NOT NULL,
    `id_order` int(10) NOT NULL,
    `title` varchar(64) NULL,
    `content` text NOT NULL,
    `customer_name` varchar(64) NULL,
    `order_reference` varchar(128) NULL,
    `product_name` varchar(128) NULL,
    `grade` float unsigned NOT NULL,
    `validate` tinyint(1) NOT NULL,
    `deleted` tinyint(1) NOT NULL,
    `date_add` datetime NOT NULL,
    PRIMARY KEY (`id_seller_comment`),
    KEY `id_seller` (`id_seller`),
    KEY `id_customer` (`id_customer`),
    KEY `id_guest` (`id_guest`)
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_comment_criterion` (
    `id_seller_comment_criterion` int(10) unsigned NOT NULL auto_increment,
    `active` tinyint(1) NOT NULL,
    PRIMARY KEY (`id_seller_comment_criterion`)
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_comment_criterion_lang` (
    `id_seller_comment_criterion` INT(11) UNSIGNED NOT NULL ,
    `id_lang` INT(11) UNSIGNED NOT NULL ,
    `name` VARCHAR(64) NOT NULL ,
    PRIMARY KEY ( `id_seller_comment_criterion` , `id_lang` )
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_comment_grade` (
    `id_seller_comment` int(10) unsigned NOT NULL,
    `id_seller_comment_criterion` int(10) unsigned NOT NULL,
    `grade` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id_seller_comment`, `id_seller_comment_criterion`),
    KEY `id_seller_comment_criterion` (`id_seller_comment_criterion`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `PREFIX_seller_comment_criterion` VALUES ('1', '1');

INSERT IGNORE INTO `PREFIX_seller_comment_criterion_lang` (`id_seller_comment_criterion`, `id_lang`, `name`)
  (
    SELECT '1', l.`id_lang`, 'Quality'
    FROM `PREFIX_lang` l
  );
  
CREATE TABLE IF NOT EXISTS `PREFIX_seller_transfer_invoice` (
    `id_seller_transfer_invoice` int( 10 ) NOT NULL AUTO_INCREMENT ,
    `id_seller` int( 10 ) NOT NULL ,
    `id_currency` int(10) NOT NULL,
    `conversion_rate` DECIMAL(13,6) NOT NULL DEFAULT '1.000000',
    `total` float NOT NULL ,
    `payment` varchar(32) NOT NULL,
    `validate` INT(2) NOT NULL,
    `date_add` DATETIME NOT NULL,
    `date_upd` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY ( `id_seller_transfer_invoice` )
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_seller_transfer_commission` (
    `id_seller_transfer_commission` int( 10 ) NOT NULL AUTO_INCREMENT ,
    `id_seller_transfer_invoice` int( 10 ) NOT NULL ,
    `id_seller_commission_history` int( 10 ) NOT NULL ,
    PRIMARY KEY ( `id_seller_transfer_commission` )
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE `PREFIX_seller_order` (
    `id_seller_order` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_shop` INT(11) UNSIGNED NOT NULL DEFAULT '1',
    `id_order` INT(10) UNSIGNED NOT NULL,
    `reference` VARCHAR(9) NULL DEFAULT NULL,
    `id_seller` INT(10) UNSIGNED NOT NULL,
    `id_customer` INT(10) UNSIGNED NOT NULL,
    `id_address_delivery` INT(10) UNSIGNED NULL DEFAULT NULL,
    `current_state` INT(10) UNSIGNED NULL DEFAULT NULL,
    `id_currency` int(10) NOT NULL,
    `conversion_rate` DECIMAL(13,6) NOT NULL DEFAULT '1.000000',
    `total_discounts` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_discounts_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_discounts_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_paid` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_paid_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_paid_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_products` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_products_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_products_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_shipping` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_shipping_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_shipping_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_wrapping` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_wrapping_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_wrapping_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_fixed_commission` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_fixed_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_fixed_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `date_add` DATETIME NOT NULL,
    `date_upd` DATETIME NOT NULL,
    PRIMARY KEY (`id_seller_order`),
    INDEX `id_seller` (`id_seller`),
    INDEX `id_customer` (`id_customer`),
    INDEX `id_order` (`id_order`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE `PREFIX_seller_order_detail` (
    `id_seller_order_detail` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_seller_order` INT(10) UNSIGNED NOT NULL,
    `id_shop` INT(11) UNSIGNED NOT NULL DEFAULT '1',
    `product_id` INT(10) UNSIGNED NOT NULL,
    `product_attribute_id` INT(10) UNSIGNED NOT NULL,
    `id_customization` INT(10) UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_quantity` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `product_price` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `reduction_percent` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
    `reduction_amount` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `reduction_amount_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `reduction_amount_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `group_reduction` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
    `product_quantity_discount` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `product_ean13` VARCHAR(13) NULL DEFAULT NULL,
    `product_isbn` VARCHAR(32) NULL DEFAULT NULL,
    `product_upc` VARCHAR(12) NULL DEFAULT NULL,
    `product_reference` VARCHAR(32) NULL DEFAULT NULL,
    `product_weight` DECIMAL(20,6) NOT NULL,
    `id_tax_rules_group` INT(11) UNSIGNED NULL DEFAULT '0',
    `tax_computation_method` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `tax_name` VARCHAR(16) NOT NULL,
    `tax_rate` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
    `ecotax` DECIMAL(21,6) NOT NULL DEFAULT '0.000000',
    `ecotax_tax_rate` DECIMAL(5,3) NOT NULL DEFAULT '0.000',
    `discount_quantity_applied` TINYINT(1) NOT NULL DEFAULT '0',
    `total_price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `unit_price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `unit_price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_shipping_price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_shipping_price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `unit_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `unit_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    `total_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT '0.000000',
    PRIMARY KEY (`id_seller_order_detail`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE `PREFIX_seller_order_history` (
    `id_seller_order_history` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_seller_order` INT(10) UNSIGNED NOT NULL,
    `id_seller` INT(10) UNSIGNED NOT NULL,
    `id_order_state` INT(10) UNSIGNED NOT NULL,
    `date_add` DATETIME NOT NULL,
    PRIMARY KEY (`id_seller_order_history`)
)