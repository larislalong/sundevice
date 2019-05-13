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

/**
 * In some cases you should not drop the tables.
 * Maybe the merchant will just try to reset the module
 * but does not want to loose all of the data associated to the module.
 */

$sql = array();
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_secondary_menu_lang;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_main_menu_lang;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_main_menu_shop;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_html_content_lang;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_html_content;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_css_property_menu;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_selectable_value;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_css_property;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_default_style;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_menu_style;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_icon;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_secondary_menu;';
$sql[] = 'drop table if exists ' . _DB_PREFIX_ . 'menupro_main_menu;';
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
