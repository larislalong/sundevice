<?php
/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 3.8.3,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_8_3($module)
{
    //create tables
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_order` (
        `id_seller_order` int(10) unsigned NOT NULL auto_increment,
        `id_shop` INT(11) UNSIGNED NOT NULL DEFAULT 1,
        `id_order` int(10) unsigned NOT NULL,
        `reference` varchar(9) NULL,
        `id_seller` int(10) unsigned NOT NULL,
        `id_customer` int(10) unsigned NOT NULL,
        `id_address_delivery` int(10) unsigned NULL,
        `current_state` int(10) unsigned NULL,
        `total_discounts` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_discounts_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_discounts_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_paid` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_paid_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_paid_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_products` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_products_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_products_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_shipping` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_shipping_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_shipping_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_wrapping` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_wrapping_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_wrapping_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_fixed_commission` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_fixed_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_fixed_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `date_add` DATETIME NOT NULL,
	`date_upd` DATETIME NOT NULL,
        PRIMARY KEY (`id_seller_order`),
        KEY `id_seller` (`id_seller`),
        KEY `id_customer` (`id_customer`),
        KEY `id_order` (`id_order`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_order_detail` (
        `id_seller_order_detail` int(10) unsigned NOT NULL auto_increment,
        `id_seller_order` int(10) unsigned NOT NULL,
        `id_shop` INT(11) UNSIGNED NOT NULL DEFAULT 1,
        `product_id` INT(10) UNSIGNED NOT NULL,
        `product_attribute_id` INT(10) UNSIGNED NOT NULL,
        `id_customization` INT(10) UNSIGNED NOT NULL,
        `product_name` VARCHAR(255) NOT NULL,
        `product_quantity` INT(10) UNSIGNED NOT NULL DEFAULT 0,
        `product_price` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `reduction_percent` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	`reduction_amount` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`reduction_amount_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`reduction_amount_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `group_reduction` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `product_quantity_discount` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `product_ean13` VARCHAR(13) NULL DEFAULT NULL,
	`product_isbn` VARCHAR(32) NULL DEFAULT NULL,
	`product_upc` VARCHAR(12) NULL DEFAULT NULL,
	`product_reference` VARCHAR(32) NULL DEFAULT NULL,
	`product_weight` DECIMAL(20,6) NOT NULL,
        `id_tax_rules_group` INT(11) UNSIGNED NULL DEFAULT 0,
        `tax_computation_method` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	`tax_name` VARCHAR(16) NOT NULL,
	`tax_rate` DECIMAL(10,3) NOT NULL DEFAULT 0.000,
	`ecotax` DECIMAL(21,6) NOT NULL DEFAULT 0.000000,
	`ecotax_tax_rate` DECIMAL(5,3) NOT NULL DEFAULT 0.000,
	`discount_quantity_applied` TINYINT(1) NOT NULL DEFAULT 0,
	`total_price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`unit_price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`unit_price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_shipping_price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
	`total_shipping_price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `unit_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `unit_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        PRIMARY KEY (`id_seller_order_detail`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_order_history` (
        `id_seller_order_history` int(10) UNSIGNED NOT NULL auto_increment,
        `id_seller_order` int(10) UNSIGNED NOT NULL,
        `id_seller` int(10) UNSIGNED NOT NULL,
        `id_order_state` INT(10) UNSIGNED NOT NULL,
        `date_add` DATETIME NOT NULL,
        PRIMARY KEY (`id_seller_order_history`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );

    //history commissions improvements
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_commission` (
        `id_seller_commission` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_seller` INT(10) NOT NULL,
	`id_shop` INT(10) NOT NULL,
	`commission` FLOAT NOT NULL,
	PRIMARY KEY (`id_seller_commission`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_commission_history` (
        `id_seller_commission_history` int(10) unsigned NOT NULL auto_increment,
        `id_order` int(10) unsigned NOT NULL,
        `id_product` int(10) unsigned NOT NULL,
        `product_name` VARCHAR(255) NOT NULL,
        `id_seller` int(10) unsigned NOT NULL,
        `id_shop` int(10) unsigned NOT NULL,
        `price_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `price_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `quantity` INT(10) NOT NULL,
        `unit_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `unit_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_commission_tax_excl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `total_commission_tax_incl` DECIMAL(20,6) NOT NULL DEFAULT 0.000000,
        `id_seller_commission_history_state` INT(10) NOT NULL,
	`date_add` DATETIME NOT NULL,
	`date_upd` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_seller_commission_history`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );

    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_commission_history_state` (
        `id_seller_commission_history_state` INT(10) NOT NULL AUTO_INCREMENT,
	`reference` VARCHAR(32) NOT NULL,
	`active` INT(2) NOT NULL,
	PRIMARY KEY (`id_seller_commission_history_state`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );

    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_commission_history_state_lang` (
        `id_seller_commission_history_state` INT(10) NOT NULL,
	`id_lang` INT(10) NOT NULL,
	`name` VARCHAR(64) NOT NULL,
	PRIMARY KEY (`id_seller_commission_history_state`, `id_lang`),
	INDEX `id_lang` (`id_lang`),
	INDEX `name` (`name`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_transfer_commission` (
        `id_seller_transfer_commission` INT(10) NOT NULL AUTO_INCREMENT,
	`id_seller_transfer_invoice` INT(10) NOT NULL,
	`id_seller_commission_history` INT(10) NOT NULL,
	PRIMARY KEY (`id_seller_transfer_commission`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    $data_commission = array();
    $data_commission_history = array();
    $data_history_state = array();
    $data_history_state_lang = array();
    $data_transfer_commission = array();
    
    $seller_commisions = Db::getInstance()->ExecuteS(
        'SELECT * FROM '._DB_PREFIX_.'seller_commision 
        ORDER BY id_seller_commision ASC'
    );

    if (is_array($seller_commisions) && count($seller_commisions)) {
        foreach ($seller_commisions as $sc) {
            $data_commission[] = array(
                'id_seller' => (int)$sc['id_seller'],
                'commission' => (float)$sc['commision'],
                'id_shop' => (int)$sc['id_shop'],
            );
        }
        
        Db::getInstance()->insert('seller_commission', $data_commission);
    }
    
    $seller_commision_history = Db::getInstance()->ExecuteS(
        'SELECT * FROM '._DB_PREFIX_.'seller_commision_history 
        ORDER BY id_seller_commision_history ASC'
    );
    
    if (is_array($seller_commision_history) && count($seller_commision_history)) {
        foreach ($seller_commision_history as $sh) {
            //$commission = (float)Db::getInstance()->getValue('SELECT commission FROM '._DB_PREFIX_.'seller_commission WHERE id_seller = '.(int)$sh['id_seller']);

            /*if ($sh['id_product'] != 0) {
                $product = new Product($sh['id_product'], true, Context::getContext()->language->id);

                $price_tax_incl = $product->getPrice(true);
                $price_tax_excl = $product->getPrice(false);
                $unit_commission_tax_incl = (float)($price_tax_incl * $commission) / 100;
                $unit_commission_tax_excl = (float)($price_tax_excl * $commission) / 100;
                $total_commission_tax_incl = (float)(($price_tax_incl * $sh['quantity']) * $commission) / 100;
                $total_commission_tax_excl = (float)(($price_tax_excl * $sh['quantity']) * $commission) / 100;
            }
            else {
                $price_tax_incl = $sh['price'];
                $price_tax_excl = $sh['price'];
                $unit_commission_tax_incl = (float)$price_tax_incl;
                $unit_commission_tax_excl = (float)$price_tax_excl;
                $total_commission_tax_incl = (float)($price_tax_incl * $sh['quantity']);
                $total_commission_tax_excl = (float)($price_tax_excl * $sh['quantity']);
            }*/
            
            $price_tax_incl = $sh['price'];
            $price_tax_excl = $sh['price'];
            $unit_commission_tax_incl = $sh['commision'] / $sh['quantity'];
            $unit_commission_tax_excl = $sh['commision'] / $sh['quantity'];
            $total_commission_tax_incl = $sh['commision'];
            $total_commission_tax_excl = $sh['commision'];

            $data_commission_history[] = array(
                'id_seller_commission_history' => (int)$sh['id_seller_commision_history'],
                'id_order' => (int)$sh['id_order'],
                'id_product' => (int)$sh['id_product'],
                'product_name' => (string)$sh['product_name'],
                'id_seller' => (int)$sh['id_seller'],
                'id_shop' => (int)$sh['id_shop'],
                'price_tax_excl' => (float)$price_tax_excl,
                'price_tax_incl' => (float)$price_tax_incl,
                'quantity' => (int)$sh['quantity'],
                'unit_commission_tax_excl' => (float)$unit_commission_tax_excl,
                'unit_commission_tax_incl' => (float)$unit_commission_tax_incl,
                'total_commission_tax_excl' => (float)$total_commission_tax_excl,
                'total_commission_tax_incl' => (float)$total_commission_tax_incl,
                'id_seller_commission_history_state' => (int)$sh['id_seller_commision_history_state'],
                'date_add' => (string)$sh['date_add'],
                'date_upd' => (string)$sh['date_upd'],
            );
        }

        Db::getInstance()->insert('seller_commission_history', $data_commission_history);
    }
    
    $seller_commision_history_state = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'seller_commision_history_state ORDER BY id_seller_commision_history_state ASC');

    if (is_array($seller_commision_history_state) && count($seller_commision_history_state)) {
        foreach ($seller_commision_history_state as $schs) {
            $data_history_state[] = array(
                'reference' => (string)$schs['reference'],
                'active' => (int)$schs['active'],
            );
        }

        Db::getInstance()->insert('seller_commission_history_state', $data_history_state);
    }
    
    $seller_commision_history_state_lang = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'seller_commision_history_state_lang ORDER BY id_seller_commision_history_state ASC');

    if (is_array($seller_commision_history_state_lang) && count($seller_commision_history_state_lang)) {
        foreach ($seller_commision_history_state_lang as $schsl) {
            $data_history_state_lang[] = array(
                'id_seller_commission_history_state' => (int)$schsl['id_seller_commision_history_state'],
                'id_lang' => (int)$schsl['id_lang'],
                'name' => (string)$schsl['name'],
            );
        }

        Db::getInstance()->insert('seller_commission_history_state_lang', $data_history_state_lang);
    }
    
    $seller_transfer_commision = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'seller_transfer_commision ORDER BY id_seller_transfer_commision ASC');

    if (is_array($seller_transfer_commision) && count($seller_transfer_commision)) {
        foreach ($seller_transfer_commision as $stc) {
            $data_transfer_commission[] = array(
                'id_seller_transfer_invoice' => (int)$stc['id_seller_transfer_invoice'],
                'id_seller_commission_history' => (int)$stc['id_seller_commision_history'],
            );
        }

        Db::getInstance()->insert('seller_transfer_commission', $data_transfer_commission);
    }
    
    //recorrer pedidos
    $orders = Order::getOrdersIdByDate('2015-01-01', date('Y-m-d'));
    if (is_array($orders) && count($orders) > 0) {
        foreach ($orders as $id_order) {
            $query = 'SELECT sch.*
                    FROM '._DB_PREFIX_.'seller_commission_history sch
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = sch.`id_order`)  
                    WHERE sch.id_order = '.(int)$id_order;
            $order_commissions = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            if (is_array($order_commissions) && count($order_commissions) > 0) {
                $order = new Order($id_order);
                $module->createSellerOrders($order);
                Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'seller_order` SET `date_add`= "'.pSQL($order->date_add).'" WHERE id_order = '.(int)$id_order);
                Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'seller_order` SET `date_upd`= "'.pSQL($order->date_upd).'" WHERE id_order = '.(int)$id_order);
            }
        }
    }
    
    /*Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commision`');
    Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commision_history`');
    Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commision_history_state`');
    Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commision_history_state_lang`');
    Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_transfer_commision`');*/
    
    $module->deleteTab('AdminSellerCommisions');
    $module->deleteTab('AdminSellerCommisionsHistory');
    $module->deleteTab('AdminSellerCommisionsHistoryStates');

    $menu_jmarketplace_seller_commissions = array(
        'en' => 'Seller Commissions',
        'es' => 'Comisiones de los vendedores',
        'fr' => 'Commissions des vendeurs',
        'it' => 'Commissioni  dei venditori',
        'de' => 'Provisionen von Anbietern',
        'br' => 'Seller Commissions',
    );

    $module->createTab('AdminSellerCommissions', $menu_jmarketplace_seller_commissions, 'AdminJmarketplace');

    $menu_jmarketplace_seller_commission_history = array(
        'en' => 'Seller Commisions History',
        'es' => 'Historial de comisiones',
        'fr' => 'Historique de commissions',
        'it' => 'Precedenti delle commissioni',
        'de' => 'Geschichte Kommissionen',
        'br' => 'Seller Commisions History',
    );

    $module->createTab('AdminSellerCommissionsHistory', $menu_jmarketplace_seller_commission_history, 'AdminJmarketplace');

    $menu_jmarketplace_seller_commissiom_history_states = array(
        'en' => 'Seller Payment States',
        'es' => 'Estado de los pagos',
        'fr' => 'Etats des paiements',
        'it' => 'Stati dei pagamenti',
        'de' => 'Zahlungsstatus',
        'br' => 'Seller Payment States',
    );

    $module->createTab('AdminSellerCommissionsHistoryStates', $menu_jmarketplace_seller_commissiom_history_states, 'AdminJmarketplace');

    $languages = Language::getLanguages();
    $meta = new Meta();
    $meta->page = 'module-jmarketplace-sellerhistorycommissions';
    $meta->configurable = 1;

    foreach ($languages as $lang) {
        $meta->title[$lang['id_lang']] = 'JA Marketplace - History commissions';
        $meta->description[$lang['id_lang']] = 'History commission.';
        $meta->url_rewrite[$lang['id_lang']] = 'sellerhistorycommissions';
    }

    $meta->save();
    
    if (version_compare(_PS_VERSION_, '1.7', '<')) {
        $id_meta = $meta->id;
        $theme_meta_value = array();

        if ($id_meta) {
            $themes = Theme::getThemes();
            foreach ($themes as $theme) {
                $theme_meta_value[] = array(
                    'id_theme' => (int)$theme->id,
                    'id_meta' => (int)$id_meta,
                    'left_column' => (int)$theme->default_left_column,
                    'right_column' => (int)$theme->default_right_column
                );
            }

            if (count($theme_meta_value) > 0) {
                Db::getInstance()->insert('theme_meta', $theme_meta_value);
            }
        }
    }

    return $module;
}
