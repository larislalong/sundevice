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
$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ibca_attribute_image
(
   id_product int(11) UNSIGNED NOT NULL,
   id_image int(11) UNSIGNED NOT NULL,
   id_attribute int(11) UNSIGNED NOT NULL,
   primary key (id_product, id_attribute, id_image),
   constraint FK_ibca_attribute_image_product foreign key (id_product)
	references ' . _DB_PREFIX_ . 'product (id_product) on delete cascade on update cascade,
   constraint FK_ibca_attribute_image_image foreign key (id_image)
	references ' . _DB_PREFIX_ . 'image (id_image) on delete cascade on update cascade,
   constraint FK_ibca_attribute_image_attribute foreign key (id_attribute)
	references ' . _DB_PREFIX_ . 'attribute (id_attribute) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_attribute_image ADD COLUMN ibca_id_product int(11) DEFAULT "0" NULL;';
$sql[] = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_attribute_image ADD COLUMN ibca_id_attribute int(11) DEFAULT "0" NULL;';

foreach ($sql as $key => $query) {
    if (Db::getInstance()->execute($query) == false) {
		return false;
	}
}
