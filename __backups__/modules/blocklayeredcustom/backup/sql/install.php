<?php
/**
 * 2007-2017 PrestaShop
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
 *  @copyright 2007-2017 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();
$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'blc_filter_block
(
   id_blc_filter_block int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   block_type int(11) UNSIGNED ,
   filter_type int(11) UNSIGNED ,
   position int(11) UNSIGNED ,
   multiple               bool,
   active               bool,
   primary key (id_blc_filter_block)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'blc_attribute_group
(
   id_blc_attribute_group int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_blc_filter_block int(11) UNSIGNED NOT NULL,
   id_attribute_group int(11) UNSIGNED NOT NULL,
   primary key (id_blc_attribute_group),
   constraint FK_blc_attribute_group_id_blc_filter_block_blc_filter_block foreign key (id_blc_filter_block)
	references ' . _DB_PREFIX_ . 'blc_filter_block (id_blc_filter_block) on delete cascade on update cascade,
   constraint FK_blc_attribute_group_id_attribute_group_attribute_group foreign key (id_attribute_group)
	references ' . _DB_PREFIX_ . 'attribute_group (id_attribute_group) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'blc_product_index
(
   id_product int(11) UNSIGNED NOT NULL,
   is_up_to_date               bool,
   primary key (id_product),
   constraint FK_blc_product_index_id_product_product foreign key (id_product)
	references ' . _DB_PREFIX_ . 'product (id_product) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'blc_product_attribute_price
(
	id_product int(11) UNSIGNED NOT NULL,
	id_product_attribute int(11) UNSIGNED NOT NULL,
	id_shop int(11) UNSIGNED NOT NULL,
	id_currency int(11) UNSIGNED NOT NULL,
	price               decimal(20,6),
   primary key (id_product_attribute, id_shop, id_currency),
   constraint FK_blc_product_attribute_price_product foreign key (id_product)
	references ' . _DB_PREFIX_ . 'product (id_product) on delete cascade on update cascade,
   constraint FK_blc_product_attribute_price_product_attribute foreign key (id_product_attribute)
	references ' . _DB_PREFIX_ . 'product_attribute (id_product_attribute) on delete cascade on update cascade,
   constraint FK_blc_product_attribute_price_shop foreign key (id_shop)
	references ' . _DB_PREFIX_ . 'shop (id_shop) on delete cascade on update cascade,
	constraint FK_blc_product_attribute_price_currency foreign key (id_currency)
	references ' . _DB_PREFIX_ . 'currency (id_currency) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

foreach ($sql as $key => $query) {
    if (Db::getInstance()->execute($query) == false) {
		return false;
	}
}
