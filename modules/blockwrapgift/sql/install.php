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
$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'bwg_wrap_gift
(
   id_bwg_wrap_gift int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   position int(11) UNSIGNED,
   price               decimal(20,6),
   image                 varchar(254),
   active               bool,
   primary key (id_bwg_wrap_gift)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'bwg_wrap_gift_product_cart
(
   id_bwg_wrap_gift int(11) UNSIGNED NOT NULL,
   id_cart int(11) UNSIGNED NOT NULL,
   id_product int(11) UNSIGNED NOT NULL,
   id_product_attribute int(11) UNSIGNED NOT NULL,
   primary key (id_bwg_wrap_gift, id_cart, id_product, id_product_attribute)
   
   /*constraint FK_bwg_p_c_bwg_wrap_gift foreign key (id_bwg_wrap_gift)
	references ' . _DB_PREFIX_ . 'bwg_wrap_gift (id_bwg_wrap_gift) on delete cascade on update cascade,
	constraint FK_bwg_p_c_product foreign key (id_product)
	references ' . _DB_PREFIX_ . 'product (id_product) on delete cascade on update cascade,
	constraint FK_bwg_p_c_cart foreign key (id_cart)
	references ' . _DB_PREFIX_ . 'cart (id_cart) on delete cascade on update cascade*/
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'bwg_wrap_gift_lang
(
   id_bwg_wrap_gift int(11) UNSIGNED NOT NULL,
   id_lang int(11) UNSIGNED NOT NULL,
   name                 varchar(254),
   description                 text,
   primary key (id_bwg_wrap_gift, id_lang),
   constraint FK_bwg_wrap_gift_lang_bwg_wrap_gift foreign key (id_bwg_wrap_gift)
	references ' . _DB_PREFIX_ . 'bwg_wrap_gift (id_bwg_wrap_gift) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

foreach ($sql as $key => $query) {
    if (Db::getInstance()->execute($query) == false) {
		return false;
	}
}
